<?php
/**
 * controller para gerenciamento de acoes de edicao de licenca
 */
class Administracao_LicencaController extends Sec_Controller_Action
{
    public function init()
    {
        /* @var $acl Sec_Controller_Action_Helper_Acl */
        $acl = $this->getHelper('Acl');
        $visitanteAction = array('home', 'exibir', 'listar');
        $acl->allow(Aew_Model_Bo_UsuarioTipo::VISITANTE, $visitanteAction);
        $administradorAction = array('editar', 'apagar', 'adicionar', 'componentes-curriculares');
        $acl->allow(Aew_Model_Bo_UsuarioTipo::ADMINISTRADOR, $administradorAction);
        $this->setLinkListagem('/administracao/licenca/listar');
        $this->setLinkExibicao('/administracao/licenca/exibir/id/');
        $this->setActionApagar('/administracao/licenca/apagar/id/');
        $this->setActionSalvar('/administracao/licenca/salvar/id/');
    }
    /**
     * Redireciona para listar
     * @return Zend_View
     */
    public function homeAction()
    {
        $this->_forward('listar');
    }
    /**
     * Lista licenças
     * @return Zend_View 
     */
    public function listarAction()
    {
        
        $this->setPageTitle('Tipos de Licenças');
        $licencasBo = new Aew_Model_Bo_ConteudoLicenca();

        if($this->isAjax()):
            $this->_helper->layout->setLayout('popup/layout');
        else:
            $opcoes = $this->opcoesAcessoConteudo($licencasBo);
            $this->view->href = $opcoes;
	endif;
        
        $options = array();
        $options['orderBy'] = array('conteudolicenca.nomeconteudolicenca ASC');
        $options['where'] = 'conteudolicenca.idconteudolicencapai IS NULL';
        $this->view->licencas = $licencasBo->select(0, 0, $options);
        $options = array();
        $options['orderBy'] = array('conteudolicenca.nomeconteudolicenca ASC');
        $options['where'] = 'conteudolicenca.idconteudolicencapai IS NOT NULL';
        $this->view->licencasRelacionadas = $licencasBo->select(0, 0, $options);
        
        $this->view->isAjax = $this->isAjax();
        
    }
    /**
     * Exibe Licença
     * @return Zend_View
     */
    public function exibirAction()
    {
    	$id = $this->getParam('id', false);
	$licenca = new Aew_Model_Bo_ConteudoLicenca();
        
        $this->view->href = $this->opcoesAcessoConteudo($licenca);
        $licenca->setId($id);
        if(!$licenca->selectAutoDados())
        {
            $this->flashError('Nenhum registro passado');
            $this->_redirect($this->getLinkListagem());
        }
        
        $this->setPageTitle($licenca->getNome());
	$this->view->licenca = $licenca;

        if($licenca->getIdconteudolicencapai()):
            $licencaRelacionada = new Aew_Model_Bo_ConteudoLicenca();
            $licencaRelacionada->setId($licenca->getIdconteudolicencapai());
            $licencaRelacionada->selectAutoDados();
            $this->view->licencaRelacionada = $licencaRelacionada;
        endif;
    }
    /**
     * Adiciona Licença
     * @return Zend_View
     */
    public function adicionarAction()
    {
        $this->setPageTitle('Adicionar Licença');
    	$form = new Administracao_Form_Licenca();
        $form->setAction('/administracao/licenca/adicionar');
        $form->adicionarRestricoes();
	if($this->getRequest()->isPost())
        {
	    if($form->isValid($this->getRequest()->getPost()))
            {
                $this->salvarLicenca($form);
            }
	}
        $this->view->adicionar = $form;
    }
    /**
     * Edita Licença
     * @return Zend_View
     */
    public function editarAction()
    {
        $this->setPageTitle('Editar Licença');
    	$licenca = new Aew_Model_Bo_ConteudoLicenca();
        $id = $this->getParam("id",0);
        $licenca->setId($id);
        if(!$licenca->selectAutoDados())
        {
    	    $this->flashError('Licença não encontrada');
            $this->_redirect($this->getLinkListagem());
    	}
        $form = new Administracao_Form_Licenca();
        $form->setAction('/administracao/licenca/editar/id/'.$id);
        $form->populate($licenca->toArray());        
	if($this->getRequest()->isPost())
        {
	    if($form->isValid($this->getRequest()->getPost()))
            {
                $this->salvarLicenca($form);
            }
	}
        $this->view->editar = $form;
    }
    /**
     * Salva mudanças
     * @param type $form recebe formulario
     * @return type flash message ou redireciona 
     */
    public function salvarLicenca($form)
    {
        $txt = ($form->getValue('idconteudolicenca') ? 'editado' : 'inserido');
        
        $licenca = new Aew_Model_Bo_ConteudoLicenca();
        $licenca->exchangeArray($form->getValues());
        if(!$licenca->save())
        {
            $this->flashError('Erro ao salvar dados');
            $this->_redirect($this->getLinkExibicao($licenca->getId()));
        }
        else 
        {
            $licenca->uploadIcon($form);
            $this->flashMessage('Licença '.$txt.' com sucesso');
            $this->_redirect($this->getLinkListagem());
        }
    }
    /**
     * Apaga Licença
     * @return Zend_View
     */
    public function apagarAction()
    {
        $form = new Aew_Form_Apagar();
        
        $id = $this->getParam('id', false);
        $licenca = new Aew_Model_Bo_ConteudoLicenca();
        $licenca->setId($id);
        
        if (!$licenca->selectAutoDados()){
            $this->flashError('Nenhum registro encontrado');
            $this->_redirect($this->getLinkListagem());
        }

        $this->setPageTitle($licenca->getNome());
        
        $form->setAction($this->getActionApagar($id));
        $form->getElement('mensagem')->setValue('Tem certeza que deseja apagar esta licença?');
        
        if($this->isPost())
        {
            if($this->getPost('nao'))
            {
                $this->_redirect($this->getLinkExibicao($id));
            }
            
            if($licenca->delete())
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
        $this->view->licenca = $licenca;
    }
}