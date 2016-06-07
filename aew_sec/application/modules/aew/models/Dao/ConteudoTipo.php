<?php

/**
 * DAO da entidade Usuario
 */

class Aew_Model_Dao_ConteudoTipo extends Sec_Model_Dao_Abstract
{

    function __construct() {
        parent::__construct('conteudotipo','idconteudotipo');
    }

    /**
     * instancia model BO correspondente 
     * @return \Aew_Model_Bo_ConteudoTipo
     */
    public function createModelBo() 
    {
        return new Aew_Model_Bo_ConteudoTipo();
    }

}