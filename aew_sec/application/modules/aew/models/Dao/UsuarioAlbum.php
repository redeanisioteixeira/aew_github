<?php

/**
 * DAO da entidade UsuarioAlbum
 */

class Aew_Model_Dao_UsuarioAlbum extends Sec_Model_Dao_Abstract
{
    function __construct() {
        parent::__construct('usuarioalbum', 'idusuarioalbum');
    }
    
    function buildQuery(array $data, $num = 0, $offset = 0, $options = null) {
        $q = parent::buildQuery($data, $num, $offset, $options);
        $q->order('datacriacao desc'); 
        return $q;
    }

    public function createModelBo() {
        return new Aew_Model_Bo_UsuarioAlbum();
    }
}