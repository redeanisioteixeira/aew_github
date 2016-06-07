<?php

/**
 * 
 *Dao da entidade Dispositivo
 * @author tiago
 */
class Aew_Model_Dao_Dispositivo extends Sec_Model_Dao_Abstract
{
    
    function __construct() 
    {
        parent::__construct('dispositivo', 'iddispositivo');
    }
    
    /**
     * cria BO da entidade Dispositivo
     * @return \Aew_Model_Bo_Dispositivo
     */
    protected function createModelBo() 
    {
        return new Aew_Model_Bo_Dispositivo();
    }
    //put your code here
}
