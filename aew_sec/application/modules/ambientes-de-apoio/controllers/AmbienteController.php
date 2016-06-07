<?php
/****
 * Controle do ambiente de apoio
 */
class AmbientesDeApoio_AmbienteController extends Sec_Controller_Action 
{
    protected $_linkExibicao = 'ambientes-de-apoio/ambiente/exibir/id/';
    protected $_linkListagem = 'ambientes-de-apoio/ambientes/categorias';
    protected $_actionApagar = '/ambientes-de-apoio/ambiente/apagar/id/';

    public function init()
    {
        /* @var $acl Sec_Controller_Action_Helper_Acl */
        parent::init();
        $acl = $this->getHelper('Acl');

        $visitanteAction = array('home', 'exibir','relacionados');
        $acl->allow(Aew_Model_Bo_UsuarioTipo::VISITANTE, $visitanteAction);

        $amigoDaEscolaAction = array('exibir','favorito','remover-favorito');
        $acl->allow(Aew_Model_Bo_UsuarioTipo::AMIGO_DA_ESCOLA, $amigoDaEscolaAction);

        $administradorAction = array('apagar', 'adicionar', 'editar', 'salvar', 'destaque', 'removerdestaque');
        $acl->allow(Aew_Model_Bo_UsuarioTipo::ADMINISTRADOR, $administradorAction);
    }
    /**
     * script live-preview
     */
    function initScripts()
    {
        parent::initScripts();
        $this->view->headLink()->appendStylesheet("/assets/plugins/jquery-live-preview/css/live-preview.css");
        $this->view->headScript()
                ->appendFile("/assets/plugins/jquery-live-preview/js/jquery-live-preview.js","text/javascript")
                ->appendFile('/assets/js/jquery.form.js','text/javascript');
    }
    /**
     * redirecionamento para ambiente de apoio
     * @return Zend_View
     */
    public function homeAction()
    {
        $this->_redirect('ambientes-de-apoio');
    }
    
    /**
     * lista de exebição de conteudo do ambiente 
     * @return Zend_View 
     */
    public function destaqueAction()
    {
        $linkListagem = 'ambientes-de-apoio';
        $linkExibicao = 'ambientes-de-apoio/ambiente/exibir/id/';
	$id = $this->getRequest()->getParam('id');
        $ambienteApoio = new Aew_Model_Bo_AmbienteDeApoio();
        $ambienteApoio->setId($id);
        $conteudo = $ambienteApoio->select(1);
        if(!$conteudo instanceof Aew_Model_Bo_AmbienteDeApoio)
        {
            $this->flashError('Nenhum registro encontrado.');
            $this->_redirect($linkListagem);
        }
        $ambienteApoio->setDestaque(true);
        if($ambienteApoio->update())
        {
            $this->flashMessage('Conteúdo adicionado a lista de destaques.');
            $this->_redirect($linkExibicao.$id);
        } 
        else 
        {
            $this->flashError('Não foi possível adicionar o conteúdo a sua lista de destaques, verifique se a lista já possui 5 conteúdos.');
            $this->_redirect($linkExibicao.$id);
        }
    }

    /**
     * remove conteudo do ambiente de apoio
     * @return Zend_View 
     */
    
