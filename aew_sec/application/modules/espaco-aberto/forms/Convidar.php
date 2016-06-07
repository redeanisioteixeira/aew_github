<?php

class EspacoAberto_Form_Convidar extends Sec_Form {

    public function init()
    {
        /* Elementos */
        $this->setMethod('post');

        $texto = $this->createElement('textarea','emails');
        $texto->setLabel('Convidar para a Rede (emails separados por vírgula)')
		->setAttrib('style', 'width:130px; height:70px; font-size: 120%;')
                ->setAttrib('class', 'form-control');
        $this->addElement($texto);

        $enviar = $this->createElement('submit','ok');
        $enviar->setLabel("Enviar")
               ->setAttrib('class', 'btn btn-default') 
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
