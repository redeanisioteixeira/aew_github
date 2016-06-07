<?php
class Sec_View_Helper_ShowVisualizar
{
    protected $extensaovalida = array(1 => "mp4", 2 => "flv", 3 => "webm", 4 => "aac", 5 => "mp3", 6 => "vorbis", 7 => "ogg", 8 => "swf", 9 => "link", 10 => "avi", 11 => "gif", 12 => "jpg", 13 => "zip", 14 => "pdf", 15 => "ods");
    protected $extensaonaovalidavisualizar = array(1 => "avi", 2 => "zip", 3 => "doc", 4 => "ods", 5 => "mpg");
    protected $siteValido = array("tvescola" => "tvescola.mec.gov.br","irdeb" => "irdeb.ba.gov.br", "dominiopublico" => "dominiopublico.gov.br", "youtube" => "www.youtube.com", "objetomec" => "objetoseducacionais2.mec.gov.br");
    protected $view;
    protected $pathArquivo;
    
    function getPathArquivo() {
        return $this->pathArquivo;
    }

    function setPathArquivo($pathArquivo) {
        $this->pathArquivo = $pathArquivo;
    }
    
    public function setView($view)
    {
        $this->view = $view;
    }

    public function ShowVisualizar(Aew_Model_Bo_ConteudoDigital $conteudo, $pathArquivo = null, $tipo = 0)
    {
        $this->setPathArquivo($pathArquivo);
        
        $tipo = ($tipo ? $tipo : $conteudo->getFormato()->getConteudoTipo()->getId());
        $site = $conteudo->getSite();

        $result = "";
        switch($tipo):

            case Aew_Model_Bo_ConteudoTipo::$ANIMACAO_SIMULACAO:
                $result = $this->visualizarAnimacao($conteudo);
                break;

            case Aew_Model_Bo_ConteudoTipo::$DOCUMENTO_EXPERIMENTO:
                $result = $this->visualizarDocumento($conteudo);
                break;

            case Aew_Model_Bo_ConteudoTipo::$PLANILHA:
                $result = $this->visualizarDocumento($conteudo);
                break;

            case Aew_Model_Bo_ConteudoTipo::$SEQUENCIA_DIDATICA:
                $result = $this->visualizarDocumento($conteudo);
                break;

            case Aew_Model_Bo_ConteudoTipo::$SOFTWARE_EDUCACIONAL:
                $result = $this->visualizarAnimacao($conteudo);
                break;

            case Aew_Model_Bo_ConteudoTipo::$IMAGEM:
                $result = $this->visualizarImagem($conteudo);
                break;
            
            case Aew_Model_Bo_ConteudoTipo::$VIDEO:
                switch($this->verificaSiteValido($site)):
                    case "tvescola": 
                        $result = $this->visualizarTvEscola($conteudo);
                        break;

                    case "youtube":
                        $result = $this->visualizarYoutube($conteudo);
                        break;
                    
                    case "dominiopublico":
                        $result = $this->visualizarDominioPublico($conteudo);
                        break;

                    case "irdeb":
                        $result = $this->visualizarIrdeb($conteudo, "video");  
                        break;
                        
                    default:
                        $result = $this->visualizarVideo($conteudo); 
                        break;
                        
                endswitch;
                break;
                
            case Aew_Model_Bo_ConteudoTipo::$AUDIO:
                switch($this->verificaSiteValido($site)):
                    
                    case "irdeb":
                        $result = $this->visualizarIrdeb($conteudo, "audio");
                        break;
                    
                    default:
                        $result = $this->visualizarAudio($conteudo);
                        break;
                    
                endswitch;
                break;
            
        endswitch;
        
        if(!$result):
            $result = "<div class='col-lg-12'>$result</div>";
        endif;

        return $result;
    }

    private function verificaSiteValido($site)
    {
        $valido = ""; 
        foreach($this->siteValido as $key => $value):
            $pos = strpos($site, $value);
            if($pos):
                $valido = $key;
            endif;
        endforeach;
        
        return $valido;
    }
    
