<?php

 class Aew_Model_Dao_ConteudoDigitalComponente extends Sec_Model_Dao_Abstract{

    function __construct()
    {
       parent::__construct('conteudodigitalcomponente',array('idcomponentecurricular','idconteudodigital'));
       $this->_sequence = false;
    }

    function buildQuery(array $data, $num = 0, $offset = 0, $options = null)
    {
        $q = parent::buildQuery($data, $num, $offset, $options);
        $q->join('componentecurricular', 'componentecurricular.idcomponentecurricular =  '.$this->getName().'.idcomponentecurricular');
        $q->joinLeft('nivelensino', 'componentecurricular.idnivelensino = nivelensino.idnivelensino');
        $q->join("conteudodigital", $this->getName().'.idconteudodigital = conteudodigital.idconteudodigital'); 
        
        return $q;
    }
    protected function createModelBo()
    {
        return new Aew_Model_Bo_ConteudoDigitalComponente();
    }
}