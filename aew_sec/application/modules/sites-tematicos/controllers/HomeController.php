<?php
/**
 * controller principal do modulo sites-tematicos
 */
class SitesTematicos_HomeController extends Sec_Controller_Action
{
    /**
     * iniciliza permissões de acesso a url's
     */
    public function init()
    {
        parent::init();
        $acl = $this->getHelper('Acl');
        $acl->allow(null);
    }

    /**
     * listagem dos sites tematicos por nivel de ensino (ensino medio)
     * @return Zend_View rediciona para disciplinas
     */
    public function homeAction() 
    {
        $this->_redirect("/sites-tematicos/disciplinas");
    }
}