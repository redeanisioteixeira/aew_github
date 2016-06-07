<?php
class Sec_View_Helper_GetAction
{
    protected $view;

    public function setView($view)
    {
        $this->view = $view;
    }

    public function getAction()
    {
 	    $result = Zend_Controller_Front::getInstance()->getRequest()->getActionName();

	    return $result;
    }
}