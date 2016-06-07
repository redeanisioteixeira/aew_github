<?php
require_once '../application/cache/Cache_Class.php';
class ConteudosDigitais_ConteudosController extends Sec_Controller_Action
{
    public function init()
    {
	/* @var $acl Sec_Controller_Action_Helper_Acl */
        parent::init();
	$acl = $this->getHelper('Acl');
	
        $visitanteAction = array('listar', 'publicador', 'urlcurta', 'urlextendida', 'palavra-chave','favoritos', 'tags');
	$acl->allow(Aew_Model_Bo_UsuarioTipo::VISITANTE, $visitanteAction);
        
        $amigoDaEscolaAction = array('favoritos');
        $acl->allow(Aew_Model_Bo_UsuarioTipo::AMIGO_DA_ESCOLA, $amigoDaEscolaAction);

        $editorAction = array('adicionar', 'relatorio');
        $acl->allow(Aew_Model_Bo_UsuarioTipo::EDITOR, $editorAction);
	
        $coordenadorAction = array('aprovar', 'listar-arquivos');
	$acl->allow(Aew_Model_Bo_UsuarioTipo::EDITOR, $coordenadorAction);
        
        $administradorAction = array('destaques');
        $acl->allow(Aew_Model_Bo_UsuarioTipo::ADMINISTRADOR, $administradorAction);
    }
    /**
     * Carrega JS e CSS para o método HeadScript
     * @return Zend_View_Helper_HeadScript 
     */
    function _initScripts()
    {
        parent::initScripts();

        $this->view->headLink()
                ->appendStylesheet('/assets/plugins/jquery-ui-themes-1.11.4/themes/blitzer/theme.css')
                ->appendStylesheet('/assets/plugins/jquery-ui-themes-1.11.4/themes/blitzer/jquery-ui.css');
        
        $this->view->headScript()
                ->appendFile('/assets/plugins/jquery-ui-1.11.4/jquery-ui.js','text/javascript')
                ->appendFile('/assets/js/autocomplete.js','text/javascript');

    }
    
    /**
     * Action para listar conteudos digitais e filtrar por tipo, busca
     * @return Zend_View
     */
    public function listarAction()
    {
        $this->view->isAjax = $this->isAjax();
        
        $usuarioLogado = $this->getLoggedUserObject();
        if($this->isAjax())
        {
            $this->disableLayout();
        }
        
        $this->setPageTitle('Conteúdos Digitais');
        
        if($this->view->session->sitetematico == 'sitetematico')
        {
            $this->setPageTitle('Sites Temáticos');
        }
        
        $conteudoDigital = new Aew_Model_Bo_ConteudoDigital();
        $this->view->href = $this->opcoesAcessoConteudo($conteudoDigital);
        
        $conteudoBusca = new Sec_ConteudoDigitalBusca($this->getRequest(), $conteudoDigital, $usuarioLogado);
        
        $conteudos = $conteudoBusca->busca();
        
        $pagina = $this->getRequest()->getParam("pagina",1);
        $topicos = $this->getRequest()->getParam("topicos",false);
        
        $conteudos = $conteudoDigital->getAsPagination($conteudos, $pagina, $this->view->session->quantidade, 10);
        
        $this->view->conteudos = $conteudos;
        $this->view->topicos = $topicos;
        $this->view->visualizacao = $this->getRequest()->getParam("visualizacao","column");;
        $this->view->filtraBusca = (!Sec_TipoDispositivo::isDesktop() ? false : true);
    }

