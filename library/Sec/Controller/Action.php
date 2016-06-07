<?php

class Sec_Controller_Action extends Zend_Controller_Action 
{
    /**
     *
     * @var Zend_Controller_Action_Helper_FlashMessenger
     */
    protected $_canal;
    protected $_flashMessenger;
    protected $_linkExibicao;
    protected $_linkListagem;
    protected $_actionAdicionar;
    protected $_actionEditar;
    protected $_actionApagar;
    protected $_actionBloquear;
    protected $_actionSalvar;
    protected $_actionConvidar;
    protected $mensagemSucesso;
    protected $mensagemErro;
    
    public function __construct(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array())
    {
        parent::__construct($request, $response, $invokeArgs);

        $canal = $this->getRequest()->getParam("canal","");

		$usuario = $this->getLoggedUserObject();
		$naoManutencao = false;

		if($usuario)
		{
			if($usuario->isSuperAdmin())
			{
				$naoManutencao = true;
			}
		}

        //OFFLINE SITE
        if(EM_MANUTENCAO && !$naoManutencao)
		{
	        $this->_redirect('index.html');
        }

        $this->view->headMeta()
            ->setName('title', 'Ambiente Educacional Web')
            ->setName('keywords', 'Ambiente Educacional Web, Espaço-Aberto, Conteúdos Digitais, Sites Temáticos, Ambiente de Apóio a Colaboração, Professor Web, Tv Anísio Teixeira')
            ->setName('description', 'Ambiente Educacional Web é o ambiente de colaboração da Secretaria de Educação do Estado da Bahia')
            ->setName('author', 'Secretaria de Educação do Estado da Bahia - SEC')
            ->setName('google-site-verification', '11116BDYKvcujg3NzLkx1T6J9KYPFpsppELPZjXHzP2WYqs')
            ->setName('bitly-verification','45ccfc57d9c1')
            ->setName('viewport','width=device-width, initial-scale=1.0')
            ->setIndent(8);
        
        $this->view->headLink()
            ->headLink(array('rel' => 'favicon','href' => '/assets/img/favicon.ico','type' => 'image/x-icon'))
            ->headLink(array('rel' => 'icon','href' => '/assets/img/favicon.ico','type' => 'image/x-icon'))
            ->headLink(array('rel' => 'shortcut icon','href' => '/assets/img/favicon.ico','type' => 'image/x-icon'))
            ->setIndent(8);
        
        $this->view->headLink()
            ->appendStylesheet('/assets/plugins/font-awesome-4.6.3/css/font-awesome.min.css')
            ->appendStylesheet('/assets/plugins/bootstrap/css/bootstrap.min.css')
            ->appendStylesheet('/assets/plugins/bootstrap/css/bootstrap-theme.min.css')
            ->appendStylesheet('/assets/plugins/jquery-ui-themes-1.11.4/themes/blitzer/theme.css')
            ->appendStylesheet('/assets/plugins/jquery-ui-themes-1.11.4/themes/blitzer/jquery-ui.css')
            ->offsetSetStylesheet(50,'/assets/css/portal.css')
            ->offsetSetStylesheet(51,'/assets/css/main.css')
            ->setIndent(8);
        
        $this->view->headScript()
            ->setAllowArbitraryAttributes(true)    
            ->prependFile('/assets/js/jquery-1.11.0.min.js')
			->appendFile('/assets/plugins/jquery-ui-1.11.4/jquery-ui.js','text/javascript')
            ->appendFile('/assets/js/custom.js',"text/javascript")
            ->appendFile('/assets/js/jquery.chainedSelects.js',"text/javascript")
            ->appendFile('/assets/plugins/lazyload/jquery.lazyload.min.js')
            ->appendFile('/assets/js/isotope.pkgd.min.js')
            ->appendFile('/assets/plugins/bootstrap/js/bootstrap.min.js')
            ->setIndent(8); 
        
        if($this->view->getModule() == 'administracao')
        {
            $this->view->corfundo = 'menu-cinza-claro';
            $this->view->corfonte = 'menu-cinza';
            $this->_helper->layout->setLayout('home/layout');
        }
        
        if($this->view->getModule() == 'aew')
        {
            $this->view->headLink()->appendStylesheet('/assets/css/home.css')->setIndent(8);
            $layout = ($this->view->getController() == 'home' && $this->view->getAction() == 'home' ? 'layout-home' : 'layout');
            $this->view->corfundo = 'menu-cinza-claro';
            $this->view->corfonte = 'menu-cinza';
            $this->_helper->layout->setLayout('home/'.$layout);
        }

        if($this->view->getModule() == 'conteudos-digitais' && $this->view->getController() != 'sites-tematicos')
        {
            $this->_helper->layout->setLayout('conteudos-digitais/layout');
            $this->view->corfundo = ($canal == "sitetematico" ? "menu-vermelho" : "menu-azul");
            $this->view->corfonte = ($canal == "sitetematico" ? "menu-vermelho" : "menu-azul");
	    $this->view->headLink()->appendStylesheet("/assets/css/".($canal == "sitetematico" ? "st.css" : "cd.css"))->setIndent(8);
        }

        if($this->view->getModule() == 'conteudos-digitais' && $this->view->getController() == 'disciplinas')
        {
            $this->view->headLink()->appendStylesheet('/assets/img/icones/tipos-arquivos/sprites/tipos-arquivos.css');
        }

        if($this->view->getModule() == 'sites-tematicos')
        {
            $this->_helper->layout->setLayout("sites-tematicos/layout");
            $this->view->corfundo = "menu-vermelho";
            $this->view->corfonte = "menu-vermelho";
	    $this->view->headLink()->appendStylesheet("/assets/css/st.css")->setIndent(8);
            $this->view->headScript()->appendFile('/assets/js/sites-tematicos/st.js')->setIndent(8);
        }

	if($this->view->getModule() == 'espaco-aberto')
        {
            $this->view->corfundo = "menu-verde";
            $this->view->corfonte = "menu-verde";
            $this->view->headLink()->appendStylesheet('/assets/css/ea.css')->setIndent(8);
            $this->view->headScript()->appendFile('/assets/js/espaco-aberto/ea.js',"text/javascript", array("async" => "true"))->setIndent(8);

            if($this->view->getController() != 'chat')
            {
                    $this->flashmessage('Espaço Aberto em manutenção', '/home/sobre-espaco-aberto');
            }

            $this->_helper->layout->setLayout('espaco-aberto/layout');
        }

	if($this->view->getModule() == 'professorweb')
        {
            $this->view->corfundo = "menu-roxo";
            $this->view->corfonte = "menu-roxo";
            $this->_helper->layout->setLayout('professorweb/layout');
        }
        
        if($this->view->getModule() == 'ambientes-de-apoio')
        {
            $this->_helper->layout->setLayout('ambientes-apoio/layout');
            $this->view->corfundo = "menu-amarelo";
            $this->view->corfonte = "menu-amarelo";
            $this->view->headLink()->appendStylesheet('/assets/css/aap.css')->setIndent(8);
            $this->view->headScript()->appendFile('/assets/js/ambientes-de-apoio/aap.js')->setIndent(8);
        }

        if($this->view->getModule() == 'tv-anisio-teixeira')
        {
            $this->setIdCanal(1);
            $this->view->headLink() ->appendStylesheet('/assets/css/tv.css')->setIndent(8);
            $this->view->corfundo = "menu-marron";
            $this->view->corfonte = "menu-marron";
            $layout = ($this->view->getController() == 'home' && $this->view->getAction() == 'home' ? 'layout-home' : 'layout');
            $this->_helper->layout->setLayout("tv-anisio-teixeira/$layout");
        }

        if($this->view->getModule() == 'emitec')
        {
            $this->setIdCanal(1);
            $this->view->headLink() ->appendStylesheet('/assets/css/emitec.css')->setIndent(8);
            $this->view->corfundo = "menu-azul";
            $this->view->corfonte = "menu-azul";
            $layout = ($this->view->getController() == 'home' && $this->view->getAction() == 'home' ? 'layout-home' : 'layout');
            $this->_helper->layout->setLayout("tv-anisio-teixeira/$layout");
        }
        
        if($this->view->getModule() == 'conteudos-digitais' && $this->view->getController() == 'disciplinas')
        {
            $this->view->headLink() ->appendStylesheet('/assets/css/tv.css')->setIndent(8);
        }
        
        if ($usuario && $this->view->getModule() != 'administracao'):
            $this->view->headLink()->appendStylesheet('/assets/css/chat.css');
        endif;
        
        $this->view->usuarioLogado = $usuario;
        
        $this->view->session = $this->initSession();        
        $this->initViewDisp();
        $this->initUrlsMenuPrincipal();

        $browser_cliente = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        if(strpos($browser_cliente, 'MSIE') !== false)
        {    
            //$this->view->headLink()->appendStylesheet('/css/estilo_ie.css');
        }

        //OFFLINE SITE
        if(OFFLINE)
            $this->view->headScript()->appendFile('/assets/js/offline.js','text/javascript');

     
        //--- Link de busca por temas tranversais
        $this->view->buscaTemas = array(103 => 'http://bit.ly/1rKvsmF',
                                        99 => 'http://bit.ly/1sDn7Az',
                                        98 => 'http://bit.ly/1ETQEtX',
                                        97 => 'http://bit.ly/1wyzrCu',
                                        101 => 'http://bit.ly/YdaoHx',
                                        102 => 'http://bit.ly/1wyB5nC',
                                        95 => 'http://bit.ly/1sSA62p',
                                        96 => 'http://bit.ly/WNNndP',
                                        100 => 'http://bit.ly/1ruhq26'
                                    );

        $form_login = new Aew_Form_Login();
        $this->view->form_login = $form_login;
        
        $this->view->corfundo = ($this->view->corfundo != "" ? "bgcolor='".$this->view->corfundo."'" : "");
        $this->view->corfonte = ($this->view->corfonte != "" ? "fcolor='".$this->view->corfonte."'"  : "");
    }


