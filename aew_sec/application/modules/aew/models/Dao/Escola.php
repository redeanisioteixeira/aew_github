<?php

/**
 * DAO da entidade Escola
 */

class Aew_Model_Dao_Escola extends Sec_Model_Dao_Abstract
{
    function __construct($config = array(), $definition = null) 
    {
        parent::__construct('escola','idescola');
    }

    /**
     * Recarrega a tabela de escolas
     */
    public function loadEscolas()
    {
        set_time_limit(300);
        $secWebService = new Sec_Service_Sec();
        $escolas = $secWebService->obterTodasEscolas();
        foreach($escolas as $escola)
        {
            if(false == $this->get($escola->id))
            {
                $ob = new Aew_Model_Bo_Escola();

                if(!isset($escola->codigoSec) || '' == trim($escola->codigoSec)){
                    echo 'codigo sec vazio';
                    echo '<br/>';
                    Zend_Debug::dump($escola);
                    continue;
                }

                $ob->setId(trim($escola->codigoSec));
                $ob->setNome(trim($escola->nomeconteudodigitalcategoria));
                $ob->setCodigoMec($escola->id);
                $munBo = new Aew_Model_Bo_Municipio();
                $munBo->setCodigoIbgeSiig($escola->codigoIbgeMunicipio);
                $municipio = $munBo->select(1);
                if(false == $municipio){
                    Zend_Debug::dump($escola);
                    exit();
                }
                $ob->setMunicipio($municipio);
                $ob->save();
            }
        }
    }

    public function createModelBo() 
    {
        return new Aew_Model_Bo_Escola();
    }

}