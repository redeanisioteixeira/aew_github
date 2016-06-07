<?php


class Aew_Model_Bo_UsuarioMinhasRedesSociais extends Aew_Model_Bo_RedeSocial
{

    protected $idusuario, $idredesocial,$url;

    /**
     * @return int
     */
    public function getIdusuario()
    {
        return $this->idusuario;
    }

    /**
     * @return int
     */
    public function getIdredesocial()
    {
        return $this->idredesocial;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * 
     * @param int $idusuario
     */
    public function setIdusuario($idusuario)
    {
        $this->idusuario = $idusuario;
    }

    /**
     * @param int $idredesocial
     */
    public function setIdredesocial($idredesocial)
    {
        $this->idredesocial = $idredesocial;
    }

    /**
     * 
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }
    
    /**
     * cria objeto de acesso ao banco de dados
     * @return \Aew_Model_Dao_UsuarioMinhasRedesSociais
     */
    function createDao() {
        return new Aew_Model_Dao_UsuarioMinhasRedesSociais();
    }

}
