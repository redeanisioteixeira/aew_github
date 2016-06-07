<?php
 class Aew_Model_Bo_AmbienteDeApoioTag extends Aew_Model_Bo_Tag
{
     /**
      * cria objeto de acesso ao banco de dados
      * @return \Aew_Model_Dao_AmbienteDeApoioTag
      */
    protected function createDao() {
        return new Aew_Model_Dao_AmbienteDeApoioTag();
    }
}