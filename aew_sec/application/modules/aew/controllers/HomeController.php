<?php
/**
 * gerencia urls primcipais e gerais do AEW
 */
class HomeController extends Sec_Controller_Action
{
    private $form_login;
    
    /**
     * inicializacao do controller e configuracao dos acessos as actions
     */
    public function init()
    {
        parent::init();
        $acl = $this->getHelper('Acl');
        $administradorAction = array('teste');
        $acl->allow(Aew_Model_Bo_UsuarioTipo::SUPER_ADMINISTRADOR, $administradorAction);
        $visitanteAction = array('denunciar', 'sugerir');
	$acl->allow(Aew_Model_Bo_UsuarioTipo::VISITANTE, $visitanteAction);
    }
    
    /**
     * carrega os scripts js da pagina html
     */
    public function initScripts() 
    {   
        $this->view->headLink()
                        ->appendStylesheet('/assets/plugins/fractionslider/css/custom.css')
                        ->appendStylesheet('/assets/plugins/fractionslider/css/fractionslider.css');
        
        $this->view->headScript()
                        ->appendFile('/assets/plugins/fractionslider/js/jquery.fractionslider.js')
                        ->appendFile('/assets/plugins/fractionslider/js/main.js')
                        ->appendFile('/assets/js/jquery.scrollTo.min.js');
    }
    
    /**
     * @return Zend_View
     */
    public function homeAction()
    {
        $session = new Zend_Session_Namespace('loginTentativas');
        $session->setExpirationSeconds(30, 'login_tentativas');
        
		$usuarioBo = new Aew_Model_Bo_Usuario();
		$request = $this->getRequest();
		$form_login = new Aew_Form_Login();
		$form_login->getElement('enviar')->setLabel('entrar');
        if($request->isPost())
        {
           if($form_login->isValid($request->getPost()))
           {    
               $session->login_tentativas = intval($session->login_tentativas) + 1; // incrementação do número de tentativas
               if ($session->login_tentativas == NUMERO_TENTATIVAS) 
               {
                   $mensagemErro = array("Número de tentativas excedidas. Tente novamente mais tarde.");
                   $this->flashError(array_shift($mensagemErro));
               } 
               else 
               {
                    $result = $usuarioBo->authenticate($form_login->getValues());
                    
                    $mensagemErro = $result->getMessages();
                    if ($result->isValid() == false) 
                    {    
                        // credenciais invalidas
                        $this->flashError(array_shift($mensagemErro));
                    } 
                    else 
                    {
                        Zend_Session::regenerateId();
                        $this->_redirect('usuario/login-sucesso');
                    }
                }
           }
        }

        $this->view->form_login = $form_login;
        $this->view->cor = "preto";
        
        $nivelEnsino = new Aew_Model_Bo_NivelEnsino();
        $nivelEnsino->setId(5);
        $this->view->disciplinas  = $nivelEnsino->selectComponentesCurriculares();
        
        $temasTranversais = new Aew_Model_Bo_CategoriaComponenteCurricular();
        $temasTranversais->setId(3);
        $this->view->temastransversais = $temasTranversais->selectComponentesCurriculares();

		$tiposConteudo = new Aew_Model_Bo_ConteudoTipo();
		$options = array();
		$options['orderBy'] = 'LOWER(sem_acentos(conteudotipo.nomeconteudotipo)) ASC';
		$this->view->tiposConteudo = $tiposConteudo->select(0,0,$options);

		$this->view->buscaSimples = true;
        $this->view->rssDestaques = $this->getRssAEW(0);
        $this->view->rssRecentes  = $this->getRssAEW(1);
        $this->view->rssVistos    = $this->getRssAEW(2, 5, array("conteudos-digitais"));
        $this->view->rssVotados   = $this->getRssAEW(3, 5, array("conteudos-digitais"));
        $this->view->rssPw        = $this->getRssAEW(4, 5, array("conteudos-digitais"));
        
        $this->view->imagens = $this->_extrairFotosExposicao();
    }
   
