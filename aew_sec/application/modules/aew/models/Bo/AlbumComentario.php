<?php

/**
 * BO da entidade AgendaComentario
 */

class Aew_Model_Bo_AlbumComentario extends Aew_Model_Bo_Comentario implements Sec_OrdenavelPorData
{
    protected $idusuarioalbumfoto; //int(11)
    protected $tipocomentario; //int(11)
    protected $tipoalbum; //int(11)
    protected $visto; //tinyint(1)
    
    /**
     * 
     * @return int
     */
    public function getIdusuarioalbumfoto() 
    {
        return $this->idusuarioalbumfoto;
    }

    /**
     * @return int
     */
    public function getTipocomentario() 
    {
        return $this->tipocomentario;
    }

    /**
     * @return int
     */
    public function getTipoalbum() 
    {
        return $this->tipoalbum;
    }

    /**
     * @return boolean
     */
    public function getVisto() 
    {
        return $this->visto;
    }

    /**
     * 
     * @param int $idalbumcomentario
     */
    public function setIdalbumcomentario($idalbumcomentario) 
    {
        $this->idalbumcomentario = $idalbumcomentario;
    }

    /**
     * 
     * @param int $idusuarioalbumfoto
     */
    public function setIdusuarioalbumfoto($idusuarioalbumfoto) 
    {
        $this->idusuarioalbumfoto = $idusuarioalbumfoto;
    }

    /**
     * @param int $tipocomentario
     */
    public function setTipocomentario($tipocomentario) 
    {
        $this->tipocomentario = $tipocomentario;
    }
    
    /**
     * @param int $tipoalbum
     */
    public function setTipoalbum($tipoalbum) {
        $this->tipoalbum = $tipoalbum;
    }

    /**
     * 
     * @param boolean $visto
     */
    public function setVisto($visto) {
        $this->visto = $visto;
    }

    /**
     * @param Aew_Model_Bo_Usuario $usuario
     * @return string
     */
    function getUrlApagar(Aew_Model_Bo_ItemPerfil $usuario)
    {
        if(!$usuario)
        return;
        if(($usuario->getId()==$this->getIdusuarioalbumfoto())||
          ($usuario->getId()==$this->getUsuarioAutor()->getId())||     
          ($usuario->isSuperAdmin()))
        return '/espaco-aberto/album/apagarcomentario/id/'.$this->getIdusuarioalbumfoto().'/idcomentario/'.$this->getId();
    }
    
    /**
     * 
     * @return string
     */
    public function getData()
    {
        return $this->getDataCriacao();
    }

    /**
     * 
     * @param type $num
     * @param type $offset
     * @param Aew_Model_Bo_Usuario $usuarioAutor
     * @return type
     */
    public function selectComentariosRelacionados($num = 0, $offset = 0, Aew_Model_Bo_Usuario $usuarioAutor=null)
    {
        return $this->getComentariosRelacionados();
    }

    /**
     * cria objeto de acesso ao banco de dados
     * @return \Aew_Model_Dao_AlbumComentario
     */
    protected function createDao() {
        return new Aew_Model_Dao_AlbumComentario();
    }

}
