<?php

/**
 * DAO da entidade ConteudoDigitalFavorito
 */

class Aew_Model_Dao_ConteudoDigitalFavorito extends Sec_Model_Dao_Abstract
{

    function __construct() 
    {
        parent::__construct('conteudodigitalfavorito','idconteudodigital');
    }
    
    /**
     * constroi o sql da consulta
     * @param array $data
     * @param int $num
     * @param int $offset
     * @param array|string $options
     * @return Zend_Db_Select
     */
    function buildQuery(array $data, $num = 0, $offset = 0,$options=null) {
        $q=parent::buildQuery($data, $num, $offset,$options);
        $q->join('conteudodigital', "conteudodigital.idconteudodigital = ".$this->_name.".idconteudodigital");
        $q->join('favorito', 'favorito.idfavorito='.$this->_name.'.idfavorito');
        $q->join("conteudolicenca", "conteudolicenca.idconteudolicenca = conteudodigital.idlicencaconteudo");
        $q->join('formato', 'formato.idformato = conteudodigital.idformato');
        $q->join('usuario', 'conteudodigital.idusuariopublicador = usuario.idusuario');
        return $q;
    }
    
    /**
     * cria BO da entidade ConteudoDgitalfavorito
     * @return \Aew_Model_Bo_ConteudoDigitalFavorito
     */
    public function createModelBo() {
        return new Aew_Model_Bo_ConteudoDigitalFavorito();
    }
}