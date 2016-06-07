<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Aew_Model_Dao_UsuarioComponente
 *
 * @author tiago
 */
class Aew_Model_Dao_UsuarioComponente extends Sec_Model_Dao_Abstract
{
    
    function __construct() 
    {
        parent::__construct('usuariocomponente',array('idusuario','idcomponentecurricular'));
        $this->_sequence = false;
    }
    function buildQuery(array $data, $num = 0, $offset = 0, $options = null) 
    {
        $q = parent::buildQuery($data, $num, $offset, $options);
        $q->join('componentecurricular',  $this->getName().'.idcomponentecurricular = componentecurricular.idcomponentecurricular');
        $q->join('nivelensino',  'componentecurricular.idnivelensino = nivelensino.idnivelensino');
        return $q;
    }
    //put your code here
    protected function createModelBo() 
    {
        return new Aew_Model_Bo_UsuarioComponente();
    }

}
