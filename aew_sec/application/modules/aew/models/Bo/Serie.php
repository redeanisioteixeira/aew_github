<?php

/**
 * BO da entidade Serie
 */
class Aew_Model_Bo_Serie extends Sec_Model_Bo_Abstract
{
    protected $nomeserie;
    
    /**
     * 
     * @return string
     */
    public function getNome() {
        return $this->nomeserie;
    }

    /**
     * 
     * @param string $nome
     */
    public function setNome($nome) 
    {
        $this->nomeserie = $nome;
    }
    /**
     * Construtor
     */
    public function __construct()
    {
        $this->setDao(new  Aew_Model_Dao_Serie());
    }

    /**
     * Recarrega a tabela de series
     */
    public function loadSeries()
    {
        $this->getDao()->loadSeries();
    }
    
    /**
     * preenche o objeto com dados de uma array (de mapeamento chave-valor)
     * @param array $data
     */
    function exchangeArray($data)
    {
        parent::exchangeArray($data);
        $this->setNome(isset($data['nomeserie']) ? $data['nomeserie']:null);
    }

    /**
     * 
     * @return \Aew_Model_Dao_Serie
     */
    protected function createDao() {
        $dao =  new Aew_Model_Dao_Serie();
        return $dao;
    }

}