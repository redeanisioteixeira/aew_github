<?php

class EspacoAberto_Form_BlogComentario extends Sec_Form {

    public function init()
    {
        /* Elementos */
        $this->setMethod('post');

        $id = $this->createElement('hidden', 'tipo');
        $id->addFilters(array(	new Zend_Filter_Int()));
        $this->addElement($id);
        $texto = $this->createElement('richText','mensagem');
        $texto
            ->setRequired(true)
            ->setAttrib('class', 'form-control')    
            ->setAttrib('cols', '100')
            ->setAttrib('rows', '4')
            ->addFilters(array(	new Zend_Filter_StringTrim(), new Sec_Filter_Xss()));
        $this->addElement($texto);
        $enviar = $this->createElement('submit','ok');
        $enviar->setLabel("Enviar")
               ->setAttrib('class', 'btn btn-default btn-block') 
               ->setAttrib('id', 'btnSubmit-Enviar')
               ->setIgnore(true);
		$this->addElement($enviar);
    }

}
