<?php
class SitesTematicos_RssController extends Zend_Controller_Action {
    private $feed = null;

    public function init()
    {
        $acl = $this->getHelper('Acl');
        $acl->allow(null);

        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
    }
    /**
     * Redireciona para os mais recentes
     */
    public function homeAction()
    {
        $this->maisrecentesAction();
    }

    /**
     * retorna o parametro GET selecionado.
     *
     * @param $key parametro
     * @param $default valor default
     * @return string
     */
    public function getParam($key = null, $default = null)
    {
        return $this->getRequest()->getParam($key, $default);
    }
    /**
     * Cria os feed RSS 
     * @param type Aew_Model_Bo_ConteudoDigital()
     * @param type $title título do conteúdo
     * @param type $opcao tipo RSS ou ATOM por default RSS 
     * @param type $type RSS
     * @return arquivo tipo XML   
     */
    public function criaFeedsConteudoDigital($conteudoDigital, $title, $opcao, $type = "rss")
    {
        $this->setupFeed($title, $opcao, $type);
        $this->fetchEntries($conteudoDigital);
        $this->outputFeed($type);
    }
    /**
     * Define a configuração do feed 
     * @param type $title
     * @param type $opcao
     * @param type $type
     * @return array asociativo de feed con conteúdo preenchido con dados do banco
     */
    private function setupFeed($title, $opcao, $type = "rss")
    {
        $image  = array("uri" => $this->view->baseUrl()."/assets/img/logo.png", "title" => "Ambiente Educacional Web", "link" => $this->view->baseUrl());
        $author = array("name" => "Rede Anísio Teixeira", "email" => "redeanisioteixeira@educacao.ba.gov.br", "uri" => $this->view->baseUrl());
        
        $this->feed = new Zend_Feed_Writer_Feed();
        
        $this->feed->setEncoding("utf-8");
        $this->feed->setLanguage("pt_BR");
        $this->feed->setTitle($title);
        $this->feed->setDescription("$title que se encomtram disponíveis no Ambiente Educaional Web.");
        $this->feed->setCopyright("Creative Commons BY NC SA");
        $this->feed->setDateCreated(time());
        $this->feed->setLink($this->view->baseUrl());
        $this->feed->addHub($this->view->baseUrl());
        $this->feed->setFeedLink($this->view->baseUrl()."/conteudos-digitais/rss/$opcao", $type);
        $this->feed->setImage($image);
        $this->feed->addAuthor($author);
    }
    /**
     * Busca as entradas no feed
     * @param $data Aew_Model_Bo_ConteudoDigital()
     * @return array() array asociativo de feed con conteúdos preenchidos con dados do banco
     */
    private function fetchEntries($data)
    {
        foreach($data as $record)
        {
            $id = $record->getId();

            if ($record->getFormato())
            {
                $conteudoTipo = $record->getFormato()->getConteudoTipo();
            }

            $idPubllicador  = $record->getUsuarioPublicador()->getId();
            $nomePublicador = $record->getUsuarioPublicador()->getNome();
            
            // --- Extrair imagem associada
            $img = $record->getConteudoImagem();
            $ext = explode('.', $img);
            $ext = end($ext);
            $img = array("uri" => $this->view->baseUrl().$img, "type" => "image/$ext", "length" => "1");
            
            $data = new Zend_Date();
            $data->set($record->getDataPublicacao());

            $entry = $this->feed->createEntry();

            $entry->setEncoding("UTF-8");
            $entry->setTitle($record->getTitulo());
            $entry->setLink($this->view->baseUrl().$record->getLinkPerfil());
            $entry->setDateModified($data);
            $entry->setDateCreated($data);
            $entry->setDescription(htmlentities($record->getDescricao(), null, "UTF-8"));
            $entry->setEnclosure($img);
            
            $entry->addAuthor(array("name" => $nomePublicador)); 

            $categories = array(
                                array("term" => $conteudoTipo->getId(), "label" => "type_id"),
                                array("term" => $conteudoTipo->getNome(), "label" => "type"),
                                array("term" => $idPubllicador, "label" => "user"),
                                array("term" => ($record->getAcessos() ? $record->getAcessos() : '0'), "label" => "views"),
                                array("term" => ($record->getQuantidadevoto() ? $record->getQuantidadevoto() : ''), "label" => "votosq"),
                                array("term" => ($record->getMediavoto() ? $record->getMediavoto() : ''), "label" => "votosm"),
                                array("term" => count($record->getComentarios()), "label" => "comments"),
                                array("term" => ($record->getQtdDownloads() ? $record->getQtdDownloads() : 0), "label" => "downloads"),
                                array("term" => ($record->getConteudoDigitalCategoria()->getId() ? $record->getConteudoDigitalCategoria()->getId() : ''), "label" => "categoryid"),
                                array("term" => ($record->getConteudoDigitalCategoria()->getLinkEpisodios() ? $this->view->baseUrl().$record->getConteudoDigitalCategoria()->getLinkEpisodios() : ''), "label" => "categoryurl"),
                                array("term" => ($record->getConteudoDigitalCategoria()->getNome() ? $record->getConteudoDigitalCategoria()->getNome() : ''), "label" => "categoryname"),
                                array("term" => ($record->getConteudoDigitalCategoria()->getCanal()->getId() ? $record->getConteudoDigitalCategoria()->getCanal()->getId() : ''), "label" => "canal")
                            );
            
            $entry->addCategories($categories);

            $tags = $record->selectTags();
            if($tags)
                foreach($tags as $tag)
                    $entry->addCategory(array("label" => "tag", "term" => $tag->getNome()));
            
            $this->feed->addEntry($entry);
        }
    }        
    /**
     * Exporta os feeds
     * @param type RSS
     * @return imprime XML  
     */
    private function outputFeed($type = "rss")
    {
        echo $this->feed->export($type);
    }
    /**
     * Feeds mais recentes
     */
    public function maisrecentesAction() 
    {
        $canal = $this->getParam('canal', null);
        $type  = $this->getParam('tipo', null);
        
        $title = "Conteúdos mais recentes";

        switch ($canal){
            
            case "estudantes": 
                $title .= " : Estudantes";
                $conteudoDigital->setIdCanal(1);
                break;
                
            case "professores": 
                $title .= " : Professores";
                $conteudoDigital->setIdCanal(2);
                break;
                
            case "municipios": 
                $title .= " : Municípios";
                $conteudoDigital->setIdCanal(3);
                break;
        }

        $conteudoDigital = new Aew_Model_Bo_ConteudoDigital();

        $options = array();
        $options["orderBy"] = array("conteudodigital.datapublicacao DESC");
        $options["where"]["conteudodigital.flsitetematico = ?"] = true;
        $options["where"]["conteudodigital.flaprovado = ?"] = true;
        
        $datasource = $conteudoDigital->select(10, 0, $options);

        $this->criaFeedsConteudoDigital($datasource, $title, "maisrecentes", $type);
    }
    /**
     * Feed mais acessados
     */
    public function maisacessadosAction() 
    {
        $canal = $this->getParam('canal', null);
        $type  = $this->getParam('tipo', null);
        
        $title = "Conteúdos mais acessados";

        switch ($canal){
            
            case "estudantes": 
                $title .= " : Estudantes";
                $conteudoDigital->setIdCanal(1);
                break;
                
            case "professores": 
                $title .= " : Professores";
                $conteudoDigital->setIdCanal(2);
                break;
                
            case "municipios": 
                $title .= " : Municípios";
                $conteudoDigital->setIdCanal(3);
                break;
        }
        
        $conteudoDigital = new Aew_Model_Bo_ConteudoDigital();
        
        $options = array();
        $options["orderBy"] = "conteudodigital.acessos DESC";
        $options["where"]["conteudodigital.flsitetematico = ?"] = true;
        $options["where"]["conteudodigital.flaprovado = ?"] = true;
        
        $datasource = $conteudoDigital->select(10, 0, $options);
        
        $this->criaFeedsConteudoDigital($datasource, $title, "maisacessados", $type);
    }
    /**
     * Feed dos mais votados
     */
    public function maisvotadosAction() 
    {
        
    	$canal = $this->getParam('canal', null);
        $type  = $this->getParam('tipo', null);
        
        $title = "Conteúdos mais votados";
        
        switch ($canal){
            
            case "estudantes": 
                $title .= " : Estudantes";
                $conteudoDigital->setIdCanal(1);
                break;
                
            case "professores": 
                $title .= " : Professores";
                $conteudoDigital->setIdCanal(2);
                break;
                
            case "municipios": 
                $title .= " : Municípios";
                $conteudoDigital->setIdCanal(3);
                break;
        }

        $conteudoDigital = new Aew_Model_Bo_ConteudoDigital();
        
        $options = array();
        $options["where"]["conteudodigital.flsitetematico = ?"] = true;
        $options["where"]["conteudodigital.flaprovado = ?"] = true;
        $options["column"][]  = "(SELECT SUM(conteudodigitalvoto.voto)/COUNT(conteudodigitalvoto.voto) AS avaliacao FROM conteudodigitalvoto WHERE conteudodigitalvoto.idconteudodigital = conteudodigital.idconteudodigital GROUP BY conteudodigitalvoto.idconteudodigital) AS mediavoto" ;
        $options["column"][]  = "(SELECT COUNT(conteudodigitalvoto.voto) AS quantidade FROM conteudodigitalvoto WHERE conteudodigitalvoto.idconteudodigital = conteudodigital.idconteudodigital GROUP BY conteudodigitalvoto.idconteudodigital) AS quantidadevoto";
        $options["orderBy"][] = "mediavoto DESC";
        $options["orderBy"][] = "quantidadevoto DESC";
        $options["orderBy"][] = "conteudodigital.acessos DESC";
        $options["orderBy"][] = "conteudodigital.datapublicacao DESC";
        $options["join"]["conteudodigitalvoto"] = "conteudodigitalvoto.idconteudodigitalvoto = (SELECT idconteudodigitalvoto FROM conteudodigitalvoto WHERE conteudodigitalvoto.idconteudodigital = conteudodigital.idconteudodigital LIMIT 1)";
        
        $datasource = $conteudoDigital->select(10, 0, $options);
        
        $this->criaFeedsConteudoDigital($datasource, $title, "maisvotados", $type);
    }
    /**
     * Feeds de destacados
     */
    public function destaquesAction()
    {
    	$canal = $this->getParam('canal', null);
        $type  = $this->getParam('tipo', null);
        
        $title = "Conteúdos em destaque";

        switch ($canal){
            
            case "estudantes": 
                $title .= " : Estudantes";
                $conteudoDigital->setIdCanal(1);
                break;
                
            case "professores": 
                $title .= " : Professores";
                $conteudoDigital->setIdCanal(2);
                break;
                
            case "municipios": 
                $title .= " : Municípios";
                $conteudoDigital->setIdCanal(3);
                break;
        }

        $conteudoDigital = new Aew_Model_Bo_ConteudoDigital();
        
        $options = array();
        $options["orderBy"] = "conteudodigital.datapublicacao DESC";
        $options["where"]["conteudodigital.fldestaque = ?"] = true;
        $options["where"]["conteudodigital.flsitetematico = ?"] = true;
        $options["where"]["conteudodigital.flaprovado = ?"] = true;

        $datasource = $conteudoDigital->select(10, 0, $options);
        $this->criaFeedsConteudoDigital($datasource, $title, "destaques", $type);
    }
}
