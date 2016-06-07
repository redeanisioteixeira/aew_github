<?php

/**
 * BO da entidade ComunidadeAlbum
 */

class Aew_Model_Bo_ComunidadeAlbum extends Aew_Model_Bo_Album
{
    protected $idcomunidade;
    
    /**
     * 
     * @return int
     */
    public function getIdcomunidade()
    {
        return $this->idcomunidade;
    }

    /**
     * 
     * @param int $idcomunidade
     */
    public function setIdcomunidade($idcomunidade)
    {
        $this->idcomunidade = $idcomunidade;
    }
    
    /**
     * @param Aew_Model_Bo_Usuario $usuario
     * @return string
     */
    function getUrlExibir(Aew_Model_Bo_Comunidade $comunidade)
    {
        return '/espaco-aberto/album/exibir/comunidade/'.$comunidade->getId().'/id/'.$this->getId();
    }
    
    /**
     * @param \Aew_Model_Bo_Foto $foto
     * @param \Aew_Model_Bo_Usuario $usuario
     * @return type
     */
    public function insertFoto(Aew_Model_Bo_Foto $foto)
    {
        $comunidadeFoto = new Aew_Model_Bo_ComunidadeAlbumFoto();
        $comunidadeFoto->exchangeArray($foto->toArray());
        $comunidadeFoto->setIdcomunidadealbum($this->getId());
        $comunidadeFoto->setFotoFile($foto->getFotoFile());
        return $comunidadeFoto->insert();
    }
    
    /**
     * 
     * @param int $num
     * @param int $offset
     * @param Aew_Model_Bo_Foto $foto
     * @return array
     */
    public function selectFotos($num = 0, $offset = 0, Aew_Model_Bo_Foto $foto = null)
    {
        $comunidadeAlbumFoto = new Aew_Model_Bo_ComunidadeAlbumFoto();
        if($foto)
        $comunidadeAlbumFoto->exchangeArray($foto->toArray());
        $comunidadeAlbumFoto->setIdcomunidadealbum($this->getId());
        $fotos = array();
        foreach($comunidadeAlbumFoto->select($num, $offset) as $comunidadeFoto)
        {
            array_push($fotos, $comunidadeFoto);
        }
        $this->setFotos($fotos);
        return $this->getFotos();
    }
    /**
     * 
     * @param Aew_Model_Bo_Usuario $usuario
     * @return string
     */
    function getUrlAdicionarFoto(Aew_Model_Bo_Comunidade $usuario=null)
    {
        if(!$usuario)
        return '';
        return '/espaco-aberto/foto/adicionar/comunidade/'.$usuario->getId().'/album/'.$this->getId();
    }
    /**
     * 
     * @param Aew_Model_Bo_Usuario $usuario
     * @return string
     */
    function getUrlEditar(Aew_Model_Bo_ItemPerfil $usuario)
    {
        if($this->isDono($usuario))
        return "/espaco-aberto/album/editar/comunidade/".$this->getIdcomunidade()."/id/".$this->getId();
    }
    
    /**
     * 
     * @param type $num
     * @param type $offset
     * @param Aew_Model_Bo_Usuario $usuario
     * @return type
     */
    function selectComentarios($num = 0, $offset = 0, Aew_Model_Bo_Usuario $usuario = null) 
    {
        $comentario = new Aew_Model_Bo_AlbumComentario(1,1);
        $comentario->setIdusuarioalbumfoto($this->getId());
        if($usuario)
        $comentario->setUsuarioAutor($usuario);
        $this->setComentarios($comentario->select($num, $offset));
        return $this->getComentarios();
    }
    
    /**
     * 
     * @param Aew_Model_Bo_AlbumComentario $comentario
     * @return int
     */
    public function insertComentario(Aew_Model_Bo_AlbumComentario $comentario) 
    {
        $comentario->setTipoalbum(2);
        $comentario->setTipocomentario(2);
        return parent::insertComentario($comentario);
    }
    
    /**
     * 
     * @return \Aew_Model_Dao_ComunidadeAlbum
     */
    public function createDao() {
        return new  Aew_Model_Dao_ComunidadeAlbum();
    }
}