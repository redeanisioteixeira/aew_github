<?php
/**
 * formulário para edição de senhas
 */
class Administracao_Form_TrocarSenha extends Sec_Form
{
    /**
     * cria os campos do formulário
     */
    public function init()
    {
	/* Elementos */
	$this->setMethod('post');

	$id = $this->createElement('hidden', 'idusuario');
	$this->addElement($id);

	$elem = $this->createElement('password','novaSenha');
	$elem->setLabel('Nova senha:')
		->setAttrib('class', 'form-control')
                ->setAttrib('maxlength', 49)
		->setRequired(true)
		->addFilters(array(new Zend_Filter_StringTrim()))
		->addValidator('IdenticalField', false, array('novaSenha2', 'Repita a nova senha'))
		->addValidator(new Zend_Validate_StringLength(array('min' => 8)));
	$this->addElement($elem);
        
	$elem = $this->createElement('password','novaSenha2');
	$elem->setLabel('Repita a nova senha :')
                ->setAttrib('class', 'form-control')   
                ->setAttrib('maxlength', 49)
                ->setRequired(true)
                ->addFilters(array(new Zend_Filter_StringTrim()))
                ->addValidator(new Zend_Validate_StringLength(array('min' => 8)));
	$this->addElement($elem);
        
	$enviar = $this->createElement('button','salvar');
	$enviar->setLabel('salvar')
                ->setAttrib('type', 'submit')
                ->setAttrib('class', 'btn btn-warning')
                ->setIgnore(true);
	$this->addElement($enviar);
    }

    /**
     * configura decoração dos campos
     */
    public function configDecorators()
    {
	$element = $this->getElement('novaSenha');
	$element->getDecorator('label')->setOption('escape', false);
        
	$element = $this->getElement('novaSenha2');
	$element->getDecorator('label')->setOption('escape', false);
    }
}