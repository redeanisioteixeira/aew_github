<?php

class AmbientesDeApoio_Form_Comentario extends ConteudosDigitais_Form_Comentario {

    public function __construct(\Aew_Model_Bo_Usuario $usuario = null, $options = null) 
    {
        parent::__construct($usuario, $options);
    }
    /**
     * adicionar comentarios
     */
    public function init()
    {
        /* Elementos */
        parent::init();
        
        $this->setMethod('post');
        $this->setAction('/ambientes-de-apoio/comentario/adicionar');
        $ambiente = $this->createElement('hidden', 'idambientedeapoio');
        $ambiente->setRequired(true);
        $this->getElement('idconteudodigital')->setRequired(false);
        $this->addElement($ambiente);
    }
}