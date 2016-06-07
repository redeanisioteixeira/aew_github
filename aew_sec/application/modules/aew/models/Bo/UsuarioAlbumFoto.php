<?php
/**
 * BO da entidade UsuarioAlbumFoto
 */
class Aew_Model_Bo_UsuarioAlbumFoto extends Aew_Model_Bo_UsuarioFoto
{
    protected $flperfil; //tinyint(4)
    
    /**
     * 
     * @return int
     */
    public function getIdusuarioalbum() {
        return $this->idusuarioalbum;
    }

    /**
     * 
     * @return boolean
     */
    public function getFlperfil() {
        return $this->flperfil;
    }

    /**
     * 
     * @param int $idusuarioalbum
     */
    public function setIdusuarioalbum($idusuarioalbum) {
        $this->idusuarioalbum = $idusuarioalbum;
    }

    /**
     * 
     * @param boolean $flperfil
     */
    public function setFlperfil($flperfil) {
        $this->flperfil = $flperfil;
    }

    /**
     * @param string $datacriacao
     */
    public function setDatacriacao($datacriacao) {
        $this->datacriacao = $datacriacao;
    }
    
    /**
     * retorna o diretorio para as fotos
     * @return string string do caminho para o diretorio das fotos de perfil
     */
    public static function getFotoDirectory()
    {
        if(CONTEUDO_PATH):
            $path = MEDIA_PATH.DS.CONTEUDO_PATH.DS.'fotos'.DS.'usuario';
        else:
            $path = MEDIA_PATH.DS.'fotos'.DS.'usuario';
        endif;
        
        return $path;
    }
    
    function toArray() {
        $data = parent::toArray();
        $this->getDao()->setTableInTableField('idusuario', 'usuario');
        return $data;
    }
    /**
     * caminho para as fotos do usuario
     * @return string
     */
    public function uri()
    {
        if(CONTEUDO_PATH):
            $path = DS.CONTEUDO_PATH.DS.'fotos'.DS.'usuario';
        else:
            $path = DS.'fotos'.DS.'usuario';
        endif;
        
        return $path;        
    }
    
    /**
     * 
     * @param Aew_Model_Bo_AlbumComentario $comentario
     * @return int
     */
    function insertComentario(Aew_Model_Bo_AlbumComentario $comentario) 
    {
        $comentario->setTipoalbum(1);
        $comentario->setTipocomentario(2);
        return parent::insertComentario($comentario);
    }
    
    /**
     * 
     * @param int $num
     * @param int $offset
     * @param Aew_Model_Bo_Usuario $usuarioAutor
     * @param int $options
     * @param int $globalCountRecords
     * @return array
     */
    public function selectComentarios($num = 0, $offset = 0, Aew_Model_Bo_Usuario $usuarioAutor = null,$options=null,$globalCountRecords=false) 
    {
        $comentario = new Aew_Model_Bo_AlbumComentario();
        $comentario->setTipocomentario(2);
        $comentario->setTipoalbum(1);
        $comentario->setIdusuarioalbumfoto($this->getId());
        if($usuarioAutor)
            $comentario->setUsuarioAutor ($usuarioAutor);
        return $comentario->select($num, $offset, $options, $globalCountRecords);
    }
    
    /**
     * cria objeto de acesso ao banco de dados
     * @return \Aew_Model_Dao_UsuarioAlbumFoto
     */
    function createDao() {
        return new Aew_Model_Dao_UsuarioAlbumFoto();
    }
}