<?php

/**
 * BO da entidade ConteudoDigitalFavorito
 */

class Aew_Model_Bo_ConteudoDigitalFavorito extends Aew_Model_Bo_ConteudoDigital
{
    /**
     * delete o objeto
     * @return int
     */
    public function delete()
    {
        if(!$this->getId())
        {
            return ;
        }
        try
        {
            return $this->getDao()->delete($this->getIdInArray(array(),true));
        } 
        catch (Exception $ex)
        {
            return 0;
        }
        
    }
    
    /**
     * cria objeto de acesso ao banco de dados
     * @return \Aew_Model_Dao_ConteudoDigitalFavorito
     */
    function createDao() {
        return new  Aew_Model_Dao_ConteudoDigitalFavorito();
    }
}