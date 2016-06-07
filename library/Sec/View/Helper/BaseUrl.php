<?php

class Sec_View_Helper_BaseUrl extends Zend_View_Helper_BaseUrl
{
    /**
     * Get BaseUrl
     *
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->view->serverUrl().parent::getBaseUrl();
    }
}
