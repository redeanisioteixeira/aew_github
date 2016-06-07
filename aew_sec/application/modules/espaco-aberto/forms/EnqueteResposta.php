<?php

class EspacoAberto_Form_EnqueteResposta extends Sec_Form {

    public function init()
    {
        /* Elementos */
        $this->setMethod('post');

        $id = $this->createElement('hidden', 'idenquete');
        $id->addFilters(array(new Zend_Filter_Int()));
        $this->addElement($id);

        $resposta = $this->createElement('radio','idenqueteopcao');
        /* @var $resposta Zend_Form_Element_Radio */
        $resposta->setLabel('Resposta: *')
            ->setRequired(true)
            ->setRegisterInArrayValidator(false);
        $this->addElement($resposta);

        $enviar = $this->createElement('submit','ok');
        $enviar->setLabel("Enviar")
               ->setAttrib('id', 'btnSubmit-Enviar')
               ->setIgnore(true);
	$this->addElement($enviar);
    }

    /**
     * Adiciona as resposta ao formulário
     * @param $respostas
     */
    public function adicionarRespostas($respostas)
    {
        $options = array();
        foreach($respostas as $resposta){
            $options[$resposta['idenqueteopcao']] = $resposta['opcao'];
        }
        $respostaElement = $this->getElement('idenqueteopcao');
        $respostaElement->setMultiOptions($options);
    }
}