    public function setIdCanal($_canal) {
        $this->_canal = $_canal;
    }

    public function getIdCanal() {
        return $this->_canal;
    }

    /** 
     * @return the $_linkExibicao
     */
    public function getLinkExibicao($id = '') {
        return $this->_linkExibicao . $id;
    }

    /**
     * @param $_linkExibicao the $_linkExibicao to set
     */
    public function setLinkExibicao($_linkExibicao) {
        $this->_linkExibicao = $_linkExibicao;
    }

    /**
     * @return the $_linkListagem
     */
    public function getLinkListagem() {
        return $this->_linkListagem;
    }

    /**
     * @param $_linkListagem the $_linkListagem to set
     */
    public function setLinkListagem($_linkListagem) {
        $this->_linkListagem = $_linkListagem;
    }

    /**
     * @param $_actionConvidar the $_actionConvidar to set
     */
    public function setActionConvidar($_actionConvidar) {
        $this->_actionConvidar = $_actionConvidar;
    }

    function getActionAdicionar() {
        return $this->_actionAdicionar;
    }

    function setActionAdicionar($_actionAdicionar) {
        $this->_actionAdicionar = $_actionAdicionar;
    }
    
    function getActionEditar($id = '') {
        return $this->_actionEditar . $id;
    }

    function setActionEditar($_actionEditar) {
        $this->_actionEditar = $_actionEditar;
    }

        /**
     * @return the $_actionApagar
     */
    public function getActionApagar($id = '') {
        return $this->_actionApagar . $id;
    }

    /**
     * @return the $_actionApagar
     */
    public function getActionBloquear($id = '') {
        return $this->_actionBloquear . $id;
    }

    /**
     * @return the _actionComentar
     */
    public function getActionComentar($id = '') {
        return $this->_actionComentar . $id;
    }

    /**
     * @return the _actionConvidar
     */
    public function getActionConvidar($id = '') {
        return $this->_actionConvidar . $id;
    }

    /**
     * @return the _actionComentar
     */
    public function getActionAdicionaMarcacao($id = '') {
        return $this->_actionAdicionaMarcacao . $id;
    }

    /**
     * @param $_actionApagar the $_actionApagar to set
     */
    public function setActionApagar($_actionApagar) {
        $this->_actionApagar = $_actionApagar;
    }

    /**
     * @param $_actionApagar the $_actionApagar to set
     */
    public function setActionBloquear($_actionApagar) {
        $this->_actionBloquear = $_actionApagar;
    }

    /**
     * @param $_actionComentar the $_actionComentar to set
     */
    public function setActionComentar($_actionApagar) {
        $this->_actionComentar = $_actionApagar;
    }

    /**
     * @param $_actionComentar the $_actionComentar to set
     */
    public function setActionAdicionaMarcacao($_actionApagar) {
           $this->_actionAdicionaMarcacao = $_actionApagar;
    }

    /**
     * @return the $_actionSalvar
     */
    public function getActionSalvar($id = '') {
        return $this->_actionSalvar . $id;
    }

    /**
     * @param $_actionSalvar the $_actionSalvar to set
     */
    public function setActionSalvar($_actionSalvar) {
        $this->_actionSalvar = $_actionSalvar;
    }

    /**
     *
     * @return Zend_Controller_Action_Helper_FlashMessenger
     */
    public function getFlashMessennger()
    {
        if(null == $this->_flashMessenger){
            $this->_flashMessenger = $this->_helper->getHelper('flashMessenger');
        }
        return $this->_flashMessenger;
    }

