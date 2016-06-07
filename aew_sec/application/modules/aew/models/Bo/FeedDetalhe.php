<?php
/**
* BO da entidade FeedDetalhe
*/
class Aew_Model_Bo_FeedDetalhe extends Sec_Model_Bo_Abstract
{
    const STATUS_ATIVO = 1;
    const STATUS_INATIVO = 0;
    const STATUS_BLOQUEADO = 2;

    protected $id; //bigint(20) unsigned
    protected $usuarioremetente; //int(11)
    protected $idusuariodestinatario; //int(11)
    protected $idfeedtabela; //int(11)
    protected $idfeedmensagem; //int(11)
    protected $idregistrotabela; //int(11)
    protected $valorantigo; //varchar(150)
    protected $valornovo; //varchar(150)
    protected $datacriacao; //datetime
    protected $idcomunidade; //int(11)
    protected $tipo;
    protected $idfoto;
    protected $descricao;
    protected $mensagem;
    protected $nome_dono;
    protected $nome_comunidade;
    protected $nome;
    protected $idusuario;
    
    public static   $COLEGA_TIPO = "colega",
                    $COLEGA_BLOG_TIPO="colega-blog",
                    $COMUNIDADE_TIPO = "comunidade",
                    $FORUNS_COMUNIDADE_TIPO="comunidade-forum",
                    $COMUNIDADE_BLOG_TIPO='comunidade-blog',
                    $CONTEUDO_DIGITAL_TIPO='conteudo-digital',
                    $AMBIENTE_DE_APOIO='ambiente-apoio';

    /**
     * 
     * @return int
     */
    function getIdusuario() {
        return $this->idusuario;
    }

    function setIdusuario($idusuario) {
        $this->idusuario = $idusuario;
    }

          
    public function __construct() 
    {
        $this->setUsuarioremetente(new Aew_Model_Bo_Usuario());
    }
    
    function getNome_dono() {
        return $this->nome_dono;
    }

    function getNome_comunidade() {
        return $this->nome_comunidade;
    }

    function getNome() {
        return $this->nome;
    }

    function setNome_dono($nome_dono) {
        $this->nome_dono = $nome_dono;
    }

