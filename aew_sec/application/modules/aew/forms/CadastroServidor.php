<?php
/**
 * cadastro formulario do servidor (colaborador da rede publica)
 */
class Aew_Form_CadastroServidor extends Sec_Form {

    /**
     * inicializa campos do formulario
     */
    public function init()
    {
        /* Elementos */
        $this->setMethod('post');
        $this->setAction('/usuario/cadastro');
        $this->addElement('hidden', 'tipo', array('value' => 'servidor'));
        
        $cpf = $this->createElement('text','cpf');
        $cpf->setLabel('CPF:')
            ->setDescription('<small>Somente digite numéros, sem ponto separador (.) nem traço (-)</small>')
            ->setAttrib('maxlength', 11)
            ->setAttrib('class', 'form-control')
            ->setAttrib('size',11)
            ->setAttrib('placeholder', 'Digite seu CPF')
            ->setAttrib('required', true) // HTML5 valida no browser                
            ->setRequired(true);
        $this->addElement($cpf);
        
        $sexo = $this->createElement('select','sexo');
        $sexo->setLabel('Sexo:')
            ->setRequired(true)
            ->setAttrib('class', 'form-control')
            ->setAttrib('required', true) // HTML5 valida no browser                
            ->setMultiOptions(array('m'=>'Masculino' ,'f'=>'Feminino'));
        $this->addElement($sexo);
        
	$nascimento = $this->createElement('text', 'nascimentoServidor');
	$nascimento->setLabel('Data de nascimento:')
            ->setAttrib('size', 10)
            ->setAttrib('maxlength',10)
            ->setAttrib('class', 'form-control')
            ->setAttrib('placeholder', 'Digite data de nascimento no formato dd/mm/aaaa')
            ->setAttrib('id','nascimentoServidor')
            ->setAttrib('required', true) // HTML5 valida no browser
            ->addValidator('StringLength', false, array(10,10))
            ->addValidator('Date', false, array('format'=>'dd/MM/yyyy'))
            ->setRequired(true);
	$this->addElement($nascimento);
        
	$email = $this->createElement('text','email');
	$email->setLabel('E-mail:')
            ->setAttrib('size',35)
            ->setAttrib('maxlength',100)
            ->setAttrib('class', 'form-control')
            ->setAttrib('placeholder', 'Digite e-mail que será utilizado como login de usuário')
            ->setAttrib('required', true) // HTML5 valida no browser
            ->addValidator('EmailAddress', true)
            ->setRequired(true)
            ->addFilters(array(new Zend_Filter_StringTrim()))
            ->addValidator('IdenticalField', false, array('confirm_email', 'Confirmar e-mail'))
            ->addValidator(new Zend_Validate_StringLength(array('min' => 8)));
	$this->addElement($email);
        
	$confirmarEmail = $this->createElement('text', 'confirm_email');
	$confirmarEmail->setLabel('Confirmar e-mail:')
            ->setRequired(true)
            ->setAttrib('size',35)
            ->setAttrib('class', 'form-control')
            ->setAttrib('placeholder', 'Repita o e-mail digitado acima')
            ->setAttrib('maxlength',100)
            ->setAttrib('required', true) // HTML5 valida no browser
            ->addValidator('EmailAddress', true)
            ->addValidator(new Zend_Validate_Identical(Zend_Controller_Front::getInstance()->getRequest()->getParam('email')));
        $this->addElement($confirmarEmail);
    
        $termoVal = new Zend_Validate_GreaterThan(0);
        $termoVal->setMessage('Você deve aceitar o termo e condições de uso para continuar com o cadastro', Zend_Validate_GreaterThan::NOT_GREATER);
        $termouso = $this->createElement('checkbox','termouso');
        $termouso->setLabel("<span>Li e concordo com os <a href='/home/termo-condicoesuso'>termos e condições de uso</a></span>")
            ->setRequired(true)
            ->addValidator($termoVal)
            ->setAttrib('id', 'termouso')
            ->setAttrib('title', 'Termos e condições de uso')
            ->setChecked(false);
        $this->addElement($termouso);
        
        $recaptcha = New Sec_View_Helper_ReCaptcha();
        $recaptcha = $recaptcha->addCaptcha($this);
        
        $enviar = $this->createElement('submit','enviar');
        $enviar->setLabel("Enviar")
            ->setAttrib('class', 'btn btn-default')
            ->setIgnore(true);
        $this->addElement($enviar);
    }

    /**
     * cria elementos decorativos do formulario (tal qual rotulos)
     */
    public function configDecorators()
    {
        $element = $this->getElement('cpf');
        $element->getDecorator('label')->setOption('escape', false);
        $element->getDecorator('description')->setOption('escape', false);

        $element = $this->getElement('sexo');
        $element->getDecorator('label')->setOption('escape', false);

        $element = $this->getElement('nascimentoServidor');
        $element->getDecorator('label')->setOption('escape', false);

        $element = $this->getElement('email');
        $element->getDecorator('label')->setOption('escape', false);

        $element = $this->getElement('termouso');
        $element->getDecorator('label')->setOption('escape', false);
        
        $element = $this->getElement('recaptcha');
        $element->getDecorator('description')->setOption('escape', false);
    }
}
