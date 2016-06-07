<?php

/**
 * DAO da entidade ComuRelacionada
 */

class Aew_Model_Dao_ComuRelacionada extends Sec_Model_Dao_Abstract
{
    public function __construct() 
    {
        parent::__construct('comurelacionada', 'idcomurelacionada');
    }

    function buildQuery(array $data, $num = 0, $offset = 0, $options = null) {
        $q = parent::buildQuery($data, $num, $offset, $options);
        $q->joinLeft('comunidade', $this->getName().'.idcomunidaderelacionada = comunidade.idcomunidade');
        $q->joinLeft('usuario', 'usuario.idusuario = comunidade.idusuario');
        $q->joinLeft('comunidadefoto', 'comunidadefoto.idcomunidade = '.$this->getName().'.idcomunidaderelacionada',array('idcomunidadefoto','extensao'));
        return $q;
    }

    public function createModelBo() {
        return new Aew_Model_Bo_ComuRelacionada();
    }
}
