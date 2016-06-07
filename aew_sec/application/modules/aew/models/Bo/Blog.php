<?php

/**
 * BO da entidade blog criado pelo usuario no espaco-aberto
 *
 * @author tiagolns
 */
class Aew_Model_Bo_Blog extends Aew_Model_Bo_ItemPerfil
{
    //put your code here
    protected $titulo; //varchar(250)
    protected $datacriacao; //timestamp
    protected $texto; //text
    protected $comentarios = array();
    protected $usuario;
    
    function __construct() 
    {
        $this->setUsuarioCriador(new Aew_Model_Bo_Usuario());
    }
    
    /**
     * 
     * @return Aew_Model_Bo_Usuario
     */
    public function getUsuarioCriador()
    {
        return $this->usuario;
    }

    /**
     * 
     * @param Aew_Model_Bo_Usuario $usuario
     */
    public function setUsuarioCriador(Aew_Model_Bo_ItemPerfil $usuario)
    {
        $this->usuario = $usuario;
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
        if($this->getUsuarioCriador()->getId())
        {
            $data['idusuario'] = $this->getUsuarioCriador()->getId();
        }
        return $data;
    }
    
    /**
     * preenche o objeto com dados de uma array (de mapeamento chave-valor)
     * @param array $data
     */
    public function exchangeArray($data){
        parent::exchangeArray($data);
        $this->getUsuarioCriador()->exchangeArray($data);
    }
    
    /**
     * 
     * @return string
     */
    public function getTitulo() 
    {
        return $this->titulo;
    }

    /**
     * 
     * @param string $titulo
     */
    public function setTitulo($titulo) 
    {
        $this->titulo = $titulo;
    }

    /**
     * 
     * @param array $comentarios
     */
    public function setComentarios(array $comentarios) 
    {
        $this->comentarios = $comentarios;
    }

    /**
     * 
     * @return array
     */
    public function getComentarios() {
        return $this->comentarios;
    }
    
    /**
     * 
     * @return string
     */
    public function getTexto() {
        return $this->texto;
    }
    
    /**
     * 
     * @return string
     */
    public function getDataCriacao() {
        return $this->datacriacao;
    }

    public function setTexto($texto) {
        $this->texto = $texto;
    }

    public function setDataCriacao($dataCriacao) {
        $this->datacriacao = $dataCriacao;
    }

    /**
     * 
     * @param Aew_Model_Bo_BlogComentario $comentario
     * @return int
     */
    public function saveComentario(Aew_Model_Bo_BlogComentario $comentario)
    {
        $comentario->setIdblog($this->getId());
        $result = $comentario->save();
        if($result)
            array_push ($this->comentarios, $comentario);
        return $result;
    }
    
    /**
     * metodo vazio
     * @param \Aew_Model_Bo_ItemPerfil $avaliador
     * @param int $voto
     */
    public function insertVoto(\Aew_Model_Bo_ItemPerfil $avaliador, $voto) {
        
    }

    /**
     * * metodo vazio
     */
    public function perfilTipo() {
        
    }

    /**
     * metodo vazio
     * @param \Aew_Model_Bo_Foto $foto
     */
    public function saveFotoPerfil(\Aew_Model_Bo_Foto $foto) {
        
    }

    /**
     * metodo vazio
     * @param type $num
     * @param type $offset
     * @param \Aew_Model_Bo_Album $album
     */
    public function selectAlbuns($num = 0, $offset = 0, \Aew_Model_Bo_Album $album = null) {
        
    }

    /**
     * metodo vazio
     * @param type $num
     * @param type $offset
     * @param Aew_Model_Bo_Blog $blog
     */
    public function selectBlogs($num = 0, $offset = 0, Aew_Model_Bo_Blog $blog = null) {
        
    }

    /**
     * metodo vazio
     */
    public function selectFotoPerfil() {
        
    }

    /**
     * metodo vazio
     * @param type $num
     * @param type $offset
     * @param \Aew_Model_Bo_ItemPerfil $avaliador
     */
    public function selectVotos($num = 0, $offset = 0, \Aew_Model_Bo_ItemPerfil $avaliador = null) {
        
    }

    /**
     * metodo vazio
     * @param type $num
     * @param type $offset
     * @param Aew_Model_Bo_Usuario $usuario
     * @return type
     */
    function selectComentarios($num=0,$offset=0,Aew_Model_Bo_Usuario  $usuario=null)
    {
        $comentario->setIdblog($this->getId());
        if($usuario)
        $comentario->setUsuarioAutor($usuario);
        $this->setComentarios($comentario->select($num, $offset));
        return $this->getComentarios();
    }

    /**
     * metodo vazio
     */
    protected function createDao() {
        
    }

}