<?php
/**
 * Classe que usa o web service do USUARIO
 *
 * @author diegop
 *
 */
class Sec_Service_Usuario extends Sec_Service_Abstract {

    const WSDL_WS 				= 'http://sec746773:8080/sec-common-services/UsuarioWebService';

    const NAMESPACE_WS 			= 'http://ws.usuario.services.sec.ba.gov.br/';

    const HEADER_WS				= 'serviceHeader';

    protected $_username = 'aew';
    protected $_password = 'senha';

    protected $_application = 'aew';

    /**
     * Variavel que define se o WS esta sendo utilizado como Mock
     *
     * @var boolean
     */
    protected $_mock = true;

    /**
     *
     * @var Zend_Soap_Client
     */
    private static $_client;

    /**
     * Retorna uma instancia do Web Service de access control
     * @return Zend_Soap_Client
     */
    public function getInstance(){
        $this->_client = $this->_createWebService(self::WSDL_WS,
            										self::NAMESPACE_WS,
            										self::HEADER_WS);
        return $this->_client;
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

        $client = $this->getInstance();
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
    public function changePassword($username, $password) // TODO VERIFICAR
    {
        $user = array('username' => $username,
                      'password' => $password
        );

        if(true === $this->_mock){
            return true;
        }

        $client = $this->getInstance();

        try {
            $result = $client->changePassword($user);
            $result = $result->changed;
        } catch (Exception $e) {
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

        $client = $this->getInstance();
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
     * Adiciona um usuário Aluno no WS
     * @param $matricula
     * @param $sexo
     * @param $dataNascimento
     * @param $email
     * @return bool
     */
    public function addUserAluno($matricula, $sexo, $dataNascimento, $email)
    {
    	$user = array('user' => array('matricula' => $matricula,
    								  'sexo' => $sexo,
    								  'dataNascimento' => $dataNascimento,
    								  'email' => $email)
        );

    	if(true === $this->_mock)
        {
    	    $usuario = new stdClass();
            $usuario->username = "joaoa";
            $usuario->displayName = "João A. da Silva";
            $usuario->email = "joaoa@sec.gov.br";
            $usuario->alternateEmail = "";
            return $usuario;
        }

        $client = $this->getInstance();
        try 
        {
            $result = $client->addUserAluno($user);
        } 
        catch(Exception $e) 
        {
            return false;
        }
        return true;
    }

    /**
     * Adiciona um usuário Servidor no WS
     * @param $cpf
     * @param $sexo
     * @param $dataNascimento
     * @param $email
     * @return bool
     */
    public function addUserServidor($cpf, $sexo, $dataNascimento, $email)
    {
    	$user = array('user' => array('cpf' => $cpf,
                                      'sexo' => $sexo,
                                      'dataNascimento' => $dataNascimento,
                                      'email' => $email));

    	if(true === $this->_mock){
    	    $usuario = new stdClass();
            $usuario->username = "joaos";
            $usuario->displayName = "João S. da Silva";
            $usuario->email = "joaos@sec.gov.br";
            $usuario->alternateEmail = "";
            return $usuario;
        }

        $client = $this->getInstance();
        try {
            $result = $client->addUserServidor($user);
        } catch(Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * Adiciona um usuário Comunidade no WS
     * @param $cpf
     * @param $nome
     * @param $email
     * @param $emailAlternativo
     * @return bool
     */
    public function addUserComunidade($cpf, $nome, $email, $emailAlternativo)
    {
    	$user = array('user' => array('cpf' => $cpf,
    								  'nome' => $nome,
    								  'email' => $email,
    								  'emailAlternativo' => $emailAlternativo)
        );

    	if(true === $this->_mock){
    	    $usuario = new stdClass();

			$usuario->username = "joaoc";
			$usuario->displayName = "João C. da Silva";
			$usuario->email = "joaoc@sec.gov.br";
			$usuario->alternateEmail = "";

            return $usuario;
        }

        $client = $this->getInstance();
        try {
            $result = $client->addUserComunidade($user);
        } catch(Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * Define o papel de um usuario no WS
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

        $client = $this->getInstance();

        try {
            $result = $client->assignUser($user);
        } catch(Exception $e) {
            return false;
        }

        return true;
    }


    /**
     * Remove o papel de um usuario no WS
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

        $client = $this->getInstance();

        try {
            $result = $client->deassignUser($user);
        } catch(Exception $e) {
            return false;
        }

        return true;
    }
}
