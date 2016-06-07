<?php
class EspacoAberto_Form_RecadoAdicionar extends Sec_Form {

    public function init()
    {
        /* Elementos */
        $this->setMethod('post')
                ->setAttrib('id', 'formrecado')
                ->setAttrib('role', 'formulario');

	$id = $this->createElement('hidden', 'idrecado');
        $this->addElement($id);

	$idRelacionado = $this->createElement('hidden', 'idrecadorelacionado');
        $this->addElement($idRelacionado);
        
        $texto = $this->createElement('richText','recado');
        $texto->setLabel('Escreva seu recado :')
                ->setRequired(true)
                ->setAttrib('placeholder', 'Deixe seu recado aqui...')
                ->setAttrib('autocomplete', 'off')
                ->setAttrib('class', 'form-control')
                ->setAttrib('rows', '4');
	$this->addElement($texto);
        
        
	$tipoRecadoArray = array(1 => "Público", 2 => "Privado", 3 => "Apenas para colegas");
	$tipoRecado = $this->createElement('select','tiporecado');
	$tipoRecado->setAttrib('id', 'tipoRecado')
                    ->setLabel('Tipo de recado :')
                    ->setAttrib('class', 'form-control')
                    ->setMultiOptions($tipoRecadoArray);
	$this->addElement($tipoRecado);
        
	$enviar = $this->createElement('submit','salvar');
	$enviar->setAttrib('id', 'submit')
                ->setAttrib('class', 'btn btn-primary')
		->setIgnore(true);
    	$this->addElement($enviar);
    }

    public function configDecorators()
    {
        $element = $this->getElement('recado');
        $element->getDecorator('label')->setOption('escape', false);
        
        $element = $this->getElement('salvar');
        $element->getDecorator('description')->setOption('escape', false);
        
    }
}