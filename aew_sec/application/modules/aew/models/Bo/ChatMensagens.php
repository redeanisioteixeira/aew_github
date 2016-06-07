<?php

/**
 * BO da entidade chatmensagens
 */
class Aew_Model_Bo_ChatMensagens extends Sec_Model_Bo_Abstract {

    const STATUS_ATIVO = 1;
    const STATUS_INATIVO = 0;
    const STATUS_BLOQUEADO = 2;

    protected $usuarioReceptor; //int(11)
    protected $usuarioEscritorMensagem; //int(11)
    protected $mensagem; //text
    protected $data; //timestamp
    protected $lido; //tinyint(1)
    protected $bloqueado;
    protected $online;
    protected $status;
    protected $tempo_minuto;
    protected $tempo_segundo;
    protected $id_de;
    protected $id_para;
    protected $alerta;
    protected $iddispositivo;
    protected $nomedispositivo;
    
    /**
     * 
     * @return int
     */
    function getIddispositivo() {
        return $this->iddispositivo;
    }

    /**
     * 
     * @return string
     */
    function getNomedispositivo() {
        return $this->nomedispositivo;
    }

    /**
     * 
     * @param int $iddispositivo
     */
    function setIddispositivo($iddispositivo) {
        $this->iddispositivo = $iddispositivo;
    }

    /**
     * 
     * @param string $nomedispositivo
     */
    function setNomedispositivo($nomedispositivo) {
        $this->nomedispositivo = $nomedispositivo;
    }

    /**
     * @return string
     */
    function getTempoSegundo() {
        return $this->tempo_segundo;
    }

    /**
     * 
     * @param type $tempoSegundo
     */
    function setTempoSegundo($tempoSegundo) {
        $this->tempo_segundo = $tempoSegundo;
    }

        
    /**
     * 
     * @return int
     */
    function getAlerta() {
        return $this->alerta;
    }

    /**
     * 
     * @param int $alerta
     */
    function setAlerta($alerta) {
        $this->alerta = $alerta;
    }

    /**
     * id so usuario que enviou a mensagem
     * @return int
     */    
    function getId_de() {
        return $this->id_de;
    }

    /**
     * id do usuario receptor da mensagem
     * @return int
     */
    function getId_para() {
        return $this->id_para;
    }

    /**
     * 
     * @param int $id_de
     */
    function setId_de($id_de) {
        $this->id_de = $id_de;
    }

    /**
     * 
     * @param int $id_para
     */
    function setId_para($id_para) {
        $this->id_para = $id_para;
    }


    /**
     * minuto ao qual mensagem foi enviada
     * @return string
     */
    function getTempoMinuto() {
        return $this->tempo_minuto;
    }

    /**
     * 
     * @param string $tempoMinuto
     */
    function setTempoMinuto($tempoMinuto) {
        $this->tempo_minuto = $tempoMinuto;
    }

    /**
     * 
     * @return int
     */
    function getStatus() {
        return $this->status;
    }

    /**
     * @param int $status
     */
    function setStatus($status) {
        $this->status = $status;
    }

    /**
     * @return boolean
     */
    function getBloqueado() {
        return $this->bloqueado;
    }

    /**
     * @param boolean $bloqueado
     */
    function setBloqueado($bloqueado) {
        $this->bloqueado = $bloqueado;
    }

    
    function getUsuarioReceptor() {
        return $this->usuarioReceptor;
    }

    function setUsuarioReceptor(Aew_Model_Bo_Usuario $usuarioReceptor) {
        $this->usuarioReceptor = $usuarioReceptor;
    }

    function toArray() {
        $data = parent::toArray();
        if($this->getUsuarioEscritorMensagem()->getId())
        {
            $data['id_de'] = $this->getUsuarioEscritorMensagem()->getId();
        }
        if($this->getUsuarioReceptor()->getId())
        {
            $data['id_para'] = $this->getUsuarioReceptor()->getId();
        }
        return $data;
    }

    /**
     * Construtor
     */
    public function __construct() 
    {
        parent::__construct();
        $this->setUsuarioEscritorMensagem(new Aew_Model_Bo_Usuario());
        $this->setUsuarioReceptor(new Aew_Model_Bo_Usuario());
    }

    function obtemUsuariosChat($usuarioid, $filtro, $status = false)
    {
        return $this->getDao()->obtemUsuariosChat($usuarioid, $filtro, $status);
    }
    /**
     * @return Aew_Model_Bo_Usuario
     */
    public function getColegaChat() {
        return $this->colegaChat;
    }

    /**
     * @return Aew_Model_Bo_Usuario
     */
    public function getUsuarioEscritorMensagem() {
        return $this->usuarioEscritorMensagem;
    }

    /**
     * @param Aew_Model_Bo_Usuario $usuarioEscritorMensagem
     */
    public function setUsuarioEscritorMensagem(Aew_Model_Bo_Usuario $usuarioEscritorMensagem) {
        $this->usuarioEscritorMensagem = $usuarioEscritorMensagem;
    }

    /*     * */

    public function exchangeArray($data) 
    {
        parent::exchangeArray($data);
        $this->getUsuarioEscritorMensagem()->exchangeArray($data);
        $this->getUsuarioReceptor()->exchangeArray($data);
        
    }

    /**
     * @return mensagem - text
     */
    public function getMensagem() {
        return $this->mensagem;
    }

    /**
     * @return data - timestamp
     */
    public function getData() {
        return $this->data;
    }

    /**
     * @return lido - tinyint(1)
     */
    public function getLido() {
        return $this->lido;
    }

    /**
     * @param Type: text
     */
    public function setMensagem($mensagem) {
        $this->mensagem = $mensagem;
    }

    /**
     * @param Type: timestamp
     */
    public function setData($data) {
        $this->data = $data;
    }

    /**
     * @param Type: tinyint(1)
     */
    public function setLido($lido) {
        $this->lido = $lido;
    }
    
    function obtemMensagemChat($chat, $id, $idm)
    {
        return $this->getDao()->obtemMensagemChat($chat, $id, $idm);
    }
    
    function getOnline() {
        return $this->online;
    }

    function setOnline($online) {
        $this->online = $online;
    }

    function obtemAlertaMensagem($id, $usuario)
    {
        return $this->getDao()->obtemAlertaMensagem($id, $usuario);
    }

    protected function createDao() {
        return new Aew_Model_Dao_ChatMensagens();
    }

}