    /**
     * Adiciona uma mensagem de erro no flashMessenger
     *
     * @param $message string Mensagem
     * @param $redirect string endereço para redirecionar
     */
    public function flashError($message, $redirect = '')
    {
        $flash = $this->getFlashMessennger();
        $flash->setNamespace('actionErrors')->addMessage($message);
        if($redirect != ''){
            $this->_redirect($redirect);
        }
    }

    /**
     * Adiciona uma mensagem no flashMessenger
     *
     * @param $message string Mensagem
     * @param $redirect string endereço para redirecionar
     */
    public function flashMessage($message, $redirect = '')
    {
        $flash = $this->getFlashMessennger();
        $flash->setNamespace('actionMessages')->addMessage($message);
        if($redirect != ''){
            $this->_redirect($redirect);
        }
    }

    /**
     * Diz se a requisição é ajax
     * @return bool
     */
    public function isAjax()
    {
        return $this->getRequest()->isXmlHttpRequest();
    }

    /**
     * Diz se a requisição é post
     * @return bool
     */
    public function isPost()
    {
        return $this->getRequest()->isPost();
    }

    /**
     * Retorna o valor do post
     * @param $key
     * @param $default
     * @return mixed
     */
    public function getPost($key = null, $default = null)
    {
        return $this->getRequest()->getPost($key, $default);
    }

    /**
     * Diz se a requisição é get
     * @return bool
     */
    public function isGet()
    {
        return $this->getRequest()->isGet();
    }

    /**
     * Retorna o valor do post
     * @param $key
     * @param $default
     * @return mixed
     */
    public function getParam($key = null, $default = null)
    {
        return $this->getRequest()->getParam($key, $default);
    }

    /**
     * Retorna os parametros da requisicao
     */
    public function getParams()
    {
        return $this->getRequest()->getParams();
    }

    /**
     * Define o título da página
     * @param string $titulo
     * @param string $tituloHeader
     */
    public function setPageTitle($titulo, $tituloHeader = null)
    {
        if($tituloHeader == null){
            $tituloHeader = $titulo;
        }

        $this->view->headTitle($tituloHeader, 'PREPEND');
        $this->view->pageTitle = $titulo;
    }
    
    /**
     * Configura os titulos de apresentação da página
     * @param type $titulo
     * @param type $subtitulo
     */
    public function configCabecalho($titulo="Título",$subtitulo="Subtítulo") 
    {
       $this->setPageTitle($titulo);
       $this->setPageSubTitle($subtitulo);
    }        

    /**
     * Define o sub título da página
     * @param string $titulo
     * @param string $tituloHeader
     */
    public function setPageSubTitle($titulo, $tituloHeader = null)
    {
        if($tituloHeader == null){
            $tituloHeader = $titulo;
        }

        $this->view->headTitle($tituloHeader, 'PREPEND');
        $this->view->pageSubTitle = $titulo;
    }

    /**
     * Desabilita o Layout
     */
    public function disableLayout()
    {
        $this->_helper->layout->disableLayout();
    }

    /**
     * Desabilita a renderização
     */
    public function disableRender()
    {
        $this->_helper->viewRenderer->setNoRender();
    }

    /**
     * Retorna se o usuario atual tem permissao
     * @param string $resource
     * @param string $action
     * @return bool
     */
    public function isAllowed($resource, $action)
    {
        $usuario = $this->getLoggedUserObject();
        $acl = Sec_Acl::getInstance();
        $allowed = $acl->isAllowed($usuario->getUsuarioTipo()->getNome(), $resource, $action);
        return $allowed;
    }   
    
    public function jsonObjects(array $objects)
    {
        $json = '[';
        foreach($objects as $ob)
        {
            if($ob instanceof Sec_Model_Bo_Abstract)
            $json .= $ob->toJson().',';
        }
        return $json.']';
    }
    
    /**
     * 
     * @param Sec_Model_Bo_Abstract $model
     * @param type $result
     * @return type
     */
    function resultJsonAjaxReq(Sec_Model_Bo_Abstract $model,$result)
    {
        return "{'response':'$result','object':'".$model->toJson()."'}";
    }
    
    /**
     * 
     * @return null|Aew_Model_Bo_ItemPerfil
     */
    function getPerfiluserObject()
    {
        $idUsuario      = $this->getParam('usuario', 0);
        $idComunidade   = $this->getParam('comunidade', 0);
        
        $perfilUsuario = null;
        if($idComunidade) 
        {
            $perfilUsuario = new Aew_Model_Bo_Comunidade();
            $perfilUsuario->setId($idComunidade);
        }
        else
        {
            $perfilUsuario = new Aew_Model_Bo_Usuario();
            if($idUsuario)
            {
                $perfilUsuario->setId($idUsuario);
                $perfilUsuario->setFlativo(true);
            }
            else
            {
                return $this->getLoggedUserObject();
            }
        }
        
        if($perfilUsuario)
            return $perfilUsuario->select(1); 
        
        return null;
     }
     
    /**
     * retorna o usuario da sessão
     * @return Aew_Model_Bo_Usuario
     */
    public static function getLoggedUserObject()
    {
        $auth = Zend_Auth::getInstance();
        if($auth->hasIdentity())
        {

            $data = $auth->getIdentity();
            return $data;
        } 
        
        return null;
    }
    
    /**
     * configura a variavel da view para o tipo de dispositivo
     * $this->view->tipoDispositivo
     */
    public function initViewDisp()
    {
        $this->tipoDispositvo = Sec_TipoDispositivo::detectar();
        $this->view->tipoDispositivo = $this->tipoDispositvo['deviceType'];
    }
    
