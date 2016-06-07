<?php
class Aew_Model_Bo_ConteudoDigitalCategoria extends Aew_Model_Bo_ItemPerfil{

    protected $nomeconteudodigitalcategoria; //varchar(500)
    protected $idconteudodigitalcategoriapai; //int(11)
    protected $flativo; //tinyint(1)
    protected $datacriacao; //datetime
    protected $subcategoriaConteudoDigital = array();
    protected $conteudosDigitais = array();
    protected $iconUrl;
    protected $descricaoconteudodigitalcategoria ;
    protected $subCategorias = array();
    protected $comentarios = array();
    protected $acessos;
    protected $avaliacao;
    protected $fldestaque;
    protected $canal;
    protected $qtddownloads;
            
    /**
     * retorna os parametro do objeto em um array
     * onde a chave e o nome da variavel de instancia e tambem
     * o nome do campo na tabela
     * @return array
     */
    function toArray() {
        $data = parent::toArray();
        if($this->getCanal()->getId())
        {
            $data['idcanal'] = $this->getCanal()->getId();
        }
        return $data;
    }
    
    /**
     * construtor
     */
    function __construct() 
    {
        $this->setCanal(new Aew_Model_Bo_Canal());
    }
    /**
     * 
     * @return Aew_Model_Bo_Canal
     */
    function getCanal() {
        return $this->canal;
    }

    /**
     * 
     * @param Aew_Model_Bo_Canal $canal
     */
    function setCanal(Aew_Model_Bo_Canal $canal) {
        $this->canal = $canal;
    }

    /**
     * 
     * @return boolean
     */
    function getDestaque() {
        return $this->fldestaque;
    }

    /**
     * 
     * @param boolean $fldestaque
     */
    function setDestaque($fldestaque) {
        $this->fldestaque = $fldestaque;
    }
        
    /**
     * conteudos digitais pertencentes a esta categoria
     * @return array
     */
    public function getConteudosDigitais()
    {
        return $this->conteudosDigitais;
    }

    /**
     * 
     * @param array $conteudosDigitais
     */
    public function setConteudosDigitais($conteudosDigitais)
    {
        $this->conteudosDigitais = $conteudosDigitais;
    }
        
    /**
     * 
     * @return array
     */
    public function getSubcategoriaConteudoDigital()
    {
        return $this->subcategoriaConteudoDigital;
    }

    /**
     * 
     * @return string
     */
    public function getDescricao()
    {
        return $this->descricaoconteudodigitalcategoria;
    }

    /**
     * 
     * @param type $subcategoriaConteudoDigital
     */
    public function setSubcategoriaConteudoDigital($subcategoriaConteudoDigital)
    {
        $this->subcategoriaConteudoDigital = $subcategoriaConteudoDigital;
    }

    /**
     * 
     * @param string $descricao
     */
    public function setDescricao($descricao)
    {
        $this->descricaoconteudodigitalcategoria = $descricao;
    }

    /**
     * @return idconteudodigitalcategoria - int(11)
     */
    public function getIdconteudodigitalcategoria(){
	return $this->idconteudodigitalcategoria;
    }

    /**
     * 
     * @return string
     */
    public function getTitulo()
    {
        return $this->nomeconteudodigitalcategoria;
    }

    /**
     * @return nomeconteudodigitalcategoria - varchar(500)
     */
    public function getNome(){
	return $this->nomeconteudodigitalcategoria;
    }

    /**
     * @return idconteudodigitalcategoriapai - int(11)
     */
    public function getIdconteudodigitalcategoriapai(){
	return $this->idconteudodigitalcategoriapai;
    }

    /**
     * @return flativo - tinyint(1)
     */
    public function getFlativo(){
	return $this->flativo;
    }

    /**
     * @return datacriacao - datetime
     */
    public function getDatacriacao(){
	return $this->datacriacao;
    }

