<?php

class Sec_Service_Sec extends Sec_Service_Abstract {

    const WSDL_SEC_UNIDADE_GEOGRAFICA = "http://webn01.sec.ba.gov.br/sec-common-unidgeografica/UnidGeograficaWebService?wsdl";
    const WSDL_SEC_UNIDADE_ESCOLAR = "http://www3.sec.ba.gov.br/sec-common-unidescolar/UnidEscolarWS?wsdl";
    const WSDL_SEC_SERVIDOR = "http://www3.sec.ba.gov.br/sec-common-servidor/ServidorWebService?wsdl";
    const WSDL_SEC_ALUNO = "http://webn01.sec.ba.gov.br/sec-common-aluno/AlunoWebService?wsdl";
    const WSDL_SEC_SERIE = "http://webn01.sec.ba.gov.br/sec-common-clientela/ClientelaWebService?wsdl";

    const NAMESPACE_SEC_UNIDADE_GEOGRAFICA = 'http://ws.unidgeografica.sec.ba.gov.br/';
    const NAMESPACE_SEC_UNIDADE_ESCOLAR = 'http://ws.escola.sec.ba.gov.br/';
    const NAMESPACE_SEC_SERVIDOR = 'http://ws.servidor.sec.ba.gov.br/';
    const NAMESPACE_SEC_ALUNO  = 'http://ws.aluno.sec.ba.gov.br/';
    const NAMESPACE_SEC_SERIE  = 'http://ws.clientela.sec.ba.gov.br/';

    const HEADER_SEC					    = 'secServiceHeader';

    protected $_username = /*solicitar usuario*/ "";
    protected $_password = /*solicitar senha*/"";

    /**
     * Variavel que define se o Rasea esta sendo utilizado como Mock
     *
     * @var boolean
     */
    protected $_mock = false;

    /**
     * Cliente do Web Service de Unidade Geográfica
     * @var Zend_Soap_Client
     */
    private static $_clientUnidadeGeografica;

    /**
     * Cliente do Web Service de Servidor
     * @var Zend_Soap_Client
     */
    private static $_clientServidor;

    /**
     * Cliente do Web Service de Aluno
     * @var Zend_Soap_Client
     */
    private static $_clientAluno;

    /**
     * Retorna uma instancia do Web Service de Servidor
     *
     * @return Zend_Soap_Client
     */
    public function getInstanceServidor(){
        $this->_clientServidor = $this->_createWebService(self::WSDL_SEC_SERVIDOR);
        $this->_clientServidor->setLocation('http://www3.sec.ba.gov.br/sec-common-servidor/ServidorWebService');
        return $this->_clientServidor;
    }

    /**
     * Retorna uma instancia do Web Service de Aluno
     *
     * @return Zend_Soap_Client
     */
    public function getInstanceAluno(){
        $this->_clientAluno = $this->_createWebService(self::WSDL_SEC_ALUNO);
        $this->_clientAluno->setLocation('http://webn01.sec.ba.gov.br/sec-common-aluno/AlunoWebService');
        return $this->_clientAluno;
    }

    /**
     * Retorna uma instancia do Web Service de Clientela
     *
     * @return Zend_Soap_Client
     */
    public function getInstanceClientela(){
        $ws = $this->_createWebService(self::WSDL_SEC_SERIE);
        $ws->setLocation('http://webn01.sec.ba.gov.br/sec-common-clientela/ClientelaWebService');
        return $ws;
    }

    /**
     * Retorna uma instancia do Web Service de Unidade Escolar
     *
     * @return Zend_Soap_Client
     */
    public function getInstanceUnidadeEscolar(){
        $ws = $this->_createWebService(self::WSDL_SEC_UNIDADE_ESCOLAR);
        $ws->setLocation('http://webn01.sec.ba.gov.br/sec-common-unidescolar/UnidEscolarWS');
        return $ws;
    }

    /**
     * Retorna uma instancia do Web Service de Unidade Geografica
     *
     * @return Zend_Soap_Client
     */
    public function getInstanceUnidadeGeografica(){
        $ws = $this->_createWebService(self::WSDL_SEC_UNIDADE_GEOGRAFICA);
        $ws->setLocation('http://webn01.sec.ba.gov.br/sec-common-unidgeografica/UnidGeograficaWebService');
        return $ws;
    }

	/**
	 * Obtém um servidor
	 * @param $cpf
	 * @param $sexo
	 * @param $dataNascimento
	 * @return obj Servidor
	 */
    public function obterServidor($cpf, $sexo, $dataNascimento)
    {
        $user = array('cpf' => (float)$cpf,
                      'sexo' => strtoupper($sexo),
        			  'dataNascimento' => $dataNascimento
        );

        if(true === $this->_mock){
        	$result = array();

        	if(!is_numeric($cpf)){
        		return $result;
        	}

        	if($cpf == 3){
	        	$servidor = new stdClass();

				$servidor->cadastro = "3";
				$servidor->nome = "João da Silva";
				$servidor->rg = "978571241";
				$servidor->endereco = "R: dos Jardineiros";
				$servidor->numero = "15";
				$servidor->complemento = "";
				$servidor->bairro = "Japorã";
				$servidor->cep = "84295-942";
				$servidor->idMunicipio = "1";
				$servidor->telefone = "(71)3253-9142";

				$result[] = $servidor;
				return $result;
        	}

        	if($cpf > 0){
	        	$servidor = new stdClass();

				$servidor->cadastro = "1";
				$servidor->nome = "João da Silva";
				$servidor->rg = "978571241";
				$servidor->endereco = "R: dos Jardineiros";
				$servidor->numero = "15";
				$servidor->complemento = "";
				$servidor->bairro = "Japorã";
				$servidor->cep = "84295-942";
				$servidor->idMunicipio = "1";
				$servidor->telefone = "(71)3253-9142";

				$result[] = $servidor;
        	}

        	if($cpf > 1){
	        	$servidor = new stdClass();

				$servidor->cadastro = "2";
				$servidor->nome = "João da Silva";
				$servidor->rg = "5232622346";
				$servidor->endereco = "R: dos Jardineiros";
				$servidor->numero = "28";
				$servidor->complemento = "";
				$servidor->bairro = "Itinga";
				$servidor->cep = "14751-323";
				$servidor->idMunicipio = "1";
				$servidor->telefone = "(71)3215-8626";

				$result[] = $servidor;
        	}

            return $result;
        }

        $client = $this->getInstanceServidor();
        $result = $client->obterServidorAtivo($user);

        if(isset($result->return))
            return $result->return;
        else
            return false;
    }

