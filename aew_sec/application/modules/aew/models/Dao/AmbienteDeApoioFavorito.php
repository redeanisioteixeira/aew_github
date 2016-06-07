<?php

/**
 * DAO da entidade AmbienteDeApoioFavorito
 */

class Aew_Model_Dao_AmbienteDeApoioFavorito extends Sec_Model_Dao_Abstract
{

    public function __construct() 
    {
        parent::__construct('ambientedeapoiofavorito',array("idfavorito","idambientedeapoio"));

    }
    
    function buildQuery(array $data, $num = 0, $offset = 0, $options = null) {
        $q = parent::buildQuery($data, $num, $offset, $options);
        $q->join('ambientedeapoio', "ambientedeapoio.idambientedeapoio = ".$this->_name.".idambientedeapoio");
        $q->join('favorito', 'favorito.idfavorito='.$this->_name.'.idfavorito');
        $q->join('usuario', 'ambientedeapoio.idusuariopublicador = usuario.idusuario');
        return $q;
    }

    public function createModelBo() {
        return new Aew_Model_Bo_AmbienteDeApoioFavorito();
    }

}