    public function removerdestaqueAction()
    {
        $linkListagem = 'ambientes-de-apoio';
        $linkExibicao = 'ambientes-de-apoio/ambiente/exibir/id/';
	$id = $this->getRequest()->getParam('id',-1);
        $ambienteApoio = new Aew_Model_Bo_AmbienteDeApoio();
        $ambienteApoio->setId($id);
        if(!$ambienteApoio->selectAutoDados())
        {
            $this->flashError('Nenhum registro encontrado.');
            $this->_redirect($linkListagem);
        }
        $ambienteApoio->setDestaque("FALSE");
        $result = $ambienteApoio->update();
        if(true == $result)
        {
            $this->flashMessage('Conteúdo removido da lista de destaques.');
            $this->_redirect($linkExibicao.$id);
        } 
        else 
        {
            $this->flashError('Não foi possível remover o conteúdo a sua lista de destaques.');
            $this->_redirect($linkExibicao.$id);
        }
    }
    /**
     * exibir conteudo, no ambiente de apoio
     * @return Zend_View 
     */
    public function exibirAction()
    {
        if($this->isAjax())
            $this->disableLayout ();
	$ambienteDeApoio = new Aew_Model_Bo_AmbienteDeApoio();
        $usuario = $this->getLoggedUserObject();
	$comentarioForm = new AmbientesDeApoio_Form_Comentario($usuario);
	$id = $this->getParam('id', false);
        $pagina = $this->getParam('pagina', false);
        $ambienteDeApoio->setId($id);
	$comunidade = $this->getParam('comunidade', null);
	if(!$ambienteDeApoio->selectAutoDados())
        {
            $this->flashError('Ambiente de Apoio não encontrado.');
            $this->_redirect($this->_linkListagem);
	}
	$ambienteDeApoio->aumentarAcesso();
	$comentarioForm->getElement('idambientedeapoio')->setValue($id);
        if($this->isPost())
        {
            if(false == $this->getPost('id', false))
            {
		$comentarioForm->isValid($this->getPost());
            }
	}
        $this->setPageTitle($ambienteDeApoio->getTitulo(), $ambienteDeApoio->getTitulo());
        $this->initUrlsAmbienteExibir($ambienteDeApoio);
        $comentarios = $ambienteDeApoio->selectComentarios(6,$pagina,null,true);
        $comentarios = $ambienteDeApoio->getAsPagination($comentarios,0,$pagina,5);
        $ambienteDeApoio->selectTags();
        $this->view->ambienteDeApoio = $ambienteDeApoio;
        $this->view->href = $this->opcoesAcessoConteudo($ambienteDeApoio);
        $this->view->usuarioLogado = $this->getLoggedUserObject();
	$this->view->formComentarios = $comentarioForm;
	$this->view->comunidade = $comunidade;
        $this->view->relacionadosApoio = $this->view->action('relacionados','ambiente','ambientes-de-apoio', array('id' => $id));
        
        // formulario comentários
        $form_login = new Aew_Form_LoginComentario();
        $form_login->getElement('enviar')->setLabel('entrar');
        $form_login->setAction("/ambientes-de-apoio/comentario/logar/id/$id");
        $form_login->setAttrib("id","login-comentario");
        
        $this->view->form_login_comentario = $form_login;
        $this->view->formComentarios = $comentarioForm;
        $this->view->comentarios =  $comentarios;
        $this->view->idDiv = 'conteudo-comentarios';
        $this->view->urlPaginator = $this->view->url(array('module'=> 'ambientes-de-apoio','controller' => 'comentario','action' => 'listar','id'=>$ambienteDeApoio->getId()));
        $this->view->relacionadosConteudos = $this->view->action('relacionados','conteudo','conteudos-digitais', array('tags' => $ambienteDeApoio->selectTags()));
        
        // SEO Otimização para Buscas método setName modifica o conteúdo da meta tag 
        $this->view->headMeta()->setName('title', $ambienteDeApoio->getTitulo());
        $this->view->headMeta()->setName('keywords', $this->palabrasChaveMeta($this->view->ambienteDeApoio->getTags()));
        $this->view->headMeta()->setName('description',  $this->view->readMore( $ambienteDeApoio->getDescricao(), 140,"")); 
        
        $this->view->headScript()
                ->appendFile('/assets/js/readmore.js')
                ->appendFile('/assets/js/comentarios.js');
        
    }
    
    /**
     * conteudos relacioandos do ambietne de apoio
     * @return Zend_View
     */

