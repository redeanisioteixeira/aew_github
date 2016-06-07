<?php
require_once '../application/cache/Cache_Class.php';

class Sec_Controller_Action_EspacoAberto extends Sec_Controller_Action 
{
    /**
     * Guarda o tipo da pagina (comunidade / usuario)
     * @var string
     */
    protected $tipoPagina;

    /**
     * Guarda o perfil da pagina (comunidade / usuario)
     * @var array
     */
    protected $perfil;

    /**
     * Guarda o tipo de perfil da pagina (comunidade / usuario)
     * @var array
     */
    protected $perfilTipo;

    /**
     * Guarda o id do perfil da pagina (comunidade / usuario)
     * @var int
     */
    protected $perfilId;

    /**
     * Guarda se o usuario é dono do perfil da pagina (comunidade / usuario)
     * @var bool
     */
    protected $perfilDono;

    /**
     * Guarda se o usuario é moderador do perfil da pagina (comunidade)
     * @var bool
     */
    protected $perfilModerador;

    /**
     * Guarda se o usuario é moderador do perfil da pagina (comunidade)
     * @var Sec_Form
     */
    protected $formPesquisa;

    public function init()
    {
        parent::init();
    	$form = new EspacoAberto_Form_FotoPerfil();
        
        $this->view->trocarImagemForm   = $form;
        $this->usuarioPerfil            = $this->getPerfiluserObject();
        $this->usuarioLogado            = $this->getLoggedUserObject();
    }
    
    public function listarFavoritosAction()
    {
        $perfilUsuario = $this->getPerfiluserObject();
        $usuario = $this->getLoggedUserObject();
        
        $conteudosFavoritos = $perfilUsuario->selectConteudosDigitaisFavoritos();
        $ambientesFavoritos = $perfilUsuario->selectAmbientesDeApoioFavotitos();
        
        $this->view->conteudosFavoritos = $conteudosFavoritos;
        $this->view->ambientesFavoritos = $ambientesFavoritos;
    } 
    
    /**
     * @return the $formPesquisa
     */
    public function getFormPesquisa()
    {
        return $this->formPesquisa;
    }

    /**
     * @param $formPesquisa the $formPesquisa to set
     */
    public function setFormPesquisa($formPesquisa)
    {
        $this->formPesquisa = $formPesquisa;
    }

    /**
     * @return the $perfilModerador
     */
    public function getPerfilModerador()
    {
        return $this->perfilModerador || $this->isAllowed('espaco-aberto', 'administrar');
    }

    /**
     * @param $perfilModerador the $perfilModerador to set
     */
    public function setPerfilModerador($perfilModerador)
    {
        $this->perfilModerador = $perfilModerador;
    }

    /**
     * @return the $perfilDono
     */
    public function getPerfilDono()
    {
	return $this->perfilDono || $this->isAllowed('espaco-aberto', 'administrar');
    }

    /**
     * @param $perfilDono the $perfilDono to set
     */
    public function setPerfilDono($perfilDono) {
	$this->perfilDono = $perfilDono;
    }

    /**
     * @return the $perfil_tipo
     */
    public function getPerfilTipo() {
	return $this->perfilTipo;
    }

    /**
     * @return the $perfil_id
     */
    public function getPerfilId() {
	return $this->perfilId;
    }

    /**
     * @param $perfil_tipo the $perfil_tipo to set
     */
    public function setPerfilTipo($perfil_tipo) {
	$this->perfilTipo = $perfil_tipo;
    }

    /**
     * @param $perfil_id the $perfil_id to set
     */
    public function setPerfilId($perfil_id) {
	$this->perfilId = $perfil_id;
    }

    /**
     * @return the $tipoPagina
     */
    public function getTipoPagina() {
	return $this->tipoPagina;
    }

    /**
     * @return the $perfil
     */
    public function getPerfil() {
	return $this->perfil;
    }

    /**
     * @param $tipoPagina the $tipoPagina to set
     */
    public function setTipoPagina($tipoPagina) {
	$this->tipoPagina = $tipoPagina;
    }

