<?php

/**
 * BO da entidade Escola
 */
class Aew_Model_Bo_Escola extends Sec_Model_Bo_Abstract
{
    protected $nomeescola,$codigomec;
    protected $municipio;
    /**
     * Construtor
     */
    public function __construct()
    {
        $this->setDao(new  Aew_Model_Dao_Escola());
        $this->setMunicipio(new Aew_Model_Bo_Municipio());
    }
    
    /**
     * preenche o objeto com dados de uma array (de mapeamento chave-valor)
     * @param array $data
     */
    public function exchangeArray($data)
    {
        parent::exchangeArray($data);
        $this->setNome(isset($data['nomeescola'])? $data['nomeescola']: null);
        $this->setCodigomec(isset($data['codigomec'])? $data['codigomec']: null);
        $this->getMunicipio()->exchangeArray($data);
    }
    
    /**
     * 
     * @return Aew_Model_Bo_Municipio
     */
    public function getMunicipio() {
        return $this->municipio;
    }

    /**
     * 
     * @param Aew_Model_Bo_Municipio $municipio
     */
    public function setMunicipio($municipio) {
        $this->municipio = $municipio;
    }

    /**
     * 
     * @return string
     */
    public function getNome() {
        return $this->nomeescola;
    }

    /**
     * 
     * @return string
     */
    public function getCodigoMec() {
        return $this->codigomec;
    }

    /**
     * 
     * @param string $nome
     */
    public function setNome($nome) {
        $this->nomeescola = $nome;
    }

    /**
     * 
     * @param string $codigoMec
     */
    public function setCodigoMec($codigoMec) {
        $this->codigomec = $codigoMec;
    }

    
    /**
     * Recarrega a tabela de escolas
     */
    public function loadEscolas()
    {
        $this->getDao()->loadEscolas();
    }

    /**
     * 
     * @return \Aew_Model_Dao_Escola
     */
    protected function createDao() {
        $dao =  new Aew_Model_Dao_Escola();
        return $dao;
    }

}