<?php
class Aew_Model_Bo_UsuarioRedeSocial extends Aew_Model_Bo_RedeSocial
{
    protected $idusuario; //int(11)
    protected $idredesocial; //int(11)
    protected $url; //varchar(250)
    
    /**
     * @return int
     */
    public function getIdredesocial() 
    {
        return $this->idredesocial;
    }
    
    /**
     * @param int $idredesocial
     */
    public function setIdredesocial($idredesocial) {
        $this->idredesocial = $idredesocial;
    }

    /**
     * @return int
     */
    public function getIdUsuario() {
        return $this->idusuario;
    }

    /**
     * @return string
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * @param int $idUsuario
     */
    public function setIdUsuario($idUsuario) {
        $this->idusuario = $idUsuario;
    }

    /**
     * 
     * @param string $url
     */
    public function setUrl($url) {
        $this->url = $url;
    }

    /**
     * 
     * @param int $idRede
     * @param string $url
     * @param int $usuario
     * @return mixed
     */
    public function existeUrl($idRede, $url, $usuario)
    {
        return $this->getDao()->existeUrl($idRede, $url, $usuario);
    }
    
    /**
     * retorna objeto de acesso ao banco de dados
     * @return \Aew_Model_Dao_UsuarioRedeSocial
     */
    function createDao() {
        return new Aew_Model_Dao_UsuarioRedeSocial();
    }
}  