    /**
     * Action para listar conteúdos por aprovar 
     * @return Zend_View
     */
    public function aprovarAction()
    {
        $conteudoDigitalBo = new Aew_Model_Bo_ConteudoDigital();
        
        $this->setPageTitle("Conteúdos pendentes por aprovar");
        if($this->isAjax())
        {
            $this->disableLayout();
        }
        else
        {
       	    $conteudoDigitalBo->setFlaprovado(TRUE);
            $conteudosAprovados = $conteudoDigitalBo->select();

            $this->view->conteudosResumoPorTipo = $conteudoDigitalBo->selectResumoConteudosPorTipo();

            $ordenacao = new Sec_OrdenadorPorData($conteudosAprovados);
            $grupos = $ordenacao->agruparPorAno();
            
            $this->view->chart = $ordenacao->graficoConteudoOrdenavel($grupos);
            $this->view->conteudosResumo = $ordenacao->getDadosEstatisticos();
            $this->view->totalGeral = count($conteudosAprovados);
            $this->view->tituloGrafico = 'Resumo de Contéudos Publicados';
            
            $this->view->headScript()->appendFile('https://www.google.com/jsapi');
            $this->view->headScript()->appendFile('/assets/js/chart.js');
        }
        
        $options = array();
        $usuario = Sec_Controller_Action::getLoggedUserObject();
        
        if($usuario->isCoordenador() && $usuario->getId() == 93)
        {
            $options['where'] = 'conteudodigitalcategoria.idcanal = 1';
        }

        if($usuario->isCoordenador() && $usuario->getId() == 545)
        {
            $options['where'] = 'conteudodigitalcategoria.idcanal = 2';
        }
        
        $pagina = $this->getParam('pagina',1);
        $conteudoDigitalBo->setFlaprovado("FALSE");
        
        $conteudos = $conteudoDigitalBo->select(6,$pagina,$options,true); 
        $this->view->conteudos = $conteudoDigitalBo->getAsPagination($conteudos, $pagina, 6, 10);
    }
    
    /**
     * Action para listar os destaques dos conteúdos digitais
     * @return Zend_View
     */
    public function destaquesAction()
    {
        

        $this->setPageTitle('Gerenciar conteúdos em Destaque');
        
        if($this->isAjax())
        {
            $this->disableLayout();
        }
        
        $pagina = $this->getParam('pagina',1);
        
        $conteudoDigitalBo = new Aew_Model_Bo_ConteudoDigital();//armazenando e extraindo do cache
        $conteudoDigitalBo->setDestaque(true);
        $conteudosDestaque = $conteudoDigitalBo->select(6,$pagina,null,true); 

        $this->view->conteudos = $conteudoDigitalBo->getAsPagination($conteudosDestaque, $pagina, 6, 10);
        
        $href['remover_destaque'] = "/conteudos-digitais/conteudo/removerdestaque/id/";
        $this->view->href = $href;
    }
    
    /**
     * Action para criação de relatório PDF, utiliza livraria mPDF
     * @return Zend_View
     */
    public function relatorioAction()
    {
        $this->disableLayout();
        $this->disableRender();
        
        $conteudoTiposBo = new Aew_Model_Bo_ConteudoTipo();
        $options = array();
        $options['orderBy'] = array('conteudotipo.nomeconteudotipo ASC');
        $conteudoTipos  =$conteudoTiposBo->select(0, 0, $options);
        
        $conteudoDigitalBo = new Aew_Model_Bo_ConteudoDigital();
        $session = new Zend_Session_Namespace('conteudosDigitaisBusca');
        $usuarioLogado = $this->getLoggedUserObject();

        $conteudoDigital = new Aew_Model_Bo_ConteudoDigital();
        $conteudoBusca = new Sec_ConteudoDigitalBusca($this->getRequest(), $conteudoDigital,$usuarioLogado);
        
        $conteudos = $conteudoBusca->busca(true);
        $options = $conteudoBusca->getRequest()->getParams();
        
        $this->view->conteudoTipos = $conteudoTipos;
        $this->view->conteudos = $conteudos;
		$this->view->options = $options;
		$this->view->usuario = $usuarioLogado;
		$result = $this->view->render('conteudos/relatorio.php');
                
		include("Mpdf/mpdf.php");
        
		$mpdf = new mPDF('','A4','','',12,12,10,10,4,4,'P');

		    $mpdf->SetHTMLHeader('<h1>RELATÓRIO DE CONTEÚDOS DIGITAIS</h1>');
		$mpdf->SetAuthor('Rede Anísio Teixeira');
		$mpdf->WriteHTML($result);
		$mpdf->setFooter('{PAGENO} de {nbpg}');
		$mpdf->Output('relatorio_conteudos.pdf', 'D');
		exit();
    }
    
