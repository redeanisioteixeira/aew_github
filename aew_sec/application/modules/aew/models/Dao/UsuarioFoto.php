<?php
/**
 * DAO da entidade UsuarioFoto
 */
class Aew_Model_Dao_UsuarioFoto extends Sec_Model_Dao_Abstract
{
    function __construct() 
    {
        parent::__construct('usuariofoto','idusuariofoto');
    }

    
    public function createModelBo() 
    {
        return new Aew_Model_Bo_UsuarioFoto();
    }
}