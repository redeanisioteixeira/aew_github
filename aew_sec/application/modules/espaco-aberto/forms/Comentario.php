<?php

class EspacoAberto_Form_Comentario extends Sec_Form {

    public function init()
    {
        /* Elementos */
        $this->setMethod('post');
        $this->setAction('/espaco-aberto/foto/comentar');
        $idfoto = $this->createElement('hidden', 'idfoto');
        $idfoto->addFilters(array(new Zend_Filter_Int()));
        $this->addElement($idfoto);
        $id = $this->createElement('hidden', 'idcomentario');
        $id->addFilters(array(new Zend_Filter_Int()));
        $this->addElement($id);
        
        
        
        $tipocomentario = $this->createElement('hidden', 'tipocomentario');
        $tipocomentario->addFilters(array(new Zend_Filter_Int()));
        $this->addElement($tipocomentario);
        $texto = $this->createElement('richText','mensagem');
        $texto->setRequired(true)
            ->setAttrib('class', 'form-control')
            ->setAttrib('placeholder', 'Escreva um Comentário')
            ->setAttrib('cols', '100')
            ->setAttrib('rows', '4')
            ->addFilters(array(	new Zend_Filter_StringTrim(), new Sec_Filter_Xss()));
        $this->addElement($texto);
        $enviar = $this->createElement('submit','ok');
        $enviar->setLabel("Enviar")
                ->setAttrib('class', 'btn btn-default')
               ->setAttrib('id', 'btnSubmit-Enviar')
               ->setIgnore(true);
	$this->addElement($enviar);
    }

}
