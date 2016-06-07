<?php

class EspacoAberto_Form_SobreMimPerfil extends Sec_Form
{
    public function init()
    {

        $this->setMethod('post');
        $this->setAttrib('role', 'formulario');

        $conteudoNivelBo = new Aew_Model_Bo_NivelEnsino();
        $conteudoNivelArray = $conteudoNivelBo->getAllForSelect('idnivelensino', 'nomenivelensino', 'Selecione');

        $conteudoComponenteBo = new Aew_Model_Bo_ComponenteCurricular();
        
        
        $idusuario = $this->createElement('hidden', 'idusuario');
        $this->addElement($idusuario);
        
        $allComponentes = $this->createElement('hidden', 'AllComponentesCurriculares');
        $allComponentes->setAttrib('id', "AllComponentesCurriculares");
        
        $this->addElement($allComponentes);

        $sobreMim = $this->createElement('richText','sobremim');
        $sobreMim->setLabel('Sobre mim:')
                ->setAttrib('maxlength', 250)
                ->setAttrib('class','form-control')    
                ->setRequired(true)    
                ->addFilters(array(new Zend_Filter_StringTrim()));
        $this->addElement($sobreMim);
        
        $cidadeNatal = $this->createElement('text','cidadenatal');
        $cidadeNatal->setLabel('Cidade Natal :')
                ->setAttrib('class', 'form-control');
        $this->addElement($cidadeNatal);
        
        $nivel = $this->createElement('select','idnivel');
        $nivel->setLabel('Disciplinas de atuação:')
                ->setAttrib('id', 'nivelEnsinoAdd')
                ->setAttrib('class','form-control select-dinamic')
                ->setAttrib('type-action','append-action')
                ->setAttrib('idloadcontainer', 'idcomponentes')
                ->setAttrib('rel', '/aew/usuario/componentes')
                ->setMultiOptions($conteudoNivelArray)
                ->setAttrib('title', 'Disciplinas de atuação');
        $this->addElement($nivel);
        
        $componenteTodos = $this->createElement('select','idcomponentes');
        $componenteTodos->setLabel('Todos:')
                ->setAttrib('class','form-control')
                ->setAttrib('required', 'true') 
                ->setAttrib('multiple', 'true') 
                ->setAttrib('disabled', 'true') 
                ->setAttrib('id', 'idcomponentes')
                ->setIgnore(true)
                ->setAttrib('title', 'Todos');
        $this->addElement($componenteTodos);

        $btnRightComponente = $this->createElement('image','right_componente_curricular');
        $btnRightComponente->setLabel("<i class='fa fa-arrow-circle'></i>");
        $this->addElement($btnRightComponente);
        
        $btnLeftComponente = $this->createElement('image','left_componente_curricular');
        $btnLeftComponente->setLabel("<i class='fa fa-arrow-circle-left fa-2x'></i>")
                ->setIgnore(true)
                ->setAttrib('id', 'removeComponenteCurricular')
                ->setAttrib('class','form-control desativado')
                ->setAttrib('title', 'Remover Componente')
                ->setAttrib('onclick', 'removeComponente()');
	$this->addElement($btnLeftComponente);
        
        $componente = $this->createElement('select','componenteCurricularAdd');
        $componente->setLabel('Componente: ')
                ->setRegisterInArrayValidator(false)
                ->setAttrib('class','form-control')
                ->setAttrib('multiple', 'multiple')
                ->setAttrib('id', 'componenteCurricularAdd')
                ->setAttrib('title', 'Componente');
        $this->addElement($componente);
        
        $this->addDisplayGroup(array('idnivel','idcomponentes','right_componente_curricular','left_componente_curricular','componenteCurricularAdd'), 'group_componentes',array('legend' => 'Componentes curriculares'));
        
        $curriculo = $this->createElement('text','lattes');
        $curriculo->setLabel('Link de currículo :')
                ->setDescription('O link esta relacionado à plataforma de currículos LATTES. Para maiores informações clique <a href="http://www.cnpq.br/web/portal-lattes/sobre-a-plataforma" target="_blank">aqui</a>.')
                ->setAttrib('class', 'form-control');
        $this->addElement($curriculo);
        
        $enviar = $this->createElement('submit','Salvar');
        $enviar->setAttrib('class','btn btn-default') 
               ->setIgnore(true)
               ->setAttrib('onclick', 'enviaComp()');
	$this->addElement($enviar);
    }

    function configDecorators()
    {
	$element = $this->getElement('lattes');
	$element->getDecorator('label')->setOption('escape', false);
        $element->getDecorator('description')->setOption('escape', false);
        
        $element = $this->getElement('right_componente_curricular');
	$element->getDecorator('label')->setOption('escape', false);
        
        $element = $this->getElement('left_componente_curricular');
	$element->getDecorator('label')->setOption('escape', false);
        
    }
    
    function populate(array $values) 
    {
        if ($values['componenteCurricular'] != null) 
        {
            $componentes = array();
            foreach($values['componenteCurricular'] as $componente) 
            {
                $componentes[ $componente["idcomponentecurricular"] ] =  strip_tags(preg_replace("/(\n|\r|%0a|%0d|Content-Type:|bcc:|to:|cc:|Autoreply:|from|select|insert|delete|where|drop table|show tables|#|\*|--|\\\\)/i", "", $componente["nomecomponentecurricular"])) . ":" . $componente["nomenivelensino"];
            }
            $this->getElement('componenteCurricularAdd')->setMultiOptions($componentes);
        }
        
        parent::populate($values);
    }
    

}