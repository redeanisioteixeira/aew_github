<?php
class Aew_Form_Apagar extends Sec_Form
{
    public function init()
    {
        /* Elementos */
        $this->setMethod('post');
        $this->setAttrib('class', 'form-apagar form-inline');

        $mensagem = $this->createElement('text','mensagem');
        $mensagem->setAttrib('class', 'text-only font-size-150')
                ->setAttrib('size', '100%')
                ->setAttrib('readonly', '');
        $this->addElement($mensagem);
        
        $nao = $this->createElement('submit','nao');
        $nao->setLabel("Não")
            ->setAttrib('class', 'btn btn-danger btn-sm');
	$this->addElement($nao);

        $sim = $this->createElement('submit','sim');
        $sim->setLabel("Sim")
            ->setAttrib('class', 'btn btn-primary btn-sm');
        $this->addElement($sim);
    }
}