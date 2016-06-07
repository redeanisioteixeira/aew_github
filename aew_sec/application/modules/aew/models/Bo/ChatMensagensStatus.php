<?php

/**
 * Classe das mensagens trocadas no chat entre usuarios
 */
class Aew_Model_Bo_ChatMensagensStatus extends Sec_Model_Bo_Abstract
{
    const STATUS_ATIVO = 1;
    const STATUS_INATIVO = 0;
    const STATUS_BLOQUEADO = 2;
    
    protected $id; //int(11)
    protected $id_de; //int(11)
    protected $id_para; //int(11)
    protected $usuarioContato;//Aew_Model_Bo_Usuario;
    protected $flavisar; //tinyint(1)
    protected $flbloquear; //tinyint(1)
    
    /**
     * Construtor
    */
    public function __construct()
    {
        parent::__construct();
        $this->setUsuarioContato(new Aew_Model_Bo_Usuario());
    }
   
    function toArray()
    {
        $data = parent::toArray();
        if($this->getUsuarioContato()->getId())
        {
             $data['id_para'] = $this->getUsuarioContato()->getId();
        }
        return $data;
    }

    /***/
    public function exchangeArray($data){
        parent::exchangeArray($data);
        $this->getUsuarioContato()->exchangeArray($data);
    }

    /**
     * @return id_de - int(11)
     */
    public function getIdDe(){
    	return $this->id_de;
    }
    
    /**
     * id do usuario destinatario da mensagem
     * @return int
     */
    function getId_para() {
        return $this->id_para;
    }

    /**
     * 
     * @param int $id_para
     */
    function setId_para($id_para) {
        $this->id_para = $id_para;
    }

        /**
     * @return flavisar - tinyint(1)
     */
    public function getFlavisar(){
    	return $this->flavisar;
    }

    /**
     * @return flbloquear - tinyint(1)
     */
    public function getFlbloquear(){
    	return $this->flbloquear;
    }

    /**
     * @param int $idDe 
     */
    public function setIdDe($idDe){
	$this->id_de = $idDe;
    }

    /**
     * @param Type: tinyint(1)
     */
    public function setFlavisar($flavisar){
    	$this->flavisar = $flavisar;
    }

    /**
     * @param Type: tinyint(1)
     */
    public function setFlbloquear($flbloquear){
	$this->flbloquear = $flbloquear;
    }
    
    /**
     * @return Aew_Model_Bo_Usuario
     */
    public function getUsuarioContato()
    {
        return $this->usuarioContato;
    }

    /**
     * @param Aew_Model_Bo_Usuario $usuarioContato
     */
    public function setUsuarioContato(Aew_Model_Bo_Usuario $usuarioContato)
    {
        $this->usuarioContato = $usuarioContato;
    }

    /**
     * cria objeto de acesso ao banco de dados
     * @return \Aew_Model_Dao_ChatMensagensStatus
     */
    protected function createDao() {
        return new Aew_Model_Dao_ChatMensagensStatus();
    }
}