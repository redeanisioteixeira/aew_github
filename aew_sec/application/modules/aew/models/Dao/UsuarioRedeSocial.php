<?php

/**
 * DAO da entidade UsuarioMinhasRedesSociais
 */

class Aew_Model_Dao_UsuarioRedeSocial extends Sec_Model_Dao_Abstract
{
    function __construct() 
    {
        parent::__construct('usuarioredesocial','idusuarioredesocial');
    }
    
    /**
     * constroi o sql da consulta
     * @param array $data
     * @param int $num
     * @param int $offset
     * @param array|string $options
     * @return Zend_Db_Select
     */
    public function buildQuery(array $data =null, $num=0,$offset=0,$options=null) 
    {
        $q = parent::buildQuery($data, $num,$offset);
        $q->join("redesocial", "redesocial.idredesocial = usuarioredesocial.idredesocial");
        return $q;
    }

    /**
     * cria BO da entidade UsuarioRedeSocial
     * @return \Aew_Model_Bo_UsuarioRedeSocial
     */
    public function createModelBo() 
    {
        return new Aew_Model_Bo_UsuarioRedeSocial();
    }

}