    /**
     * @param $perfil the $perfil to set
     */
    public function setPerfil($perfil) {
	$this->perfil = $perfil;
    }

    /**
     * retorna a parte da url com as informações do perfil (/comunidade/12)
     * @return string
	 */
    public function getPerfilUrl() {
        if($this->getPerfilTipo() == null)
        {
            return '';
        }
	return '/'.$this->getPerfilTipo().'/'.$this->getPerfilId();
    }

    /**
     * @return the $_linkExibicao
     */
    public function getLinkExibicao($id = '', $insertPerfilUrl = true) 
    {
	$url = $this->_linkExibicao . $id;
	if($insertPerfilUrl)
        {
	    $url .= $this->getPerfilUrl();
	}
	return $url;
    }

    /**
     * @return the $_linkListagem
     */
    public function getLinkListagem() {
	return $this->_linkListagem . $this->getPerfilUrl();
    }

    /**
     * @return the $_actionApagar
     */
    public function getActionApagar($id = '') {
	return $this->_actionApagar . $id . $this->getPerfilUrl();
    }

    /**
     * 
     * @param Aew_Model_Bo_ItemPerfil $boPerfil
     * @param Aew_Model_Bo_Usuario $usuario
     */
    public function carregarPerfil($forcarUsuario = false)
    {
        $dono = false;
        $moderador = false;

        $boPerfil = $this->getPerfiluserObject();
        $usuario  = $this->getLoggedUserObject();
        
        if(!$boPerfil)
        {
            $this->flashError('Perfil não encontrada');
            $this->_redirect('espaco-aberto');
        }
        
        if(($boPerfil instanceof Aew_Model_Bo_Comunidade) && false == $forcarUsuario)
        {
            $tipoPagina = Sec_Constante::COMUNIDADE;
            $dono = $boPerfil->isDono($usuario);
            $moderador = $boPerfil->isModerador($usuario);
            $this->view->perfil_moderadores = $boPerfil->selectModeradores();
            //$this->view->comunidade = $boPerfil;

        } 
        else if($boPerfil instanceof Aew_Model_Bo_Usuario) 
        {
            $tipoPagina = Sec_Constante::USUARIO;
            $dono = ($boPerfil->getId() == $usuario->getId() ? true : false);
            
            $this->view->redesSociais = $boPerfil->selectRedesSociais();
            $this->view->colegas = $boPerfil->selectColegas();
            $this->view->comunidades = $boPerfil->selectComunidadesParticipo(); 
            $this->view->blogs = $boPerfil->selectBlogs(); 
            $this->view->recados = $boPerfil->selectRecadosRecebidos(5);
            $this->view->albuns = $boPerfil->selectAlbuns(5);
        }
        
        $this->view->perfilTipo = $boPerfil->perfilTipo();
        $this->view->perfilId = $boPerfil->getId();
        
        $this->setPerfilDono($dono);
        $this->setTipoPagina($tipoPagina);
        $this->setPerfilTipo($this->view->perfilTipo);
        $this->setPerfilId($this->view->perfilId);
        $this->setPerfilModerador($moderador);
        
        $this->view->tipoPagina = $tipoPagina;
	$this->view->perfilDono = $dono;
        $this->view->perfilModerador = $moderador; 
        $this->view->usuarioPerfil = $boPerfil; 
        
        $this->setFormPesquisa(new EspacoAberto_Form_Buscar());
        if($this->getParam('controller') == 'pesquisa')
        {
	    $form = $this->getFormPesquisa();
	    $form->populate($this->getParams());
	    $form->populate(array('tipo' => $this->getParam('action')));
	}
        
	$this->view->formPesquisa = $this->getFormPesquisa();
    }

    /**
     *	Retorna tipop de navegador
     */
    public function getBrowser()
    { 
        $u_agent = $_SERVER['HTTP_USER_AGENT']; 
        $bname = 'Unknown';
        $platform = 'Unknown';
        $version= "";

        //First get the platform?
        if (preg_match('/linux/i', $u_agent)) {
    	   $platform = 'linux';
        }
        elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
    	   $platform = 'mac';
        }
        elseif (preg_match('/windows|win32/i', $u_agent)) {
    	   $platform = 'windows';
        }
    
