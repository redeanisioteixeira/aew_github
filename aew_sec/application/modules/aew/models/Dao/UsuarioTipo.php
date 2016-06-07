<?php

/**
 * DAO da entidade UsuarioTipo
 */

class Aew_Model_Dao_UsuarioTipo extends Sec_Model_Dao_Abstract
{
    public function __construct() 
    {
        parent::__construct('usuariotipo','idusuariotipo');
    }
    
    public function createModelBo() 
    {
        return new Aew_Model_Bo_UsuarioTipo();
    }

}