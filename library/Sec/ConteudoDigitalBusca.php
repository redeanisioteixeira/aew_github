<?php
/**
 * Description of ConteudoDigitalBusca
 * @author tiago-souza
 */
class Sec_ConteudoDigitalBusca
{
    //put your code here
    protected $formatos = array();
    protected $conteudoTipo;
    protected $conteudoLicenca;
    protected $conteudoDigital;
    protected $ordem = array();
    protected $tags = array();
    protected $usuarioPublicador;
    protected $conteudotag;
    protected $request;
    protected $componenteCurricularCategoria;
    protected $nivelEnsino;
    protected $componenteCurricular;
    protected $qtd;
    protected $indice;
    protected $favorito;
    protected $usuarioLogado;
    
    public function __construct(Zend_Controller_Request_Abstract $request, $conteudoDigital = null, Aew_Model_Bo_Usuario $usuario = null)
    {
        $session = new Zend_Session_Namespace("conteudosDigitaisBusca");
        
        $arguments = $session->getIterator()->getArrayCopy();
        
        $request->setParams($arguments);
        
        $this->setRequest($request);
        
        $this->criaConteudosTipos();
        $this->criaOrdem();
        $this->criaPalavrasChaves();
        $this->criaUsuarioPublicador();
        $this->criaComponentesCurriculares();
        $this->criaConteudosLicencas();
        $this->criaTag();
        
        $this->setQtd($this->getRequest()->getParam('quantidade',15));
        $this->setIndice($this->getRequest()->getParam('pagina',1));
        $this->setFavorito($this->getRequest()->getParam('favorito',0));
        $this->setUsuarioLogado($usuario);
        
        if(!$conteudoDigital)
        {
            $conteudoDigital = new Aew_Model_Bo_ConteudoDigital();
        }

        $conteudoDigital->setUsuarioLogado($this->getUsuarioLogado());
        
        $this->setConteudoDigital($conteudoDigital);
    }
    
    function getUsuarioLogado() {
        return $this->usuarioLogado;
    }

    function setUsuarioLogado($usuarioLogado) {
        $this->usuarioLogado = $usuarioLogado;
    }

    function getFavorito() {
        return $this->favorito;
    }

    function setFavorito($favorito) {
        $this->favorito = $favorito;
    }

    public function getQtd()
    {
        return $this->qtd;
    }

    public function getIndice()
    {
        return $this->indice;
    }

    public function setQtd($qtd)
    {
        $this->qtd = $qtd;
    }

    public function setIndice($indice)
    {
        $this->indice = $indice;
    }

    function getComponenteCurricularCategoria()
    {
        return $this->componenteCurricularCategoria;
    }

    function setComponenteCurricularCategoria($componenteCurricularCategoria)
    {
        $this->componenteCurricularCategoria = $componenteCurricularCategoria;
    }
    
    function getNivelEnsino()
    {
        return $this->nivelEnsino;
    }

    function setNivelEnsino($nivelEnsino)
    {
        $this->nivelEnsino = $nivelEnsino;
    }

    public function getComponenteCurricular()
    {
        return $this->componenteCurricular;
    }

    public function setComponenteCurricular($componenteCurricular)
    {
        $this->componenteCurricular = $componenteCurricular;
    }

    private function criaUsuarioPublicador()
    {
        $idpublicador = $this->getRequest()->getParam("publicador");
        
        $usuarioPublicador = new Aew_Model_Bo_Usuario();
        $usuarioPublicador->setId($idpublicador);

        if($usuarioPublicador->selectAutoDados())
        {
            $this->usuarioPublicador = $usuarioPublicador;
        }
    }
    
    public function getTags()
    {
        return $this->tags;
    }

    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    public function getOrdem()
    {
        return $this->ordem;
    }

