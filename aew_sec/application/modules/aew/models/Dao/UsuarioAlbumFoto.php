<?php

/**
 * DAO da entidade UsuarioAlbumFoto
 */

class Aew_Model_Dao_UsuarioAlbumFoto extends Sec_Model_Dao_Abstract
{
    
    function __construct()
    {
        parent::__construct('usuarioalbumfoto','idusuarioalbumfoto');
    }

    function buildQuery(array $data, $num = 0, $offset = 0, $options = null) {
        $q = parent::buildQuery($data, $num, $offset, $options);
        $q->join('usuarioalbum', 'usuarioalbum.idusuarioalbum='.$this->getName().'.idusuarioalbum',array('idusuarioalbum','idusuario'));
        $q->join('usuario', 'usuario.idusuario=usuarioalbum.idusuario',array('idusuario'));
        return $q;
    }
    public function createModelBo() {
        return new Aew_Model_Bo_UsuarioAlbumFoto();
    }

}