<?php

/**
 * DAO da entidade FeedTipo
 */

class Aew_Model_Dao_FeedTipo extends Sec_Model_Dao_Abstract
{
    
    function __construct() 
    {
        parent::__construct("feedtipo", "idfeedtipo");
    }
    
    /**
     * entidade BO feedtipo
     * @return \Aew_Model_Bo_FeedTipo
     */
    public function createModelBo() {
        return new Aew_Model_Bo_FeedTipo();
    }

}
