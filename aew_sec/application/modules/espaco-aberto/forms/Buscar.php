<?php

class EspacoAberto_Form_Buscar extends Sec_Form {

    public function init()
    {
	/* Elementos */
        $this->setMethod('post')
             ->setAttrib('class', 'form-inline load-content-form')
             ->setAttrib('role', 'formularioBusca');
            
        
        $texto = $this->createElement('text','filtro_buscar');
        $texto->setAttrib('id', 'filtro-buscar')
                ->setAttrib('class', 'form-control')
                ->setAttrib('maxlength', '250')
                ->setAttrib('size', '82%')
                ->setAttrib('placeholder', 'buscar...');
        $this->addElement($texto);
        
        $buscar = $this->createElement('submit','submit');
        $buscar->setLabel("Buscar")
               ->setAttrib('id', 'botao-buscar')
               ->setAttrib('class', 'btn btn-default')
               ->setAttrib('name', 'filtrar_busca')
               ->setIgnore(false);
        $this->addElement($buscar);
        
        $this->setAttrib('loadcontent','resultado_busca');
    }
}