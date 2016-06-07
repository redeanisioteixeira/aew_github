<?php
/**
 * BO da entidade ConteudoDigitalTag
 */
 class Aew_Model_Bo_ConteudoDigitalTag extends Aew_Model_Bo_Tag
{
     /**
      * cria objeto de acesso ao banco de dados
      * @return \Aew_Model_Dao_ConteudoDigitalTag
      */
    function createDao() {
        return new Aew_Model_Dao_ConteudoDigitalTag();
    }
}