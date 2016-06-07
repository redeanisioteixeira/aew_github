<?php
class SitesTematicos_TemasTransversaisController extends Sec_Controller_Action
{
    public function init()
    {
        parent::init();
        
        $acl = $this->getHelper('Acl');
        $acl->allow(null);
    }
    /**
     * Carrega JS e CSS para o método HeadScript
     * @return Zend_View_Helper_HeadScript 
     */
    public function initScripts()
    {
        $this->view->inlineScript()->appendFile('/assets/js/jquery.scrollTo.min.js');
    }
    
    /**
     * listagem dos sites tematicos por nivel de ensino (ensino medio)
     * @return Zend_View
     */
    public function homeAction() 
    {
        $this->setPageTitle("Sites Temáticos");
        $categoriaComponente = new Aew_Model_Bo_CategoriaComponenteCurricular();
        $categoriaComponente->setId(3);
        $this->view->temastransversais = $categoriaComponente->selectComponentesCurriculares();
        
        $this->view->rssDestaques = $this->getRssAEW(0, 4, array("sites-tematicos"));
        $this->view->rssRecentes  = $this->getRssAEW(1, 4, array("sites-tematicos"));
        $this->view->rssVistos    = $this->getRssAEW(2, 4, array("sites-tematicos"));

        $this->view->inlineScript()->appendFile("/assets/plugins/jquery-live-preview/js/jquery-live-preview.js","text/javascript");
        $this->view->headLink()->appendStylesheet("/assets/plugins/jquery-live-preview/css/live-preview.css");
    }
    /**
     * Lista de sites tematicos filtrados por temas transversais
     * @return Zend_View
     */
    public function listarAction()
    {
        $this->getHelper('layout')->disableLayout();
        
        $disciplina = $this->getRequest()->getParam('id', false);
        $pagina = $this->getRequest()->getParam("pagina",1);
        $qtde = 9;
        
        $conteudoDigitalBo = new Aew_Model_Bo_ConteudoDigital();
        $componenteCurricular = new Aew_Model_Bo_ComponenteCurricular();

        $componenteCurricular->setId($disciplina);
        $disciplina = $componenteCurricular->select(1);
        
        $conteudos = $componenteCurricular->selectSitesTematicos($qtde, $pagina, $conteudoDigitalBo);
        
        $conteudos = $conteudoDigitalBo->getAsPagination($conteudos, $pagina, $qtde);
        
        $this->view->disciplina = $disciplina;
        $this->view->conteudos = $conteudos;

        $template = '$(document).ready(function(){$(".livepreview").livePreview({viewWidth: 250, viewHeight: 160, position: "top", trigger: "click"});});';
        echo $this->view->inlineScript()->setScript($template,'text/javascript');
    }

}
