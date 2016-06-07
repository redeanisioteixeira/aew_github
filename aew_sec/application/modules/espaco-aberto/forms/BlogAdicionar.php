<?php

class EspacoAberto_Form_BlogAdicionar extends Sec_Form 
{
    public function init()
    {
        /* Elementos */
        $this->setMethod('post');
        
        $idblog = $this->createElement('hidden','idblog');
        $this->addElement($idblog);
        
        $titulo = $this->createElement('text','titulo');
        $titulo->setLabel('Titulo:')
            ->setAttrib('maxlength', 250)
            ->setAttrib('class', 'form-control')
            ->setAttrib('required', 'true')     
            ->setRequired(true)
            ->addFilters(array(	new Zend_Filter_StringTrim()));
        $this->addElement($titulo);
        
        
        $texto = $this->createElement('richText','texto');
        $texto->setLabel('Descrição:')
            ->setAttrib('class', 'form-control')
            ->setAttrib('rows', 5)  
            ->setRequired(true)
            ->addFilters(array(	new Zend_Filter_StringTrim()));
        $this->addElement($texto);
        
        $enviar = $this->createElement('submit','enviar');
        $enviar->setLabel("Enviar")
               ->setAttrib('class', 'btn btn-default')  
               ->setIgnore(true);
	$this->addElement($enviar);
    }

    public function configDecorators()
    {
	$element = $this->getElement('titulo');
	$element->getDecorator('label')->setOption('escape', false);
        
	$element = $this->getElement('texto');
	$element->getDecorator('label')->setOption('escape', false);
    }
}