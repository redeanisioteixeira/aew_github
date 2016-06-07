<?php

/**
 * DAO da entidade Categoria Componente Curricular
 */

class Aew_Model_Dao_CategoriaComponenteCurricular extends Sec_Model_Dao_Abstract
{
    
    function __construct() 
    {
        parent::__construct('categoriacomponentecurricular','idcategoriacomponentecurricular');
    }

    protected function createModelBo() 
    {
        return new Aew_Model_Bo_CategoriaComponenteCurricular();
    }
}