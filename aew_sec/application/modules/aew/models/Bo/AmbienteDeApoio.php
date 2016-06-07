<?php

/**
 * BO da entidade AmbienteDeApoio
 */
class Aew_Model_Bo_AmbienteDeApoio extends Aew_Model_Bo_ItemPerfil
{
    protected $ambientedeApoioCategoria; //Aew_Model_AmbienteDeApoioCategoria
    protected $titulo; //varchar(150)
    protected $url; //varchar(200)
    protected $urlprojeto; //varchar(200)
    protected $descricao; //text
    protected $usopedagogico; //text
    protected $acessos; //int(11)
    protected $fldestaque; //tinyint(1)
    protected $avaliacao; //int(11)
    protected $idfavorito;//int(11)
    protected $comentarios = array();
    protected $tags = array();
    protected $datacriacao;// varchar(8)
    protected $usuarioPublicador; //Aew_Model_Bo_Usuario
    
    /**
     * Construtor
     */
    public function __construct()
    {
        $this->setAmbientedeApoioCategoria(new Aew_Model_Bo_AmbienteDeApoioCategoria());
        $this->setUsuarioPublicador(new Aew_Model_Bo_Usuario());
    }

    /**
     * 
     * @return type
     */
    function getAvaliacao() {
        return $this->avaliacao;
    }

    /**
     * 
     * @return string
     */
    function getDatacriacao() {
        return $this->datacriacao;
    }

    /**
     * 
     * @return Aew_Model_Bo_Usuario
     */
    function getUsuarioPublicador() {
        return $this->usuarioPublicador;
    }

    /**
     * 
     * @param int $avaliacao
     */
    function setAvaliacao($avaliacao) {
        $this->avaliacao = $avaliacao;
    }

    /**
     * 
     * @param string $datacriacao
     */
    function setDatacriacao($datacriacao) {
        $this->datacriacao = $datacriacao;
    }

    /**
     * 
     * @param Aew_Model_Bo_Usuario $usuarioPublicador
     */
    function setUsuarioPublicador($usuarioPublicador) {
        $this->usuarioPublicador = $usuarioPublicador;
    }

    /**
     * 
     * @return int
     */
    public function getIdfavorito()
    {
        return $this->idfavorito;
    }

    /**
     * 
     * @param int $idfavorito
     */
    public function setIdfavorito($idfavorito)
    {
        $this->idfavorito = $idfavorito;
    }

    /**
     * retorna os parametro do objeto em um array
     * onde a chave e o nome da variavel de instancia e tambem
     * o nome do campo na tabela
     * @return array
     */
    function toArray()
    {
        $data = parent::toArray();
        if($this->getAmbientedeApoioCategoria()->getId())
        {
            $data['idambientedeapoiocategoria'] = $this->getAmbientedeApoioCategoria()->getId();
            $this->getDao()->setTableInTableField('idambientedeapoiocategoria', $this->getDao()->getName());
        }
        if($this->getUsuarioPublicador()->getId())
        {
            $data['idusuariopublicador'] = $this->getUsuarioPublicador()->getId();
        }
        return $data;
    }
    
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * 
     * @param array|string $tags
     */
    public function setTags($tags)
    {
        if(is_array($tags))
            $this->tags = $tags;
        
        else if(is_string($tags))
        {
            $nometags = explode(',',$tags);
            foreach( $nometags as $nometag)
            {
                $boTag = new Aew_Model_Bo_Tag();
                $boTag->setNome(trim($nometag));
                $boTag = $boTag->select(1);
                if($boTag instanceof Aew_Model_Bo_Tag)
                {
                    $this->addTag($boTag);   
                }
            }
        }
    }
    
    /**
     * 
     * @param Aew_Model_Bo_Tag $tag
     * @return type
     */
    public function insertTag(Aew_Model_Bo_Tag $tag)
    {
        $tagAmbiente = new Aew_Model_Bo_AmbienteDeApoioTag();
        if(is_array($tag->getId()))
        {
            $tagAmbiente->setId($tag->getId()); 
        }
        else
        $tagAmbiente->setId(array($this->getId(),$tag->getId())); 
        $result = $tagAmbiente->insert();
        if($result)
            $this->addTag ($tagAmbiente);
        return $result;
    }
    
    /**
     * insere tags no ambiente de apoio
     */
    function insertTags($tags = "")
    {
        if($tags)
        {
            $this->setTags($tags);
        }
        foreach($this->getTags() as $tag)
        {
            $this->insertTag($tag);
        }
    }
    
