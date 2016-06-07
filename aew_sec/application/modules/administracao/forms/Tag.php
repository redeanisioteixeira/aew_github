<?php
/**
 * formulário para cadastro e edição de palavras chaves
 */
class Administracao_Form_Tag extends Sec_Form{
    
    /**
     * cria os campos do formulário
     */
    public function init()
    {
		$conteudoTipoBo = new Aew_Model_Bo_Tag();

		/* Elementos */
		$this->setMethod('post')
		     ->setAttrib('class', 'form-group');

		$id = $this->createElement('hidden', 'idtag');
		$this->addElement($id);
		    
		$nometag = $this->createElement('text','nometag');
		$nometag->setLabel('Tag :')
		                ->setRequired(true)
						->setAttrib('id', 'tags')
		                ->setAttrib('placeholder', 'Digite nome da tag')
						->setAttrib('autocomplete', true)
		                ->setAttrib('class', 'form-control not-comma');
		$this->addElement($nometag);
		    
		$enviar = $this->createElement('submit','Salvar');
		$enviar->setAttrib('class', 'btn btn-default box-loading-ajax')
					->setAttrib('data-message','Salvando tag');
		$this->addElement($enviar);
    }

    /**
     * configura decoração dos campos
     */
    public function configDecorators()
    {
	$element = $this->getElement('nometag');
	$element->getDecorator('label')->setOption('escape', false);
    }
}
