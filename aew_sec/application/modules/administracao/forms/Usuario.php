<?php

/**
 * formulário para cadastro e edição de usaurios
 */
class Administracao_Form_Usuario extends Sec_Form {

    /**
     * cria os campos do formulário
     */
    public function init()
    {
	$tipoUsuarioBo = new Aew_Model_Bo_UsuarioTipo();
	$tipoUsuarioArray = $tipoUsuarioBo->getAllForSelect('idusuariotipo', 'nomeusuariotipo', 'Selecione');
        
	$estadoBo = new Aew_Model_Bo_Estado();
	$estadoArray = $estadoBo->getAllForSelect('idestado', 'nomeestado', 'Selecione');

	$serieBo = new Aew_Model_Bo_Serie();
	$serieArray = $serieBo->getAllForSelect('idserie', 'nomeserie', 'Selecione');

	$escolaBo = new Aew_Model_Bo_Escola();
	$escolaArray = $escolaBo->getAllForSelect('idescola', 'nomeescola', 'Selecione');

	$municipioBo = new Aew_Model_Bo_Municipio();
	$municipioArray = $escolaBo->getAllForSelect('idmunicipio', 'nomemunicipio', 'Selecione');
        
	/* Elementos */
	$this->setMethod('post');

	$id = $this->createElement('hidden', 'idusuario');
	$this->addElement($id);
        
	$login = $this->createElement('text','username');
	$login->setLabel('Login :')
                ->setRequired(true)
		->setAttrib('maxlength', 100)
                ->setAttrib('class', 'form-control')
                ->setAttrib('placeholder', 'Digite login do usuário')
                ->setDescription('<small class="text-info">Pode utilizar uma conta de e-mail como login (exemplo: aew.usuario@gmail.com, aew.usuario@hotmail.com)</small>')
		->addFilters(array(new Zend_Filter_StringTrim()));
	$this->addElement($login);

	$nome = $this->createElement('text','nomeusuario');
	$nome->setLabel('Nome :')
             ->setAttrib('class', 'form-control')
             ->setAttrib('placeholder', 'Digite nome completo do usuário')   
	     ->setRequired(true)
	     ->addFilters(array(new Zend_Filter_StringTrim()));
	$this->addElement($nome);

	$tipo = $this->createElement('select','idusuariotipo');
	$tipo->setLabel('Tipo usuário :')
             ->setRequired(true)
             ->setDescription('<small class="text-info">Selecione tipo de usuário:<ul><li><b>Super adminitrador : </b> Equipe técnica responsável pelo AEW</li><li><b>Administrador : </b> Coordenadores do AEW</li><li><b>Coordenador : </b> Grupo de gestores pedagógicos do AEW</li><li><b>Editor : </b> Professores e diretores da rede estadual</li><li><b>Colaborador : </b> Alunos da rede estadual</li><li><b>Amigo da escola : </b> Colaboradores externos que não pertencem à rede estadual</li></ul></small>')
             ->setAttrib('class', 'form-control')   
             ->setMultiOptions($tipoUsuarioArray);
	$this->addElement($tipo);
        
	$radio = $this->createElement('radio', 'sexo');
	$radio->setLabel('Sexo :')
	      ->setRequired(true)
              ->addMultiOptions(array('m' => 'MASCULINO', 'f' => 'FEMININO'))
	      ->setSeparator(' - ');
	$this->addElement($radio);
        
        $dataNascimento = $this->createElement('date','datanascimento');
	$dataNascimento->setLabel('Data de nascimento :')
		       ->setRequired(true)
                       ->setAttrib('class', 'form-control')
		       ->addValidator('Date', false, array('date'=>'dd/mm/yyyy'));
 
	$this->addElement($dataNascimento);
        
	$email = $this->createElement('text','email');
	$email->setLabel('E-mail Institucional :')
                ->setRequired(true)
                ->setAttrib('class', 'form-control')  
                ->setAttrib('maxlength', 150)
                ->addFilters(array(new Zend_Filter_StringTrim()));
	$this->addElement($email);
        
	$emailPessoal = $this->createElement('text','emailpessoal');
	$emailPessoal->setLabel('E-mail Pessoal :')
                    ->setRequired(true)
                    ->setAttrib('class', 'form-control')
                    ->setAttrib('maxlength', 150)
                    ->addFilters(array(new Zend_Filter_StringTrim()));
	$this->addElement($emailPessoal);

	$cpf = $this->createElement('text','cpf');
	$cpf->setLabel('CPF :')
            ->setAttrib('class', 'form-control')    
	    ->setAttrib('maxlength', 15)
	    ->addFilters(array(	new Zend_Filter_StringTrim()));
	$this->addElement($cpf);
        
	$rg = $this->createElement('text','rg');
	$rg->setLabel('RG :')
           ->setAttrib('class', 'form-control')     
	   ->setAttrib('maxlength', 20)
	   ->addFilters(array(new Zend_Filter_StringTrim()));
	$this->addElement($rg);
        
	$telefone = $this->createElement('text','telefone');
	$telefone->setLabel('Telefone :')
		 ->setAttrib('class', 'form-control') 
                 ->setAttrib('maxlength', 15)
		 ->addFilters(array(new Zend_Filter_StringTrim()));
	$this->addElement($telefone);
        
	$endereco = $this->createElement('text','endereco');
	$endereco->setLabel('Endereço :')
                 ->setAttrib('class', 'form-control')
		 ->setAttrib('maxlength', 250)
		 ->addFilters(array(new Zend_Filter_StringTrim()));
		$this->addElement($endereco);

	$numero = $this->createElement('text','numero');
	$numero->setLabel('Numero :')
                        ->setAttrib('class', 'form-control')
			->setAttrib('maxlength', 15)
			->addFilters(array(new Zend_Filter_StringTrim()));
	$this->addElement($numero);
        
	$complemento = $this->createElement('text','complemento');
	$complemento->setLabel('Complemento :')
                    ->setAttrib('class', 'form-control')
                    ->setAttrib('maxlength', 100)
                    ->addFilters(array(new Zend_Filter_StringTrim()));
	$this->addElement($complemento);
        
	$estado = $this->createElement('select','idestado');
	$estado->setLabel('Estado :')
                ->setAttrib('class', 'form-control select-dinamic')
                ->setAttrib('rel', '/administracao/usuario/municipios')
                ->setAttrib('idloadcontainer', 'idmunicipio')
               ->setAttrib('type-action', 'html-action')
               ->setMultiOptions($estadoArray);
	$this->addElement($estado);
        
	$municipio = $this->createElement('select','idmunicipio');
	$municipio->setLabel('Municipio :')
		->setRegisterInArrayValidator(false)
		->setMultiOptions($municipioArray)
                ->setAttrib('disabled', 'true')
                ->setAttrib('class', 'form-control');
	$this->addElement($municipio);

	$bairro = $this->createElement('text','bairro');
	$bairro->setLabel('Bairro :')
                ->setAttrib('class', 'form-control')
		->setAttrib('maxlength', 100)
		->addFilters(array(new Zend_Filter_StringTrim()));
	$this->addElement($bairro);
        
	$cep = $this->createElement('text','cep');
	$cep->setLabel('CEP :')
		->setAttrib('maxlength', 9)
                ->setAttrib('class', 'form-control')
		->addFilters(array(new Zend_Filter_StringTrim()));
	$this->addElement($cep);
        
	$escola = $this->createElement('select','idescola');
	$escola->setLabel('Escola :')
		->setAttrib('id', 'escola')
                ->setAttrib('class', 'form-control')
		->setMultiOptions($escolaArray);
	$this->addElement($escola);
        
	$serie = $this->createElement('select','idserie');
	$serie->setLabel('Serie :')
		->setAttrib('id', 'serie')
                ->setAttrib('class', 'form-control')
		->setMultiOptions($serieArray);
	$this->addElement($serie);

        $flativo = $this->createElement('checkbox', 'flativo');
        $flativo->setLabel('Ativo :')
                ->setCheckedValue('t')
                ->setUncheckedValue('f')
                ->setAttrib('title', 'Status usuário');
        $this->addElement($flativo);
        
	$enviar = $this->createElement('submit','Salvar');
	$enviar->setAttrib('class', 'btn btn-default');
        
	$this->addElement($enviar);
    }

