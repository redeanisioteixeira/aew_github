<?php

/**
 * DAO da entidade ComuVoto
 */

class Aew_Model_Dao_ComuVoto extends Sec_Model_Dao_Abstract
{

    function __construct()
    {
        parent::__construct("comuvoto",'idcomuvoto');
    }
    
    /**
     * constroi o sql da consulta
     * @param array $data
     * @param int $num
     * @param int $offset
     * @param array|string $options
     * @return Zend_Db_Select
     */
    function buildQuery(array $data, $num = 0, $offset = 0, $options = null)
    {
        $q = parent::buildQuery($data, $num, $offset, $options);
        $q->join('usuario', 'usuario.idusuario =  comuvoto.idusuario');
        return $q;
    }
    
    /**
     * 
     * @return \Aew_Model_Bo_ComuVoto
     */
    public function createModelBo() 
    {
        return new Aew_Model_Bo_ComuVoto();
    }

}