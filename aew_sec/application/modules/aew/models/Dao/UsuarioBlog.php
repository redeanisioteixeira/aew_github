<?php

/**
 * DAO da entidade UsuarioBlog
 */

class Aew_Model_Dao_UsuarioBlog extends Aew_Model_Dao_Blog
{
    function __construct() 
    {
        parent::__construct('usuarioblog','idusuarioblog');
    }
   
    public function buildQuery(array $data, $num = 0, $offset = 0, $options = null) {
        $q =  parent::buildQuery($data, $num, $offset, $options);   
        return $q;
    }
        
    public function createModelBo() 
    {
        return new Aew_Model_Bo_UsuarioBlog();
    }
}