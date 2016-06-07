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
    protected $conteudoDigital;
    protected $ordem = array();
    protected $tags = array();
    protected $usuarioPublicador;
    protected $request;
    protected $componenteCurricular;
    protected $qtd;
    protected $indice;
    protected $favorito;
    protected $usuarioLogado;
    
    public function __construct(Zend_Controller_Request_Abstract $request, $conteudoDigital = null,Aew_Model_Bo_Usuario $usuario = null)
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
        
        $this->setQtd($this->getRequest()->getParam('quantidade',15));
        $this->setIndice($this->getRequest()->getParam('pagina',1));
        $this->setFavorito($this->getRequest()->getParam('favorito',0));
        
        $this->setUsuarioLogado($usuario);
        if(!$conteudoDigital)
        {
            $conteudoDigital = new Aew_Model_Bo_ConteudoDigital();
        }

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
        $idpublicador = $this->getRequest()->getParam("id");
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
			return;

		if(!is_array($tipos))
		{
			$tipos = explode(',', $tipos);
		}

        $idtipos = array();
        foreach ($tipos as $tipo) 
        {
            $tipo = trim($tipo);
            if($tipo)
                array_push($idtipos,$tipo);
        }
        
        $conteudoTipo = new Aew_Model_Bo_ConteudoTipo();
        $conteudoTipo->setId($idtipos);
        $this->setConteudoTipo($conteudoTipo);
    }
    
    protected function criaComponentesCurriculares()
    {	
		$opcoes = $this->getRequest()->getParam('opcoes', false);
		if(!$opcoes)
			return;

		if(!is_array($opcoes))
		{
			$opcoes = explode(',', $opcoes);
		} 

        $idopcoes = array();
        foreach ($opcoes as $opcao) 
        {
            $opcao = trim($opcao);
            if($opcao)
	            array_push($idopcoes,$opcao);
        }

        $componenteCurricular = new Aew_Model_Bo_ComponenteCurricular();
        $componenteCurricular->setId($idopcoes);
        $this->setComponenteCurricular($componenteCurricular);
    }
    
    public function getFormatos()
    {
        return $this->formatos;
    }

    public function getConteudoTipo()
    {
        return $this->conteudoTipo;
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

    public function setConteudoDigital($conteudosDigital)
    {
        $this->conteudosDigital = $conteudosDigital;
    }
    
    protected function criaPalavrasChaves()
    {
        $palavras = $this->getRequest()->getParam('busca',0);
        $tags = array();
        $palavrasChaves = explode(',', $palavras);
        $tag = new Aew_Model_Bo_Tag();
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
            array_push($tags, $tag);
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
            $conteudoDigital->setAutores($this->getRequest()->getParam('busca'));
            $conteudoDigital->setDescricao($this->getRequest()->getParam('busca'));
        }
        
        if($this->usuarioPublicador)
        {
            $conteudoDigital->setUsuarioPublicador ($this->usuarioPublicador);
            $conteudoDigital->setUsuarioPublicador($this->usuarioPublicador);
        }
        
        if($this->getConteudoTipo())
        {
            $conteudoDigital->setConteudoTipo($this->getConteudoTipo());
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
