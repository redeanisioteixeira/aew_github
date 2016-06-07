<?php

/**
 * BO da entidade UsuarioColega
 */

class Aew_Model_Bo_UsuarioColega extends Sec_Model_Bo_Abstract
{
    protected $idusuario; //int(11)
    protected $datacriacao; //timestamp
    protected $usuario;
    protected $colega;
    protected $flativocolega; //tinyint(4)
    protected $idcolega; //int(11)
    protected $visto; //tinyint(4)
    
    function getFlativocolega() {
        return $this->flativocolega;
    }

    function setFlativocolega($flativocolega) {
        $this->flativocolega = $flativocolega;
    }

    function __construct() 
    {
        $this->setColega(new Aew_Model_Bo_Usuario());
        $this->setUsuario(new Aew_Model_Bo_Usuario());
    }
    
    public function exchangeArray($data)
    {
        parent::exchangeArray($data);

        $this->getUsuario()->exchangeArray($data);
        $this->getColega()->setNome(isset($data['nome2'])?$data['nome2']: null);
        $this->getColega()->setEmail(isset($data['email2'])?$data['email2']: null);
        $this->getColega()->getFotoPerfil()->setExtensao(isset($data['extensao2'])?$data['extensao2']: null);
        $this->getColega()->getFotoPerfil()->setId(isset($data['idusuariofoto2'])?$data['idusuariofoto2']: null);
        $this->getColega()->setId(isset($data['idusuarioadd2'])?$data['idusuarioadd2']: null);
    }

    /**
     * @return Aew_Model_Bo_Usuario
    */
    function getUsuario() {
        return $this->usuario;
    }

    /**
     * @return Aew_Model_Bo_Usuario
     */
    function getColega() {
        return $this->colega;
    }

    /**
     * @param Aew_Model_Bo_Usuario $usuario
     */
    function setUsuario($usuario) {
        $this->usuario = $usuario;
    }

    /**
     * @param Aew_Model_Bo_Usuario $colega
     */
    function setColega(Aew_Model_Bo_Usuario $colega) {
        $this->colega = $colega;
    }

    /**
     * @return int
     */
    public function getIdusuario() {
        return $this->idusuario;
    }

    /**
     * @return string
     */
    public function getDatacriacao() {
        return $this->datacriacao;
    }

    /**
     * 
     * @return int
     */
    public function getIdcolega() {
        return $this->idcolega;
    }

    /**
     * @return boolean
     */
    public function getVisto() {
        return $this->visto;
    }

    /**
     * 
     * @param int $idusuario
     */
    public function setIdusuario($idusuario) {
        $this->idusuario = $idusuario;
    }

    /**
     * 
     * @param string $datacriacao
     */
    public function setDatacriacao($datacriacao) {
        $this->datacriacao = $datacriacao;
    }

    /**
     * 
     * @param int $idcolega
     */
    public function setIdcolega($idcolega) {
        $this->idcolega = $idcolega;
    }

    /**
     * 
     * @param boolean $visto
     */
    public function setVisto($visto) {
        $this->visto = $visto;
    }

   /**
    * 
    * @return strings
    */
    function getLinkPerfil()
    {
        return '/espaco-aberto/perfil/feed/usuario/'.$this->getIdusuario();
    }
    
    /**
     * cria objeto de acesso ao banco de dados
     * @return \Aew_Model_Dao_UsuarioColega
     */
    function createDao() {
        return new Aew_Model_Dao_UsuarioColega();
    }
}