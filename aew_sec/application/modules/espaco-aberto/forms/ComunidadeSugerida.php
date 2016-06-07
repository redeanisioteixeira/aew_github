<?php

class EspacoAberto_Form_ComunidadeSugerida extends Sec_Form {

    public function init()
    {
        $colegas = array(0 => 'Sem Colegas');
	
	$this->setMethod('post');
        $this->setAction('/espaco-aberto/comunidade/sugerir');
	
        $idcomunidade = $this->createElement('hidden','idcomunidade');
        $idcomunidade->setRequired(true);
        $this->addElement($idcomunidade);
        
        $select = $this->createElement('select','colegas');
        $select->setMultiOptions($colegas)
                ->setRequired(true)
                ->setAttrib('class', 'tokenize')
                ->setAttrib('multiple', 'multiple');
        $this->addElement($select);
        
        $enviar = $this->createElement('submit','enviarConvite');
	$enviar->setLabel("Enviar convite")
                ->setAttrib('class', 'btn btn-sm btn-primary pull-right')
                ->setDescription('<div class="resp padding-all-05 pull-left"></div>')
                ->setIgnore(true);
	$this->addElement($enviar);
    }

    public function configDecorators()
    {
        parent::configDecorators();
        $element = $this->getElement('enviarConvite');
        $element->getDecorator('description')->setOption('escape', false);
        
    }
    /**
     * Validate the form
     *
     * @param  array $data
     * @return boolean
     */
    public function isValid($data)
    {
	return parent::isValid($data);
    }
}
