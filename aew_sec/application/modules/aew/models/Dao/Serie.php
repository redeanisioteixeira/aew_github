<?php

/**
 * DAO da entidade Serie
 */

class Aew_Model_Dao_Serie extends Sec_Model_Dao_Abstract
{
    /**
     * Nome da entidade do DAO
     * @var string
     */
    public $_entityName = "Serie";

    /**
     * Chave primária
     * @var string
     */
    protected $_primaryKey = "idSerie";

    protected $_options  = array('orderBy' => 'e.nome ASC');
    
    public function __construct() 
    {
        parent::__construct('serie', 'idserie');
    }

    /**
     * Recarrega a tabela de series
     */
    public function loadSeries()
    {
        set_time_limit(300);
        $secWebService = new Sec_Service_Sec();
        $series = $secWebService->obterTodasSeries();

        foreach($series as $serie){
            if(false == $this->get($serie->idClientela)){
                $ob = new Aew_Model_Bo_Serie();
                $ob->setId($serie->idClientela);
                $ob->setNome($serie->nomeconteudodigitalcategoria);
                $ob->save();
            }
        }
    }

    public function createModelBo() {
        return new Aew_Model_Bo_Serie();
    }

}