<?php
/**
 * representa uma entiidade que possua perfil, fotos , albuns etc
 *
 * @author tiagolns
 */
abstract class Aew_Model_Bo_ItemPerfil extends Sec_Model_Bo_Abstract
{
    //put your code here
    
    private $fotoPerfil,$linkPerfil,$primeiroNome;
    protected $conteudosDigitaisFavoritos = array(),$idfavorito;//
    protected $ambientesDeApoioFavoritos = array(),$nome;
    protected $albuns =array();
    protected $avaliacao;
    protected $votos = array();
    protected $blogs = array();
    protected $fotos = array();

    /**
     * 
     * @return Aew_Model_Bo_Foto
     */
    public function getFotoPerfil() {
        return $this->fotoPerfil;
    }
    
    /**
     * 
     * @return array
     */
    function getFotos() {
        return $this->fotos;
    }

    /**
     * 
     * @param array $fotos
     */
    function setFotos($fotos) {
        $this->fotos = $fotos;
    }

    /**
    * 
    * @return type
    */    
    function getBlogs() {
        return $this->blogs;
    }

    /**
     * 
     * @param type $blogs
     */
    function setBlogs($blogs) {
        $this->blogs = $blogs;
    }

    /**
     * 
     * @param array $albuns
     */
    public function setAlbuns($albuns) {
        $this->albuns = $albuns;
    }
    
    /**
     * 
     * @return int
     */
    public function getAvaliacao()
    {
        $votos = 0;
        foreach ($this->votos as $voto)
        {
            $votos += $voto->getVoto();
        }
        if(count($this->votos)>0)
        $this->avaliacao = $votos/count($this->votos);
        return $this->avaliacao;
    }

    /**
     * 
     * @param int $avaliacao
     */
    public function setAvaliacao($avaliacao)
    {
        $this->avaliacao = $avaliacao;
    }
    
    /**
     * 
     * @return int
     */
    public function getIdFavorito() {
        return $this->idfavorito;
    }

    /**
     * 
     * @param int $idFavorito
     */
    public function setIdFavorito($idFavorito) {
        $this->idfavorito = $idFavorito;
    }

    /**
     * 
     * @return array
     */
    public function getVotos()
    {
        return $this->votos;
    }
    
    /**
     * 
     * @param array $votos
     */
    public function setVotos($votos)
    {
        $this->votos = $votos;
    }

    /**
     * 
     * @return string
     */
    public function getNome() {
        return $this->nome;
    }

    /**
     * 
     * @return string
     */
    public function getLinkPerfil() {
        return $this->linkPerfil;
    }

    public function getPrimeiroNome()
    {
        $nomes = explode(' ', $this->getNome());
        return $nomes[0];
    }

    /**
     * 
     * @param Aew_Model_Bo_Foto $fotoPerfil
     */
    public function setFotoPerfil(Aew_Model_Bo_Foto $fotoPerfil) {
        $this->fotoPerfil = $fotoPerfil;
    }

    /**
     * 
     * @param string $nome
     */
    public function setNome($nome) {
        $this->nome = $nome;
        
    }

    /**
     * 
     * @param string $linkPerfil
     */
    public function setLinkPerfil($linkPerfil) {
        $this->linkPerfil = $linkPerfil;
    }

    /**
     * 
     * @param string $primeiroNome
     */
    public function setPrimeiroNome($primeiroNome) {
        $this->primeiroNome = $primeiroNome;
    }
    
    /**
     * 
     * @return array
     */
    public function getAmbientesDeApoioFavoritos()
    {
        return $this->ambientesDeApoioFavoritos;
    }

    /**
     * 
     * @param array $ambientesDeApoioFavoritos
     */
    public function setAmbientesDeApoioFavoritos($ambientesDeApoioFavoritos)
    {
        $this->ambientesDeApoioFavoritos = $ambientesDeApoioFavoritos;
    }
    
    /**
     * 
     * @param Aew_Model_Bo_AmbienteDeApoio $ambiente
     */
    function addAmbienteDeApoioFavorito(Aew_Model_Bo_AmbienteDeApoio $ambiente)
    {
        array_push($this->ambientesDeApoioFavoritos, $ambiente);
    }
    
    /**
     * 
     * @return array
     */
    public function getConteudosDigitaisFavoritos()
    {
        return $this->conteudosDigitaisFavoritos;
    }

    /**
     * 
     * @param type $conteudosDigitaisFavoritos
     */
    public function setConteudosDigitaisFavoritos($conteudosDigitaisFavoritos)
    {
        $this->conteudosDigitaisFavoritos = $conteudosDigitaisFavoritos;
    }

    
    /**
     * busca no banco de dados a fotoperfil
     */
    abstract function selectFotoPerfil();
    
