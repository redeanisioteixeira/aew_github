<?php

/**
 * BO da relacao entre usuarios e componentes curriculares
 *
 * @author tiago
 */
class Aew_Model_Bo_UsuarioComponente extends Aew_Model_Bo_ComponenteCurricular
{
    /**
     * cria objeto de acesso ao banco de dados
     * @return \Aew_Model_Dao_UsuarioComponente
     */
    protected function createDao() 
    {
        return new Aew_Model_Dao_UsuarioComponente();
    }

//put your code here
}