<?php
class AmbientesDeApoio_AmbientesController extends Sec_Controller_Action
{
    public function init(){
        /* @var $acl Sec_Controller_Action_Helper_Acl */
        parent::init();
        $acl = $this->getHelper('Acl');
        
        $this->view->usuarioLogado = $this->getLoggedUserObject();
        
        $visitanteAction = array('home', 'listar', 'categorias', 'tags', 'categorias');
        $acl->allow(Aew_Model_Bo_UsuarioTipo::VISITANTE, $visitanteAction);
        
        $administradorAction = array('destaques');
        $acl->allow(Aew_Model_Bo_UsuarioTipo::ADMINISTRADOR, $administradorAction);
    }

    function initScripts()
    {
        $this->view->headScript()->appendFile("/assets/plugins/jquery-live-preview/js/jquery-live-preview.js","text/javascript");
        $this->view->headLink()->appendStylesheet("/assets/plugins/jquery-live-preview/css/live-preview.css");
    }
    /**
     * redirecionar para ambiente de apoio
     * @return Zend_View
     */
    public function homeAction()
    {
        $this->_redirect('ambientes-de-apoio');
    }
    /**
     * exibir categoria do ambiente de apoio
     * @return Zend_View
     */
    public function categoriasAction()
    {
        $this->setPageTitle("Apoio a Produção e Colaboração");

        $categoriaAmbiente = new Aew_Model_Bo_AmbienteDeApoioCategoria();
        $categorias = $categoriaAmbiente->select();
        
        $this->view->usuario = $this->getLoggedUserObject();

        $this->view->categoriaSelecionada = $this->getRequest()->getParam('id', false);
        $this->view->categorias = $categorias;
        $this->view->comunidade = $this->getParam('comunidade',null);
        
        $this->view->rssDestaques = $this->getRssAEW(0, 4, array("ambientes-de-apoio"));
        $this->view->rssRecentes  = $this->getRssAEW(1, 4, array("ambientes-de-apoio"));
        $this->view->rssVistos    = $this->getRssAEW(2, 4, array("ambientes-de-apoio"));
    }
    /**
     * listar de categoria do ambiente de apoio
     * @return Zend_View
     */
    public function listarAction()
    {
        if(!$this->isAjax())
            $this->_redirect ('ambientes-de-apoio');
        
        $this->disableLayout();
        
        $categoria = $this->getRequest()->getParam('id', false);
    	$pagina = $this->getRequest()->getParam('pagina', 1);
        
        $this->view->comunidade = $this->getParam('comunidade',null);
        
        $categoriaAmbiente = new Aew_Model_Bo_AmbienteDeApoioCategoria();
        $categoriaAmbiente->setId($categoria);
        $categoriaAmbiente->selectAutoDados();
        
        $ambienteApoio = new Aew_Model_Bo_AmbienteDeApoio();
        $ambienteApoio->setAmbientedeApoioCategoria($categoriaAmbiente);
        
        $limite = 6;
        $ambientes = $ambienteApoio->select($limite, $pagina,null,true);
        
        $this->view->href = $this->opcoesAcessoConteudo($ambienteApoio);
        $ambientes = $ambienteApoio->getAsPagination($ambientes, $pagina, $limite,10);
        
        $this->view->categoria = $categoriaAmbiente;
        $this->view->ambientesDeApoio = $ambientes;
    }
    /**
     * Tags do conteudo do ambiente de apoio
     * @return Zend_View
     */
    public function tagsAction()
    {
        if($this->isAjax())
            $this->disableLayout();

        $id = $this->getRequest()->getParam('id', false);
    	$pagina = $this->getRequest()->getParam('pagina', 1);
        
        $ambienteApoio = new Aew_Model_Bo_AmbienteDeApoio();
        $options = array();
        $options['where']['EXISTS(SELECT * FROM ambientedeapoiotag WHERE ambientedeapoiotag.idambientedeapoio = ambientedeapoio.idambientedeapoio AND ambientedeapoiotag.idtag IN(?))'] = $id;
        
        $limite = 10;
        $ambientes = $ambienteApoio->select($limite, $pagina, $options, true);
        
        $ambientes = $ambienteApoio->getAsPagination($ambientes, $pagina, $limite,10);
        
        $tag = new Aew_Model_Bo_Tag();
        $tag->setId($id);
        $tag = $tag->select(1);
        
        $this->setPageTitle('Resultado da busca por "'.$tag->getNome().'"');
        
        $this->view->categoria = $categoriaAmbiente;
        $this->view->ambientesDeApoio = $ambientes;

        $template = "$(document).ready(function(){ 
                        $('.livepreview').livePreview({viewWidth: 250, viewHeight: 160, position: 'top', trigger: 'click'});
                    });";
        
        if($this->isAjax())
        {
            echo $this->view->headScript()->setScript($template,'text/javascript');
        }
        else
        {
            $this->view->headScript()->appendScript($template,'text/javascript');
        }
    }
    /**
     * destaque do ambiente de apoio
     * @return Zend_View
     */

    public function destaquesAction()
    {
        if($this->isAjax()):
            $this->disableLayout();
        endif;
        
        $this->setPageTitle('Ambientes de Apoio em Destaque');
        
        $ambienteApoio = new Aew_Model_Bo_AmbienteDeApoio();
        $pagina = $this->getRequest()->getParam('pagina', 1);
        
        $ambienteApoio->setDestaque(true);
                
        $limite = 6;
        $ambientes = $ambienteApoio->select($limite, $pagina,null,true);
        $ambientes = $ambienteApoio->getAsPagination($ambientes, $pagina, $limite,10);
        
        $this->view->ambientesDeApoio = $ambientes;
        $this->view->href = $this->opcoesAcessoConteudo($ambienteApoio);
        
        $template = "jQuery(document).ready(function($){
                        $('.livepreview').livePreview({viewWidth: 250, viewHeight: 160, position: 'top', trigger: 'click'});
                        $('img.lazy').lazyload({effect : 'fadeIn'});
                    });";
        
        $this->view->headScript()->appendScript($template,'text/javascript');
    }
}
