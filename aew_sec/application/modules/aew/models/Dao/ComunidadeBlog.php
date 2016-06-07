<?php

/**
 * DAO da entidade ComunidadeBlog
 */

class Aew_Model_Dao_ComunidadeBlog extends Aew_Model_Dao_Blog
{
    function __construct() 
    {
        parent::__construct('comunidadeblog','idcomunidadeblog');
    }
    
    public function createModelBo() 
    {
        return new Aew_Model_Bo_ComunidadeBlog();
    }

}