<?php

class EspacoAberto_Form_FiltroAmbiente extends Sec_Form {

    public function init()
    {
        $categoriaBo = new Aew_Model_Bo_AmbienteDeApoioCategoria();
        $categoriaArray = $categoriaBo->getAllForSelect('idambientedeapoiocategoria',
                                                'nome',
                                                'Selecione...');

        /* Elementos */
        $this->setMethod('post')
                ->setAttrib('class', 'form-inline')
                ->setAttrib('role', 'formularioBusca');
        $this->setAction('/espaco-aberto/favorito/listar-ambientes-de-apoio');

        $nivel = $this->createElement('select','categoria');
        $nivel->setLabel('filtro: ')
             ->setMultiOptions($categoriaArray)
             ->setAttrib('class', 'form-control');
        $this->addElement($nivel);

        $enviar = $this->createElement('submit','ok');
        $enviar->setLabel("Filtrar")
               ->setAttrib('class', 'btn btn-default') 
               ->setAttrib('id', 'btnSubmit-Enviar')
               ->setIgnore(true);
		$this->addElement($enviar);
    }
}