<?php
class Sec_View_Helper_GetModule
{
    protected $view;

    public function setView($view)
    {
        $this->view = $view;
    }

    public function getModule()
    {
        $result = Zend_Controller_Front::getInstance()->getRequest()->getModuleName();
        return $result;
    }
}