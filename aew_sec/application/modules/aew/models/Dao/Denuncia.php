<?php

/**
 * DAO da entidade Denuncia
 */

class Aew_Model_Dao_Denuncia extends Sec_Model_Dao_Abstract
{
    function __construct()
    {
        parent::__construct("denuncia", "iddenuncia");
    }
    
    function buildQuery(array $data, $num = 0, $offset = 0, $options = null) 
    {
        $q = parent::buildQuery($data, $num, $offset, $options);
        $q->joinLeft('usuario', $this->getName().'.idusuario = usuario.idusuario');
        return $q;
    }
    
    public function createModelBo() {
        return new Aew_Model_Bo_Denuncia();
    }

}