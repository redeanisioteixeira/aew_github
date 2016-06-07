<?php

class EspacoAberto_Form_AlbumAdicionar extends Sec_Form 
{
    public function init()
    {
        /* Elementos */
        $this->setMethod('post');
        $titulo = $this->createElement('text','titulo');
        $titulo->setLabel('Titulo: *')
            ->setAttrib('class', 'form-control')
            ->setAttrib('placeholder', 'Escreva um título para seu album')
            ->setAttrib('required', 'true')     
            ->setAttrib('maxlength', 150)
            ->setRequired(true)
            ->addFilters(array(new Zend_Filter_StringTrim()));
        $this->addElement($titulo);
        $enviar = $this->createElement('submit','ok');
        $enviar->setLabel("Enviar")
                ->setAttrib('class', 'btn btn-default')
               ->setAttrib('id', 'btnSubmit-Enviar')
               ->setIgnore(true);
	$this->addElement($enviar);
        $id = $this->createElement('hidden', 'idalbum');
        $id->addFilters(array(new Zend_Filter_Int()));
        $this->addElement($id);
    }
    
}