<?php

class Sec_Auth_Adapter_Doctrine implements Zend_Auth_Adapter_Interface
{
    private $_login;
    private $_user;

    /**
     * Sets username and password for authentication
     *
     * @return void
     */
    public function __construct($login, $user)
    {
        $this->_login = $login;
        $this->_user = $user;
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
	$mensagem = 'Nome de usuário ou senha incorretos. Tente novamente';
        try 
        {
            if ($this->_user == NULL) 
            {
                $result = new Zend_Auth_Result(Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND,null,array($mensagem));
            } 
            else 
            {
                if ($this->_user->getSenha() != md5($this->_login['senha'])) 
                {
                    $result = new Zend_Auth_Result(Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID,null,array($mensagem));
                } 
                else 
                {
                    $result = new Zend_Auth_Result(Zend_Auth_Result::SUCCESS,$this->_user,array());
                }
            }
            return $result;
        } 
        catch(Exception $e) 
        {
            throw new Zend_Auth_Adapter_Exception($e->getMessage());
        }
    }
}


