<?php

class EspacoAberto_Form_MinhasRedesSociais extends Sec_Form
{
    public function init()
    {
        /* Elementos */
        $this->setMethod('post');
        $this->setAttrib('id', 'redesocialform');
        
        $urlRede = $this->createElement('text','url');
        $urlRede->setLabel('link de sua rede social ou blog :')
                ->setAttrib('class', 'form-control validar')
                ->setAttrib('placeholder', 'por exemplo : https://www.facebook.com/usuario.456645');
        $this->addElement($urlRede);
        
        $enviar = $this->createElement('submit','ok');
        $enviar->setLabel("Adicionar")
               ->setAttrib('class', 'btn btn-default') 
               ->setAttrib('id', 'btnEnviarRede')
               ->setIgnore(true);
        $this->addElement($enviar);
    }
}