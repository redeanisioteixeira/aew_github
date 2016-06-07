<?php
/**
 * formulário de cadastro de amigo da escola
 */
class Administracao_Form_AmigoDaEscola extends Sec_Form 
{
    /**
     * inicializa os campos do formulario
     */
    public function init()
    {
        $estadoBo = new Aew_Model_Bo_Estado();
        $estadoArray = $estadoBo->getAllForSelect('idestado', 'nomeestado', 'Selecione...');
        /* Elementos*/
        $this->setMethod('post');
        $this->setAction('/administracao/amigo-da-escola/salvar');

        $id = $this->createElement('hidden', 'categoria');
        $id->setValue('c');
        $this->addElement($id);
        $nome = $this->createElement('text','nome');
        $nome->setLabel('Nome:')
                ->setAttrib('class', 'form-control')
                ->setAttrib('placeholder', 'Escreva o nome completo do usuário')
                ->setAttrib('maxlength', 150)
                ->setRequired(true)
                ->addFilters(array(new Zend_Filter_StringTrim()));
        $this->addElement($nome);

        $apelido = $this->createElement('text','username');
        $apelido->setLabel('Login:')
                ->setAttrib('class', 'form-control') 
                ->setAttrib('placeholder', 'Digite login do usuário. Pode utilizar uma conta de e-mail como login (exemplo: fulano@gmail.com, fulano@hotmail.com)')
                ->setAttrib('maxlength', 100)
                ->setRequired(true)
                ->addFilters(array(new Zend_Filter_StringTrim()));
        $this->addElement($apelido);
        
        $radio = $this->createElement('radio', 'sexo');
        $radio->setLabel('Sexo:')
                ->setAttrib('id', 'sexo')
                ->setRequired(true)
                ->addMultiOptions(array('m' => 'Masculino', 'f' => 'Feminino'))
                ->setSeparator('');
        $this->addElement($radio);

        $dataNascimento = $this->createElement('text','datanascimento');
        $dataNascimento->setLabel('Nascimento:')
                ->setRequired(true)
		->setAttrib('size', 10)
                ->setAttrib('class', 'form-control')
		->setAttrib('maxlength',10)
		->addValidator('StringLength', false, array(10,10))
		->addValidator('Date', false, array('format'=>'dd/MM/yyyy'));
        $this->addElement($dataNascimento);

        $cpf = $this->createElement('text','cpf');
        $cpf->setLabel('CPF:')
                ->setAttrib('class', 'form-control')
                ->setRequired(true)
                ->setAttrib('maxlength', 15)
                ->addFilters(array(new Zend_Filter_StringTrim()));
        $this->addElement($cpf);

        $rg = $this->createElement('text','rg');
        $rg->setLabel('RG:')
                ->setAttrib('class', 'form-control')    
                ->setRequired(true)
                ->setAttrib('maxlength', 20)
                ->addFilters(array(new Zend_Filter_StringTrim()	));
        $this->addElement($rg);

        $email = $this->createElement('text','email');
        $email->setLabel('E-mail:')
                ->setAttrib('class', 'form-control')
                ->setRequired(true)
                ->setAttrib('class', 'form-control')
                ->setAttrib('maxlength', 150)
                ->addFilters(array(new Zend_Filter_StringTrim()	));
        $this->addElement($email);

        $telefone = $this->createElement('text','telefone');
        $telefone->setLabel('Telefone:')
                ->setAttrib('class', 'form-control')    
                ->setRequired(true)
                ->setAttrib('maxlength', 15)
                ->addFilters(array(new Zend_Filter_StringTrim()	));
        $this->addElement($telefone);

        $endereco = $this->createElement('text','endereco');
        $endereco->setLabel('Endereço:')
                ->setRequired(true)
                ->setAttrib('class', 'form-control')    
                ->setAttrib('maxlength', 250)
                ->addFilters(array(new Zend_Filter_StringTrim()	));
        $this->addElement($endereco);

        $numero = $this->createElement('text','numero');
        $numero->setLabel('Numero:')
                ->setRequired(true)
                ->setAttrib('class', 'form-control')    
                ->setAttrib('maxlength', 15)
                ->addFilters(array(new Zend_Filter_StringTrim()	));
        $this->addElement($numero);

        $complemento = $this->createElement('text','complemento');
        $complemento->setLabel('Complemento:')
                ->setAttrib('maxlength', 100)
                ->setAttrib('class', 'form-control')
                ->addFilters(array(new Zend_Filter_StringTrim()	));
        $this->addElement($complemento);

        $bairro = $this->createElement('text','bairro');
        $bairro->setLabel('Bairro:')
                ->setRequired(true)
                ->setAttrib('class', 'form-control')
                ->setAttrib('maxlength', 100)
                ->addFilters(array(new Zend_Filter_StringTrim()	));
        $this->addElement($bairro);

        $estado = $this->createElement('select','idestado');
        $estado->setLabel('Estado:')
                ->setAttrib('class', 'form-control select-dinamic')
                ->setAttrib('rel', '/administracao/usuario/municipios')
                ->setAttrib('idloadcontainer', 'idmunicipio')
                ->setAttrib('type-action', 'html-action')
                ->setMultiOptions($estadoArray);
        $this->addElement($estado);

        $municipio = $this->createElement('select','idmunicipio');
        $municipio->setLabel('Municipio:')
                ->setRegisterInArrayValidator(false)
                ->setMultiOptions(array('', '--'))
                ->setAttrib('class', 'form-control')
                ->setAttrib('disabled', 'true');
        $this->addElement($municipio);

        $cep = $this->createElement('text','cep');
        $cep->setLabel('CEP:')
                ->setRequired(true)
                ->setAttrib('class', 'form-control')
                ->setAttrib('maxlength', 9)
                ->addFilters(array(new Zend_Filter_StringTrim()	));
        $this->addElement($cep);

        $enviar = $this->createElement('submit','ok');
        $enviar->setLabel("Enviar")
                ->setAttrib('class', 'btn btn-default')
                ->setAttrib('id', 'btnSubmit-Enviar');
	$this->addElement($enviar);
    }
}