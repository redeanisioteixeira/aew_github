<?php
/**
 * controller de url referentes a listagem de disciplinas
 */
class SitesTematicos_DisciplinasController extends Sec_Controller_Action
{
    /**
     * configura permissção de acesso a urls
     */
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
        $nivelEnsino = new Aew_Model_Bo_NivelEnsino();
        $nivelEnsino->setId(Aew_Model_Bo_NivelEnsino::$ENSINO_MEDIO);
        $this->view->disciplinas = $nivelEnsino->selectComponentesCurriculares();

        $this->view->rssDestaques = $this->getRssAEW(0, 4, array("sites-tematicos"));
        $this->view->rssRecentes  = $this->getRssAEW(1, 4, array("sites-tematicos"));
        $this->view->rssVistos    = $this->getRssAEW(2, 4, array("sites-tematicos"));

        $this->view->inlineScript()->appendFile("/assets/plugins/jquery-live-preview/js/jquery-live-preview.js","text/javascript");
        $this->view->headLink()->appendStylesheet("/assets/plugins/jquery-live-preview/css/live-preview.css");

    }
    /**
     * listar de conteudo digitais para o site tematicos método AJAX
     * @return Zend_View
     */
    public function listarAction()
    {
        $this->getHelper('layout')->disableLayout();
        
        $disciplina = $this->getRequest()->getParam('id', false);
        $pagina = $this->getRequest()->getParam("pagina",1);
        $qtde = 10;
        $conteudoDigitalBo = new Aew_Model_Bo_ConteudoDigital();
        $componenteCurricular = new Aew_Model_Bo_ComponenteCurricular();

        $disciplina = explode(",", $disciplina);

        $componenteCurricular->setId($disciplina[0]);
        $componenteCurricular->selectAutoDados();

        $options = array();
        $options["where"]["conteudodigitalcomponente.idcomponentecurricular IN(?)"] = $disciplina;
                
        $conteudos = $componenteCurricular->selectSitesTematicos($qtde, $pagina, $conteudoDigitalBo, $options);
        $conteudos = $conteudoDigitalBo->getAsPagination($conteudos, $pagina, $qtde);
        
        $this->view->disciplina = $componenteCurricular;
        $this->view->conteudos = $conteudos;
        $this->view->iddisciplina = $disciplina;
        
        $template = '$(document).ready(function(){$(".livepreview").livePreview({viewWidth: 250, viewHeight: 160, position: "top", trigger: "click"});});';
        echo $this->view->headScript()->setScript($template,'text/javascript');
    }
    
}