    /***/
    public function exchangeArray($data)
    {
        parent::exchangeArray($data);
        $this->getAmbientedeApoioCategoria()->exchangeArray($data);
        $this->getUsuarioPublicador()->exchangeArray($data);
        $this->setTags(isset($data["tags"])? $data["tags"]:null);
    }

    /**
     * 
     * @return Aew_Model_Bo_AmbienteDeApoioCategoria
     */
    public function getAmbientedeApoioCategoria() 
    {
        return $this->ambientedeApoioCategoria;
    }

    /**
     * @param Aew_Model_Bo_AmbienteDeApoioCategoria $ambientedeApoioCategoria
     */
    public function setAmbientedeApoioCategoria(Aew_Model_Bo_AmbienteDeApoioCategoria $ambientedeApoioCategoria) {
        $this->ambientedeApoioCategoria = $ambientedeApoioCategoria;
    }

    public function getUrl() {
        return $this->url;
    }

    public function getTitulo()
    {
        return $this->titulo;
    }

    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
    }

    public function getUrlprojeto() {
        return $this->urlprojeto;
    }

    public function getDescricao() {
        return $this->descricao;
    }

    public function getUsopedagogico() {
        return $this->usopedagogico;
    }

    public function getAcessos() {
        return $this->acessos;
    }

    public function getDestaque() {
        return $this->fldestaque;
    }

    public function setUrl($url) {
        $this->url = $url;
    }

    public function setUrlprojeto($urlprojeto) {
        $this->urlprojeto = $urlprojeto;
    }

    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    public function setUsopedagogico($usopedagogico) {
        $this->usopedagogico = $usopedagogico;
    }

    public function setAcessos($acessos) {
        $this->acessos = $acessos;
    }

    public function setDestaque($destaque) {
        $this->fldestaque = $destaque;
    }

    function uploadIcon(Sec_Form $form)
    {
        return $this->upload($form->icone, $this->getIconeDirectory());
    }
    
    function addTag(Aew_Model_Bo_Tag $tag)
    {
        array_push($this->tags, $tag);
    }
    /**
     * Aumenta o número de acessos de um ambiente de apoio
     * @return int
     */
    public function aumentarAcesso()
    {
        $this->setAcessos($this->getAcessos()+1);
        return $this->update();
    }

    /**
     * retorna o diretorio para os icones
     */
    static  function getIconeDirectory()
    {
        if(CONTEUDO_PATH):
            $path = MEDIA_PATH.DS.CONTEUDO_PATH.DS.'ambientes-apoio'.DS.'imagem-associada';
        ELSE:
            $path = $_SERVER['DOCUMENT_ROOT'].DS.'ambientes-apoio'.DS.'imagem-associada';
            //$path = MEDIA_PATH.DS.'ambientes-apoio'.DS.'imagem-associada';
        endif;
        
        return $path;
    }    

    public function removerAmbienteDestaque(AmbienteDeApoio $conteudo)
    {
        $conteudo->fldestaque = false;
        return $this->save($conteudo);
    }
    
    public function getComentarios()
    {
        return $this->comentarios;
    }

    public function setComentarios($comentarios)
    {
        $this->comentarios = $comentarios;
    }
    
    /**
     * seleciona no banco de dados os comentarios deste ambiente de apoio
     * @param int $num
     * @param int $offset
     * @param Aew_Model_Bo_Usuario $usuario
     * @return array
     */
    public function selectComentarios($num=0,$offset = 0,Aew_Model_Bo_Usuario $usuario=null)
    {
        if(!$this->getId())
            return array();
        $comentario = new Aew_Model_Bo_AmbienteDeApoioComentario();    
        if($usuario)
        {
            $comentario->setUsuario ($usuario);
        }
        $comentario->setIdambientedeapoio($this->getId());
        $this->setComentarios($comentario->select($num, $offset,null,true));
        return $this->getComentarios();
    }

    /**
     * @param int $num
     * @param int $offset
     * @param Aew_Model_Bo_ConteudoDigitalTag $tag
     * @return array
     */
    function selectTags($num=0,$offset=0,  Aew_Model_Bo_Tag $tag = null)
    {
        if(!$this->getId())
            return;
        $tagAmbiente = new Aew_Model_Bo_AmbienteDeApoioTag();
        if($tag)
        $tagAmbiente->exchangeArray ($tag->toArray());
        $tagAmbiente->setId($this->getId());
        $this->setTags($tagAmbiente->select($num, $offset));
        return $this->getTags();
    }
    
    /**
     * @param type $comunidade
     * @return string
     */
    function getUrlExibir($comunidade='')
    {
        if($comunidade!=""):
            $url_ambiente_apoio =  '/ambientes-de-apoio/ambiente/exibir/id/'.$this->getId().'/comunidade/'.$comunidade; 	
	else: 	
            $url_ambiente_apoio = '/ambientes-de-apoio/ambiente/exibir/id/'.$this->getId(); 	
	endif;  
        return $url_ambiente_apoio;
    
    }

    /**
     * @param Aew_Model_Bo_Usuario $usuario
     * @return string
     */
    function getUrlApagar(Aew_Model_Bo_ItemPerfil $usuario)
    {
        if($this->getId())
        if($usuario->isAdmin())  
        return '/ambientes-de-apoio/ambiente/apagar/id/'.$this->getId();		
    }   
    
    /**
     * @param Aew_Model_Bo_Usuario $usuario
     * @return string
     */
    function getUrlEditar(Aew_Model_Bo_Usuario $usuario)
    {
        if($this->getId())
        if(($usuario->getUsuarioTipo()->getNome()==Aew_Model_Bo_UsuarioTipo::SUPER_ADMINISTRADOR) ||
        ($usuario->getUsuarioTipo()->getNome()==Aew_Model_Bo_UsuarioTipo::ADMINISTRADOR))        
        return '/ambientes-de-apoio/ambiente/editar/id/'.$this->getId();
    }
    
    
    
    /**
     * @param Aew_Model_Bo_Usuario $usuario
     * @return string
     */
    function getUrlRemoverDestaque(Aew_Model_Bo_Usuario $usuario)
    {
        if($this->getId())
        if($this->getDestaque())
        if(($usuario->getUsuarioTipo()->getNome()== Aew_Model_Bo_UsuarioTipo::SUPER_ADMINISTRADOR)||
          ($usuario->getUsuarioTipo()->getNome()== Aew_Model_Bo_UsuarioTipo::ADMINISTRADOR))
        return '/ambientes-de-apoio/ambiente/removerdestaque/id/'.$this->getId();
    }
    
    /**
     * @param Aew_Model_Bo_Usuario $usuario
     * @return string
     */
    function getUrlAdicionarDestaque(Aew_Model_Bo_Usuario $usuario)
    {
        if($this->getId())
        if(!$this->getDestaque())
        if(($usuario->getUsuarioTipo()->getNome()== Aew_Model_Bo_UsuarioTipo::SUPER_ADMINISTRADOR)||
          ($usuario->getUsuarioTipo()->getNome()== Aew_Model_Bo_UsuarioTipo::ADMINISTRADOR))
        return '/ambientes-de-apoio/ambiente/destaque/id/'.$this->getId();
    }
    
    /**
     * @param Aew_Model_Bo_Usuario $usuario
     * @return int
     */
    function getUrlAdicionarFavorito(Aew_Model_Bo_ItemPerfil $item)
    {
        if(!$this->isFavorito($item))
        return '/ambientes-de-apoio/ambiente/favorito/id/'.$this->getId().'/'.$item->perfilTipo().'/'.$item->getId();
    }

    /**
     * @param Aew_Model_Bo_Usuario $usuario
     * @return int
     */
    function getUrlRemoverFavorito(Aew_Model_Bo_ItemPerfil $item)
    {
        if($this->isFavorito($item))
        return '/ambientes-de-apoio/ambiente/remover-favorito/id/'.$this->getId().'/'.$item->perfilTipo().'/'.$item->getId();
    }
    
    /**
     * 
     * @param Aew_Model_Bo_Tag $tag
     * @return int
     */
    function deleteTag(Aew_Model_Bo_Tag $tag)
    {
        $tagAmbiente = new Aew_Model_Bo_AmbienteDeApoioTag();
        if(is_array($tag->getId()))
        $tagAmbiente->setId($tag->getId());
        else
        $tagAmbiente->setId(array($this->getId(),  $tag->getId()));
        return $tagAmbiente->delete();
    }
    
    /**
     * @param string|array $tags
     */
    function deleteTags($tags = "")
    {
        if($tags)
            $this->setTags ($tags);
        foreach($this->getTags() as $tag)
        {
            $this->deleteTag($tag);
        }
        $this->setTags(array());
    }
    
    
    function insertComentario(Aew_Model_Bo_Comentario $comentario)
    {
        $ambienteComentario = new Aew_Model_Bo_AmbienteDeApoioComentario();
        $ambienteComentario->setIdambientedeapoio($this->getId());
        $ambienteComentario->exchangeArray($comentario->toArray());
        $result = $ambienteComentario->insert();
        if($result)
        {
            $this->addComentario($ambienteComentario);
        }
        return $result;
    }
    
    /**
     * retorn se o ambiente é favorito do objeto de perfil
     * @param Aew_Model_Bo_ItemPerfil $usuarioPerfil
     * @return boolean
     */
    function isFavorito(Aew_Model_Bo_ItemPerfil $usuarioPerfil)
    {
        $ambienteDeApoiofavorito = new Aew_Model_Bo_AmbienteDeApoioFavorito();
        $ambienteDeApoiofavorito->setId(array($usuarioPerfil->getIdFavorito(), $this->getId()));
        return $ambienteDeApoiofavorito->selectAutoDados(); 
    }
    
    public function getSiteStatus()
    {
        $result = 0;
        return $result = 200;
        $arrValidos = array(0 => 200, 1 => 302, 2 => 301); //301
        $status = $this->extrairContentType($this->getUrlprojeto());

        if(isset($status['http_code'])){
            foreach($arrValidos as $key=>$value){
                if($status['http_code'] == $value){
                    $result = $value;
                    break;
                }
            }
        }
        return $result;
    }

    private function extrairContentType($arquivo)
    {
        $tipo = "";
        
        ob_start();
        // Crear un gestor curl
        $ch = curl_init($arquivo);

        // Ejecutar
        curl_exec($ch);

        // Comprobar si ocurrió un error
        if(!curl_errno($ch))
        {
            $info = curl_getinfo($ch);

            $tipo["type"] = $info["content_type"];
            $tipo["length"] = $info["size_download"];
            $tipo["http_code"] = $info["http_code"];
        }
        // Cerrar manipulador
        curl_close($ch);
        ob_get_clean();
        return $tipo;
    }

    /**
     * @param Aew_Model_Bo_ConteudoDigital $conteudo
     * @param int $num
     * @param int $offset
     * @return array
     */
    public function selectAmbientesRelacionados($num=0,$offset=0)
    {   
        return $this->getDao()->selectAmbientesRelacionados($this,$num,$offset);
    }

    public function insertVoto(\Aew_Model_Bo_ItemPerfil $avaliador, $voto) {
        
    }

    public function perfilTipo() {
        
    }

    public function saveFotoPerfil(\Aew_Model_Bo_Foto $foto) {
        
    }

    public function selectAlbuns($num = 0, $offset = 0, Aew_Model_Bo_Album $album = null) {
        
    }

    public function selectBlogs($num = 0, $offset = 0,  Aew_Model_Bo_Blog $blog = null) {
        
    }

    public function selectFotoPerfil() {
        
    }

    public function selectVotos($num = 0, $offset = 0, \Aew_Model_Bo_ItemPerfil $avaliador = null) {
        
    }

    public static function getImagemAssociadaDirectory()
    {
        if(CONTEUDO_PATH):
            $path = MEDIA_PATH.DS.CONTEUDO_PATH.DS.'ambiente-apoio'.DS.'imagem-associada';
        ELSE:
            $path = MEDIA_PATH.DS.'ambiente-apoio'.DS.'imagem-associada';
        endif;

        return $path;
    }
    
    public static function getImagemAssociadaUrl($urlAbsoluta = false)
    {
        $path = '';
        if($urlAbsoluta):
            $path = new Zend_View_Helper_ServerUrl();
            $path = $path->serverUrl();    
        endif;
        
        if(CONTEUDO_PATH):
            $path .= DS.CONTEUDO_PATH.DS.'ambientes-apoio'.DS.'imagem-associada';
        ELSE:
            $path .= DS.'ambientes-apoio'.DS.'imagem-associada';
        endif;

        return $path;
    }
    
    /**
     * @return string
     */
    function getLinkPerfil($urlAbsoluta = false)
    {
        $path = '';
        if($urlAbsoluta):
            $path = new Zend_View_Helper_ServerUrl();
            $path = $path->serverUrl();    
        endif;
        
        return $path.'/ambientes-de-apoio/ambiente/exibir/id/'.$this->getId();
    }

    protected function createDao()
    {
        return new Aew_Model_Dao_AmbienteDeApoio();
    }
}