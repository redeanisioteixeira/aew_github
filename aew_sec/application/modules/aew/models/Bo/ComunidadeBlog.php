<?php

/**
 * BO da entidade ComunidadeBlog
 */

class Aew_Model_Bo_ComunidadeBlog extends Aew_Model_Bo_Blog
{
    protected $idcomunidade; //int(11)
    
    /**
     * @return idcomunidade - int(11)
     */
    public function getIdcomunidade(){
	return $this->idcomunidade;
    }

    /**
     * @param Type: int(11)
     */
    public function setIdcomunidade($idcomunidade){
    	$this->idcomunidade = $idcomunidade;
    }

    /**
     * retorna a foto do perfil (nada, não apagar!!)
     */
    public function selectFotoPerfil() {
        
    }

    /**
     * 
     * @return string
     */
    public function perfilTipo() {
        return 'blog';
    }

    /**
     * nao implementado
     * @param type $num
     * @param type $offset
     * @param Aew_Model_Bo_Album $album
     */
    public function selectAlbuns($num = 0, $offset = 0, Aew_Model_Bo_Album $album = null)
    {                                                   
        
    }

    /**
     * nao implementado
     * @param type $num
     * @param type $offset
     * @param Aew_Model_Bo_Blog $blog
     */
    public function selectBlogs($num = 0, $offset = 0, Aew_Model_Bo_Blog $blog = null)
    {
        
    }

    /**
     * 
     * @param type $num
     * @param type $offset
     * @param Aew_Model_Bo_Usuario $usuario
     * @return type
     */
    public function selectComentarios($num = 0, $offset = 0, Aew_Model_Bo_Usuario $usuario = null)
    {
        $comentario = new Aew_Model_Bo_BlogComentario();
        $comentario->setTipo(Sec_Constante::COMUNIDADE);
        $comentario->setIdblog($this->getId());
        if($usuario)
        $comentario->setUsuarioAutor($usuario);
        $this->setComentarios($comentario->select($num, $offset));
        return $this->getComentarios();
    }

    /**
     * metodo nao implementado
     * @param Aew_Model_Bo_Foto $foto
     */
    public function saveFotoPerfil(Aew_Model_Bo_Foto $foto)
    {
        
    }

    /**
     * metodo nao implementado
     * @param Aew_Model_Bo_ItemPerfil $avaliador
     * @param type $voto
     */
    public function insertVoto(Aew_Model_Bo_ItemPerfil $avaliador, $voto)
    {
        
    }

    /**
     * metado nao implementado
     * @param type $num
     * @param type $offset
     * @param Aew_Model_Bo_ItemPerfil $avaliador
     */
    public function selectVotos($num = 0, $offset = 0,Aew_Model_Bo_ItemPerfil $avaliador = null)
    {
        
    }
    
    /**
     * 
     * @return string
     */
    public function getLinkPerfil()
    {
        return '/espaco-aberto/blog/exibir/comunidade/'.$this->getIdcomunidade().'/id/'.$this->getId();
    }
    
    /**
     * cria objeto de acesso ao banco de dados
     * @return \Aew_Model_Dao_ComunidadeBlog
     */
    public function createDao() {
        return new Aew_Model_Dao_ComunidadeBlog();
    }
}
                                     