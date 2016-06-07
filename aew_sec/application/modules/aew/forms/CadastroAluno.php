<?php

/**
 * formulário de cadastro de alunos 
 */
class Aew_Form_CadastroAluno extends Sec_Form
{
    /**
     * cria os campos do formulario
     */
    public function init()
    {
        /* Elementos */
	$this->setMethod('post');
	$this->setAction('/usuario/cadastro');
	$this->addElement('hidden', 'tipo', array('value' => 'aluno'));
	$matricula = $this->createElement('text','matricula');
	$matricula->setLabel('<span>Matricula:</span>')
		->setAttrib('maxlength', 15)
		->setAttrib('size',15)
		->setRequired(true);
        $this->addElement($matricula);
	$sexo = $this->createElement('select','sexo');
	$sexo->setLabel('<span>Sexo:</span>')
				->setRequired(true)
				->setMultiOptions(array('m'=>'Masculino' ,'f'=>'Feminino'));
	$this->addElement($sexo);
	$nascimento = $this->createElement('text', 'nascimentoAluno');
	$nascimento->setLabel('<span>Data de nascimento:</span>')
		->setAttrib('size', 10)
		->setAttrib('maxlength',10)
		->setAttrib('onKeyPress', 'mascaraData(this,\'nascimentoAluno\')')
		->setAttrib('onKeyUp', 'mascaraData(this,\'nascimentoAluno\')')
                ->setAttrib('onKeyDown', 'mascaraData(this,\'nascimentoAluno\')')
		->addValidator('StringLength', false, array(10,10))
		->addValidator('Date', false, array('format'=>'dd/MM/yyyy'))
		->setRequired(true);
	$this->addElement($nascimento);
	$email = $this->createElement('text','email');
	$email->setLabel('<span>E-mail:</span>')
		->setAttrib('size',35)
		->setAttrib('maxlength',100)
		->addValidator('EmailAddress', true)
		->setRequired(true);
	$email->addFilters(array(
			new Zend_Filter_StringTrim()
			));
	$this->addElement($email);
	$recaptcha = new Zend_Service_ReCaptcha(Sec_Constante::KEYPUBLIC,Sec_Constante::KEYPRIVATE);
	$captcha = new Zend_Form_Element_Captcha('challenge',array('captcha'=> 'ReCaptcha',
						'captchaOptions' => array('captcha' => 'ReCaptcha', 'service' => $recaptcha)));
	$recaptcha_pt_translation = array('visual_challenge' => "Confira o texto",
					'audio_challenge' => "Confira o áudio",
					'refresh_btn' => "Fazer uma nova verificação",
					'instructions_visual' => "Digite as duas palavras",
					'instructions_audio' => "Escreva o que você ouve",
					'help_btn' => "Ajuda",
					'play_again' => "Repoduzir o áudio novamente",
					'cant_hear_this' => "Baixar arquivo de áudio como MP3",
					'incorrect_try_again' => "Tente novamente",
					'errors'=> "Valor do captcha está errado");
	$recaptcha->setOption('custom_translations', $recaptcha_pt_translation);
	$recaptcha->setOption('theme', 'white');
	$captcha->setLabel("<span>Esse desafio é para nos certificar que você é um visitante humano e serve para evitar que envios sejam realizados por scripts automatizados de SPAM.</span>")
		->setAttrib('style','float:left')
		->setRequired(true);
	$this->addElement($captcha);
	$enviar = $this->createElement('submit','enviar');
	$enviar->setLabel("Enviar")
		->setIgnore(true)
		->setAttrib("style","float:right")
		->setAttrib('class','botao-colorbox');
	$this->addElement($enviar);
	$this->addButton('enviar');
    }

    /**
     * insere elementos decorativos nas tags
     */
    public function configDecorators()
    {
	$element = $this->getElement('matricula');
	$element->getDecorator('label')->setOption('escape', false);
	$element = $this->getElement('sexo');
	$element->getDecorator('label')->setOption('escape', false);
	$element = $this->getElement('nascimentoAluno');
	$element->getDecorator('label')->setOption('escape', false);
	$element = $this->getElement('email');
	$element->getDecorator('label')->setOption('escape', false);
	$element = $this->getElement('challenge');
	$element->getDecorator('label')->setOption('escape', false);
    }
}