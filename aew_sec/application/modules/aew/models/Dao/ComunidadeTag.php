<?php

/**
 * DAO da entidade ComunidadeTag
 */

class Aew_Model_Dao_ComunidadeTag extends Sec_Model_Dao_Abstract
{
    
    public function __construct() 
    {
        parent::__construct('comunidadetag', array('idcomunidade','idtag'));
    }

    function buildQuery(array $data, $num = 0, $offset = 0, $options = null)
    {
        $q = parent::buildQuery($data, $num, $offset, $options);
        $q->join('tag', 'tag.idtag = '.$this->getName().'.idtag');
        return $q;
    }
    
    public function createModelBo() {
        return new Aew_Model_Bo_ComunidadeTag();
    }
}