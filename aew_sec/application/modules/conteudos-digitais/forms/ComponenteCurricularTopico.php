<?php
/**
 * Description of TopicoComponenteCurricular
 *
 * @author tiago-souza
 */
class ConteudosDigitais_Form_ComponenteCurricularTopico extends Sec_Form
{
    //put your code here
    function init()
    {
        $this->setMethod('post');
	$this->setAction('/conteudos-digitais/topico/salvar');
        $this->setAttrib('role', 'formulario');
	$id = $this->createElement('hidden', 'idcomponentecurriculartopico');
	$this->addElement($id);
        
        $conteudoCategoria = new Aew_Model_Bo_NivelEnsino();
	$conteudoCategoriaArray = $conteudoCategoria->getAllForSelect('idnivelensino', 'nomenivelensino', 'Selecione');
	$conteudoTipo = $this->createElement('select','idnivelensino');
	$conteudoTipo->setLabel('Nivel de Ensino')
		     ->setMultiOptions($conteudoCategoriaArray)
                     ->setAttrib('class', 'form-control')
                     ->setAttrib('title', 'Tipo do conteúdo');
	$this->addElement($conteudoTipo);
        
	$componenteCurricular = $this->createElement('select','idcomponentecurricular');
	$componenteCurricular->setLabel('Componente Curricular')
                     ->setAttrib('class', 'form-control')
                     ->setRegisterInArrayValidator(false)
                     ->setRequired(true)
                     ->setAttrib('title', 'Topico componente curricular');
	$this->addElement($componenteCurricular);
	
	$titulo = $this->createElement('text','nomecomponentecurriculartopico');
	$titulo->setLabel('Titulo:')
               ->setAttrib('maxlength', 250)
               ->setAttrib('class', 'form-control')
	       ->setRequired(true)
	       ->addFilters(array(new Zend_Filter_StringTrim()))
               ->setAttrib('title', 'Nome');
	$this->addElement($titulo);
        
	$url = $this->createElement('text','urlcomponentecurriculartopico');
	$url->setLabel('Url:')
                  ->setRequired(true)
		  ->addFilters(array(new Zend_Filter_StringTrim()))
		  ->setAttrib('title', 'Descrição conteúdo digital')
                  ->setAttrib('class', 'form-control');
	$this->addElement($url);
	//Fim campo pergunta
	$enviar = $this->createElement('submit','ok');
	$enviar->setLabel("Salvar")
               ->setAttrib('id', 'btnSubmit-Enviar');
	$this->addElement($enviar);
    }
}