    /**
     * constroi uma cadeia de elementos li no formato html
     * @param array $objetos
     * @return string
     */
    public function carregaHtml(array $objetos)
    {
        $html = "";
	foreach($objetos as $feed)
        {
            if(!$feed instanceof Sec_Model_Bo_Abstract)
                continue;
            $id = $feed->getId();
            $url_perfil  = $this->view->url(array('module' => 'espaco-aberto', 'controller' => 'perfil','action' => 'exibir', 'usuario' => $feed->getUsuarioremetente()->getId()), null, true);
            $linkFoto = $feed->getUsuarioremetente()->getFotoPerfil()->getFotoCache(Aew_Model_Bo_Foto::$FOTO_CACHE_30X30,false);
            $DataCriacao = $this->view->SetupDate($feed->getDataCriacao(),1);
            if ($feed->getMensagem()!="")
            {
		$html.= '<li id="feed'.$id.'" class="feedBusca" style="display:none">';
		$html.= '   <a href="'.$url_perfil.'">'.$linkFoto.'</a>'.$feed->getMensagem().'<br/><span>'.$DataCriacao.'</span>';
		$html.= '</li>';
            }
	}
	return $html;
    } 
    
    /**
     * cadeia de strings html no formato json
     * @return string
     */
    public function carregaFeedAction()
    {
        $this->getHelper('layout')->disableLayout();
	$idusuario  = $this->getParam('usuario', false);
	$tipofeed = $this->getParam('feed', false);
	$idfeed   = $this->getParam('posicao', false);
        $usuario = new Aew_Model_Bo_Usuario();
        $usuario->setId($idusuario);
        $idfeed_min = 0; $idfeed_max = 0;
	if ($tipofeed == 1)
        { 
            $idfeed_max = $idfeed;
	}
	else
        {
            $idfeed_min = $idfeed;
	}
	$feeds = $usuario->selectFeedsDetalhe(0,0, null,$idfeed_min,$idfeed_max);
	$response['html'] = $this->carregaHtml($feeds);
	echo json_encode($response);
	die();
    }    
    
    /**
     * formulário para envio de denuncias
     * @return Zend_View
     */
    public function denunciarAction()
    {   
        $this->setPageTitle('Denunciar');
        if($this->isAjax())
        {
            $this->disableLayout();
	}
        $form = new Aew_Form_Denunciar();
        if($this->getRequest()->isPost())
        {
            $captcha = New Sec_View_Helper_ReCaptcha();
            if($captcha->validar($this->getRequest()->getPost()))
            {
                $this->getRequest()->setPost("recaptcha","OK");
            }

            if($form->isValid($this->getRequest()->getPost()))
            {
                $this->avisarFaleconosco($form->getValues(), true);

                if($this->enviarFaleconosco($form->getValues(), true))
                {
                    //$denunciaBo = new Aew_Model_Bo_Denuncia();
                    //$result = $denunciaBo->create($form->getValues());
                    $this->flashMessage('Sua denuncia foi enviada com sucesso. No caso de ter fornecido um e-mail, logo estaremos em contato . Muito Obrigado pela ajuda.');
                    $form->reset();
                }
                else
                {
                    $this->flashError('Não foi possível enviar o e-mail');
                }
            }
	}
        if($this->isAjax())
        {
            echo $this->view->headScript()->setFile('/assets/js/plugins/validator/js/bootstrapValidator.min.js');
            echo $this->view->headScript()->setFile('/assets/js/plugins/validator/js/language/pt_BR.js');
            echo $this->view->headScript()->setFile('/assets/js/plugins/validator/js/validaAjaxForm.js');
        }
        $this->view->formDenunciar = $form;
        $this->view->isAjax = $this->isAjax();
    }

    /**
     * formulário fale conosco
     * @return Zend_View
     */
    public function faleconoscoAction()
    {
        $this->setPageTitle('Fale Conosco');
        if($this->isAjax())
        {
            $this->disableLayout();
	}
	$form = new Aew_Form_Faleconosco();
        if($this->getRequest()->isPost())
        {
            $captcha = New Sec_View_Helper_ReCaptcha();
            if($captcha->validar($this->getRequest()->getPost()))
            {
                $this->getRequest()->setPost("recaptcha","OK");
            }
            if($form->isValid($this->getRequest()->getPost()))
            {
                $this->avisarFaleconosco($form->getValues());
                if($this->enviarFaleconosco($form->getValues()))
                {
                    $this->flashMessage('Sua mensagem foi enviada com sucesso, logo estaremos em contato. Muito Obrigado.');                
                    $form->reset();
                }
                else
                {
                    $this->flashError('Não foi possível enviar o e-mail');
                }
            }
	}

        if($this->isAjax())
        {
            echo $this->view->headScript()->setFile('/assets/js/plugins/validator/js/bootstrapValidator.min.js');
            echo $this->view->headScript()->setFile('/assets/js/plugins/validator/js/language/pt_BR.js');
            echo $this->view->headScript()->setFile('/assets/js/plugins/validator/js/validaAjaxForm.js');
        }

        $this->view->formFaleconosco = $form;
        $this->view->isAjax = $this->isAjax();
    }