    /**
     *    configura as urls da barra de menu  principal do AEW
     */
    function initUrlsMenuPrincipal()
    {
        $this->view->urlModuleEspacoAberto = $this->view->url(array('module' => 'espaco-aberto', 'controller' => 'home', 'action' => 'home'), null, true);
        $usuario = $this->getLoggedUserObject();
        if($usuario)
        {
            $this->view->urlModuleEspacoAberto = $this->view->url(array('module' => 'espaco-aberto'), null, true);
            $this->view->urlSair = $this->view->url(array('controller' => 'usuario','action' => 'sair'), null, true);
            $this->view->menuAcesso = $this->menuAcesso($usuario);
        }
        $this->view->urlModuleConteudoDigital   = $this->view->url(array('module' => 'conteudos-digitais'), null, true);
        $this->view->urlModuleSitesTematicos    = $this->view->url(array('module' => 'sites-tematicos'), null, true);
        $this->view->urlModuleAmbienteDeApoio   = $this->view->url(array('module' => 'ambientes-de-apoio'), null, true);
        $this->view->urlModuleProfessorWeb      = $this->view->url(array('module' => 'professorweb'), null, true);
        $this->view->urlModuleTVanisioteixeira  = $this->view->url(array('module' => 'tv-anisio-teixeira'), null, true);
        $this->view->urlAjuda                   = $this->view->url(array('module' => 'conteudos-digitais', 'controller' => 'conteudo', 'action' => 'exibir', 'id' => '2237'), null, true);
        $this->view->urlFaleConosco             = $this->view->url(array('module' => 'aew', 'controller' => 'home', 'action' => 'faleconosco'), null, true);
        $this->view->urlDenunciar               = $this->view->url(array('module' => 'aew', 'controller' => 'home', 'action' => 'denunciar'), null, true);
        $this->view->urlTermoUso                = $this->view->url(array('module' => 'aew', 'controller' => 'home', 'action' => 'termo-condicoes-uso'), null, true);
        $this->view->urlSobre                   = $this->view->url(array('module' => 'aew', 'controller' => 'home', 'action' => 'sobre'), null, true);
        $this->view->urlRecuperarSenha          = $this->view->url(array('module' => 'aew', 'controller' => 'usuario', 'action'  => 'esqueci-a-senha'), null, true); 
        $this->view->urlCadastro                = $this->view->url(array('module' => 'aew', 'controller' => 'usuario', 'action'  => 'cadastro'), null, true);
        $this->view->urlCarregaAlerta           = $this->view->url(array('module' => 'aew', 'controller' => 'home','action' => 'verifica-alerta'), null, true);
        
        $this->view->usuario = $usuario;
        $this->view->acl = Sec_Acl::getInstance();
    }
    
