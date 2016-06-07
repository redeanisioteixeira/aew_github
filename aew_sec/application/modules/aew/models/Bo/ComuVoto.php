<?php

/**
 * BO da entidade ComuVoto
 */

class Aew_Model_Bo_ComuVoto extends Aew_Model_Bo_Voto
{
    protected $idcomunidade;

    function exchangeArray($data) {
        parent::exchangeArray($data);
        $this->setIdcomunidade($data['idcomunidade']);
    }
    
    /**
     * retorna o id da comunidade relacionada ao voto
     * @return int
     */
    public function getIdcomunidade()
    {
        return $this->idcomunidade;
    }

    /**
     * 
     * @param int $idcomunidade
     */
    public function setIdcomunidade($idcomunidade)
    {
        $this->idcomunidade = $idcomunidade;
    }

    /**
     * cria objeto de acesso ao banco de dados
     * @return \Aew_Model_Dao_ComuVoto
     */
    protected function createDao() {
        return new Aew_Model_Dao_ComuVoto();
    }

}