    /**
     * salva o submissao do formulario fale conosco
     * @param  array $values
     * @param  boolean $denunciar
     * @return boolean
     */
    private function enviarFaleconosco($values, $denunciar = false)
    {
        if($denunciar == true && $values['email'])
        {
            return true;
        }
        $this->setPageTitle(($denunciar ? "Denuncia" : "Fale Conosco"));
        
        $this->view->denunciar = $denunciar;
        
        $assunto = "[AEW] ".($denunciar ? "Denuncia":"Fale Conosco")." - ".ucfirst(strtolower($values['assunto']));
        $mensagem = $this->view->render('faleconosco/faleconosco-resposta.email.php');
        
        $resultado = $this->enviarEmail($values['email'], $mensagem, $assunto, $values['nome']);
        
	return $resultado;
    }    

    /**
     * 
     * @param  array $values
     * @param  boolean $denunciar
     * @return array
     */
    private function avisarFaleconosco($values, $denunciar = false)
    {

        $usuarioBo = new Aew_Model_Bo_Usuario();
        $usuarioBo->setFlativo(true);
        $usuarioBo->getUsuarioTipo()->setNome(Aew_Model_Bo_UsuarioTipo::SUPER_ADMINISTRADOR);
        $usuarios = $usuarioBo->select();
        
	if(!count($usuarios))
            return;
        
        $arrUsuarios = array();
        $i = 0;
        foreach($usuarios as $usuario)
        {
            $arrUsuarios[$i]['id'] = $usuario->getId();
            $arrUsuarios[$i]['nome'] = $usuario->getNome();
            $arrUsuarios[$i]['email'] = $usuario->getEmail();
            $i++;
	}
        
        $email = Sec_Global::getSystemEmail();
        
        $nome  = "Ambiente Educacional Web";
        $faleconosco = array('nome' => $values['nome'],
                                'email' => $values['email'],
                                'mensagem' => $values['mensagem']);
        
        $this->view->faleconosco = $faleconosco;
        $this->view->denunciar = $denunciar;
        
        $email = Sec_Global::getSystemEmail();
        $assunto = "[AEW] ".($denunciar ? "Denuncia":"Fale Conosco")." - ".ucfirst(strtolower($values['assunto']));
        
        $mensagem = $this->view->render('faleconosco/faleconosco-aviso.email.php');
        $resultado = $this->enviarEmail($email, $mensagem, $assunto, $nome, $arrUsuarios);
        
	return $resultado;        
    }
    
    /**
     * imprime string no formato json dos dados de validacao
     * @return string
     */
    public function validarEmailAction()
    {
        $email = $this->getParam('remetente','');
        $mail = new Sec_Mail();
        $resultado = $mail->validarMail($email);
        echo json_encode($resultado['code']);
        die();
    }	
    
    /**
     * retorna um array de objetos Aew_Model_Bo_Foto
     * com os dados das fotos de destaque
     * @return array
     */
    function getFotosDestaques()
    {
        $fotos = array();	
        $dir = "img/home/bg/";	
        foreach(glob($dir."*.jpg") as $arquivo)
        {
            $foto = new Aew_Model_Bo_Foto($arquivo);
            $foto->selectMetaDados();
            array_push($fotos, $foto);
        }
        
        return $fotos;
    }

