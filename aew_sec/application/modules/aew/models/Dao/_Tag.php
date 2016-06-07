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
     * @return Doctrine_Collection
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

    public function selectNumUsos() 
    {
	$query = "SELECT t.idtag, t.nome, COUNT(cd.*) AS num
 		  FROM tag AS t
		  LEFT JOIN conteudodigitaltag AS cd ON(cd.idtag = t.idtag)
		  GROUP BY t.idtag, t.nome 
		  ORDER BY t.nome";
        return Doctrine_Manager::getInstance()->getCurrentConnection()->fetchAssoc($query);
    }

    public function filtrarPorNome($nome) 
    {
        $adapter = $this->getAdapter();
        $result =  $adapter->query("select * from tag where (sem_acentos(nometag) LIKE sem_acentos(?)) OR (sem_acentos(nometag) LIKE sem_acentos(?)) OR (sem_acentos(nometag) LIKE sem_acentos(?))", array("$nome %", "% $nome %", "% $nome"));
	return $this->createObjects($result);
    }

    function buildQuery(array $data, $num = 0, $offset = 0, $options = null)
    {
        $this->setAliasTable('tag');
        $q = parent::buildQuery($data, $num, $offset, $options);
        if(isset($data["nometag"]))
        {
            $q->orWhere(" sem_acentos(nometag) LIKE sem_acentos(?) ",'%'.$data["nometag"].'%');
            $q->orWhere(" sem_acentos(nometag) LIKE sem_acentos(?) ",'%'.$data["nometag"]);
            $q->orWhere(" sem_acentos(nometag) LIKE sem_acentos(?) ",$data["nometag"].'%');
        }
        if(isset($data['idcomponentecurricular']))
        {
            $q->join('conteudodigitaltag', 'conteudodigitaltag.idtag = tag.idtag',array());
            $q->join("conteudodigitalcomponente","conteudodigitalcomponente.idconteudodigital =  conteudodigitaltag.idconteudodigital",array());
            $q->join("componentecurricular","componentecurricular.idcomponentecurricular = conteudodigitalcomponente.idcomponentecurricular",array())->distinct();
        }
        if(isset($data['busca']))
        {
            $q->orWhere(" tag.busca >= ? ", $data['busca']);
        }
       	$q->where("UPPER(nometag) not like '%LOTE%'");
	$q->order('tag.busca DESC'); 

        return $q;
    }
    
    protected function createModelBo() 
    {
        return new Aew_Model_Bo_Tag();
    }
    
}