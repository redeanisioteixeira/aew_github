<?php
class AmbientesDeApoio_RssController extends Zend_Controller_Action {

    public function init()
    {
        $acl = $this->getHelper('Acl');
        $acl->allow(null);

        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
    }
    /**
     * redirecionar para o mais recentes
     * @return Zend_View
     */
    public function homeAction() {
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
     * 
     * @param string $ambienteDeApoio
     * @param array $title
     */
    public function criaFeedsAmbientesDeApoio($ambienteDeApoio, $title)
    {
        $this->setupFeed($title);
        $this->fetchEntries($ambienteDeApoio);
        $this->outputFeed();
    }
    /**
     * 
     * @param array $title
     */
    private function setupFeed($title)
    {
        $this->feed = new Zend_Feed_Writer_Feed();
        
        $this->feed->setEncoding("UTF-8");
        $this->feed->setLanguage("pt_BR");
        $this->feed->setTitle($title);
        $this->feed->setCopyright("Creative Commons BY NC SA");
        $this->feed->setDateCreated(time());
        $this->feed->setLink($this->view->baseUrl());
        $this->feed->setFeedLink($this->view->baseUrl(), "atom");
        $this->feed->addAuthor(array(
                                    "name"  => "Rede Anísio Teixeira",
                                    "email" => "redeanisioteixeira@educacao.ba.gov.br",
                                    "uri"   => "http://ambiente.educacao.ba.gov.br"
                                    ));
    }
    /**  
     * @param string $data
     */
    private function fetchEntries($data) 
    {
        foreach($data as $record)
        {
            $id = $record->getId();

            $idPubllicador  = $record->getUsuarioPublicador()->getId();
            $nomePublicador = $record->getUsuarioPublicador()->getNome();
            $urlExibir      = $record->getLinkPerfil(true);

            $img = array("uri" => $record->getImagemAssociadaUrl(true).DS.$record->getId().'.png', "type" => "image/png");
                
            $data = strtotime(($record->getDataCriacao() ? $record->getDataCriacao() : date("Y-m-d H:i")));
            
            $entry = $this->feed->createEntry();

            $entry->setEncoding("UTF-8");
            $entry->setTitle($record->getTitulo());
            $entry->setLink($urlExibir);
            $entry->setDateModified($data);
            $entry->setDateCreated($data);
            $entry->setDescription(htmlentities($record->getDescricao(), null, "UTF-8"));
            $entry->setEnclosure($img);
            
            $entry->addAuthor(array("name" => $nomePublicador)); 
            $entry->addCategory(array("label" => "type_id", "term" => $record->getAmbientedeApoioCategoria()->getId()));
            $entry->addCategory(array("label" => "type", "term" => $record->getAmbientedeApoioCategoria()->getNome()));
            $entry->addCategory(array("label" => "user", "term" => $idPubllicador));

            $tags = $record->selectTags();
            if($tags)
                foreach($tags as $tag)
                    $entry->addCategory(array("label" => "tag", "term" => $tag->getNome()));
            
            $this->feed->addEntry($entry);
        }        
    }
    /**
     * utf-8
     */
    private function outputFeed()
    {
        header("Content-Type: application/atom+xml; charset= UTF-8");
        echo $this->feed->export("Atom");
    }
    /**
     * feed do ambiente de apoio
     * @return Zend_View
     */
    public function maisrecentesAction()
    {
        $title='Ambientes de apoio mais recentes';
        $ambienteApoio = new Aew_Model_Bo_AmbienteDeApoio();

        $options["orderBy"] = "ambientedeapoio.idambientedeapoio DESC";
        $datasource = $ambienteApoio->select(10, 0, $options);
        $this->criaFeedsAmbientesDeApoio($datasource, $title);
    }

    public function maisacessadosAction()
    {
    	$title='Ambientes de apoio mais acessados';
        $ambienteApoio = new Aew_Model_Bo_AmbienteDeApoio();

        $options["orderBy"][] = "ambientedeapoio.acessos DESC";
        $options["orderBy"][] = "ambientedeapoio.idambientedeapoio DESC";
        
        $datasource = $ambienteApoio->select(10, 0, $options);
        $this->criaFeedsAmbientesDeApoio($datasource, $title);        
    }
    /**
     * os mais votados
     * @return Zend_View
     */
        public function maisvotadosAction()
    {
    	$title='Ambientes de apoio mais votados';
        $ambienteApoio = new Aew_Model_Bo_AmbienteDeApoio();

        $options["orderBy"][] = "ambientedeapoio.avaliacao DESC";
        $options["orderBy"][] = "ambientedeapoio.idambientedeapoio DESC";
        
        $datasource = $ambienteApoio->select(10, 0, $options);
        $this->criaFeedsAmbientesDeApoio($datasource, $title);        
    }
    /**
     * @return Zend_View
     */
    public function destaquesAction()
    {
    	$title='Destaque dos ambientes de apoio';
        
        $ambienteDeApoio = new Aew_Model_Bo_AmbienteDeApoio();
        $ambienteDeApoio->setDestaque(true);
        $options["orderBy"] = "ambientedeapoio.titulo ASC";
        
    	$datasource=$ambienteDeApoio->select(10, 0, $options);
        $this->criaFeedsAmbientesDeApoio($datasource, $title);        
    }
}