    /**
     * exibe pagina informativa dos modulos do AEW
     * @return Zend_View
     */
    public function sobreAction()
    {
        $this->setPageTitle('Sobre o Ambiente Educacional Web');
        $this->view->urlConteudosDigitais = $this->view->url(array('module' => 'aew', 'controller' => 'home', 'action' => 'sobre-conteudos-digitais'), null, true);
	$this->view->urlSitesTematicos = $this->view->url(array('module' => 'aew', 'controller' => 'home', 'action' => 'sobre-sites-tematicos'), null, true);
        $this->view->urlAmbientesApoio = $this->view->url(array('module' => 'aew', 'controller' => 'home', 'action' => 'sobre-ambientes-apoio'), null, true);
	$this->view->urlEspacoAberto = $this->view->url(array('module' => 'aew', 'controller' => 'home', 'action' => 'sobre-espaco-aberto'), null, true);
        $this->view->urlProfessorWeb = $this->view->url(array('module' => 'aew', 'controller' => 'home', 'action' => 'sobre-professor-web'), null, true);
        $this->view->urlTvAnisioTeixeira = $this->view->url(array('module' => 'aew', 'controller' => 'home', 'action' => 'sobre-tv-anisio-teixeira'), null, true);
        $this->view->urlSobreFotos = $this->view->url(array('module' => 'aew', 'controller' => 'home', 'action' => 'sobre-fotos'), null, true);
    }

    /**
     * exibe pagina informativa do modulo conteudos-digitais
     * @return Zend_View
     */
    public function sobreConteudosDigitaisAction()
    {
        $paginaPai[] = array('titulo' => 'Sobre o AEW', 'url' => '/home/sobre');
        $this->view->paginaPai = $paginaPai;
        $this->setPageTitle("Sobre os Conteúdos Digitais");
        $this->view->corfundo = "bgcolor='menu-azul'";
        $this->view->corfonte = "fcolor='menu-azul'";
    }

    /**
     * exibe pagina informativa do modulo sites-tematicos
     * @return Zend_View
     */
    public function sobreSitesTematicosAction()
    {
        $paginaPai[] = array('titulo' => 'Sobre o AEW', 'url' => '/home/sobre');
        $this->view->paginaPai = $paginaPai;
        $this->setPageTitle("Sobre os Sites Temáticos");
        $this->view->corfundo = "bgcolor='menu-vermelho'";
        $this->view->corfonte = "fcolor='menu-vermelho'";
    }

    /**
     * exibe pagina informativa do modulo ambientes-apoio
     * @return Zend_View
     */
    public function sobreAmbientesApoioAction()
    {
        $paginaPai[] = array('titulo' => 'Sobre o AEW', 'url' => '/home/sobre');
        $this->view->paginaPai = $paginaPai;
        $this->setPageTitle("Sobre o Apoio a Produção e Colaboração");
        $this->view->corfundo = "bgcolor='menu-amarelo'";
        $this->view->corfonte = "fcolor='menu-amarelo'";
    }
    
    /**
     * exibe pagina informativa do modulo espaco-aberto
     * @return Zend_View
     */
    public function sobreEspacoAbertoAction()
    {
        $paginaPai[] = array('titulo' => 'Sobre o AEW', 'url' => '/home/sobre');
        $this->view->paginaPai = $paginaPai;
        $this->setPageTitle('Sobre o Espaço Aberto');
        $this->view->corfundo = "bgcolor='menu-verde'";
        $this->view->corfonte = "fcolor='menu-verde'";
    }

    /**
     * pagina informativa sobre as fotos do AEW
     * @return Zend_View
     */
    public function sobreFotosAction()
    {
        $paginaPai[] = array('titulo' => 'Sobre o AEW', 'url' => '/home/sobre');
        $this->view->paginaPai = $paginaPai;
        $this->setPageTitle('Sobre a exposição fotográfica');
    }

    public function creditosAction()
    {
        
    }
    
    /**
     * pagina de condicoes de uso
     * @return Zend_View
     */
    public function termoCondicoesUsoAction()
    {
        $this->setPageTitle('Termos e Condições de Uso');
    }
    
