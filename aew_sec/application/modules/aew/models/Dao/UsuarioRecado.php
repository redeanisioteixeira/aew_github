<?php

/**
 * DAO da entidade UsuarioRecado
 */

class Aew_Model_Dao_UsuarioRecado extends Sec_Model_Dao_Abstract
{
    function __construct() 
    {
        parent::__construct('usuariorecado','idusuariorecado');
    }
    
    public function buildQuery(array $data, $num=0,$offset=0,$options=null) 
    {
        $q =   parent::buildQuery($data, $num,$offset,$options);
        
        if(isset($data['idusuario']))
        {
            $q->where('usuariorecado.idusuario = ? OR usuariorecado.idusuarioautor = ?',$data['idusuario']);
        }   
        if(!isset($data['idrecadorelacionado']))
        {
            $q->where('idrecadorelacionado IS NULL');
        }
        $q->join('usuario','usuario.idusuario = '.$this->getName().'.idusuarioautor',array('nomeusuario','email','flativo'));
        $q->joinLeft('usuariofoto','usuariofoto.idusuario='.$this->getName().'.idusuarioautor', array('extensao','idusuariofoto'));
        $q->order('dataenvio desc');
        return $q;
    }
    
    public function createModelBo() 
    {
        return new Aew_Model_Bo_UsuarioRecado();
    }   
}
