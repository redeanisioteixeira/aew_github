<?php
/**
 * Classe que usa o web service do RASEA
 * 
 * @author diegop
 *
 */
class Sec_Service_Rasea extends Sec_Service_Abstract {
    
    const WSDL_RASEA_AC 				= 'http://127.0.0.1:8080/rasea/services/AccessControl?wsdl';
    const WSDL_RASEA_MANAGEMENT 		= 'http://127.0.0.1:8080/rasea/services/Management?wsdl';
    
    const NAMESPACE_RASEA_AC 			= 'http://rasea.org/ws/AccessControl';
    const NAMESPACE_RASEA_MANAGEMENT 	= 'http://rasea.org/ws/Management';
    
    const HEADER_RASEA					= 'raseaServiceHeader';
    
    protected $_username = 'rasea';
    protected $_password = 'rasea';
    
    protected $_application = 'aew';
    
    /**
     * Variavel que define se o Rasea esta sendo utilizado como Mock
     * 
     * @var boolean
     */
    protected $_mock = true;
    
    /**
     * 
     * @var Zend_Soap_Client
     */
    private static $_clientAc;
    
    /**
     * 
     * @var Zend_Soap_Client
     */
    private static $_clientManagement;
    
    /**
     * Retorna uma instancia do Web Service de access control
     * @return Zend_Soap_Client
     */
    public function getInstanceAc(){
        //if(NULL === $this->_clientAc){
            $this->_clientAc = $this->_createWebService(self::WSDL_RASEA_AC,
            											self::NAMESPACE_RASEA_AC,
            											self::HEADER_RASEA);
        //}
        return $this->_clientAc;
    }
    
    /**
     * Retorna uma instancia do Web Service de management
     * @return Zend_Soap_Client
     */
    public function getInstanceManagement(){
        //if(NULL === $this->_clientAc){
            $this->_clientManagement = $this->_createWebService(self::WSDL_RASEA_MANAGEMENT,
            													 self::NAMESPACE_RASEA_MANAGEMENT,
            													 self::HEADER_RASEA);
        //}
        return $this->_clientManagement;
    }
    
    /**
     * Verifica se um usuario eh valido
     * 
     * @param $username
     * @param $password
     * @return bool
     */
    public function authenticate($username, $password)
    {
        $user = array('username' => $username,
                      'password' => $password
        );
        
        if(true === $this->_mock){
            return true;
        }
        
        $client = $this->getInstanceAc();
        $valid = $client->authenticate($user);
        $result = $valid->authenticated;
        
        return $result;
    }
    
    /**
     * Troca a senha de um usuário
     * 
     * @param $username
     * @param $password
     * @return bool
     */
    public function changePassword($username, $password)
    {
        $user = array('username' => $username,
                      'password' => $password
        );
        
        if(true === $this->_mock){
            return true;
        }
        
        $client = $this->getInstanceAc();
        
        try {
            $result = $client->changePassword($user);
            $result = $result->changed;
        } catch(Exception $e) {
            return false;
        }
        
        return $result;
    }
    
    /**
     * Retorna informacoes do usuario
     * 
     * @param $username
     * @return Object
     */
    public function userDetail($username)
    {
        $user = array('username' => $username);
        
	    if(true === $this->_mock){
	    	
	        if($username != 'a3' && $username != 's3'){
	    		return false;
	    	}
	    	
        	$usuario = new stdClass();
        	
			$usuario->username = "joao";
			$usuario->displayName = "João da Silva";
			$usuario->email = "joao@sec.gov.br";
			$usuario->alternateEmail = "";

			return $usuario;
	    }
        
        $client = $this->getInstanceAc();
        try {
            $result = $client->userDetail($user);            
        } catch (Exception $e) {
            return false;
        }
        
        return $result->user;
    }
    
    /**
     * Retorna se um usuario tem permissoes no AEW
     * 
     * @param $username
     * @return bool
     */
    public function userPermission($username)
    {
        $user = array('username' => $username,
                      'application' => $this->_application
        );
        
        if(true === $this->_mock){
            return true;
        }
        
        $client = $this->getInstanceAc();
        
        try {
            $permissions = $client->userPermissions($user);
        } catch (Exception $e) {
            return false;
        }
        
        $result = (isset($permissions->permissions))? true : false;
        
        return $result;
    }
    
    /**
     * Adiciona um usuário no rasea
     * @param $username
     * @param $name
     * @param $email
     * @param $password
     * @return bool
     */
    public function addUser($username, $name, $email, $password)
    {
    	$user = array('user' => array('username' 	=> $username,
    								  'displayName' => $name,
    								  'email' 		=> $email),
                      'password' => $password
        );
        
    	if(true === $this->_mock){
            return true;
        }
        
        $client = $this->getInstanceManagement();
        try {
            $result = $client->addUser($user);
        } catch(Exception $e) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Define o papel de um usuario no RASEA
     * @param $username
     * #param $role
     * @return bool
     */
    public function assignUser($username, $role)
    {
    	$user = array('username' 	=> $username,
   					  'role' 		=> $role,
    				  'application' => $this->_application);

    	if(true === $this->_mock){
            return true;
        }
        
        $client = $this->getInstanceManagement();
        
        try {
            $result = $client->assignUser($user);
        } catch(Exception $e) {
            return false;
        }
        
        return true;
    }
    
    
    /**
     * Remove o papel de um usuario no RASEA
     * @param $username
     * #param $role
     * @return bool
     */
    public function deassignUser($username, $role)
    {
    	$user = array('username' 	=> $username,
   					  'role' 		=> $role,
    				  'application' => $this->_application);

    	if(true === $this->_mock){
            return true;
        }
        
        $client = $this->getInstanceManagement();
        
        try {
            $result = $client->deassignUser($user);
        } catch(Exception $e) {
            return false;
        }
        
        return true;
    }
}
