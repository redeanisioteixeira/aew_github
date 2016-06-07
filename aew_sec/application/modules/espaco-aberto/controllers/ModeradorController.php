<?php

class EspacoAberto_ModeradorController extends Sec_Controller_Action_EspacoAberto
{
    public function init()
    {
        parent::init();
        $acl = $this->getHelper('Acl');
        $amigoDaEscolaAction = array('home','listar', 'remover', 'adicionar', 'bloquear', 'desbloquear', 'aprovar');
        $acl->allow(Aew_Model_Bo_UsuarioTipo::AMIGO_DA_ESCOLA, $amigoDaEscolaAction);
        
        $this->setLinkListagem('espaco-aberto/moderador/listar');
        $this->setLinkExibicao('espaco-aberto/comunidade/exibir');
        $this->setActionApagar('/espaco-aberto/moderador/remover/id/');
    }
    /**
     * Redireciona a listar 
     */
    public function homeAction()
    {
        $this->_forward('listar');
    }
    /**
     * lista de moderadores da comunidade
     * @return Zend_View
     */
    public function listarAction()
    {
        $this->setPageTitle('Lista de moderadores');
        
        $this->carregarPerfil();
        if(false == $this->getPerfilDono())
        {
            $this->flashError('Você não possui permissão para executar essa ação.');
            $this->_redirect($this->getLinkExibicao());
        }
    	$pagina = $this->getParam('pagina', 0);
    	$limite = 15;
        $comunidade = $this->getPerfiluserObject();
        $this->view->comunidade = $comunidade;

        $this->view->moderadores = $comunidade->selectModeradores($limite,$pagina);
        $this->view->membros = $comunidade->selectMembrosAtivos($limite,$pagina);
        $this->view->bloqueados = $comunidade->selectMembrosBloqueados($limite,$pagina);
        $this->view->pendentes = $comunidade->selectMembrosPendentes($limite,$pagina);
    }
    /**
     * Adicionar moderador da comunidade
     * @return Redireciona
     */
    public function adicionarAction()
    {
    	$this->carregarPerfil();
        
        $usuario = $this->getLoggedUserObject();
        $comunidade = $this->getPerfiluserObject();
        
        $id = $this->getParam('id', false);
        
        if(!$comunidade instanceof Aew_Model_Bo_Comunidade)
            $this->flashError('Comunidade não encontrada.', $this->getLinkListagem());    
        
        $novoModerador = new Aew_Model_Bo_ComuUsuario();
        $novoModerador->setId($id);
        if(!$novoModerador->selectAutoDados())
        {
            $this->flashError('Usuário não encontrado.', 'espaco-aberto');
            $this->_redirect($this->getLinkListagem());
        }
        
        if($this->getTipoPagina() == Sec_Constante::COMUNIDADE)
        {
            if(false == $this->getPerfilDono())
            {
                $this->flashError('Você não possui permissão para executar essa ação.');
                $this->_redirect($this->getLinkExibicao());
            }
            else if($usuario->insertModerador($novoModerador, $comunidade))
            {
                $this->flashMessage('Usuário(a) '.$novoModerador->getNome().' adicionado como moderador.', $this->getLinkListagem());
            }
            else
            {
                $this->flashError('Não foi possível inserir novo moderador.');
                $this->_redirect($this->getLinkExibicao());
            }
    	}
    }
    /**
     * Remover moderador da comunidade
     * @return Zend_View
     */
    public function removerAction()
    {
    	$this->setPageTitle('Espaço Aberto');
        $this->setPageSubTitle('Remover moderador');
        $this->carregarPerfil();
        $usuario = $this->getLoggedUserObject();
        $idusuario = $this->getParam('id', false);
        $comunidade = $this->getPerfiluserObject();
        $moderador = new Aew_Model_Bo_ComuUsuario();
        $moderador->setId($idusuario);
        if(false == $this->getPerfilDono()){
            $this->flashError('Você não possui permissão para executar essa ação.');
            $this->_redirect($this->getLinkExibicao());
        }
        if (!$moderador->selectAutoDados())
        {
            $this->flashError('Nenhum registro passado.', $this->getLinkListagem());
        }
        if(!$comunidade instanceof Aew_Model_Bo_Comunidade)
        {
    	    $this->flashError('Comunidade não encontrada.', 'espaco-aberto');
    	}
        $form = new Aew_Form_Apagar();
        $form->setAction($this->getActionApagar($idusuario));
        if($this->isPost())
        {
            if(false != $this->getPost('nao'))
            {
                $this->_redirect($this->getLinkListagem());
            }
            if($comunidade->deleteModerador($usuario,$moderador))
            {
                $this->flashMessage('Membro removido dos moderadores com sucesso.', $this->getLinkListagem());
            } 
            else 
            {
                $this->flashError('Houve um problema ao tentar remover o usuário.',
                                  $this->getLinkListagem());
            }
        }
        $this->view->form = $form;
        $this->view->objeto = $moderador;
        $this->view->comunidade = $comunidade;
    }
    /**
     * Aprovar usuário da comunidade
     * @return Redireciona
     */
    public function aprovarAction()
    {
    	$this->carregarPerfil();
        
        $usuario = $this->getLoggedUserObject();
        $comunidade = $this->getPerfiluserObject();
        
        
        $id = $this->getRequest()->getParam('id',null);
        
        if(!$id)
        {
            $this->flashError('Usuário não encontrado.', 'espaco-aberto');
        }
        
        $membroPendente = new Aew_Model_Bo_ComuUsuario();
        $membroPendente->setId($id);
        $membroPendente = $membroPendente->select(1);
        
        if(!$membroPendente)
        {
            $this->flashError('Usuário não encontrado', 'espaco-aberto');
        }
        
        if($this->getTipoPagina() == Sec_Constante::COMUNIDADE)
        {
            if($comunidade->aprovarMembroPendente($usuario, $membroPendente))
            {
                $this->flashMessage('Usuario Apovado');
            }
            else 
            {
                $this->flashError('Não foi possível aprovar usuário');
            }
            $this->_redirect($this->getLinkListagem());
    	}
    }
    /**
     * Bloqueia usuário da comunidade
     * @return Redireciona
     */
    public function bloquearAction()
    {
    	$this->carregarPerfil();
        $usuario = $this->getLoggedUserObject();
        
        $id = $this->getParam('id', false);
        $idComunidade = $this->getParam('comunidade', false);
        
        $usuarioABloquear = new Aew_Model_Bo_ComuUsuario();
        $usuarioABloquear->setId($id);
        
        $comunidade = new Aew_Model_Bo_Comunidade();
        $comunidade->setId($idComunidade);
        if(!$usuarioABloquear->selectAutoDados())
        {
            $this->flashError('Usuário não encontrado', 'espaco-aberto');
        }

        if(!$this->getPerfilDono()){
            $this->flashError('Você não possui permissão para executar essa ação.', $this->getLinkExibicao());
        }

        if($this->getTipoPagina() == Sec_Constante::COMUNIDADE)
        {
            if($comunidade->bloquearMembro($usuario, $usuarioABloquear))
            {
                $this->flashMessage('Usuário desbloqueado com sucesso.', $this->getLinkListagem());
            } 
            else 
            {
                $this->flashError('Houve um problema ao tentar bloquear o usuário', $this->getLinkListagem());
            }
        }
    }
    /**
     * Desbloqueia usuário da comunidade
     * @return Redireciona
     */
    public function desbloquearAction()
    {
    	$this->carregarPerfil();
        $usuario = $this->getLoggedUserObject();
        
        $id = $this->getParam('id', false);
        $idComunidade = $this->getParam('comunidade', false);
        
        $usuarioBloqueado = new Aew_Model_Bo_ComuUsuario();
        $usuarioBloqueado->setId($id);
        
        $comunidade = new Aew_Model_Bo_Comunidade();
        $comunidade->setId($idComunidade);
        if(!$usuarioBloqueado->selectAutoDados())
        {
            $this->flashError('Usuário não encontrado', 'espaco-aberto');
        }

        if(!$this->getPerfilDono()){
            $this->flashError('Você não possui permissão para executar essa ação', $this->getLinkExibicao());
        }

        if($this->getTipoPagina() == Sec_Constante::COMUNIDADE)
        {
            if($comunidade->desbloquearMembro($usuario, $usuarioBloqueado))
            {
                $this->flashMessage('Usuário desbloqueado com sucesso', $this->getLinkListagem());
            } 
            else 
            {
                $this->flashError('Houve um problema ao tentar desbloquear o usuário', $this->getLinkListagem());
            }
        }
    }
}