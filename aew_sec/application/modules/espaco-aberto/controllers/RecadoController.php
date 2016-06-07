<?php

class EspacoAberto_RecadoController extends Sec_Controller_Action_EspacoAberto
{
    public function init()
    {
        parent::init();
        $acl = $this->getHelper('Acl');
        $amigoDaEscolaAction = array('home','listar', 'apagar', 'editar', 'salvar', 'enviar-resposta','lista-recados');
        $acl->allow(Aew_Model_Bo_UsuarioTipo::AMIGO_DA_ESCOLA, $amigoDaEscolaAction);
        
        $this->setLinkListagem('/espaco-aberto/recado/listar');
        $this->setLinkExibicao('espaco-aberto/recado/exibir/id/');
        $this->setActionApagar('/espaco-aberto/recado/apagar/id/');
    }
    /**
     * Carrega JS e CSS para o método HeadScript
     * @return Zend_View_Helper_HeadScript 
     */
    public function initScripts() {
        parent::initScripts();
        $this->view->headScript()
                ->appendFile('/assets/js/espaco-aberto/recado.js','text/javascript');
    }
    /**
     * Redireciona para listar
     */
    public function homeAction()
    {
        if($this->isAjax())
        {
            $this->disableLayout ();
        }
        $this->_forward('listar');
    }
    /**
     * Lista de recados
     * @return Zend_View
     */
    public function listarAction()
    {
        $this->setPageSubTitle('Recados');
        if($this->isAjax())
        {
            $this->disableLayout();
        }
        
        $this->carregarPerfil();
        if($this->getTipoPagina() != Sec_Constante::USUARIO)
        {
            $this->flashError('Houve um erro no processamento da URL.');
            $this->_redirect('espaco-aberto');
        }
        $form = new EspacoAberto_Form_RecadoAdicionar();
        $form->setAction('/espaco-aberto/recado/salvar'.$this->getPerfilUrl());
        if($this->isPost())
        {
            $form->isValid($this->getPost());
        }
        $this->listaRecadosAction();
        $this->view->form_recado   = $form;
        $this->view->usuarioPerfil = $this->usuarioPerfil;
    }
    /**
     * Renderiza o recado
     * @return Zend_View
     */
    public function listaRecadosAction()
    {
        if($this->isAjax())
        {
            $this->disableLayout();
        }
        $limite = 10;
        
        $usuarioPerfil = $this->getPerfiluserObject();
        $pagina = $this->getParam('pagina', 1);
        
        $recados = array();
        $this->view->urlPaginator = $this->view->url(array('module' => 'espaco-aberto', 'controller' => 'recado', 'action' => 'lista-recados','id'=>$usuarioPerfil->getId()));
        $this->view->idDiv = 'recados-lista';
        
        if($usuarioPerfil)
        {
            $recados = $usuarioPerfil->selectRecadosRecebidos($limite,$pagina,null,true); 
            $recados = $usuarioPerfil->getAsPagination($recados,$pagina,$limite,5);
        }
        
        $this->view->recados = $recados;
    }
    
    /**
     * Resposta ao recado recebido
     * @return Zend_View
     */    
    public function enviarRespostaAction()
    {
	$this->getHelper('layout')->disableLayout();
	$usuario = $this->getLoggedUserObject();
	$idUsuario = $usuario->getId();
	$idRecado  = $this->getParam('id',null);
    	$form = new EspacoAberto_Form_RecadoAdicionar();
        
    	$form->setAction('/espaco-aberto/recado/salvar/usuario/'.$idUsuario.'/id/'.$idRecado)
                ->setAttrib('class', 'resposta');
        
	$idRelacionado = $form->getElement('idrecadorelacionado');
        $idRelacionado->setValue($idRecado);
	$form->getElement('idrecadorelacionado')->setAttrib('class', 'idrecadorelacionadol'.$idRecado);
	if($this->isPost()){
	    $form->isValid($this->getPost());
	}
	$this->view->form_responder = $form;
    }
    
    /**
     * Salva o recado via AJAX
     * @return flash message ou redireciona
     */
    public function salvarAction()
    {
        $this->carregarPerfil();
        $usuarioLogado = $this->getLoggedUserObject();
        $usuarioPerfill = $this->getPerfiluserObject();
        
        $form = new EspacoAberto_Form_RecadoAdicionar();
        if($this->isPost())
        {
            $recado = new Aew_Model_Bo_UsuarioRecado(); // ++++
            if($form->isValid($this->getPost()))
            {
                if($form->getValue('idrecado') > 0)
                {
                    $txt = 'editado';
                } 
                else 
                {
                    $txt = 'inserido';
                }
                $recado->exchangeArray($form->getValues());
                $recado->setDataenvio(new Sec_Date());
                $recado->getUsuarioAutor()->setId($usuarioLogado->getId());
                $recado->setUsuarioDestinatario($usuarioPerfill);
                if($recado->save())
                {
                    $this->flashMessage('Recado '.$txt.' com sucesso.');
                    $this->_redirect($this->getLinkListagem());
                }
                else
                {
                    $this->flashError('Não foi possível inserir recado.');
                }
            }
        } 
        else 
        {
            $this->flashError('Nenhuma informação para salvar.');
            
        }
        
        $this->_redirect($this->getLinkListagem());
    }
    
    
    /**
     * Apagar recado
     * @return flash message ou redireciona 
     */
    public function apagarAction()
    {
        $usuarioPerfil = $this->getPerfiluserObject();
	$id = $this->getParam('id', false);

        $recado = new Aew_Model_Bo_UsuarioRecado();
        $recado->setId($id);
        
        $result = $recado->delete();
        if($result)
        {
            $this->flashMessage('Recado apagado com sucesso');
        } 
        else 
        {
            $this->flashError('Recado não pode ser apagado');
        }

        $this->view->recados = $this->view->action('lista-recados','recado','espaco-aberto', array('usuario' => $usuarioPerfil->getId()));
    }
    
}