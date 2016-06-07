<?php

/**
 * BO da entidade Usuario Tipo
 */
class Aew_Model_Bo_ComponenteCurricular extends Sec_Model_Bo_Abstract
{
    protected $nomecomponentecurricular;
    protected $nivelEnsino;
    protected $categoriaComponenteCurricular;
    protected $conteudosDigitais;
    protected $idconteudodigital;
    protected $tags = array();
    protected $topicos = array();
    
    /**
     * Construtor
     */
    public function __construct()
    {
        $this->setNivelEnsino(new Aew_Model_Bo_NivelEnsino()); 
        $this->setCategoriaComponenteCurricular(new Aew_Model_Bo_CategoriaComponenteCurricular());
    }
    
    /**
     * topicos do componente curricular (local)
     * @return array
     */
    public function getTopicos()
    {
        return $this->topicos;
    }

    /**
     * @param array $topicos
     */
    public function setTopicos($topicos)
    {
        $this->topicos = $topicos;
    }

    /**
     * palavras chaves do componente curricular
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * 
     * @param array $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    /**
     * id do conteudo digital relacionado a este componente
     * @return int
     */
    public function getIdConteudoDigital()
    {
        return $this->idconteudodigital;
    }

    /**
     * 
     * @param type $idConteudoDigital
     */
    public function setIdConteudoDigital($idConteudoDigital)
    {
        $this->idconteudodigital = $idConteudoDigital;
    }

    /**
     * retorna a categoria do componente curricular
     * @return Aew_Model_Bo_CategoriaComponenteCurricular
     */
    public function getCategoriaComponenteCurricular() {
        return $this->categoriaComponenteCurricular;
    }

    /**
     * 
     * @return array
     */
    public function getConteudosDigitais()
    {
        return $this->conteudosDigitais;
    }

    /**
     * 
     * @param array $conteudosDigitais
     */
    public function setConteudosDigitais($conteudosDigitais)
    {
        $this->conteudosDigitais = $conteudosDigitais;
    }

    /**
     * 
     * @param Aew_Model_Bo_CategoriaComponenteCurricular $categoriaComponenteCurricular
     */
    public function setCategoriaComponenteCurricular(Aew_Model_Bo_CategoriaComponenteCurricular $categoriaComponenteCurricular)
    {
        $this->categoriaComponenteCurricular = $categoriaComponenteCurricular;
    }

    /**
     * 
     * @return string
     */
    public function getNome() {
        return $this->nomecomponentecurricular;
    }

    /**
     * @return Aew_Model_Bo_NivelEnsino
     */
    public function getNivelEnsino() {
        return $this->nivelEnsino;
    }

    /**
     * 
     * @param string $nomecomponentecurricular
     */
    public function setNome($nomecomponentecurricular) {
        $this->nomecomponentecurricular = $nomecomponentecurricular;
    }
    
    
   /**
    * 
    * @param Aew_Model_Bo_NivelEnsino $nivelEnsino
    */
    public function setNivelEnsino(Aew_Model_Bo_NivelEnsino $nivelEnsino) {
        $this->nivelEnsino = $nivelEnsino;
    }

    /**
     * retorna os parametro do objeto em um array
     * onde a chave e o nome da variavel de instancia e tambem
     * o nome do campo na tabela
     * @return array
     */
    function toArray()
    {
        $data = parent::toArray();
        if($this->getCategoriaComponenteCurricular()->getId())
        {
            $data["idcategoriacomponentecurricular"] = $this->getCategoriaComponenteCurricular()->getId();
            $this->getDao()->setTableInTableField('idcategoriacomponentecurricular', 'categoriacomponentecurricular');
        }
        if($this->getNivelEnsino()->getId())
        {
            $data["idnivelensino"] = $this->getNivelEnsino()->getId();
            $this->getDao()->setTableInTableField('idnivelensino', 'nivelensino');
        }
        if($this->getIdConteudoDigital())
        {
            $data['idconteudodigital'] = $this->getIdConteudoDigital();
        }
        return $data;
    }

    /***/
    public function exchangeArray($data)
    {
        parent::exchangeArray($data);
        $this->getIdConteudoDigital(isset($data["idconteudodigital"])?$data["idconteudodigital"]:null);
        $this->getNivelEnsino()->exchangeArray($data);
        $this->getCategoriaComponenteCurricular()->exchangeArray($data);
    }
    
    /**
     * @param int $num
     * @param int $offset
     * @param Aew_Model_Bo_ConteudoDigital $conteudo
     * @return type
     */
    function selectConteudosDigitais($num=0,$offset=0,  Aew_Model_Bo_ConteudoDigital $conteudo = null, $options = null)
    {
    	if(!$conteudo)
        {
            $conteudo = new Aew_Model_Bo_ConteudoDigital();
        }
        $conteudo->setIdComponenteCurricular($this->getId());
        $this->setConteudosDigitais($conteudo->select($num, $offset, $options));
        return $this->getConteudosDigitais();
    }
    
    /**
     * @param int $num
     * @param int $offset
     * @param Aew_Model_Bo_Tag $tag
     * @return array
     */
    function selectTags($num = 0, $offset = 0, Aew_Model_Bo_Tag $tag = null)
    {
        if(!$tag)
        $tag = new Aew_Model_Bo_Tag();
        $tag->setIdCoomponenteCurricular($this->getId());
        $this->setTags($tag->select($num, $offset));
        return $this->getTags();
    }
    /**
     * @param int $num
     * @param int $offset
     * @param Aew_Model_Bo_ConteudoDigital $conteudo
     * @return array
     */
    function selectSitesTematicos($num=0,$offset=0,  Aew_Model_Bo_ConteudoDigital $conteudo = null, $options = null)
    {
        if(!$conteudo)
        $conteudo = new Aew_Model_Bo_ConteudoDigital();
        $conteudoTipo = new Aew_Model_Bo_ConteudoTipo();
        $conteudoTipo->setId(Aew_Model_Bo_ConteudoTipo::$SITE);
        $conteudo->getFormato()->setConteudoTipo($conteudoTipo);
        $conteudo->setFlSiteTematico(true);
        $conteudo->setFlaprovado(true);
        return $this->selectConteudosDigitais($num, $offset, $conteudo, $options);
        
    }
    
    /**
     * seleciona no banco os topicos deste compoente curricular
     * @param int $num
     * @param int $offset
     * @param Aew_Model_Bo_ComponenteCurricularTopico $topico
     * @return array
     */
    function selectComponenteCurricularTopicos($num=0,$offset=0, Aew_Model_Bo_ComponenteCurricularTopico $topico = null)
    {
        if(!$topico)
        $topico = new Aew_Model_Bo_ComponenteCurricularTopico();
        $topico->setComponenteCurricular($this);
        $this->setTopicos($topico->select($num, $offset));
        return $this->getTopicos();
    }

    protected function createDao() {
        $dao =  new Aew_Model_Dao_ComponenteCurricular();
        return $dao;
    }

}
