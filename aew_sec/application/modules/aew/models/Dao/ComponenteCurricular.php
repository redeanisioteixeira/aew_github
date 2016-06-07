<?php
/**
 * DAO da entidade Usuario
 */
class Aew_Model_Dao_ComponenteCurricular extends Sec_Model_Dao_Abstract
{
    public function __construct() 
    {
        parent::__construct('componentecurricular','idcomponentecurricular');
    }

    function buildQuery(array $data, $num = 0, $offset = 0,$options=null) 
    {
        $q =  parent::buildQuery($data, $num, $offset,$options);
        $q->joinLeft('nivelensino', "nivelensino.idnivelensino=componentecurricular.idnivelensino");
        if(isset($data["idconteudodigital"]))
        {
            $q->join('conteudodigitalcomponente', "componentecurricular.idcomponentecurricular = conteudodigitalcomponente.idcomponentecurricular");
        }
        $q->joinLeft("categoriacomponentecurricular", "categoriacomponentecurricular.idcategoriacomponentecurricular = ".$this->getName().".idcategoriacomponentecurricular");
        $q->order('nomecomponentecurricular');
        
        return $q;
    }

    public function createModelBo() 
    {
        return new Aew_Model_Bo_ComponenteCurricular();
    }
}