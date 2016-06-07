<?php

class Sec_View_Helper_Url
{
    protected $view;

    public function setView($view)
    {
        $this->view = $view;
    }

    /**
     * Generates an url given the name of a route.
     *
     * @access public
     *
     * @param  array $urlOptions Options passed to the assemble method of the Route object.
     * @param  mixed $name The name of a Route to use. If null it will use the current Route
     * @param  bool $reset Whether or not to reset the route defaults with those provided
     * @return string Url for the link href attribute.
     */
    public function url(array $urlOptions = array(), $name = null, $reset = false, $encode = true)
    {
        $router = Zend_Controller_Front::getInstance()->getRouter();
        return $this->view->serverUrl().$router->assemble($urlOptions, $name, $reset, $encode);
    }
}