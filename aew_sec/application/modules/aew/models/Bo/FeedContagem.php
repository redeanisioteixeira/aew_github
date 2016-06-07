<?php
/**
 * BO da entidade feedcontagem
 */
class Aew_Model_Bo_FeedContagem extends Sec_Model_Bo_Abstract
{
    const STATUS_ATIVO = 1;
    const STATUS_INATIVO = 0;
    const STATUS_BLOQUEADO = 2;
    
    protected $idfeedcontagem; //int(11)
    protected $idusuario; //int(11)
    protected $qtd_feed_recados; //int(11)
    protected $qtd_feed_colegas; //int(11)
    protected $qtd_feed_comunidades; //int(11)
    protected $qtd_feed_albuns; //int(11)
    protected $qtd_feed_agenda; //int(11)
    protected $qtd_feed_blog; //int(11)
    protected $datacriacao; //timestamp
    protected $flacesso; //tinyint(1)
    protected $dataacesso; //timestamp
    protected $flchatativo; //tinyint(1)
    protected $iddispositivo; //int(11)
    
    /**
     * 
     * @return int
     */
    function getIddispositivo() {
        return $this->iddispositivo;
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
     * @return int
     */
    public function getIdfeedcontagem() {
        return $this->idfeedcontagem;
    }

    /**
     * 
     * @return int
     */
    public function getIdusuario() {
        return $this->idusuario;
    }

    /**
     * 
     * @return int
     */
    public function getQtdFeedRecados() {
        return $this->qtd_feed_recados;
    }

    /**
     * 
     * @return int
     */
    public function getQtdFeedColegas() {
        return $this->qtd_feed_colegas;
    }

    /**
     * 
     * @return int
     */
    public function getQtdFeedComunidades() {
        return $this->qtd_feed_comunidades;
    }

    /**
     * 
     * @return int
     */
    public function getQtdFeedAlbuns() {
        return $this->qtd_feed_albuns;
    }

    /**
     * 
     * @return int
     */
    public function getQtdFeedAgenda() {
        return $this->qtd_feed_agenda;
    }

    /**
     * 
     * @return int
     */
    public function getQtdFeedBlog() {
        return $this->qtd_feed_blog;
    }

    /**
     * 
     * @return string
     */
    public function getDatacriacao() {
        return $this->datacriacao;
    }

    /**
     * 
     * @return boolean
     */
    public function getFlacesso() {
        return $this->flacesso;
    }

    /**
     * 
     * @return string
     */
    public function getDataacesso() {
        return $this->dataacesso;
    }

    /**
     * 
     * @return boolean
     */
    public function getFlchatativo() {
        return $this->flchatativo;
    }

    /**
     * 
     * @param int $idfeedcontagem
     */
    public function setIdfeedcontagem($idfeedcontagem) {
        $this->idfeedcontagem = $idfeedcontagem;
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
     * @param int $qtdFeedRecados
     */
    public function setQtdFeedRecados($qtdFeedRecados) {
        $this->qtd_feed_recados = $qtdFeedRecados;
    }

    /**
     * 
     * @param int $qtdFeedColegas
     */
    public function setQtdFeedColegas($qtdFeedColegas) {
        $this->qtd_feed_colegas = $qtdFeedColegas;
    }

    /**
     * 
     * @param int $qtdFeedComunidades
     */
    public function setQtdFeedComunidades($qtdFeedComunidades) {
        $this->qtd_feed_comunidades = $qtdFeedComunidades;
    }

    /**
     * 
     * @param int $qtdFeedAlbuns
     */
    public function setQtdFeedAlbuns($qtdFeedAlbuns) {
        $this->qtd_feed_albuns = $qtdFeedAlbuns;
    }

    /**
     * 
     * @param int $qtdFeedAgenda
     */
    public function setQtdFeedAgenda($qtdFeedAgenda) {
        $this->qtd_feed_agenda = $qtdFeedAgenda;
    }

    /**
     * 
     * @param int $qtdFeedBlog
     */
    public function setQtdFeedBlog($qtdFeedBlog) {
        $this->qtd_feed_blog = $qtdFeedBlog;
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
     * @param boolean $flacesso
     */
    public function setFlacesso($flacesso) {
        $this->flacesso = $flacesso;
    }

    /**
     * 
     * @param string $dataacesso
     */
    public function setDataacesso($dataacesso) {
        $this->dataacesso = $dataacesso;
    }

    /**
     * 
     * @param boolean $flchatativo
     */
    public function setFlchatativo($flchatativo) {
        $this->flchatativo = $flchatativo;
    }

    /**
     * seleciona no banco de dados usuarios online
     * @return array
     */
    public function obtemUsuariosOnline()
    {
	$online = $this->getDao()->obtemUsuariosOnline();
	return $online;
    }
    /**
     * status do usuario 
     * @param int $id
     * @return array
     */
    public function ChatStatus($id) 
    {
	$resultado = $this->getDao()->obtemPorUsuario($id);
		return $resultado;
    }

    /**
     * @param int $id
     * @return int
     */
    public function limparContagem($id)
    {
        switch (intval($id)):
            case 1:
                $this->setQtdFeedRecados('0');
                break;
            
            case 2:
                $this->setQtdFeedColegas(0); 
                break;
            
            case 3:
                $this->setQtdFeedComunidades('0');
                break;
            
            case 4:
                $this->setQtdFeedAlbuns('0');
                break;
            
            case 5:
                $this->setQtdFeedAgenda('0');
                break;
            
            case 6:
                $this->setQtdFeedBlog('0');
                break;
        endswitch;
        
        return $this->save();
    }

    /**
     * cria objeto de acesso ao banco de dados
     * @return \Aew_Model_Dao_FeedContagem
     */
    protected function createDao() {
        $dao =  new Aew_Model_Dao_FeedContagem();
        return $dao;
    }
}
