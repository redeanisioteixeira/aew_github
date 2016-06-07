<?php

/**
 * DAO da entidade ConteudoDigitalVoto
 */

class Aew_Model_Dao_ConteudoDigitalVoto extends Sec_Model_Dao_Abstract
{

    function __construct() {
        parent::__construct('conteudodigitalvoto','idconteudodigitalvoto');
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
        $q->join('usuario', $this->getName().'.idusuario = usuario.idusuario');
        return $q;
    }

    /**
     * cria objeto BO da entidade ConteudoDigitalVoto
     * @return \Aew_Model_Bo_ConteudoDigitalVoto
     */
    public function createModelBo() {
        return new Aew_Model_Bo_ConteudoDigitalVoto();
    }
}