<?php
/**
 * formulário de cadastro das categorias de conteudo
 *
 * @author tiago-souza
 */
class Administracao_Form_CategoriaConteudo extends Sec_Form
{
    /**
     * cria compos do formulario
     */
    function init()
    {
        $this->setMethod('post');
	$this->setAttrib('enctype', 'multipart/form-data');
        $this->setAttrib('role', 'formulario');
        
	$id = $this->createElement('hidden', 'idconteudodigitalcategoria');
	$this->addElement($id);
        
	$titulo = $this->createElement('text','nomeconteudodigitalcategoria');
	$titulo->setLabel('Titulo :')
                ->setRequired(true)
                ->setAttrib('title', 'Nome')
                ->setAttrib('maxlength', 250)
                ->setAttrib('class', 'form-control')
                ->addFilters(array(new Zend_Filter_StringTrim()));
	$this->addElement($titulo);

        $conteudoCategoria = new Aew_Model_Bo_ConteudoDigitalCategoria();
        $options = array();
        $options['orderBy'] = array('conteudodigitalcategoria.nomeconteudodigitalcategoria ASC');
        
        $descricao = $this->createElement('richText','descricaoconteudodigitalcategoria');
	$descricao->setLabel('Descrição categoria :')
                    ->setRequired(true)
                    ->setAttrib('title', 'Descrição categoria')
                    ->setAttrib('class', 'form-control')
                    ->setAttrib('rows',15)
                    ->addFilters(array(new Zend_Filter_StringTrim()));
	$this->addElement($descricao);
/*        
	$conteudoCategoriaArray = $conteudoCategoria->getAllForSelect('idconteudodigitalcategoria', 'nomeconteudodigitalcategoria', 'Selecione','idconteudodigitalcategoriapai', $options, null, FALSE);
	$conteudoTipo = $this->createElement('select','idconteudodigitalcategoriapai');
	$conteudoTipo->setLabel('Categoria relacionada :')
                        ->setAttrib('title', 'Tipo do conteúdo')
                        ->setMultiOptions($conteudoCategoriaArray)
                        ->setAttrib('class', 'form-control');
	$this->addElement($conteudoTipo);

        $canalBo = new Aew_Model_Bo_Canal();
        $canalArray = $canalBo->getAllForSelect('idcanal', 'nomecanal','Selecione');
        
        $canal = $this->createElement('select','idcanal');
	$canal->setLabel('Canal:')
                ->setRequired(true)
                ->setDescription('<p class="text-info">Canal ao qual pertence a categoria</p>')
                ->setRegisterInArrayValidator(false)
                ->setMultiOptions($canalArray)
                ->setAttrib('class', 'form-control')
                ->setAttrib('title', 'Canal');
        $this->addElement($canal);
*/
        $canalBo = new Aew_Model_Bo_Canal();
        $canais = $canalBo->getAllForSelect('idcanal', 'nomecanal','Selecione um Canal');
        
        $canal = $this->createElement('select','idcanal');
        $canal->setLabel('Canal :')
                    ->setDescription('<span class="text-info">Canal do conteudo.</span>')
                    ->setRegisterInArrayValidator(false)
                    ->setMultiOptions($canais)
                    ->setAttrib('id', 'canalAdd')
                    ->setAttrib('class', 'form-control select-dinamic')
                    ->setAttrib('idloadcontainer','idconteudodigitalcategoria')
                    ->setAttrib('type-action', 'html-action')
                    ->setAttrib('rel', '/administracao/categoria-conteudo/categorias-canal')
                    ->setAttrib('title', 'Canal');
        $this->addElement($canal);

        $categoria = $this->createElement('select','idconteudodigitalcategoria');
        $categoria->setLabel('Categoria :')
                    ->setDescription('<span class="text-info">Canal do conteudo.</span>')
                    ->setRegisterInArrayValidator(false)
                    ->setAttrib('disable', 'true')
                    ->setAttrib('id', 'idconteudodigitalcategoria')
                    ->setAttrib('class', 'form-control')
                    ->setAttrib('title', 'Categoria Conteudo Digital');
        $this->addElement($categoria);
        
	$conteudoCategoriaArray = $conteudoCategoria->getAllForSelect('idconteudodigitalcategoria', 'nomeconteudodigitalcategoria', 'Selecione','idconteudodigitalcategoriapai', $options, null, FALSE);
	$conteudoTipo = $this->createElement('select','idconteudodigitalcategoriapai');
	$conteudoTipo->setLabel('Categoria relacionada :')
                        ->setAttrib('title', 'Tipo do conteúdo')
                        ->setMultiOptions($conteudoCategoriaArray)
                        ->setAttrib('class', 'form-control');
	$this->addElement($conteudoTipo);
        
        
        $ativo = $this->createElement('checkbox', 'flativo');
        $ativo->setLabel('Ativo :')
                ->setRequired(true)
                ->setCheckedValue('t')
                ->setUncheckedValue('f');
        $this->addElement($ativo);

        $ativo = $this->createElement('checkbox', 'fldestaque');
        $ativo->setLabel('Destacado :')
                ->setRequired(true)
                ->setCheckedValue('t')
                ->setUncheckedValue('f');
        $this->addElement($ativo);
        
        $iconeMaxSize = '2MB';
        $iconeImagem = $this->createElement('image','iconeImagem');
        $iconeImagem->setAttrib('class', 'desativado')
                    ->setAttrib('disabled', true);
        $this->addElement($iconeImagem);
        
        $icone = $this->createElement('file','icone');
        $icone->setLabel('<i class="fa fa-picture-o"></i> Icone da categoria :')
            ->setDescription('<span class="text-info">Imagem no formato <b>.png</b>, dimensão: <b>400px x 220px</b>, tamanho máximo: <b>'.$iconeMaxSize.'</b></span>')
            ->setDestination(Aew_Model_Bo_ConteudoDigitalCategoria::getIconeDirectory())
            ->addValidator('Count', false, 1)
            ->addValidator('Extension', false, 'png')
            ->addValidator('Size', false, $iconeMaxSize);
        $this->addElement($icone);

        $videoMaxSize = '50MB';
        $videoDestaque = $this->createElement('file','video');
        $videoDestaque->setLabel('<i class="fa fa-video-camera"></i> Vídeo destaque:')
            ->setDescription('<p class="text-info">Vídeo no formato <b>.webm</b>, tamanho máximo: <b>'.$videoMaxSize.'</b></p><div class="visualizar-video"></div>')
            ->setDestination(Aew_Model_Bo_ConteudoDigitalCategoria::getIconeDirectory().DS.'video-destaque')
            ->addValidator('Count', false, 1)
            ->addValidator('Extension', false, 'webm')
            ->addValidator('Size', false, $videoMaxSize);
        $this->addElement($videoDestaque);

        $apagar = $this->createElement('checkbox', 'apagar');
        $apagar->setAttrib('class', 'desativado')
                ->setOptions(array('class' => 'desativado'))
                ->setCheckedValue('t')
                ->setUncheckedValue('f');
        $this->addElement($apagar);
        
	$enviar = $this->createElement('submit','salvar');
	$enviar->setLabel("Salvar")
                ->setAttrib('class', 'btn btn-default');
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

        $element = $this->getElement('video');
        $element->getDecorator('label')->setOption('escape', false);    
        $element->getDecorator('description')->setOption('escape', false);
        
        $element = $this->getElement('idcanal');
        $element->getDecorator('label')->setOption('escape', false);    
        $element->getDecorator('description')->setOption('escape', false);

        $element = $this->getElement('apagar');
        $element->getDecorator('label')->setOption('escape', false);    
        $element->getDecorator('description')->setOption('escape', false);
    }
    