    public function relacionadosAction()
    {
        $this->view->isAjax = $this->isAjax();
        if($this->isAjax())
        {
            $this->disableLayout();
        }
        $id = $this->getRequest()->getParam('id', false);
        $pagina = $this->getRequest()->getParam('pagina', 1);
        $ambienteDeApoio = new Aew_Model_Bo_AmbienteDeApoio();
        $ambienteDeApoio->setId($id);
        $limite = 3;
        $relacionados = $ambienteDeApoio->selectAmbientesRelacionados($limite, $pagina);
        $relacionados = $ambienteDeApoio->getAsPagination($relacionados, $pagina, $limite, 1);
        $this->view->relacionados = $relacionados;
    }
    /**
     * apagar categorias de ambiente de apoio
     * @return Zend_View
     */
    public function apagarAction()
    {
        $this->setPageTitle('Apagar Ambiente de Apoio');
        $ambiente = new Aew_Model_Bo_AmbienteDeApoio();
        $form = new Aew_Form_Apagar();
        $id = $this->getRequest()->getParam('id', false);
        $ambiente->setId($id); 
        if (!$ambiente->selectAutoDados()){
            $this->flashError('Nenhum registro encontrado.');
            $this->_redirect($this->_linkListagem);
        }
        $form->setAction($this->_actionApagar.$id);
        if($this->getRequest()->isPost())
        {
            if(false != $this->getRequest()->getPost('nao')){
                $this->_redirect($this->_linkExibicao.$id);
            }
            if(!$ambiente->delete())
            {
                $this->flashError('Houve um problema ao tentar apagar o registro.');
                $this->_redirect($this->_linkListagem);
            }
            else
            {
                $this->flashMessage('Registro apagado com sucesso.');
                $this->_redirect($this->_linkListagem);
            } 
        }
        $this->view->form = $form;
        $this->view->ambienteDeApoio = $ambiente;
        $this->view->categoria = $ambiente->getAmbienteDeApoioCategoria();
    }
    
