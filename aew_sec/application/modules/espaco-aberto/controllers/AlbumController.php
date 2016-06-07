<?php
class EspacoAberto_AlbumController extends Sec_Controller_Action_EspacoAberto
{
    public function init()
    {
        parent::init();
        $acl = $this->getHelper('Acl');

        $amigoDaEscolaAction = array('home','listar', 'exibir', 'apagar', 'apagarcomentario', 'adicionar', 'editar', 'salvar', 'comentario','listar-comentarios','lista-fotos');
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
        $this->setLinkListagem('espaco-aberto/album/listar/'.$urlTipo);
        $this->setLinkExibicao('espaco-aberto/album/exibir/'.$urlTipo);
        $this->setActionApagar('/espaco-aberto/album/apagar/'.$urlTipo);
        $this->setActionComentar('/espaco-aberto/album/comentario'.$urlTipo);
        
    }
    /**
     * Carrega JS e CSS para o método HeadScript
     * @return Zend_View_Helper_HeadScript 
     */
    public function initScripts() {
        parent::initScripts();
        $this->view->headScript()
                    ->appendFile('/assets/js/espaco-aberto/foto.js');
    }
    /**
     * redireciona a listar 
     * @return Zend_View
     */
    public function homeAction()
    {
        $this->_forward('listar');
    }
    /**
     * lista albums
     * @return Zend_View
     */
    public function listarAction()
    {
    	$this->setPageTitle('Listagem dos álbuns');
        
        $this->carregarPerfil();
        $usuarioPerfil = $this->getPerfiluserObject();
    	$pagina = $this->getParam('pagina', 0);
    	$albuns = $usuarioPerfil->selectAlbuns(10,$pagina);
        $quantidade= count($albuns);
            
        $this->view->nAlbums = $quantidade; 
	$this->view->albuns = $albuns;
        $this->view->perfil = $usuarioPerfil;
        $this->view->comunidade = $usuarioPerfil;
    }
    /**
     * Renderiza bloco de foto
     * @return Zend_View 
     */
    public function listaFotosAction()
    {
        if($this->isAjax())
            $this->disableLayout();
        if(!$this->usuarioPerfil)
        $this->usuarioPerfil = $this->getPerfiluserObject();
        $id = $this->getParam('id', false);
        $album = new Aew_Model_Bo_Album();
        $album->setId($id);
        $albumusuario  = $this->usuarioPerfil->selectAlbuns(1, 0, $album);
        if(!$albumusuario instanceof Aew_Model_Bo_Album)
        {   
            if($this->isAjax())
            {
                echo Zend_Json::encode(array('success'=>false)); die();
            }
            else
            {
                $this->flashError('Album não encontrado.');
                $this->_redirect($this->getLinkListagem());
            }
        }
        $pagina = $this->getParam('pagina', false);
        $this->listarComentariosAction();
        $this->view->fotos = $albumusuario->selectFotos(16,$pagina);
       	$this->view->album = $albumusuario;
        $this->view->admin = true;
    }
    /*
     * Exibe um album
     * @return Zend_View 
     */
    public function exibirAction()
    {
        $this->setPageTitle('Fotos do álbum');
        $id = $this->getParam('id', false);
        $this->carregarPerfil();
        $this->usuarioPerfil = $this->getPerfiluserObject();
        $usuarioPerfilTipo = $this->usuarioPerfil->perfilTipo();
        //formulario
        $albumComentario = new EspacoAberto_Form_Comentario();
        $albumComentario->setAction($this->getActionComentar($id));
        $albumComentario->setAttrib('id', 'formcomentario');
        
        $this->view->albumComentario = $albumComentario;
        $this->view->comunidade = $this->usuarioPerfil;
        $this->view->headScript()
                ->appendFile('/assets/js/comentarios.js')
                ->appendFile('/assets/js/readmore.js');
        $this->listaFotosAction();
        
    }
    /**
     * Adiciona novo album
     * @return Zend_View
     */
    public function adicionarAction()
    {
        $this->setPageTitle('Adicionar album');
        $this->carregarPerfil();
        $usuarioLogado = $this->getLoggedUserObject();
        if(false == $this->getPerfilModerador()){
            $this->flashError('Você não possui permissão para executar essa ação.',
                              $this->getLinkListagem());
        }
    	$form = new EspacoAberto_Form_AlbumAdicionar();
    	$form->setAction('/espaco-aberto/album/salvar'.$this->getPerfilUrl());
        $objetos = $usuarioLogado->selectAlbuns();
        if(count($objetos)>=10)
        {
            $this->flashError('Você só pode criar 10 álbuns.',$this->getLinkListagem());
        }
	if($this->isPost())
        {
	    $form->isValid($this->getPost());
	}
	$this->view->adicionar = $form;
        $this->view->comunidade = $this->getPerfiluserObject();
    }
    /**
     * Edita título do album
     * @return Zend_View 
     */
    public function editarAction()
    {
    	$this->setPageTitle('Editar álbum');
        
        $this->carregarPerfil();
        if(false == $this->getPerfilModerador())
        {
            $this->flashError('Você não possui permissão para executar essa ação.',
            $this->getLinkListagem());
        }
    	$form = new EspacoAberto_Form_AlbumAdicionar(); // ++++
        $form->setAction('/espaco-aberto/album/salvar'.$this->getPerfilUrl()); // ++++
        if($this->getTipoPagina() == Sec_Constante::USUARIO)
        {
            $album = new Aew_Model_Bo_UsuarioAlbum(); // ++++
    	} 
        elseif($this->getTipoPagina() == Sec_Constante::COMUNIDADE)
        {
    	    $album = new Aew_Model_Bo_ComunidadeAlbum(); // ++++
    	}
    	$id = $this->getParam('id', false);
        $album->setId($id);
        if(!$album->selectAutoDados())
        {
    	    $this->flashError('Album não encontrado.'); // ++++
            $this->_redirect($this->getLinkListagem());
    	}
    	$form->populate($album->toArray());
        $form->getElement('idalbum')->setValue($album->getId());
    	if($this->isPost())
        {
	    $form->isValid($this->getPost());
	}
	$this->view->editar = $form;
    }
    /**
     * Salva album action (AJAX)
     * @return flash message ou redireciona
     */
    public function salvarAction()
    {
    	$this->carregarPerfil();
        $usuarioPerfil = $this->getPerfiluserObject();
        $form = new EspacoAberto_Form_AlbumAdicionar();
        $album = new Aew_Model_Bo_Album();
        $album->setId($this->getParam('idalbum'));
        $usuario = $this->getLoggedUserObject();
        if($this->isPost())
        {
            if(false == $this->getPerfilModerador())
            {
                $this->flashError('Você não possui permissão para executar essa ação.',$this->getLinkListagem());
            }
            if($form->isValid($this->getPost()))
            {
                $album->exchangeArray($form->getValues());
                $album->setUsuarioDono($usuario);
                if($usuarioPerfil->saveAlbum($album))
                {
                    if($album->getId())
                     $txt = "salvo";
                    else
                    {
                        $txt = "atualizado";
                    }
                    $this->flashMessage('Álbum '.$txt.' com sucesso.');
                    $this->_redirect($this->getLinkListagem());
                    
                }
                else
                {
                    $this->flashError('Nao foi possivel editar album "'.$album->getTitulo().'"');
                    $this->_redirect($this->getLinkExibicao($album->getId()));
                }
            } 
            else 
            {
                $this->_forward('listar');
            }
        } 
        else 
        {
            $this->flashError('Nenhuma informação para salvar.');
            $this->_redirect($this->getLinkListagem());
        }
    }
    /**
     * Apaga album
     * @return Zend_View
     */
    public function apagarAction()
    {
    	$this->setPageTitle('Apagar álbum');
        
        if(false == $this->getPerfilModerador()){
            $this->flashError('Você não possui permissão para executar essa ação.',
                              $this->getLinkListagem());
        }
        $form = new Aew_Form_Apagar();
        $id = $this->getParam('id', false);
        $form->setAction($this->getActionApagar($id));
        $album = new Aew_Model_Bo_Album();
        $album->setId($id);
        $usuario = $this->getPerfiluserObject();
        $album = $usuario->selectAlbuns(1, 0, $album);
        if($this->isPost())
        {
            if($this->getPost('Nao'))
            {
                $this->_redirect($this->getLinkListagem());
            }
            $result = $usuario->deleteAlbum($album);
            if(true == $result)
            {
                $this->flashMessage('Registro apagado com sucesso.');
                $this->_redirect($this->getLinkListagem());
            } 
            else 
            {
                $this->flashError('Houve um problema ao tentar apagar o registro.');
                $this->_redirect($this->getLinkListagem());
            }
        }    
        $this->view->form = $form;
        $this->view->objeto = $album;
    }
    /**
     * Lista dos comentários do album
     * @return Zend_View
     */
    public function listarComentariosAction()
    {
        if($this->isAjax())
            $this->disableLayout ();
        $usuarioPerfil = $this->getPerfiluserObject();
        $pagina = $this->getParam('pagina', false);
        $id = $this->getParam('id', false);
        $album = new Aew_Model_Bo_Album();
        $album->setId($id);
        $album = $usuarioPerfil->selectAlbuns(1,0,$album);
        $comentarios = $album->selectComentarios(6,$pagina,null,null,true);
        $this->view->urlPaginator = $this->view->url(array('module' => 'espaco-aberto','controller' => 'album','action' => 'listar-comentarios'));
        $comentarioPaginacao = $album->getAsPagination($comentarios,$pagina,6);
        $comentarioPaginacao->setPageRange(1);
        $this->view->comentarios = $comentarioPaginacao;
    }
    /**
     * Adiciona comentário
     * @return renderiza Zend_View 
     */
    public function comentarioAction()
    {
        if($this->isAjax())
            $this->disableLayout ();
    	$form = new EspacoAberto_Form_Comentario();
    	$this->carregarPerfil();
        $usuarioLogado = $this->getLoggedUserObject();
        $usuarioPerfil = $this->getPerfiluserObject();
        $id = $this->getParam('id', false);
        $album = new Aew_Model_Bo_Album();
        $album->setId($id);
        $albumUsuario = $usuarioPerfil->selectAlbuns(1, 0, $album);//album do usuario|comunidade 
        if($this->isPost())
        {
            if($form->isValid($this->getPost()))
            {
		$valores = $form->getValues();
                $mensagem = trim($valores['mensagem']);
                if($mensagem!="")
                {
                    $comentario = $usuarioLogado->insertComentarioAlbum($mensagem, $albumUsuario);
                    if($comentario)
	            {
                        $this->flashMessage('Comentário postado com sucesso.'); 
                    }
                    else
                    {
                        $this->flashError('Erro ao inserir mensagem.'); 
                    }
	        }
            } 
            else 
            {
                $this->flashError(implode('\n', $form->getMessages()));
            }
        } 
        $this->listarComentariosAction();
        $this->renderScript('/usuario/listar-comentarios.php');
    }

    /**
     * Apagar comentário
     * @return Renderiza Zend_View
     */
    public function apagarcomentarioAction()
    {
	$this->setPageTitle('Espaço Aberto');
	$this->setPageSubTitle('Apagar Comentário');
	$this->carregarPerfil();
	$usuario = $this->getLoggedUserObject();
	$idcomentario = $this->getParam('idcomentario', false);
	$comentario = new Aew_Model_Bo_AlbumComentario(); // ++++
        $comentario->setId($idcomentario);
        if (!$comentario->selectAutoDados()){
            $this->flashError('Nenhum registro encontrado.');
        }
        if(false == $this->getPerfilDono() && $comentario->getUsuarioAutor()->getId() != $usuario->getId()){
            $this->flashError('Você não possui permissão para executar essa ação.');
        }
        if(!$comentario->delete())
        {
            $this->flashError('Houve um problema ao tentar apagar o registro. '.
             			'O registro ainda está relacionado a outros registros.');
        } 
        else 
        {
            $this->flashMessage('Registro apagado com sucesso.');
        }
        $this->listarComentariosAction();
        $this->renderScript('/usuario/listar-comentarios.php');
    }
}