<?php

class Sec_Auth_Adapter_Rasea implements Zend_Auth_Adapter_Interface
{
	/**
	 * Dados do usuário
	 * @var Array
	 */
    protected $_user;
    
	/**
	 * Dados passados para o login
	 * @var Array
	 */
    protected $_login;

    /**
     * Sets username and password for authentication
     *
     * @return void
     */
    public function __construct($login, $user)
    {
        $this->_user = $user;
        $this->_login = $login;
    }

    /**
     * Performs an authentication attempt using Doctrine User class.
     *
     * @throws Zend_Auth_Adapter_Exception If authentication cannot
     *                                     be performed
     * @return Zend_Auth_Result
     */
    public function authenticate()
    {
        $result = null;

        try {	   
            $client = new Sec_Service_Rasea();
	        $user = null;
	        
	        // Autentica no Rasea
	        $valid = $client->authenticate($this->_login['username'], $this->_login['senha']);
	        
	        if(false === $valid){
	        	$result = new Zend_Auth_Result(
                        Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND,
                        null,
                        array('Desculpe, o nome de usuario ou senha estão incorretos.'));
                        
                return $result;
	        }
	        
	        unset($valid);
	        
	        // Verifica se está cadastrado no sistema pelo Rasea
	        $valid = $client->userPermission($this->_login['username']);
            
	        // Verifica se está cadastrado no banco de dados
            if(false === $this->_user){
	            $valid = false;
	            $this->_user = null;
            }
	        
        	if(false === $valid){
	        	$result = new Zend_Auth_Result(
                        Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND,
                        null,
                        array('Desculpe, esse usuário não está cadastrado nesse sistema.'));
                        
                return $result;
	        }
	        
            $result = new Zend_Auth_Result(
                        Zend_Auth_Result::SUCCESS,
                        $this->_user,
                        array());
                        
            return $result;
        } catch(Exception $e) {
            throw new Zend_Auth_Adapter_Exception($e->getMessage());
        }
    }
}