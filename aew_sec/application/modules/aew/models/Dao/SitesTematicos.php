<?php
/**
 * DAO da entidade SitesTematicos
 */
class Aew_Model_Dao_SitesTematicos extends Sec_Model_Dao_Abstract
{
    
    function __construct() {
        parent::__construct('sitestematicos','id');
    }

    public function createModelBo() {
        return new Aew_Model_Bo_SitesTematicos();
    }

}