    /**
     * @param Type: int(11)
     */
    public function setIdconteudodigitalcategoria($idconteudodigitalcategoria){
	$this->idconteudodigitalcategoria = $idconteudodigitalcategoria;
    }

    /**
     * @param Type: varchar(500)
     */
    public function setNome($nome){
    	$this->nomeconteudodigitalcategoria = $nome;
    }

    /**
     * @param Type: int(11)
     */
    public function setIdconteudodigitalcategoriapai($idconteudodigitalcategoriapai){
	$this->idconteudodigitalcategoriapai = $idconteudodigitalcategoriapai;
    }

    /**
     * @param Type: tinyint(1)
     */
    public function setFlativo($flativo){
	$this->flativo = $flativo;
    }

    /**
     * @param Type: datetime
     */
    public function setDatacriacao($datacriacao)
    {
        $this->datacriacao = $datacriacao;
    }
       
    /**
     * 
     * @param Sec_Form $form
     * @return boolean
     */
    public function uploadIcon(Sec_Form $form)
    {
        return $this->upload($form->icone, $this->getIconeDirectory());
    }

    /**
     * 
     * @param Sec_Form $form
     * @param boolean $apagar
     * @return boolean
     */
    public function uploadVideo(Sec_Form $form, $apagar = false)
    {
        if($apagar)
            $resultado = unlink($this->getIconeDirectory().DS.'video-destaque'.DS.$this->getId().'.webm');
        else
            $resultado =  $this->upload($form->video, $this->getIconeDirectory().DS.'video-destaque', $apagar);
        
        return $resultado;
    }
    
    /**
     * @return string
     */
    static  function getIconeDirectory()
    {
        if(CONTEUDO_PATH):
            $path = MEDIA_PATH.DS.CONTEUDO_PATH.DS.'conteudos-digitais'.DS.'imagem-associada'.DS.'categoria';
        ELSE:
            $path = MEDIA_PATH.DS.'conteudos'.DS.'imagem-associada'.DS.'categoria';
        endif;
        return $path;
    }
    
    public function getLinkEpisodios()
    {
        return '/tv-anisio-teixeira/programas/episodios/id/'.$this->getId();
    }
    
    /**
     * seleciona conteudos digitais do banco de dados desta categoria
     * @param int $num
     * @param int $offset
     * @param Aew_Model_Bo_ConteudoDigital $conteudoDigital
     * @param array $options
     * @return array
     */
    function selectConteudosDigitais($num = 0,$offset = 0, Aew_Model_Bo_ConteudoDigital $conteudoDigital = null,array $options=null)
    {
        if(!$conteudoDigital)
            $conteudoDigital = new Aew_Model_Bo_ConteudoDigital();
        
        $conteudoDigital->setConteudoDigitalCategoria($this);
        foreach($conteudoDigital->select($num, $offset, $options, true) as $conteudo)
        {
            array_push($this->conteudosDigitais, $conteudo);
        }
        return $this->getConteudosDigitais();
    }
    
    /**
     * 
     * @return type
     */
    function getAcessos()
    {
        return $this->acessos;
    }
    
    /**
     * 
     * @return int
     */
    function getQtddownloads()
    {
        return $this->qtddownloads;
    }
    
    /**
     * 
     * @return array
     */
    function getComentarios()
    {
        return $this->comentarios;
    }
    
    /**
     * 
     * @return int
     */
    function getNumComentarios()
    {
        $numComentarios = 0;
        foreach($this->getConteudosDigitais() as $conteudo)
        {
            $numComentarios+= count($conteudo->getComentarios());
        }
        return $numComentarios;
    }
    
    /**
     * 
     * @return string
     */
    public function getImagemVideoUrl()
    {
        $pathVideo = '';
	if(file_exists($this->getImagemAssociadaDirectory().DS.'video-destaque'.DS.$this->getId().".webm")):
            $pathVideo = $this->getImagemAssociadaUrl().DS.'video-destaque'.DS.$this->getId().".webm";;
	endif;
	return $pathVideo;
    }
    
