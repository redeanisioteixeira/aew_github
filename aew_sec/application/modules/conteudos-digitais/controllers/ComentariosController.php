<?php
class ConteudosDigitais_ComentariosController extends Sec_Controller_Action
{
    /**
     * configura as permicoes de acesso as actions
     */
    public function init()
    {
        parent::init();
	$acl = $this->getHelper('Acl');
	$visitanteAction = array('listar','logar','adicionar','listar-comentarios');
	$acl->allow(Aew_Model_Bo_UsuarioTipo::VISITANTE, $visitanteAction);
	$coordenadorAction = array('apagar');
	$acl->allow(Aew_Model_Bo_UsuarioTipo::COORDENADOR, $coordenadorAction);
    }
    /**
     * Carrega JS e CSS para o método HeadScript
     * @return Zend_View_Helper_HeadScript 
     */
    public function initScripts() 
    {
        parent::initScripts();
        $this->view->headScript()
                ->appendFile('/assets/js/jquery.form.js')
                ->appendFile('/assets/js/readmore.js')
                ->appendFile('/assets/js/comentarios.js');
    }
    /**
     * Lista comentários
     * @return Zend_View 
     */
    public function listarAction()
    {
	$request = Zend_Controller_Front::getInstance();
	if($this->isAjax() == true):
            $this->disableLayout();
	endif;
        $id = $this->getParam("id", null);
        $usuario = $this->getLoggedUserObject();
	$comentarioForm = new ConteudosDigitais_Form_Comentario($usuario);
        $form_login = new Aew_Form_LoginComentario();
        $form_login->getElement('enviar')->setLabel('entrar');
        $form_login->setAction("/".$request->getRequest()->getModuleName()."/comentarios/logar/id/$id");
        $form_login->setAttrib("id","login-comentario");
        $this->view->form_login_comentario = $form_login;
        $this->view->pagination = true;
        $this->listarComentariosAction();
	$this->view->id = $id;
	$this->view->formComentarios = $comentarioForm; 
    }
    /**
     * Método para listar com paginação os comentários relacionados
     * 
     */
    public function listarComentariosAction()
    {
        if($this->isAjax() == true):
            $this->disableLayout();
	endif;
        
        $id = $this->getRequest()->getParam('id', false);
	$pagina = $this->getRequest()->getParam('pagina', 1);
	$limite = $this->getRequest()->getParam('limite', 5);

        $conteudoBo = new Aew_Model_Bo_ConteudoDigital();
        $conteudoBo->setId($id);
        $comentarios = $conteudoBo->selectComentarios($limite,$pagina,null,true);
        $comentarios = $conteudoBo->getAsPagination($comentarios,$pagina,5,5);
        $comentarios->setPageRange(1);
        
        $this->view->urlPaginator = $this->view->url(array('module' => 'conteudos-digitais', 'controller' => 'comentarios','action' => 'listar-comentarios','id'=>$conteudoBo->getId()));
        $this->view->idDiv = 'conteudo-comentarios';
        $this->view->pagination = true;
      	$this->view->comentarios =  $comentarios;
        $this->usuarioLogado = $this->getLoggedUserObject();
    }
    /**
     * Método para se logar desde a opção comentários
     */
    public function logarAction()
    {
        parent::logarAction();
        
        if(!$this->isAjax()):
            $this->_redirect('');
        endif;
        
        $this->disableLayout();
        //$this->getRequest()->setParam('idconteudodigital', $conteudoDigital->getId());
        $this->listarAction();
    }
}