    /**
     * Permite a carga valores nos campo no form
     */
    public function populate(array $values)
    {
        parent::populate($values);
        
        if($values['idconteudodigitalcategoria']):
            $categoriaConteudo = new Aew_Model_Bo_ConteudoDigitalCategoria();                    ;
            $categoriaConteudo->setId($values['idconteudodigitalcategoria']);
            $categoriaConteudo = $categoriaConteudo->select(1);
            
            $element = $this->getElement('iconeImagem');
            $element->setAttrib('class', 'menu-cinza img-rounded shadow-center')
                    ->setAttrib('src', $categoriaConteudo->getConteudoImagem());
            
            if($categoriaConteudo->getImagemVideoUrl()):
                $element = $this->getElement('apagar');
                $element->setLabel('<span class="link-vermelho">Apagar vídeo :</span>')
                        ->setOptions(array('class' => ''));
                
                $element = $this->getElement('video');
                $description = $element->getDescription();
                
                $video = '<video class="shadow rounded col-lg-6 text-center" poster="'.$categoriaConteudo->getConteudoImagem().'" preload controls><source src="'.$categoriaConteudo->getImagemVideoUrl().'" type="video/webm"></video>';
                
                $description = str_replace('<div class="visualizar-video"></div>', '<div class="visualizar-video col-lg-12 margin-bottom-10">'.$video.'</div>', $description);
                $element->setDescription($description);
            endif;
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