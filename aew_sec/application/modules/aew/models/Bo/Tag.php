<?php
/**
 * BO da entidade Tag
 */
class Aew_Model_Bo_Tag extends Sec_Model_Bo_Abstract
{
    protected $nometag; //varchar(150)
    protected $busca; //int(11)
    protected $idcomponentecurricular;
    protected $dataatualizacao; //timestamp
    
    public $qtdeusoCD;
    public $qtdeusoAA;
    public $idletra;
    public $nomeletra;

    /**
     * @return int
     */
    public function getIdCoomponenteCurricular()
    {
        return $this->idcomponentecurricular;
    }

    /**
     * 
     * @param int $idCoomponenteCurricular
     */
    public function setIdCoomponenteCurricular($idCoomponenteCurricular)
    {
        $this->idcomponentecurricular = $idCoomponenteCurricular;
    }
    
    /**
     * @return string
     */
    public function getNome()
    {
        return $this->nometag;
    }

    /**
     * 
     * @return int
     */
    public function getBusca()
    {
        return $this->busca;
    }

    /**
     * 
     * @return string
     */
    public function getDataAtualizacao()
    {
        return $this->dataatualizacao;
    }
    
    /**
     * 
     * @param int $qtdeusoCD
     */
    function setQtdeusoCD($qtdeusoCD)
    {
        $this->qtdeusoCD = $qtdeUsoCD;
    }

    /**
     * 
     * @return int
     */
    function getQtdeusoCD()
	{
        return $this->qtdeusoCD;
    }

    /**
     * 
     * @param int $qtdeusoAA
     */
    function setQtdeusoAA($qtdeusoAA)
	{
        $this->qtdeusoAA = $qtdeUsoAA;
    }

    /**
     * 
     * @return int
     */
    function getQtdeusoAA()
	{
        return $this->qtdeusoAA;
    }

    /**
     * 
     * @return int
     */
    function getIdletra()
    {
        return $this->idletra;
    }

    /**
     * 
     * @return string
     */
    function getNomeletra()
    {
        return $this->nomeletra;
    }

    /**
     * 
     * @param int $idletra
     */
    function setIdletra($idletra)
	{
        $this->idletra = $idletra;
    }

    /**
     * 
     * @param string $nomeletra
     */
    function setNomeletra($nomeletra)
	{
        $this->nomeletra = $nomeletra;
    }

    public function setNome($nometag)
	{
        $this->nometag = strtolower($nometag);
    }

    /**
     * 
     * @param int $busca
     */
    public function setBusca($busca)
    {
        $this->busca = $busca;
    }

    /**
     * 
     * @param string $dataAtualizacao
     */
    public function setDataAtualizacao($dataAtualizacao)
    {
        $this->dataatualizacao = Sec_Date::setDoctrineDate($dataAtualizacao);
    }

    /**
     * 
     * @param int $id
     * @param int $num
     * @param int $offset
     * @param array $options
     * @return array (Aew_Model_Bo_Tag)
     */
    public function selectNumUsos($id = null, $num = 0, $offset = 0 , $options = null)
    {
        $result = $this->getDao()->selectNumUsos($id, $num, $offset, $options);
        return $result;
    }

    /**
     * 
     * @param string $nome
     * @return array
     */
    public function filtrarPorNome($nome)
    {
        $result = $this->getDao()->filtrarPorNome($nome);
        return $result;
    }

    /**
     * retorna tags filtradas pela letra inicial
     * @return array
     */
    public function filtrarLetras()
    {
        $result = $this->getDao()->filtrarLetras();
        return $result;
    }

    /**
     * retorna colecao de tags mais utilizadas
     * @param array $options
     * @return array
     */
    public function getAllTagFromCloud(array $options = null)
    {
        $dataAtualizacao = new Sec_Date();
        $this->setDataAtualizacao($dataAtualizacao);
        $result = $this->getDao()->getAllTagFromCloud($this->toArray());
        return $result;
    }

    /**
     * incrementa valor da busca da tag
     * @return type
     */
    public function aumentarBusca()
    {
	$this->setDataAtualizacao(date('d/m/Y H:m:s'));
        $this->setBusca($this->getBusca()+1);
        return $this->update();
    }

    /**
     * cria objeto de acesso ao banco de dados
     * @return \Aew_Model_Dao_Tag
     */
    protected function createDao() {
        return new Aew_Model_Dao_Tag();
    }
}
