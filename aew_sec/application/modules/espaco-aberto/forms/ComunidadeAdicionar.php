<?php

class EspacoAberto_Form_ComunidadeAdicionar extends Sec_Form {
    public function init()
    {
	/* Elementos */
	$this->setMethod('post');
        $this->setAttrib('role', 'formulario');;

	$id = $this->createElement('hidden', 'idcomunidade');
	$id->addFilters(array(new Zend_Filter_Int()));
	$this->addElement($id);

	$titulo = $this->createElement('text','nomecomunidade');
	$titulo->setLabel('Nome :')
                ->setAttrib('maxlength', 250)
                ->setRequired(true)
                ->setAttrib('class', 'form-control')
                ->setAttrib('placeholder', 'Nome')
                ->setAttrib('required', 'true')
                ->addFilters(array( new Zend_Filter_StringTrim()));
	$this->addElement($titulo);

	$texto = $this->createElement('richText','descricao');
	$texto->setLabel('Descrição :')
                ->setRequired(true)
                ->setAttrib('rows', '5')
                ->setAttrib('class', 'form-control ')
                ->setAttrib('autocomplete', 'off')
                ->addFilters(array(new Zend_Filter_StringTrim(),new Sec_Filter_Xss()));
	$this->addElement($texto);

	$palavrasChave = $this->createElement('textarea','tags');
	$palavrasChave->setLabel('Palavras-Chave:')
                ->setRequired(true)
                ->setDescription('<p class="text-success">Defina com cautela os descritores, pois esse é o campo mais importante para o sucesso da busca. Lembre de separar por vírgulas as palavras escolhidas.')
                ->addFilters(array(new Zend_Filter_StripTags(), new Sec_Filter_StringToLower()))
                ->setAttrib('placeholder', 'Ex: matematica, biologia, etc')
                ->setAttrib('title', 'Palavras-Chave')
                ->setAttrib('autocomplete', true)
                ->setAttrib('rel','/conteudos-digitais/conteudo/palavra-chave')
                ->setAttrib('class', 'multiple form-control search-input')
                ->setAttrib('rows','4');
	$this->addElement($palavrasChave);
        
	$moderacao = $this->createElement('checkbox', 'flmoderausuario');
	$moderacao->setLabel('Comunidade moderada :')
                ->setRequired(true)
                ->setAttrib('class', 'checkbox')
                ->setCheckedValue('t')
                ->setUncheckedValue('f');
	$this->addElement($moderacao);

	$enviar = $this->createElement('submit','Salvar');
	$enviar->setAttrib('class', 'btn btn-primary')
               ->setIgnore(true);
	$this->addElement($enviar);
    }

    public function configDecorators()
    {
	$element = $this->getElement('nomecomunidade');
	$element->getDecorator('label')->setOption('escape', false);

	$element = $this->getElement('descricao');
	$element->getDecorator('label')->setOption('escape', false);
        
	$element = $this->getElement('tags');
	$element->getDecorator('label')->setOption('escape', false);
        $element->getDecorator('description')->setOption('escape', false);
        
	$element = $this->getElement('flmoderausuario');
	$element->getDecorator('label')->setOption('escape', false);
    }

    /**
    * Popula o formulario
    */
    public function populate(array $values)	
    {
        $idComunidade =  $values['idcomunidade'];
        
        $comunidadeBo = new Aew_Model_Bo_Comunidade();
        $comunidadeBo->setId($idComunidade);
        $comunidadeBo->select(1);
        
        $tags = $comunidadeBo->selectTags();
        if($tags)
        {
            foreach ($tags as $tag)
            {
                $values['tags'] .= $tag->getNome().', ';
            }
            $values['tags'] = substr($values['tags'], 0, -2);
        }
        
	parent::populate($values);
    }
}