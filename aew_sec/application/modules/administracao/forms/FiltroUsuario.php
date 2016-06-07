<?php
/**
 * formulário para filtragem de usuarios
 */
class Administracao_Form_FiltroUsuario extends Sec_Form {

    /**
     * cria os campos do formulário
     */
    public function init()
    {
	$tipoUsuarioBo = new Aew_Model_Bo_UsuarioTipo();
	$tipoUsuarioArray = $tipoUsuarioBo->getAllForSelect('idusuariotipo', 'nomeusuariotipo');

	/* Elementos */
	$this->setMethod('post')
                ->setAttrib('class', 'form-inline')
                ->setAction('/administracao/usuario/listar');
	$texto = $this->createElement('text','buscarUsuario');
	$texto->setLabel('Buscar :')
                    ->setAttrib('placeholder', 'Buscar por nome ou e-mail')
                    ->setAttrib('size', '60%')
                    ->setAttrib('class', 'form-control')
                    ->setAttrib('maxlength', 250);
	$this->addElement($texto);

	$tipo = $this->createElement('select','idusuariotipo');
	$tipo->setLabel('Tipo :')
                    ->setAttrib('class', 'form-control')
                    ->setMultiOptions($tipoUsuarioArray);
	$this->addElement($tipo);
        
        $flativo = $this->createElement('checkbox', 'flativo');
        $flativo->setLabel('Usuarios inativos :')
                    ->setAttrib('unchecked', 'unchecked')
                    ->setCheckedValue('f')
                    ->setUncheckedValue('t')
                    ->setAttrib('title', 'Filtrar usuarios inativos');
        $this->addElement($flativo);
        
	$enviar = $this->createElement('submit','filtrar');
	$enviar->setAttrib('class', 'btn btn-default');
        $this->addElement($enviar);
    }

    /**
     * configura decoração dos campos
     */
    public function configDecorators()
    {
	$element = $this->getElement('buscarUsuario');
	$element->getDecorator('label')->setOption('escape', false);
	$element = $this->getElement('idusuariotipo');
	$element->getDecorator('label')->setOption('escape', false);
    }
}