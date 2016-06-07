<?php

class Sec_Service_Abstract {

    protected $_username;
    protected $_password;

    /**
     * Variavel que define se o web service esta sendo utilizado como Mock
     *
     * @var boolean
     */
    protected $_mock = false;

	/**
	 * Cria um web service
	 *
	 * @param $wsdl
	 * @param $namespace
	 * @param $header
	 * @return Zend_Soap_Client
	 */
    protected function _createWebService($wsdl, $namespace = false, $header = ''){
        $client = new Zend_Soap_Client($wsdl, array('soap_version' => SOAP_1_1));
        if(false != $namespace){
			$credentials = array('username' => $this->_username, 'password' => $this->_password);
	        $client->addSoapInputHeader(new SoapHeader($namespace, $header, $credentials));
        } elseif($this->_username != '') {
            $client->setHttpLogin($this->_username);
	        $client->setHttpPassword($this->_password);
        }

        return $client;
    }

    /**
     * Get the mock value
     * @return bool
     */
    public function getMock()
    {
        return $this->_mock;
    }

    /**
     * Set the mock value
     * @param $value bool
     * @return void
     */
    public function setMock($value)
    {
        $this->_mock = $value;
    }
}