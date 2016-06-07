<?php

/**
 * DAO da entidade ComuTopicoMsg
 */

class Aew_Model_Dao_ComuTopicoMsg extends Aew_Model_Dao_Comentario
{
    public function __construct() {
        parent::__construct('comutopicomsg', 'idcomutopicomsg');
    }

    public function buildQuery(array $data, $num=0, $offset=0, $options=null) 
    {
        $q = parent::buildQuery($data, $num, $offset, $options);
        $q->join('usuario', 'comutopicomsg.idusuario = usuario.idusuario');
        $q->join("usuariofoto", "usuariofoto.idusuario = usuario.idusuario");
        return $q;
    }
    
    public function createModelBo() 
    {
        return new Aew_Model_Bo_ComuTopicoMsg();
    }

}