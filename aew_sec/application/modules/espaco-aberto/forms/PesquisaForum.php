<?php

class EspacoAberto_Form_PesquisaForum extends Sec_Form {

    public function init()
    {
        /* Elementos */
        $this->setMethod('post');
        $this->setAction('/espaco-aberto/pesquisa');

        $texto = $this->createElement('text','buscaForum');
        $texto->setRequired(true)
            ->setAttrib('placeholder','Busca dentro do fórum... ')
            ->setAttrib('class', 'form-control')    
            ->setAttrib('maxlength', 100)
            ->setAttrib('style', 'font-size:13px; width:150px; float:left')
            ->addFilters(array(
				new Zend_Filter_StringTrim()
				));
        $this->addElement($texto);

        $enviar = $this->createElement('submit','ok');
        $enviar->setLabel("Ok")
        		->setAttrib('style', 'font-size:13px; float:left ')
               ->setAttrib('class', 'btn btn-default') 
               ->setAttrib('id', 'btnSubmit-EnviarForum')
               ->setIgnore(true);
		$this->addElement($enviar);
    }
}