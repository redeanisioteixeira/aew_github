<?php

class EspacoAberto_Form_AgendaAdicionar extends Sec_Form {

    public function init()
    {
        /* Elementos */
        $this->setMethod('post');

        $titulo = $this->createElement('text','evento');
        $titulo->setLabel('Evento: *')
            ->setAttrib('maxlength', 250)
            ->setRequired(true)
            ->addFilters(array(
				new Zend_Filter_StringTrim()
				));
        $this->addElement($titulo);

        $texto = $this->createElement('richText','mensagem');
        $texto->setLabel('Mensagem: *')
            ->setRequired(true)
            ->setAttrib('cols', '110')
            ->setAttrib('rows', '2')
            ->addFilters(array(
				new Zend_Filter_StringTrim(),
                                new Sec_Filter_Xss()
				));
        $this->addElement($texto);

        $titulo = $this->createElement('text','local');
        $titulo->setLabel('Local:')
            ->setAttrib('maxlength', 200)
            ->addFilters(array(
				new Zend_Filter_StringTrim()
				));
        $this->addElement($titulo);

        $texto = $this->createElement('textarea','marcacao');
        $texto->setLabel('Adicionar colegas ao evento  (Aperte "@" para cada colega que deseja buscar para adicionar)')
        		->setAttrib('style', 'height: 15px;');
        $this->addElement($texto);

        $dataInicio = $this->createElement('dateTime','dataInicio');
        $dataInicio->setLabel('Data de Início: *')
			->setRequired(true)
			->setIsArray(true)
			->addValidator('StringLength', false, array(16,16))
			->addValidator('Date', false, array('format'=>'dd/MM/yyyy HH:mm'))
			->addValidator('DataMenor', false, array('dataFim', 'Data de Término'))
			->setAttrib('onChange', 'alteraData()');
        $this->addElement($dataInicio);

        $dataFim = $this->createElement('datetime','dataFim');
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
     * Validate the form
     *
     * @param  array $data
     * @return boolean
     */
    public function isValid($data)
    {

        return parent::isValid($data);
    }

    /**
     * Cria o campo ID de acordo com o perfil
     */
    public function criarCampoId($tipo)
    {
        if($tipo == Sec_Constante::USUARIO){
            $nome = 'idUsuarioAgenda';
        } elseif($tipo == Sec_Constante::COMUNIDADE){
            $nome = 'idComunidadeAgenda';
        } else {
            return;
        }

        $id = $this->createElement('hidden', $nome);
        $id->addFilters(array(
				new Zend_Filter_Int()
				));
        $this->addElement($id);
    }
}