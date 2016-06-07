<?php
/**
 * formulário para cadastro e edição de formatos
 */
class Administracao_Form_Formato extends Sec_Form{
    
    /**
     * cria os campos do formulário
     */
    public function init()
    {
	$conteudoTipoBo = new Aew_Model_Bo_ConteudoTipo();
        $options = array();
        $options['orderBy'] = array('conteudotipo.nomeconteudotipo');
	$conteudoTipoArray = $conteudoTipoBo->getAllForSelect('idconteudotipo', 'nomeconteudotipo', 'Selecione', null, $options);

	/* Elementos */
	$this->setMethod('post')
             ->setAttrib('class', 'form-group');

	$id = $this->createElement('hidden', 'idformato');
	$this->addElement($id);
        
	$nomeformato = $this->createElement('text','nomeformato');
	$nomeformato->setLabel('Formato :')
                    ->setRequired(true)
                    ->setAttrib('placeholder', 'Digite o formato')
                    ->setAttrib('class', 'form-control')
                    ->setAttrib('maxlength', 10);
	$this->addElement($nomeformato);
        
	$conteudoTipo = $this->createElement('select','idconteudotipo');
	$conteudoTipo->setLabel('Tipo de conteúdo :')
                    ->setRequired(true)
                    ->setAttrib('class', 'form-control')
                    ->setMultiOptions($conteudoTipoArray);
	$this->addElement($conteudoTipo);
        
	$enviar = $this->createElement('submit','Salvar');
	$enviar->setAttrib('class', 'btn btn-default');
        $this->addElement($enviar);
    }

    /**
     * configura decoração dos campos
     */
    public function configDecorators()
    {
	$element = $this->getElement('nomeformato');
	$element->getDecorator('label')->setOption('escape', false);
        
	$element = $this->getElement('idconteudotipo');
	$element->getDecorator('label')->setOption('escape', false);
    }
}