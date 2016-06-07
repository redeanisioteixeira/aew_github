<?php

/**
 * DAO da entidade Conteudo Licenca
 */

class Aew_Model_Dao_ConteudoLicenca extends Sec_Model_Dao_Abstract
{
    function __construct()
    {
        parent::__construct('conteudolicenca','idconteudolicenca');
    }
    
    function buildQuery(array $data, $num = 0, $offset = 0, $options = null) {
        $q = parent::buildQuery($data, $num, $offset, $options);
        return $q;
    }
    
    public function createModelBo() {
        return new Aew_Model_Bo_ConteudoLicenca();
    }
}