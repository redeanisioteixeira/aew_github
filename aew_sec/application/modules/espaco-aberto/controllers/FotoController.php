<?php

class EspacoAberto_FotoController extends Sec_Controller_Action_EspacoAberto
{
    public function init()
    {
        parent::init();
        $acl = $this->getHelper('Acl');
        $amigoDaEscolaAction = array('home','listar', 'exibir', 'apagar', 'apagarcomentario', 'adicionar', 'editar', 'salvar', 'comentar','listar-comentarios');
        $acl->allow(Aew_Model_Bo_UsuarioTipo::AMIGO_DA_ESCOLA, $amigoDaEscolaAction);
        $urlTipo = '';
    	$id = $this->getParam('usuario', false);
    	if ($id != null)
        {
            $urlTipo = '/usuario/'.$id;
    	} 
        else 
        {
            $id = $this->getParam('comunidade', false);
            $urlTipo = '/comunidade/'.$id;
    	}
        $this->setLinkListagem('/espaco-aberto/album/exibir/id/'.$this->getParam('album'));
        $this->setLinkExibicao('/espaco-aberto/foto/exibir'.$urlTipo.'/id/'.$this->getParam('id'));
        $this->setActionApagar('/espaco-aberto/foto/apagar'.$urlTipo.'album/'.$this->getParam('album').'/id/');
        $this->setActionComentar('/espaco-aberto/foto/comentar'.$urlTipo.'/id/'.$this->getParam('id'));
    }
    /**
     * Redireciona para listar Fotos 
     */
    public function homeAction()
    {
        $this->_forward('listar');
    }
    /**
     * Listagem de fotos do album
     * @return Zend_View
     */
    public function listarAction()
    {
        $this->setPageTitle('Espaço Aberto');
        $this->setPageSubTitle('Álbuns');
        if($this->isAjax())
            $this->disableLayout ();
        $perfilObject = $this->getPerfiluserObject(); 
    	$pagina = $this->getParam('pagina', 1);
        $fotos = $perfilObject->selectFotosDosAlbuns(8, $pagina);
        $this->view->fotos = $perfilObject->getAsPagination($fotos, $pagina, 8);
    }
    /**
     * Exibe foto e comentários da foto
     * @return Zend_View  
     */
    public function exibirAction()
    {
	if($this->isAjax())
        $this->disableLayout();
        $id = $this->getParam('id', false);
        $fotoComentario = new EspacoAberto_Form_Comentario();
        $usuario = $this->getPerfiluserObject();
        $fotoComentario->setAction('/espaco-aberto/foto/comentar/'.$usuario->perfilTipo().'/'.$usuario->getId());
        $fotoComentario->getElement('tipocomentario')
                       ->setValue(2);
        $fotoComentario->getElement('idfoto')
                       ->setValue($id);
        $this->view->formComentario = $fotoComentario; //formulario
        $this->listarComentariosAction();
    }
    /**
     * Lista comentários da foto via AJAX
     * @return Zend_View
     */
    function listarComentariosAction()
    {
        if($this->isAjax())
        {
            $this->disableLayout ();
        }
        $id = $this->getParam('id', false);
        $pagina = $this->getParam('pagina', false);
        $idAlbum = $this->getParam('album', false);
        $album= new Aew_Model_Bo_Album();
        $album->setId($idAlbum);
        $usuario = $this->getPerfiluserObject();
        $albumUsuario = $usuario->selectAlbuns(1, 0, $album);
        // foto específica
        $foto = new Aew_Model_Bo_Foto();
        $foto->setId($id);
        $fotoUsuario = $albumUsuario->selectFotos(1,0,$foto);
        $usuarioPerfil = $this->getPerfiluserObject();
        $fotoUsuario = $usuarioPerfil->selectFotosDosAlbuns(1,0, $foto);
        $comentarios = $fotoUsuario->selectComentarios(5,$pagina,null,null,true); 
        $comentarios = $fotoUsuario->getAsPagination($comentarios,$pagina,5,5);
        $this->view->urlPaginator = $this->view->url(array('module' => 'espaco-aberto', 
            'controller' => 'foto',
            'action' => 'listar-comentarios',
            'id'=>$fotoUsuario->getId()));
        $this->view->idDiv = 'comentarios-album';
        $this->view->foto  = $fotoUsuario; 
        $this->view->comentarios = $comentarios;
    }
    /**
     * Adiciona comentários da foto
     * @return Renderiza listar comentarios
     */
    public function comentarAction()
    {
        if($this->isAjax())
        $this->disableLayout();
        $this->carregarPerfil();
        $usuarioLogado = $this->getLoggedUserObject(); 
        $usuarioPerfil = $this->getPerfiluserObject();
        $form = new EspacoAberto_Form_Comentario();
        if($this->isPost())
        {   
            if($form->isValid($this->getPost()))
            {   
		$valores = $form->getValues();
                $foto = new Aew_Model_Bo_Foto();
                $foto->setId($form->getValue('idfoto'));
                $fotoUsuario = $usuarioPerfil->selectFotosDosAlbuns(1,0, $foto);
                $mensagem = trim($valores['mensagem']);
                $comentario = $usuarioLogado->insertComentarioFoto($mensagem, $fotoUsuario,$form->getValue('tipoalbum'));
                if($comentario)
                {    
                    $this->flashMessage('Comentário salvo com sucesso!.');
                }
                else
                {
                    $this->flashError('Erro ao salvar.');
                }
            }
        } 
        $this->listarComentariosAction();
        $this->renderScript('/usuario/listar-comentarios.php');
    }
    /**
     * Método para apagar foto via AJAX
     * @return Zend_View
     */
    public function apagarAction()
    {
        if($this->isAjax())
            $this->disableLayout ();
        $this->setPageTitle('Espaço Aberto');
        $this->setPageSubTitle('Apagar foto');
        $this->carregarPerfil();
        if(false == $this->getPerfilModerador()){
            $this->flashError('Você não possui permissão para executar essa ação.');
            $this->_redirect($this->getLinkListagem());
        }
        $id = $this->getParam('id', false);
        if($this->getTipoPagina() == Sec_Constante::USUARIO){
    	    $foto = new Aew_Model_Bo_UsuarioAlbumFoto();
    	} 
        elseif($this->getTipoPagina() == Sec_Constante::COMUNIDADE)
        {
    	    $foto = new Aew_Model_Bo_ComunidadeAlbumFoto(); // ++++
    	}
        $foto->setId($id);
        if (!$foto->selectAutoDados())
        {
            $this->flashError('Nenhum registro encontrado.');
        }
        if($foto->delete())
        {
            if($this->isAjax())
            {
                echo json_encode(array('success'=>true,'html'=>'Foto removida com sucesso.'));die();
            }
            else
            {
                $this->flashMessage('Registro apagado com sucesso.');
                $this->_redirect($this->getLinkListagem());
            }
        } 
        else 
        {
            if($this->isAjax())
            {
                echo json_encode(array('false'=>true,'html'=>'Erro ao executar ação.'));die();
            }
            else
            {
                $this->flashError('Houve um problema ao tentar apagar o registro.');
                $this->_redirect($this->getLinkListagem());
            }
        }
        $this->view->objeto = $foto;
    }
    /**
     * Método para apagar comntário
     * @return flash message
     */
    public function apagarcomentarioAction()
    {
        $this->setPageTitle('Espaço Aberto');
        $this->setPageSubTitle('Apagar Comentário');
        $this->carregarPerfil();
        $usuario = $this->getLoggedUserObject();
	$id = $this->getParam('id', false);
        $idcomentario = $this->getParam('idcomentario', false);
        $comentario = new Aew_Model_Bo_AlbumComentario(); // ++++
        $comentario->setId($idcomentario);
        if (!$comentario->selectAutoDados())
        {
            $this->flashError('Nenhum registro encontrado.');
        }
        if(!$this->getPerfilDono())
        {
            $this->flashError('Você não possui permissão para executar essa ação.');
        }
        if(!$comentario->delete())
        {
            $this->flashMessage('Registro apagado com sucesso.');
        } 
    }
    /**
     * Adiciona Foto ao album
     * @return Zend_View
     */
    public function adicionarAction()
    {
        $this->setPageTitle('Espaço Aberto');
        $this->setPageSubTitle('Adicionar foto');
        $this->carregarPerfil();
        if(false == $this->getPerfilModerador()){
            $this->flashError('Você não possui permissão para executar essa ação.');
            $this->_redirect($this->getLinkListagem());
        }
    	$form = new EspacoAberto_Form_FotoAdicionar();
    	$form->setAction('/espaco-aberto/foto/salvar'.$this->getPerfilUrl().'/album/'.$this->getParam('album'));
	if($this->isPost())
        {
	    $form->isValid($this->getPost());
	}
	$this->view->adicionar = $form;
    }
    /**
     * Edita uma foto 
     * @return Zend_View
     */
    public function editarAction()
    {
        $this->setPageTitle('Espaço Aberto');
        $this->setPageSubTitle('Editar foto'); // ++++
        $this->carregarPerfil();

        if(false == $this->getPerfilModerador()){
            $this->flashError('Você não possui permissão para executar essa ação.');
            $this->_redirect($this->getLinkListagem());
        }
    	$form = new EspacoAberto_Form_FotoAdicionar(); // ++++
        $usuario = $this->getPerfiluserObject();
        $form->setAction('/espaco-aberto/foto/salvar'.$this->getPerfilUrl().'/album/'.$this->getParam('album')); // ++++
        $form->removeElement('foto');
    	$id = $this->getParam('id', false);
        $foto = new Aew_Model_Bo_Foto();
        $foto->setId($id);
        $foto = $usuario->selectFotos(1,0,$foto);
    	if(!$foto->selectAutoDados())
        {
    	    $this->flashError('Foto não encontrada.'); // ++++
            $this->_redirect($this->getLinkListagem());
    	}
    	$form->populate($foto->toArray());
	if($this->isPost())
        {
	    $form->isValid($this->getPost());
	}
	$this->view->editar = $form;
    }
    /**
     * Salva alterações
     * @return flash message ou redireciona
     */
    public function salvarAction()
    {
        $this->carregarPerfil();
        if(false == $this->getPerfilModerador())
        {
            $this->flashError('Você não possui permissão para executar essa ação.');
            $this->_redirect($this->getLinkListagem());
        }
        $form = new EspacoAberto_Form_FotoAdicionar();
        $albumId = $this->getParam('album', false);
        $album = new Aew_Model_Bo_Album();
        $foto = new Aew_Model_Bo_Foto();
        $album->setId($albumId);
        $usuarioPerfil = $this->getPerfiluserObject();
        if($this->isPost())
        {
            if($form->isValid($this->getPost()))
            {
                if($album->isCotaAlcancada($this->getPerfilId()))
                {
                    $this->flashError('Seu limite de fotos foi alcançado.');
		    $this->_redirect($this->getLinkListagem());
                }
                $foto->exchangeArray($form->getValues());
                $foto->setFotoFile($form->foto);
                if($usuarioPerfil->insertFotoAlbum($foto, $album))
                {
                    $this->flashMessage('Foto salva com sucesso.');
                    $this->_redirect($this->getLinkListagem());
                }
                else 
                {
                    $this->flashError('Foto.');
                }
            } 
            else 
            {
                $this->flashError('Formulario Invalido.');
                if($form->getValue($campo) > 0)
                {
                    $this->_forward('editar');
                } 
                else 
                {
                    $this->_forward('adicionar');
                }
            }
        } 
        else 
        {
            $this->flashError('Nenhuma informação para salvar.');
        }
        $this->_redirect($this->getLinkListagem());
    }
}
