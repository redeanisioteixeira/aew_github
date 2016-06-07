<?php

/**
 * BO da entidade AmbienteDeApoioCategoria
 */

class Aew_Model_Bo_AmbienteDeApoioCategoria extends Sec_Model_Bo_Abstract
{
    
    protected $nomeambientedeapoiocategoria; //varchar(150)
    protected $ambientesDeApoio = array();
    
    /**
     * 
     * @return string
     */
    public function getNome() {
        return $this->nomeambientedeapoiocategoria;
    }

    /**
     * 
     * @param string $nome
     */
    public function setNome($nome) {
        $this->nomeambientedeapoiocategoria = $nome;
    }

    /**
     * 
     * @return array
     */
    public function getAmbientesDeApoio() {
        return $this->ambientesDeApoio;
    }

    /**
     * 
     * @param array $ambientesDeApoio
     */
    public function setAmbientesDeApoio($ambientesDeApoio) {
        $this->ambientesDeApoio = $ambientesDeApoio;
    }

    /**
     * @return string
     */
    function getUrlExibir()
    {
        return "/ambientes-de-apoio/ambientes/categorias/id/".$this->getId();
    }
    
    /**
     * @param Zend_Form $form
     * @return boolean
     */
    function uploadIcone(Zend_Form $form)
    {
        
        $dirty = false;
        /* @var $file Zend_Form_Element_File */

        $file = $form->icone;
        if($file->isUploaded())
        {
            $dirty = true;
            $ext = Sec_File::getExtension($file->getFileName());
            
            $file->addFilter('Rename', array('target' => $this->getIconeDirectory().DS.$this->getId().'.'.$ext, 'overwrite' => true));
            
            if($file->receive())
                return true;
        }        
        return false;
        
    }
    
    /**
     * retorna o diretorio para os icones
     * @return string
     */
    function getIconeDirectory()
    {
        if(CONTEUDO_PATH):
            $path = MEDIA_PATH.DS.CONTEUDO_PATH.DS.'ambientes-apoio'.DS.'imagem-associada'.DS.'categoria';
        ELSE:
            $path = $_SERVER['DOCUMENT_ROOT'].DS.'ambientes-apoio'.DS.'imagem-associada'.DS.'categoria';
            //$path = MEDIA_PATH.DS.'ambientes-apoio'.DS.'imagem-associada';
        endif;
        
        return $path;
    }    
    
    /**
     * retorna o diretorio para os icones
     * @return string
     */
    function getIconeUrl()
    {
        if(CONTEUDO_PATH):
            $path = DS.CONTEUDO_PATH.DS.'ambientes-apoio'.DS.'imagem-associada'.DS.'categoria';
        ELSE:
            $path = DS.'ambientes-apoio'.DS.'imagem-associada'.DS.'categoria';
            //$path = DS.'ambientes-apoio'.DS.'imagem-associada';
        endif;
        
        return $path;
    }    
    

    /**
     * retorna o imagem associada do ambiente de apoio
     * @return string
     */
    function getImagemAssociada()
    {
        $imagem = $this->getId().'.png';
        if(!file_exists($this->getIconeDirectory().DS.$imagem)):
            $imagem = 'padrao.png';
        endif;
        
        if(CONTEUDO_PATH):
            $path = DS.CONTEUDO_PATH.DS.'ambientes-apoio'.DS.'imagem-associada'.DS.'categoria'.DS.$imagem;
        ELSE:
            $path = DS.'ambientes-apoio'.DS.'imagem-associada'.DS.'categoria'.DS.$imagem;
            //$path = DS.'ambientes-apoio'.DS.'imagem-associada';
        endif;
        
        return $path;
    }    
    
    /**
     * 
     * @param int $num
     * @param int $offset
     * @param Aew_Model_Bo_AmbienteDeApoio $ambienteDeApoio
     * @return type
     */
    function selectAmbientesDeApoio($num=0,$offset = 0,  Aew_Model_Bo_AmbienteDeApoio $ambienteDeApoio=null)
    {
        if(!$ambienteDeApoio)
           $ambienteDeApoio = new Aew_Model_Bo_AmbienteDeApoio();
        
        $ambienteDeApoio->setAmbientedeApoioCategoria($this);
        $this->setAmbientesDeApoio($ambienteDeApoio->select($num, $offset));
        return $this->getAmbientesDeApoio();
    }
    
    /**
     * retorna a url da pagina de visuaizacao
     * de ambientes desta categoria
     * @param type $comunidadeId
     * @return string
     */
    function getUrlCategoriaAmbientes($comunidadeId='')
    {
        $url = '/ambientes-de-apoio/ambientes/listar/categoria/'.$this->getId();
        if($comunidadeId)
        {
            $url = '/ambientes-de-apoio/ambientes/listar/categoria/'.$this->getId().'/comunidade/'.$comunidadeId;   
        }
        return $url;
    }
    
    /**
     * 
     * @param Aew_Model_Bo_Usuario $usuario
     * @return type
     */
    function getUrlEditar(Aew_Model_Bo_Usuario $usuario)
    {
        if($this->getId())
        if(($usuario->getUsuarioTipo()->getNome()== Aew_Model_Bo_UsuarioTipo::SUPER_ADMINISTRADOR)||
          ($usuario->getUsuarioTipo()->getNome()== Aew_Model_Bo_UsuarioTipo::ADMINISTRADOR)||
          ($usuario->getUsuarioTipo()->getNome()== Aew_Model_Bo_UsuarioTipo::COORDENADOR))  
        {
            return  '/ambientes-de-apoio/categoria/editar/id/'.$this->getId(); 
        }
        
    } 
    
    /**
     * @param Aew_Model_Bo_Usuario $usuario
     * @return string
     */
    function getUrlApagar(Aew_Model_Bo_ItemPerfil $usuario)
    {
        if(($usuario->getUsuarioTipo()->getNome()==Aew_Model_Bo_UsuarioTipo::ADMINISTRADOR) ||
          ($usuario->getUsuarioTipo()->getNome()==Aew_Model_Bo_UsuarioTipo::SUPER_ADMINISTRADOR))
        return '/ambientes-de-apoio/categoria/apagar/id/'.$this->getId();		
    }

    /**
     * cria objeto de acesso ao banco de dados
     * @return \Aew_Model_Dao_AmbienteDeApoioCategoria
     */
    protected function createDao() {
        $dao =  new Aew_Model_Dao_AmbienteDeApoioCategoria();
        return $dao;
    }

}