    /**
     * configura decoração dos campos
     */
    public function configDecorators()
    {
	$element = $this->getElement('idusuariotipo');
	$element->getDecorator('label')->setOption('escape', false);
	$element->getDecorator('description')->setOption('escape', false);
        
	$element = $this->getElement('nomeusuario');
	$element->getDecorator('label')->setOption('escape', false);
	$element->getDecorator('description')->setOption('escape', false);
        
	$element = $this->getElement('username');
	$element->getDecorator('label')->setOption('escape', false);
	$element->getDecorator('description')->setOption('escape', false);
        
	$element = $this->getElement('sexo');
	$element->getDecorator('label')->setOption('escape', false);
	$element->getDecorator('description')->setOption('escape', false);
	
        $element = $this->getElement('datanascimento');
	$element->getDecorator('label')->setOption('escape', false);
        $element->getDecorator('description')->setOption('escape', false);
	
        $element = $this->getElement('cpf');
	$element->getDecorator('label')->setOption('escape', false);
	$element->getDecorator('description')->setOption('escape', false);
	
        $element = $this->getElement('rg');
	$element->getDecorator('label')->setOption('escape', false);
	$element->getDecorator('description')->setOption('escape', false);
	
        $element = $this->getElement('email');
	$element->getDecorator('label')->setOption('escape', false);
	$element->getDecorator('description')->setOption('escape', false);
	
        $element = $this->getElement('emailpessoal');
	$element->getDecorator('label')->setOption('escape', false);
	$element->getDecorator('description')->setOption('escape', false);
	
        $element = $this->getElement('telefone');
	$element->getDecorator('label')->setOption('escape', false);
	$element->getDecorator('description')->setOption('escape', false);
	
        $element = $this->getElement('endereco');
	$element->getDecorator('label')->setOption('escape', false);
	$element->getDecorator('description')->setOption('escape', false);
	
        $element = $this->getElement('numero');
	$element->getDecorator('label')->setOption('escape', false);
	$element->getDecorator('description')->setOption('escape', false);
	
        $element = $this->getElement('complemento');
	$element->getDecorator('label')->setOption('escape', false);
	$element->getDecorator('description')->setOption('escape', false);
	
        $element = $this->getElement('bairro');
	$element->getDecorator('label')->setOption('escape', false);
	$element->getDecorator('description')->setOption('escape', false);
	
        $element = $this->getElement('idestado');
	$element->getDecorator('label')->setOption('escape', false);
	$element->getDecorator('description')->setOption('escape', false);
	
        $element = $this->getElement('idmunicipio');
	$element->getDecorator('label')->setOption('escape', false);
	$element->getDecorator('description')->setOption('escape', false);
	
        $element = $this->getElement('cep');
	$element->getDecorator('label')->setOption('escape', false);
	$element->getDecorator('description')->setOption('escape', false);
	
        $element = $this->getElement('idescola');
	$element->getDecorator('label')->setOption('escape', false);
	$element->getDecorator('description')->setOption('escape', false);
	
        $element = $this->getElement('idserie');
	$element->getDecorator('label')->setOption('escape', false);
	$element->getDecorator('description')->setOption('escape', false);
    }
}