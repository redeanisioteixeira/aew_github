<?php
class Sec_View_Helper_MetaGraph{
    protected $view;

    public function setView($view)
    {
        $this->view = $view;
    }
    
    public function MetaGraph(){
        
        $descricao = 'Descrição...';
        $titulo = $this->view->pageTitle;
        $baseUrl = $this->view->baseUrl();
        $meta =   "<meta property='og:type' content='website'/>"
                . "<meta property='og:title' content='$titulo'>"
                . "<meta property='og:description' content='$descricao'/>"
                . "<meta property='og:url' content='$baseUrl'/>" // facebook compartilhamento
                . "<meta property='og:site_name' content='Ambiente educacional Web'/>"
                . "<meta property='og:image' content='$baseUrl/assets/img/logo.png'/>"
                . "<meta property='og:image:width' content='200'/>"
                . "<meta property='og:image:height' content='200'/>"
                . "<meta property='og:locale' content='pt_BR'/>"
                . "<link rel='canonical' href='$baseUrl' />"; // google + compartilhamento
        
        return $meta;
    }
}