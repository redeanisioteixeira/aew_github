<?php
/**
 * formulário para trocar senha de acesso
 */
class EspacoAberto_Form_TrocarSenha extends Sec_Form
{
    public function init()
    {
        /* Elementos */
        $this->setMethod('post')
             ->setAttrib('id', 'trocar-senha');

        $this->setAttrib('action', 'espaco-aberto/perfil/salvar-senha');
        
        $elem = $this->createElement('password','senhaAtual');
        $elem->setLabel('Senha atual :')
            ->setAttrib('class', 'form-control')
            ->setAttrib('placeholder', 'Senha atual')
            ->setAttrib('required', 'true')    
            ->setAttrib('maxlength', 49)
            ->setRequired(true)
            ->addFilters(array(	new Zend_Filter_StringTrim()));
        $this->addElement($elem);

        $elem = $this->createElement('password','novaSenha');
        $elem->setLabel('Nova senha :')
            ->setAttrib('class', 'form-control')
            ->setAttrib('placeholder', 'Nova senha')
            ->setAttrib('required', 'true')    
            ->setAttrib('maxlength', 49)
            ->setRequired(true)
            ->addFilters(array(	new Zend_Filter_StringTrim()))
		    ->addValidator('IdenticalField', false, array('novaSenha2', 'Repita a nova senha'))
		    ->addValidator(new Zend_Validate_StringLength(array('min' => 8)));
        $this->addElement($elem);

        $elem = $this->createElement('password','novaSenha2');
        $elem->setLabel('Repita a nova senha :')
            ->setAttrib('class', 'form-control')
            ->setAttrib('placeholder', 'Repita sua nova senha')
            ->setAttrib('required', 'true')     
            ->setAttrib('maxlength', 49)
            ->setRequired(true)
            ->addFilters(array(	new Zend_Filter_StringTrim()))
			->addValidator(new Zend_Validate_StringLength(array('min' => 8)));
        $this->addElement($elem);

        $enviar = $this->createElement('submit','Salvar');
        $enviar->setAttrib('class', 'btn btn-default') 
               ->setAttrib('id', 'btnSubmit-Enviar')
               ->setIgnore(true);
		$this->addElement($enviar);
    }
}