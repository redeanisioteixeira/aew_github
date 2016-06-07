<?php

class Administracao_Form_Licenca extends Sec_Form {

    /**
     * cria os campos do formulário
     */
    public function init()
    {
        $iconeMaxSize = '600KB';
        
        /* Elementos */
        $this->setMethod('post');
        $this->setAction('/administracao/licenca/salvar');
        
        $idlicenca = $this->createElement('hidden', 'idconteudolicenca');
        $this->addElement($idlicenca);

        $nome = $this->createElement('text','nomeconteudolicenca');
        $nome->setLabel('Nome da Licença:')
                ->setAttrib('maxlength', 150)
                ->setAttrib('class', 'form-control')
                ->setRequired(true)
                ->addFilters(array(new Zend_Filter_StringTrim()));
        $this->addElement($nome);
        
        $licencaConteudo = new Aew_Model_Bo_ConteudoLicenca();
        
        $options = array();
        $options['orderBy'] = 'conteudolicenca.nomeconteudolicenca ASC';
	$licencaConteudoArray = $licencaConteudo->getAllForSelect('idconteudolicenca', 'nomeconteudolicenca', 'Selecione', 'idconteudolicencapai', $options, null, false);
        
	$licencaTipo = $this->createElement('select','idconteudolicencapai');
	$licencaTipo->setLabel('Licença relacionada')
		     ->setMultiOptions($licencaConteudoArray)
                     ->setAttrib('class', 'form-control')
                     ->setAttrib('title', 'Tipo do Licença');
	$this->addElement($licencaTipo);

        $descricao = $this->createElement('textarea','descricaoconteudolicenca');
        $descricao->setLabel('Descrição:')
                    ->setAttrib('rows', 10)
                    ->setAttrib('class', 'form-control')
                    ->setRequired(true)
                    ->addFilters(array(new Zend_Filter_StringTrim()));
        $this->addElement($descricao);

        $site = $this->createElement('text','siteconteudolicenca');
        $site->setLabel('Site referenciado:')
                ->setAttrib('maxlength', 500)
                ->setAttrib('class', 'form-control')
                ->addFilters(array(new Zend_Filter_StringTrim()));
        $this->addElement($site);

        $iconeImagem = $this->createElement('image','iconeImagem');
        $iconeImagem->setAttrib('class', 'desativado')
                    ->setAttrib('disabled', true);
        $this->addElement($iconeImagem);
        
        $icone = $this->createElement('file','icone');
        $icone->setLabel('Icone da licença:')
            ->setDescription('<span class="text-info">Imagem no formato <b>.png</b>, dimensão: <b>200px x 90px</b>, tamanho máximo: <b>'.$iconeMaxSize.'</b></span>')
            ->setDestination(Aew_Model_Bo_ConteudoLicenca::getIconeDirectory())
            ->addValidator('Count', false, 1)
            ->addValidator('Extension', false, 'png')
            ->addValidator('Size', false, $iconeMaxSize);
        $this->addElement($icone);
        
        $enviar = $this->createElement('submit','enviar');
        $enviar->setLabel("Enviar")
                ->setAttrib('class', 'btn btn-default') 
                ->setAttrib('id', 'enviar');
	$this->addElement($enviar);
    }
    
    /**
     * Permite a configuração dos decorators no form
     */
    public function configDecorators()
    {
        $element = $this->getElement('icone');
        $element->getDecorator('label')->setOption('escape', false);    
        $element->getDecorator('description')->setOption('escape', false);
    }    

    /**
     * Permite a carga valores nos campo no form
     */
    public function populate(array $values)
    {
        parent::populate($values);
        
        if($values['idconteudolicenca']):
            $licencaConteudo = new Aew_Model_Bo_ConteudoLicenca();
            $licencaConteudo->setId($values['idconteudolicenca']);
            $licencaConteudo = $licencaConteudo->select(1);
            
            $element = $this->getElement('iconeImagem');
            $element->setAttrib('class', 'menu-cinza img-rounded shadow-center')
                    ->setAttrib('src', $licencaConteudo->getImagemAssociada());
        endif;
    }
    
    /**
     * Adiciona restricoes para novas licenças
     */
    public function adicionarRestricoes()
    {
        $this->getElement('icone')->setRequired(true);
    }
}