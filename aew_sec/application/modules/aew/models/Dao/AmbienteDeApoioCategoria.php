<?php

/**
 * DAO da entidade AmbienteDeApoioCategoria
 */

class Aew_Model_Dao_AmbienteDeApoioCategoria extends Sec_Model_Dao_Abstract
{

   
    function __construct() {
        
        parent::__construct('ambientedeapoiocategoria','idambientedeapoiocategoria');
    }
    
    
    public function buildQuery(array $data, $num = 0, $offset = 0,$options=NULL) 
    {
        $q = parent::buildQuery($data, $num, $offset,$options);
        $q->order($this->_name.'.nomeambientedeapoiocategoria ASC');
        return $q;
    }

    public function createModelBo() {
        return new Aew_Model_Bo_AmbienteDeApoioCategoria();
    }

}