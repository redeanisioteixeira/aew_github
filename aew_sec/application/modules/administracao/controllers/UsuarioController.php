<?php
/**
 * controller para gerenciamento de acoes de edicao de usuario
 */
class Administracao_UsuarioController extends Sec_Controller_Action
{
    public function init()
    {
        /* @var $acl Sec_Controller_Action_Helper_Acl */
        parent::init();
        $acl = $this->getHelper('Acl');
        $visitanteAction = array('home');
        $acl->allow(Aew_Model_Bo_UsuarioTipo::VISITANTE, $visitanteAction);
        $editorAction = array('municipios','trocar-senha', 'exibir');
        $acl->allow(Aew_Model_Bo_UsuarioTipo::EDITOR, $editorAction);
        $administradorAction = array('listar', 'editar', 'apagar', 'adicionar', 'componentes-curriculares','relatorio','listar-usuarios');
        $acl->allow(Aew_Model_Bo_UsuarioTipo::ADMINISTRADOR, $administradorAction);
        $this->setLinkListagem('/administracao/usuario/listar');
        $this->setLinkExibicao('/administracao/usuario/exibir/id/');
        $this->setActionAdicionar('/administracao/usuario/adicionar');
        $this->setActionEditar('/administracao/usuario/editar/id/');
        $this->setActionApagar('/administracao/usuario/apagar/id/');
        $this->setActionSalvar('/administracao/usuario/salvar/id/');
    }
    /**
     * Carrega JS e CSS para o método HeadScript
     * @return Zend_View_Helper_HeadScript 
     */
    function initScripts()
    {
        parent::initScripts();
        $this->view->headScript()->appendFile('/assets/js/functions-load-ajax.js','text/javascript')
                    ->headScript()->appendFile('/assets/plugins/datepicker/js/bootstrap-datepicker.min.js','text/javascript')
                    ->headScript()->appendFile('/assets/plugins/datepicker/locales/bootstrap-datepicker.pt-BR.min.js','text/javascript');

        $this->view->headLink()->appendStylesheet('/assets/plugins/datepicker/css/bootstrap-datepicker3.standalone.css');

    }
    /**
     * Redireciona a listar 
     * @return Zend_View
     */
    public function homeAction()
    {
        $this->_redirect($this->getLinkListagem());
    }
    /**
     * Lista Usuarios
     * @return Zend_View
     */
    public function listarAction()
    {
        $this->setPageTitle('Usuários');
        $this->listarUsuariosAction();
    }
    /**
     * Renderiza bloco html de usuario
     * @return Zend_View
     */
    public function listarUsuariosAction()
    {
        if($this->isAjax())
            $this->disableLayout();
        
        $filtro = new Administracao_Form_FiltroUsuario();
        $filtro->populate($this->getPost());
        
        $pagina = $this->getParam('pagina',1);
        
    	$limite = 20;
        $usuarioBo = new Aew_Model_Bo_Usuario();
        
        $usuarioBo->exchangeArray($filtro->getValues());
        
        $nome = $filtro->getElement('buscarUsuario')->getValue();
        
        $options = array();
        $options['where']["lower(sem_acentos(usuario.nomeusuario)) like lower(sem_acentos(?)) OR lower(sem_acentos(usuario.email)) like lower(sem_acentos(?)) OR lower(sem_acentos(usuario.emailpessoal)) like lower(sem_acentos(?))"] = "%$nome%";
        
        $usuario = $usuarioBo->select($limite, $pagina, $options, true);
        $usuario = $usuarioBo->getAsPagination($usuario, $pagina, $limite, 5);
        
        $this->view->usuarios = $usuario;
        $this->view->urlPaginator = $this->view->url(array( 'module' => 'administracao', 'controller' => 'usuario', 'action' => 'listar-usuarios'));
        $this->view->href = $this->opcoesAcessoConteudo($usuarioBo);
        $this->view->filtro = $filtro;

    }
    /**
     * Gera relatório de usuários
     * @return Zend_View arquivo PDF
     */
    public function relatorioAction()
    {
	//$this->disableLayout();
	$this->disableRender();
        
	$this->setPageTitle('Usuários');

        $filtro = new Administracao_Form_FiltroUsuario();
        $filtro->populate($this->getPost());
        
        $usuarioBo = new Aew_Model_Bo_Usuario();
        $nome = $filtro->getElement('buscarUsuario')->getValue();
        
        $options = array();
        $options['where']["lower(sem_acentos(usuario.nomeusuario)) like lower(sem_acentos(?)) OR lower(sem_acentos(usuario.email)) like lower(sem_acentos(?)) OR lower(sem_acentos(usuario.emailpessoal)) like lower(sem_acentos(?))"] = "%$nome%";
        $usuarios = $usuarioBo->select(0, 0, $options);        
        
        $this->view->objetos = $usuarios;
		$result = $this->view->render('usuario/relatorio.php');	
		include "Mpdf/mpdf.php";
		$mpdf = new mPDF('','A4','','',5,5,12,10,5,5,'L');
		    
		$mpdf->SetHTMLHeader('<h1>Lista de usuários</h1>');
		    
		$mpdf->SetAuthor('Rede Anísio Teixeira');
		$mpdf->SetWatermarkImage($this->view->baseUrl().'/assets/img/img_marcadagua.png', 0.8, 'D', 'D');
		    
		$mpdf->showWatermarkImage = true;
		$mpdf->setFooter('{PAGENO} de    {nbpg}');
		$mpdf->WriteHTML($result);
		$mpdf->Output('relatorio_usuario.pdf', 'D');
    }
    /**
     * Troca senha de usuário
     * @return Zend_View
     */
    public function trocarSenhaAction()
    {
        $this->setPageTitle('Trocar Senha');

    	$usuarioBo = new Aew_Model_Bo_Usuario();
        $id = $this->getParam('id', false);

		$usuario = $this->getLoggedUserObject();
		if($usuario->getId() != $id && !$usuario->isAdmin())
		{
			$this->flashError('Não possui permissão para acessar este contéudo','/');
		}
        
    	$usuarioBo->setId($id);
    	if(!$usuarioBo->selectAutoDados())
        {
    	    $this->flashError('Usuário não encontrado.', $this->getLinkListagem());
    	}
        
        $form = new Administracao_Form_TrocarSenha();
        $form->setAction('/administracao/usuario/trocar-senha/id/'.$id);
        $form->populate($usuarioBo->toArray());
        
        if($this->isPost())
        {
            if($form->isValid($this->getPost()))
            {
                if(!$this->salvarSenha($form))
                {
                    $form->reset();
                }
            }
        }
        
        $this->view->usuario = $usuarioBo;
        $this->view->trocarSenha = $form;
    }
    /**
     * Salva mudanças da senha
     * @param Sec_Form $form
     */
    public function salvarSenha(Sec_Form $form)
    {
        $usuarioBo = new Aew_Model_Bo_Usuario();
        if($usuarioBo->forcarTrocarSenha($form->getValue('idusuario'), $form->getValue('novaSenha')))
        {
            $this->flashMessage('Senha alterada com sucesso.');
        } 
        else 
        {
            $this->flashError('A senha atual fornecida é inválida.');
        }
        $this->_redirect($this->getLinkExibicao($form->getValue('idusuario')));
    }
    /**
     * Exibe usuário
     * @return Zend_View
     */
    public function exibirAction()
    {
        $this->setPageTitle('Exibir Usuário');
    	$id = $this->getParam('id', false);
        
	$usuarioBo = new Aew_Model_Bo_Usuario();
    	$usuarioBo->setId($id);
        if(!$usuarioBo->selectAutoDados())
        {
            $this->flashError('Registro não encontrado.');
            $this->_redirect($this->getLinkListagem());
        }
        
        $this->view->href = $this->opcoesAcessoConteudo($usuarioBo);
	$this->view->usuario = $usuarioBo;
    }
    /**
     * Adiciona usuário
     * @return Zend_View
     */
    public function adicionarAction()
    {
        $this->setPageTitle('Adicionar Usuário');
    	$form = new Administracao_Form_Usuario();
        
        $form->setAction($this->getActionAdicionar());
		if($this->getRequest()->isPost())
        {
	    if($form->isValid($this->getRequest()->getPost()))
            {
                $this->salvarUsuario($form);
            }
	}

        $this->view->adicionar = $form;
    }
    /**
     * Exibe usuário
     * @return Zend_View
     */
    public function editarAction()
    {
        $this->setPageTitle('Editar Usuário');
        $id = $this->getParam('id', false);

        $usuario = new Aew_Model_Bo_Usuario();
    	$usuario->setId($id);
    	if(!$usuario->selectAutoDados())
        {
    	    $this->flashError('Usuário não encontrado.');
            $this->_redirect($this->getLinkListagem());
    	}
        
        $form = new Administracao_Form_Usuario();
        
        $form->setAction($this->getActionEditar($usuario->getId()));
        $form->populate($usuario->toArray());

	if($this->getRequest()->isPost())
        {
	    if($form->isValid($this->getRequest()->getPost()))
            {
                $this->salvarUsuario($form);
            }
	}
        
        $this->view->usuario = $usuario;
        $this->view->editar = $form;
    }
    /**
     * Salvar Usuário
     * @param type $form
     */
    public function salvarUsuario($form)
    {
        $txt = ($form->getValue('idusuario') ? 'editado' : 'inserido');
        
        $usuario = new Aew_Model_Bo_Usuario();
        $usuario->exchangeArray($form->getValues());
        if($usuario->save($form->getValues()))
        {
            $this->flashMessage('Usuário '.$txt.' com sucesso.');
            $this->_redirect($this->getLinkExibicao($usuario->getId()));
        } 
        else 
        {
            $this->flashError('Nao foi possivel salvar dados');
            if($form->getValue('idusuario'))
            {
                $this->_forward('editar');
            } 
            else 
            {
                $this->_forward('adicionar');
            }
        }
    }
    /**
     * Apaga usuário
     * @return Zend_View
     */
    public function apagarAction()
    {
        $this->setPageTitle('Apagar usuário');
        $form = new Aew_Form_Apagar();
        
        $id = $this->getParam('id', false);
       	$usuario = new Aew_Model_Bo_Usuario();
        $usuario->setId($id);
        if (!$usuario->selectAutoDados())
        {
            $this->flashError('Nenhuma registro passado.');
            $this->_redirect($this->getLinkListagem());
        }
        
        $form->setAction($this->getActionApagar($id));
        if($this->isPost())
        {
            if(false != $this->getPost('nao'))
            {
                $this->_redirect($this->getLinkExibicao($id));
            }
            if($usuario->delete())
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
        
        $form->getElement('mensagem')->setValue('Tem certeza que deseja apagar este usuário?');
        
        $this->view->apagar = $form;
        $this->view->usuario = $usuario;
    }
    
}
