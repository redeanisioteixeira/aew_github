<?php
class Aew_Form_Faleconosco extends Sec_Form
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
        
        $this->setMethod('post');
        $this->setAction("/aew/home/faleconosco")
                ->setAttrib('id','contactform');

        $nome = $this->createElement('text','nome');
        $nome->setLabel('Nome:')
                ->setAttrib('maxlength',100)				
                ->setAttrib('class', 'form-control')
                ->setAttrib('placeholder','Digite seu nome')
                ->setRequired(true);
        
        if($usuario){
            $nome->setValue($usuario->getNome())
                    ->setAttrib('readonly','readonly');
        }
        
        $this->addElement($nome);

        $email = $this->createElement('text','email');
        $email->setLabel('E-mail:')
                ->setAttrib('maxlength',250)				
                ->setAttrib('class', 'form-control')
                ->setAttrib('placeholder','Digite seu e-mail')
                ->addValidator('EmailAddress', false)
                ->setRequired(true)
                ->addFilter('StringToLower');

        if($usuario){
            $email->setValue($usuario->getEmail())
                    ->setAttrib('readonly','readonly');
        }
        
        $this->addElement($email);

        $titulo = $this->createElement('text','assunto');
        $titulo->setLabel('Assunto:')
                ->setAttrib('maxlength',200)
                ->setAttrib('class', 'form-control')
                ->setAttrib('placeholder','Quál é o assunto do contato?')
                ->setRequired(true)
                ->addFilters(array(new Zend_Filter_StringTrim()));
        $this->addElement($titulo);

        $mensagem = $this->createElement('textarea','mensagem');
        $mensagem->setLabel('Mensagem:')
                    ->setAttrib('rows', '4')
                    ->setAttrib('cols', '75')
                    ->setAttrib('class', 'form-control')
                    ->setAttrib('placeholder','Digite aqui o sua mensagem')
                    ->setRequired(true)
                    ->addFilters(array(new Zend_Filter_StringTrim()));
        $this->addElement($mensagem);

        $url = $this->createElement('text','url');
        $url->setLabel('URL:')
            ->setAttrib('maxlength',250)
            ->setAttrib('class', 'form-control')
            ->setAttrib('readonly','readonly')
            ->setValue($urlRef)
            ->addFilters(array(new Zend_Filter_StringTrim()));

        if(!$urlRef)
            $url->setAttrib('class', 'desativado')
                ->setLabel("");
        $this->addElement($url);
        
        // --- Cria recaptcha
        $recaptcha = New Sec_View_Helper_ReCaptcha();
        $recaptcha = $recaptcha->addCaptcha($this);
        
        $enviar = $this->createElement('submit','enviar');
        $enviar->setLabel("Enviar")
                ->setIgnore(true)
                ->setAttrib('class','btn btn-primary')
                                ;
        $this->addElement($enviar);
    }

    public function configDecorators()
    {
        $element = $this->getElement('nome');
        $element->getDecorator('label')->setOption('escape', false);

        $element = $this->getElement('email');
        $element->getDecorator('label')->setOption('escape', false);

        $element = $this->getElement('assunto');
        $element->getDecorator('label')->setOption('escape', false);

        $element = $this->getElement('url');
        $element->getDecorator('label')->setOption('escape', false);

        $element = $this->getElement('mensagem');
        $element->getDecorator('label')->setOption('escape', false);
            
        $element = $this->getElement('recaptcha');
        $element->getDecorator('description')->setOption('escape', false);
    }
}