    /**
     * Action lista os conteúdos do usuário publicador
     * @return 
     */
    public function publicadorAction()
    {
	$id = $this->getRequest()->getParam('id', false);
        $usuarioPublicador = new Aew_Model_Bo_Usuario();
        $usuarioPublicador->setId($id);
	if(!$usuarioPublicador->selectAutoDados())
        {
            $this->flashError('Nenhum publicador foi passado.');
	}
        $this->listarAction();
    }
    /**
     * Método de busca por tag
     * @return Zend_View 
     */
    public function tagsAction()
    {
	$id = $this->getRequest()->getParam("id", null);
    	$pagina = $this->getRequest()->getParam('pagina', 1);
        $limite = 15;
        
	if($id == null):
            $this->_redirect("conteudos-digtais/listar");
	endif;
        
        if($this->isAjax()):
            $this->disableLayout();
	endif;
        
	$conteudoDigitalBo = new Aew_Model_Bo_ConteudoDigital();
        $tag = new Aew_Model_Bo_Tag();
        
        $tag->setId($id);
        $conteudoDigitalBo->addTag($tag);
        $tag = $tag->select(1);
        
        $options = array();
	$options["orderBy"] = array("conteudodigital.datapublicacao DESC", "conteudodigital.titulo");
        
	$conteudos = $conteudoDigitalBo->select($limite, $pagina, $conteudoDigital, $options, true);
        $conteudos = $conteudoDigitalBo->getAsPagination($conteudos, $pagina, $limite);

        $this->setPageTitle('Resultado da busca por "'.$tag->getNome().'"');
	$this->view->conteudos = $conteudos;
    }    

    /**
     * Método que lista as licenças para mais informação
     * @return Zend_View
     */
    public function licencasAction()
    {
        if($this->isAjax()){  $this->disableLayout(); }
        $licencasBo = new Aew_Model_Bo_ConteudoLicenca();
        $this->view->licencas = $licencasBo->select();
    }

    public function urlcurtaAction()
    {
		$this->disableLayout();
		if(!$this->isAjax()):
	        $this->_redirect('');
		endif;

		$url = $this->getParam('url', null);

		$bitly = new Sec_Bitly();
		$url_encurtada = $bitly->bitly_v3_shorten($url);
		echo json_encode($url_encurtada['url']);
		die();
    }

    
    /**
     * Método para acortar url com plugin Bitly
     * @return $array JSON 
     */
    public function urlextendidaAction()
    {
		$this->disableLayout();
		if(!$this->isAjax()):
	        $this->_redirect('');
		endif;
        
		$url = $this->getParam('url', null);

        header('Access-Control-Allow-Origin: *');
        $ordenarPor = array("http://ambiente.educacao.ba.gov.br/" => "/",
                            "e.avaliacao+DESC,e.titulo+ASC" => "avaliacao",
                            "e.acessos+DESC,e.titulo+ASC" => "popularidade",
                            "e.titulo" => "título",
                            "e.dataPublicacao+DESC,e.titulo+ASC" =>"data"
                        );

        $bitly = new Sec_Bitly();
        $url_extendida = $bitly->bitly_v3_expand($url);

        if(is_array($url_extendida)):
            if(isset($url_extendida[0]['long_url'])):
                $url = $url_extendida[0]['long_url'];
                foreach($ordenarPor as $key => $value):
                    $url = str_replace($key, $value, $url);
                endforeach;
				$url_extendida = $url;
            endif;
        endif;

        echo json_encode($url_extendida);
		die();
    }
    
    /**
     * Action Lista arquivos disponiveis em diretório download
     */
    public function _listarArquivosAction()
    {
	$this->setPageTitle('Conteúdos Digitais de Áudio e Vídeo');
	$usuario = $this->getLoggedUserObject();
	$conteudo = new Aew_Model_Bo_ConteudoDigital;
	
	//Abre Diretório Download
        $pathDown = $conteudo->getConteudoDownloadDirectory();
        $conteudoDownload= $conteudo->getArquivosFisicos($pathDown);
        
        $pathVisua = $conteudo->getConteudoVisualizacaoDirectory();
        $conteudoVisualiza = $conteudo->getArquivosFisicos($pathVisua);
        
        
	$this->view->visualiza = $conteudoVisualiza;
	$this->view->download= $conteudoDownload;
    }
    
