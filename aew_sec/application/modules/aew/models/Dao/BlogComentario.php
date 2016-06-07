<?php

/**
 * DAO da entidade AgendaComentario
 */

class Aew_Model_Dao_BlogComentario extends Aew_Model_Dao_Comentario
{
    /**
     * Construtor
     */
    public function __construct()
    {
        parent::__construct('blogcomentario','idblogcomentario');
    }

    public function createModelBo() 
    {
        return new Aew_Model_Bo_BlogComentario(1);
    }
    
    public function buildQuery(array $data, $num = 0, $offset = 0, $options = null)
    {
        $q = parent::buildQuery($data, $num, $offset, $options);
        $q->join('usuario', 'usuario.idusuario='.$this->getName().'.idusuario');
        $q->join('usuariofoto', 'usuariofoto.idusuario=usuario.idusuario');
        $q->order($this->getName().'.datacriacao desc');
        return $q;
    }
}