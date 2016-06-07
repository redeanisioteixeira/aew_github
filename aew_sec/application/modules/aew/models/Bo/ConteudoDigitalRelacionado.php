<?php

/**
 * BO da entidade ConteudoDigitalRelacionado
 */

class Aew_Model_Bo_ConteudoDigitalRelacionado extends Sec_Model_Bo_Abstract
{
    protected $idconteudodigital; //int(11)
    protected $idconteudodigitalrelacionado; //int(11)
    
    /**
     * retorna os parametro do objeto em um array
     * onde a chave e o nome da variavel de instancia e tambem
     * o nome do campo na tabela
     * @return array
     */
    function toArray()
    {
        $data = parent::toArray();
        if($this->getIdconteudodigital())
        {
             $data['idconteudodigital'] = $this->getIdconteudodigital();
        }
        if($this->getIdconteudodigitalrelacionado())
        {
             $data['idconteudodigitalrelacionado'] = $this->getIdconteudodigitalrelacionado();
        }
        return $data;
    }

    /**
     * preenche o objeto com dados de uma array (de mapeamento chave-valor)
     * @param array $data
     */
    public function exchangeArray($data){
         parent::exchangeArray($data);
              $this->setIdconteudodigital(isset($data['idconteudodigital'])? $data['idconteudodigital']: null);
              $this->setIdconteudodigitalrelacionado(isset($data['idconteudodigitalrelacionado'])? $data['idconteudodigitalrelacionado']: null);
	}

	/**
	 * @return idconteudodigital - int(11)
	 */
	public function getIdconteudodigital(){
		return $this->idconteudodigital;
	}

	/**
	 * @return idconteudodigitalrelacionado - int(11)
	 */
	public function getIdconteudodigitalrelacionado(){
		return $this->idconteudodigitalrelacionado;
	}

	/**
	 * @param Type: int(11)
	 */
	public function setIdconteudodigital($idconteudodigital){
		$this->idconteudodigital = $idconteudodigital;
	}

	/**
	 * @param Type: int(11)
	 */
	public function setIdconteudodigitalrelacionado($idconteudodigitalrelacionado){
		$this->idconteudodigitalrelacionado = $idconteudodigitalrelacionado;
	}
    /**
     * Cria o relacionamento entre dois conteudos
     * @param int $id1
     * @param int $id2
     * @return ConteudoDigitalRelacionado
     */
    public function relacionar($id1, $id2)
    {
        if($this->isRelacionado($id1, $id2)){
           return false;
        }

        $relacionamento = new ConteudoDigitalRelacionado();
        $relacionamento['idConteudoDigital'] = $id1;
        $relacionamento['idConteudoDigitalRelacionado'] = $id2;

        return $this->save($relacionamento);
    }

    /**
     * Retorna se dois conteudos estao relacionados
     * @param int $conteudo1
     * @param int $conteudo2
     * @return bool
     */
    public function isRelacionado($conteudo1, $conteudo2)
    {
        return $this->getDao()->isRelacionado($conteudo1, $conteudo2);
    }

    /**
     * cria objeto de acesso ao banco de dados
     * @return \Aew_Model_Dao_ConteudoDigitalRelacionado
     */
    protected function createDao() {
        return new Aew_Model_Dao_ConteudoDigitalRelacionado();
    }

}