    private function visualizarImagem(Aew_Model_Bo_ConteudoDigital $conteudo)
    {
        $id = $conteudo->getId();
        $link = "";
        $arquivo = "";
        $download = "";
        $site = $conteudo->getSite();
        
        if($site)
        {
            $formato = $conteudo->extrairContentType($site);
        }
        else
        {
            $link = $conteudo->getConteudoVisualizar($arquivo, $download);
        }
        
        $div  = "<div class='col-lg-12 shadow rounded'>";
        $div .= "   <center>";
        $div .= "      <img class='img-responsive-conteudo' src='$link'>";
        $div .= "   </center>";
        $div .= "</div>";

        $div .= $this->visualizarAtributos($conteudo, $arquivo, $download);
        
        return $div;        
        
    }

    private function visualizarDocumento(Aew_Model_Bo_ConteudoDigital $conteudo)
    {
        $arr_extensao = array(
                            "pdf"  => "application/pdf",
                            "doc"  => "application/msword",
                            "docx" => "application/msword",
                            "xls"  => "application/vnd.ms-excel",
                            "ppt"  => "application/vnd.ms-powerpoint",
                            "odt"  => "application/vnd.oasis.opendocument.text",
                            "txt"  => "text/plain; charset=UTF-8",
                            "html" => "text/html; charset=UTF-8",
                        );

        $link = "";
        $arquivo = "";
        $download = "";

        if($this->getPathArquivo())
        {
            $link = $this->getPathArquivo();
        }
        else
        {
            $site = $conteudo->getSite();

            if($site)
            {
                $formato = $conteudo->extrairContentType($site);

                if(!is_array($formato))
                {
                        return;
                }

                if(!array_search($formato['type'],$arr_extensao))
                {
                        return;
                }

				$link = $arquivo = $site;
            }
            else
            {
                $link = $conteudo->getConteudoVisualizar($arquivo, $download);
            }
        }

        $div = "";
        $formato = explode(".", $link);
        $formato = end($formato);

        if(array_search($formato, $this->extensaovalida) && !array_search($formato, $this->extensaonaovalidavisualizar))
        {
            $div .= "<div class='shadow rounded embed-responsive embed-responsive-4by3'>";
            $div .= "   <iframe class='embed-responsive-item' src='$link' frameborder='0' allowfullscreen></iframe>";
            $div .= "</div>";
        }
        
        $div .= $this->visualizarAtributos($conteudo, $arquivo, $download);
        
        return $div;        
    }
    
    private function visualizarAnimacao(Aew_Model_Bo_ConteudoDigital $conteudo)
    {
        $id = $conteudo->getId();
        $link = "";
        $arquivo = "";
        $download = "";
        $formato = "";
        $site = $conteudo->getSite();
        
        if($this->getPathArquivo())
        {
            $link = $this->getPathArquivo();
        }
        else
        {
        if($site)
        {
            $formato = $conteudo->extrairContentType($site);
            if($formato["type"] == "application/x-shockwave-flash" || $formato["type"] == "application/octet-stream")
            {
                $link = addslashes(htmlentities(strip_tags($site)));
            }
        }
        else
            {
				$link = $conteudo->getConteudoVisualizar($arquivo, $download, $formato);

                if($conteudo->getFormato()->getNome() != "swf" && $conteudo->getFormatoDownload()->getNome() != "swf")
                {	
                    return $this->visualizarAtributos($conteudo, $arquivo, $download);
                }

                $link = $conteudo->getConteudoVisualizar($arquivo, $download, $formato);
            }
        }
	
        if(!$link){
            return;
        }
        
        $div  = '';
        $formato = explode('.', $link);
        $formato = end($formato);
        
        if(array_search($formato, $this->extensaovalida) && !array_search($formato, $this->extensaonaovalidavisualizar))
        {
            $div .= "<div class='embed-responsive embed-responsive-4by3 shadow rounded'>";
		    $div .= "   <div id='conteudo-flash-$id' class='text-center'>";
		    $div .= "      <h4><b>Seu navegador não possui flash</b></h4>";
		    $div .= "      <p><a href='http://www.adobe.com/go/getflashplayer'><img src='http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif' alt='Get Adobe Flash player' /></a></p>";
		    $div .= "   </div>";
		    $div .= "</div>";

		    $template = "swfobject.embedSWF('$link', 'conteudo-flash-$id', '0', '0', '9.0.0', '/assets/plugins/swfobject/expressInstall.swf',null,{allowfullscreen:'true',wmode:'transparent'},{class:'embed-responsive-item'})";

		    $view = new Zend_View();

		    $ajax = new Zend_Controller_Request_Http();
		    if($ajax->isXmlHttpRequest())
			{
	            echo $view->headScript()->setFile('/assets/plugins/swfobject/swfobject.js');
	            echo $view->headScript()->setScript($template,'text/javascript');
			}
			else
			{	
	            $view->headScript()->appendFile('/assets/plugins/swfobject/swfobject.js');
	            $view->headScript()->appendScript($template,'text/javascript');
			}
        }

        $div .= $this->visualizarAtributos($conteudo, $arquivo, $download);

        return $div;
    }
    