    public function setOrdem($ordem)
    {
        $this->ordem = $ordem;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function setRequest($request)
    {
        $this->request = $request;
    }

    protected function criaOrdem()
    {
        $ordem = array();
        $ordemParam = $this->getRequest()->getParam("ordenarPor");

        if(strpos($ordemParam, 'data') || $ordemParam == 'data')  
        {
            $ordem['datapublicacao'] = (strpos($ordemParam,'asc') ? 'ASC' : 'DESC');
        }
        
        if(strpos($ordemParam, 'avaliacao') || $ordemParam == 'avaliacao')
        {
            $ordem['avaliacao'] = (strpos($ordemParam,'asc') ? 'ASC NULLS LAST' : 'DESC NULLS LAST');
        }
        
        if(strpos($ordemParam, 'popularidade') || $ordemParam == 'popularidade')
        {
            $ordem['acessos'] = (strpos($ordemParam,'asc') ? 'ASC NULLS LAST' : 'DESC NULLS LAST');
        }
        
        if(strpos($ordemParam, 'titulo') || $ordemParam == 'titulo')
        {
            $ordem['titulo'] = (strpos($ordemParam,'desc') ? 'DESC' : 'ASC');
        }

        $this->setOrdem($ordem);
    }
    
    public function setAvaliacao($avaliacao)
    {
        $this->avaliacao = $avaliacao;
    }

    public function setPopularidade($popularidade)
    {
        $this->popularidade = $popularidade;
    }

    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
    }

    protected function criaConteudosTipos()
    {
        $tipos = explode(",",$this->getRequest()->getParam('tipos'));
        if(!$tipos)
        {
            return;
        }
        
        if(!is_array($tipos))
        {
            $tipos = explode(',', $tipos);
        }

        $idtipos = array();
        foreach ($tipos as $tipo) 
        {
            $tipo = trim($tipo);
            if($tipo)
            {
                array_push($idtipos,$tipo);
            }
        }
        
        $conteudoTipo = new Aew_Model_Bo_ConteudoTipo();
        $conteudoTipo->setId($idtipos);
        
        $this->setConteudoTipo($conteudoTipo);
    }
    
    protected function criaComponentesCurriculares()
    {	
        $opcoes = $this->getRequest()->getParam('opcoes', false);
        $categorias = $this->getRequest()->getParam('categorias', false);
        $niveisEnsino = $this->getRequest()->getParam('niveisensino', false);
        
        if(!$opcoes)
        {
            return;
        }
        
        if(!is_array($opcoes))
        {
            $opcoes = explode(',', $opcoes);
        } 
        
        if(!is_array($categorias))
        {
            $categorias = explode(',', $categorias);
        } 
        
        if(!is_array($niveisEnsino))
        {
            $niveisEnsino = explode(',', $niveisEnsino);
        } 
      
        $categoriaComponenteCurricular = new Aew_Model_Bo_CategoriaComponenteCurricular();
        $categoriaComponenteCurricular->setId($categorias);
        
        $nivelEnsino = new Aew_Model_Bo_NivelEnsino();
        $nivelEnsino->setId($niveisEnsino);
        
        $componenteCurricular = new Aew_Model_Bo_ComponenteCurricular();
        $componenteCurricular->setNivelEnsino($nivelEnsino);
        
        $componenteCurricular->setCategoriaComponenteCurricular($categoriaComponenteCurricular);
        $componenteCurricular->setId($opcoes);
        
        $this->setComponenteCurricular($componenteCurricular);
    }

    protected function criaConteudosLicencas()
    {
        $licencas = explode(",",$this->getRequest()->getParam('licencas', false));
        if(!$licencas)
            return;

        if(!is_array($licencas))
        {
            $licencas = explode(',', $licencas);
        }

        $idlicencas = array();
        foreach ($licencas as $licenca) 
        {
            $licenca = trim($licenca);
            if($licenca)
                array_push($idlicencas, $licenca);
        }
        
        $conteudoLicenca = new Aew_Model_Bo_ConteudoLicenca();
        $conteudoLicenca->setId($idlicencas);
        
        $this->setConteudoLicenca($conteudoLicenca);
	}

	protected function criaTag()
	{
        $tag = $this->getRequest()->getParam('tag', 0);
        if(!$tag)
            return;
		$this->setConteudoTag($tag);
    }

    public function setConteudoTag($conteudotag)
    {
        $this->conteudotag = $conteudotag;
    }

    public function getConteudoTag()
    {
		return $this->conteudotag;
    }

    public function getFormatos()
    {
        return $this->formatos;
    }

    public function getConteudoTipo()
    {
        return $this->conteudoTipo;
    }

    public function getConteudoLicenca()
    {
        return $this->conteudoLicenca;
    }
    
    /**
     * 
     * @return Aew_Model_Bo_ConteudoDigital
     */
    public function getConteudoDigital()
    {
        return $this->conteudosDigital;
    }

    public function setFormatos($formatos)
    {
        $this->formatos = $formatos;
    }

