<?php

/**
 * DAO da entidade ComunidadeSugerida
 */

class Aew_Model_Dao_ComunidadeSugerida extends Sec_Model_Dao_Abstract
{
    
    public function __construct() 
    {
        parent::__construct('comunidadesugerida', array('idcomunidade','idusuario','idusuarioconvite'));
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
        $q->join('comunidade', $this->getName().'.idcomunidade = comunidade.idcomunidade');
        $q->join('usuario', $this->getName().'.idusuarioconvite = usuario.idusuario');
        $q->joinLeft('usuariofoto', $this->getName().'.idusuarioconvite = usuariofoto.idusuario');
        $q->joinLeft('comunidadefoto', 'comunidadefoto.idcomunidade = '.$this->getName().'.idcomunidade',array('idcomunidadefoto','extensao as ext_foto_comunidade'));
        
        return $q;
    }
    
    /**
     * cria BO entidade comunidade sugerida
     * @return \Aew_Model_Bo_ComunidadeSugerida
     */
    public function createModelBo() {
        return new Aew_Model_Bo_ComunidadeSugerida();
    }
}