    /**
     * Obtém um servidor por sua matricula
     * @param $matricula
     * @return Usuario|bool
     */
    public function obterServidorPorMatricula($matricula)
    {
   		if(!is_numeric($matricula)){
        	return false;
        }

        $user = array('matricula' => (float)$matricula);

        if(true === $this->_mock){
            if($matricula == 0){
                return false;
            }

        	$servidor = new stdClass();

			$servidor->cadastro = "1";
			$servidor->nome = "João da Silva";
			$servidor->rg = "1412512512";
			$servidor->endereco = "R: dos Jardineiros";
			$servidor->numero = "15";
			$servidor->complemento = "";
			$servidor->bairro = "Japorã";
			$servidor->cep = "84295-942";
			$servidor->idMunicipio = "1";
			$servidor->telefone = "(71)3253-9142";

            return $servidor;
        }

        $client = $this->getinstanceAl();
        $result = $client->obterServidorPorCadastro($user);

        return $result;
    }

	/**
	 * Obtém um aluno
	 * @param $matricula
	 * @return obj Aluno
	 */
    public function obterAlunoPorMatricula($matricula)
    {
   		if(!is_numeric($matricula)){
        	return false;
        }

        $user = array('matriculaAluno' => (float)$matricula);

        if(true === $this->_mock){
            if($matricula == 0){
                return false;
            }

        	$aluno = new stdClass();

			$aluno->idAluno = "1";
			$aluno->nome = "João da Silva";
			$aluno->rg = "978571241";
			$aluno->dataNascimento = "1987-09-04";
			$aluno->endereco = "R: dos Jardineiros, 20";
			$aluno->complemento = "";
			$aluno->bairro = "Japorã";
			$aluno->cep = "84295-942";
			$aluno->idMunicipio = "1";
			$aluno->sexo = "m";
			$aluno->ddd = "71";
			$aluno->telefone = "(71)3253-9142";
			$aluno->idEscola = "1";
			$aluno->idClientela = "1";

            return $aluno;
        }

        $client = $this->getInstanceAluno();
        $result = $client->obterPorPeriodoAtual($user);

        if(isset($result->return))
            return $result->return;
        else
            return false;
    }

	/**
	 * Retorna se a matricula é de diretor
	 * @param $matricula
	 * @return bool
	 */
    public function isDiretor($matricula)
    {
        $user = array('cadastro' => $matricula);

        if(true === $this->_mock){
			if($matricula == 3){
				return true;
			} else {
				return false;
			}
        }

        $client = $this->getInstanceServidor();
        $result = $client->isDiretor($user);

        if(isset($result->return))
            return $result->return;
        else
            return false;
    }

	/**
	 * Retorna se a matricula é de professor
	 * @param $matricula
	 * @return bool
	 */
    public function isProfessor($matricula)
    {
        $user = array('cadastro' => $matricula);

        if(true === $this->_mock){
			if($matricula == 2){
				return true;
			} else {
				return false;
			}
        }

        $client = $this->getInstanceServidor();
        $result = $client->isProfessor($user);

        if(isset($result->return))
            return $result->return;
        else
            return false;
    }

    /**
     * Retorna todas as series
     * @return array
     */
    public function obterTodasSeries()
    {
        $client = $this->getInstanceClientela();
        $result = $client->obterTodas();

        if(isset($result->return))
            return $result->return;
        else
            return false;
    }

    /**
     * Retorna todas as escolas
     * @return array
     */
    public function obterTodasEscolas()
    {
        $client = $this->getInstanceUnidadeEscolar();
        $result = $client->obterTodas();

        if(isset($result->return))
            return $result->return;
        else
            return false;
    }

    /**
     * Retorna todos os estados
     * @return array
     */
    public function obterTodosEstados()
    {
        $client = $this->getInstanceUnidadeGeografica();
        $result = $client->obterEstados();

        if(isset($result->return))
            return $result->return;
        else
            return false;
    }

    /**
     * Retorna todos os municipios de um estado
     * @return array
     */
    public function obterTodosMunicipiosPorEstado($codigoIbge)
    {
        $request = array('codigoIbgeEstado' => (float)$codigoIbge);

        $client = $this->getInstanceUnidadeGeografica();
        $result = $client->obterPorCodigoIbge($request);

        if(isset($result->return))
            return $result->return;
        else
            return false;
    }
}
