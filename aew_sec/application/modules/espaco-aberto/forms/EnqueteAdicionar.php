
<?php

class EspacoAberto_Form_EnqueteAdicionar extends Sec_Form {

    public function init()
    {
        /* Elementos */
        $this->setMethod('post');

        $pergunta = $this->createElement('text','pergunta');
        $pergunta->setLabel('Pergunta: *')
            ->setAttrib('maxlength', 250)
            ->setRequired(true)
            ->addFilters(array(
				new Zend_Filter_StringTrim()
				));
        $this->addElement($pergunta);
        
        for($i=1;$i<7;$i++)
        {
            $opcao = $this->createElement('text','opcao'.$i);
            $opcao->setLabel("Opção $i: *")
                ->setAttrib('maxlength', 250)
                ->setRequired(true)
                ->addFilters(array(	new Zend_Filter_StringTrim()));
            $this->addElement($opcao);
        }
        
        $dataInicio = $this->createElement('dateTime','dataInicio');
        $dataInicio->setLabel('Data de Início: *')
			->setRequired(true)
			->setIsArray(true)
			->addValidator('StringLength', false, array(16,16))
			->addValidator('Date', false, array('format'=>'dd/MM/yyyy HH:mm'))
			->addValidator('DataMenor', false, array('dataFim', 'Data de Término'))
                        ->setAttrib('onChange', 'alteraData()');
        $this->addElement($dataInicio);

        $dataFim = $this->createElement('dateTime','dataFim');
        $dataFim->setLabel('Data de Término: *')
			->setRequired(true)
			->setIsArray(true)
			->addValidator('StringLength', false, array(16,16))
			->addValidator('Date', false, array('format'=>'dd/MM/yyyy HH:mm'));
        $this->addElement($dataFim);

        $enviar = $this->createElement('submit','ok');
        $enviar->setLabel("Enviar")
               ->setAttrib('id', 'btnSubmit-Enviar')
               ->setIgnore(true);
		$this->addElement($enviar);
    }

    /**
     * Popula as opcoes do form
     * @param $values
     */
    public function populate(array $values)
    {
        if(isset($values['enqueteOpcao'])){
            foreach($values['enqueteOpcao'] as $key => $opcao){
                $values['opcao'.($key + 1)] = $opcao['opcao'];
            }
        }

        return parent::populate($values);
    }
}