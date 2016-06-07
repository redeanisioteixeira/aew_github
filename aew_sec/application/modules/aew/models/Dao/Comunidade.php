<?php

/**
 * DAO da entidade Comunidade
 */

class Aew_Model_Dao_Comunidade extends Sec_Model_Dao_Abstract
{
    function __construct() 
    {
        parent::__construct('comunidade','idcomunidade');
    }

    function buildQuery(array $data, $num = 0, $offset = 0, $options = null)
    {
        $q = parent::buildQuery($data, $num, $offset, $options);
        $q->join('usuario','usuario.idusuario='.$this->getName().'.idusuario',array('nomeusuario','idusuario','flativo'));
        $q->joinLeft('comunidadefoto', 'comunidadefoto.idcomunidade = '.$this->getName().'.idcomunidade',array('idcomunidadefoto','extensao'));
        return $q;
    }
    
    /**
     * .
     * @param int $num
     * @param int $offset
     * @return array
     */
    public function selectComunidadesRelacionadasTag(Aew_Model_Bo_Comunidade $comunidade, $num = 0, $offset = 0, $tags = null)
    {   
        $id = 0;
        if(!isset($tags))
        {
            $id = $comunidade->getId();
            $comunidade->selectTags();
            
            $tags = $comunidade->getTags();
            $comunidade->setTags(array());
        }
        
        $q = $this->buildQuery($comunidade->toArray(), $num, $offset);
        $q->order("nomecomunidade");
        
        $q->reset(Zend_Db_Select::WHERE);
        $insql = "";
        foreach( $tags as $tag)
        {
            if($insql)
                $insql .= ",";
            
            $idtag = $tag->getId();
            
            if(is_array($idtag))
                $idtag = end($idtag);
            
            $insql .= $idtag;
        }
        
        if(!$insql)
        {
            return array();
        }
        
        $numTags = NUMERO_TAGS-0;
        if(is_array($id))
            $id = $id[0];
        
        $q->where("comunidade.idcomunidade
                        IN(SELECT comunidade.idcomunidade
                            FROM comunidade
                            INNER JOIN comunidadetag ON comunidadetag.idcomunidade = comunidade.idcomunidade
                            WHERE comunidade.ativa = TRUE AND comunidadetag.idtag IN($insql) AND comunidade.idcomunidade <> $id
                            GROUP BY comunidade.idcomunidade
                            HAVING COUNT(comunidade.idcomunidade)>=$numTags)"); 
        
        $q->where("comunidade.idcomunidade NOT IN(SELECT idcomunidaderelacionada FROM comurelacionada WHERE idcomunidade = $id)");
        
        $this->countRecords($q);
        $result = $q->query();
        return $this->createObjects($result);
    }
 
    public function createModelBo() 
    {
        return new Aew_Model_Bo_Comunidade();
    }
}