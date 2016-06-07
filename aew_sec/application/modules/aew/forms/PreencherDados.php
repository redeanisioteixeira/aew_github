<?php
/**
 * Dados da matricula
 */
class Aew_Form_PreencherDados extends Sec_Form
{

    /**
     * inicializa campos do formulario
     */
    public function init()
    {
        /* Elementos */
        $this->setMethod('post');
        $this->setAction('/usuario/preencher-dados');

        $matriculas = $this->createElement('select','matriculas');
        $matriculas->setLabel('Matrícula:')
                   ->setRegisterInArrayValidator(false);
        $matriculas->addFilters(array(
				new Zend_Filter_StringTrim()
				));
		$this->addElement($matriculas);

        $enviar = $this->createElement('submit','enviar');
        $enviar->setLabel("Enviar")
               ->setIgnore(true);
		$this->addElement($enviar);

        $this->addButton('enviar');
    }

    public function addMatricula($id, $matricula)
    {
        $element = $this->getElement('matriculas');
        $element->addMultiOption($id, $matricula);
    }
}