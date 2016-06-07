<?php

class EspacoAberto_Form_FotoPerfil extends Sec_Form
{
    public function init()
    {
        $fotoExtension = 'jpeg, png, jpg';
        $fotoMaxSize = '1MB';
        
        $this->setMethod('post');

        $this->addElement('hidden', 'idfoto');
        
        $foto = $this->createElement('file','foto');
        $foto->setLabel('Foto:')
                ->setDescription('<span class="link-cinza-escuro">Foto/Imagem no formato <b>.png</b> ou <b>.jpeg</b>, dimensão mínima: <b>35px x 35px</b>, tamanho máximo: <b>'.$fotoMaxSize.'</b></span>')
                ->setDestination(Aew_Model_Bo_UsuarioFoto::getFotoDirectory())
                ->addValidator('Extension', false, $fotoExtension)
                ->addValidator('Size', false, $fotoMaxSize)
                ->setValueDisabled(true)
                ->setRequired(true)
                ->setAttrib('id','foto')
                ->setAttrib('data-fv-file',true)    
                ->setAttrib('class', 'from-control');
        $this->addElement($foto);
        
        $enviar = $this->createElement('button','submit');
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
        $element = $this->getElement('foto');
        $element->getDecorator('label')->setOption('escape', false);    
        $element->getDecorator('description')->setOption('escape', false);
    }
}