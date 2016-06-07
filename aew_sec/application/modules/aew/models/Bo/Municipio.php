<?php
/**
 * BO DE MUNICIPIOS
 */
class Aew_Model_Bo_Municipio extends Sec_Model_Bo_Abstract
{   
    
    protected $nomemunicipio,$codigoibgesiig,$idestado;
    
    /**
     * 
     * @return int
     */
    public function getIdestado()
    {
        return $this->idestado;
    }

    /**
     * 
     * @param int $idestado
     */
    public function setIdestado($idestado)
    {
        $this->idestado = $idestado;
    }

    /**
     * 
     * @return string
     */
    public function getNome() {
        return $this->nomemunicipio;
    }

    /**
     * 
     * @return string
     */
    public function getCodigoIbgeSiig() {
        return $this->codigoibgesiig;
    }

    /**
     * 
     * @param string $nome
     */
    public function setNome($nome) {
        $this->nomemunicipio = $nome;
    }

    /**
     * 
     * @param string $codigoIbgeSiig
     */
    public function setCodigoIbgeSiig($codigoIbgeSiig) {
        $this->codigoibgesiig = $codigoIbgeSiig;
    }

    /**
     * cria objeto de acesso ao banco de dados
     * @return \Aew_Model_Dao_Municipio
     */
    protected function createDao() {
        $dao =  new Aew_Model_Dao_Municipio();
        return $dao;
    }

}