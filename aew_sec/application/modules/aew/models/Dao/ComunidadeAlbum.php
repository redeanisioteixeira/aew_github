<?php

/**
 * DAO da entidade ComunidadeAlbum
 */
class Aew_Model_Dao_ComunidadeAlbum extends Sec_Model_Dao_Abstract
{
    function __construct() {
        parent::__construct('comunidadealbum', 'idcomunidadealbum');
    }
   
     /**
     * constroi o sql da consulta
     * @param array $data
     * @param int $num
     * @param int $offset
     * @param array|string $options
     * @return Zend_Db_Select
     */
    public function buildQuery(array $data, $num = 0, $offset = 0, $options = null) {
        $q = parent::buildQuery($data, $num, $offset, $options);
        $q->order('datacriacao desc'); 
        return $q;
    }
    
    /**
     * 
     * @return \Aew_Model_Bo_ComunidadeAlbum
     */
    public function createModelBo() {
        return new Aew_Model_Bo_ComunidadeAlbum();
    }
}