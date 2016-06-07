<?php

/**
* DAO da entidade chatmensagensstatus
*/

class Aew_Model_Dao_ChatMensagensStatus extends Sec_Model_Dao_Abstract
{
    function __construct()
    {
        parent::__construct('chatmensagensstatus', 'id');
    }
    
    function buildQuery(array $data, $num = 0, $offset = 0, $options = null)
    {
        $q = parent::buildQuery($data, $num, $offset, $options);
        $q->join("usuario", "usuario.idusuario =  ".$this->getName().".id_para");
        $q->join('usuariofoto', 'usuario.idusuario = usuariofoto.idusuario');
        //echo $q; die();
        return $q;
    }
    
    public function createModelBo() 
    {
        return new Aew_Model_Bo_ChatMensagensStatus();
    }

}
