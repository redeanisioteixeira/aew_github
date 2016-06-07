<?php
class ConteudosDigitais_Form_EnviarAmigo extends Sec_Form 
{
    public function init()
    {
	Zend_Loader::loadClass('Zend_Session_Namespace');
	$sessionNamespace = new Zend_Session_Namespace('cptc');

	/* Elementos */
	$this->setMethod('post');
	$this->setAction('/conteudos-digitais/conteudo/enviar-para-amigo');
	$this->setAttrib('enctype', 'multipart/form-data');
	$conteudo = $this->createElement('hidden', 'idconteudodigital');
	$this->addElement($conteudo);
	$nome = $this->createElement('text','nome');
	$nome->setLabel('<span style="font-size:12px">Nome:</span>')
        ->setAttrib('maxlength', 150)
        ->setAttrib( "id", "nomeId" )
	->setRequired(true)
         ->addFilters(array(new Zend_Filter_StringTrim()))
		      ->setAttrib('title', 'Nome do amigo');
        $this->addElement($nome);
        $email = $this->createElement('text','email');
        $email->setLabel('<span style="font-size:12px">E-mail:</span>')
              ->setAttrib('maxlength',100)
            ->setAttrib( "id", "emailId" )
              ->addValidator('EmailAddress', false)
              ->setRequired(true)
              ->addFilters(array(
				new Zend_Filter_StringTrim()
				));
        $this->addElement($email);
        $mensagem = $this->createElement('textarea','mensagem');
        $mensagem->setLabel('<span style="font-size:12px">Mensagem:</span>')
            ->setAttrib( "id", "mensagemId" )->setRequired(true)
            ->addFilters(array(	new Zend_Filter_StringTrim()));
        $this->addElement($mensagem);
	$captcha = $this->createElement('captcha', 'captchaAluno',
        array('required' => true,
        'captcha' => array('captcha' => 'Image',
        'font' => APPLICATION_PATH.DS.'..'.DS.'public'.DS.'fonts'.DS.'freefont/FreeSans.ttf',
        'fontSize' => '24',
        'wordLen' => 5,
        'height' => '50',
        'width' => '120',
        'imgDir' => APPLICATION_PATH.DS.'..'.DS.'public'.DS.'captcha',
        'imgUrl' => Zend_Controller_Front::getInstance()->getBaseUrl().'/captcha',
        'dotNoiseLevel' => 50,
        'lineNoiseLevel' => 5)));
        $captcha->setLabel('Esse desafio é para nos certificar que você é um visitante humano e serve para evitar que envios sejam realizados por scripts automatizados de SPAM.');
        $this->addElement($captcha);
        $enviar = $this->createElement('submit','ok');
        $enviar->setLabel("Enviar")
               ->setIgnore(true)
               ->setAttrib( "id", "enviarAmigoForm-ok" );
	$this->addElement($enviar);
	$this->addButton('ok');
    }

    /**
     * Permite a configuração dos decorators no form
     */
    public function configDecorators()
    {
	$element = $this->getElement('nome');
	$element->getDecorator('label')->setOption('escape', false);
	$element = $this->getElement('email');
	$element->getDecorator('label')->setOption('escape', false);
	$element = $this->getElement('mensagem');
	$element->getDecorator('label')->setOption('escape', false);
    }
}
