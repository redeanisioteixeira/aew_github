<?php

class Aew_Form_Login extends Sec_Form
{
    public function init()
    {
        $this->setMethod('post')
                ->setAction('/home')
                ->setAttrib('id','login-usuario')
                ->setAttrib('role','form')
                ->setAttrib('autocomplete','off')
                ->setAttrib('value','')
                ->setAttrib("class", "padding-left-10 padding-right-10");

        $username = $this->createElement('text','username');
        $username->setLabel('<i class="fa fa-user fa-1x"></i>')
                ->setAttrib('id','login')
                ->setAttrib('autocomplete','off')
                ->setAttrib('value','')
                ->setAttrib('size',80)
                ->setAttrib('class','form-control')
                ->setRequired(true)
                ->setAttrib('placeholder','E-mail ou nome de usuário');
        $this->addElement($username);

        $senha = $this->createElement('password','senha');
        $senha->setLabel('<i class="fa fa-lock fa-1x"></i>')
                ->setAttrib('id','password')
                ->setAttrib('autocomplete','off')
                ->setAttrib('size',40)
                ->setAttrib('class', 'form-control')
                ->setAttrib('placeholder','Senha')
                ->setRequired(true)
                ->setAttrib('autocomplete','off');
        $this->addElement($senha);

        $enviar = $this->createElement('submit','enviar');
        $enviar->setAttrib("class","btn btn-default btn-block")
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
