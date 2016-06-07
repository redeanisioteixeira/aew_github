<?php

/**
 * DAO da entidade Usuario
 */

class Aew_Model_Dao_ConteudoDigitalComentario extends Sec_Model_Dao_Abstract
{
    function __construct() {
        parent::__construct('conteudodigitalcomentario','idconteudodigitalcomentario');
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
        $q->join('usuario', 'usuario.idusuario = '.$this->getName().'.idusuario',array('nomeusuario','idusuario'));
        $q->joinLeft('usuariofoto', 'usuariofoto.idusuario = '.$this->getName().'.idusuario');
        $q->order($this->getName().'.datacriacao desc');
        if(isset($data['idconteudodigitalcategoria']))
        {
            $q->join('conteudodigital', 'conteudodigital.idconteudodigital = conteudodigitalcomentario.idconteudodigital');
            $q->join('conteudodigitalcategoria', 'conteudodigitalcategoria.idconteudodigitalcategoria = conteudodigital.idconteudodigitalcategoria');
        }
        
        return $q;
    }

    /**
     * cria BO da entidade ConteudoDigitalComentario
     * @return \Aew_Model_Bo_ConteudoDigitalComentario
     */
    public function createModelBo() 
    {
        return new Aew_Model_Bo_ConteudoDigitalComentario();
    }
}