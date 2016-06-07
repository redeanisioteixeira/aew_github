<?php
/**
 * DAO da entidade ComunidadeBlog
 */
class Aew_Model_Dao_UsuarioColega extends Sec_Model_Dao_Abstract
{
    function __construct() 
    {
        parent::__construct('usuariocolega','idusuariocolega');
    }

    function buildQuery(array $data, $num=0 , $offset=0, $options=null) 
    {
        $q = parent::buildQuery($data, $num , $offset,$options);
        
        $q->joinLeft('usuario', 'usuario.idusuario = usuariocolega.idusuario', array('idusuario as idusuarioadd','nomeusuario','email'));
        $q->joinLeft("usuariofoto", "usuariofoto.idusuario = usuario.idusuario", array('idusuariofoto','extensao'));
        $q->joinLeft('usuario as usuario2', 'usuario2.idusuario = usuariocolega.idcolega', array('idusuario as idusuarioadd2','nomeusuario as nome2','email as email2'));
        $q->joinLeft("usuariofoto as usuariofoto2", "usuariofoto2.idusuario = usuario2.idusuario", array('idusuariofoto as idusuariofoto2','extensao as extensao2'));
        
        $q->where('usuario.flativo = true');
        $q->where('usuario2.flativo = true');

        return $q;
    }
    
    protected function createModelBo() 
    {
        return new Aew_Model_Bo_UsuarioColega();
    }
}