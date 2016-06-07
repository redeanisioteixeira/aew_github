<?php

 class Aew_Model_Bo_Favorito extends Sec_Model_Bo_Abstract{

	
    protected function createDao() {
        return new Aew_Model_Dao_Favorito();
    }

}