<?php

class EspacoAberto_Form_FotoAdicionar extends Sec_Form 
{
    public function init()
    {
        $fotoExtension = 'jpg,jpeg,gif,png';
        $fotoMaxSize = '1mb';
        /* Elementos */
        $this->setMethod('post');
        $legenda = $this->createElement('text','legenda');
        $legenda->setLabel('Legenda:')
            ->setAttrib('class', 'form-control')    
            ->setAttrib('maxlength', 250)
            ->addFilters(array(	new Zend_Filter_StringTrim()));
        $this->addElement($legenda);
        $foto = $this->createElement('file','foto');
        $foto->setLabel('Foto: *')
            ->setDestination(Aew_Model_Bo_UsuarioFoto::getFotoDirectory())
            ->addValidator('Extension', false, $fotoExtension)
            ->addValidator('Size', false, $fotoMaxSize)
            ->setAttrib('class','form-control')
            ->setValueDisabled(true)
            ->setRequired(true);
        $this->addElement($foto);
        $enviar = $this->createElement('submit','ok');
        $enviar->setLabel("Enviar")
               ->setAttrib('class', 'btn btn-default') 
               ->setAttrib('id', 'btnSubmit-Enviar')
               ->setIgnore(true);
	$this->addElement($enviar);
    }
}