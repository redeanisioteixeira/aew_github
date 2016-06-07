<?php

/**
 * BO da entidade AmbienteDeApoioFavorito
 * representa um relacionamento entre ambiente de apoio 
 * e um outro conteudo favorito
 */

class Aew_Model_Bo_AmbienteDeApoioFavorito extends Aew_Model_Bo_AmbienteDeApoio
{
    protected $idambientedeapoio;
    
    /**
     * 
     * @return int
     */
    function getIdambientedeapoio() {
        return $this->idambientedeapoio;
    }

    /**
     * 
     * @param int $idambientedeapoio
     */
    function setIdambientedeapoio($idambientedeapoio) {
        $this->idambientedeapoio = $idambientedeapoio;
    }

    /**
     * 
     * @param boolean $urlAbsoluta
     * @return string
     */
    function getLinkPerfil($urlAbsoluta = false) {
        return '/ambientes-de-apoio/ambiente/exibir/id/'.$this->getIdambientedeapoio();
    }
    
    /**
     * cria objeto de acesso ao banco de dados
     * @return Aew_Model_Dao_AmbienteDeApoioFavorito
     */
    protected function createDao() {
        return new Aew_Model_Dao_AmbienteDeApoioFavorito();
    }   

}