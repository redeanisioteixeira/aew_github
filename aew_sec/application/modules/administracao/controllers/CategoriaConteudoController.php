<?php
/**
 * controller responsavel pelas url's referentes as acoes de cadastro das categorias
 */
class Administracao_CategoriaConteudoController extends Sec_Controller_Action
{
    public function init()
    {
        /* Initialize action controller here */
        parent::init();
        $acl = $this->getHelper('Acl');

        $action = array('categorias-canal');
	$acl->allow(Aew_Model_Bo_UsuarioTipo::VISITANTE, $action);

        $action = array('home', 'listar', 'editar', 'exibir', 'adicionar','apagar');
	$acl->allow(Aew_Model_Bo_UsuarioTipo::ADMINISTRADOR, $action);
        
        $this->setLinkListagem('/administracao/categoria-conteudo/listar');
        $this->setLinkExibicao('/administracao/categoria-conteudo/exibir/id/');
        $this->setActionApagar('/administracao/categoria-conteudo/apagar/id/');
        $this->setActionSalvar('/administracao/categoria-conteudo/salvar/id/');
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
     * Lista categorias dos conteúdos digitais
     * @return Zend_View
     */
    public function listarAction()
    {
        $this->setPageTitle('Tipos de Categorias');
        
        $categoriaBo = new Aew_Model_Bo_ConteudoDigitalCategoria();
        $this->view->href = $this->opcoesAcessoConteudo($categoriaBo);
        
        $options = array();
        $options['orderBy'] = array('conteudodigitalcategoria.nomeconteudodigitalcategoria ASC');
        $options['where'] = 'conteudodigitalcategoria.idconteudodigitalcategoriapai IS NULL';
        $this->view->categorias = $categoriaBo->select(0, 0, $options);

        $options = array();
        $options['orderBy'] = array('conteudodigitalcategoria.nomeconteudodigitalcategoria ASC');
        $options['where'] = 'conteudodigitalcategoria.idconteudodigitalcategoriapai IS NOT NULL';
        
        $this->view->categoriasRelacionadas = $categoriaBo->select(0, 0, $options);
        $this->view->isAjax = $this->isAjax();
    }
    /**
     * Exibe a categoria
     * @return Zend_View
     */
    public function exibirAction()
    {
    	$categoriaConteudo = new Aew_Model_Bo_ConteudoDigitalCategoria();
        $id = $this->getParam("id",0);
        
        $categoriaConteudo->setId($id);
        if(!$categoriaConteudo->selectAutoDados())
        {
    	    $this->flashError('Categoria não encontrada');
            $this->_redirect($this->getLinkListagem());
    	}
        
        $this->setPageTitle($categoriaConteudo->getNome());
        
        $this->view->categoria = $categoriaConteudo;
        $this->view->href = $this->opcoesAcessoConteudo($categoriaConteudo);
        
        if($categoriaConteudo->getIdconteudodigitalcategoriapai()):
            $categoriaRelacionada = new Aew_Model_Bo_ConteudoDigitalCategoria();
            $categoriaRelacionada->setId($categoriaConteudo->getIdconteudodigitalcategoriapai());
            $categoriaRelacionada->selectAutoDados();
            $this->view->categoriaRelacionada = $categoriaRelacionada;
        endif;        
    }
    /**
     * Edita uma categoria
     * @return Zend_View
     */
    public function editarAction()
    {
        $this->setPageTitle('Editar Categoria');

    	$categoriaConteudo = new Aew_Model_Bo_ConteudoDigitalCategoria();
        $id = $this->getParam("id",0);
        
        $categoriaConteudo->setId($id);
        if(!$categoriaConteudo->selectAutoDados())
        {
    	    $this->flashError('Categoria não encontrada');
            $this->_redirect($this->getLinkListagem());
    	}

        $form = new Administracao_Form_CategoriaConteudo();        

        $form->setAction('/administracao/categoria-conteudo/editar/id/'.$id);
        $form->populate($categoriaConteudo->toArray());

	if($this->getRequest()->isPost())
        {
	    if($form->isValid($this->getRequest()->getPost()))
            {
                $this->salvarCategoria($form);
            }
	}

        $this->view->editar = $form;
    }
    /**
     * Adiciona uma categoria
     * @return Zend_View
     */
    public function adicionarAction()
    {
        $this->setPageTitle('Adicionar Categoria');
        
        $form = new Administracao_Form_CategoriaConteudo();
        $form->setAction('/administracao/categoria-conteudo/adicionar');
        $form->adicionarRestricoes();
        
        if($this->getRequest()->isPost())
        {
            if($form->isValid($this->getRequest()->getParams()))
            {
                $this->salvarCategoria($form);
            }
        }
        
        $this->view->adicionar = $form;
    }
    /**
     * Salva mudanças
     * @param Sec_Form $form recebe forumulario
     */
    public function salvarCategoria(Sec_Form $form)
    {
        $txt = ($form->getValue('idconteudocategoria') ? 'editado' : 'inserido');

        $categoriaConteudo = new Aew_Model_Bo_ConteudoDigitalCategoria();
        
        $categoriaConteudo->exchangeArray($form->getValues());

        if(!$categoriaConteudo->save())
        {
            $this->flashError('Erro ao salvar dados');
            $this->_redirect($this->getLinkExibicao($categoriaConteudo->getId()));
        }
        else
        {
            $apagar = ($form->getValue('apagar') == 't' ? true : false);

            $categoriaConteudo->uploadIcon($form);
            $categoriaConteudo->uploadVideo($form, $apagar);
            
            $this->flashMessage("Categoria <b>".$categoriaConteudo->getNome()."</b> salva com sucesso ");
            $this->_redirect($this->getLinkListagem());
        }
    }
    /**
     * Apaga uma categoria
     * @return Zend_View
     */
    function apagarAction()
    {
        $form = new Aew_Form_Apagar();
        
        $conteudoCategoria = new Aew_Model_Bo_ConteudoDigitalCategoria();
        
        $id = $this->getRequest()->getParam('id',false);
        $conteudoCategoria->setId($id);
        if (!$conteudoCategoria->selectAutoDados())
        {
            $this->flashError('Nenhuma registro passado.');
            $this->_redirect($this->getLinkListagem());
        }

        $this->setPageTitle($conteudoCategoria->getNome());
        
        $form->setAction($this->getActionApagar($id));
        $form->getElement('mensagem')->setValue('Tem certeza que deseja apagar esta categoria?');
        
        if($this->getRequest()->isPost())
        {
            if($this->getRequest()->getPost('nao'))
            {
                $this->_redirect($this->getLinkExibicao($id));
            }
            
            if($conteudoCategoria->delete())
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
        $this->view->categoria = $conteudoCategoria;
    }
    
    /**
     * Renderiza cannais
     * @return Zend_View
     */
    public function categoriasCanalAction()
    {
        if(!$this->isAjax())
        {
            //$this->_redirect ('');
        }
        
        $this->disableLayout();
        $this->disableRender();
        
        $id = $this->getParam('idcanalcategoria',null);
        $canal = new Aew_Model_Bo_Canal();
        
        $canal->setId($id);
        $categorias = $canal->selectCategoriasConteudo();  
        $categorias = $canal->getAllForSelect('idconteudodigitalcategoria', 'nomeconteudodigitalcategoria',false,'idconteudodigitalcategoriapai',null,$categorias);
        $this->view->objetos = $categorias;
        
        echo $this->renderScript('_componentes/select-opcoes.php');
    }
}