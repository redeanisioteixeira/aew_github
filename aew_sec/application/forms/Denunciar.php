<?php

class Aew_Form_Denunciar extends Sec_Form
{
    public function init()
    {
        $usuario = Sec_Controller_Action::getLoggedUserObject();
        $request = Zend_Controller_Front::getInstance();

        $urlRef = $request->getRequest()->getHeader('REFERER');
        if($urlRef)
        {
            $uri = $request->getRequest()->getHeader('URI');
            if(strpos($urlRef,$uri))
            {
                $urlRef = "";
            }
        }
        
        /* Elementos */
        $this->setMethod('post');
        $this->setAction('/aew/home/denunciar')
                ->setAttrib('id','contactform');

        $nome = $this->createElement('text','nome');
        $nome->setLabel('Nome:')
                ->setAttrib('maxlength',100)				
                ->setAttrib('class', 'form-control')
                ->setAttrib('placeholder','Digite seu nome');
        
        if($usuario){
            $nome->setValue($usuario->getNome())
                    ->setRequired(true)
                    ->setAttrib('readonly','readonly');
        }
        
        $this->addElement($nome);

        $email = $this->createElement('text','email');
        $email->setLabel('E-mail:')
                ->setAttrib('maxlength',250)				
                ->setAttrib('class', 'form-control')
                ->setAttrib('placeholder','Digite seu e-mail')
                ->addValidator('EmailAddress', false)
                ->addFilter('StringToLower');

        if($usuario){
            $email->setValue($usuario->getEmail())
                    ->setRequired(true)
                    ->setAttrib('readonly','readonly');
        }
        
        $this->addElement($email);
        
        $url = $this->createElement('text','url');
        $url->setLabel('URL:')
                ->setAttrib('maxlength',250)
                ->setAttrib('class', 'form-control')
                ->setAttrib('placeholder', 'Digite a URL relacionada à denuncia')
                ->setRequired(true)
                ->setValue($urlRef)
                ->addFilters(array(
                        new Zend_Filter_StringTrim()
                ));
        
        if($urlRef):
            $url->setAttrib('readonly','readonly');
        endif;
        
        $this->addElement($url);
        
        $titulo = $this->createElement('text','assunto');
        $titulo->setLabel('Assunto:')
                ->setAttrib('maxlength',250)
                ->setAttrib('class', 'form-control')
                ->setAttrib('placeholder', 'Digite o assunto da denuncia')
                ->setRequired(true)
                ->addFilters(array(
                        new Zend_Filter_StringTrim()
                                ));
        $this->addElement($titulo);

        $mensagem = $this->createElement('textarea','mensagem');
        $mensagem->setLabel('Mensagem:')
                ->setAttrib('rows', '5')
                ->setAttrib('cols', '75')
                ->setAttrib('class', 'form-control')
                ->setAttrib('placeholder', 'Escreva a sua denuncia')
                ->setRequired(true)
                ->addFilters(array(
                        new Zend_Filter_StringTrim()
                ));
        $this->addElement($mensagem);

        // --- Cria recaptcha
        $recaptcha = New Sec_View_Helper_ReCaptcha();
        $recaptcha = $recaptcha->addCaptcha($this);

        $enviar = $this->createElement('submit','enviar');
        $enviar->setLabel("Enviar")
                                ->setIgnore(true)
                                ->setAttrib('class', 'btn btn-default');
        $this->addElement($enviar);
    }

    public function configDecorators()
    {
        $element = $this->getElement('assunto');
        $element->getDecorator('label')->setOption('escape', false);

        $element = $this->getElement('mensagem');
        $element->getDecorator('label')->setOption('escape', false);

        $element = $this->getElement('url');
        $element->getDecorator('label')->setOption('escape', false);

        $element = $this->getElement('recaptcha');
        $element->getDecorator('description')->setOption('escape', false);
    }

    /**
    * Seta o valor da URL
    * @param string $value
    */
    public function setUrl($value)
    {
            $this->getElement('url')->setValue($value)->setAttrib('readonly', 'readonly');
    }
}
