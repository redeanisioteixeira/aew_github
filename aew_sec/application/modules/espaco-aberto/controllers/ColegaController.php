<?php
class EspacoAberto_ColegaController extends Sec_Controller_Action_EspacoAberto
{
    public function init(){
        
        parent::init();
        $acl = $this->getHelper('Acl');
        $amigoDaEscolaAction = array('home','listar', 'convidar', 'remover', 'recusar', 'aceitar','lista-colegas');
        $acl->allow(Aew_Model_Bo_UsuarioTipo::AMIGO_DA_ESCOLA, $amigoDaEscolaAction);
        $this->setLinkListagem('/espaco-aberto/colega/listar');
        $this->setLinkExibicao('espaco-aberto/perfil/feed');
        $this->setActionApagar('/espaco-aberto/colega/remover/id/');
    }
    /**
     * Redireciona a listar 
     */
    public function homeAction()
    {
        $this->_forward('listar');
    }
    /**
     * Lista 
     */
    public function listarAction()
    {
    	$this->setPageTitle('Meus colegas');
        
        $this->carregarPerfil();
        
        $this->view->totalColegas = count($this->view->colegas);
        if($this->usuarioPerfil->getId() != $this->usuarioLogado->getId())
        {
            $paginaPai[] = array('titulo' => $this->usuarioPerfil->getNome(), 'url' => $this->usuarioPerfil->getLinkPerfil());
            $this->view->paginaPai = $paginaPai;
        }
        if($this->usuarioLogado->isDonoPerfil($this->usuarioPerfil))
            $this->view->colegasPendentes = $this->usuarioPerfil->selectColegasPendentes(); 
        
        $this->listaColegasAction();
    }
    
    public function listaColegasAction()
    {
        if($this->isAjax())
        {
            $this->disableLayout();
        }
        $pagina = $this->getParam('pagina',1);
        $filtro = $this->getParam('filtro');
        $colega = null;
        $options = array();
        if($filtro)
        {
            $options['where']['lower(sem_acentos(usuario.nomeusuario)) LIKE (sem_acentos(?)) OR lower(sem_acentos(usuario2.nomeusuario)) LIKE (sem_acentos(?))'] = "%".$filtro."%";
        }
        if($this->isAjax())
        {
            $this->disableLayout();
            $this->view->colegas = $this->usuarioPerfil->selectColegas(8, $pagina, null, $options);
        }
        else
        {
            $this->view->meusColegas = $this->usuarioPerfil->selectColegas(8, $pagina, null, $options);
        }
        $this->view->usuarioPerfil = $this->usuarioPerfil;
    }

    public function convidarAction()
    {
        $this->carregarPerfil();
        $usuario = $this->getLoggedUserObject();
        $usuarioPerfil = $this->getPerfiluserObject();
        $usuarioColega = new Aew_Model_Bo_Usuario();
        $idcolega = $this->getParam('usuario');
        $usuarioColega->setId($idcolega);
        if(!$usuarioColega->selectAutoDados())
        {
            $this->flashError('Usuario não encontrado');
        }
        if($this->getTipoPagina() == Sec_Constante::USUARIO)
        {
            if($usuario->isColega($usuarioPerfil))
            {
                $this->flashMessage('Esse usuário já faz parte de sua rede.',$this->getLinkExibicao());
            }
            else if($usuario->isColegaPendente($usuarioPerfil))
            {
                $this->flashMessage('Esse usuário já foi convidado.',$this->getLinkExibicao());
            }
    	}
        $result = $usuario->insertSolicitacaoColega($usuarioColega);
    	if(true == $result)
        {
            $usuario->avisarUsuario($usuarioColega);
            $this->flashMessage('Você convidou o usuário para ser seu colega.',$this->getLinkExibicao());
    	} 
        else 
        {
    	    $this->flashMessage('Erro ao fazer inserção.', $this->getLinkExibicao());
    	}
        $this->view->render('perfil/feed.php');
    }

    public function removerAction()
    {
        $this->setPageTitle('Espaço Aberto');
        $this->setPageSubTitle('Remover colega');
        $this->carregarPerfil();
        $form = new Aew_Form_Apagar();
        $usuario = $this->getPerfiluserObject();
        $colega = new Aew_Model_Bo_Usuario();
    	$id = $this->getParam('id', false); 
        $colega->setId($id); 
        if(!$colega->selectAutoDados() )
        {
    	    $this->flashError('Usuário não encontrado.', 'espaco-aberto');
    	}
        if(!$this->getPerfilModerador() )
        {
            $this->flashError('Você não possui permissão para executar essa ação.',$this->getLinkListagem());
        }
        $form->setAction($this->getActionApagar($id));
        if($this->isPost())
        {
            if($this->getPost('Nao'))
            {
                $this->_redirect($this->getLinkListagem());
            }
            else if($usuario->deleteColega($colega))
            {
                $this->flashMessage('Colega removido com sucesso.');
                $this->_redirect($this->getLinkListagem());
            } 
            else 
            {
                $this->flashError('Houve um problema ao tentar apagar o registro.');
                $this->_redirect($this->getLinkListagem());
            }
        }
        $this->view->form = $form;
        $this->view->colega = $colega;
        $this->view->nome = $colega->getNome();
    }

    public function recusarAction()
    {
    	$this->carregarPerfil();
        $usuario = $this->getLoggeUserObject();
        $id = $this->getParam('id', false);
        $colega = new Aew_Model_Bo_Usuario();
        if((!$colega->selectAutoDados()) || (!$usuario->isColega($colega)))
        {
            $this->flashError('Nenhuma registro passado.');
            $this->_redirect($this->getLinkListagem());
        }
    	if($usuario->deleteColega($colega))
        {
            $this->flashMessage('Colega recusado com sucesso.',$this->getLinkExibicao());
    	} 
        else 
        {
    	    $this->flashMessage('Houve um erro na operação', $this->getLinkExibicao());
    	}
    }

    public function aceitarAction()
    {
        $this->carregarPerfil();
        $usuario = $this->getPerfiluserObject();
        $id = $this->getParam('id', false);
        $colega = new Aew_Model_Bo_Usuario();
        $colega->setId($id);
        
        if(!$colega->selectAutoDados())
        {
            $this->flashError('Nenhuma registro passado.');
            $this->_redirect($this->getLinkListagem());
        }
        if($usuario->aceitarRequisicaoColega($colega))
        {
            
            $linkRecado = $this->view->url(array('module' => 'espaco-aberto', 'controller' => 'recado','action' => 'listar', 'usuario' => $colega->getId()), null, true);
            $this->flashMessage('Você aceitou o usuário como seu colega. <a href="'. $linkRecado.'">Deixe um recado aqui.</a>',
                                $this->getLinkExibicao());
    	} 
        else 
        {
    	    $this->flashMessage('Houve um erro na inserção', $this->getLinkExibicao());
    	}
    }
}
