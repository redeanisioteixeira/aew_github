<?php

class Sec_View_Helper_IsAllowed
{
    protected $view;

    public function setView($view)
    {
        $this->view = $view;
    }

    public function isAllowed(Aew_Model_Bo_Usuario $usuario,$resource, $action)
    {
        $acl = Sec_Acl::getInstance();
        $allowed = $acl->isAllowed($usuario->getUsuarioTipo()->getNome(), $resource, $action);
        return $allowed;
    }
}
