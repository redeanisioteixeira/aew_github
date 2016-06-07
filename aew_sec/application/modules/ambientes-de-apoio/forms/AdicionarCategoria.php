<?php

class AmbientesDeApoio_Form_AdicionarCategoria extends Sec_Form {
    /**
     * inicialização  do formurario de categoria doi ambiente de apoio
     */
    public function init()
    {
        $iconeMaxSize = '600kb';

        /* Elementos */
        $this->setMethod('post');
        $this->setAction('/ambientes-de-apoio/categoria/salvar');
        $this->setAttrib('enctype', 'multipart/form-data');
        $this->setAttrib('role', 'form');

        $id = $this->createElement('hidden', 'idambientedeapoiocategoria');
        $id->addFilters(array(new Zend_Filter_Int()));
        $this->addElement($id);
        
        $titulo = $this->createElement('text','nomeambientedeapoiocategoria');
        $titulo->setLabel('Nome:')
            ->setAttrib('maxlength', 250)
            ->setAttrib('class', 'form-control')
            ->setAttrib('placeholder', 'Nome da Categoria')
            ->setRequired(true)
            ->addFilters(array(new Zend_Filter_StringTrim()));
        $this->addElement($titulo);

        $iconeImagem = $this->createElement('image','iconeImagem');
        $iconeImagem->setAttrib('class', 'desativado')
                    ->setAttrib('disabled', true);
        $this->addElement($iconeImagem);
        
        $icone = $this->createElement('file','icone');
        $icone->setLabel('Icone da categoria:')
            ->setDescription('<span class="text-warning">Imagem no formato <b>.png</b>, dimensão: <b>130px x 130px</b>, tamanho máximo: <b>'.$iconeMaxSize.'</b></span>')
            ->setDestination(Aew_Model_Bo_AmbienteDeApoio::getIconeDirectory())
            ->addValidator('Count', false, 1)
            ->addValidator('Extension', false, 'png')
            ->addValidator('Size', false, $iconeMaxSize)
            ->setValueDisabled(true);
        $this->addElement($icone);

        $enviar = $this->createElement('submit','ok');
        $enviar->setLabel("Enviar")
               ->setAttrib('class', 'btn btn-default')
               ->setIgnore(true);
        $this->addElement($enviar);
    }

    /**
     * Permite a configuração dos decorators no form
     */
    public function configDecorators()
    {
        $element = $this->getElement('nomeambientedeapoiocategoria');
        $element->getDecorator('label')->setOption('escape', false);    
        $element->getDecorator('description')->setOption('escape', false);

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
        
        if($values['idambientedeapoiocategoria']):
            $categoriaAmbiente = new Aew_Model_Bo_AmbienteDeApoioCategoria();
            $categoriaAmbiente->setId($values['idambientedeapoiocategoria']);
            $categoriaAmbiente = $categoriaAmbiente->select(1);
            
            $element = $this->getElement('iconeImagem');
            $element->setAttrib('class', 'menu-cinza img-rounded shadow-center')
                    ->setAttrib('src', $categoriaAmbiente->getImagemAssociada())
                    ->setAttrib('width', '130px');
        endif;
    }
    
    /**
     * Adiciona restricoes para novos conteudos
     */
    public function adicionarRestricoes()
    {
        $this->getElement('icone')->setRequired(true);
    }
}