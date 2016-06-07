<?php
class ConteudosDigitais_Form_Comentario extends Sec_Form{

    protected $usuario;
    
    function __construct( Aew_Model_Bo_Usuario $usuario =  null ,    $options = null ) 
    {
        $this->usuario = $usuario?$usuario:new Aew_Model_Bo_Usuario;
        parent::__construct($options);
    }

    public function init()
    {
        parent::init();
	$linkFotoLogado = $this->usuario->getFotoPerfil()->getFotoCache(Aew_Model_Bo_Foto::$FOTO_CACHE_30X30, "", 30,30,true);
	
        /* Elementos */
	$description = "";
	$placeholder = "Para enviar seu comentário deve estar logado";
	$enabled     = "disabled";
	$submit      = "Logar-se";
	$classe      = "form-comentario logar";
	$classeBotao = "fa fa-lock";
        
	if($this->usuario->getId()):
            $classe      = "form-comentario ativo";
            $description = "Publicando como <b>".$this->usuario->getNome()."</b>";
            $placeholder = "Comentar...";
            $enabled     = "enabled";
            $submit      = "Comentar";
	endif;
        
	$this->setMethod("post")
            ->setAttrib("id", "form-comentario")
            ->setAttrib("class", "$classe pull-left")
            ->setAttrib('role','form');

	$foto = $this->createElement('image','foto-usuario');
	$foto->setAttrib("src",$linkFotoLogado)
            ->setAttrib("width","50")
            ->setAttrib("height","50");
	$this->addElement($foto);
        $this->setAction("/conteudos-digitais/comentario/adicionar/");
        
	$comentario = $this->createElement('textarea','comentario');
        
	$comentario->setAttrib( "id", "idcomentario" )
                    ->setAttrib("class", "form-control")
                    ->setAttrib("placeholder",$placeholder)
                    ->setAttrib($enabled,"")
                    ->setAttrib("rows", "2")
                    ->setDescription("<span class='hidden-xs'>$description</span>")
                    ->setRequired(true)
                    ->addFilters(array(new Zend_Filter_StringTrim()));
	$this->addElement($comentario);
        
	$enviar = $this->createElement('submit','comentar');
	$enviar->setLabel($submit)
		->setAttrib("class","btn btn-default pull-right")
		->setIgnore(true);
	$this->addElement($enviar);
	$conteudo = $this->createElement('hidden', 'idconteudodigital');
        $conteudo->setRequired(true);
	$this->addElement($conteudo);
    }

    /**
     * Permite a configuração dos decorators no form
     */
    public function configDecorators()
    {
	$element = $this->getElement('comentario');
	$element->getDecorator('label')->setOption('escape', false);
        
	$element = $this->getElement('comentario');
	$element->getDecorator('description')->setOption('escape', false);
        
	$element = $this->getElement('comentar');
    	$element->getDecorator('description')->setOption('escape', false);
    }
}