    public function opcoesAcessoConteudo($conteudo)
    {
        $usuario = $this->getLoggedUserObject();
        $acl = Sec_Acl::getInstance();
        
        $href = array();

        if($conteudo instanceof Aew_Model_Bo_ConteudoDigital)
        {
            $href['incorporar_url'] = $conteudo->getIncorporarConteudoUrl(false);
        }    
        if(!$usuario)
        {
            return $href;
        }

        if($conteudo instanceof Aew_Model_Bo_Formato)
        {
            if($acl->isAllowed($usuario->getUsuarioTipo()->getNome(), 'administracao:formato', 'adicionar'))
            {
                $href['adicionar_formato'] = $this->view->url(array('module' => 'administracao', 'controller' => 'formato', 'action' => 'adicionar'), null, true);
            }
            if($acl->isAllowed($usuario->getUsuarioTipo()->getNome(), 'administracao:formato', 'editar'))
            {
                $href['editar_formato'] = $this->view->url(array('module' => 'administracao', 'controller' => 'formato', 'action' => 'editar'), null, true);
            }            
            if($acl->isAllowed($usuario->getUsuarioTipo()->getNome(), 'administracao:formato', 'apagar'))
            {
                $href['apagar_formato'] = $this->view->url(array('module' => 'administracao', 'controller' => 'formato', 'action' => 'apagar'), null, true);
            }            
        }

        if($conteudo instanceof Aew_Model_Bo_Tag)
        {
            if($acl->isAllowed($usuario->getUsuarioTipo()->getNome(), 'administracao:tag', 'adicionar'))
            {
                $href['adicionar_tag'] = $this->view->url(array('module' => 'administracao', 'controller' => 'tag', 'action' => 'adicionar'), null, true);
            }
            if($acl->isAllowed($usuario->getUsuarioTipo()->getNome(), 'administracao:tag', 'editar'))
            {
                $href['editar_tag'] = $this->view->url(array('module' => 'administracao', 'controller' => 'tag', 'action' => 'editar'), null, true);
            }            
            if($acl->isAllowed($usuario->getUsuarioTipo()->getNome(), 'administracao:tag', 'apagar'))
            {
                $href['apagar_tag'] = $this->view->url(array('module' => 'administracao', 'controller' => 'tag', 'action' => 'apagar'), null, true);
            }            
        }
        
        if($conteudo instanceof Aew_Model_Bo_Usuario)
        {
            $href['perfil_usuario'] = $this->view->url(array('module' => 'espaco-aberto', 'controller' => 'perfil', 'action' => 'feed'), null, true);
            
            if($acl->isAllowed($usuario->getUsuarioTipo()->getNome(), 'administracao:usuario', 'relatorio'))
            {
                $href['relatorio_usuario'] = $this->view->url(array('module' => 'administracao', 'controller' => 'usuario', 'action' => 'relatorio'), null, true);
            }
            if($acl->isAllowed($usuario->getUsuarioTipo()->getNome(), 'administracao:usuario', 'editar'))
            {
                $href['editar_usuario'] = $this->view->url(array('module' => 'administracao', 'controller' => 'usuario', 'action' => 'editar'), null, true);
            }
            if($acl->isAllowed($usuario->getUsuarioTipo()->getNome(), 'administracao:usuario', 'apagar'))
            {
                $href['apagar_usuario'] = $this->view->url(array('module' => 'administracao', 'controller' => 'usuario', 'action' => 'apagar'), null, true);
            }            
            if($acl->isAllowed($usuario->getUsuarioTipo()->getNome(), 'administracao:usuario', 'trocar-senha'))
            {
                $href['trocarsenha_usuario'] = $this->view->url(array('module' => 'administracao', 'controller' => 'usuario', 'action' => 'trocar-senha'), null, true);
            }                    
        }
        
        if($conteudo instanceof Aew_Model_Bo_ConteudoLicenca)
        {
            if($acl->isAllowed($usuario->getUsuarioTipo()->getNome(), 'administracao:licenca', 'editar'))
            {
                $href['editar_licenca'] = $this->view->url(array('module' => 'administracao', 'controller' => 'licenca', 'action' => 'editar'), null, true);
            }
            if($acl->isAllowed($usuario->getUsuarioTipo()->getNome(), 'administracao:licenca', 'apagar'))
            {
                $href['apagar_licenca'] = $this->view->url(array('module' => 'administracao', 'controller' => 'licenca', 'action' => 'apagar'), null, true);
            }            
        }

        if($conteudo instanceof Aew_Model_Bo_ConteudoDigitalCategoria)
        {
            if($acl->isAllowed($usuario->getUsuarioTipo()->getNome(), 'administracao:categoria-conteudo', 'editar'))
            {
                $href['editar_categoria'] = $this->view->url(array('module' => 'administracao', 'controller' => 'categoria-conteudo', 'action' => 'editar'), null, true);
            }
            if($acl->isAllowed($usuario->getUsuarioTipo()->getNome(), 'administracao:categoria-conteudo', 'apagar'))
            {
                $href['apagar_categoria'] = $this->view->url(array('module' => 'administracao', 'controller' => 'categoria-conteudo', 'action' => 'apagar'), null, true);
            }            
        }
        
        if($conteudo instanceof Aew_Model_Bo_ConteudoDigital)
        {
            if(!$conteudo->getFlaprovado()):
                $href['aprovar_conteudo']  = $this->view->url(array('module' => 'conteudos-digitais', 'controller' => 'conteudo', 'action' => 'aprovar', 'id' => $conteudo->getId()), null, true);
                $href['reprovar_conteudo'] = $this->view->url(array('module' => 'conteudos-digitais', 'controller' => 'conteudo', 'action' => 'reprovar', 'id' => $conteudo->getId()), null, true);
            endif;
            
            if ($usuario->isSuperAdmin())
            {   
                $destaque = ($conteudo->getDestaque() == null ? false : true);
                if($destaque == true)
                {
                    $href['remover_destaque'] = $this->view->url(array('module' => 'conteudos-digitais', 'controller' => 'conteudo', 'action' => 'removerdestaque', 'id' => $conteudo->getId()), null, true);
                }
                else
                {
                    $href['adicionar_destaque'] = $this->view->url(array('module' => 'conteudos-digitais', 'controller' => 'conteudo', 'action' => 'destaque', 'id' => $conteudo->getId()), null, true);
                }
            }
            
            $href['adicionar_favorito'] = $conteudo->getUrlAdicionarfavorito($usuario);
            if(!$href['adicionar_favorito'])
                $href['remover_favorito']   = $conteudo->getUrlRemoverFavorito($usuario);
            
            if($this->view->getModule() == 'conteudos-digitais')
            {
                if($this->view->getController() == 'conteudos')
                {
                    if($acl->isAllowed($usuario->getUsuarioTipo()->getNome(), 'conteudos-digitais:conteudos', 'relatorio'))
                    {
                        $href['relatorio_usuario'] = $this->view->url(array('module' => 'conteudos-digitais', 'controller' => 'conteudos', 'action' => 'relatorio'), null, true);
                    }
                }

                if($this->view->getController() == 'conteudo')
                {
                    if((($acl->isAllowed($usuario->getUsuarioTipo()->getNome(), 'conteudos-digitais:conteudo', 'editar')) &&
                        $conteudo->getUsuarioPublicador()->getId() == $usuario->getid()) ||
                        $usuario->getUsuarioTipo()->getNome() == "super administrador"||
                        $usuario->getUsuarioTipo()->getNome() == "administrador"||
                        $usuario->getUsuarioTipo()->getNome() == "coordenador")
                    {
                        $href['editar_conteudo'] = $this->view->url(array('module' => 'conteudos-digitais', 'controller' => 'conteudo', 'action' => 'editar', 'id' => $conteudo->getId()), null, true);
                    }

                    if(($acl->isAllowed($usuario->getUsuarioTipo()->getNome(), 'conteudos-digitais:conteudo', 'apagar') &&
                        $conteudo->getUsuarioPublicador()->getId() == $usuario->getid()) ||
                        $usuario->isCoordenador())
                    {
                        $href['apagar_conteudo'] = $this->view->url(array('module' => 'conteudos-digitais', 'controller' => 'conteudo', 'action' => 'apagar', 'id' => $conteudo->getId()), null, true);
                    }
                }
            }
            
            if($this->view->getModule() == 'tv-anisio-teixeira')
            {
                if((($acl->isAllowed($usuario->getUsuarioTipo()->getNome(), 'tv-anisio-teixeira:programas', 'editar')) &&
                    $conteudo->getUsuarioPublicador()->getId() == $usuario->getid()) ||
                    $usuario->isCoordenador())
                {
                    $href['editar_conteudo']    = $this->view->url(array('module' => 'conteudos-digitais', 'controller' => 'conteudo', 'action' => 'editar', 'id' => $conteudo->getId()), null, true);
                }

                if(($acl->isAllowed($usuario->getUsuarioTipo()->getNome(), 'tv-anisio-teixeira:programas', 'apagar') &&
                    $conteudo->getUsuarioPublicador()->getId() == $usuario->getid()) ||
                    $usuario->isCoordenador())
                {
                    $href['apagar_conteudo'] = $this->view->url(array('module' => 'conteudos-digitais', 'controller' => 'conteudo', 'action' => 'apagar', 'id' => $conteudo->getId()), null, true);
                }
            }

            if(!$usuario->isAdmin())
            {
                if($usuario->getid() == 93 || $usuario->getid() == 545)
                {
                    if($conteudo->getUsuarioPublicador()->getId() != $usuario->getId())
                    {
                        $href['editar_conteudo'] = '';
                        $href['apagar_conteudo'] = '';
                    }
                }
            }

        }

        if($conteudo instanceof Aew_Model_Bo_AmbienteDeApoio)
        {
            if($acl->isAllowed($usuario->getUsuarioTipo()->getNome(), 'ambientes-de-apoio', 'categoria/adicionar'))
            {
                $href['adicionar_categoria'] = $this->view->url(array('module' => 'ambientes-de-apoio', 'controller' => 'categoria', 'action' => 'adicionar'), null, true);
            }
            
            if($acl->isAllowed($usuario->getUsuarioTipo()->getNome(), 'ambientes-de-apoio', 'editar-categoria'))
            {
                $href['editar_categoria'] = $this->view->url(array('module' => 'ambientes-de-apoio', 'controller' => 'categoria', 'action' => 'editar', 'id' => $conteudo->getAmbientedeApoioCategoria()->getId()), null, true);
            }

            if($acl->isAllowed($usuario->getUsuarioTipo()->getNome(), 'ambientes-de-apoio', 'apagar-categoria'))
            {
                $href['apagar_categoria'] = $this->view->url(array('module' => 'ambientes-de-apoio', 'controller' => 'categoria', 'action' => 'apagar', 'id' => $conteudo->getAmbientedeApoioCategoria()->getId()), null, true);
            }

            if ($usuario->isSuperAdmin())
            {   
                $destaque = ($conteudo->getDestaque() == null ? false : true);
                
                if($destaque == true)
                {
                    $href['remover_destaque']   = $this->view->url(array('module' => 'ambientes-de-apoio', 'controller' => 'ambiente','action' => 'removerdestaque', 'id' => $conteudo->getId()), null, true);
                }
                else
                {
                    $href['adicionar_destaque'] = $this->view->url(array('module' => 'ambientes-de-apoio', 'controller' => 'ambiente','action' => 'destaque', 'id' => $conteudo->getId()), null, true);
                }
            }
            
            $href['adicionar_favorito'] = $conteudo->getUrlAdicionarFavorito($usuario);
            if(!$href['adicionar_favorito'])
                $href['remover_favorito'] = $conteudo->getUrlRemoverFavorito($usuario);
            
            if((($acl->isAllowed($usuario->getUsuarioTipo()->getNome(), 'ambientes-de-apoio', 'editar')) &&
                $conteudo->getUsuarioPublicador()->getId() == $usuario->getid()) ||
                $usuario->isCoordenador())
            {
                $href['editar_conteudo']    = $this->view->url(array('module' => 'ambientes-de-apoio', 'controller' => 'ambiente','action' => 'editar', 'id' => $conteudo->getId()), null, true);
            }

            if(($acl->isAllowed($usuario->getUsuarioTipo()->getNome(), 'ambientes-de-apoio', 'apagar') &&
                $conteudo->getUsuarioPublicador()->getId() == $usuario->getid()) ||
                $usuario->isCoordenador())
            {
                $href['apagar_conteudo']    = $this->view->url(array('module' => 'ambientes-de-apoio', 'controller' => 'ambiente','action' => 'apagar', 'id' => $conteudo->getId()), null, true);		
            }
        }
        
        return $href;
    }
    
