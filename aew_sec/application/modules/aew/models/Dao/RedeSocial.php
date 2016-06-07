<?php

/**
 * DAO da entidade UsuarioMinhasRedesSociais
 */

class Aew_Model_Dao_RedeSocial extends Sec_Model_Dao_Abstract
{
    protected $_options  = array();

    function __construct() 
    {
        parent::__construct('redesocial','idredesocial');
    }   
    
    function buildQuery(array $data, $num = 0, $offset = 0, $options = null) {
        $q=parent::buildQuery($data, $num, $offset, $options);
        return $q;
    }
    public function createModelBo() 
    {
        return new Aew_Model_Bo_RedeSocial();
    }

}