    public function listarArquivosAction()
    {
        $this->setPageTitle('Conteúdos Digitais de Áudio e Vídeo');

        $conteudoDigitalBo = new Aew_Model_Bo_ConteudoDigital;

        $usuario = null;
        $auth = Zend_Auth::getInstance();
        if($auth->hasIdentity()):
            $usuario = $auth->getIdentity();
        endif;

        //Extenções que não deve listar
        $nao_listar = array("", ".", "doc", "odt", "swf", "htm", "zip", "php","pdf","png","gif","docx","xml","rar","txt","exe","jpg","html");
        $conteudo_download = array();

        //Abre Diretório Download
        $dir_handle = @opendir(ConteudoDigital::getConteudoDownloadDirectory()) or die("Não se pode abrir Diretório");

        //Recorre o Diretório
        while ($file = readdir($dir_handle)):
            $extensao = end(explode('.',$file));

            if(!in_array($extensao, $nao_listar)):
                $id = explode('.',$file);
                $id = $id[0];

                //Prenche o Array
                $conteudo_download[$id]['id'] = $id;
                $conteudo_download[$id]['download'] = $file;
                $conteudo_download[$id]['formatoD'] = '';
                $conteudo_download[$id]['tamanhoDownload'] = round((filesize(ConteudoDigital::getConteudoDownloadDirectory()."/".$file)/1024));
                $conteudo_download[$id]['titulo'] = '';
                $conteudo_download[$id]['categoria'] = '';
                $conteudo_download[$id]['visualizacao'] = '';
                $conteudo_download[$id]['formatoV'] = '';
                $conteudo_download[$id]['tamanhoVisualizacao'] = '';
                $conteudo_download[$id]['usuarioPublicador'] = '';
                $conteudo_download[$id]['aprovado'] = '';
                $conteudo_download[$id]['visualizacao'] = '';
                $conteudo_download[$id]['tamanhoVisualizacao'] = '';

                if(intVal($id)>0):
                    $options = array();
                    $options['where']['e.aprovado = ?'] = true;

                    $conteudo = $conteudoDigitalBo->get($id, $options);

                    if($conteudo['titulo'] == ''):
                            $options = array();
                            $options['where']['e.aprovado = ?'] = false;
                            $conteudo = $conteudoDigitalBo->setId($id);
                            $conteudo = $conteudoDigitalBo->setId($id);
                    endif;

                    $conteudo_download[$id]['titulo'] = $conteudo['titulo'];
                    $conteudo_download[$id]['categoria'] = $conteudo["conteudoDigitalCategoria"]["nome"];
                    $conteudo_download[$id]['formatoV'] = $conteudo['formato']['nome'];
                    $conteudo_download[$id]['formatoD'] = $conteudo['formatoDownload']['nome'];
                    $conteudo_download[$id]['usuarioPublicador'] = $conteudo['usuarioPublicador'];
                    $conteudo_download[$id]['aprovado'] = $conteudo['aprovado'];

                    if($conteudo != null):
                        if($extensao != $conteudo['formato']['nome'] && $extensao != $conteudo['formatoDownload']['nome']):
                            $conteudo_download[$id.$extensao]['id'] = $id;
                            $conteudo_download[$id.$extensao]['download'] = $file;
                            $conteudo_download[$id.$extensao]['formatoD'] = '';
                            $conteudo_download[$id.$extensao]['tamanhoDownload'] = round(filesize(ConteudoDigital::getConteudoDownloadDirectory()."/".$file)/1024);
                            $conteudo_download[$id.$extensao]['titulo'] = '';
                            $conteudo_download[$id.$extensao]['categoria'] = '';
                            $conteudo_download[$id.$extensao]['visualizacao'] = '';
                            $conteudo_download[$id.$extensao]['formatoV'] = '';
                            $conteudo_download[$id.$extensao]['tamanhoVisualizacao'] = '';
                            $conteudo_download[$id.$extensao]['usuarioPublicador'] = '';
                            $conteudo_download[$id.$extensao]['aprovado'] = '';
                            $conteudo_download[$id.$extensao]['visualizacao'] = '';
                            $conteudo_download[$id.$extensao]['tamanhoVisualizacao'] = '';
                        endif;
                    endif;

                endif;
            endif;
        endwhile;

        closedir($dir_handle);

        //Abre Diretório Visualização
        $dir_handle = @opendir(ConteudoDigital::getConteudoVisualizacaoDirectory()) or die("Não se pode abrir Diretório");

        //Recorre o Diretório
        while ($file = readdir($dir_handle)):
                $extensao = end(explode('.',$file));

                if(!in_array($extensao, $nao_listar)):
                        $id = explode('.',$file);
                        $id = $id[0];

                        //Prenche o Array
                        if(array_key_exists($id, $conteudo_download) == false):
                                $conteudo_download[$id]['id'] = $id;
                                $conteudo_download[$id]['download'] = '';
                                $conteudo_download[$id]['formatoD'] = '';
                                $conteudo_download[$id]['tamanhoDownload'] = '';
                                $conteudo_download[$id]['titulo'] = '';
                                $conteudo_download[$id]['categoria'] = '';
                                $conteudo_download[$id]['usuarioPublicador'] = '';
                                $conteudo_download[$id]['aprovado'] = '';
                                $conteudo_download[$id]['visualizacao'] = '';
                        endif;

                        $conteudo_download[$id]['formatoV'] = '';
                        $conteudo_download[$id]['tamanhoVisualizacao'] = round((filesize(ConteudoDigital::getConteudoVisualizacaoDirectory()."/".$file)/1024));

                        if(intVal($id)>0):
                                $options = array();
                                $options['where']['e.aprovado = ?'] = true;

                                $conteudo = $conteudoDigitalBo->get($id, $options);

                                if($conteudo['titulo'] == ''):
                                        $options = array();
                                        $options['where']['e.aprovado = ?'] = false;
                                        $conteudo = $conteudoDigitalBo->get($id, $options);
                                endif;

                                $conteudo_download[$id]['titulo'] = $conteudo['titulo'];
                                $conteudo_download[$id]['categoria'] = $conteudo["conteudoDigitalCategoria"]["nome"];
                                $conteudo_download[$id]['formatoV'] = $conteudo['formato']['nome'];
                                $conteudo_download[$id]['formatoD'] = $conteudo['formatoDownload']['nome'];
                                $conteudo_download[$id]['usuarioPublicador'] = $conteudo['usuarioPublicador'];
                                $conteudo_download[$id]['aprovado'] = $conteudo['aprovado'];
                                $conteudo_download[$id]['visualizacao'] = $file;

                                if($conteudo != null):
                                        if($extensao != $conteudo['formato']['nome'] && $extensao != $conteudo['formatoDownload']['nome']):
                                                $conteudo_download[$id.$extensao]['id'] = $id;
                                                $conteudo_download[$id.$extensao]['download'] = '';
                                                $conteudo_download[$id.$extensao]['formatoD'] = '';
                                                $conteudo_download[$id.$extensao]['tamanhoDownload'] = '';
                                                $conteudo_download[$id.$extensao]['titulo'] = '';
                                                $conteudo_download[$id.$extensao]['categoria'] = '';
                                                $conteudo_download[$id.$extensao]['visualizacao'] = $file;
                                                $conteudo_download[$id.$extensao]['formatoV'] = '';
                                                $conteudo_download[$id.$extensao]['tamanhoVisualizacao'] = round((filesize(ConteudoDigital::getConteudoVisualizacaoDirectory()."/".$file)/1024));
                                                $conteudo_download[$id.$extensao]['usuarioPublicador'] = '';
                                                $conteudo_download[$id.$extensao]['aprovado'] = '';
                                        endif;
                                endif;

                        endif;

                endif;
        endwhile;

        closedir($dir_handle);

        //Envia dados para View
        arsort($conteudo_download);
        $this->view->download= $conteudo_download;
    }    
}