    /**
     * 
     * @param Aew_Model_Bo_ConteudoTipo $conteudoTipo
     */
    public function setConteudoTipo(Aew_Model_Bo_ConteudoTipo $conteudoTipo)
    {
        $this->conteudoTipo = $conteudoTipo;
    }

    public function setConteudoLicenca(Aew_Model_Bo_ConteudoLicenca $conteudoLicenca)
    {
        $this->conteudoLicenca = $conteudoLicenca;
    }
    
    public function setConteudoDigital($conteudosDigital)
    {
        $this->conteudosDigital = $conteudosDigital;
    }
    
    protected function criaPalavrasChaves()
    {
		$tagsBusca = array();
        $palavras = $this->getRequest()->getParam('busca',0);

		if(!$palavras)
		{
			return;
		}

        $tag = new Aew_Model_Bo_Tag();
		if($palavras[0] == "\"" && $palavras[count($palavras)-1] == "\"")
		{
			$palavras = trim($palavras, "\"");
		}

		$tagsBusca[] = $palavras;

        $tags = array();
        $palavrasChaves = explode(',', $palavras);

        if($palavras)
        {
            $tag->setNome($palavras);
            array_push($tags, $tag);
        }

        foreach ($palavrasChaves as $palavra)
        {
            $tag = new Aew_Model_Bo_ConteudoDigitalTag();
            $tag->setNome($palavra);
            if($palavra)
			{	$palavra = explode(' ', $palavra);
				$tagsBusca = array_merge($tagsBusca, $palavra);
	            array_push($tags, $tag);
			}
        }

		$tagsBusca = array_unique($tagsBusca);
		if(count($tagsBusca))
		{	
			$options = array(); 

			$sqlTags  = "LOWER(sem_acentos(tag.nometag)) = LOWER(sem_acentos('";
			$sqlTags .= implode("')) OR LOWER(sem_acentos(tag.nometag)) = LOWER(sem_acentos('",$tagsBusca);
			$sqlTags .= "'))";

			$options["where"] = $sqlTags;

	        $tagsBo = new Aew_Model_Bo_Tag();
			$tagsBo = $tagsBo->select(0, 0, $options);
			if($tagsBo)
			{
				foreach($tagsBo as $tag)
				{
					$tag->aumentarBusca();
				}
			}
		}

        $this->setTags($tags);
    }
    
    /**
     * 
     * @return array(Aew_Model_Bo_ConteudoDigital)
     */
    function busca($todos = false)
    {
        $conteudoDigital = $this->getConteudoDigital();
        
        $conteudoDigital->setTags($this->getTags());
        
        if($this->getRequest()->getParam('busca'))
        {
            $conteudoDigital->setTitulo($this->getRequest()->getParam('busca'));

			if($this->getRequest()->getParam('opcao-busca-palavra') == 'tag')
		    {
		        $conteudoDigital->setAutores($this->getRequest()->getParam('busca'));
		        $conteudoDigital->setFonte($this->getRequest()->getParam('busca'));
			}
        }
        
        if($this->usuarioPublicador)
        {
            $conteudoDigital->setUsuarioPublicador($this->usuarioPublicador);
        }
        
        if($this->getConteudoTipo())
        {
            $conteudoDigital->setConteudoTipo($this->getConteudoTipo());
        }
        
        if($this->getConteudoLicenca())
        {
            $conteudoDigital->setConteudoLicenca($this->getConteudoLicenca());
        }

        if($this->getConteudoTag())
        {
			$tagBo = new Aew_Model_Bo_Tag();
			$tagBo->setId($this->getConteudoTag());
            $tagBo = $tagBo->select(1);
                
            if($tagBo instanceof Aew_Model_Bo_Tag)
            {
				$tagBo->aumentarBusca();
                $conteudoDigital->addTag($tagBo);   
            }
        }

        if($this->getComponenteCurricular())
        {
            $conteudoDigital->addComponenteCurricular($this->getComponenteCurricular());
        }
        
        if($this->getUsuarioLogado() && $this->getFavorito())
        {
            $conteudoDigital->setIdFavorito($this->getUsuarioLogado()->getIdfavorito());
        }
        
        $num = $offset = 0;
        if($todos == false)
        {
            $num = $this->getQtd();
            $offset = $this->getIndice();
        }
        return $conteudoDigital->busca($num, $offset, $this->getOrdem());
    }
}
