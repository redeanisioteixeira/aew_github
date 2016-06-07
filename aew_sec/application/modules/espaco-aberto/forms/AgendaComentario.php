<?php

class EspacoAberto_Form_AgendaComentario extends Sec_Form {

    public function init()
    {
        /* Elementos */
        $this->setMethod('post');
        
        $id = $this->createElement('hidden', 'tipoAgenda');
        $id->addFilters(array(
				new Zend_Filter_Int()
				));
        $this->addElement($id);

        $texto = $this->createElement('richText','mensagem');
        $texto
            ->setRequired(true)
            ->setAttrib('cols', '110')
            ->setAttrib('rows', '2')
            ->addFilters(array(
				new Zend_Filter_StringTrim(),
                                new Sec_Filter_Xss()
				));
        $this->addElement($texto);

        $enviar = $this->createElement('submit','ok');
        $enviar->setLabel("Enviar")
               ->setAttrib('id', 'btnSubmit-Enviar')
               ->setIgnore(true);
		$this->addElement($enviar);
    }

}