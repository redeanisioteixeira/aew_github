<?php
/**
 * DAO da entidade Usuario
 */
class Aew_Model_Dao_Formato extends Sec_Model_Dao_Abstract
{

    function __construct() 
    {
        parent::__construct('formato', 'idformato');
    }
    
    public function buildQuery(array $data,$num=0,$offset=0,$options=null) 
    {
        $q = parent::buildQuery($data,$num,$offset,$options);
        $q->join('conteudotipo', 'conteudotipo.idconteudotipo='.$this->getName().'.idconteudotipo');
        
        return $q;
    }
    
    public function createModelBo() 
    {
        return new Aew_Model_Bo_Formato();
    }
}