<?php

/**
 * DAO da entidade ConteudoDigital
 */
class Aew_Model_Dao_ConteudoDigital extends Sec_Model_Dao_Abstract
{

    function __construct()
    {
        parent::__construct('conteudodigital', 'idconteudodigital');
    }

    /**
     * @return Zend_Db_Select 
     */
    public function buildQuery(array $data, $num = 0, $offset = 0, $options = null)
    {
        $q = parent::buildQuery($data, $num, $offset, $options);
        if (isset($data["idcomponentecurricular"]))
        {
            $q->join('conteudodigitalcomponente', $this->getName() . '.idconteudodigital = conteudodigitalcomponente.idconteudodigital');
        }
        if(isset($data['idconteudotag']))
        {
            $q->join('conteudodigitaltag', "conteudodigitaltag.idconteudodigital = ".$this->getName().".idconteudodigital");
            $q->where("(conteudodigitaltag.idtag in (?))",  $data['idconteudotag']);
        }
        
        $this->joinConteudoDigital($q,$data);
        return $q;
    }
    
    private function joinConteudoDigital(Zend_Db_Select $q,$data = array())
    {
        if(isset($data['idfavorito']))
        {
            $q->join('conteudodigitalfavorito', "conteudodigitalfavorito.idconteudodigital = ".$this->getName().".idconteudodigital");
            $q->join('favorito', 'favorito.idfavorito = conteudodigitalfavorito.idfavorito');
        }

        $q->joinleft('formato', 'formato.idformato = conteudodigital.idformato',array("nomeformato as nomeformato","idformato as idformato"));
        $q->joinLeft("formato as formatodownload","formatodownload.idformato = conteudodigital.idformatodownload",array("nomeformato as nomeformatodownload","idformato as idformatodownload"));
        $q->joinleft("formato as formatoguia", "formatoguia.idformato = conteudodigital.idformatoguiapedagogico",array("idformato as idformatoguiapedagogico","nomeformato as nomeformatoguiapedagogico"));
        $q->joinLeft('conteudotipo', 'conteudotipo.idconteudotipo = formato.idconteudotipo');
        $q->joinleft('conteudodigitalcategoria', 'conteudodigitalcategoria.idconteudodigitalcategoria = conteudodigital.idconteudodigitalcategoria',array("idconteudodigitalcategoria","nomeconteudodigitalcategoria","idconteudodigitalcategoriapai","idcanal"));
        $q->join('conteudolicenca', 'conteudolicenca.idconteudolicenca = '.$this->getName().'.idlicencaconteudo');
        $q->join('usuario','usuario.idusuario = conteudodigital.idusuariopublicador',array('nomeusuario','idusuario', 'email', 'emailpessoal'));
        
    }
    
