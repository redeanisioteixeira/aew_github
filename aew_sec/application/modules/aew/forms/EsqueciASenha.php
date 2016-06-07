<?php
/**
 * formulário de edicao de senha
 */
class Aew_Form_EsqueciASenha extends Sec_Form 
{
    public function init()
    {
        /* Elementos */
        $this->setMethod('post');
        $this->setAction('/usuario/esqueci-a-senha');

        $email = $this->createElement('text','email');
        $email->setLabel('E-mail:')
              ->setAttrib('size',42)
              ->setAttrib('class', 'form-control')
              ->setAttrib('required', true)
              ->setAttrib('maxlength',100)
              ->addValidator('EmailAddress', true)
              ->setRequired(true);
        $this->addElement($email);
        
        $recaptcha = New Sec_View_Helper_ReCaptcha();
        $recaptcha = $recaptcha->addCaptcha($this);
       
        $enviar = $this->createElement('submit','enviar');
        $enviar->setLabel("Enviar")
               ->setAttrib("class","btn btn-default")
               ->setIgnore(true);
        
        $this->addElement($enviar);
        $this->addButton('enviar');
    }

    public function configDecorators()
    {
        $element = $this->getElement('email');
        $element->getDecorator('label')->setOption('escape', false);

        $element = $this->getElement('recaptcha');
        $element->getDecorator('description')->setOption('escape', false);
    }
}