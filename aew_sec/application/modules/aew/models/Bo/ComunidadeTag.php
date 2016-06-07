<?php

/**
 * BO da entidade ComunidadeTag
 */

class Aew_Model_Bo_ComunidadeTag extends Aew_Model_Bo_Tag
{
    protected $idcomunidade; //int(11)
    protected $idtag; //int(11)

    /**
     * @return idcomunidade - int(11)
     */
    public function getIdcomunidade()
    {
    	return $this->idcomunidade;
    }

    /**
     * @param Type: int(11)
     */
    public function setIdcomunidade($idcomunidade){
	$this->idcomunidade = $idcomunidade;
    }

    /**
     * 
     * @return int
     */
    public function getIdtag()
    {
        return $this->idcomunidaderelacionada;
    }

    /**
     * 
     * @param int $idtag
     */
    public function setIdtag($idtag)
    {
        $this->idtag = $idtag;
    }
    
    /**
     * cria objeto de acesso ao banco de dados
     * @return \Aew_Model_Dao_ComunidadeTag
     */
    protected function createDao() 
    {
        return new Aew_Model_Dao_ComunidadeTag();
    }
}