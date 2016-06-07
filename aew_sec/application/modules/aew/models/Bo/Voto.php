<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Voto/Avaliaçao feita por um usuario
 *
 * @author tiago-souza
 */
class Aew_Model_Bo_Voto extends Sec_Model_Bo_Abstract
{
    //put your code here
    protected $voto,$usuario,$datacriacao;
    
    function __construct() 
    {
        $this->setUsuario(new Aew_Model_Bo_Usuario());
    }
    
    /**
     * 
     * @return int
     */
    public function getVoto()
    {
        return $this->voto;
    }
    /**
     * 
     * @return string
     */
    public function getDatacriacao()
    {
        return $this->datacriacao;
    }

    /**
     * 
     * @param string $datacriacao
     */
    public function setDatacriacao($datacriacao)
    {
        $this->datacriacao = $datacriacao;
    }
    
    function exchangeArray($data) {
        parent::exchangeArray($data);
        $this->getUsuario()->exchangeArray($data);
    }
    /**
     * 
     * @return array
     */
    function toArray()
    {
        $data = parent::toArray();
        if(($this->getUsuario()) && ($this->getUsuario()->getId()))
        {
            $data['idusuario'] = $this->getUsuario()->getId();
            $this->getDao()->setTableInTableField('idusuario', $this->getDao()->getName());
        }
        return $data;
    }

    /**
     * retorna usuario que inseriu o voto
     * @return Aew_Model_Bo_Usuario 
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * @param int $voto
     */
    public function setVoto($voto)
    {
        $this->voto = $voto;
    }

    /**
     * usuario que inseriu o voto
     * @param Aew_Model_Bo_Usuario $usuario
     */
    public function setUsuario(Aew_Model_Bo_Usuario $usuario)
    {
        $this->usuario = $usuario;
    }

    protected function createDao() {
        
    }

}