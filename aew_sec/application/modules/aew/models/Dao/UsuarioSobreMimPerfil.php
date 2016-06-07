<?php

/**
 * DAO da entidade UsuarioSobreMimPerfil
 */

class Aew_Model_Dao_UsuarioSobreMimPerfil extends Sec_Model_Dao_Abstract
{
    function __construct() 
    {
        parent::__construct('usuariosobremimperfil', 'idusuario');
    }

    public function createModelBo() 
    {
        return new Aew_Model_Bo_UsuarioSobreMimPerfil();
    }

}