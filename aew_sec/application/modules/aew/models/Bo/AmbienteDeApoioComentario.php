<?php

/**
 * BO da entidade AmbienteDeAPoioComentario
 */

class Aew_Model_Bo_AmbienteDeApoioComentario extends Aew_Model_Bo_Comentario
{
    
    protected $idambientedeapoio; //int(11)
    
    /**
     * 
     * @return int
     */
    public function getIdambientedeapoio() {
        return $this->idambientedeapoio;
    }

    /**
     * @param int $idambientedeapoio
     */
    public function setIdambientedeapoio($idambientedeapoio) {
        $this->idambientedeapoio = $idambientedeapoio;
    }

    /**
     * metodo nao implementado
     * @param type $num
     * @param type $offset
     * @param \Aew_Model_Bo_Usuario $usuarioAutor
     */
    public function selectComentariosRelacionados($num = 0, $offset = 0, \Aew_Model_Bo_Usuario $usuarioAutor = null)
    {
        
    }

    /**
     * url para apagar comentario
     * @param Aew_Model_Bo_Usuario $usuario
     * @return string
     */
    public function getUrlApagar(Aew_Model_Bo_ItemPerfil $usuario=null) 
    {
        if($usuario)
        if($this->isAutor($usuario)||$usuario->isAdmin())
        return '/ambientes-de-apoio/comentario/apagar/id/'.$this->getId ().'/idambientedeapoio/'.$this->getIdambientedeapoio();
    }

    /**
     * cria objeto de acesso ao banco de dados
     * @return \Aew_Model_Dao_AmbienteDeApoioComentario
     */
    protected function createDao() {
        return new Aew_Model_Dao_AmbienteDeApoioComentario();
    }

}