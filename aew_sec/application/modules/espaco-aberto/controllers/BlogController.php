<?php

class EspacoAberto_BlogController extends Sec_Controller_Action_EspacoAberto
{
    public function init()
    {
        parent::init();
        $acl = $this->getHelper('Acl');
        $amigoDaEscolaAction = array('home','listar', 'exibir', 'apagar', 'apagarcomentario', 'adicionar', 'editar', 'salvar', 'comentar','lista-blogs');
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
        $this->setLinkListagem('espaco-aberto/blog/listar');
        $this->setLinkExibicao('espaco-aberto/blog/exibir/id/');
        $this->setActionApagar('/espaco-aberto/blog/apagar/id/');
        $this->setActionComentar('/espaco-aberto/blog/comentar'.$urlTipo.'/id/');
    }
    /**
     * Redireciona a listar artígos
     */
    public function homeAction()
    {
        $this->_forward('listar');
    }
    /**
     * Lista artígos do blog
     * @return Zend_View
     */
    public function listarAction()
    {
    	$this->setPageTitle('Meus artigos');
        
        $this->carregarPerfil();
        $this->view->usuarioPerfil = $this->usuarioPerfil;
        $this->view->comunidade = $this->usuarioPerfil;
        $this->listaBlogsAction();
        $this->view->url();
    }
    /**
     * Renderiza o conteúdo do artígo (post)
     * @return Zend_View
     */
    public function listaBlogsAction()
    {
        if($this->isAjax())
            $this->disableLayout ();
        $usuarioPerfil = $this->getPerfiluserObject();
        $filtro = $this->getParam('filtro');
        $blog = null;
        if($filtro)
        {
            $blog = new Aew_Model_Bo_UsuarioBlog();
            $blog->setTitulo($filtro);
        }
        $pagina = $this->getParam('pagina');
        $this->view->blogs = $usuarioPerfil->selectBlogs(5,$pagina,$blog);
    }
    /**
     * Exibe um artígo (post)
     * @return Zend_View
     */
    public function exibirAction()
    {
        $this->carregarPerfil();
        $form = new EspacoAberto_Form_BlogComentario();
    	
        $id = $this->getParam('id', false);
        if($this->getTipoPagina() == Sec_Constante::USUARIO){
    	    $blog = new Aew_Model_Bo_UsuarioBlog(); // ++++
    	    $form->getElement('tipo')->setValue(1);
    	} 
        elseif($this->getTipoPagina() == Sec_Constante::COMUNIDADE)
        {
    	    $blog = new Aew_Model_Bo_ComunidadeBlog(); // ++++
    	    $form->getElement('tipo')->setValue(2);
    	}
        $blog->setId($id);
        if(!$blog->selectAutoDados())
        {
            $this->flashError('Artigo não encontrado.');
            $this->_redirect($this->getLinkListagem());
        }
    	$comentarios = $blog->selectComentarios(6);
    	$form->setAction($this->getActionComentar($id));
        $this->setPageTitle($blog->getTitulo()); // titulo
        
        
        $this->view->form = $form;
	$this->view->post = $blog;
        $this->view->comunidade = $this->getPerfiluserObject();
	$this->view->comentarios = $comentarios;
    }
    /**
     * Adiciona um artígo (post)
     * @return Zend_View
     */
    public function adicionarAction()
    {
        $this->setPageTitle('Adicionar novo artigo');
        $this->carregarPerfil();

        if(false == $this->getPerfilModerador()){
            $this->flashError('Você não possui permissão para executar essa ação.',
                              $this->getLinkListagem());
        }
    	$form = new EspacoAberto_Form_BlogAdicionar();
    	$form->setAction('/espaco-aberto/blog/salvar'.$this->getPerfilUrl());
        if($this->isPost())
        {
            $form->isValid($this->getPost());
        }
        $this->view->comunidade = $this->getPerfiluserObject();
        $this->view->adicionar = $form;
    }
    /**
     * Edita um artígo (post) 
     * @return Zend_View
     */
    public function editarAction()
    {
    	$this->setPageTitle('Editar artigo');
        
        $this->carregarPerfil();
        $usuarioPerfil = $this->getPerfiluserObject();
        if(false == $this->getPerfilModerador()){
            $this->flashError('Você não possui permissão para executar essa ação.',
            $this->getLinkListagem());
        }
        
    	$form = new EspacoAberto_Form_BlogAdicionar(); // ++++
        $form->setAction('/espaco-aberto/blog/salvar'.$this->getPerfilUrl()); // ++++
        $blog = new Aew_Model_Bo_Blog();
        
    	$id = $this->getParam('id', false);
        
        $blog->setId($id);
        
        $blog = $usuarioPerfil->selectBlogs(1, 0, $blog);
        
        
        if(!$blog)
        {
    	    $this->flashError('Artigo de blog não encontrado');
            $this->_redirect($this->getLinkListagem());
    	}
        
        
    	$form->populate($blog->toArray());
        $form->getElement('idblog')->setValue($blog->getId());
        
	if($this->isPost())
        {
	    $form->isValid($this->getPost());
	}
        
        $this->setPageTitle($blog->getTitulo());
        
        $this->view->comunidade = $this->getPerfiluserObject();
	$this->view->editar = $form;
    }
    /**
     * Salva mudanças feitas pelo usuário
     * @return flash message ou redireciona 
     */
    public function salvarAction()
    {
    	$this->carregarPerfil();
        $usuarioPerfil = $this->getPerfiluserObject();
        $form = new EspacoAberto_Form_BlogAdicionar();
        $blog = new Aew_Model_Bo_Blog();
        if(false == $this->getPerfilModerador())
        {
            $this->flashError('Você não possui permissão para executar essa ação.',$this->getLinkListagem());
        }
        if($this->isPost())
        {
            if(false == $this->getPerfilDono())
            {
	        $this->flashError('Você não possui permissão para executar essa ação.');
                $this->_redirect($this->getLinkListagem());
	    }
            if($form->isValid($this->getPost()))
            {
                $blog->exchangeArray($form->getValues());
                $blog->setId($form->getValue('idblog'));
                $blog->setUsuarioCriador($this->getLoggedUserObject());
                $blogusuario = $usuarioPerfil->saveBlog($blog);
                if($blogusuario)
                {
                    $this->flashMessage('Artigo salvo com sucesso.');
                    $this->_redirect($this->getLinkExibicao($blogusuario->getId()));
                }
                else
                {
                    $this->flashMessage('Não foi possível inserir artigo.');
                    $this->_redirect("listar");
                }
            }
            else 
            {
                $this->flashError('Capos obrigatorios não preenchidos');
                $this->_redirect('/espaco-aberto/blog/adicionar');
            }
        } 
        else 
        {
            $this->flashError('Nenhuma informação para salvar.');
            $this->_redirect($this->getLinkListagem());
        }
    }
    /**
     * Apaga um artígo 
     * @return Zend_View
     */
    public function apagarAction()
    {
     	$this->setPageTitle('Espaço Aberto');
        $this->setPageSubTitle('Apagar artigo do blog');
        $this->carregarPerfil();
        $usuario = $this->getPerfiluserObject();
        if(false == $this->getPerfilModerador())
        {
            $this->flashError('Você não possui permissão para executar essa ação.',
            $this->getLinkListagem());
        }
        $form = new Aew_Form_Apagar();
        $id = $this->getParam('id', false);
        $blog = new Aew_Model_Bo_Blog();
        $blog->setId($id);
        $blog = $usuario->selectBlogs(1, 0, $blog);
        $form->setAction($this->getActionApagar($id));
        if($this->isPost())
        {
            if($this->getPost('Nao'))
            {
                $this->_redirect($this->getLinkListagem());
            }
            if(!$usuario->deleteBlog($blog))
            {
                $this->flashError('Houve um problema ao tentar apagar o registro.');
                $this->_redirect($this->getLinkListagem());
            }
            else 
            {
                $this->flashMessage('Registro apagado com sucesso.');
                $this->_redirect($this->getLinkListagem());
            } 
        }
        $this->view->usuarioPerfil = $this->getPerfiluserObject();
        $this->view->form = $form;
        $this->view->blog = $blog;
    }
    /**
     * Adiciona um comentário ao artígo
     * @return flash message ou redireciona
     */
    public function comentarAction()
    {
    	$form = new EspacoAberto_Form_BlogComentario();
    	$this->setPageTitle('Espaço Aberto');
        $this->setPageSubTitle('Comentar evento do blog');
        $this->carregarPerfil();
        $id = $this->getParam('id', false);
        $blog = new Aew_Model_Bo_Blog();
        $blog->setId($id);
        $comentario = new Aew_Model_Bo_BlogComentario();
        $usuario = $this->getLoggedUserObject();
        if($this->isPost())
        {
            if($form->isValid($this->getPost()))
            {
		$valores = $form->getValues();
		if (trim($valores['mensagem'])!="")
                {
                    $comentario->exchangeArray($form->getValues());
                    $result=$usuario->insertComentarioBlog($comentario, $blog);
                    if($result)
	            {
                        $this->flashMessage('Resposta enviada com sucesso.');
                        $this->_redirect($this->getLinkExibicao($id));
                    }
	        }
	        else 
                {
	            $this->flashError('Inserir corpo da mensagem.');
                    $this->_forward('exibir');
	        }
            } 
            else 
            {
                $this->_forward('exibir');
            }
        }
        else 
        {
            $this->flashError('Nenhuma informação para salvar.');
            $this->_redirect($this->getLinkExibicao($id));
        }

    }
    /**
     * Apaga o comentário do artígo 
     * @return flash message ou redireciona
     */
    public function apagarcomentarioAction()
    {
        $this->setPageTitle('Espaço Aberto');
        $this->setPageSubTitle('Apagar Comentário');
        $this->carregarPerfil();
        $usuario = $this->getLoggedUserObject();
	$idblog = $this->getParam('id', false);
        $idcomentario = $this->getParam('idcomentario', false);
        $comentario = new Aew_Model_Bo_BlogComentario();
        $comentario->setId($idcomentario);
        if (!$comentario->selectAutoDados())
        {
            $this->flashError('Nenhum registro encontrado.');
            $this->_redirect($this->getLinkExibicao($idblog));
        }
        if(!$usuario->isAdmin()|| !$comentario->isAutor($usuario) || !$comentario->delete())
        {
            $this->flashError('Houve um problema ao tentar apagar o registro.');
            $this->_redirect($this->getLinkExibicao($idblog));
        }
        else
        {
            $this->flashMessage('Registro apagado com sucesso.');
            $this->_redirect($this->getLinkExibicao($idblog));
        } 
    }

    
    
}
