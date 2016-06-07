<?php

/**
 * DAO da entidade Usuario
 */

class Aew_Model_Dao_Tag extends Sec_Model_Dao_Abstract
{
    public function __construct() 
    {
        parent::__construct('tag','idtag');
    }

    /**
     * Retorna todos as tags para fazer nuvemd e tags
     *
     * @return array
     */
    public function getAllTagFromCloud($data, array $options = null)
    {
       $q = $this->getSql();
       $q->where('tag.busca > ?', 0);
       $q->where("UPPER(nome) not like '%LOTE%'");
       $q->limit(35);
       $q->order('tag.busca DESC');
       $result = $this->select();
       return $result;
    }

    /**
     * 
     * @param int $id
     * @param int $num
     * @param int $offset
     * @param array $options
     * @return array
     */
    public function selectNumUsos($id = null, $num = 0, $offset = 0, $options = null)
    {
        $data = array();
        $this->setAliasTable('tag');
        
        $q = parent::buildQuery($data, $num, $offset, $options);
        
	$q->columns('(SELECT COUNT(*) FROM conteudodigitaltag WHERE conteudodigitaltag.idtag = tag.idtag) AS qtdeusoCD');
	$q->columns('(SELECT COUNT(*) FROM ambientedeapoiotag WHERE ambientedeapoiotag.idtag = tag.idtag) AS qtdeusoAA');
        $q->order("tag.nometag ASC");

        if($id):
            $q->where("tag.idtag = ".$id);
        else:
            $this->countRecords($q);
        endif;

        $result = $q->query();
        
        return $this->createObjects($result);
    }

    /**
     * 
     * @param string $nome
     * @return array
     */
    public function filtrarPorNome($nome) 
    {
        $adapter = $this->getAdapter();
        $result =  $adapter->query("SELECT * FROM tag WHERE (sem_acentos(nometag) LIKE sem_acentos(?)) OR (sem_acentos(nometag) LIKE sem_acentos(?)) OR (sem_acentos(nometag) LIKE sem_acentos(?))", array("$nome %", "% $nome %", "% $nome"));
	return $this->createObjects($result);
    }

    /**
     * 
     * @return array
     */
    public function filtrarLetras() 
    {
        $adapter = $this->getAdapter();
        $result =  $adapter->query("SELECT UPPER(sem_acentos(LEFT(nometag, 1))) AS idletra, UPPER(sem_acentos(LEFT(nometag, 1))) AS nomeletra FROM tag GROUP BY UPPER(sem_acentos(LEFT(nometag, 1))) ORDER BY 1");
	return $this->createObjects($result);
    }
    
    /**
     * constroi o sql da consulta
     * @param array $data
     * @param int $num
     * @param int $offset
     * @param array|string $options
     * @return Zend_Db_Select
     */
    function buildQuery(array $data, $num = 0, $offset = 0, $options = null)
    {
        $this->setAliasTable('tag');
        $q = parent::buildQuery($data, $num, $offset, $options);
        
        if(isset($data["nometag"]))
        {
            $q->orWhere("sem_acentos(nometag) LIKE sem_acentos(?) ",'%'.$data["nometag"].'%');
        }
        if(isset($data['idcomponentecurricular']))
        {
            $q->join('conteudodigitaltag', 'conteudodigitaltag.idtag = tag.idtag',array());
            $q->join("conteudodigitalcomponente","conteudodigitalcomponente.idconteudodigital =  conteudodigitaltag.idconteudodigital",array());
            $q->join("componentecurricular","componentecurricular.idcomponentecurricular = conteudodigitalcomponente.idcomponentecurricular",array())->distinct();
        }
        
        if(isset($data['busca']))
        {
            $q->orWhere("tag.busca >= ? ", $data['busca']);
        }
        
       	//$q->where("UPPER(nometag) not like '%LOTE%'");

		$q->order('tag.busca DESC'); 
        
        return $q;
    }
    
    /**
     * cria objeto de acesso ao banco de dados
     * @return \Aew_Model_Bo_Tag
     */
    protected function createModelBo() 
    {
        return new Aew_Model_Bo_Tag();
    }
}
