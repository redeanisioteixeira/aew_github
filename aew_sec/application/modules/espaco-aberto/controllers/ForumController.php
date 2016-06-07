<?php

class EspacoAberto_ForumController extends Sec_Controller_Action_EspacoAberto
{
    public function init()
    {
        parent::init();
        $acl = $this->getHelper('Acl');
        $amigoDaEscolaAction = array('home','listar', 'exibir', 'resposta','responder', 'apagar', 'adicionar', 'editar', 'salvar', 'apagarmsg','lista-foruns','listar-mensagens-forum');
        $acl->allow(Aew_Model_Bo_UsuarioTipo::AMIGO_DA_ESCOLA, $amigoDaEscolaAction);
        $this->setLinkListagem('espaco-aberto/forum/listar');
        $this->setLinkExibicao('espaco-aberto/forum/exibir/id/');
        $this->setActionApagar('/espaco-aberto/forum/apagar/id/');
    }
    /**
     * Carrega JS e CSS para o método HeadScript
     * @return Zend_View_Helper_HeadScript 
     */
    public function initScripts() 
    {
        parent::initScripts();
        //$this->view->headScript()->appendFile('/assets/js/generals.js','text/javascript');
        $this->view->headScript()->appendFile('/assets/js/espaco-aberto/forumComentarios.js','text/javascript');
    }
    /**
     * Redireciona para listar 
     */
    public function homeAction()
    {
        $this->_forward('listar');
    }
    /**
     * Lista postagens do forum e formulário de comentários
     * @return Zend_View
     */
    public function listarAction()
    {
        if($this->isAjax()){$this->disableLayout();}
        $this->setPageTitle('Listagem de tópicos');
        $this->carregarPerfil();
    	$form = new EspacoAberto_Form_PesquisaForum();
        $form->setAction('/espaco-aberto/pesquisa/forum'.$this->getPerfilUrl());
	if($this->isPost()){ $form->isValid($this->getPost());}
        $this->listaForunsAction();
	$this->view->form = $form;
    }
    /**
     * Lista de postagens ou tópicos
     * @return Zend_View
     */
    public function listaForunsAction()
    {
        if($this->isAjax()){$this->disableLayout ();}
       	$pagina = $this->getParam('pagina', 1);
        $this->carregarPerfil();
        
        $comunidadePerfil = $this->getPerfiluserObject();
        $this->view->topicos = $comunidadePerfil->selectTopicos(5,$pagina);
        $this->view->comunidade = $comunidadePerfil;
    }
    /**
     * Exibe forum e formulário de comentários
     * @return Zend_View
     */
    public function exibirAction()
    {
        $this->carregarPerfil();
    	$id = $this->getParam('id', false);
        $usuario = $this->getLoggedUserObject();
        $comunidade = $this->getPerfiluserObject();
        if(false == $id)
        {
            $this->flashError('Tópico não encontrado.');
            $this->_redirect($this->getLinkListagem());
        }
    	$comunidadeTopico = new Aew_Model_Bo_ComuTopico();
        $comunidadeTopico->setId($id);
        $this->setPageTitle($comunidadeTopico->getTitulo()); // Seta o título
        $form = new EspacoAberto_Form_TopicoResposta();
        $form->getElement('idcomutopico')->setValue($comunidadeTopico->getId());
        $form->setAction('/espaco-aberto/forum/resposta/id/'.$comunidadeTopico->getId().$this->getPerfilUrl());
	if($this->isPost())
        {
	    $form->isValid($this->getPost());
	}
        if($comunidade instanceof Aew_Model_Bo_Comunidade)
        {
            $this->view->participante = $comunidade->isParticipante($usuario);
        }
        $this->listarMensagensForum();
        $this->view->form = $form;
	$this->view->topico = $comunidadeTopico->select(1);
	$this->view->usuario = $usuario;
        $this->view->comunidade = $comunidade;
    }
    /**
     * Lista todas as mensagens do tópico
     */
    function listarMensagensForum()
    {
        if($this->isAjax())
        {
            $this->disableLayout();
        }
        
        $this->carregarPerfil();
        $id = $this->getParam('id', false);
        $pagina = $this->getParam('pagina', false);
        $limit = 8;
        $comunidadeTopico = new Aew_Model_Bo_ComuTopico();
        $comunidadeTopico->setId($id);
        
        $comentarios = $comunidadeTopico->selectComentarios($limit,$pagina,null,true);
        $this->view->comentarios = $comunidadeTopico->getAsPagination($comentarios, $pagina, $limit);
        
    }
    /**
     * Adiciona novo tópico
     * @return Zend_VIew
     */
    public function adicionarAction()
    {
        $this->setPageTitle('Adicionar novo tópico');
        $this->carregarPerfil();
        $usuario = $this->getLoggedUserObject();
        $comunidade = $this->getPerfiluserObject();
        if(false == $this->getPerfilModerador() && 
           false==$comunidade->isParticipante($usuario))
        {
            $this->flashError('Você não possui permissão para executar essa ação.');
            $this->_redirect($this->getLinkListagem());
        }
    	$form = new EspacoAberto_Form_TopicoAdicionar();
    	$form->setAction('/espaco-aberto/forum/salvar'.$this->getPerfilUrl());
	if($this->isPost())
        {
	    $form->isValid($this->getPost());
	}
	$this->view->adicionar = $form;
        $this->view->comunidade = $comunidade;
    }
    /**
     * Edita tópico
     * @return Zend_View
     */
    public function editarAction()
    {
        $this->carregarPerfil();
        if(false == $this->getPerfilModerador()){
            $this->flashError('Você não possui permissão para executar essa ação.');
            $this->_redirect($this->getLinkListagem());
        }
    	$form = new EspacoAberto_Form_TopicoAdicionar(); // ++++
        $form->setAction('/espaco-aberto/forum/salvar'.$this->getPerfilUrl()); // ++++
        //$idComutopico = $this->getPost('idcomutopico', false); // ++++
    	$id = $this->getParam('id', false);
        $forum = new Aew_Model_Bo_ComuTopico();
        $comunidade = $this->getPerfiluserObject();
        $forum->setId($id);
    	if(!$forum->selectAutoDados())
        {
    	    $this->flashError('Nenhum tópico passado.'); // ++++
	    $this->_redirect($this->getLinkListagem());
    	}
    	$form->populate($forum->toArray());
	if($this->isPost())
        {
	    $form->isValid($this->getPost());
	}
        $this->setPageTitle('Editar'. $comunidade->getNome());
        $this->view->editar = $form;
        $this->view->comunidade = $comunidade;
    }
    /**
     * Salva o tópico
     * @return flash message ou redireciona
     */
    public function salvarAction()
    {
        $this->carregarPerfil();
        $usuario = $this->getLoggedUserObject();
        $form = new EspacoAberto_Form_TopicoAdicionar();
        $topico = new Aew_Model_Bo_ComuTopico();
        $comunidade = $this->getPerfiluserObject();
        $topico->getUsuarioAutor()->exchangeArray($usuario->toArray());
        if(false == $this->getPerfilModerador() && 
           false==$comunidade->isParticipante($usuario))
        {
            $this->flashError('Você não possui permissão para executar essa ação.');
            $this->_redirect($this->getLinkListagem());
        }
        if($this->isPost())
        {
            if($form->isValid($this->getPost()))
            {
                $topico->exchangeArray($form->getValues());
                if($form->getValue('idcomutopico') > 0)
                $txt = 'editado';
                else 
                $txt = 'inserido';
                if($comunidade->insertTopico($topico))
                {
                    $this->flashMessage('Tópico '.$txt.' com sucesso.');
                    $this->_redirect($this->getLinkExibicao($topico->getId()));
                }
                else
                {
                    $this->flashMessage('Erro ao inserir');
                }
            } 
            else 
            {
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
            $this->_redirect($this->getLinkListagem());
        }
    }
    /**
     * Apaga Tópico
     * @return Zend_View
     */
    public function apagarAction()
    {
        $this->setPageTitle('Espaço Aberto');
        $this->setPageSubTitle('Apagar tópico do fórum');
        $this->carregarPerfil();
        $comunidade = $this->getPerfiluserObject();
        if(false == $this->getPerfilModerador())
        {
	    $this->flashError('Você não possui permissão para executar essa ação.');
	    $this->_redirect($this->getLinkListagem());
	}
        $form = new Aew_Form_Apagar();
        $id = $this->getParam('id', false);
        $topico = new Aew_Model_Bo_ComuTopico();
        $topico->setId($id);
        if (!$topico->selectAutoDados())
        {
            $this->flashError('Nenhuma registro passado.');
            $this->_redirect($this->getLinkListagem());
        }
        $form->setAction($this->getActionApagar($id));
        if($this->isPost())
        {
            if($this->getPost('nao'))
            {
                $this->_redirect($this->getLinkListagem());
            }
            if($comunidade->deleteTopico($topico))
            {
                $this->flashMessage('Registro apagado com sucesso.');
	        $this->_redirect($this->getLinkListagem());
            } 
            else 
            {
                $this->flashError('Houve um problema ao tentar apagar o registro. O registro ainda está relacionado a outros registros.');
		$this->_redirect($this->getLinkListagem());
	    }
	}
        $this->view->form = $form;
        $this->view->objeto = $topico;
        $this->view->comunidade = $comunidade;
    }
    /**
     * Mensagem do topico 
     */
    public function respostaAction()
    {
        $this->carregarPerfil();
        $usuario = $this->getLoggedUserObject();
        $form = new EspacoAberto_Form_TopicoResposta();
        $id= $this->getParam('id');
        $comunidade= $this->getParam('comunidade');
        //$forum = new Aew_Model_Bo_ComuTopico();
        
        if($this->isPost())
        {
            if($form->isValid($this->getPost()))
            {
                $boMensagem = new Aew_Model_Bo_ComuTopicoMsg();
		$valores = $form->getValues();
                $mensagem = $this->getParam('mensagem');
                
                
                if ($mensagem!='' && $valores['pai']==0)
                {
                    $boMensagem->exchangeArray($valores);
                    $boMensagem->getUsuarioAutor()->exchangeArray($usuario->toArray());
                    if($boMensagem->insert())
	            {
                        $this->flashMessage('Resposta enviada com sucesso.');
                        $this->_redirect('/espaco-aberto/forum/exibir/id/'.$id.'/comunidade/'.$comunidade);
                    }
	        }
                else if($mensagem!='' && trim($valores['pai'])>0)
                {
                    $boMensagem->exchangeArray($valores);
                    $boMensagem->getUsuarioAutor()->exchangeArray($usuario->toArray());
                    if($boMensagem->insert())
	            {
                        $this->flashMessage('Resposta enviada com sucesso.');
                        $this->_redirect('/espaco-aberto/forum/exibir/id/'.$id.'/comunidade/'.$comunidade);
                    }
                }
	        else 
                {
                    $this->flashError('Inserir corpo da mensagem.');
                    $this->_redirect('/espaco-aberto/forum/exibir/id/'.$id.'/comunidade/'.$comunidade);
	        }
            } 
        } 
        $this->listarMensagensForum();
        $this->renderScript('/recado/lista-recados.php');

    }
    /**
     * Responder a comentario
     */
    public function responderAction()
    { 
        $this->disableLayout();
        
        $pai= $this->getParam('id');
        $topicoid = $this->getParam('topico');
        
        $formComentarioFilho = new EspacoAberto_Form_TopicoResposta();
        
        $formComentarioFilho->setAction('/espaco-aberto/forum/resposta/');
        $formComentarioFilho->getElement('pai')->setValue($pai);
        $formComentarioFilho->getElement('idcomutopico')->setValue($topicoid);
        
                
        $this->view->formValues = $this->getParams() ;
        $this->view->formResposta = $formComentarioFilho;
    }
    /**
     * Apaga mensagem
     * @return Zend_View
     */
    public function apagarmsgAction()
    {
        $this->disableLayout();
        $id = $this->getParam('id', false);
	$comunidade = $this->getParam('comunidade', false);
	$usuario = $this->getLoggedUserObject();
        
        $mensagem = new Aew_Model_Bo_ComuTopicoMsg(); // ++++
        $mensagem->setId($id);  
        
        if(!$mensagem->selectAutoDados() && !$usuario->isAdmin()){
            $this->flashError('Você não possui permissão para executar essa ação.');
	}else{
            $result = $mensagem->delete();
            if(true == $result)
            {
                $this->flashMessage('Registro apagado com sucesso.');
            }else{
               $this->flashError('Houve um problema ao tentar apagar o registro.'); 
            }
        
        }
        $this->view->comentarios = $this->view->action('listar','forum','espaco-aberto', array('comunidade' => $comunidade));
    }

   
}
