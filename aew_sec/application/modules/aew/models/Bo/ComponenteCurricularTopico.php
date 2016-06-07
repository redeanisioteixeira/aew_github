<?php
/**
 * BO dat entidade ComponenteCurricularTopico
 *
 * @author tiago-souza
 */
class Aew_Model_Bo_ComponenteCurricularTopico extends Sec_Model_Bo_Abstract
{
    protected $componenteCurricular; //Aew_Model_Bo_ComponenteCurricular
    protected $nome; //varchar(2000)
    protected $url; //varchar(500)
    protected $flvisivel; //tinyint(1)
    protected $idcomponentecurriculartopicopai; //int(11)
    protected $flativo;

    function __construct()
    {
        $this->setComponenteCurricular(new Aew_Model_Bo_ComponenteCurricular());
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
        if($this->getComponenteCurricular()->getId())
        {
             $data['idcomponentecurricular'] = $this->getComponenteCurricular()->getId();
             $this->getDao()->setTableInTableField("idcomponentecurricular", $this->getDao()->getName());
        }
        if($this->getNome())
        {
             $data['nomecomponentecurriculartopico'] = $this->getNome();
        }
        if($this->getUrl())
        {
             $data['urlcomponentecurriculartopico'] = $this->getUrl();
        }
        if($this->getFlvisivel())
        {
             $data['flvisivel'] = $this->getFlvisivel();
        }
        if($this->getIdcomponentecurriculartopicopai())
        {
             $data['idcomponentecurriculartopicopai'] = $this->getIdcomponentecurriculartopicopai();
        }
        if($this->getFlativo())
        {
            $data['flativo'] = $this->getFlativo();
        }
        return $data;
    }

    /**
     * preenche o objeto com dados de uma array (de mapeamento chave-valor)
     * @param array $data
     */
    public function exchangeArray($data){
        parent::exchangeArray($data);
        $this->setNome(isset($data['nomecomponentecurriculartopico'])? $data['nomecomponentecurriculartopico']: null);
        $this->setUrl(isset($data['urlcomponentecurriculartopico'])? $data['urlcomponentecurriculartopico']: null);
        $this->setFlvisivel(isset($data['flvisivel'])? $data['flvisivel']: null);
        $this->setIdcomponentecurriculartopicopai(isset($data['idcomponentecurriculartopicopai'])? $data['idcomponentecurriculartopicopai']: null);
        $this->setFlativo(isset($data['flativo'])? $data['flativo']: null);
        $this->getComponenteCurricular()->exchangeArray($data);
    }
    
    /**
     * 
     * @return boolean  
     */
    public function getFlativo()
    {
        return $this->flativo;
    }

    /**
     * 
     * @param boolean $flativo
     */
    public function setFlativo($flativo)
    {
        $this->flativo = $flativo;
    }

    /**
     * 
     * @return Aew_Model_Bo_ComponenteCurricular
     */
    public function getComponenteCurricular()
    {
        return $this->componenteCurricular;
    }

    /**
     * 
     * @param Aew_Model_Bo_ComponenteCurricular $componenteCurricular
     */
    public function setComponenteCurricular(Aew_Model_Bo_ComponenteCurricular $componenteCurricular)
    {
        $this->componenteCurricular = $componenteCurricular;
    }

        
    /**
     * @return idcomponentecurriculartopico - bigint(20) unsigned
     */
    public function getIdcomponentecurriculartopico(){
    	return $this->idcomponentecurriculartopico;
    }

    /**
     * @return nomecomponentecurriculartopico - varchar(2000)
     */
    public function getNome(){
    	return $this->nome;
    }

    /**
     * @return urlcomponentecurriculartopico - varchar(500)
     */
    public function getUrl(){
    	return $this->url;
    }

    /**
     * @return flvisivel - tinyint(1)
     */
    public function getFlvisivel(){
    	return $this->flvisivel;
    }

    /**
     * @return idcomponentecurriculartopicopai - int(11)
     */
    public function getIdcomponentecurriculartopicopai(){
    	return $this->idcomponentecurriculartopicopai;
    }

    /**
     * @param Type: bigint(20) unsigned
     */
    public function setIdcomponentecurriculartopico($idcomponentecurriculartopico){
    	$this->idcomponentecurriculartopico = $idcomponentecurriculartopico;
    }

    /**
     * @param Type: varchar(2000)
     */
    public function setNome($nome){
    	$this->nome = $nome;
    }

    /**
     * @param Type: varchar(500)
     */
    public function setUrl($urlcomponentecurriculartopico){
    	$this->url = $urlcomponentecurriculartopico;
    }

    /**
     * @param Type: tinyint(1)
     */
    public function setFlvisivel($flvisivel){
    	$this->flvisivel = $flvisivel;
    }

    /**
     * @param Type: int(11)
     */
    public function setIdcomponentecurriculartopicopai($idcomponentecurriculartopicopai){
    	$this->idcomponentecurriculartopicopai = $idcomponentecurriculartopicopai;
    }

    /**
     * cria objeto de acesso ao banco de dados
     * @return \Aew_Model_Dao_ComponenteCurricularTopico
     */
    protected function createDao() {
        return new Aew_Model_Dao_ComponenteCurricularTopico();
    }

}