    /**
     * @return Zend_View
     */
    public function exposicaoFotosAction()
    {
        $this->view->ocultar = 'hidden';
        $this->view->linkCor = 'link-branco';
        $this->view->fixoAbaixo = 'bottom-rodape';
        $this->view->isContainer = true;
        
        $this->_helper->layout->setLayout('home/layout-home');

        $template = " 
                    $(function(){
                        jQuery('#maximage').maximage({cycleOptions:
                            {	fx      : 'fade',
                                speed   : 2000,
                                timeout : 2000,
                                prev    : '#arrow_left',
                                next    : '#arrow_right',
                                pause   : 1
                            }});
                    });";
        
        $this->view->headLink()
                ->appendStylesheet('/assets/js/plugins/maximage/jquery.maximage.css');
        
        $this->view->headScript()->appendFile('/assets/js/plugins/maximage/jquery.maximage.js')
                ->appendFile('/assets/js/plugins/maximage/jquery.cycle.all.min.js')
                ->appendScript($template,'text/javascript');
        
        $this->view->imagens = $this->_extrairFotosExposicao();
    }
    
    /**
     * extrai fotos do diretorio
     * @return type
     */
    private function _extrairFotosExposicao()
    {
        $meses = array("JAN","FEV","MAR","ABR","MAI","JUN","JUL","AGO","SET","OUT","NOV","DEZ");
        $imagens = array();
        $fonte = "assets/img/bg";
        $i=0;

        $mes_atual = $meses[intVal(date("m"))-1];
        $imagens = glob("$fonte/$mes_atual/*.jpg");

        if(!count($imagens)):
            $imagens = glob("$fonte/GERAL/*.jpg");
        endif;

        shuffle($imagens);
        
        return $imagens;
    }
    
    /**
     * imprime as tags no formato json
     * @return string
     */
    public function sugerirAction()
    {
        $stringTags = array();

        $term   = $this->getRequest()->getParam("term", "");
        $filter = $this->getRequest()->getParam("by", "");
        $all    = $this->getRequest()->getParam("all", false);

        //--- Nuvens de conteúdos
        if($filter == "titulo")
        {
            $conteudosBo = new Aew_Model_Bo_ConteudoDigital();
            $options = array();
            $options["where"]['LOWER(sem_acentos(conteudodigital.titulo)) LIKE LOWER(sem_acentos(?))'] = "%$term%";
            $options["where"]['conteudodigital.flaprovado = ?'] = true;
            $options["orderBy"] = "conteudodigital.titulo ASC";

            $conteudos = $conteudosBo->select(0, 0, $options);
            if($conteudos)
            {
                foreach($conteudos as $conteudo)
                {
                    if($conteudo->getTitulo())
                    {
                        $nomeTitulo = $conteudo->getTitulo();
                        
                        $nomeTitulo = str_replace('"',"'",$nomeTitulo);
                        $nomeTitulo = str_replace('”',"'",$nomeTitulo);
                        $nomeTitulo = str_replace('“',"'",$nomeTitulo);

                        $stringTags[] = $nomeTitulo;
                    }
                }
            }
        }
        
        //--- Nuvens de tag
        if($filter == "tag")
        {
            $tagsBo = new Aew_Model_Bo_Tag();
            $options = array();
            $options['where']["tag.nometag NOT LIKE '%lote%' AND tag.nometag NOT LIKE CONCAT('%',CHR(34),'%') AND tag.nometag NOT LIKE CONCAT('%',CHR(39),'%') AND tag.nometag NOT LIKE CONCAT('%',CHR(13),'%') AND nometag = regexp_replace(nometag, '[.;:]','')"] = "";
            $options["where"]["LOWER(sem_acentos(tag.nometag)) LIKE LOWER(sem_acentos(?))"] = "%$term%";
			if($all == false)
			{
			    $options['where']["EXISTS(SELECT * FROM conteudodigitaltag WHERE conteudodigitaltag.idtag = tag.idtag)"] = "";
			}

            $options['orderBy'] = 'tag.nometag ASC';

            $tags = $tagsBo->select(0,0, $options);
            if($tags)
            {
                foreach($tags as $tag)
                {
                    if($tag->getNome())
                    {
                        $nomeTag = str_replace(chr(34), '', $tag->getNome());
                        $nomeTag = str_replace(chr(39), '', $tag->getNome());
                        $nomeTag = str_replace(chr(13), '', $tag->getNome());
                        
                        $stringTags[] = $nomeTag;
                    }
                }
            }
        }

        echo json_encode($stringTags);
        die();
    }
}
