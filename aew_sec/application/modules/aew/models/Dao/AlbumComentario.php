<?php

class Aew_Model_Dao_AlbumComentario extends Sec_Model_Dao_Abstract
{
    /**
     * Construtor
     */
    public function __construct()
    {
        parent::__construct('albumcomentario','idalbumcomentario');
    }

    function buildQuery(array $data, $num = 0, $offset = 0, $options = null) {
        $q = parent::buildQuery($data, $num, $offset, $options);
        $q->join('usuario', 'usuario.idusuario = '.$this->getName().'.idusuario',array('nomeusuario','idusuario'));
        $q->join('usuariofoto', 'usuariofoto.idusuario = '.$this->getName().'.idusuario');
        $q->order($this->getName().'.datacriacao desc'); 
        return $q;
    }

    public function createModelBo() 
    {
        return new Aew_Model_Bo_AlbumComentario(1,1);
    }

}