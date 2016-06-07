<?php
/**
 * DAO da entidade Usuario
 */
class Aew_Model_Dao_Usuario extends Sec_Model_Dao_Abstract
{
    
    public function __construct() 
    {
        parent::__construct('usuario','idusuario');
    }
    
    /**
     * Adiciona um servidor ao sistema
     * @param $cpf
     * @param $sexo
     * @param $dataNascimento
     * @param $email
     * @return Usuario
     */
    public function addUserServidor($cpf, $sexo, $dataNascimento, $email)
    {
        $usuarioWebService = $this->_getUsuarioWebService();
    }

    /**
     * Retorna os servidores da sec
     * @param $cpf
     * @param $sexo
     * @param $dataNascimento
     * @return Array|bool
     */
    public function getUsuarioServidorSec($cpf, $sexo, $dataNascimento, $email)
    {
        $secWebService = $this->_getSecWebService();
        $usuariosSec = $secWebService->obterServidor($cpf, $sexo, $dataNascimento);

        if($usuariosSec == false){
            return false;
        }

        $result = array();

        if(!is_array($usuariosSec)){
            $usuariosSec = array($usuariosSec);
        }
        foreach($usuariosSec as $usuarioSec)
        {
            if($usuarioSec instanceof stdClass) 
            {
                $usuario = new Aew_Model_Bo_Usuario();
		$usuario->setMatricula($usuarioSec->cadastro);
		$usuario->setNome(trim($usuarioSec->dadosPessoais->nomeconteudodigitalcategoria));
		$usuario->setRg($usuarioSec->dadosPessoais->rg);
		$usuario->setEndereco(trim($usuarioSec->dadosPessoais->endereco));
		$usuario->setNumero(trim($usuarioSec->dadosPessoais->numero));
		$usuario->setComplemento(trim($usuarioSec->dadosPessoais->complemento));
		$usuario->setBairro(trim($usuarioSec->dadosPessoais->bairro));
		$usuario->setCep($usuarioSec->dadosPessoais->cep);
		// TODO adicionar municipio quando Thiago corrigir web service
		//$usuario->idMunicipio = $usuarioSec->idMunicipio;
		$usuario->setTelefone($usuarioSec->dadosPessoais->telefone);
		$usuario->setCpf($cpf);
		$usuario->setSexo($sexo);
		$usuario->setDataNascimento($dataNascimento);
        	$usuario->setEmail($email);
    		$usuario->setCategoria('s');

                $result[] = $usuario;
            }
        }

        return $result;
    }

    /**
     * Retorna o servidor da sec pela matricula
     * @param $matricula
     * @return Usuario|bool
     */
    public function getUsuarioServidorSecByMatricula($matricula)
    {
        $secWebService = $this->_getSecWebService();
        $usuarioSec = $secWebService->obterServidorPorMatricula($matricula);
        if($usuarioSec instanceof stdClass) 
        {
	    $usuario = new Aew_Model_Bo_Usuario();
            $usuario->setNome($usuarioSec->nome);
            $usuario->setRg($usuarioSec->rg);
            $usuario->setEndereco($usuarioSec->endereco);
            $usuario->setNumero($usuarioSec->numero);
            $usuario->setComplemento($usuarioSec->complemento);
            $usuario->setBairro($usuarioSec->bairro);
            $usuario->setCep($usuarioSec->cep);
            $usuario->getMunicipio()->setId($usuarioSec->idMunicipio);
            $usuario->setTelefone($usuarioSec->telefone);
            return $usuario;
        } 
        else 
        {
            return false;
        }
    }

    /**
     * Verifica se é um professor
     * @param $matricula
     * @return bool
     */
    public function isProfessorSec($matricula)
    {
        $secWebService = $this->_getSecWebService();
        return $secWebService->isProfessor($matricula);
    }

    /**
     * Verifica se é um diretor
     * @param $matricula
     * @return bool
     */
    public function isDiretorSec($matricula)
    {
        $secWebService = $this->_getSecWebService();
        return $secWebService->isDiretor($matricula);
    }

