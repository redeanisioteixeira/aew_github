<?php

/**
 * DAO da entidade ComunidadeFoto
 */

class Aew_Model_Dao_ComunidadeFoto extends Aew_Model_Dao_Foto
{
    function __construct() 
    {
        parent::__construct('comunidadefoto','idcomunidadefoto');
    }

    function buildQuery(array $data, $num = 0, $offset = 0, $options = null) {
        $q = parent::buildQuery($data, $num, $offset, $options);
        return $q;
    }
    public function createModelBo() 
    {
        return new Aew_Model_Bo_ComunidadeFoto();
    }
}