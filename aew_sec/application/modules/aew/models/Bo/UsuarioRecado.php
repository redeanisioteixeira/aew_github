<?php
/**
 * BO da entidade UsuarioRecado
 */
class Aew_Model_Bo_UsuarioRecado extends Aew_Model_Bo_Comentario implements Sec_OrdenavelPorData
{
    protected $idusuariorecado;
    protected $dataenvio; //timestamp
    protected $tiporecado; //int(11)
    protected $visto; //tinyint(4)
    protected $idrecadorelacionado; //int(11)
    protected $usuarioDestinatario;
    
    
    function toArray()
    {
        $data = parent::toArray();
        if($this->getUsuarioDestinatario()->getId())
        {
            $data['idusuario'] = $this->getUsuarioDestinatario()->getId();
            $this->getDao()->setTableInTableField('idusuario', 'usuariorecado');
        }
        if($this->getUsuarioAutor()->getId())
        {
            $data['idusuarioautor'] = $this->getUsuarioAutor()->getId();
        }
        if($this->getComentario())
        {
            $data['recado'] = $this->getComentario();
        }
        if($this->getDataenvio())
        {
            $data['dataenvio'] = $this->getDataenvio();
        }
        if($this->getTiporecado())
        {
            $data['tiporecado'] = $this->getTiporecado();
        }
        return $data;
    }
    
    /**
     * @return int
     */
    function getIdusuariorecado() {
        return $this->idusuariorecado;
    }

    /**
     * @param int $idusuariorecado
     */
    function setIdusuariorecado($idusuariorecado) {
        $this->idusuariorecado = $idusuariorecado;
    }

    /***/
    public function exchangeArray($data)
    {
        parent::exchangeArray($data);
        $this->getUsuarioAutor()->exchangeArray($data);
        $this->getUsuarioAutor()->setId(isset($data['idusuarioautor'])?$data['idusuarioautor']: null);
        $this->getUsuarioDestinatario()->setId(isset($data['idusuario'])?$data['idusuario']:null) ; 
        $this->setComentario(isset($data['recado'])? $data['recado']: null);
        $this->setTiporecado(isset($data['tiporecado'])? $data['tiporecado']: null);
    }
    
    /**
     * Construtor
     */
    public function __construct()
    {
        parent::__construct();
        $this->setUsuarioDestinatario(new Aew_Model_Bo_Usuario());
    }
    
    /**
     * 
     * @return Aew_Model_Bo_Usuario usuario a quem se destina o recado
     */
    public function getUsuarioDestinatario() {
        return $this->usuarioDestinatario;
    }

    /**
     * @return string
     */
    public function getDataenvio() {
        return $this->dataenvio;
    }

    /**
     * 
     * @return int
     */
    public function getTiporecado() {
        return $this->tiporecado;
    }

    /**
     * 
     * @return boolean
     */
    public function getVisto() {
        return $this->visto;
    }

    /**
     * id do recado do qual originou este recado
     * @return int
     */
    public function getIdrecadorelacionado() {
        return $this->idrecadorelacionado;
    }

    /**
     * 
     * @param Aew_Model_Bo_Usuario $usuario usuario destinatario
     */
    public function setUsuarioDestinatario(Aew_Model_Bo_Usuario $usuario) {
        $this->usuarioDestinatario = $usuario;
    }

    /**
     * 
     * @param string $dataenvio
     */
    public function setDataenvio($dataenvio) {
        $this->dataenvio = $dataenvio;
    }

    /**
     * @param int $tiporecado
     */
    public function setTiporecado($tiporecado) {
        $this->tiporecado = $tiporecado;
    }

    /**
     * 
     * @param boolen $visto
     */
    public function setVisto($visto) {
        $this->visto = $visto;
    }

    /**
     * 
     * @param int $idrecadorelacionado
     */
    public function setIdrecadorelacionado($idrecadorelacionado) {
        $this->idrecadorelacionado = $idrecadorelacionado;
    }
    
    public function obtemNaoVistos($id)
    {
        $id = $this->getDao()->obtemNaoVistos($id);
        return $id;
    }

    public function obtemNaoVistosNum($id)
    {
        $id = $this->getDao()->obtemNaoVistosNum($id);
        return $id;
    }

    public function setaVistos($id)
    {
       $id = $this->getDao()->obtemPorUsuario($id, null);

    	for ($i = 0; $i < count($id); $i++)
        {
	    $objeto = $id[$i];
            $objeto['visto'] = true;
	    $this->save($objeto);
    	}

    }
    
    public function selectComentariosRelacionados($num=0,$offset = 0, Aew_Model_Bo_Usuario $usuarioAutor=null) 
    {
        if(!$this->getId())
            return false;
        
        $recado = new Aew_Model_Bo_UsuarioRecado();
        if($usuarioAutor)
            $recado->setUsuarioAutor($usuarioAutor);
        
        $recado->setIdrecadorelacionado($this->getId());
        $this->setComentariosRelacionados($recado->select($num,$offset));
        return $this->getComentariosRelacionados();
    }
    
    /**
     * retorna a url para apagar um recado
     * @param Aew_Model_Bo_UsuarioRecado $recado
     * @return string
     */
    public function getUrlApagar(Aew_Model_Bo_ItemPerfil $usuario=null)
    {
        if(($usuario->getId()==$this->getUsuarioDestinatario()->getId())||
          ($usuario->getId()==$this->getUsuarioAutor()->getId())||     
          ($usuario->getUsuarioTipo()->getNome()==Aew_Model_Bo_UsuarioTipo::SUPER_ADMINISTRADOR))
        return '/espaco-aberto/recado/apagar/usuario/'.$usuario->getId().'/id/'.$this->getId();
    }
    
    /**
     * retorna a url para responder um recado
     * @param Aew_Model_Bo_UsuarioRecado $recado
     * @return string
     */
    public function getUrlResponder(Aew_Model_Bo_ItemPerfil $usuario = null)
    {
        if($usuario->getId() == $this->getUsuarioDestinatario()->getId() ||
            $usuario->getId() == $this->getUsuarioAutor()->getId())
        return '/espaco-aberto/recado/enviar-resposta/id/'.$this->getId();
    }

    /**
     * 
     * @param int $id
     */
    public function setId($id) {
        $this->idusuariorecado = $id;
    }
    
    /**
     * @return string
     */
    public function getData()
    {
        return $this->getDataenvio();
    }

    /**
     * @return \Aew_Model_Dao_UsuarioRecado
     */
    protected function createDao() {
        return new Aew_Model_Dao_UsuarioRecado();
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->idusuariorecado;
    }

}