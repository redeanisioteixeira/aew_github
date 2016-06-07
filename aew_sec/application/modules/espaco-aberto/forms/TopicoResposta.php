<?php
/**
 * formulário para resposta a comentário
 */
class EspacoAberto_Form_TopicoResposta extends Sec_Form {

    public function init()
    {
        /* Elementos */
        $this->setMethod('post');
        $this->setAttrib('class', 'form-comentario');
        $comutopicomsg = $this->createElement('hidden', 'idcomutopicomsg');
        $comutopicomsg->addFilters(array(
				new Zend_Filter_Int()
				));
        $this->addElement($comutopicomsg);
        
        $pai = $this->createElement('hidden', 'pai');
        $pai->addFilters(array(
				new Zend_Filter_Int()
				));
        $this->addElement($pai);
        
        
        
        $id = $this->createElement('hidden', 'idcomutopico');
        $id->addFilters(array(
				new Zend_Filter_Int()
				));
        $this->addElement($id);
        
        $texto = $this->createElement('richText','mensagem');
        
        $texto->setLabel('Mensagem do tópico')
                    ->setRequired(true)
		    ->setAttrib('rows', '5')
                    ->setAttrib('class', 'form-control')
            ->addFilters(array(
				new Zend_Filter_StringTrim(),
				new Sec_Filter_Xss()
				));
        $this->addElement($texto);
		
        
        $enviar = $this->createElement('submit','salvar');
	$enviar->setAttrib('id', 'submit')
                ->setAttrib('class', 'btn btn-primary btnEnviar')
		->setIgnore(true);
    	$this->addElement($enviar);
    }
    

}