    protected function menuAcesso($usuario)
    {
        $arr_opcoes = array();
        
        $arr_menu = array(
                        "administracao" => "área de administração",
                        "conteudos-digitais" => "conteúdos digitais",
                        "ambientes-de-apoio" => "apoio a produção e colaboração",
                        "espaco-aberto" => ""
                    );
        
        $arr_subMenu = array(
                        "usuario/trocar-senha" => "alterar minha senha",
                        "usuario/adicionar" => "criar usuário",
                        "usuario/listar" => "listar usuários",
                        "tag/listar" => "listar tags",
                        "denuncias/listar" => "verificar denúncias",
                        "licenca/adicionar" => "adicionar liçenca",
                        "licenca/listar" => "listar liçencas",
                        "formato/listar" => "listar tipo de formatos",
                        "categoria-conteudo/adicionar" => "adicionar categoria de conteúdos digitais",
                        "categoria-conteudo/listar" => "listar categorias de conteúdos digitais",
                        "amigo-da-escola/adicionar" => "adicionar amigo da escola",
                        "amigo-da-escola/pendentes" => "amigos da escola pendentes por aprovar",
                        "conteudo/adicionar" => "adicionar conteúdo digital",
                        "conteudos/aprovar" => "aprovar conteúdos",
                        "conteudos/destaques" => "gerenciar destaques",
                        "ambiente/adicionar" => "adicionar ambiente de apoio",
                        "categoria/adicionar" => "adicionar categoria ambiente de apoio",            
                        "ambientes/destaques" => "gerenciar destaques",
                        "perfil/editar-perfil" => "editar meu perfil",
                        "album/adicionar" => "criar album de fotos",
                        "blog/adicionar" => "criar blog",
                        "comunidade/adicionar" => "criar comunidade",
                    );
        
        $arr_IconeMenu = array(
                        "usuario/trocar-senha" => "fa-key",
                        "usuario/adicionar" => "fa-user-plus",
                        "usuario/listar" => "fa-users",
						"tag/listar" => "fa-tags",
                        "denuncias/listar" => "",
                        "licenca/adicionar" => "",
                        "licenca/listar" => "fa-creative-commons",
                        "formato/listar" => "",
                        "categoria-conteudo/adicionar" => "fa-plus-circle",
                        "categoria-conteudo/listar" => "",
                        "amigo-da-escola/adicionar" => "",
                        "amigo-da-escola/pendentes" => "",
                        "conteudo/adicionar" => "fa-cubes",
                        "conteudos/aprovar" => "fa-thumbs-up",
                        "conteudos/destaques" => "fa-star",
                        "ambiente/adicionar" => "fa-wrench",
                        "categoria/adicionar" => "fa-plus-circle",
                        "ambientes/destaques" => "fa-star",
                        "perfil/editar-perfil" => "fa-pencil-square-o",
                        "album/adicionar" => "fa-camera",
                        "blog/adicionar" => "fa-rss-square",
                        "comunidade/adicionar" => "fa-comments-o",
                    ); 
                
                
        $acl = Sec_Acl::getInstance();
        $permissoes = $acl->getPermissions();

        foreach($permissoes as $modulo=>$opcoes)
        {
            foreach($opcoes as $key=>$value)
            {
                if(strpos($key,"/"))
                {
                    if($acl->isAllowed($usuario->getUsuarioTipo()->getNome(), $modulo, $key))
                    {
                        $arr_opcoes[$modulo][0] = $arr_menu[$modulo];
                        $icone = ($arr_IconeMenu[$key] ? $arr_IconeMenu[$key] : 'fa-circle-thin');

                        $arr_opcoes[$modulo][1]["<i class='fa $icone'></i> ".ucfirst($arr_subMenu[$key])] = $key.($key == 'usuario/trocar-senha' ? '/id/'.$usuario->getId() : '');
                    }
                }
            }
        }
        
        return $arr_opcoes;
    }
    
    protected function initSession()
    {
        $session = new Zend_Session_Namespace("conteudosDigitaisBusca");

        $limpar = $this->getRequest()->getParam("limpar", 0);
        $request = $this->getRequest();
        
        if($limpar)
        {
            $session->unsetAll();    
            $request = $this->getRequest()->clearParams();
        }
        
        $arguments = $session->getIterator()->getArrayCopy();
        if(count($arguments) == 0)
        {
            $arguments = array();
            $arguments["quantidade"] = 15;
            $arguments["busca"] = "";
            $arguments["tipos"] = "";
            $arguments["categorias"] = "";
            $arguments["niveisensino"] = "";
            $arguments["opcoes"] = "";
            $arguments["licencas"] = "";
            $arguments["visualizacao"] = "column";
			$arguments["tag"] = "";
			$arguments["opcao-busca-palavra"] = "tag";
            $arguments["ordenarPor"] = "avaliacao";
            $arguments["publicador"] = "";
            $arguments["sitetematico"] = false;
            $arguments["favorito"] = "";
            $arguments["pagina"] = 1;
        }
        
        $session->quantidade = ($request->getParam("qtdeTopico",0) > 0 ? $request->getParam("qtdeTopico") : $request->getParam("quantidade", $arguments["quantidade"]));
        $session->busca = $request->getParam("busca", $arguments["busca"]);
        $session->tipos = $request->getParam("tipos", $arguments["tipos"]);
        $session->categorias = $request->getParam("categorias", $arguments["categorias"]);
        $session->niveisensino = $request->getParam("niveisensino", $arguments["niveisensino"]);
        $session->opcoes = $request->getParam("opcoes", $arguments["opcoes"]);
        $session->licencas = $request->getParam("licencas", $arguments["licencas"]);
        $session->ordenarPor = $request->getParam("ordenarPor", $arguments["ordenarPor"]);
        $session->publicador = $request->getParam("publicador", $arguments["publicador"]);
        $session->favorito = $request->getParam("favorito", $arguments["favorito"]);
        $session->pagina = $request->getParam("pagina", $arguments["pagina"]);
        $session->visualizacao = $request->getParam('visualizacao', $arguments["visualizacao"]);
        $session->tag = $request->getParam("tag", $arguments["tag"]);
        $session->opcaoBuscaPalavra = $request->getParam("opcao-busca-palavra", $arguments["opcao-busca-palavra"]);
        
        $session->tipos = (is_array($session->tipos) ? implode(",", $session->tipos) : $session->tipos);
        $session->categorias = (is_array($session->categorias) ? implode(",", $session->categorias) : $session->categorias);
        $session->niveisensino = (is_array($session->niveisensino) ? implode(",", $session->niveisensino) : $session->niveisensino);
        $session->opcoes = (is_array($session->opcoes) ? implode(",", $session->opcoes) : $session->opcoes);
        $session->licencas = (is_array($session->licencas) ? implode(",", $session->licencas) : $session->licencas);

        return $session;
    }
    
