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
     * constroi o sql da consulta
     * @param array $data
     * @param int $num
     * @param int $offset
     * @param array|string $options
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
            $q->join('conteudodigitaltag', 'conteudodigitaltag.idconteudodigital = '.$this->getName().'.idconteudodigital');
            $q->where("(conteudodigitaltag.idtag in (?))",  $data['idconteudotag']);
        }

        $this->joinConteudoDigital($q, $data);
        
        return $q;
    }
    
    /**
     * 
     * @param Zend_Db_Select $q
     * @param array $data
     */
    private function joinConteudoDigital(Zend_Db_Select $q, $data = array())
    {
        $q->join('usuario','usuario.idusuario = conteudodigital.idusuariopublicador',array('nomeusuario','idusuario', 'email', 'emailpessoal'));
        $q->join('conteudolicenca', 'conteudolicenca.idconteudolicenca = '.$this->getName().'.idlicencaconteudo');
        
        $q->joinleft('formato', 'formato.idformato = conteudodigital.idformato', array('nomeformato as nomeformato'));
        $q->joinLeft('conteudotipo', 'conteudotipo.idconteudotipo = formato.idconteudotipo');
        
        $q->joinLeft('formato as formatodownload','formatodownload.idformato = conteudodigital.idformatodownload', array('nomeformato as nomeformatodownload'));
        $q->joinLeft('conteudotipo as conteudotipodownload', 'conteudotipodownload.idconteudotipo = formatodownload.idconteudotipo', array('nomeconteudotipo as nomeconteudotipodownload','idconteudotipo as idconteudotipodownload'));

        $q->joinleft('formato as formatoguiapedagogico','formatoguiapedagogico.idformato = conteudodigital.idformatoguiapedagogico', array('nomeformato as nomeformatoguiapedagogico'));
        $q->joinLeft('conteudotipo as conteudotipoguiapedagogico', 'conteudotipoguiapedagogico.idconteudotipo = formatoguiapedagogico.idconteudotipo', array('nomeconteudotipo as nomeconteudotipoformatoguiapedagogico','idconteudotipo as idconteudotipoformatoguiapedagogico'));
        
        $q->joinleft('conteudodigitalcategoria', 'conteudodigitalcategoria.idconteudodigitalcategoria = conteudodigital.idconteudodigitalcategoria',array('idconteudodigitalcategoria','nomeconteudodigitalcategoria','idconteudodigitalcategoriapai','idcanal'));
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

        $q->reset(Zend_Db_Select::WHERE);
        $q->reset(Zend_Db_Select::LEFT_JOIN);
        
        $this->joinConteudoDigital($q, $data);

	    $sqltag = "";
		$sqlPalavra = array();

        if (isset($data["idcomponentecurricular"]))
        {
            $q->join('conteudodigitalcomponente', $this->getName() . '.idconteudodigital = conteudodigitalcomponente.idconteudodigital');
        }
        
        if(isset($data['idusuariopublicador']))
        {
            $q->where('conteudodigital.idusuariopublicador = ?',$data['idusuariopublicador']);
        }
        elseif(isset($data['titulo']))
        {	
			$expReg = "[".chr(92).chr(92).chr(39).chr(39).chr(92).chr(34)."”“]+";

	        $titulo = $data['titulo'];
			if(!isset($data['autores']) && !isset($data['fonte']))
			{
		        if($titulo[0] == "\"" && $titulo[count($titulo)-1]=="\"")
		        {
					$titulo = trim($titulo, "\"");
					$q->where("sem_acentos(LOWER(REGEXP_REPLACE(conteudodigital.titulo,'$expReg', '','g'))) = sem_acentos(LOWER(?))", $titulo);
				}
				else
				{
					$q->where("sem_acentos(LOWER(REGEXP_REPLACE(conteudodigital.titulo,'$expReg', '','g'))) LIKE sem_acentos(LOWER(?))", "%$titulo%");
				}
			}

			if(isset($data['autores']) && isset($data['fonte']))
			{
		        if($titulo[0] == "\"" && $titulo[count($titulo)-1]=="\"")
		        {
					$titulo = trim($titulo, "\"");
					$sqlPalavra[] = "sem_acentos(LOWER(REGEXP_REPLACE(conteudodigital.titulo,'$expReg', '','g'))) LIKE sem_acentos(LOWER('%$titulo%'))";
					$sqlPalavra[] = "sem_acentos(LOWER(REGEXP_REPLACE(conteudodigital.autores,'$expReg', '','g'))) LIKE sem_acentos(LOWER('%$titulo%'))";
					$sqlPalavra[] = "sem_acentos(LOWER(REGEXP_REPLACE(conteudodigital.fonte,'$expReg', '','g'))) LIKE sem_acentos(LOWER('%$titulo%'))";

					$sqltag[] = "sem_acentos(LOWER(REGEXP_REPLACE(tag.nometag,'$expReg', '','g'))) LIKE sem_acentos(LOWER('%$titulo%'))";
				}
				else
				{
	                $palavraBusca = explode(' ', $titulo);
    	            foreach($palavraBusca as $titulo)
					{
						$sqlPalavra[] = "sem_acentos(LOWER(REGEXP_REPLACE(conteudodigital.titulo,'$expReg', '','g'))) LIKE sem_acentos(LOWER('%$titulo%'))";
						$sqlPalavra[] = "sem_acentos(LOWER(REGEXP_REPLACE(conteudodigital.autores,'$expReg', '','g'))) LIKE sem_acentos(LOWER('%$titulo%'))";
						$sqlPalavra[] = "sem_acentos(LOWER(REGEXP_REPLACE(conteudodigital.fonte,'$expReg', '','g'))) LIKE sem_acentos(LOWER('%$titulo%'))";
    	            }

					$tagExistente = array();
					foreach($conteudo->getTags() as $tag)
					{
					    $tagNome = $tag->getNome();
					    if($tagNome && !array_key_exists($tagNome, $tagExistente))
					    {
					        $tagExistente[$tagNome] = '';
				            $sqltag[] = "sem_acentos(LOWER(REGEXP_REPLACE(tag.nometag,'$expReg', '','g'))) LIKE sem_acentos(LOWER('%$tagNome%'))";
				        }
				    }
	    		}

				if($sqltag)
				{

					$sqltag = implode(' OR ', $sqltag);
				    $sqlPalavra[] = "conteudodigital.idconteudodigital IN (SELECT conteudodigitaltag.idconteudodigital FROM conteudodigitaltag JOIN tag ON tag.idtag = conteudodigitaltag.idtag WHERE $sqltag)";
				}

				if(count($sqlPalavra))
				{
					$sqlPalavra = implode(' OR ', $sqlPalavra);
					$q->where($sqlPalavra);
				}

			}
        } 
		elseif(!empty($data['idconteudotag']))
	        {	
    	        $q->join('conteudodigitaltag', 'conteudodigitaltag.idconteudodigital = '.$this->getName().'.idconteudodigital');
    	        $q->where("conteudodigitaltag.idtag in(?)",  $data['idconteudotag']);
    	    }
		
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
            $q->order("LOWER(sem_acentos(conteudodigital.titulo)) ASC");
        }
        
        
        $idscomponentes = array();
        $idscategorias = array();
        $idsniveis = array();
        
        foreach($conteudo->getComponentesCurriculares() as $componente)
        {
            array_push($idscomponentes ,$componente->getId()); 
            array_push($idscategorias, $componente->getCategoriaComponenteCurricular()->getId());
            array_push($idsniveis,$componente->getNivelEnsino()->getId());
        }
        
        if(count($idscomponentes)>0)
        {
            if(count($idscategorias[0]))
            {   $idscategorias = $idscategorias[0];
                $idscategorias = implode(" AND componentecurricular.idcategoriacomponentecurricular = ", $idscategorias);

                if(!empty($idscategorias))
                {
                    $q->Where("conteudodigital.idconteudodigital IN (SELECT conteudodigitalcomponente.idconteudodigital FROM conteudodigitalcomponente
                            INNER JOIN componentecurricular ON componentecurricular.idcomponentecurricular = conteudodigitalcomponente.idcomponentecurricular
                            WHERE componentecurricular.idcategoriacomponentecurricular = $idscategorias AND componentecurricular.idcomponentecurricular IN(?))", $idscomponentes);
                }
            }
            
            if(count($idsniveis[0]))
            {   $idsniveis = $idsniveis[0];
                $idsniveis = implode(" AND componentecurricular.idnivelensino = ", $idsniveis);

                if(!empty($idsniveis))
                {
                    $q->Where("conteudodigital.idconteudodigital IN (SELECT conteudodigitalcomponente.idconteudodigital FROM conteudodigitalcomponente
                            INNER JOIN componentecurricular ON componentecurricular.idcomponentecurricular = conteudodigitalcomponente.idcomponentecurricular
                            WHERE componentecurricular.idnivelensino = $idsniveis AND componentecurricular.idcomponentecurricular IN(?))", $idscomponentes);
                }
            }
        }

        if ($conteudo->getConteudoTipo())
        {
            $ids = $conteudo->getConteudoTipo()->getId();
            if(is_array($ids) && count($ids)>0)
            {
                $q->where("conteudotipo.idconteudotipo IN (?) ",$ids);
            }
        }

        if ($conteudo->getConteudoLicenca())
        {
            $ids = $conteudo->getConteudoLicenca()->getId();
            if(is_array($ids) && count($ids)>0)
            {
                $q->where("conteudolicenca.idconteudolicenca IN(?) OR conteudolicenca.idconteudolicencapai IN(?)",$ids);
            }
        }
        
        if(isset($data['idfavorito']))
        {
            $q->join('usuario as usuariologado','usuariologado.idusuario = '.$conteudo->getUsuarioLogado()->getId());
            $q->join('conteudodigitalfavorito', "conteudodigitalfavorito.idconteudodigital = ".$this->getName().".idconteudodigital AND conteudodigitalfavorito.idfavorito = usuariologado.idfavorito");
        }

        $q->where("conteudodigital.flaprovado = ?",  TRUE);

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

    /**
     * 
     * @return array
     */
    public function selectCanalPortal()
    {
        $q = $this->getAdapter();
        $result = $q->fetchAll("SELECT 0 AS idcanalportal,'« TODOS »' AS nomecanalportal UNION SELECT 1 ,'Estudantes' UNION SELECT 2,'Professores' ORDER BY 1");
        return $result;
    }

    /**
     * 
     * @return array
     */
    public function selectResumoconteudosPorTipo()
    {
        $q = $this->getAdapter();
        $result = $q->fetchAll("SELECT conteudotipo.idconteudotipo, conteudotipo.nomeconteudotipo, SUM(conteudodigital.acessos) AS qtdacessos, COUNT(*) AS qtdconteudos FROM conteudodigital INNER JOIN formato ON formato.idformato = conteudodigital.idformato INNER JOIN conteudotipo ON conteudotipo.idconteudotipo = formato.idconteudotipo WHERE conteudodigital.flaprovado = true GROUP BY conteudotipo.idconteudotipo, conteudotipo.nomeconteudotipo ORDER BY LOWER(sem_acentos(conteudotipo.nomeconteudotipo)) ASC");
        return $result;
    }

    /**
     * 
     * @param type $query
     * @return array
     */
    public function executarSQL($query)
    {
        $resultado = $this->getAdapter()->query($query);
        return $this->createObjects($resultado);
    }
    
    /**
     * 
     * @return \Aew_Model_Bo_ConteudoDigital
     */
    public function createModelBo()
    {
        return new Aew_Model_Bo_ConteudoDigital();
    }
}