    private function visualizarVideo(Aew_Model_Bo_ConteudoDigital $conteudo, $link = "")
    {
        $ajax = new Zend_Controller_Request_Http();

        $formato = "";
        $imagemPoster = "";
        $arquivo = "";
        $download = false;
        $arquivo = "";
        
        if(!$link):
            if($this->getPathArquivo())
            {
                $link = $this->getPathArquivo();
            }
            else
            {
                $link = $conteudo->getConteudoVisualizar($arquivo, $download, $formato);
                if(!$link):
                   return;
                endif;
            }
        endif;
        
        $formato = explode(".", $link);
        $formato = end($formato);
        $arquivo = $link;
        

        if(!array_search($formato, $this->extensaovalida)):
            return;
        endif;

        if($formato == "flv"):
            $div = $this->visualizarPlayerFlash($conteudo);
        else:
            if(!array_search($formato, $this->extensaonaovalidavisualizar)):
                $imagemPoster = $conteudo->getConteudoImagem();

                $preload = (!$ajax->isXmlHttpRequest() ? "preload='auto'" : "");
                $preload = "";

                $div  = "<video id='video-html5' class='shadow rounded' poster='$imagemPoster' width='100%' height='100%' controls ".$preload.">";
                $div .= "   <source src='$link' type='video/$formato'>Seu navegador não suporta a tag video";
                $div .= "</video>";
            endif;
        endif;

        $div .= $this->visualizarAtributos($conteudo, $arquivo, $download);
        
        return $div;    
    }

    private function visualizarAudio(Aew_Model_Bo_ConteudoDigital $conteudo)
    {
        $imagemPoster = "";
		$download = "";
 		$arquivo = "";

        $id = $conteudo->getId();

        if($this->getPathArquivo())
        {
            $link = $this->getPathArquivo();
        }
        else
        {
    	    $formato = $conteudo->getFormato()->getNome();
    	    if(array_search($formato, $this->extensaovalida) == false)
    	    {
    	        return;
    	    }

			$formato = ($formato == 'link' ? $conteudo->getFormatoDownload()->getNome() : $formato);

	        $arquivo = "";
	        $download = "";

	        $link = $conteudo->getConteudoVisualizar($arquivo, $download);

	        if(!$link)
	        {
	            return;
	        }
        }

		$div = "";
		$extensao = end(explode('.',$link));
        if(array_search($extensao, $this->extensaovalida) == true && !array_search($extensao, $this->extensaonaovalidavisualizar))
		{
		    $div  = "<div class='col-lg-offset-3 col-lg-6 col-md-12 col-sm-12 col-xs-12'>";
		    $div .= "   <div class='audio-html5 row'>";
		    $div .= "      <img id='equalizer$id' class='col-lg-12 col-md-12 col-sm-12 col-xs-12 desativado' src='/assets/img/equalizer_audio.gif'>";
		    $div .= "      <audio id='audio-html5_$id' class='audio-conteudo col-lg-12 col-md-12 col-sm-12 col-xs-12' controls preload='auto' idaudio='$id'>";
            $div .= "         <source src='$link' type='audio/$extensao'>Seu navegador não suporta a tag video";
		    $div .= "      </audio>";
		    $div .= "   </div>";
		    $div .= "</div>";
		}
        
        $div .= $this->visualizarAtributos($conteudo, $arquivo, $download);
        
        return $div;    
    }

    private function visualizarTvEscola(Aew_Model_Bo_ConteudoDigital $conteudo)
    {
        $div = "";
        $attr = $conteudo->extrairContentType($conteudo->getSite());
        if($attr["http_code"] != 200):
            return;
        endif;
        
		$html = file_get_contents($conteudo->getSite());
		    
		if($html):
			//--- Expressão regular para procurar link de video para visualizar
			$regex = '/file:\s[\'"]\s*(.*?)\s*[\'"]/i';

			if(preg_match($regex, $html, $contents)):
				$div = $this->visualizarVideo($conteudo, $contents[1]);
			endif;
		endif;

        return $div;
    }
    