    /**
     * 
     * @param int $num
     * @param int $offset
     * @param Aew_Model_Bo_AmbienteDeApoioFavorito $ambienteDeApoio
     * @return type
     */
    public function selectAmbientesDeApoioFavotitos($num=0,$offset=0,Aew_Model_Bo_AmbienteDeApoio $ambienteDeApoio=null)
    {
        if(!$ambienteDeApoio)
            $ambienteDeApoio = new Aew_Model_Bo_AmbienteDeApoio();
        $ambienteDeApoioFavorito = new Aew_Model_Bo_AmbienteDeApoioFavorito();
        $ambienteDeApoioFavorito->setIdfavorito($this->getIdFavorito());
        $ambienteDeApoioFavorito->setIdambientedeapoio($ambienteDeApoio->getId());
        $ambienteDeApoioFavorito->setTitulo($ambienteDeApoio->getTitulo());
        $this->setAmbientesDeApoioFavoritos($ambienteDeApoioFavorito->select($num, $offset));
        return $this->getAmbientesDeApoioFavoritos();
    }
    
    /**
     * seleciona os conteudos digitais favoritos da comunidade no banco de dados
     * @param int $num
     * @param int $offset
     * @param Aew_Model_Bo_ConteudoDigitalFavorito $conteudodigital
     * @return array
     */
    public function selectConteudosDigitaisFavoritos($num=0,$offset=0,  Aew_Model_Bo_ConteudoDigital $conteudodigital = null) 
    {
        if(!$conteudodigital)
        $conteudodigital = new Aew_Model_Bo_ConteudoDigital();
        $conteudoDigitalFavorito = new Aew_Model_Bo_ConteudoDigitalFavorito();
        $conteudoDigitalFavorito->setIdFavorito($this->getIdFavorito());
        $conteudoDigitalFavorito->setId($conteudodigital->getId());
        $conteudoDigitalFavorito->setTitulo($conteudodigital->getTitulo());
        $this->setConteudosDigitaisFavoritos($conteudoDigitalFavorito->select($num, $offset));
        return $this->getConteudosDigitaisFavoritos();
    }
    
    /**
     * 
     * @param Aew_Model_Bo_ConteudoDigitalFavorito $conteudo
     * @return int
     */
    function insertConteudoFavorito(Aew_Model_Bo_ConteudoDigital $conteudo)
    {
        $conteudoFavorito = new Aew_Model_Bo_ConteudoDigitalFavorito();
        $conteudoFavorito->setId($conteudo->getId());
        $conteudoFavorito->setIdFavorito($this->getIdFavorito()); 
        return $conteudoFavorito->insert();
    }
    
    /**
     * 
     * @param Aew_Model_Bo_ConteudoDigital $conteudo
     * @return boolean 
     */
    function deleteConteudoDigitalFavorito(Aew_Model_Bo_ConteudoDigital $conteudo)
    {
        $conteudoFavorito = $this->selectConteudosDigitaisFavoritos(1, 0, $conteudo);
        return $conteudoFavorito->delete();
    }
    
    /**
     * 
     * @param Aew_Model_Bo_ConteudoDigital $conteudo
     * @return boolean
     */
    function isConteudoFavorito(Aew_Model_Bo_ConteudoDigital $conteudo)
    {
        $conteudoFavorito = $this->selectConteudosDigitaisFavoritos(1, 0, $conteudo);
        if( $conteudoFavorito instanceof Aew_Model_Bo_ConteudoDigital)
        {
            return true;
        }
        return false;
    }
    
    /**
     * 
     * @return array
     */
    public function getAlbuns()
    {
        return $this->albuns;
    }

    public function deleteAlbum(Aew_Model_Bo_Album $album) 
    {
        $album = $this->selectAlbuns(1, 0, $album);
        if($album)
            return $album->delete ();
        return false;
    }
    /**
     * 
     * @param array $albuns
     */
    public function setAlbum(array $albuns)
    {
        $this->albuns = $albuns;
    }

    abstract function selectAlbuns($num =0, $offset=0, Aew_Model_Bo_Album $album=null);
   /**
     * retorna tipo do perfil
     */
    abstract function perfilTipo();
    
    /**
     * Seleciona na base de dados os blogs do usuario
     * @param int $num quantidade de objetos do array
     * @return array Aew_Model_Bo_UsuarioBlog
     */
    abstract function selectBlogs($num=0,$offset=0, Aew_Model_Bo_Blog $blog =null);
    
    /**
     * @param Aew_Model_Bo_AmbienteDeApoio $ambiente
     * @return type
     */
    function insertAmbienteFavorito(Aew_Model_Bo_AmbienteDeApoio $ambiente)
    {
        $result = $ambiente->insert();
        if($result)
        {
            $this->addAmbienteDeApoioFavorito($ambiente);
        }
        return $result;
    }

