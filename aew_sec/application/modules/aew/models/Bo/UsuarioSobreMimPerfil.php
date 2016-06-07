<?php


class Aew_Model_Bo_UsuarioSobreMimPerfil extends Sec_Model_Bo_Abstract
{

    protected $idusuario; //int(11)
    protected $sobremim; //text
    protected $cidadenatal; //varchar(250)
    protected $lattes; //varchar(250)
    protected $dataenvio; //timestamp
    
    /**
     * 
     * @return int
     */
    public function getIdusuario() {
        return $this->idusuario;
    }

    /**
     * 
     * @return string
     */
    public function getCidadenatal() {
        return $this->cidadenatal;
    }

    /**
     * @return string
     */
    public function getLattes() {
        return $this->lattes;
    }

    /**
     * ultima data de atualizacao
     * @return string
     */
    public function getDataenvio() {
        return $this->dataenvio;
    }

    /**
     * 
     * @param int $idusuario
     */
    public function setIdusuario($idusuario) {
        $this->idusuario = $idusuario;
    }

    /**
     * @param string $cidadenatal
     */
    public function setCidadenatal($cidadenatal) {
        $this->cidadenatal = $cidadenatal;
    }

    /**
     * @param string $lattes
     */
    public function setLattes($lattes) {
        $this->lattes = $lattes;
    }

    /**
     * 
     * @param int $dataenvio
     */
    public function setDataenvio($dataenvio) {
        $this->dataenvio = $dataenvio;
    }

    /**
     * texto de descricao do usuario
     * @return string
     */
    public function getSobremim()
    {
        return $this->sobremim;
    }

    /**
     * 
     * @param string $sobremim
     */
    public function setSobremim($sobremim)
    {
        $this->sobremim = $sobremim;
    }

    /**
     * cria objeto de acesso ao banco de dados
     * @return \Aew_Model_Dao_UsuarioSobreMimPerfil
     */
    protected function createDao() {
        $dao =  new Aew_Model_Dao_UsuarioSobreMimPerfil();
        return $dao;
    }

}