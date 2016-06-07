<?php

 class Aew_Model_Dao_Favorito extends Sec_Model_Dao_Abstract{

   function __construct()
   {
       parent::__construct('favorito','idfavorito');
   }

   /**
    * retorna objeto Favorito
    * @return \Aew_Model_Bo_Favorito
    */
   protected function createModelBo() {
        return new Aew_Model_Bo_Favorito();
   }

}