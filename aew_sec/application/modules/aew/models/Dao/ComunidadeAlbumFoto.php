<?php

/**
 * DAO da entidade ComunidadeAlbumFoto
 */

class Aew_Model_Dao_ComunidadeAlbumFoto extends Sec_Model_Dao_Abstract
{
    function __construct() 
    {
        parent::__construct('comunidadealbumfoto','idcomunidadealbumfoto');
    }
    
    function buildQuery(array $data, $num = 0, $offset = 0, $options = null) {
        $q = parent::buildQuery($data, $num, $offset, $options);
        $q->join('comunidadealbum', 'comunidadealbum.idcomunidadealbum='.$this->getName().'.idcomunidadealbum',array('idcomunidadealbum','idcomunidade'));
        $q->join('comunidade', 'comunidade.idcomunidade=comunidadealbum.idcomunidade',array('idcomunidade'));
        return $q;
    }
    
    public function createModelBo() 
    {
        return new Aew_Model_Bo_ComunidadeAlbumFoto();
    }
}