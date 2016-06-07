<?php

 class Aew_Model_Dao_ConteudoDigitalTag extends Sec_Model_Dao_Abstract{

   function __construct()
   {
       parent::__construct('conteudodigitaltag',array('idconteudodigital','idtag'));
   }

   function buildQuery(array $data, $num = 0, $offset = 0, $options = null)
   {
       $q = parent::buildQuery($data, $num, $offset, $options);
       $q->join('tag', 'tag.idtag = '.$this->getName().'.idtag');
       return $q;
   }
   
   protected function createModelBo()
   {
       return new Aew_Model_Bo_ConteudoDigitaltag();
   }
}