    private function visualizarYoutube(Aew_Model_Bo_ConteudoDigital $conteudo)
    {
        $arr_atributos  = array('http:','https:','watch?v=','&feature=player_embedded','?feature=player_detailpage','&feature=channel','&feature=related');
        $arr_substituir =  array("","","embed/","","","","");
        $link = str_replace($arr_atributos,$arr_substituir,$conteudo->getSite());
        $div  = "<div class='embed-responsive embed-responsive-4by3'>";
        $div .= "   <iframe class='embed-responsive-item' src='$link?theme=light&color=white&modestbranding=1&autoplay=&rel=0&hd=1&autohide=1&showinfo=0&disablekb=1&controls=1&fs=1&ap=%2526fmt%3D22' frameborder='0' allowfullscreen></iframe>";
        $div .= "</div>";
        return $div;
    }

    private function visualizarIrdeb(Aew_Model_Bo_ConteudoDigital $conteudo, $tipoConteudo)
    {
        $id = explode("/",$conteudo->getSite());
        $id = end($id);
        
        $classe = "";
        $width = 0;
        $height = 0;
        if($tipoConteudo == "audio")
        {
            $link = "http://www.irdeb.ba.gov.br/components/com_mediaz/mp3player.swf";
            $width = "100%";
            $height = 202;
            
            $div  = "<div class='col-lg-offset-2 col-lg-8'>";
            $div .= "      <div id='conteudo-flash' class='text-center'>";
            $div .= "         <h4><b>Seu navegador não possui flash</b></h4>";
            $div .= "         <p><a href='http://www.adobe.com/go/getflashplayer'><img src='http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif' alt='Get Adobe Flash player' /></a></p>";
            $div .= "      </div>";
            $div .= "</div>";

        }
        else
        {
            $classe = "embed-responsive-item";
            $link = "http://www.irdeb.ba.gov.br/components/com_mediaz/playerVideo.swf";
            
            $div  = "<div class='embed-responsive embed-responsive-4by3'>";
            $div .= "   <div id='conteudo-flash' class='text-center'>";
            $div .= "      <h4><b>Seu navegador não possui flash</b></h4>";
            $div .= "      <p><a href='http://www.adobe.com/go/getflashplayer'><img src='http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif' alt='Get Adobe Flash player' /></a></p>";
            $div .= "   </div>";
            $div .= "</div>";
        }

        $template = "swfobject.embedSWF('$link', 'conteudo-flash', '$width', '$height', '9.0.0', '/assets/plugins/swfobject/expressInstall.swf',null,{allowfullscreen:'true',wmode:'transparent',FlashVars:'xmlLocation=http://www.irdeb.ba.gov.br/index.php?option=com_mediaz|task=view|view=media|format=ajax|id=$id|preview=0'},{class:'$classe'})";

        $view = new Zend_View();
        $ajax = new Zend_Controller_Request_Http();
        if($ajax->isXmlHttpRequest())
		{
            echo $view->headScript()->setFile('/assets/plugins/swfobject/swfobject.js');
            echo $view->headScript()->setScript($template,'text/javascript');
		}
		else
		{	
            $view->headScript()->appendFile('/assets/plugins/swfobject/swfobject.js');
            $view->headScript()->appendScript($template,'text/javascript');
    	}

        return $div;
    }
    
    private function visualizarDominioPublico(Aew_Model_Bo_ConteudoDigital $conteudo)
    {
        $site = $conteudo->getConteudoVisualizar();
        if(!$site)
        {
            return;
        }

        $div = $this->visualizarVideo($conteudo);
        //$div = $this->visualizarPlayerFlash($conteudo, $site);
        return $div;
    }
    