    /**
     * @param Aew_Model_Bo_ConteudoDigital $conteudo objeto conteudo utilizado para filtro
     * @param array $conteudosTipos tipos adicionados como filtro a busca
     * @param int $num   
     * @param int $offset
     * @param array $ordem
     * @return array(Aew_Model_Bo_ConteudoDigital)
     */
    function busca(Aew_Model_Bo_ConteudoDigital $conteudo,  $num = 0, $offset = 0, array $ordem = null)
    {
        $data = $conteudo->toArray();
        $q = parent::buildQuery($data, $num, $offset);

        $groupFavorito = '';
        $q->reset(Zend_Db_Select::WHERE);
        $q->reset(Zend_Db_Select::LEFT_JOIN);
        $this->joinConteudoDigital($q,$data);
        if(isset($data['idusuariopublicador']))
        {
            $q->where('conteudodigital.idusuariopublicador = ?',$data['idusuariopublicador']);
        }
        
        if (isset($data["idcomponentecurricular"]))
        {
            $q->join('conteudodigitalcomponente', $this->getName() . '.idconteudodigital = conteudodigitalcomponente.idconteudodigital');
        }
        
        if(isset($data['idfavorito']))
        {
            $groupFavorito = '(';
        }
        
        if (isset($data['titulo']))
        {
            $titulo = $data['titulo'] ;
            
            if(($titulo[0] == "\"") && ($titulo[count($titulo)-1]=="\""))
            {
                $titulo = trim($titulo, "\"");
                $q->orWhere($groupFavorito.'(((conteudodigital.titulo like (?)','% '.$titulo.' %');
                $q->orWhere('conteudodigital.titulo  like (?)','% '.$titulo);
                $q->orWhere('conteudodigital.titulo  like (?)',$titulo.' %');
                $q->orWhere('conteudodigital.autores like (?)', '% '.$titulo. ' %');
                $q->orWhere('conteudodigital.autores like (?)', '% '.$titulo);
                $q->orWhere('conteudodigital.autores like (?)))', $titulo. '%');
            }
            else
            {
                $q->orWhere($groupFavorito.'(((lower(sem_acentos(titulo)) like lower(sem_acentos(?)) ', '%'.$titulo.'%');
                $q->orWhere('lower(sem_acentos(autores)) like lower(sem_acentos(?))))', '%' . $data['titulo'] . '%');
            }
                
            
        }
        $sqltag = "";
        if(is_array($ordem))
        {
            foreach($ordem as $or => $value)
            {
                if(trim($or) != 'titulo')
                {
                    if($or == 'avaliacao')
                    {
                        $q->columns("(SELECT SUM(conteudodigitalvoto.voto)/COUNT(conteudodigitalvoto.voto) AS avaliacao FROM conteudodigitalvoto WHERE conteudodigitalvoto.idconteudodigital = conteudodigital.idconteudodigital GROUP BY conteudodigitalvoto.idconteudodigital) AS mediavoto" );
                        $q->columns("(SELECT COUNT(conteudodigitalvoto.voto) AS quantidade FROM conteudodigitalvoto WHERE conteudodigitalvoto.idconteudodigital = conteudodigital.idconteudodigital GROUP BY conteudodigitalvoto.idconteudodigital) AS quantidadevoto");
                        $q->order("mediavoto DESC NULLS LAST");
                        $q->order("quantidadevoto DESC NULLS LAST");
                        $q->order("acessos DESC NULLS LAST");
                        $q->order("datapublicacao DESC");
                    }
                    else
                    {
                        $q->order("$or $value");
                    }
                }
            }
            /*--- Campo obrigatório pata ordenar sql ---*/
            $q->order("lower(sem_acentos(titulo)) ASC");
        }
        
        $tagExistente = array();
        foreach($conteudo->getTags() as $tag)
        {
            $tagNome = $tag->getNome();
            if($tagNome && !array_key_exists($tagNome, $tagExistente))
            {
                $tagExistente[$tagNome] = '';
                
                if($sqltag)
                   $sqltag .= " or ";

                if($tagNome[0] == "\"" && $tagNome[count($tagNome)-1]=="\"")
                {
                    $tagNome = trim($tagNome, "\"");
                    $sqltag .= " ((tag.nometag) like lower(sem_acentos('% ".$tagNome." %'))";
                    $sqltag .= " or (tag.nometag) like lower(sem_acentos('% ".$tagNome."'))";
                    $sqltag .= " or (tag.nometag) like lower(sem_acentos('".$tagNome." %')))";
                }
                else
                {
                    $sqltag .= " lower(sem_acentos(tag.nometag)) like lower(sem_acentos('%".$tagNome."%'))";
                }
            }
        }
        
        if($sqltag)
        {
            if($groupFavorito)
            {
                $groupFavorito = ')';
            }
            $q->orWhere("conteudodigital.idconteudodigital in (select conteudodigitaltag.idconteudodigital from conteudodigitaltag join tag on tag.idtag = conteudodigitaltag.idtag where $sqltag)) $groupFavorito");
        }
        
        $idscomponentes = array();
        foreach($conteudo->getComponentesCurriculares() as $componente)
        {
            array_push($idscomponentes ,$componente->getId());
        }
        
        if(count($idscomponentes)>0)
        {
            $q->orWhere("conteudodigital.idconteudodigital in (select distinct conteudodigitalcomponente.idconteudodigital from conteudodigitalcomponente
                        INNER JOIN componentecurricular ON componentecurricular.idcomponentecurricular = conteudodigitalcomponente.idcomponentecurricular
                        where componentecurricular.idcomponentecurricular in (?))",$idscomponentes);
        }

        if ($conteudo->getConteudoTipo())
        {
            $ids = $conteudo->getConteudoTipo()->getId();
            if((is_array($ids)) && (count($ids)>0))
            {
                $q->where("conteudotipo.idconteudotipo in (?) ",$ids);
            }
        }

        if(isset($data['idfavorito']))
        {
            $q->where('conteudodigitalfavorito.idfavorito = ? ',$data['idfavorito']);
        }

        $q->where("(conteudodigital.flaprovado = ?)",  TRUE);
        
        $this->countRecords($q);
        $result = $q->query();

        return $this->createObjects($result);
    }
    
    /**
     * .
     * @param int $num
     * @param int $offset
     * @return array
     */
    public function selectConteudosRelacionados(Aew_Model_Bo_ConteudoDigital $conteudo, $num = 0, $offset = 0, $tags = null)
    {   
        $id = 0;
        if(!isset($tags))
        {
            $id = $conteudo->getId();
            $conteudo->selectTags();
            
            $tags = $conteudo->getTags();
            $conteudo->setTags(array());
        }
        
        $q = $this->buildQuery($conteudo->toArray(), $num, $offset);
        $q->order("titulo");
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
        
        $numTags = NUMERO_TAGS;
        if(is_array($id))
            $id = $id[0];
        
        $q->where("conteudodigital.idconteudodigital
                        IN(SELECT conteudodigital.idconteudodigital
                            FROM conteudodigital
                            INNER JOIN conteudodigitaltag ON conteudodigitaltag.idconteudodigital = conteudodigital.idconteudodigital
                            WHERE conteudodigitaltag.idtag IN($insql) AND conteudodigital.idconteudodigital <> $id AND conteudodigital.flaprovado = TRUE
                            GROUP BY conteudodigital.idconteudodigital
                            HAVING COUNT(conteudodigital.idconteudodigital)>=$numTags)"); 
        $this->countRecords($q);
        $result = $q->query();
        
        return $this->createObjects($result);
    }
 
	public function executarSQL($query)
    {
        $resultado = $this->getAdapter()->query($query);
        return $this->createObjects($resultado);
    }

    public function createModelBo()
    {
        return new Aew_Model_Bo_ConteudoDigital();
    }
}
