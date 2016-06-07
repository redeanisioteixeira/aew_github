<?php

class EspacoAberto_Form_CadastroColaborativo extends Sec_Form
{
    public function init()
    {
	//$formatoBo = new Aew_Model_Bo_Formato();
	$conteudoExtensions = 'AVI, MP4, WEBM'; //$formatoBo->getList();
	$conteudoMaxSize = '40MB';
	/* Elementos */
	$this->setMethod('post');
	$this->setAction('/espaco-aberto/colaborativo/participar');
	$this->setAttrib('class','form-participar');
	$this->addElement('hidden', 'tipo', array('value' => 'servidor'));
	$matricula = $this->createElement('text','matricula');
	$matricula->setLabel('<span>Nº Matrícula :</span>')
                  ->setAttrib('readonly', '');
	$this->addElement($matricula);
	$nome = $this->createElement('text','nome');
	$nome->setLabel('<span>Nome :</span>')
             ->setAttrib('readonly', '');
	$this->addElement($nome);
	$cpf = $this->createElement('text','cpf');
	$cpf->setLabel('<span>CPF :</span>')
			->setAttrib('readonly', '');
	$this->addElement($cpf);
	$nascimento = $this->createElement('text', 'datanascimento');
	$nascimento->setLabel('<span>Data de nascimento :</span>')
		   ->setAttrib('readonly', '');
	$this->addElement($nascimento);
	$email = $this->createElement('text','email');
	$email->setLabel('<span>E-mail :</span>')
			->setAttrib('readonly', '');
	$this->addElement($email);
	$ficha = $this->createElement('file','ficha');
	$ficha->setLabel('<span>Anexar ficha de inscrição :</span>')
		->setDescription('<span>Após clicar, localize no seu computador a <strong>ficha de inscrição preenchida</strong> a ser enviada.</span>')
		->setDestination(ConteudoDigital::getColaborativoDownloadDirectory())
                ->addValidator('Extension', false, 'doc')
                ->addValidator('Size', false, $conteudoMaxSize)
		->addValidator('Count', false, 1)
		->setRequired(true)
		->setValueDisabled(true);
	$this->addElement($ficha);
	$video = $this->createElement('file','video');
	$video->setLabel('<span>Anexar arquivo de vídeo :</span>')
		->setDescription("<span>Após clicar, localize no seu computador o arquivo de vídeo a ser enviado. O arquivo deve ser em formato <strong>.MP4</strong> ou <strong>.AVI</strong> (tamanho máximo permitido <strong>$conteudoMaxSize</strong>)</span>")
		->setDestination(ConteudoDigital::getColaborativoDownloadDirectory())
                ->addValidator('Extension', false, 'avi, mp4')
                ->addValidator('Size', false, $conteudoMaxSize)
		->addValidator('Count', false, 1)
		->setRequired(true)
		->setValueDisabled(true);
	$this->addElement($video);
	$recaptcha = new Zend_Service_ReCaptcha(Sec_Constante::KEYPUBLIC,Sec_Constante::KEYPRIVATE);
	$captcha = new Zend_Form_Element_Captcha('challenge',array('captcha'=> 'ReCaptcha',
						'captchaOptions' => array('captcha' => 'ReCaptcha', 'service' => $recaptcha)));
	$recaptcha_pt_translation =array('visual_challenge' => "Confira o texto",
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
	$captcha->setLabel('<span>Código de segurança :</span>')
			->setDescription("<span>Esse desafio é para nos certificar que você é um visitante humano e serve para evitar que envios sejam realizados por scripts automatizados de SPAM.</span>")
			->setRequired(true);
	$this->addElement($captcha);
	$termoVal = new Zend_Validate_GreaterThan(0);
	$termoVal->setMessage('Você deve aceitar o termo e condições do regulamento para continuar com o cadastro', Zend_Validate_GreaterThan::NOT_GREATER);
	$termouso = $this->createElement('checkbox','termouso');
	$termouso->setLabel("<span>Li e concordo com os <strong><a target='_blank' href='/conteudos/colaborativo/regulamento.pdf'>termos e condições do regulamento</a></strong></span>")
			->setRequired(true)
			->addValidator($termoVal)
			->setAttrib('id', 'termouso')
			->setAttrib('class', 'termouso')
			->setAttrib('title', 'Termos e condições de uso')
			->setChecked(false);
	$this->addElement($termouso);
	$enviar = $this->createElement('submit','enviar');
	$enviar->setLabel("Enviar")
		->setAttrib('class','botao-colorbox')
		->setIgnore(true);
	$this->addElement($enviar);
	$this->addButton('enviar');
    }

    public function configDecorators()
    {
	$element = $this->getElement('matricula');
	$element->getDecorator('label')->setOption('escape', false);
	$element = $this->getElement('nome');
	$element->getDecorator('label')->setOption('escape', false);
	$element = $this->getElement('cpf');
	$element->getDecorator('label')->setOption('escape', false);
	$element = $this->getElement('datanascimento');
	$element->getDecorator('label')->setOption('escape', false);
	$element = $this->getElement('ficha');
	$element->getDecorator('label')->setOption('escape', false);
	$element = $this->getElement('ficha');
	$element->getDecorator('description')->setOption('escape', false);
	$element = $this->getElement('video');
	$element->getDecorator('label')->setOption('escape', false);;
	$element = $this->getElement('video');
	$element->getDecorator('description')->setOption('escape', false);
	$element = $this->getElement('email');
	$element->getDecorator('label')->setOption('escape', false);
	$element = $this->getElement('email');
	$element->getDecorator('description')->setOption('escape', false);
	$element = $this->getElement('challenge');
	$element->getDecorator('label')->setOption('escape', false);
	$element = $this->getElement('challenge');
	$element->getDecorator('description')->setOption('escape', false);
	$element = $this->getElement('termouso');
	$element->getDecorator('label')->setOption('escape', false);
	$element = $this->getElement('termouso');
	$element->getDecorator('description')->setOption('escape', false);
    }
}