    private function visualizarPlayerFlash(Aew_Model_Bo_ConteudoDigital $conteudo)
    {
        
        $view = new Zend_View();
        
        $arquivo = "";
        $download = "";
        
        $id   = $conteudo->getId();
        $site = $conteudo->getConteudoVisualizar($arquivo, $download);
        $site = $view->baseUrl().$site;
        
        $image  = $view->baseUrl().$conteudo->getConteudoImagem();
        $link   = $view->baseUrl()."/assets/plugins/flash/player5.9.swf";
        $skin   = $view->baseUrl()."/assets/plugins/flash/playcasso/playcasso_cinza.zip";
        $plugin = $view->baseUrl()."/assets/plugins/flash/ifequalizer-1.swf";

        $vars   = "var vars = { file: '$site', provider: 'video', image: '$image', skin: '$skin'};";
        $params = "var params = { scale: 'noscale', quality: 'high', allowfullscreen: 'true', allowscriptaccess: 'always'};";
        $attrs  = "var attrs = {id: 'id$id', class: 'embed-responsive-item menu-cinza'};";

        $div  = "<div class='embed-responsive embed-responsive-4by3 shadow rounded'>";
        $div .= "   <div id='conteudo-flash' class='text-center'>";
        $div .= "      <h4><b>Seu navegador não possui flash</b></h4>";
        $div .= "      <p><a href='http://www.adobe.com/go/getflashplayer'><img src='http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif' alt='Get Adobe Flash player' /></a></p>";
        $div .= "   </div>";
        $div .= "</div>";

        $template = $vars."\n".$params."\n".$attrs."\n"."swfobject.embedSWF('$link', 'conteudo-flash', '100%', '100%', '9.0.0', '/assets/plugins/swfobject/expressInstall.swf', vars, params, attrs)";

        $ajax = new Zend_Controller_Request_Http();
        if($ajax->isXmlHttpRequest())
        {
            echo $view->headScript()->setFile('/assets/plugins/swfobject/swfobject.js');
            echo $view->headScript()->setScript($template,'text/javascript');
        }
        else
        {	
            $view->headScript()->appendFile('/assets/plugins/swfobject/swfobject.js');
            $view->headScript()->appendScript($template,'text/javascript');
    	}

        return $div;
    }
    
    private function visualizarAtributos(Aew_Model_Bo_ConteudoDigital $conteudo, $arquivo, $download)
    {
        $view = new Zend_View();
        $view->headLink()->appendStylesheet('/assets/img/icones/tipos-arquivos/sprites/tipos-arquivos.css');

		if($download)
		{
			$attr = $conteudo->getAtributosArquivo($conteudo->getConteudoDownloadPath());
	        $formato = $conteudo->getFormatoDownload()->getNome();
		}
		else
		{
	        $attr = $conteudo->getAtributosArquivo($arquivo);
	        $formato = $conteudo->getFormato()->getNome();
		}

        if(!isset($attr["filesize"]) && !isset($attr["duration"]))
        {
            return;
        }
        
        $href = "";
        if($download)
        {	
            $href = "location.href='/conteudos-digitais/conteudo/baixar/id/".$conteudo->getId()."'";
        }

        $div  = "<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 page-header text-center margin-top-10'>";
        $div .= "   <div class='row'>";
        
        if(isset($attr["duration"])):
            $div .= "<span class='col-lg-3 col-xs-6 margin-bottom-05' title='Duração vídeo'><i class='fa fa-clock-o'></i> <b>".$attr["duration"]."</b></span>";
        endif;
        
        if(isset($attr["filesize"])):
            $div .= "<span class='col-lg-3 col-xs-6 margin-bottom-05' title='Tamanho arquivo'><i class='fa fa-hdd-o'></i> <b>".$attr["filesize"]."</b></span>";
        endif;
        
        if(isset($attr["filesize"]) && $download):

			if($formato == "link"):
				if($conteudo->getConteudoDownloadPath()):
					$formato = end(explode('.', $conteudo->getConteudoDownloadPath()));
				endif;
			endif;
            $div .= "<span class='col-lg-4 col-xs-12 margin-bottom-05 uppercase cursor-pointer' title='Baixar arquivo' onclick=$href><i class='fa fa-download'></i> <b>Baixar Arquivo</b></span>";
            $div .= "<span class='col-lg-2 col-xs-12 margin-bottom-05' title='.$formato'><i class='absolute sprite-tipos-arquivos-application sprite-tipos-arquivos-application-$formato size-28'></i><b class='padding-left-20'>FORMATO</b></span>";
        endif;
        
        $div .= "   </div>";
        $div .= "</div>";
        
        return $div;
    }
}