        // Next get the name of the useragent yes seperately and for good reason
	    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) 
	    { 
		   $bname = 'Internet Explorer'; 
		   $ub = "MSIE"; 
	    } 
	    elseif(preg_match('/Firefox/i',$u_agent)) 
	    { 
		   $bname = 'Mozilla Firefox'; 
		   $ub = "Firefox"; 
	    } 
	    elseif(preg_match('/Chrome/i',$u_agent)) 
	    { 
		   $bname = 'Google Chrome'; 
		   $ub = "Chrome"; 
	    } 
	    elseif(preg_match('/Safari/i',$u_agent)) 
	    { 
		   $bname = 'Apple Safari'; 
		   $ub = "Safari"; 
	    } 
	    elseif(preg_match('/Opera/i',$u_agent)) 
	    { 
		   $bname = 'Opera'; 
		   $ub = "Opera"; 
	    } 
	    elseif(preg_match('/Netscape/i',$u_agent)) 
	    { 
		   $bname = 'Netscape'; 
		   $ub = "Netscape"; 
	    } 
	    
	    // finally get the correct version number
	    $known = array('Version', $ub, 'other');
	    $pattern = '#(?<browser>' . join('|', $known) .
	    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
	    if (!preg_match_all($pattern, $u_agent, $matches)) {
		   // we have no matching number just continue
	    }
	    
	    // see how many we have
	    $i = count($matches['browser']);
	    if ($i != 1) {
		   //we will have two since we are not using 'other' argument yet
		   //see if version is before or after the name
		   if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
		       $version= $matches['version'][0];
		   }
		   else {
		       $version= $matches['version'][1];
		   }
	    }
	    else {
		   $version= $matches['version'][0];
	    }
	    
	    // check if we have a number
	    if ($version==null || $version=="") {$version="?";}
			 return array(
					'userAgent' => $u_agent,
					'name'      => $bname,
					'version'   => $version,
					'platform'  => $platform,
					'pattern'    => $pattern
			    );
    }
    /**
    *	Retorna atributo da imagem
    */
    public function adicionarAmbienteFavorito(Aew_Model_Bo_AmbienteDeApoio $ambiente,  Aew_Model_Bo_ItemPerfil $item)
    {
	$texto_comunidade = "";
	$this->carregarPerfil();
	if(false == $this->getPerfilModerador()){
            $this->flashError('Você não possui permissão para executar essa ação.');
            return;
	}
	if($item->perfilTipo() == Sec_Constante::COMUNIDADE)
        {
            $texto_comunidade = $this->view->url(array('module' => 'espaco-aberto', 'controller' => 'comunidade','action' => 'exibir', 'comunidade' => $this->getPerfilId()),null, true);
            $texto_comunidade = " da comunidade <a href='".$texto_comunidade."'>".$item->getNome()."</a>";
	}
        if($item->selectAmbientesDeApoioFavotitos(1,0, $ambiente))
        {
            $this->flashMessage('Este conteudo ja pertence a sua lista de favoritos');
            return ;
        }
        else
        {
            $result = $item->insertAmbienteFavorito($ambiente);
        }
	if($result == true)
        {
            $this->flashMessage('Conteúdo adicionado à lista de favoritos'.$texto_comunidade);
            $link = $this->view->url(array('module' => 'ambientes-de-apoio', 'controller' => 'conteudo','action' => 'exibir', 'id' => $ambiente->getId()),null, true);
	} 
        else 
        {
            $this->flashError('Não foi possível adicionar o conteúdo  à lista de favoritos'.$texto_comunidade);
            $link = $this->view->url(array('module' => 'ambientes-de-apoio', 'controller' => 'ambientes','action' => 'categorias'));
	}
        return $link;
    }
    
    public function adicionarFavorito(Aew_Model_Bo_ConteudoDigital $conteudo,  Aew_Model_Bo_ItemPerfil $item)
    {
	$texto_comunidade = "";
	$this->carregarPerfil();
	if(false == $this->getPerfilModerador()){
            $this->flashError('Você não possui permissão para executar essa ação.');
            return;
	}
	if($item->perfilTipo() == Sec_Constante::COMUNIDADE)
        {
            $texto_comunidade = $this->view->url(array('module' => 'espaco-aberto', 'controller' => 'comunidade','action' => 'exibir', 'comunidade' => $this->getPerfilId()),null, true);
            $texto_comunidade = " da comunidade <a href='".$texto_comunidade."'>".$item->getNome()."</a>";
	}
        if($item->isConteudoFavorito($conteudo))
        {
            $this->flashMessage('Este conteudo ja pertence a sua lista de favoritos');
            return ;
        }
        else
        {
            $result = $item->insertConteudoFavorito($conteudo);
        }
	if($result == true)
        {
            $this->flashMessage('Conteúdo adicionado à lista de favoritos'.$texto_comunidade);
            $link = $this->view->url(array('module' => 'conteudos-digitais', 'controller' => 'conteudo','action' => 'exibir', 'id' => $conteudo->getId()),null, true);
	} 
        else 
        {
            $this->flashError('Não foi possível adicionar o conteúdo  à lista de favoritos'.$texto_comunidade);
            $link = $this->view->url(array('module' => 'conteudos-digitais', 'controller' => 'conteudos','action' => 'lista'));
	}
        return $link;
    }

    public function removerFavorito(Aew_Model_Bo_ConteudoDigital $conteudo,  Aew_Model_Bo_ItemPerfil $item)
    {
	$texto_comunidade = "";
	$this->carregarPerfil();
        $result = false;
	if(false == $this->getPerfilModerador())
        {
            $this->flashError('Você não possui permissão para executar essa ação.');
            return;
	}
	if($item->perfilTipo() == Sec_Constante::COMUNIDADE)
        {
            $nome_comunidade = $item->getNome();
            $texto_comunidade = $this->view->url(array('module' => 'espaco-aberto', 'controller' => 'comunidade','action' => 'exibir', 'comunidade' => $this->getPerfilId()),null, true);
            $texto_comunidade = " da comunidade <a href='".$texto_comunidade."'>".$nome_comunidade."</a>";
	}
        if($item->selectConteudosDigitaisFavoritos(1,0, $conteudo))
        {
            $result = $item->deleteConteudoDigitalFavorito($conteudo);
        }
        else
        {
            $this->flashMessage('Este conteudo nao pertence a sua lista de favoritos');
        }
        if($result)
        {
            $this->flashMessage('Conteúdo removido da lista de favoritos'.$texto_comunidade);
	} 
        else 
        {
            $this->flashError('Não foi possível remover o conteúdo da lista de favoritos'.$texto_comunidade);
	}
    }

    function formatar($val, $mask)
    {
        $maskared = '';
	$k = 0;
	for($i = 0; $i<=strlen($mask)-1; $i++):
            if($mask[$i] == '#'):
                if(isset($val[$k])):
                    $maskared .= $val[$k++];
                endif;	
            else:
             if(isset($mask[$i])):
					 $maskared .= $mask[$i];
				endif;
			 endif;
		 endfor;
		 return $maskared;
    }

    /**
    * Função para validar URIs
    * @param        string $uri URI que deseja validar
    * @return      bool true caso seje válido, false caso contrário
    */

    function checkURI($uri)
    {
        return (@fclose(@fopen($uri, 'r'))) ? true : false;
    }
        
    function initScripts() 
    {
        $this->view->headScript()->appendFile('/assets/js/jquery.form.js','text/javascript')
            ->appendFile('/assets/js/espaco-aberto/feed.js','text/javascript')
            ->appendFile('/assets/js/componente.js','text/javascript')
            ->appendFile('/assets/js/functions-load-ajax.js','text/javascript');
    }
}