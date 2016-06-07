<?php
/**
 * controler responsavel pelas url's referentes a administracao
 */
class Administracao_FormatoController extends Sec_Controller_Action
{
    public function init()
    {
        /* @var $acl Sec_Controller_Action_Helper_Acl */
        $acl = $this->getHelper('Acl');
        $visitanteAction = array('home', 'exibir', 'listar');
        $acl->allow(Aew_Model_Bo_UsuarioTipo::VISITANTE, $visitanteAction);
        $administradorAction = array('editar', 'apagar', 'adicionar');
        $acl->allow(Aew_Model_Bo_UsuarioTipo::ADMINISTRADOR, $administradorAction);
        $this->setLinkListagem('/administracao/formato/listar');
        $this->setLinkExibicao('/administracao/formato/exibir/id/');
        $this->setActionApagar('/administracao/formato/apagar/id/');
        $this->setActionSalvar('/administracao/formato/salvar/id/');
    }
    /**
     * Redireciona para listar formato
     * @return Zend_View
     */
    public function homeAction()
    {
        $this->_redirect('/administracao/formato/listar');
    }
    /**
     * Lista formatos disponíveis 
     * @return Zend_View
     */
    public function listarAction()
    {
        if($this->isAjax())
        {
            $this->disableLayout();
        }
        
        $this->setPageTitle('Tipos de Formatos');
        
        $pagina = $this->getParam('pagina',1);
    	$limite = 20;
        
        $formatosBo = new Aew_Model_Bo_Formato();

        $opcoes = $this->opcoesAcessoConteudo($formatosBo);
        
        $options = array();
        $options['orderBy'] = array('conteudotipo.nomeconteudotipo ASC', 'formato.nomeformato ASC');
        
        $formatos = $formatosBo->select($limite, $pagina, $options, true);
        $formatos = $formatosBo->getAsPagination($formatos, $pagina, $limite, 5);
        
        $this->view->href = $opcoes;
        $this->view->formatos = $formatos;

    }
    /**
     * Adiciona novo formato pdf, webm, etc
     * @return Zend_View
     */
    public function adicionarAction()
    {
        $this->setPageTitle('Adicionar Formato');
    	$form = new Administracao_Form_Formato();
        $form->setAction('/administracao/formato/adicionar');
        if($this->getRequest()->isPost())
        {
	    if($form->isValid($this->getRequest()->getPost()))
            {
                $this->salvarFormato($form);
            }
	}
        $this->view->adicionar = $form;
    }
    /**
     * Edita formato
     * @return Zend_View
     */
    public function editarAction()
    {
        $this->setPageTitle('Editar Formato');
    	$formato = new Aew_Model_Bo_Formato();
        
        $id = $this->getParam("id",0);
        $formato->setId($id);
        if(!$formato->selectAutoDados())
        {
    	    $this->flashError('Formato não encontrada');
            $this->_redirect($this->getLinkListagem());
    	}
        
        $form = new Administracao_Form_Formato();
        $form->setAction('/administracao/formato/editar/id/'.$id);

	if($this->getRequest()->isPost())
        {
	    if($form->isValid($this->getRequest()->getPost()))
            {
                $this->salvarFormato($form);
            }
	}
        $form->populate($formato->toArray());
        
        $this->view->editar = $form;
    }
    /**
     * Salva mudanças
     * @param type formulario
     * @return type flash message ou rediciona
     */
    public function salvarFormato($form)
    {
        $txt = ($form->getValue('idformato') ? 'editado' : 'inserido');
        
        $formato = new Aew_Model_Bo_Formato();
        
        $formato->exchangeArray($form->getValues());
        if(!$formato->save())
        {
            $this->flashError('Erro ao salvar dados');
            $this->_redirect($this->getLinkExibicao($formato->getId()));
        }
        else 
        {
            $this->flashMessage('Formato '.$txt.' com sucesso');
            $this->_redirect($this->getLinkListagem());
        }
    }
    /**
     * Apaga formato
     * @return Zend_View
     */
    public function apagarAction()
    {
        $form = new Aew_Form_Apagar();
        
        $id = $this->getParam('id', false);
        $formato = new Aew_Model_Bo_Formato();
        $formato->setId($id);
        
        if (!$formato->selectAutoDados()){
            $this->flashError('Nenhum registro encontrado');
            $this->_redirect($this->getLinkListagem());
        }

        $this->setPageTitle('.'.$formato->getNome());
        
        $form->setAction($this->getActionApagar($id));
        $form->getElement('mensagem')->setValue('Tem certeza que deseja apagar este formato?');
        
        if($this->isPost())
        {
            if($this->getPost('nao'))
            {
                $this->_redirect($this->getLinkListagem());
            }
            
            if($formato->delete())
            {
                $this->flashMessage('Registro apagado com sucesso');
                $this->_redirect($this->getLinkListagem());
            } 
            else 
            {
                $this->flashError('Houve um problema ao tentar apagar o registro.');
                $this->_redirect($this->getLinkListagem());
            }
        }

        $this->view->apagar = $form;
        $this->view->formato = $formato;
    }
}