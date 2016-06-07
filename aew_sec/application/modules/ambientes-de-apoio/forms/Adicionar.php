<?php

class AmbientesDeApoio_Form_Adicionar extends Sec_Form {
    /**
     * inicialização do formulario de conteudo da categoria no ambiente de apoio
     */
    public function init()
    {
        $categoriaBo = new Aew_Model_Bo_AmbienteDeApoioCategoria();
        $categoriaArray = $categoriaBo->getAllForSelect('idambientedeapoiocategoria',
                                                        'nomeambientedeapoiocategoria',
                                                      'Selecione...');
        $iconeMaxSize = '600kb';
        /* Elementos */
        $this->setMethod('post');
        $this->setAction('/ambientes-de-apoio/ambiente/salvar');
        $this->setAttrib('enctype', 'multipart/form-data');
        $this->setAttrib('role', 'formulario');
        $id = $this->createElement('hidden', 'idambientedeapoio');
        $id->addFilters(array(
				new Zend_Filter_Int()
				));
        $this->addElement($id);
        $categoria = $this->createElement('select','idambientedeapoiocategoria');
        $categoria->setLabel('Categoria:')
                ->setAttrib('class', 'form-control')
                ->setRequired(true)
                ->setMultiOptions($categoriaArray);
        $this->addElement($categoria);

        $titulo = $this->createElement('text','titulo');
        $titulo->setLabel('Titulo:')
                ->setAttrib('class', 'form-control')
                ->setAttrib('required', 'true')
                ->setAttrib('placeholder', 'Título do ambiente de apoio') // HTML5
                ->setAttrib('maxlength', 250)
                ->setRequired(true)
                ->addFilters(array(
				new Zend_Filter_StringTrim()
				));
        $this->addElement($titulo);

        $url = $this->createElement('text','url');
        $url->setLabel('URL:')
            ->setAttrib('class', 'form-control')
            ->setAttrib('required', 'true')
            ->setAttrib('placeholder', 'http://exemplo.com')    
            ->setAttrib('maxlength', 200)
            ->setRequired(true)
            ->addFilters(array(
				new Zend_Filter_StringTrim()
				));
        $this->addElement($url);

        $urlProjeto = $this->createElement('text','urlProjeto');
        $urlProjeto->setLabel('URL do projeto:')
                ->setAttrib('class', 'form-control')
                ->setAttrib('placeholder', 'http://exemplo.com/download')
                ->setAttrib('maxlength', 200)
                ->addFilters(array(
				new Zend_Filter_StringTrim()
				));
        $this->addElement($urlProjeto);

        $palavrasChave = $this->createElement('text','tags');
        $palavrasChave->setLabel('Palavras-Chave: (Separadas por vírgula)')
            ->addFilters(array(
                new Zend_Filter_StripTags(),
                new Sec_Filter_StringToLower()
            ))
                ->setAttrib('class', 'form-control')
                ->setAttrib('maxlength', 250);
        $this->addElement($palavrasChave);

        $descricao = $this->createElement('textarea','descricao');
        $descricao->setLabel('Descrição:')
                ->setAttrib('class', 'form-control')
                ->setAttrib('placeholder', 'Escreva aqui uma descrição para o ambiente de apoio')
                ->setAttrib('required', 'true')
            ->setRequired(true)
            ->addFilters(array(
				new Zend_Filter_StringTrim()
				));
        $this->addElement($descricao);

        $descricao = $this->createElement('textarea','usopedagogico');
        $descricao->setLabel('Informações para o uso pedagógico:')
                ->setAttrib('class', 'form-control')
                ->setAttrib('placeholder', 'Texto para uso Pedagogico')
            ->addFilters(array(
				new Zend_Filter_StringTrim()
				));
        $this->addElement($descricao);

        $icone = $this->createElement('file','icone');
        $icone->setLabel('Icone: ')
            ->setAttrib('placeholder', 'Escolha um Arquivo')
            ->setDescription('Imagem no formato png com 96x96px')
            ->setDestination(Aew_Model_Bo_AmbienteDeApoio::getIconeDirectory())
            ->addValidator('Count', false, 1)
            ->addValidator('Extension', false, 'png')
            ->addValidator('Size', false, $iconeMaxSize)
            ->setValueDisabled(true);
        $this->addElement($icone);

        $enviar = $this->createElement('submit','ok');
        $enviar->setLabel("Enviar")
                ->setAttrib('class', 'btn btn-primary')
               ->setAttrib('id', 'btnSubmit-Enviar')
               ->setIgnore(true);
		$this->addElement($enviar);
    }

    /**
     * Adiciona restricoes para novos conteudos
     */
    public function adicionarRestricoes()
    {
        $this->getElement('icone')->setRequired(true)->setLabel('Icone:');
    }
}