<?php
/**
 * DAO da entidade NivelEnsino
 */
class Aew_Model_Dao_NivelEnsino extends Sec_Model_Dao_Abstract
{
    public function __construct() 
    {
        parent::__construct('nivelensino','idnivelensino');
    }

    public function createModelBo() 
    {
        return new Aew_Model_Bo_NivelEnsino();
    }

    function buildQuery(array $data, $num = 0, $offset = 0, $options = null)
    {
        $q = parent::buildQuery($data, $num, $offset, $options);
        $q->order("idnivelensino");
        return $q;
    }
}