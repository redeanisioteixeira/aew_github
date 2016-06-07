<?php

class EspacoAberto_Form_TopicoAdicionar extends Sec_Form {

    public function init()
    {
        /* Elementos */
        $this->setMethod('post');

        $id = $this->createElement('hidden', 'idcomutopico');
        $id->addFilters(array(
				new Zend_Filter_Int()
				));
        $this->addElement($id);

        $titulo = $this->createElement('text','titulo');
        $titulo->setLabel('Titulo: ')
            ->setAttrib('maxlength', 250)
            ->setAttrib('class','form-control')
            ->setAttrib('required','true')    
            ->setRequired(true)
            ->addFilters(array(
				new Zend_Filter_StringTrim()
				));
        $this->addElement($titulo);

        $texto = $this->createElement('richText','mensagem');
        $texto->setLabel('Mensagem: ')
            ->setRequired(true)
            ->setAttrib('class','form-control')    
            ->addFilters(array(
				new Zend_Filter_StringTrim(),
				new Sec_Filter_Xss()
				));
        $this->addElement($texto);

        $enviar = $this->createElement('button','ok');
        $enviar->setLabel("Enviar")
               ->setAttrib('id', 'btnSubmit-Enviar')
               ->setAttrib('class', 'btn btn-default')
               ->setAttrib('onclick', 'form.submit()')
               ->setIgnore(true);
		$this->addElement($enviar);
    }
}