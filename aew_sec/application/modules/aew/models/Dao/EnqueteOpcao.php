<?php

/**
 * DAO da entidade EnqueteOpcao
 */

class Aew_Model_Dao_EnqueteOpcao extends Sec_Model_Dao_Abstract
{
    function __construct()
    {
        parent::__construct('enqueteopcao','idenqueteopcao');
    }
    
    protected function createModelBo()
    {
        return new Aew_Model_Bo_EnqueteOpcao();
    }
}