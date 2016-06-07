<?php

/**
 * DAO da entidade ComuTopico
 */

class Aew_Model_Dao_ComuTopico extends Aew_Model_Dao_Comentario
{
    
    function __construct()
    {
        parent::__construct('comutopico','idcomutopico');
    }
    
    /**
     * 
     * @param array $data
     * @param type $num
     * @param type $offset
     * @param type $options
     * @return type
     */
    public function buildQuery(array $data, $num=0, $offset=0, $options=null) 
    {
        $q = parent::buildQuery($data, $num, $offset, $options);
        if(isset($data['titulo']))
        {
            $q->where($this->getName().'.titulo = ? ', $data['titulo']);
        }
        $q->join('usuario', 'comutopico.idusuario = usuario.idusuario');
        $q->join("usuariofoto", "usuariofoto.idusuario = usuario.idusuario");
        return $q;
    }

    /**
     * cria BO da entidade
     * @return \Aew_Model_Bo_ComuTopico
     */
    public function createModelBo() 
    {
        return new Aew_Model_Bo_ComuTopico();
    }

}