    /**
     * adicionar uma categoria em ambiente de apoio
     * @return Zend_View
     */
    public function adicionarAction()
    {
        $this->setPageTitle('Adicionar Ambiente de Apoio');
    	$form = new AmbientesDeApoio_Form_Adicionar();
        $usuario = $this->getLoggedUserObject();
	$form->adicionarRestricoes();
	if($this->getRequest()->isPost()){
	    $form->isValid($this->getRequest()->getPost());
    	}
	$this->view->adicionar = $form;
	$this->view->usuario = $usuario;
        $this->view->categoria = new Aew_Model_Bo_AmbienteDeApoio();
    }
    /**
     * editar conteudo de mabiente de apoio
     * @return Zend_View
     */
    public function editarAction()
    {
        $this->setPageTitle('Editar Ambiente de Apoio');
    	$form = new AmbientesDeApoio_Form_Adicionar();
    	$ambienteDeApoio = new Aew_Model_Bo_AmbienteDeApoio();
    	$id = $this->getRequest()->getParam('id', false);
    	$idAmbienteDeApoio = $this->getRequest()->getPost('idAmbienteDeApoio', false);
    	if(false == $id)
        {
    	    if(false == $idAmbienteDeApoio)
            {
	        $this->flashError('Nenhum Ambiente de Apoio passado.');
	        $this->_redirect('ambientes');
    	    } 
            else 
            {
    	        $id = $idAmbienteDeApoio;
    	    }
    	}
        $ambienteDeApoio->setId($id);
        if(!$ambienteDeApoio->selectAutoDados())
        {
    	    $this->flashError('Ambiente de Apoio não encontrado.');
            $this->_redirect($this->_linkListagem);
    	}
        $tags = $ambienteDeApoio->selectTags();
        $data = $ambienteDeApoio->toArray();
        $data['tags'] =  '';
        foreach ($tags as $tag) 
        {
            $data['tags'] .= $tag->getNome();
            if(next($tags))
            $data['tags']  .= ',';
        }
    	$form->populate($data);
	if($this->getRequest()->isPost())
        {
	    $form->isValid($this->getRequest()->getPost());
	}
	$this->view->editar = $form;
        $this->view->categoria = $ambienteDeApoio;
    }
    /**
     * salvar conteudo no ambiente de apoio
     * @return Zend_View
     */
    public function salvarAction()
    {
        $form = new AmbientesDeApoio_Form_Adicionar();
        $ambienteDeApoio = new Aew_Model_Bo_AmbienteDeApoio();
        $usuario = $this->getLoggedUserObject();
        if($this->isPost())
        {
            if(false == ($this->getPost('idambientedeapoio') > 0))
            {
                $form->adicionarRestricoes();
            }
            if($form->isValid($this->getRequest()->getPost()))
            {
                if($form->getValue('idambientedeapoio') > 0)
                {
                    $txt = 'editado';
                } 
                else 
                {
                    $txt = 'inserido';
                    $ambienteDeApoio->setUsuarioPublicador($usuario);
                }
                $ambienteDeApoio->exchangeArray($form->getValues());
                $ambienteDeApoio->selectTags();
                $ambienteDeApoio->deleteTags();
                if($ambienteDeApoio->save())
                {
                    $ambienteDeApoio->uploadIcon($form);
                    $ambienteDeApoio->insertTags($form->getValue("tags"));
                    $this->flashMessage('Ambiente de Apoio '.$txt.' com sucesso.');
                    $this->_redirect($this->_linkExibicao.$ambienteDeApoio->getId());
                } 
            } 
            else 
            {
                $this->flashError(implode('\n',$form->getMessages('icone')));
                if($form->getValue('idambientedeapoio') > 0)
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
        $this->_redirect($this->_linkListagem);

    }
    /**
     * adicionar em meus favoritos
     * @return Zend_View
     */
    public function favoritoAction()
    {
        $usuario = $this->getLoggedUserObject();
        $usuarioperfil = $this->getPerfiluserObject();
        $ambiente = new Aew_Model_Bo_AmbienteDeApoio();
        $id = $this->getParam('id', false);
        $ambiente->setId($id);
        $linkExibicao = $this->view->url(array('module' => 'ambientes-de-apoio', 
                                               'controller' => 'ambiente',
                                                'action' => 'exibir', 
                                                'id' => $id,
                                                $usuarioperfil->perfilTipo()=>$usuarioperfil->getId()),null, true);
        if(!$ambiente->selectAutoDados())
        {
            $this->flashError('Nenhuma registro passado');
            $this->_redirect($linkExibicao);
	}
        if($usuarioperfil->insertAmbienteFavorito($ambiente))
        {
            $this->flashMessage('Conteudo adicionado aos favoritos com sucesso!');
        }
        else
        {
            $this->flashError('Não foi possível adicionar ambiente aos favoritos.');
        }
	$this->_redirect($linkExibicao);
    }
    /**
     * remove conteudo do meus favoritos
     * @return Zend_View
     */
    public function removerFavoritoAction()
    {
        $usuario = $this->getLoggedUserObject();
        $usuarioPerfil = $this->getLoggedUserObject();
	$id = $this->getParam('id', false);
        $ambiente = new Aew_Model_Bo_AmbienteDeApoio();
        $ambiente->setId($id);
	if(!$ambiente->selectAutoDados())
        {
            $this->flashError('Nenhuma registro passado');
	}
        $linkExibicao = $this->view->url(array('module' => 'ambientes-de-apoio', 'controller' => 'ambiente','action' => 'exibir', 'id' => $id, $usuario->perfilTipo() => $usuarioPerfil->getId()),null, true);
        if($usuarioPerfil->deleteAmbienteFavorito($ambiente))
        {
            $this->flashMessage('Conteudo removido dos favoritos com sucesso!');
        }
        else
        {
            $this->flashError('Não foi possível adicionar ambiente aos favoritos.');
        }
	$this->_redirect($linkExibicao);
    }
    
    /**
     * @param Aew_Model_Bo_AmbienteDeApoioCategoria $categoria
     */
    public function initUrlsAmbienteExibir(Aew_Model_Bo_AmbienteDeApoio $ambiente=null)
    {
        $usuario = $this->getLoggedUserObject();
        if(!$usuario)
        return ;
        $this->view->urlAdicionar = $usuario->getUrlAdicionarAmbiente();
        if($ambiente)
        {
            $this->view->urlEditar = $ambiente->getUrlEditar($usuario);
            $this->view->urlApagar = $ambiente->getUrlApagar($usuario);
            $this->view->urlRemoverFavorito = $ambiente->getUrlRemoverFavorito($usuario);
            $this->view->urlAdicionarFavorito = $ambiente->getUrlAdicionarFavorito($usuario);
            $this->view->urlRemoverDestaque   = $ambiente->getUrlRemoverDestaque($usuario);
            $this->view->urlAdicionarDestaque = $ambiente->getUrlAdicionarDestaque($usuario);
        }
    }
    
}
