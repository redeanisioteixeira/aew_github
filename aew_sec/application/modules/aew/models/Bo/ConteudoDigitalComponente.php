<?php
/**
 * 
 */
class Aew_Model_Bo_ConteudoDigitalComponente extends Aew_Model_Bo_ComponenteCurricular
{
    protected $idconteudodigital;
    protected $idcomponentecurricular;
    
    /**
     * 
     * @return type
     */
    function getIdcomponentecurricular() {
        return $this->idcomponentecurricular;
    }

    function getIdconteudodigital() {
        return $this->idconteudodigital;
    }

    function setIdcomponentecurricular($idcomponentecurricular) {
        $this->idcomponentecurricular = $idcomponentecurricular;
    }

    function setIdconteudodigital($idconteudodigital) {
        $this->idconteudodigital = $idconteudodigital;
    }
    
    function createDao() {
        return new Aew_Model_Dao_ConteudoDigitalComponente();
    }
}