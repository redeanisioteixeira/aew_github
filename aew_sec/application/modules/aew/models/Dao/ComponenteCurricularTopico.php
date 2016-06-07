<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ComponenteCurricularTopico
 *
 * @author tiago-souza
 */
class Aew_Model_Dao_ComponenteCurricularTopico extends Sec_Model_Dao_Abstract
{
    function __construct()
    {
        parent::__construct('componentecurriculartopico', 'idcomponentecurriculartopico');
    }
    protected function createModelBo()
    {
        return new Aew_Model_Bo_ComponenteCurricularTopico();
    }
    
    function buildQuery(array $data, $num = 0, $offset = 0, $options = null)
    {
        $q = parent::buildQuery($data, $num, $offset, $options);
        $q->join('componentecurricular', $this->getName().'.idcomponentecurricular=componentecurricular.idcomponentecurricular ');
        $q->order(array($this->getName().".idcomponentecurricular", $this->getName().".idcomponentecurriculartopico", $this->getName().".idcomponentecurriculartopicopai"));
        return $q;
    }

//put your code here
}