    function salvarRegistro(Sec_Model_Bo_Abstract $model,  Sec_Form $form)
    {
        if($this->isPost())
	{
	    if($form->isValid($this->getRequest()->getPost()))
	    {
                $id = $form->getValue('id');
                if($form->getValue('id') > 0)
		{
                    $txt = 'editado';
                } 
		else 
		{
                    $txt = 'inserido';
                }
                $model->setId($id);
                $model->exchangeArray($form->getValues());
                $result = $model->save();
		if($result)
		{
                   $this->flashMessage($this->getMenssagemSucesso());
	           $this->_redirect($this->getLinkExibicao($model->getId()));
                }
                else
                {
                    $this->flashMessage($this->getMenssagemErro());
                    $this->getLinkExibicao();
                }
            } 
	    else 
	    {
                if($form->getValue('id') > 0)
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
            $this->_redirect($this->getLinkListagem());
        }
    }
    
    public function getMensagemSucesso()
    {
        return $this->mensagemSucesso;
    }

    public function getMensagemErro()
    {
        return $this->mensagemErro;
    }

    public function setMensagemSucesso($mensagemSucesso)
    {
        $this->mensagemSucesso = $mensagemSucesso;
    }

    public function setMensagemErro($mensagemErro)
    {
        $this->mensagemErro = $mensagemErro;
    }
    /**
     * palabras chave para metatags separadas por virgula
     * @param type $tags
     * @return type
     */
    public function palabrasChaveMeta($tags)
    {   
        $resp = array();
        foreach ($tags as $tag) {
            array_push($resp, $tag->getNome());
        }
        $string = implode (", ", $resp);
        return $string;
    }
    /**
     * retorna um array de objetos Rss
     * com os dados das conteudos em destaque
     * 
     * @param $tipo integer (0 = destaques', 1 = maisrecentes, 2 = maisacessados
     * @return array
     */
    public function getRssAEW($tipo, $qtde_registro = 4, $arr_linkRss = array("conteudos-digitais", "ambientes-de-apoio", "tv-anisio-teixeira"))
    {
        $arr_tipos = array(0 => "destaques", 1 => "maisrecentes", 2 => "maisacessados", 3 => "maisvotados", 4 => "blogPW");
		$arr_feed = array();
		$i = 0;

        if($tipo == 4)
        {
            /*--- Destaques blog Professor Web ---*/
            $feedUrl = "http://oprofessorweb.wordpress.com/feed";
            $feed = new Zend_Feed_Reader();
            try 
            {
                $entrada = $feed->import($feedUrl);
            } 
            catch (Exception $ex) 
            {
                return array();
            }
            
            if($entrada->count()):
                $img = $entrada->getImage();
                $i = 0;
                foreach ($entrada as $item):
                    $dataPublicacao = strtotime(str_replace("/","-",$item->getDateCreated()));

                    $author = $item->getAuthor();
                    $arr_feed[$i]["author"]      = "Publicado em ".$this->view->SetupDate($dataPublicacao)." por <b>".$author["name"]."</b>"; 
                    $arr_feed[$i]["title"]       = $item->getTitle();
                    $arr_feed[$i]["img"]         = $img["uri"];
                    $arr_feed[$i]["description"] = strip_tags($item->getContent());
                    $arr_feed[$i]["link"]        = $item->getLink();
                    $arr_feed[$i]['color']       = "roxo";

                    $re_extractImages = '/<img.*?src=["\'](.*?)["\'].*\/?>/';
                    preg_match_all($re_extractImages, $item->getContent(), $links);
                    if($links){
                        foreach($links[1] as $link){
                            if(strpos($link, ".files.")){
                                $length = strpos($link, "?w=");
                                if($length){
                                    $link = substr($link, 0, $length);
                                    $link .= "?w=370";
                                }
                                $arr_feed[$i]["img"] = $link;
                                break;
                            }
                        }
                    }
                    
                    $categories = $item->getCategories();
                    if($categories->count()):
                        $tags = array();
                        foreach($categories as $tag):
                            $tags[] = strtolower($tag["term"]);
                        endforeach;
                        $arr_feed[$i]["category"] =  $tags;
                    endif;
                    
                    $i++;    
                endforeach;
                
            endif;
            
            shuffle($arr_feed);

            $arr_feed = array_slice($arr_feed, 0, 6);
            
            return $arr_feed;
        }
        
        foreach($arr_linkRss as $key => $rss)
        {
            $feedUrl = $this->view->baseUrl()."/$rss/rss/".$arr_tipos[$tipo]."/tipo/atom";
            
            $feed = new Zend_Feed_Reader();
            try
            {
                $feed = $feed->import($feedUrl);
            }
            catch (Exception $es)
            {
               break;
            }
            
            $arr_feed[] = array(
                "title"       => $feed->getTitle(),
                "link"        => $feed->getLink(),
                "published"   => $feed->getDateModified(),
                "description" => $feed->getDescription(),
                "language"    => $feed->getLanguage(),
                "entries"     => array(),
            );

            foreach ($feed as $entry) {

                $canal = 0;
                $type = "";
                $type_id = "";
                $publisher = "";
                $acessos = "";
                $baixados = "";
                $comentarios = "";
                $categoriaid = "";
                $categorianome = "";
                $categoriaurl = "";
                $color = "";
                $votosQtde = 0;
                $votosMedia = 0;
                $color = "";
                $tags = array();

                $categories = $entry->getCategories()->getArrayCopy();
                foreach($categories as $key=>$value){
                    switch ($value["label"]){
                        case "type_id":
                            $type_id = $value["term"];
                            break;
                        
                        case "type":
                            $type = $value["term"];
                            break;

                        case "user":
                            $publisher = $value["term"];
                            break;

                        case "tag":
                            $tags[] = $value["term"];
                            break;

                        case "views":
                            $acessos = $value["term"];
                            break;
                        
                        case "downloads":
                            $baixados = $value["term"];
                            break;
                        
                        case "comments":
                            $comentarios = $value["term"];
                            break;
                        
                        case "categoryid":
                            $categoriaid = $value["term"];
                            break;
                        
                        case "categoryname":
                            $categorianome = $value["term"];
                            break;
                        
                        case "categoryurl":
                            $categoriaurl = $value["term"];
                            break;
                        
                        case "canal":
                            $canal = $value["term"];
                            break;
                        
                        case "votosq":
                            $votosQtde = $value["term"];
                            break;                            
                            
                        case "votosm":
                            $votosMedia = $value["term"];
                            break;                            
                        }
                }

                if($rss == "ambientes-de-apoio"):
                    $color = "amarelo";
                elseif($rss == "tv-anisio-teixeira"):
                        $color = "marron";
                    else:
                        if($type == "Site"):
                            $color = "vermelho";
                        else:
                            $color = ($canal == 1 ? "marron" : "azul");
                        endif;
                    endif;
                
                $edata = array(
                    "type"          => $type,
                    "type_id"       => $type_id,
                    "title"         => $entry->getTitle(),
                    "description"   => $entry->getDescription(),
                    "published"     => $entry->getDateCreated(),
                    "publisher"     => $publisher,
                    "author"        => $entry->getAuthor(),
                    "link"          => $entry->getLink(),
                    "img"           => $entry->getEnclosure(),
                    "color"         => $color,
                    "tags"          => $tags,
                    "acessos"       => $acessos,
                    "baixados"      => $baixados,
                    "comentarios"   => $comentarios,
                    "categoriaid"   => $categoriaid,
                    "categorianome" => $categorianome,
                    "categoriaurl"  => $categoriaurl,
                    "votos-quantidade"  => $votosQtde,
                    "votos-media"  => $votosMedia,
                );

                $arr_feed["entries"][] = $edata;
            }
        }

        if($tipo != 2 && $tipo != 3)
        {
            shuffle($arr_feed["entries"]);
        }
        
        $arr_feed = array_slice($arr_feed["entries"], 0, $qtde_registro);
        
        return $arr_feed;
    }
    
       
    
    /**
     * @return boolean
     */
    public function enviarEmail($email, $mensagem, $assunto, $nome = "", $emailCopia = false)
    {
        $mail = new Sec_Mail();

        $validar = $mail->validarMail($email);
        
        if(!isset($validar))
            return false;

        if(!$validar['0'])
            return false;

        $mail->setBodyHtml($mensagem);
        $mail->setFrom(Sec_Global::getSystemEmail(), 'Ambiente Educacional Web');
        $mail->addTo($email, $nome);
        
        if($emailCopia)
        {
            if(!is_array($emailCopia))
            {
                $mail->addBcc($emailCopia);
            }
            else
            {
                foreach($emailCopia as $email)
                {
                    $mail->addBcc($email['email']);
                }
            }
        }    
        
        $mail->setSubject($assunto);
	try
        {
	    $result = $mail->send();
	} 
        catch (Exception $e)
        {
            return false;
        }
        
        return true;
    }
    
    /**
     * gerar um string aleatoria
     * @param int $size
     * @return string
     */
    public function randString($size=5)
    {
        //String com valor possíveis do resultado, os caracteres pode ser adicionado ou retirados conforme sua necessidade
        $basic = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $return= "";
        
        for($count= 0; $size > $count; $count++){
            //Gera um caracter aleatorio
            $return.= $basic[rand(0, strlen($basic) - 1)];
        }
        
        return $return;
    }
  
    /**
     * Retorna a palavra sem acentuação para melhorar a performance na consulta
     * @return string
     */
    public function retiraAcentuacao($palavra)
    {
    	$a = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
        $b = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';

        $string = utf8_decode($palavra);
        $string = strtr($string, utf8_decode($a), $b); //substitui letras acentuadas por "normais"
        $string = strtolower($string); // passa tudo para minusculo

        return utf8_encode($string);
    }
    
    public function logarAction()
    {
	$this->disableLayout();
	$id = $this->getRequest()->getParam('id', false);
	if($id == null):
            return;
	endif;
        
	$request = Zend_Controller_Front::getInstance();

        $usuarioBo = new Aew_Model_Bo_Usuario();
	$form_login = new Aew_Form_Login();
	if($this->getRequest()->isPost()):
            if($form_login->isValid($this->getRequest()->getPost())):
                if ($session->login_tentativas == NUMERO_TENTATIVAS):
                    $mensagemErro = array("Número de tentativas excedidas. Tente novamente mais tarde.");
                    $this->flashError(array_shift($mensagemErro));
		else:
                    $values = $form_login->getValues();
                    if($values['username'] != "" && $values['senha'] != ""):
                        $result = $usuarioBo->authenticate($form_login->getValues());

                        $mensagemErro = $result->getMessages();
                        if ($result->isValid() == false):
                            $session = new Zend_Session_Namespace('loginTentativas');
                            $session->setExpirationSeconds(30, 'login_tentativas');

                            $session->login_tentativas += 1; // incrementação do número de tentativas
                            
                            // credenciais invalidas
                            $this->flashError(array_shift($mensagemErro));
                        else:
                            // autenticado com sucesso
                            Zend_Session::regenerateId();
                            $this->flashMessage("Bem-vindo! Agora pode comentar nossos conteúdos");
                    endif;	
		endif;
            endif;
	endif;
        endif;
    }

    public function calcularEspacoDisco($tipoEspaco = 0, &$espaco = 0)
    {
        $path = MEDIA_PATH.DS.CONTEUDO_PATH;
        switch($tipoEspaco)
        {
            case 0:
                $bytes = disk_free_space($path); 
                break;

            case 1:
                $bytes = disk_total_space($path); 
                break;

            case 2:
                $bytes = disk_total_space($path) - disk_free_space($path); 
                break;
        }

        $si_prefix = array( 'B', 'KB', 'MB', 'GB', 'TB', 'EB', 'ZB', 'YB' );
        $base = 1024;
        $class = min((int)log($bytes , $base) , count($si_prefix) - 1);

		$espaco = round($bytes/pow($base,$class), 2);
		$bytes  = number_format($espaco, 2, ',', '.');

		return $bytes.' '.$si_prefix[$class];
        //return sprintf('%1.2f' , $bytes / pow($base,$class)).' '.$si_prefix[$class];
    }
}
?>
