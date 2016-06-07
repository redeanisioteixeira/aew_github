<?php

/**
 * DAO da entidade Enquete
 */

class Aew_Model_Dao_Enquete extends Sec_Model_Dao_Abstract
{
    function __construct()
    {
        parent::__construct('enquete', 'idenquete');
    }
    
    public function createModelBo() 
    {
        return new Aew_Model_Bo_Enquete();
    }

}