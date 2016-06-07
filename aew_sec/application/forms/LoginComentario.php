<?php

class Aew_Form_LoginComentario extends Sec_Form
{
    public function init()
    {
	$this->setMethod('post')
		->setAction('/home')
		->setAttrib('id','login-usuario')
		->setAttrib("class", 'form-usuario');
        
	$login = $this->createElement('text','username');
	$login->setLabel('<i class="fa fa-user"></i> Usuário:')
		->setAttrib('class','form-control margin-bottom-10')
                ->setAttrib('placeholder','E-mail ou nome de usuário')
                ->setRequired(true)
		->setAttrib('size',40);
	$this->addElement($login);
	$senha = $this->createElement('password','senha');
	$senha->setLabel('<i class="fa fa-lock"></i> Senha:')
		->setAttrib('class','form-control margin-bottom-10')
                ->setAttrib('placeholder','Senha')
                ->setRequired(true)
		->setAttrib('size',40)
		->setAttrib('autocomplete','off');
	$this->addElement($senha);
        
	$enviar = $this->createElement('submit','enviar');
	$enviar->setAttrib("class","btn btn-default pull-right")
		->setIgnore(true);
	$this->addElement($enviar);
    }

    public function configDecorators()
    {
	$element = $this->getElement('username');
	$element->getDecorator('label')->setOption('escape', false);
        
	$element = $this->getElement('senha');
	$element->getDecorator('label')->setOption('escape', false);
    }
}