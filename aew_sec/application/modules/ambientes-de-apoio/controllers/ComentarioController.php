<?php

class AmbientesDeApoio_ComentarioController extends Sec_Controller_Action
{
    public function init()
    {
        $acl = $this->getHelper('Acl');
        $visitanteAction = array('logar','listar');
        $acl->allow(Aew_Model_Bo_UsuarioTipo::VISITANTE, $visitanteAction);

        $amigodaescolaAction = array('adicionar','apagar');
        $acl->allow(Aew_Model_Bo_UsuarioTipo::AMIGO_DA_ESCOLA, $amigodaescolaAction);
    }

    /**
     * Pagina de listagem dos comentarios do ambiente de apoio
     */
    public function listarAction()
    {
        if($this->isAjax())
            $this->disableLayout();
        
        $this->listarComentariosAction();
    }
    
    /**
     * listar de comentario do conteudo
     * @return Zend_View
     */
    public function listarComentariosAction()
    {
        $id = $this->getRequest()->getParam('id', false);
	$pagina = $this->getRequest()->getParam('pagina', 1);
        
        $ambienteDeApoio = new Aew_Model_Bo_AmbienteDeApoio();
        $ambienteDeApoio->setId($id);
        $usuario = $this->getLoggedUserObject();
	$comentarioForm = new AmbientesDeApoio_Form_Comentario($usuario);
        
        $comentarios = $ambienteDeApoio->selectComentarios(5,$pagina,null,true); 
        $comentarios = $ambienteDeApoio->getAsPagination($comentarios,$pagina,5);

        $this->view->urlPaginator = $this->view->url(array('module'=> 'ambientes-de-apoio','controller' => 'comentario', 'action' => 'listar','id'=>$ambienteDeApoio->getId()));
        $this->view->idDiv = 'conteudo-comentarios';
        $this->view->comentarios = $comentarios; 
    }
    /**
     * adicionar um comentario 
     * @return Zend_View
     */
    public function adicionarAction()
    {
        if(!$this->isAjax()):
            $this->_redirect('');
        endif;
            
        $this->disableLayout();
        
    	$form = new AmbientesDeApoio_Form_Comentario();
    	$comentarioBo = new Aew_Model_Bo_AmbienteDeApoioComentario();
        $ambienteDeApoio = new Aew_Model_Bo_AmbienteDeApoio();
        $usuario = $this->getLoggedUserObject();
        $comentarioBo->getUsuarioAutor()->setId($usuario->getId());
	if($this->getRequest()->isPost())
        {
	    if($form->isValid($this->getPost()))
            {
                $ambienteDeApoio->exchangeArray($form->getValues());
                $comentarioBo->exchangeArray($this->getPost());
                $comentarioBo->setDataCriacao(date('Y-m-d h:i:s'));
                if($comentarioBo->save())
                {
                    $this->flashMessage('Comentário adicionado com sucesso.');
                } 
                else 
                {
                    $this->flashError('Houve um problem ao tentar inserir seu comentário.');
                }
            }
	}
        
        echo $this->view->action('listar','comentario','ambientes-de-apoio', array('id' => $ambienteDeApoio->getId(), 'limite' => true));
        die();
    }
    /**
     * apagar comentario
     * @return Zend_View
     */
    public function apagarAction()
    {
        if(!$this->isAjax())
            $this->_redirect ('');
        $this->disableLayout();
        $usuario = $this->getLoggedUserObject();
        $id = $this->getRequest()->getParam('id');
        $comentarioBo = new Aew_Model_Bo_AmbienteDeApoioComentario();
        $comentarioBo->setId($id);
        if(!$comentarioBo->selectAutodados())
        {
            $this->flashError('Houve um problem ao tentar apagar o comentário.');
        }
        else
        {
            if(!$usuario->isSuperAdmin() || !$comentarioBo->isAutor($usuario))
            {
                $this->flashError('Não possui permissão para apagar este comentário.');
            }
            else
            {
                if($comentarioBo->delete())
                {
                    $this->flashMessage('Comentário apagado com sucesso.');
                } 
                else 
                {
                    $this->flashError('Houve um problem ao tentar apagar o comentário.');
                }
            }
        }
        $this->listarComentariosAction();
        $this->renderScript('/usuario/listar-comentarios.php');
    }
    /**
     * deslogando do sistema
     * @return Zend_View
     */
    public function logarAction() 
    {
        parent::logarAction();
        if(!$this->isAjax()):
            $this->_redirect('');
        endif;
        $this->disableLayout();
        $this->listarAction();
    }
}
