<?php
class Sec_View_Helper_GetController
{
    protected $view;

    public function setView($view)
    {
        $this->view = $view;
    }

    public function getController()
    {
 	    $result = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();

	    return $result;
    }
}