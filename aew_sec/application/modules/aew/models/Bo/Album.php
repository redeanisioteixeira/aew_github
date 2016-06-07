<?php
/**
 * Bo da entidade Album
 *
 * @author tiago-souza
 */
class Aew_Model_Bo_Album extends Sec_Model_Bo_Abstract
{
    //put your code here
    protected $titulo;
    protected $fotos = array();
    protected $comentarios = array();
    protected $usuarioDono; //Aew_Model_Bo_Usuario ;
    protected $datacriacao;
    protected $cota;
    
    
    function __construct() {
        $this->setUsuarioDono(new Aew_Model_Bo_Usuario());
    }
    
    /**
     * 
     * @return array
     */
    function toArray() {
        $data = parent::toArray();
        if($this->getUsuarioDono()->getId())
        {
            $data['idusuario'] = $this->getUsuarioDono()->getId();
        }
        return $data;
    }
    
    /**
     * preenche o objeto com dados de uma array (de mapeamento chave-valor)
     * @param array $data
     */
    function exchangeArray($data) {
        parent::exchangeArray($data);
        $this->getUsuarioDono()->exchangeArray($data);
    }
    /**
     * retorna verdadeiro se usuario ja atingiu cota limite de
     * fotos para upload
     * @return boolean
     */
    function isCotaAlcancada()
    {
        if($this->getCota() < count($this->getFotos()))
        return true;
        return false;
    }
    
    /**
     * numero de fotos maximo por album
     * @return int
     */
    public function getCota()
    {
        return $this->cota;
    }

    /**
     * 
     * @param int $cota
     */
    public function setCota($cota)
    {
        $this->cota = $cota;
    }

    /**
     * 
     * @param Aew_Model_Bo_AlbumComentario $comentario
     * @return int|boolean
     */
    function insertComentario(Aew_Model_Bo_AlbumComentario $comentario)
    {
        $comentario->setIdusuarioalbumfoto($this->getId());
        return $comentario->insert();
    }
    /**
     * 
     * @return Aew_Model_Bo_Usuario
     */
    public function getUsuarioDono()
    {
        return $this->usuarioDono;
    }

    /**
     * 
     * @return string
     */
    public function getDataCriacao()
    {
        return $this->datacriacao;
    }

    /**
     * 
     * @param string $datacriacao
     */
    public function setDataCriacao($datacriacao)
    {
        $this->datacriacao = $datacriacao;
    }

    /**
     * @param Aew_Model_Bo_Usuario $usuario
     */
    public function setUsuarioDono(Aew_Model_Bo_Usuario $usuario)
    {
        $this->usuarioDono = $usuario;
    }

    /**
     * 
     * @param type $num
     * @param type $offset
     * @param Aew_Model_Bo_Foto $foto
     * @return type
     */
    function selectFotos($num=0,$offset=0,  Aew_Model_Bo_Foto $foto=null)
    {
        return $foto->select($num, $offset);
    }
    
    /**
     * 
     * @param type $num
     * @param type $offset
     * @param Aew_Model_Bo_Usuario $usuario
     * @return type
     */
    function selectComentarios($num=0,$offset=0,  Aew_Model_Bo_Usuario $usuario = null)
    {
        return $this->getComentarios();
    }
    
    /**
     * 
     * @return array
     */
    public function getComentarios()
    {
        return $this->comentarios;
    }

    /**
     * retorna array de comentarios
     * @param array $comentarios
     */
    public function setComentarios($comentarios)
    {
        $this->comentarios = $comentarios;
    }

    /**
     * retorna array com fotos de usuario
     * @return array
     */    
    public function getFotos()
    {
        return $this->fotos;
    }

    /**
     * 
     * @param array $fotos
     */
    public function setFotos(array $fotos)
    {
        $this->fotos = $fotos;
    }
    
    /**
     * @return string
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * @param string $titulo
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
    }
    
    /**
     * @param Aew_Model_Bo_Foto $foto
     * @return int
     */
    function insertFoto(Aew_Model_Bo_Foto $foto)
    {
        $foto->setIdalbum($this->getId());
        $foto->setDataCriacao(date('Y-m-d h:i:s'));
        return $foto->save();
    }
    
    /**
     * 
     * @param Aew_Model_Bo_ItemPerfil $perfilObject
     * @return boolean
     */
    function isDono(Aew_Model_Bo_ItemPerfil $perfilObject)
    {
        if($this->getUsuarioDono()->getId()==$perfilObject->getId())
        return true;
        return false;
    }

    protected function createDao() 
    {
        
    }
}