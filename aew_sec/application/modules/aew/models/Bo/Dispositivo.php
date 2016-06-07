<?php

/**
 * Description of Dispositivo
 *
 * @author tiago
 */
class Aew_Model_Bo_Dispositivo extends Sec_Model_Bo_Abstract
{
    //put your code here
    protected $nomedispositivo,$datacriacao;
    
    function getNome() 
    {
        return $this->nomedispositivo;
    }

    /**
     * 
     * @return string
     */
    function getDatacriacao() {
        return $this->datacriacao;
    }

    /**
     * 
     * @param string $nomedispositivo
     */
    function setNome($nomedispositivo) {
        $this->nomedispositivo = $nomedispositivo;
    }

    /**
     * 
     * @param type $datacriacao
     */
    function setDatacriacao($datacriacao) {
        $this->datacriacao = $datacriacao;
    }

    /**
     * 
     * @return \Aew_Model_Dao_Dispositivo
     */
    protected function createDao() 
    {
        return new Aew_Model_Dao_Dispositivo();
    }
}