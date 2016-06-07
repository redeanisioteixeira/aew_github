<?php

/**
 * DAO da entidade AmbienteDeApoio
 */

class Aew_Model_Dao_AmbienteDeApoio extends Sec_Model_Dao_Abstract
{
    function __construct() 
    {
        parent::__construct('ambientedeapoio','idambientedeapoio');
    }
    
    function buildQuery(array $data, $num = 0, $offset = 0,$options=null) 
    {
        $q = parent::buildQuery($data, $num, $offset,$options);
        if(isset($data['idfavorito']))
        {
            $q->joinLeft('ambientedeapoiofavorito', 'ambientedeapoiofavorito.idambientedeapoio = ambientedeapoio.idambientedeapoio');
        }
        
        $q->join("ambientedeapoiocategoria","ambientedeapoiocategoria.idambientedeapoiocategoria = ambientedeapoio.idambientedeapoiocategoria");
        $q->joinLeft("usuario","usuario.idusuario = ambientedeapoio.idusuariopublicador");
        $q->order("ambientedeapoiocategoria.nomeambientedeapoiocategoria ASC");
        
        return $q;
    }

    /**
     * @param Aew_Model_Bo_ConteudoDigital $conteudo
     * @param int $num
     * @param int $offset
     * @return array
     */
    public function selectAmbientesRelacionados(Aew_Model_Bo_AmbienteDeApoio $ambienteDeApoio, $num = 0, $offset = 0)
    {   
        $id = $ambienteDeApoio->getId();
        $ambienteDeApoio->selectTags();
        
        $tags = $ambienteDeApoio->getTags();

        $q = $this->buildQuery($ambienteDeApoio->toArray(), $num, $offset);
        
        $q->order("titulo");
        $q->reset(Zend_Db_Select::WHERE);
        
        $insql = "";
        foreach( $tags as $tag)
        {
            $tag = $tag->getId();
            $tag = end($tag);
            $insql .= $tag;
            if(next($tags))
                $insql .= ",";
        }

        if(!$insql)
        {
            return array();
        }
        
        $numTags = NUMERO_TAGS-2;
        if(is_array($id))
        {
            $id = $id[0];
        }
        
        $q->where("ambientedeapoio.idambientedeapoio <> $id");
        $q->where("ambientedeapoio.idambientedeapoio
                        IN(SELECT ambientedeapoio.idambientedeapoio
                            FROM ambientedeapoio
                            INNER JOIN ambientedeapoiotag ON ambientedeapoiotag.idambientedeapoio = ambientedeapoio.idambientedeapoio
                            WHERE ambientedeapoiotag.idtag IN($insql)
                            GROUP BY ambientedeapoio.idambientedeapoio
                            HAVING COUNT(ambientedeapoio.idambientedeapoio)>=$numTags)");
        
        $this->countRecords($q);
        
        $result = $q->query();

        return $this->createObjects($result);
    }    

    public function createModelBo() 
    {
        return new Aew_Model_Bo_AmbienteDeApoio();
    }
}