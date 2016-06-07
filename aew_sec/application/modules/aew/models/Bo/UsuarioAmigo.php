<?php
/**
 * BO que representa um usuario indicado 
 *
 * @author tiago-souza
 */
class Aew_Model_Bo_UsuarioAmigo extends Sec_Model_Bo_Abstract
{
    protected $idusuario, $usuarioIndicou,$idusuarioaprovar,$flaprovador,$flespacoaberto,$usuarioCriado;
    
    function __construct()
    {
        parent::__construct();
        $this->setUsuarioIndicou(new Aew_Model_Bo_Usuario());
        $this->setUsuarioCriado(new Aew_Model_Bo_Usuario());
    }
    
    function toArray() {
        $data = parent::toArray();
        if($this->getUsuarioIndicou()->getId())
        {
            $data['idusuarioindicou'] = $this->getUsuarioIndicou()->getId();
        }
        return $data;
    }
    
    /**
     * 
     * @return Aew_Model_Bo_Usuario
     */
    function getUsuarioCriado() {
        return $this->usuarioCriado;
    }

    /**
     * 
     * @param Aew_Model_Bo_Usuario $usuarioCriado
     */
    function setUsuarioCriado(Aew_Model_Bo_Usuario $usuarioCriado) {
        $this->usuarioCriado = $usuarioCriado;
    }

    /**
     * @return int
     */
    function getIdusuario() {
        return $this->idusuario;
    }

    /**
     * @param int $idusuario
     */
    function setIdusuario($idusuario) {
        $this->idusuario = $idusuario;
    }

       
    /**
     * retona usuario que indicou colega
     * @return Aew_Model_Bo_Usuario
     */
    public function getUsuarioIndicou()
    {
        return $this->usuarioIndicou;
    }

    /**
     * @return int
     */
    public function getIdusuarioaprovar()
    {
        return $this->idusuarioaprovar;
    }

    /**
     * 
     * @return 
     */
    public function getFlaprovador()
    {
        return $this->flaprovador;
    }

    public function getFlespacoaberto()
    {
        return $this->flespacoaberto;
    }


    /**
     * @param Aew_Model_Bo_Usuario $usuarioIndicou
     */
    public function setUsuarioIndicou(Aew_Model_Bo_Usuario $usuarioIndicou)
    {
        $this->usuarioIndicou = $usuarioIndicou;
    }

    public function setIdusuarioaprovar($idusuarioaprovar)
    {
        $this->idusuarioaprovar = $idusuarioaprovar;
    }

    public function setFlaprovador($flaprovador)
    {
        $this->flaprovador = $flaprovador;
    }

    public function setFlespacoaberto($flespacoaberto)
    {
        $this->flespacoaberto = $flespacoaberto;
    }
    
    function createDao() {
        return new Aew_Model_Dao_UsuarioAmigo();
    }
    
    function exchangeArray($data) {
        parent::exchangeArray($data);
        $this->getUsuarioIndicou()->exchangeArray($data);
        $this->getUsuarioIndicou()->setId(isset($data['idusuarioindicou']) ? $data['idusuarioindicou']:null);
        $this->getUsuarioIndicou()->setNome(isset($data['nomeusuarioindicou']) ? $data['nomeusuarioindicou']:null);
        $this->getUsuarioIndicou()->setFlativo(isset($data['flativousuarioindicou']) ? $data['flativousuarioindicou']:null);
        $this->getUsuarioCriado()->exchangeArray($data);
    }
    
    /**
     * 
     * @param Aew_Model_Bo_Usuario $usuarioLogado
     * @return string
     */
    function getUrlAprovar(Aew_Model_Bo_Usuario $usuarioLogado)
    {
        if($usuarioLogado->isCoordenador())
        return "/administracao/amigo-da-escola/aprovar/id/".$this->getId();
    }
    
    function getUrlreprovar(Aew_Model_Bo_Usuario $usuarioLogado)
    {
        if($usuarioLogado->isCoordenador())
        return "/administracao/amigo-da-escola/reprovar/id/".$this->getId();
    }
    
    /**
     * 
     * @return int|boolean
     */
    function save() 
    {
        if($this->getUsuarioCriado()->save())
        {
            $this->setIdusuario($this->getUsuarioCriado()->getId());
            return parent::save();
        }
    }
}