    /**
     * 
     * @param Aew_Model_Bo_AmbienteDeApoio $ambiente
     * @return type
     */
    function deleteAmbienteFavorito(Aew_Model_Bo_AmbienteDeApoio $ambiente)
    {
        $ambienteFavorito = $this->selectAmbientesDeApoioFavotitos(1, 0, $ambiente);
        return $ambienteFavorito->delete();
    }
    
    /**
     * remove ambiente da lista dos favoritos
     * @param Aew_Model_Bo_AmbienteDeApoio $ambiente
     */
    function removeAmbienteFavorito(Aew_Model_Bo_AmbienteDeApoio $ambiente)
    {
        $ambientesFavoritos = $this->getAmbientesDeApoioFavoritos();
        foreach ($ambientesFavoritos as $ambienteFavorito)
        {
            if($ambiente->getId()==$ambienteFavorito->getId())
            {
                
            }
        }
    }
    
    /**
     * 
     * @param Aew_Model_Bo_Blog $blog
     * @return int
     */
    function deleteBlog(Aew_Model_Bo_Blog $blog)
    {
        return $blog->delete();
    }
    
    /**
     * 
     * @param Aew_Model_Bo_Album $album
     * @return int
     */
    function saveAlbum(Aew_Model_Bo_Album $album)
    {
        return  $album->save();
    }
    
    /**
     * 
     * @param Aew_Model_Bo_Foto $foto
     * @return boolean|int
     */
    function saveFotoPerfil(Aew_Model_Bo_Foto $foto)
    {
        if($foto->uploadImg())
        {
            $result = $foto->save();
            if($result)
                $this->setFotoPerfil ($foto);
            return $result;
        }
        return false;
    }
    
    abstract function insertVoto(Aew_Model_Bo_ItemPerfil $avaliador,$voto);
    
    /**
     * @param int $avaliacao
     */
    function addVoto($voto)
    {
        array_push($this->votos, $voto);
    }   
    
    /**
     * retorna se o conteudo foi marcado como favorito
     * 
     * @param Aew_Model_Bo_AmbienteDeApoio $ambienteDeApoio
     * @return boolean
     */
    public function isAmbienteFavorito(Aew_Model_Bo_AmbienteDeApoio $ambienteDeApoio)
    {
        $ambienteFavorito = $this->selectAmbientesDeApoioFavotitos(1, 0, $ambienteDeApoio);
        return $ambienteFavorito->delete();
    }
    
    /**
     * 
     * @return \Aew_Model_Bo_Voto
     */
    public function getMediaVotos()
    {
        $media = new Aew_Model_Bo_Voto();
        foreach($this->getVotos() as $avali)
        {
            $media->setVoto($avali->getVoto()+$media->getVoto());
        }
        $media->setVoto($media->getVoto()/count($this->getVotos()));
        return $media;
    }
    
    /**
     * retorna registro de votos no banco de dados
     */
    abstract public function selectVotos($num=0,$offset=0,Aew_Model_Bo_ItemPerfil $avaliador=null);
   
    
    /**
     * nao implementado
     * @param Aew_Model_Bo_ItemPerfil $avaliador
     * @param int $avaliacao
     */
    public function insertAvaliacao(Aew_Model_Bo_ItemPerfil $avaliador, $avaliacao)
    {
        
    }
    
    /**
     * usuario insere a foto no seu album
     * @param Aew_Model_Bo_Foto $foto
     * @param Aew_Model_Bo_Album $album
     */
    function insertFotoAlbum(Aew_Model_Bo_Foto $foto, Aew_Model_Bo_Album $album) 
    {
        $albumPerfil = $this->selectAlbuns(1,0, $album);
        $foto->setIdalbum($albumPerfil->getId());
        return $albumPerfil->insertFoto($foto);
    }
    /**
     * 
     * @param Aew_Model_Bo_Blog $blog
     * @return int
     */
    function saveBlog(Aew_Model_Bo_Blog $blog)
    {
        return $blog->save();
    }
    
    /**
     * retorna verdadeiro se os objetos tem mesmo id
     * @param Aew_Model_Bo_ItemPerfil $item
     * @return boolean
     */
    function itSelf(Aew_Model_Bo_ItemPerfil $item)
    {
        if($this->getId() === $item->getId())
            return true;
        
        return false;
    }
    
    /**
     * nao impementado
     * @param type $num
     * @param type $offset
     * @param Aew_Model_Bo_Foto $foto
     */
    function selectFotos($num=0,$offset=0,Aew_Model_Bo_Foto $foto=null)
    {
        
    }
}   