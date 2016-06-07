<?php

class AmbientesDeApoio_HomeController extends Sec_Controller_Action
{
    public function init(){
        /* @var $acl Sec_Controller_Action_Helper_Acl */
        $acl = $this->getHelper('Acl');
        $acl->allow(null);
    }
    /**
     * redirecionamento para categorias do ambiente de apoio
     * @return Zend_View
     */
    public function homeAction()
    {
        $this->_redirect('ambientes-de-apoio/ambientes/categorias');
    }
}