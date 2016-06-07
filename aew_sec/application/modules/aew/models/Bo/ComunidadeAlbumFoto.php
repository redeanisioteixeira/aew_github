<?php

/**
 * BO da entidade ComunidadeAlbumFoto
 */

class Aew_Model_Bo_ComunidadeAlbumFoto extends Aew_Model_Bo_ComunidadeFoto
{
    protected $idcomunidadealbum;
    
    /**
     * 
     * @return int
     */
    function getIdcomunidadealbum() {
        return $this->idcomunidadealbum;
    }
    
    /**
     * 
     * @param int $idcomunidadealbum
     */
    function setIdcomunidadealbum($idcomunidadealbum) {
        $this->idcomunidadealbum = $idcomunidadealbum;
    }
    /**
     * retorna o diretorio para as fotos
     * @return string string do caminho para o diretorio das fotos de perfil
     */
    public static function getFotoDirectory()
    {
        if(CONTEUDO_PATH):
            $path = MEDIA_PATH.DS.CONTEUDO_PATH.DS.'fotos'.DS.'comunidade';
        else:
            $path = MEDIA_PATH.DS.'fotos'.DS.'comunidade';
        endif;
        
        return $path;        
    }
    
    /**
     * @return string
     */
    public function uri()
    {
        if(CONTEUDO_PATH):
            $path = DS.CONTEUDO_PATH.DS.'fotos'.DS.'comunidade';
        else:
            $path = DS.'fotos'.DS.'comunidade';
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
        $comentario->setTipoalbum(2);
        $comentario->setTipocomentario(2);
        return parent::insertComentario($comentario);
    }
    
    /**
     * 
     * @param type $num
     * @param type $offset
     * @param Aew_Model_Bo_Usuario $usuarioAutor
     * @param type $options
     * @param type $globalCountRecords
     * @return type
     */
    public function selectComentarios($num = 0, $offset = 0, Aew_Model_Bo_Usuario $usuarioAutor = null,$options=null,$globalCountRecords=false) 
    {
        $comentario = new Aew_Model_Bo_AlbumComentario();
        $comentario->setTipocomentario(2);
        $comentario->setTipoalbum(2);
        $comentario->setIdusuarioalbumfoto($this->getId());
        if($usuarioAutor)
            $comentario->setUsuarioAutor ($usuarioAutor);
        return $comentario->select($num, $offset, $options, $globalCountRecords);
    }
    
    /**
     * cria objeto de acesso ao banco de dados
     * @return \Aew_Model_Dao_ComunidadeAlbumFoto
     */
    public function createDao() {
        return new Aew_Model_Dao_ComunidadeAlbumFoto();
    }
}