    /**
     * preenche o objeto com dados de uma array (de mapeamento chave-valor)
     * @param array $data
     */
    function exchangeArray($data) {
        parent::exchangeArray($data);
        $this->getCanal()->exchangeArray($data);
    }
            
    /**
     * seleciona no banco de dados subcategoria desta categoria
     * @param type $num
     * @param type $offset
     * @param Aew_Model_Bo_ConteudoDigitalCategoria $categoria
     * @param array $options
     * @return array
     */
    function selectSubCategorias($num=0,$offset = 0,  Aew_Model_Bo_ConteudoDigitalCategoria $categoria = null,array $options = null)
    {
        if(!$categoria)
        $categoria = new Aew_Model_Bo_ConteudoDigitalCategoria();
        $categoria->setIdconteudodigitalcategoriapai($this->getId());
        $subcategorias = $categoria->select($num, $offset, $options);
        $sc = array();
        foreach ($subcategorias as $subcategoria) 
        {
            array_push($sc, $subcategoria);
        }
        $this->setSubCategorias($sc);
        return $this->getSubCategorias();
    }
    
    /**
     *
     * @return array
     */
    function getSubCategorias() {
        return $this->subCategorias;
    }

    /**
     * 
     * @param array $subCategorias
     */
    function setSubCategorias($subCategorias) {
        $this->subCategorias = $subCategorias;
    }

    /**
     * 
     * @param  string $baseUrl
     * @return string
     */
    public function getConteudoCategoria($baseUrl = "")
    {
        $baseUrl = ( $baseUrl != "" ? addslashes(htmlentities(strip_tags($baseUrl))) : "");
        return $baseUrl.'/tv-anisio-teixeira/programas/episodios/id/'.$this['idConteudoDigitalCategoria'];
    }

    /**
     * Retorna a URL para a imagem associada
     * @return string
     */
    public function getConteudoImagem()
    {
	$id = $this->getId();
	$imagem = "";
	$extensoesValidas = array("png", "gif", "jpg");
        
	//--- Verifica se possui imagem associada				
	foreach($extensoesValidas as $key => $value):
            $image_path = Aew_Model_Bo_ConteudoDigital::getImagemAssociadaDirectory()."/categoria/".$id.".".$value;
            if(file_exists($image_path)):
		$imagem = Aew_Model_Bo_ConteudoDigital::getImagemAssociadaUrl()."/categoria/$id.$value";
		break;
            endif;
	endforeach;
	if($imagem == ""):
            //--- Seleciona imagem padrão
            $imagem = "/assets/img/icones/icone-video.png";
	endif;
	return $imagem;
    }
    
    /**
     * 
     * @return string
     */
    function getLinkPerfil() {
        return '/tv-anisio-teixeira/programas/episodios/id/'.$this->getId();
    }

    /**
     * Retorna o diretorio para as imagens associadas aos conteúdos
     * @return string
     */
    public static function getImagemAssociadaDirectory()
    {
        if(CONTEUDO_PATH):
            $path = MEDIA_PATH.DS.CONTEUDO_PATH.DS.'conteudos-digitais'.DS.'imagem-associada'.DS.'categoria';
        else:
            $path = MEDIA_PATH.DS.'conteudos'.DS.'conteudos-digitais'.DS.'imagem-associada'.DS.'categoria';
        endif;
        return  $path;
    }

    /**
     * Retorna o diretorio para as imagens associadas aos conteúdos
     * @return string
     */
    public static function getImagemAssociadaUrl()
    {
        if(CONTEUDO_PATH):
            $path = DS.CONTEUDO_PATH.DS.'conteudos-digitais'.DS.'imagem-associada'.DS.'categoria';
        else:
            $path = DS.'conteudos'.DS.'conteudos-digitais'.DS.'imagem-associada'.DS.'categoria';
        endif;
        return  $path;
    }
    
