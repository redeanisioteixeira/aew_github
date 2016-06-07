<?php

class EspacoAberto_Form_AdicionaMarcacao extends Sec_Form {

    public function init()
    {
        /* Elementos */
        $this->setMethod('post');

        $id = $this->createElement('hidden', 'tipoAgenda');
        $id->addFilters(array(
				new Zend_Filter_Int()
				));
        $this->addElement($id);

        $texto = $this->createElement('textarea','marcacao');
        $texto->setLabel('Adicionar colegas ao evento (Aperte "@" para cada colega que deseja buscar para adicionar)');
        $this->addElement($texto);

        $enviar = $this->createElement('submit','ok');
        $enviar->setLabel("Enviar")
               ->setAttrib('id', 'btnSubmit-Enviar')
               ->setIgnore(true);
		$this->addElement($enviar);
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