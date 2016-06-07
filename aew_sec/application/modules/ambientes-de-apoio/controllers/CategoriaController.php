<?php
class AmbientesDeApoio_CategoriaController extends Sec_Controller_Action
{
    protected $_linkExibicao = '/ambientes-de-apoio/ambientes/categorias/id/';
    protected $_linkListagem = '/ambientes-de-apoio/ambientes/categorias';
    protected $_actionApagar = '/ambientes-de-apoio/categoria/apagar/id/';

    public function init(){
        /* @var $acl Sec_Controller_Action_Helper_Acl */
        $acl = $this->getHelper('Acl');
        $this->view->usuarioLogado = $this->getLoggedUserObject();

        $visitanteAction = array('home');
        $acl->allow(Aew_Model_Bo_UsuarioTipo::VISITANTE, $visitanteAction);
        
        $administradorAction = array('apagar', 'adicionar', 'editar', 'salvar');
        $acl->allow(Aew_Model_Bo_UsuarioTipo::ADMINISTRADOR, $administradorAction);
    }
    /**
     * Redirecionamento para home do ambiente de apoio
     * @return Zend_View
     */
    public function homeAction()
    {
        $this->_redirect('ambientes-de-apoio');
    }
    /**
     * adicionar uma categoria no ambiente d apoio
     * @return Zend_View
     */
    public function adicionarAction()
    {
        $this->setPageTitle('Adicionar Categoria');
    	$form = new AmbientesDeApoio_Form_AdicionarCategoria();
        
        $categoria = new Aew_Model_Bo_AmbienteDeApoioCategoria();
        $usuario = $this->getLoggedUserObject();
	$form->adicionarRestricoes();
	if($this->getRequest()->isPost())
        {
	    $form->isValid($this->getRequest()->getPost());
	}
        $this->view->categoria = $categoria;
        $this->view->ambienteDeApoio = $categoria;
	$this->view->adicionar = $form;
	$this->view->usuario = $usuario;
    }
    /**
     * Editar uma categoria do ambbiente de apoio
     * @return Zend_View
     */
    public function editarAction()
    {
        $this->setPageTitle('Editar Categoria');
        
    	
    	$categoria = new Aew_Model_Bo_AmbienteDeApoioCategoria();
        
    	$id = $this->getRequest()->getParam('id', false);
    	$idAmbienteDeApoioCategoria = $this->getRequest()->getPost('idambientedeapoiocategoria', false);
    	if(false == $id)
        {
    	    if(false == $idAmbienteDeApoioCategoria)
            {
	        $this->flashError('Nenhuma Categoria passada.');
	        $this->_redirect($this->_linkListagem);
    	    } 
            else 
            {
    	        $id = $idAmbienteDeApoioCategoria;
    	    }
    	}
        
        $categoria->setId($id);
        if(!$categoria->selectAutoDados())
        {
    	    $this->flashError('Ambiente de Apoio não encontrado.');
            $this->_redirect('conteudos-digitais');
    	}
        
        $form = new AmbientesDeApoio_Form_AdicionarCategoria();        
        $form->populate($categoria->toArray());
	if($this->getRequest()->isPost())
        {
	    $form->isValid($this->getRequest()->getPost());
	}
        
        $this->view->categoria = $categoria;
	$this->view->editar = $form;
    }
    /**
     * Salva catgoria do ambiente de apoio
     * @return Zend_View
     */
    public function salvarAction()
    {
        $form = new AmbientesDeApoio_Form_AdicionarCategoria();
        
        $categoriaAmbiente = new Aew_Model_Bo_AmbienteDeApoioCategoria();
        
        if($this->isPost())
        {
            if(false == ($this->getPost('idAmbienteDeApoioCategoria') > 0))
            {
                $form->adicionarRestricoes();
            }
            
            if($form->isValid($this->getRequest()->getPost()))
            {
                if($form->getValue('idambientedeapoiocategoria') > 0)
                {
                    $txt = 'editado';
                } 
                else 
                {
                    $txt = 'inserido';
                }
                
                $categoriaAmbiente->exchangeArray($form->getValues());
                if($categoriaAmbiente->save())
                {
                    $categoriaAmbiente->uploadIcone($form);
                    
                    $this->flashMessage("Categoria $txt com sucesso.");
                    $this->_redirect($this->_linkExibicao.$categoriaAmbiente->getId());
                }
            } 
            else 
            {
                if($form->getValue('idambientedeapoiocategoria') > 0)
                {
                    $this->_forward('editar');
                } 
                else 
                {
                    $this->_forward('adicionar');
                }
            }
        } 
        else 
        {
            $this->flashError('Nenhuma informação para salvar.');
            $this->_redirect($this->_linkListagem);
        }
    }
    /**
     * apagar uma categoria do ambiente de apoio
     * @return Zend_View
     */
    public function apagarAction()
    {
        $id = $this->getRequest()->getParam('id', false);
        if (false == $id)
        {
            $this->flashError('Nenhuma registro passado.');
            $this->_redirect($this->_linkListagem);
        }

        $categoria = new Aew_Model_Bo_AmbienteDeApoioCategoria();
        $categoria->setId($id);
        $categoria->selectAutoDados();

        if($categoria->delete())
        {
            $icone = $categoria->getIconeDirectory().DS.$categoria->getId().'.png';
            if(file_exists($icone)):
                unlink($icone);
            endif;
            $this->flashMessage('Categoria apagada com sucesso.');
        } 
        else 
        {
            $this->flashError('Houve um problema ao tentar apagar a categoria.');
        }
        
        $this->_redirect($this->_linkListagem);
    }    
}