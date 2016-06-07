<?php
/**
 * Description of Canal
 *
 * @author tiago
 */
class Aew_Model_Dao_Canal extends Sec_Model_Dao_Abstract
{
    /**
     * Construtor
     */
    public function __construct()
    {
        parent::__construct('canal','idcanal');
    }

    
    public function createModelBo() 
    {
        return new Aew_Model_Bo_Canal();
    }

}