    /**
     * Retorna um aluno da sec
     * @param $matricula
     * @return Usuario|bool
     */
    public function getUsuarioAlunoSec($matricula)
    {
        $secWebService = $this->_getSecWebService();
        $usuarioSec = $secWebService->obterAlunoPorMatricula($matricula);

        if($usuarioSec instanceof stdClass) 
        {
	    $usuario = new Aew_Model_Bo_Usuario();
            $usuario->setNome($usuarioSec->nome);
            $usuario->setRg($usuarioSec->rg);
            $usuario->setdataNascimento($usuarioSec->dataNascimento);
            $usuario->setEndereco($usuarioSec->endereco->endereco);
            $usuario->setComplemento( $usuarioSec->endereco->complemento);
            $usuario->setBairro($usuarioSec->endereco->bairro);
            $usuario->setCep($usuarioSec->endereco->cep);
            $usuario->setTelefone($usuarioSec->endereco->telefone);
            $usuario->setSexo(strtolower($usuarioSec->sexo));
            $escolaBo = new Aew_Model_Bo_Escola();
            $escolaBo->setId($usuarioSec->escola->idEscola);
            if($escolaBo->selectAutoDados())
            {
                $usuario->setEscola($escolaBo);
            }
            $serieBo = new Aew_Model_Bo_Serie();
            $serieBo->setId($usuarioSec->escola->idClientela);
            if($serieBo->selectAutoDados())
            {
                $usuario->setSerie($serieBo);
            }

            $munBo = new Aew_Model_Bo_Municipio();
            $munBo->setCodigoIbgeSiig($usuarioSec->endereco->codigoIbgeMunicipio);
            if($munBo = $munBo->select(1))
            {
                $usuario->setMunicipio($munBo);
            }
            $usuario->setMatricula($matricula);
            return $usuario;
        } 
        else 
        {
            return false;
        }
    }

    /**
     * Retorna um usuario do rasea
     * @param string $username
     * @return Aew_Model_Bo_Usuario|bool
     */
    public function getUsuarioRasea($username)
    {
        $raseaWebService = $this->_getRaseaWebService();
        $usuarioRasea = $raseaWebService->userDetail($username);

        if($usuarioRasea instanceof stdClass) 
        {
	    $usuario = new Aew_Model_Bo_Usuario();
            $usuario->setEmail($usuarioRasea->email);
            $usuario->setNome($usuarioRasea->displayName);
            $usuario->setUsername($username);
            return $usuario;
        } 
        else 
        {
            return false;
        }
    }

    /**
     * Registra o tipo do usuario no RASEA
     * @return bool
     */
    public function assignUsuario($username, $role)
    {
        $raseaWebService = $this->_getRaseaWebService();
        return $raseaWebService->assignUser($username, $role);
    }

    /**
     * Remove o tipo de usuario do RASEA
     * @param $username
     * @param $role
     */
    public function deassignUserRasea($username, $role)
    {
        $raseaWebService = $this->_getRaseaWebService();
        return $raseaWebService->deassignUser($username, $role);
    }

    /**
     * Retorna o web service da SEC
     * @return Sec_Service_Sec
     */
    protected function _getSecWebService()
    {
        if(self::$_secWebService == null){
            self::$_secWebService = new Sec_Service_Sec();
        }
        return self::$_secWebService;
    }
    

    /**
     * Retorna o web service do RASEA
     * @return Sec_Service_Rasea
     */
    protected function _getRaseaWebService()
    {
        if(self::$_raseaWebService == null){
            self::$_raseaWebService = new Sec_Service_Rasea();
        }
        return self::$_raseaWebService;
    }

    /**
     * Retorna o web service de Usuario
     * @return Sec_Service_Usuario
     */
    protected function _getUsuarioWebService()
    {
        if(self::$_usuarioWebService == null){
            self::$_usuarioWebService = new Sec_Service_Usuario();
        }
        return self::$_usuarioWebService;
    }

    function buildQuery(array $data = null, $num=0, $offset=0, $options=null) 
    {
        $q = parent::buildQuery($data, $num,$offset,$options);
        
        $q->joinLeft("usuariofoto", "usuariofoto.idusuario = usuario.idusuario",array('idusuariofoto','extensao'));
        $q->joinLeft('usuariotipo', 'usuariotipo.idusuariotipo = usuario.idusuariotipo');
        $q->joinLeft('usuariosobremimperfil', 'usuariosobremimperfil.idusuario = usuario.idusuario',array('sobremim','cidadenatal','lattes','dataenvio'));
        $q->joinLeft('municipio', 'municipio.idmunicipio = usuario.idmunicipio');
        $q->joinLeft('estado', 'estado.idestado = municipio.idestado');
        $q->order(array('nomeusuario','idusuario'));

        return $q;
    }
    
    protected function createModelBo() 
    {
        return new Aew_Model_Bo_Usuario();
    }
}