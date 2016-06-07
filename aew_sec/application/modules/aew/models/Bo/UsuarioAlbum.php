<?php

/**
 * BO da entidade UsuarioAlbum
 */

class Aew_Model_Bo_UsuarioAlbum extends Aew_Model_Bo_Album
{
    protected $idusuario; //int(11)
    
    /**
     * @return int
     */
    public function getIdusuario() {
        return $this->idusuario;
    }

    /**
     * 
     * @param type $idusuario
     */
    public function setIdusuario($idusuario)
    {
        $this->idusuario = $idusuario;
    }

    function getUrlApagar(Aew_Model_Bo_ItemPerfil $usuario)
    {
        if($usuario->getId()==$this->getIdUsuario())
        return "/espaco-aberto/album/apagar/usuario/".$this->getIdusuario ()."/id/".$this->getId();
    }
    
    /**
     * 
     * @param Aew_Model_Bo_Usuario $usuario
     * @return string
     */
    function getUrlEditar(Aew_Model_Bo_Usuario $usuario)
    {
        if($usuario->getId()==$this->getIdUsuario())
        return "/espaco-aberto/album/editar/usuario/".$this->getIdusuario ()."/id/".$this->getId();
    }
    
    /**
     * 
     * @param Aew_Model_Bo_Usuario $usuario
     * @return string
     */
    function getUrlExibir(Aew_Model_Bo_Usuario $usuario)
    {
        return '/espaco-aberto/album/exibir/usuario/'.$usuario->getId().'/id/'.$this->getId();
    }
    
    /**
     * 
     * @param Aew_Model_Bo_Usuario $usuario
     * @return string
     */
    function getUrlAdicionarFoto(Aew_Model_Bo_Usuario $usuario=null)
    {
        if(!$usuario)
        return '';
        return '/espaco-aberto/foto/adicionar/usuario/'.$usuario->getId().'/album/'.$this->getId();
    }
    
    /**
     * 
     * @param int $num
     * @param  int $offset
     * @param  Aew_Model_Bo_Usuario $usuario
     * @return type
     */
    function selectComentarios($num = 0, $offset = 0, Aew_Model_Bo_Usuario $usuario = null,$options=array(),$globalCount=false)
    {
        $comentario = new Aew_Model_Bo_AlbumComentario(1,1);
        $comentario->setIdusuarioalbumfoto($this->getId());
        $comentario->setTipocomentario(1);
        $comentario->setTipoalbum(1);//tipo de album do usuario
        if($usuario)
        $comentario->setUsuarioAutor($usuario);
        if(!$globalCount)
        die();
        $this->setComentarios($comentario->select($num, $offset,$options,$globalCount));
        return $this->getComentarios();
    }
    
    /**
     * 
     * @param \Aew_Model_Bo_Foto $foto
     * @param \Aew_Model_Bo_Usuario $usuario
     * @return int
     */
    public function insertFoto(Aew_Model_Bo_Foto $foto)
    {
        $usuarioFoto = new Aew_Model_Bo_UsuarioAlbumFoto();
        $usuarioFoto->setFotoFile($foto->getFotoFile());
        $usuarioFoto->exchangeArray($foto->toArray());
        $usuarioFoto->setIdusuarioalbum($this->getId());
        return parent::insertFoto($usuarioFoto);
    }

    /**
     * 
     * @param int $num
     * @param int $offset
     * @param Aew_Model_Bo_Foto $foto
     * @return array
     */
    public function selectFotos($num = 0, $offset = 0, Aew_Model_Bo_Foto $foto = null,$options = array(),$globalCountRecords=false)
    {
        $usuarioAlbumFoto = new Aew_Model_Bo_UsuarioAlbumFoto();
        if($foto)
        {
            $usuarioAlbumFoto->setId ($foto->getId ()); 
        }
        $usuarioAlbumFoto->setIdusuarioalbum($this->getId()); 
        $this->setFotos($usuarioAlbumFoto->select($num, $offset, $options, $globalCountRecords));
        return $this->getFotos();
    }
    
    /**
     * @param Aew_Model_Bo_AlbumComentario $comentario
     * @return int
     */
    function insertComentario(Aew_Model_Bo_AlbumComentario $comentario) 
    {
        $comentario->setTipoalbum(1);
        return parent::insertComentario($comentario);
    }
    
    /**
     * cria objeto de acesso ao banco de dados
     * @return \Aew_Model_Dao_UsuarioAlbum
     */
    function createDao() {
        return new  Aew_Model_Dao_UsuarioAlbum();
    }

}