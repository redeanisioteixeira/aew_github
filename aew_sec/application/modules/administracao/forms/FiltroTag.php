<?php
/**
 * formulário para filtro de palavras chaves
 */
class Administracao_Form_FiltroTag extends Sec_Form{
    
    public function init()
    {
        $this->setMethod('post')
                 ->setAttrib('class', 'form-group form-inline padding-none margin-none')
                 ->setAction('/administracao/tag/listar');

	$tagBo = new Aew_Model_Bo_Tag();
        $arrTags = $tagBo->filtrarLetras();
        $letrasArray = $tagBo->getAllForSelect('idletra', 'nomeletra', 'Selecione','',null,$arrTags);
        $letras = $this->createElement('select','letra');
        $letras->setLabel('Buscar :')
                ->setAttrib('class', 'form-control')
                ->setMultiOptions($letrasArray)
                ->setAttrib('title', 'Filtro por letra inicial');
        $this->addElement($letras);
        
        $nometag = $this->createElement('text','tag');
        $nometag->setAttrib('placeholder', 'Digite nome da tag')
        ->setAttrib('class', 'form-control');
        $this->addElement($nometag);

        $enviar = $this->createElement('submit','Filtrar');
        $enviar->setAttrib('class', 'btn btn-default');
        $this->addElement($enviar);
	$semuso = $this->createElement('checkbox','semuso');
	$semuso->setAttrib('title','Sem uso')
			->setLabel('<span class="padding-left-05">Sem uso</span>')
			->setAttrib('class','pull-left')
			->setAttrib('data-toggle','tooltip')
			->setAttrib('data-placement','top');
	$this->addElement($semuso);
	$buscada = $this->createElement('checkbox','buscada');
	$buscada->setAttrib('title','Buscadas')
			->setLabel('<span class="padding-left-05">Buscadas</span>')
			->setAttrib('class','pull-left')
			->setAttrib('data-toggle','tooltip')
			->setAttrib('data-placement','top');
	$this->addElement($buscada);
    }

    /**
     * decoracao de elementos do formulario
     */
    public function configDecorators()
    {
        $element = $this->getElement('tag');
        $element->getDecorator('label')->setOption('escape', false);
        $element = $this->getElement('semuso');
        $element->getDecorator('label')->setOption('escape', false);
        $element = $this->getElement('buscada');
        $element->getDecorator('label')->setOption('escape', false);
    }
}