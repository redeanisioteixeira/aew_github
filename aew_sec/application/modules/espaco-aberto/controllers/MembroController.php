<?php

class EspacoAberto_MembroController extends Sec_Controller_Action_EspacoAberto
{
    public function init()
    {
        parent::init();
        $acl = $this->getHelper('Acl');
        $amigoDaEscolaAction = array('home','listar', 'apagar','lista-membros');
        $acl->allow(Aew_Model_Bo_UsuarioTipo::AMIGO_DA_ESCOLA, $amigoDaEscolaAction);

        $this->setLinkListagem('espaco-aberto/membro/listar');
        $this->setActionApagar('/espaco-aberto/membro/apagar/id/');
        
        if(!$this->usuarioPerfil instanceof Aew_Model_Bo_Comunidade)
        {
            $this->flashError('Comunidade não encontrada.');
            $this->_redirect('espaco-aberto');
        }
    }
    /**
     * Redireciona a listar
     */
    public function homeAction()
    {
        $this->_forward('listar');
    }
    /**
     * Lista de Membros da comunidade chama listaMembrosAction
     * @return Zend_View 
     */
    public function listarAction()
    {
        $this->setPageTitle('Membros da comunidade');
        $this->carregarPerfil();

        $paginaPai[] = array("titulo" => $this->usuarioPerfil->getNome(), "url" => $this->usuarioPerfil->getLinkPerfil());
        $this->view->paginaPai = $paginaPai;

        $this->listaMembrosAction();
    }
    /**
     * Retorna a vista do membro
     * @return Zend_View
     */
    public function listaMembrosAction()
    {
        if($this->isAjax())
        {
            $this->disableLayout();
        }
        
      	$pagina = $this->getParam('pagina',1);
        
        $this->carregarPerfil();
        
        $comunidadePerfil = $this->getPerfiluserObject();
        $this->view->membros = $comunidadePerfil->selectMembrosAtivos(8, $pagina, null);
        
    }
    /**
     * Apaga membro da comunidade
     * @return Zend_View
     */
    public function apagarAction()
    {
        $this->carregarPerfil();

        if(false == $this->getPerfilDono()){
            $this->flashError('Você não possui permissão para executar essa ação.');
            $this->_redirect($this->getLinkListagem());
        }
        $form = new Aew_Form_Apagar();
        $id = $this->getParam('id', false);
        $membro = new Aew_Model_Bo_ComuUsuario();
        $membro->setId($id);
        if (!$membro->selectAutoDados())
        {
            $this->flashError('Nenhuma registro passado.');
            $this->_redirect($this->getLinkListagem());
        }
        if(!$this->usuarioPerfil instanceof Aew_Model_Bo_Comunidade)
        {
    	    $this->flashError('Comunidade não encontrada.');
            $this->_redirect('espaco-aberto');
    	}
        $form->setAction($this->getActionApagar($id));
        if($this->isPost())
        {
            if($this->getPost('nao'))
            {
                $this->_redirect($this->getLinkListagem());
            }
            if (!$this->usuarioPerfil->deleteMembro($membro))
            {
                $this->flashError('Nenhum registro encontrado.');
                $this->_redirect($this->getLinkListagem());
            }
            else 
            {
                $this->flashMessage('Membro removido.');
                $this->_redirect($this->getLinkListagem());
            }
	}
        $this->setPageTitle('Remover membro ' . $membro->getNome());
        $this->view->form   = $form;
        $this->view->objeto = $membro;  
        $this->view->comunidade = $this->usuarioPerfil;
    }
}