    /**
     * nao implementado
     * @param \Aew_Model_Bo_ItemPerfil $avaliador
     * @param type $voto
     */
    public function insertVoto(\Aew_Model_Bo_ItemPerfil $avaliador, $voto) {
        
    }

    /**
     * nao implementado
     */
    public function perfilTipo() {
        
    }

    /**
     * 
     * @param Aew_Model_Bo_Foto $foto
     */
    public function saveFotoPerfil(Aew_Model_Bo_Foto $foto) {
        
    }

    /**
     * nao implementado
     * @param type $num
     * @param type $offset
     * @param Aew_Model_Bo_Album $album
     */
    public function selectAlbuns($num = 0, $offset = 0, Aew_Model_Bo_Album $album = null) {
        
    }

    /**
     * nao implementado
     * @param type $num
     * @param type $offset
     * @param Aew_Model_Bo_Blog $blog
     */
    public function selectBlogs($num = 0, $offset = 0,Aew_Model_Bo_Blog $blog = null) {
        
    }

    /**
     * nao implementado
     */
    public function selectFotoPerfil() {
        
    }

    /**
     * 
     * @param type $num
     * @param type $offset
     * @param \Aew_Model_Bo_ItemPerfil $avaliador
     */
    public function selectVotos($num = 0, $offset = 0, \Aew_Model_Bo_ItemPerfil $avaliador = null) {
        
    }
    
    /**
     * 
     * @param  $comentarios
     */
    public function setComentarios(array $comentarios)
    {
        $this->comentarios = $comentarios;
    }
    
    public function selectComentarios($num = 0, $offset = 0,  Aew_Model_Bo_ConteudoDigitalComentario $comentario = null,$options=null)
    {
        if(!$comentario)
        $comentario = new Aew_Model_Bo_ConteudoDigitalComentario();
        $comentario->setIdconteudodigitalcategoria($this->getId());
        $this->setComentarios($comentario->select($num, $offset, $options, true));
        return $this->getComentarios();
    }
    
    /**
     * 
     * @param int $acessos
     */
    function setAcessos($acessos)
    {
        $this->acessos = $acessos;
    }
    
    /**
     * 
     * @param int $avaliacao
     */
    function setAvaliacao($avaliacao)
    {
        $this->avaliacao = $avaliacao;
    }
    
    /**
     * 
     * @param int $qtddownloads
     */
    function setQtddownloads($qtddownloads)
    {
        $this->qtddownloads =$qtddownloads;
    }
    
    /**
     * retorna numero de acessos do conteudo digital do banco de dados
     * @return int
     */
    function selectQtddownloads()
    {
        $this->setQtddownloads($this->getDao()->selectSumaAttr($this->toArray(),'qtddownloads'));
        return $this->getQtddownloads();
    }
    
    /**
     * seleciona no banco de dados o numero de acessos do conteudo digital
     * @return int
     */
    function selectAcessos()
    {
        $this->setAcessos($this->getDao()->selectSumaAttr($this->toArray(),'acessos'));
        return $this->getAcessos();
    }
    
    /**
     * seleciona no banco de dados a avaliacao do conteudo digital
     * @return int
     */
    function selectAvaliacao()
    {
        $this->setAvaliacao($this->getDao()->selectAvgAttr($this->toArray(),'avaliacao'));
        return $this->getAvaliacao();
    }
    
    /**
     * 
     * @return int
     */
    function getAvaliacao()
    {
        return $this->avaliacao;
    }

    /**
     * cria objeto de acesso ao banco de dados
     * @return \Aew_Model_Dao_ConteudoDigitalCategoria
     */
    protected function createDao()
    { 
        return new Aew_Model_Dao_ConteudoDigitalCategoria();
    }

    /**
     * deleta registro do banco de dados e remove icone arquivo
     * @return boolean
     */
     public function delete(){
        if(parent::delete()):
            $icone = $this->getIconeDirectory().DS.$this->getId().'.png';
            if(file_exists($icone)):
                unlink($icone);
            endif;
            return true;
        endif;
    }   
}