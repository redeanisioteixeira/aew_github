<?php

/**
 * BO da entidade UsuarioBlog
 */

class Aew_Model_Bo_UsuarioBlog extends Aew_Model_Bo_Blog
{
    
    protected $idusuarioblog; //int(11)
    protected $idusuario; //int(11)
    
    /**
     * @return int
     */
    public function getIdusuarioblog() {
        return $this->idusuarioblog;
    }

    /**
     * @return int
     */
    public function getIdusuario() {
        return $this->idusuario;
    }

    /**
     * 
     * @param int $idusuarioblog
     */
    public function setIdusuarioblog($idusuarioblog) {
        $this->idusuarioblog = $idusuarioblog;
    }

    /**
     * 
     * @param int $idusuario
     */
    public function setIdusuario($idusuario) {
        $this->idusuario = $idusuario;
    }

    /**
     * @return string
     */
    public function getLinkPerfil() 
    {
        return '/espaco-aberto/blog/exibir/usuario/'.$this->getIdUsuario().'/id/'.$this->getId();
    }

    public function selectFotoPerfil() {
        
    }

    public function perfilTipo() {
        return 'blog';
    }

    public function selectAlbuns($num = 0, $offset = 0, Aew_Model_Bo_Album $album = null)
    {
        
    }
    
    public function selectBlogs($num = 0, $offset = 0, Aew_Model_Bo_Blog $blog=null)
    {
    }

    /**
     * seleciona os comentarios do blog no banco de dadoss
     * @param int $num
     * @param int $offset
     * @param type $usuario
     * @return type
     */
    public function selectComentarios($num=0, $offset=0, Aew_Model_Bo_Usuario  $usuario=null)
    {
        $comentario = new Aew_Model_Bo_BlogComentario(1);
        $comentario->setIdblog($this->getId());
        if($usuario)
        $comentario->setUsuarioAutor($usuario);
        $this->setComentarios($comentario->select($num, $offset));
        return $this->getComentarios();
    }

    public function saveFotoPerfil(Aew_Model_Bo_Foto $foto)
    {
        
    }

    public function insertAvaliacao(Aew_Model_Bo_ItemPerfil $avaliador, $avaliacao)
    {
        
    }

    public function insertVoto(Aew_Model_Bo_ItemPerfil $avaliador, $voto)
    {
        
    }

    public function selectVotos($num = 0, $offset = 0, \Aew_Model_Bo_ItemPerfil $avaliador = null)
    {
        
    }
    
    /**
     * cria objeto de acesso ao banco de dados
     * @return \Aew_Model_Dao_UsuarioBlog
     */
    function createDao() {
        return new Aew_Model_Dao_UsuarioBlog();
    }

}