    function setNome_comunidade($nome_comunidade) {
        $this->nome_comunidade = $nome_comunidade;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

        
    public function getNomeComunidade()
    {
        return $this->nome_comunidade;
    }

    public function setNomeComunidade($nome_comunidade)
    {
        $this->nome_comunidade = $nome_comunidade;
    }

    public function getIdfoto()
    {
        return $this->idfoto;
    }

    public function setIdfoto($idfoto)
    {
        $this->idfoto = $idfoto;
    }

    public function getNomeDono()
    {
        return $this->nome_dono;
    }

    public function setNomeDono($nome_dono)
    {
        $this->nome_dono = $nome_dono;
    }

    public function getDescricao()
    {
        return $this->descricao;
    }

    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    function obtemResultado($filtro,$num=0,$offset=0)
    {
        return $this->getDao()->obtemResultado($filtro,$num,$offset);
    }
    /***/
    public function exchangeArray($data)
    {
        parent::exchangeArray($data);
        $this->getUsuarioremetente()->exchangeArray($data);
    }

    public function getTipo()
    {
        return $this->tipo;
    }

    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    public function getMensagem()
    {
        return $this->mensagem;
    }

    public function setMensagem($mensagem)
    {
        $this->mensagem = $mensagem;
    }

    /**
     * @return Aew_Model_Bo_Usuario
     */
    public function getUsuarioremetente(){
    	return $this->usuarioremetente;
    }

    public function getUrlFoto()
    {
        $url = '';
        switch ($this->getTipo()):
            case Aew_Model_Bo_FeedDetalhe::$COLEGA_TIPO:
                    $url = '/conteudos/fotos-perfil/usuario/'.$this->getIdfoto();
                    break;
                
            case Aew_Model_Bo_FeedDetalhe::$COMUNIDADE_TIPO: 
                    $url = '/conteudos/fotos-perfil/comunidade/'.$this->getIdfoto();
                    break;
                
            case Aew_Model_Bo_FeedDetalhe::$COMUNIDADE_BLOG_TIPO: 
                    $url = '/conteudos/fotos-perfil/comunidade-blog/padrao.png';   
                    break;
                
            case Aew_Model_Bo_FeedDetalhe::$COLEGA_BLOG_TIPO: 
                    $url = '/conteudos/fotos-perfil/colega-blog/padrao.png';
                    break;
                
            case Aew_Model_Bo_FeedDetalhe::$CONTEUDO_DIGITAL_TIPO: 
                    $conteudoDigital = new Aew_Model_Bo_ConteudoDigital();
                
                    $conteudoDigital->setId($this->getId());
                    $conteudoDigital->selectAutoDados();
                    $url = $conteudoDigital->getConteudoImagem();
                    break;
                
            case Aew_Model_Bo_FeedDetalhe::$AMBIENTE_DE_APOIO: 
                    $ambienteDeApoio = new Aew_Model_Bo_AmbienteDeApoio();
                
                    $ambienteDeApoio->setId($this->getId());
                    $ambienteDeApoio->selectAutoDados();
                    
                    $url = $ambienteDeApoio->getImagemAssociadaUrl().DS.$ambienteDeApoio->getId().".png";
                    break;
                
        endswitch;
        
        return $url;
    }
    
    function getUrlPerfil()
    {
        $url = '';
        switch ($this->getTipo())
        {
            case Aew_Model_Bo_FeedDetalhe::$COLEGA_TIPO : 
                 $usuario = new Aew_Model_Bo_Usuario();
                 $usuario->setId($this->getId());
                 $url = $usuario->getLinkPerfil();
                 break;
             
            case Aew_Model_Bo_FeedDetalhe::$COMUNIDADE_TIPO : 
                 $comunidade = new Aew_Model_Bo_Comunidade();
                 $comunidade->setId($this->getId());
                 $url = $comunidade->getLinkPerfil();
                 break;
             
            case Aew_Model_Bo_FeedDetalhe::$COMUNIDADE_BLOG_TIPO : 
                 $url = '/espaco-aberto/blog/exibir/usuario/'.$this->getIdcomunidade().'/id/'.$this->getId(); break;   
                 break;
             
            case Aew_Model_Bo_FeedDetalhe::$COLEGA_BLOG_TIPO: 
                 $url = '/espaco-aberto/blog/exibir/usuario/'.$this->getIdusuario().'/id/'.$this->getId(); break;
             
            case Aew_Model_Bo_FeedDetalhe::$CONTEUDO_DIGITAL_TIPO : 
                 $conteudoDigital = new Aew_Model_Bo_ConteudoDigital();
                 $conteudoDigital->setId($this->getId());
                 $url = $conteudoDigital->getLinkPerfil(); 
                 break;
        }
        return $url;
    }
    /**
     * @return idusuariodestinatario - int(11)
     */
    public function getIdusuariodestinatario(){
    	return $this->idusuariodestinatario;
    }

    /**
     * @return idfeedtabela - int(11)
     */
    public function getIdfeedtabela(){
    	return $this->idfeedtabela;
    }
    
    /**
     * @return idfeedmensagem - int(11)
     */
    public function getIdfeedmensagem(){
    	return $this->idfeedmensagem;
    }

    /**
     * @return idregistrotabela - int(11)
     */
    public function getIdregistrotabela(){
    	return $this->idregistrotabela;
    }

    /**
     * @return valorantigo - varchar(150)
     */
    public function getValorantigo(){
    	return $this->valorantigo;
    }
    
    /**
     * @return valornovo - varchar(150)
     */
    public function getValornovo(){
    	return $this->valornovo;
    }

    /**
     * @return datacriacao - datetime
     */
    public function getDatacriacao(){
    	return $this->datacriacao;
    }

    /**
     * @return idcomunidade - int(11)
     */
    public function getIdcomunidade(){
	return $this->idcomunidade;
    }

    /**
     * @param Type: bigint(20) unsigned
     */
    public function setId($id){
    	$this->id = $id;
    }

    /**
     * @param Type: int(11)
     */
    public function setUsuarioremetente(Aew_Model_Bo_Usuario $usuarioremetente){
	$this->usuarioremetente = $usuarioremetente;
    }

    /**
     * @param Type: int(11)
     */
    public function setIdusuariodestinatario($idusuariodestinatario){
    	$this->idusuariodestinatario = $idusuariodestinatario;
    }

    /**
     * @param Type: int(11)
     */
    public function setIdfeedtabela($idfeedtabela){
	$this->idfeedtabela = $idfeedtabela;
    }

    /**
     * @param Type: int(11)
     */
    public function setIdfeedmensagem($idfeedmensagem){
    	$this->idfeedmensagem = $idfeedmensagem;
    }

    /**
     * @param Type: int(11)
     */
    public function setIdregistrotabela($idregistrotabela){
	$this->idregistrotabela = $idregistrotabela;
    }

    /**
     * @param Type: varchar(150)
     */
    public function setValorantigo($valorantigo){
    	$this->valorantigo = $valorantigo;
    }

    /**
     * @param Type: varchar(150)
     */
    public function setValornovo($valornovo){
	$this->valornovo = $valornovo;
    }

    /**
     * @param Type: datetime
     */
    public function setDatacriacao($datacriacao){
	$this->datacriacao = $datacriacao;
    }

    /**
     * @param Type: int(11)
     */
    public function setIdcomunidade($idcomunidade)
    {
	$this->idcomunidade = $idcomunidade;
    }
    
    public function selectFeedsEspacoAberto($num=0,$offset=0,$idfeed_min=0,$idfeed_max=0)
    {
        return $this->getDao()->selectFeedEspacoAberto( $this->toArray(),$num,$offset,$idfeed_min,$idfeed_max);
    }

    protected function createDao() {
        return new Aew_Model_Dao_FeedDetalhe();
    }

}