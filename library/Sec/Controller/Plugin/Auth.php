<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Sec_Controller_Plugin_Auth
 *
 * @author tmornellas
 */
class Sec_Controller_Plugin_Auth extends Zend_Controller_Plugin_Abstract {

    public function preDispatch(Zend_Controller_Request_Abstract $request) {

        if (strtolower($request->getControllerName()) == 'integracao') {
            $authkey = $request->getHeader('authkey');
            if ($authkey != 'owm6hT06SIXp27RnP5z3RZvF4GJ5TYLlPeFXAHCnhkWmqL8RFP4ouV4uXRcbCEhh') {
                $this->getResponse()
                        ->setHttpResponseCode(403)
                        ->appendBody("Invalid API Key\n");

                $request->setModuleName('default')
                        ->setControllerName('error')
                        ->setActionName('denied')
                        ->setDispatched(true);
            }
        }
    }
}

?>
