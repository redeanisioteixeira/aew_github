<?php

/**
 * DAO da entidade Municipio
 */

class Aew_Model_Dao_Municipio extends Sec_Model_Dao_Abstract
{

    public function __construct() 
    {
        parent::__construct('municipio','idmunicipio');
    }
    
    function buildQuery(array $data, $num = 0, $offset = 0, $options = null)
    {
        $q =parent::buildQuery($data, $num, $offset, $options);
        $q->order('nomemunicipio');
        return $q;
    }
    
    public function createModelBo() 
    {
        return new Aew_Model_Bo_Municipio();
    }

}