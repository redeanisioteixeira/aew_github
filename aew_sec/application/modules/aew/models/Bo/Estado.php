<?php

/**
 * BO da entidade Estado
 */
class Aew_Model_Bo_Estado extends Sec_Model_Bo_Abstract
{
    protected $nomeestado; //varchar(150)
    protected $codigoibgesiig; //varchar(16)
    
    /***/
    public function exchangeArray($data)
    {
        parent::exchangeArray($data);
        $this->setNome(isset($data['nomeestado'])? $data['nomeestado']: null);
        $this->setCodigoibgesiig(isset($data['codigoibgesiig'])? $data['codigoibgesiig']: null);
    }
    
    /**
     * Construtor
     */
    public function __construct()
    {
        $this->setDao(new  Aew_Model_Dao_Estado());
    }
    
    /**
     * 
     * @return string
     */
    public function getNome() {
        return $this->nomeestado;
    }

    /**
     * 
     * @return string
     */
    public function getCodigoibgesiig() {
        return $this->codigoibgesiig;
    }

    /**
     * 
     * @param string $nome
     */
    public function setNome($nome) {
        $this->nomeestado = $nome;
    }

    /**
     * 
     * @param string $codigoibgesiig
     */
    public function setCodigoibgesiig($codigoibgesiig) {
        $this->codigoibgesiig = $codigoibgesiig;
    }

    
    /**
     * Carrega tabela de estados
     */
    public function loadEstados()
    {
        $this->getDao()->loadEstados();
    }

    /**
     * Recarrega a tabela de municipios
     */
    public function loadMunicipios()
    {
        $this->getDao()->loadMunicipios();
    }

    /**
     * Recarrega a tabela de municipios
     * @param $estado
     */
    public function loadMunicipiosByEstado($estado)
    {
        $this->getDao()->loadMunicipiosByEstado($estado);
    }

    /**
     * cria objeto de acesso ao banco de dados
     * @return \Aew_Model_Dao_Estado
     */
    protected function createDao() {
        $dao =  new Aew_Model_Dao_Estado();
        return $dao;
    }

}