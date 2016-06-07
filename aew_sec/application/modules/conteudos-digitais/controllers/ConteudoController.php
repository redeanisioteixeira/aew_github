<?php
class ConteudosDigitais_ConteudoController extends Sec_Controller_Action 
{
    public function init()
    {
        parent::init();

        $acl = $this->getHelper('Acl');

        $visitanteAction = array('exibir', 'enviar-para-amigo', 'obter-estrelas', 'componentes-curriculares', 'visualizar', 'incorporar-conteudo', 'palavra-chave','componentes-conteudo-form', 'relacionados', 'baixar','filtrar-formato');
        $acl->allow(Aew_Model_Bo_UsuarioTipo::VISITANTE, $visitanteAction);

        $colaboradorAction = array('votar', 'favorito', 'remover-favorito','categorias-canal');
        $acl->allow(Aew_Model_Bo_UsuarioTipo::COLABORADOR, $colaboradorAction);

        $editorAction = array('adicionar','apagar', 'editar', 'salvar','salvar-topico');
        $acl->allow(Aew_Model_Bo_UsuarioTipo::EDITOR, $editorAction);

        $coordenadordorAction = array('aprovacao', 'reprovar', 'aprovar', 'adicionar','editar','apagar', 'salvar','tags', 'componente-curricular');
        $acl->allow(Aew_Model_Bo_UsuarioTipo::COORDENADOR, $coordenadordorAction);

        $sitesTematicosAction = array('sites-tematicos');
        $acl->allow(Aew_Model_Bo_UsuarioTipo::SITES_TEMATICOS, $sitesTematicosAction);

        $administradorAction = array('sites-tematicos', 'destaque', 'removerdestaque','aprovacao', 'aprovar', 'reprovar');
        $acl->allow(Aew_Model_Bo_UsuarioTipo::ADMINISTRADOR, $administradorAction);
        
        $this->setActionAdicionar('/conteudos-digitais/conteudo/adicionar');
        $this->setActionEditar('/conteudos-digitais/conteudo/editar/id/');
        $this->setActionApagar('/conteudos-digitais/conteudo/apagar/id/');
        $this->setLinkListagem('/conteudos-digitais/conteudos/listar');
        $this->setLinkExibicao('/conteudos-digitais/conteudo/exibir/id/');
    }
    /**
     * Carrega JS e CSS para o método HeadScript
     * @return Zend_View_Helper_HeadScript 
     */
    function initScripts()
    {
        $this->view->headLink()->appendStylesheet('/assets/plugins/datepicker/css/bootstrap-datepicker3.standalone.min.css');

        $this->view->headScript()
				->appendFile('/assets/js/jquery.form.js')
				->appendFile('/assets/js/functions-load-ajax.js')
                ->appendFile('/assets/plugins/jquery-ui-1.11.4/jquery-ui.js','text/javascript')
				->appendFile('/assets/plugins/datepicker/js/bootstrap-datepicker.min.js','text/javascript')
				->appendFile('/assets/plugins/datepicker/locales/bootstrap-datepicker.pt-BR.min.js','text/javascript')
                ->appendFile('/assets/js/autocomplete.js','text/javascript');

    }
    
    /**
     * Exibe um conteúdo digital em particular
     * @request type int $id Conteúdo Digital
     */
    public function exibirAction()
    {
		if($this->isAjax())
        {
            $this->disableLayout();
        }
        
        $session = new Zend_Session_Namespace('conteudosDigitaisBusca');
		$session->buscaFormValue;
		$conteudoDigital = new Aew_Model_Bo_ConteudoDigital();
		    $this->view->topicos = $this->getRequest()->getParam('topicos', false);
		    
		$conteudoDigital = new Aew_Model_Bo_ConteudoDigital();
		$id = $this->getRequest()->getParam('id');
		    
		$conteudoDigital->setId($id);
		    
		$conteudoDigital = $conteudoDigital->select(1);
		    if(!$conteudoDigital)
		{
			$this->flashError('Conteúdo não encontrado');
			$this->_redirect('conteudos-digitais');
		}

	    if(!$conteudoDigital->getFlaprovado())
		{
            $usuario = $this->getLoggedUserObject();
            if(!$usuario)
            {
                $this->flashError('Conteúdo não encontrado', $this->getLinkListagem());
            }

            $href = $this->opcoesAcessoConteudo($conteudoDigital);
            if(!$usuario->isCoordenador() && !$href['editar_conteudo'])
            {
                $this->flashError('Conteúdo não encontrado', $this->getLinkListagem());
            }
	}

	if($conteudoDigital->getConteudoDigitalCategoria()->getCanal()->getId() == 1)
        {
            $linkConteudo  = $conteudoDigital->getLinkPerfil();
            $linkConteudo .= ($this->view->topicos ? '/topicos/1' : '');
            $this->_redirect($linkConteudo);
        }
        
		$this->setPageTitle($conteudoDigital->getTitulo(), $conteudoDigital->getTitulo());
		    
		$conteudoDigital->aumentarAcesso();
		$conteudoDigital->selectComponentesCurriculares();
		$conteudoDigital->selectTags(); 

        $form_login = new Aew_Form_LoginComentario();
        $form_login->getElement('enviar')->setLabel('entrar');
        $form_login->setAttrib("id","login-comentario");
        
        $comentarioForm = new ConteudosDigitais_Form_Comentario($this->getLoggedUserObject());
        $comentarioForm->populate($conteudoDigital->toArray());
        $form_login->setAction("/".  $this->getRequest()->getModuleName()."/comentarios/logar/id/$id");
        
        $this->view->form_login_comentario = $form_login;
		$this->view->href = $this->opcoesAcessoConteudo($conteudoDigital);
		$this->view->conteudo = $conteudoDigital;
		    
		$this->view->relacionados = $this->view->action('relacionados','conteudo','conteudos-digitais', array('id' => $id, 'cor' => ($conteudoDigital->getFlSiteTematico() ? 'vermelho' : false)));
        
        $pagina = $this->getRequest()->getParam('pagina');
        $this->view->formComentarios = $comentarioForm;
        
        $comentarios = $conteudoDigital->selectComentarios(6,$pagina,null,true); 
        $comentarios = $conteudoDigital->getAsPagination($comentarios,$pagina,5,5);
        $comentarios->setPageRange(1);
        
        $this->view->pagination = true;
        $this->view->comentarios  = $comentarios;
        $this->view->urlPaginator = $this->view->url(array('module' => 'conteudos-digitais', 'controller' => 'comentarios','action' => 'listar-comentarios'));
        $this->view->idDiv = 'conteudo-comentarios';
        
        // SEO Otimização para Buscas método setName modifica o conteúdo da meta tag 
		$this->view->headMeta()->setName('title', $conteudoDigital->getTitulo());
		$this->view->headMeta()->setName('keywords', $this->palabrasChaveMeta($conteudoDigital->getTags()));
		$this->view->headMeta()->setName('description',  $this->view->readMore($conteudoDigital->getDescricao(), 150, ""));
        
        if($conteudoDigital->getFlSiteTematico() == true)
		{
            $this->view->corfundo = "bgcolor='menu-vermelho'";
            $this->view->corfonte = "fcolor='menu-vermelho'";
            $this->view->panel    = "danger";
            
            $disciplina = $this->getRequest()->getParam('disciplina');
            if($disciplina)
            {
                $componenteCurricular = new Aew_Model_Bo_ComponenteCurricular();
                $componenteCurricular->setId($disciplina);
                $componenteCurricular->selectAutoDados();
                
                $paginaPai[] = array('titulo' => $componenteCurricular->getNome(), 'url' => '/sites-tematicos/disciplinas');
                $this->view->paginaPai = $paginaPai;
            }
        }
        
        $this->view->headLink()->appendStylesheet("/assets/plugins/jquery-live-preview/css/live-preview.css");
        
        $this->view->headScript()->appendFile("/assets/plugins/jquery-live-preview/js/jquery-live-preview.js","text/javascript")
                    ->appendFile('/assets/js/comentarios.js')
                    ->appendFile('/assets/js/readmore.js');
    }
    /**
     * Filtra formatos
     */
    public function filtrarFormatoAction()
    {
        $typeFile = $this->getRequest()->getParam('id');
    	$formFormatos = new ConteudosDigitais_Form_Adicionar();
        $formatos = $formFormatos->getFileExtensions($typeFile);
        
        echo json_encode($formatos);
        die();
    }
    
    /**
     * Adiciona novo Conteúdo Digital
     * @return Zend_View
     */
    public function adicionarAction()
    {
        $this->setPageTitle('Adicionar conteúdo digital');
        
    	$formAdicionar = new ConteudosDigitais_Form_Adicionar();
        $formAdicionar->setAction($this->getActionAdicionar());
        $formAdicionar->adicionarRestricoes();
        
        if($this->getRequest()->isPost())
        {
            if($formAdicionar->isValid($this->getRequest()->getPost()))
            {
                $this->salvarConteudo($formAdicionar, $this->getRequest()->getPost());
            }
            else
            {
                $this->flashError('Campos obrigatórios não foram preenchidos');
            }                
        }

        $this->view->usuario = $this->getLoggedUserObject();
		$this->view->adicionar = $formAdicionar;
        $this->view->editando = true;
        
        $this->view->headScript()->appendFile('/assets/js/conteudos-digitais/adicionar.js','text/javascript');
    }
    
    /**
     * Prenche o formulario e edita as informações do conteúdo
     * @return Zend_View 
     */
    public function editarAction()
    {
        $id = $this->getRequest()->getParam('id', false);
        
    	if(!$id)
        {
            $this->flashError('Nenhum conteúdo passado', $this->getLinkListagem());
    	}

        $conteudoDigitalBo = new Aew_Model_Bo_ConteudoDigital();
    	$conteudoDigitalBo->setId($id);
        $conteudo = $conteudoDigitalBo->select(1);
        if(!$conteudo)
        {
    	    $this->flashError('Conteúdo não encontrado', $this->getLinkListagem());
    	}

        $href = $this->opcoesAcessoConteudo($conteudo);
        if(!$href['editar_conteudo']):
            $this->flashError('Não possui permissão para editar este contéudo', $this->getLinkListagem());
        endif;
        
        $this->setPageTitle($conteudo->getTitulo());

        $paginaPai[] = array('titulo' => 'editando', 'url' => '');
        $this->view->paginaPai = $paginaPai;
        
        $conteudo->selectComponentesCurriculares();
		$conteudo->selectTags(); 

        $formEditar = new ConteudosDigitais_Form_Adicionar();
        $formEditar->setAction($this->getActionEditar($id));
        
        $formEditar->adicionarRestricoes($conteudo);
        $formEditar->populate($conteudo);

        if($this->getRequest()->isPost())
        {
            if($formEditar->isValid($this->getRequest()->getPost()))
            {
                $this->salvarConteudo($formEditar, $this->getRequest()->getPost());
            }
            else
            {
                $this->flashError('Campos obrigatórios não foram preenchidos');
            }
        }

        $this->view->editar = $formEditar;
        $this->view->editando = true;
        $this->view->conteudo = $conteudo;
        
        $this->view->headScript()->appendFile('/assets/js/conteudos-digitais/adicionar.js','text/javascript');
        $this->view->headLink()->appendStylesheet('/assets/img/icones/tipos-arquivos/sprites/tipos-arquivos.css');
    }
    
    /**
     * Guarda as informações de editar ou adicionar Conteúdo Digital
     * @return Zend_View 
     */
    public function salvarConteudo($form, $conteudo)
    {
        $usuario = $this->getLoggedUserObject();
        $conteudoBo = new Aew_Model_Bo_ConteudoDigital();
        
        if($conteudo['idconteudodigital'])
        {
            $txt = 'editado';
            $conteudoBo->setId($conteudo['idconteudodigital']);
        } 
        else 
        {
            $txt = 'adicionado';
            $conteudoBo->getUsuarioPublicador()->setId($usuario->getId());
        }
        
        if(!$usuario->isCoordenador())
        {
            $conteudoBo->setFlaprovado(false);
        }
        
        $conteudoDigitalCategoria = new Aew_Model_Bo_ConteudoDigitalCategoria();
        $conteudoDigitalCategoria->exchangeArray($conteudo);
        $conteudoBo->setConteudoDigitalCategoria($conteudoDigitalCategoria);

        $conteudoBo->exchangeArray($conteudo);
        
        $adicionarTag = (($usuario->isCoordenador() && $usuario->getId() == 545) || $usuario->isAdmin() ? true : false);
        $adicionarTag = false;

        if($conteudoBo->saveConteudo($conteudo, false, $adicionarTag))
        {
            if(!$this->uploadConteudoFiles($form, $conteudoBo))
            {
                $linkRetorno = ($txt == 'editado' ? $this->getActionEditar($idconteudo) : $this->getActionAdicionar());
                $this->flashError('Erro ao '.$txt.' conteúdo digital. Tente novamente', $linkRetorno);
            }
            else
            {
                $idconteudo = $conteudoBo->getId();
                if($conteudoBo->getFlaprovado())
                {
                    $this->flashMessage('Conteudo '.$txt.' com sucesso.',$this->getLinkExibicao($idconteudo));
                } 
                else 
                {
                    $this->flashMessage('Conteudo '.$txt.' com sucesso, aguardando aprovação', $this->getLinkListagem());
                }
            }
        }
        else
        {
            $linkRetorno = ($txt == 'editado' ? $this->getActionEditar($idconteudo) : $this->getActionAdicionar());
            $this->flashError('Erro ao '.$txt.' conteúdo digital. Tente novamente', $linkRetorno);
        }
    }
    /**
     * Método para fazer upload (subir) arquivo
     * @return boolean
     */
    private function uploadConteudoFiles(ConteudosDigitais_Form_Adicionar $form,  Aew_Model_Bo_ConteudoDigital $conteudo)
    {
        $isupload = false;
        $values = $form->getValues();
        
        $fileVazio = new Zend_Form_Element_File('fileVazio');
        
        $fileV  = ($form->conteudov ? $form->conteudov : $fileVazio);
        $fileD  = ($form->conteudod ? $form->conteudod : $fileVazio);
        $fileG  = ($form->guiaPedagogico ? $form->guiaPedagogico : $fileVazio);
        $fileI  = ($form->imagemAssociada ? $form->imagemAssociada : $fileVazio);

        $deleteV  = $form->apagarconteudov->checked;
        $deleteD  = $form->apagarconteudod->checked;
        $deleteG  = $form->apagarGuiaPedagogico->checked;
        $deleteI  = $form->apagarImagemAssociada->checked;

        if(!$fileV->isUploaded() && !$fileD->isUploaded() && !$fileG->isUploaded() && !$fileI->isUploaded() && !$deleteV && !$deleteD && !$deleteG && !$deleteI)
        {
            return true;
        }

        $conteudoTipo = new Aew_Model_Bo_ConteudoTipo();
        $conteudoTipo->exchangeArray($form->getValues());
        
        //--- Arquivo Visualização 
        if($fileV instanceof Zend_Form_Element_File)
        {
            $formatoView = new Aew_Model_Bo_Formato();
            $formatoView->setConteudoTipo($conteudoTipo);
            
            if($deleteV)
            {
                $isupload = unlink(Aew_Model_Bo_ConteudoDigital::getConteudoVisualizacaoDirectory().DS.$conteudo->getId().'.'.$conteudo->getFormato()->getNome());
                
                $formatoView->setNome('link');
                $formatoView = $formatoView->select(1);
                
                $conteudo->setFormato($formatoView);
            }
            
            if($fileV->isUploaded() && !$deleteV)
            {   
                $isupload = $formatoView->uploadArquivoConteudo($fileV, $conteudo->getId());
                
                $formatoView = $formatoView->select(1);
                $conteudo->setFormato($formatoView);
            } 
        }

        //--- Arquivo Download 
        if($fileD instanceof Zend_Form_Element_File)
        {
            $formatoDownload = new Aew_Model_Bo_Formato();
            $formatoDownload->setConteudoTipo($conteudoTipo);
            
            if($deleteD)
            {
                unlink(Aew_Model_Bo_ConteudoDigital::getConteudoDownloadDirectory().DS.$conteudo->getId().'.'.$conteudo->getFormatoDownload()->getNome());
                $conteudo->setFormatoDownload();
            }
            
            if($fileD->isUploaded() && !$deleteD)
            {   
				$tamanho = $fileD->getFileSize();
                $isupload = $formatoDownload->uploadArquivoConteudo($fileD, $conteudo->getId());
                
                $formatoDownload = $formatoDownload->select(1);
                $conteudo->setFormatoDownload($formatoDownload);
                $conteudo->setTamanho($tamanho);
            }
        }
        
        //--- Arquivo guia pedagógico 
        if($fileG instanceof Zend_Form_Element_File)
        {
            $formatoGuiaPedagogico = new Aew_Model_Bo_Formato();
            if($deleteG)
            {
                unlink(Aew_Model_Bo_ConteudoDigital::getGuiaPedagogicoDirectory().DS.$conteudo->getId().'.'.$conteudo->getFormatoGuiaPedagogico()->getNome());
                $conteudo->setFormatoGuiaPedagogico();
            }
            
            if($fileG->isUploaded() && !$deleteG)
            {
                $conteudoTipoGuiaPedagogico = new Aew_Model_Bo_ConteudoTipo();
                $conteudoTipoGuiaPedagogico->setId(Aew_Model_Bo_ConteudoTipo::$DOCUMENTO_EXPERIMENTO);

                $isupload = $formatoGuiaPedagogico->uploadArquivoConteudo($fileG, $conteudo->getId());                
                
                $formatoGuiaPedagogico->setConteudoTipo($conteudoTipoGuiaPedagogico);
                $formatoGuiaPedagogico = $formatoGuiaPedagogico->select(1);
                
                $conteudo->setFormatoGuiaPedagogico($formatoGuiaPedagogico);
            }
        }

        //--- Arquivo imagem associada
        if($fileI instanceof Zend_Form_Element_File)
        {
            if($deleteI)
            {
                $arrFormato = array('png', 'jpg', 'gif');
                foreach($arrFormato as $formato)
                {
                    unlink(Aew_Model_Bo_ConteudoDigital::getImagemAssociadaDirectory().DS.$conteudo->getId().'.'.$formato);
                }
                $isupload = true;
            }
            
            if($fileI->isUploaded() && !$deleteI)
            {
                $formatoImagemAssociada = new Aew_Model_Bo_Formato();
                $isupload = $formatoImagemAssociada->uploadArquivoConteudo($fileI, $conteudo->getId());
            }
        }
        
        if($isupload || $deleteV || $deleteD || $deleteG)
        {
            $isupload = $conteudo->saveConteudo(null, true);
        }
        
        return $isupload;
    }
    
    /**
     * Apaga conteúdo digital
     * @return Zend_View  
     */
    public function apagarAction()
    {
        $this->setPageTitle('Apagar Conteúdo Digital');
        $conteudoDigital = new Aew_Model_Bo_ConteudoDigital();
        
        $linkListagem = 'conteudos-digitais';
        
        $actionApagar = '/conteudos-digitais/conteudo/apagar/id/';
        
        $id = $this->getParam('id',false);
        $conteudoDigital->setId($id);
        
        $formApagar = new Aew_Form_Apagar();
        $formApagar->setAction($this->getActionApagar($id));
        $formApagar->getElement($actionApagar);
        
        if (!$conteudoDigital->selectAutoDados()){
            $this->flashError('Nenhum registro encontrado.', $this->getLinkListagem());
        }

        $formApagar->getElement('mensagem')->setValue('Tem certeza que deseja apagar o conteúdo digital '.$conteudoDigital->getTitulo().'?');
        
        if($this->getRequest()->isPost())
        {
            if($this->getRequest()->getPost('nao')){
                $this->_redirect($this->getLinkExibicao($id));
            }
            
            $result = $conteudoDigital->delete();
            if(true == $result)
            {
                $this->flashMessage('Registro apagado com sucesso.', $this->getLinkListagem());
            } 
            else 
            {
                $this->flashError('Houve um problema ao tentar apagar o registro.', $this->getLinkListagem());
            }
        }
        
        $this->view->formApagar = $formApagar;
        $this->view->conteudo = $conteudoDigital;
    }
    /**
     * Adiciona a bloco de destaques seja para a SEC ou para o AEW
     * @return 
     */
    public function destaqueAction()
    {
	$linkListagem = 'conteudos-digitais';
	$linkExibicao = 'conteudos-digitais/conteudo/exibir/id/';
	$id = $this->getRequest()->getParam('id');
	$conteudoDigital = new Aew_Model_Bo_ConteudoDigital();
        $conteudoDigital->setId($id);
	if(!$conteudoDigital->selectAutoDados())
        {
            $this->flashError('Nenhum registro encontrado.');
            $this->_redirect($linkListagem);
	}
        if($conteudoDigital->getDestaque())
        {
            $this->flashError('Este conteudo ja e um destaque.');
            $this->_redirect($linkListagem);
        }
	$usuario = $this->getLoggedUserObject();
	$result = $usuario->adicionaDestaque($conteudoDigital);
	if($result)
        {
            $this->flashMessage('Conteúdo adicionado a lista de destaques.');
            $this->_redirect($linkExibicao.$conteudoDigital->getId());
	} 
        else 
        {
            $this->flashError('Não foi possível adicionar o conteúdo a sua lista de destaques, verifique se a lista já possui 5 conteúdos.');
            $this->_redirect($linkExibicao.$conteudoDigital->getId());
	}
    }
    
    /**
     * Remover do bloco de destaques
     * @return
     */
    public function removerdestaqueAction()
    {
	$linkListagem = 'conteudos-digitais';
	$linkExibicao = 'conteudos-digitais/conteudo/exibir/id/';
	$id = $this->getRequest()->getParam('id');
	$conteudoDestaque = new Aew_Model_Bo_ConteudoDigital();
        $conteudoDestaque->setId($id);
        $conteudoDestaque->setDestaque(true);
	if(!$conteudoDestaque->selectAutoDados())
        {
            $this->flashError('Nenhum registro encontrado.');
            $this->_redirect($linkListagem);
	}
	$usuario = $this->getLoggedUserObject();
	$result = $usuario->removerDestaque($conteudoDestaque);
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
     * Método para aprovar um conteúdo digital
     * @return Zend_View
     */
    public function aprovarAction()
    {
        $request = $this->getRequest();
        $conteudo = new Aew_Model_Bo_ConteudoDigital();
        $id = $request->getParam('id');
        
        $conteudo->setId($id);
        if(!$conteudo->selectAutoDados())
        {
            $this->flashError('Objeto não identificado, por favor tente novamente.');
            $usuario = $this->getLoggedUserObject();
        }
        
        $usuario = $this->getLoggedUserObject();
        if(!$usuario) 
        {
	    $this->flashError('Usuário inválido.');
	}
        
        $conteudo->setUsuarioAprova($usuario);
        $conteudo->setFlaprovado(true);
        if($usuario->isCoordenador())
        {
            if($conteudo->update())
            {
                $this->setPageTitle("Processo de aprovação de conteúdo digital");
                
                $this->view->conteudo = $conteudo;
                $this->view->usuario  = $conteudo->getUsuarioPublicador();
                $this->view->aprovar  = true;
                
                $nome = $conteudo->getUsuarioPublicador()->getNome();
                $email = ($conteudo->getUsuarioPublicador()->getEmailPessoal() ? $conteudo->getUsuarioPublicador()->getEmailPessoal() : $conteudo->getUsuarioPublicador()->getEmail());
                
                $assunto = "[AEW] Aviso sobre aprovação de conteúdo digital";
                $mensagem = $this->view->render('conteudo/notificar-aprovacao.email.php');
                $resultado = $this->enviarEmail($email, $mensagem, $assunto, $nome);

                $this->flashMessage('Conteúdo aprovado com sucesso');
            }
            else 
            {
                $this->flashError('Houve um problema no pedido, por favor tente novamente.');
            }
        }
        
        $this->_redirect('conteudos-digitais/conteudos/aprovar');
    }
    /**
     * Método para reprovar um conteúdo digital
     * @return Zend_View     
     */
    public function reprovarAction()
    {
        $request = $this->getRequest();
        $conteudo = new Aew_Model_Bo_ConteudoDigital();
        $id = $request->getParam('id');
        $conteudo->setId($id);
        if(!$conteudo->selectAutoDados())
        {
            $this->flashError('Objeto não identificado, por favor tente novamente.');
            $this->_redirect('conteudos-digitais/conteudos/aprovar');
        }

        $usuario = $this->getLoggedUserObject();
        if(!$usuario) 
        {
	    $this->flashError('Usuário inválido.');
	}

        $conteudo->setUsuarioAprova($usuario);
        $conteudo->setFlaprovado('false');
        
        if($usuario->isCoordenador())
        {
            if($conteudo->update())
            {
                $this->setPageTitle("Processo de aprovação de conteúdo digital");
                
                $this->view->conteudo = $conteudo;
                $this->view->usuario  = $conteudo->getUsuarioPublicador();
                
                $nome = $conteudo->getUsuarioPublicador()->getNome();
                $email = ($conteudo->getUsuarioPublicador()->getEmailPessoal() ? $conteudo->getUsuarioPublicador()->getEmailPessoal() : $conteudo->getUsuarioPublicador()->getEmail());
                
                $assunto = "[AEW] Aviso sobre aprovação de conteúdo digital";
                $mensagem = $this->view->render('conteudo/notificar-aprovacao.email.php');
                $resultado = $this->enviarEmail($email, $mensagem, $assunto, $nome);

                $this->flashMessage('Conteúdo reprovado com sucesso');
            }
            else 
            {
                $this->flashError('Houve um problema no pedido, por favor tente novamente.');
            }
        }
        
        $this->_redirect('conteudos-digitais/conteudos/aprovar');        
    }
    /**
     * Qualifica um conteúdo digital valores de 1 a 5
     * @return   
     */
    public function votarAction()
    {
        if($this->isAjax())
            $this->disableLayout ();
        $request = $this->getRequest();
        $conteudoBo = new Aew_Model_Bo_ConteudoDigital();
        $id = $request->getParam('id');
        $conteudoBo->setId($id);
        $voto = $request->getParam('voto');
        if(!$conteudoBo->selectAutoDados())
        {
            $this->flashError('Objeto não identificado, por favor tente novamente.');
            $this->_redirect('conteudos-digitais');
        }
        $usuario = $this->getLoggedUserObject();
        if(!$usuario)
        {
            $this->flashError('Você não tem permissão para votar neste conteúdo digital.');
            $this->_redirect('conteudos-digitais');
    	}
        $result = $conteudoBo->insertVoto($usuario, $voto);
        if($result)
        {
            $this->flashMessage('Conteúdo votado com sucesso.');
        } 
        else 
        {
            $this->flashError('Você já votou nesse conteúdo.');
        }
        $conteudoBo->atualizaAvaliacao();
        $this->view->conteudo = $conteudoBo;
        $this->renderScript('conteudo/conteudo-digital.php');
    }
    /**
     * Método para aprovar um conteúdo digital
     * @return Zend_View
     */
    public function aprovacaoAction()
    {
    	$this->setPageTitle('Conteúdos Digitais Pendentes de Aprovação');
    	
    	$conteudoDigital = new Aew_Model_Bo_ConteudoDigital();
    	$buscaForm->setAction('conteudos-digitais/conteudos/aprovar');
	$usuario = $this->getLoggedUserObject();
	$id = $this->getRequest()->getParam('id');
        $conteudoDigital->setId($id);
        if(!$conteudoDigital->selectAutoDados())
        {
	    $this->flashError('Conteúdo não encontrado.');
            $this->_redirect('conteudos-digitais');
        }
	$acl = Sec_Acl::getInstance();
	$this->view->acl = $acl;
	$this->view->conteudo = $conteudoDigital;
	
	$this->view->usuario = $usuario;
	$this->setPageSubTitle($conteudoDigital->getTitulo());
    }
    /**
     * Método para selecionar os conteúdos relacionado por tags ou palavras chaves 
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
        $tags = $this->getRequest()->getParam('tags', null);
        $cor = $this->getRequest()->getParam('cor', null);
        $limite = 4;
        $conteudoDigital = new Aew_Model_Bo_ConteudoDigital();
        if(!$tags)
        {
            $conteudoDigital->setId($id);
            $limite = 5;
        }
        $relacionados = $conteudoDigital->selectConteudosRelacionados($limite, $pagina, $tags);
        $relacionados = $conteudoDigital->getAsPagination($relacionados, $pagina, $limite, 1);
        if($cor)
	{
            $this->view->corfundo = "bgcolor='menu-$cor'";
            $this->view->corfonte = "fcolor='menu-$cor'";
        }
        $this->view->relacionados = $relacionados;
        $this->view->tags = $tags;
    }
    /**
     * Método para adicionar um conteúdo a lista de favoritos
     * @return
     */
    public function favoritoAction()
    {
        $idconteudodigital = $this->getParam('id', false);
        $idcomunidade      = $this->getParam('comunidade', false);
        $conteudo = new Aew_Model_Bo_ConteudoDigital();
        $conteudo->setId($idconteudodigital);
        if(!$conteudo->selectAutoDados())
        {
            $this->flashError('Nenhuma registro passado');
            $linkExibicao = $this->view->url(array('module' => 'conteudos-digitais', 'controller' => 'conteudos','action' => 'listar'));
            $this->_redirect($linkExibicao);
	}
        $itemPerfil = $this->getPerfiluserObject();
        $itemPerfil->insertConteudoFavorito($conteudo);
        $this->flashMessage('Adicionado com sucesso!', '/conteudos-digitais/conteudo/exibir/id/'.$idconteudodigital);
    }
    /**
     * Método para apagar da lista de favoritos
     * @return
     */
    public function removerFavoritoAction()
    {
        $idconteudodigital = $this->getParam('id', false);
        $conteudo = new Aew_Model_Bo_ConteudoDigital();
        $conteudo->setId($idconteudodigital);
	if(!$conteudo->selectAutoDados())
        {
            $this->flashError('Nenhuma registro passado');
            $linkExibicao = $this->view->url(array('module' => 'conteudos-digitais', 'controller' => 'conteudos','action' => 'listar'));
            $this->_redirect($linkExibicao);
	}
        $perfil = $this->getPerfiluserObject();
        $perfil->deleteConteudoDigitalFavorito($conteudo);
	$this->flashMessage('Conteúdo removido a favoritos','/conteudos-digitais/conteudo/exibir/id/'.$idconteudodigital);
    }

    /**
     * Envia conteúdo para amigo
     * @return 
     */
    public function enviarParaAmigoAction()
    {
    	$this->disableLayout();

	$form = new ConteudosDigitais_Form_EnviarAmigo();
	$conteudoBo = new Aew_Model_Bo_ConteudoDigital();

	$id = -1;
	$id = (int)$this->getRequest()->getParam('id', false);
	if(-1 == $id){
	    echo "Valor do conteúdo não foi passado";
	    exit();
	}

	$form->getElement('idconteudodigital')->setValue($id);
	if($this->getRequest()->isPost()){ 
	    if($form->isValid($this->getRequest()->getPost())){
	        $conteudo = $conteudoBo->get(
                $form->getValue('idconteudodigital'));
	        $mensagem = $this->view->partial('conteudo/enviar-para-amigo.email.php',
		                        array('conteudo' => $conteudo, 'values' => $form->getValues())
		                        );
	        try
                {
		    $result = $conteudoBo->enviarParaAmigo($form, $mensagem);
		} 
                catch (Zend_Exception $e) 
                {
                    $this->flashError('Houve um problema ao tentar enviar a mensagem, '.
                    				  'por favor tente novamente.');
		}
	        if(true == $result)
                {
		    $this->flashMessage('A mensagem foi enviada com sucesso.');
		} 
                else
                {
		    $this->flashError('Verifique se você preencheu os campos e o captcha corretamente.');
		}
	        exit();
	    }
	}
    	$this->view->enviarAmigo = $form;
    	$this->view->id = $id;
    }

    /**
     * Apresenta Código de incorporação iframe para mostrar em páginas externas
     * @return Zend_View
     */
    public function incorporarConteudoAction()
    {
        $this->_helper->layout->setLayout('popup/layout');
        $id = $this->getRequest()->getParam('id', false);

        $conteudoDigitalBo = new Aew_Model_Bo_ConteudoDigital();
        $conteudoDigitalBo->setId($id);
        
        $conteudo = $conteudoDigitalBo->select(1);
        $conteudo->aumentarAcesso();
        
        $this->setPageTitle($conteudo->getTitulo());
        
        $this->view->conteudo = $conteudo;
        
        //if(!$this->isAjax()):
            
            $template = "$('a.link-incorporar').click(function(){
                            if (top.location != this.location){
                                top.location = this.href;
                                return false;
                            }
                        });

                        window.onload = function(){
                                        if (top.location != this.location){
                                            $('button.close').remove();
                                         }
                                    };
                        $('audio.audio-conteudo').on('play', function(){
                                var id = $(this).attr('idaudio');
                                $('img#equalizer'+id).removeClass('desativado');
                        });
		
                        $('audio.audio-conteudo').on('pause', function(){
                                var id = $(this).attr('idaudio');
                                $('img#equalizer'+id).addClass('desativado');
                        });";
        
            echo $this->view->headScript()
                ->setFile('/assets/js/jquery-1.11.0.min.js')
                ->setScript($template,'text/javascript');
        //endif;
    }

    /**
     * Obten qualificação do conteúdo
     * @return Zend_View
     */
    public function obterEstrelasAction()
    {
        $this->disableLayout();
        $request = $this->getRequest();
        $this->view->nota = $request->getParam('nota');
        $this->view->conteudoDigital = $request->getParam('id');
    }

    /**
     * Lista componentes curriculares em formato JSON
     * @return  $array JSON 
     */
    public function componentesCurricularesAction()
    {
    	if($this->isAjax())
        {
	    $this->disableLayout();
	    $this->disableRender();
	    $nome = $this->getParam('_name');
	    $valor = $this->getParam('_value');
	    $todos = $this->getParam('_todos');
            if($valor == "")
            {
	        return;
	    }
            if($nome == "nivelEnsino" || $nome == "nivel_ensino")
            {
	        $bo = new Aew_Model_Bo_ComponenteCurricular;
	        $componentes = $bo->getByNivelEnsino($valor);
	        if($todos == "nao")
                {
	            $result = array();
	        } else {
	                $result = array(array('' => 'Todos'));
	            }
	            foreach($componentes as $componente){
	                $result[] = array($componente['idComponenteCurricular'] => $componente['nome']);
	            }

	            $return = Zend_Json::encode($result);
	            $this->getResponse()->appendBody($return);
	        }
        }
    }

    /**
     * Método para Visualizar conteúdo 
     */
    public function visualizarAction()
    {
    	$this->disableLayout();
	$id = $this->getRequest()->getParam('id');
	$conteudo = new Aew_Model_Bo_ConteudoDigital();
        $conteudo->setId($id);
	$this->view->conteudo = $conteudo->select(1);
	$this->view->usuario = $this->getLoggedUserObject();
    }
    /**
     * Lista palabras chaves en singular
     * $return $array
     */
    public function palavraChaveAction()
    {
	$this->disableLayout();
	$nome = $this->getParam("filtro","");
	$nome = str_replace('"','',$nome);
	$nome = str_replace("'","",$nome);
	$arr_tags = array();
	$inflector = new Sec_Inflector();
	$arr_tags = array_merge($inflector->singularize($nome), $inflector->pluralize($nome));
	$arr_tags = array_unique($arr_tags);
	$json = "";
        $inicio = true;
        $arrTags=array();
        if($nome)
	foreach($arr_tags as $nome){
            $tag = new Aew_Model_Bo_Tag();
            $tag->setNome($nome);
            $tags = $tag->select(30);
            foreach($tags as $t)
            {
                $arrTags[$t->getId()] = $t->getNome();
            }
        }
        $this->view->objetos = $arrTags;
	$this->renderScript('_componentes/lista-objetos.php');
    }
    /**
     * Lista de tags ou palavras chaves 
     */
    public function tagsAction()
    {
        $tag = new Aew_Model_Bo_Tag();
        $tags = $tag->select();
        $this->view->tagsContagem = $tags;
    }
    /**
     * 
     */
    public function componenteCurricularAction()
    {
	$componente = new Aew_Model_Bo_ComponenteCurricular();
        $options['orderBy'] = array('componentecurricular.idnivelensino ASC','componentecurricular.idcategoriacomponentecurricular ASC','componentecurricular.nomecomponentecurricular ASc');
        
	$componentes = $componente->select(0,0,$options);
	$this->view->componentes = $componentes;
    }
    /**
     * Disponibiliza as urls de acesso 
     * @return Zend_View
     * @param Aew_Model_Bo_ConteudoDigital $conteudo
     */
    function initUrlsExirconteudo(Aew_Model_Bo_ConteudoDigital $conteudo)
    {
        $acl = Sec_Acl::getInstance();
        $usuario = $this->getLoggedUserObject();
        if($usuario)
        {
            if(($acl->isAllowed($usuario->getUsuarioTipo()->getNome(), 'conteudos-digitais:conteudo', 'editar') &&
            $conteudo->getUsuarioPublicador()->getId() == $usuario->getId()) ||
            $usuario->getUsuarioTipo()->getNome() == Aew_Model_Bo_UsuarioTipo::SUPER_ADMINISTRADOR ||
            $usuario->getUsuarioTipo()->getNome() == Aew_Model_Bo_UsuarioTipo::ADMINISTRADOR ||
            $usuario->getUsuarioTipo()->getNome() == Aew_Model_Bo_UsuarioTipo::COORDENADOR)
            {
            }
            if($conteudo instanceof Aew_Model_Bo_ConteudoDigitalFavorito)
            if($conteudo->getIdFavorito()==$usuario->getIdFavorito())
            {
            }
            if($usuario->getUsuarioTipo()->getNome() == Aew_Model_Bo_UsuarioTipo::SUPER_ADMINISTRADOR)
            {
            }
            $this->view->hrefAdicionaFavorito = $this->view->url(array('module' => 'conteudos-digitais', 'controller' => 'conteudo', 'action' => 'favorito', 'id' => $conteudo->getId()), null, true);
        }
        
        $this->view->hrefFaleConosco = $this->view->url(array('module' => 'aew', 'controller' => 'home', 'action' => 'faleconosco'), null, true);
        $this->view->hrefRelacionados = $this->view->url(array('module' => 'conteudos-digitais', 'controller' => 'relacionados', 'action' => 'listar', 'id' => $conteudo->getId()), null, true);
    }
    /**
     * Método para fazer download dos conteúdos
     * @return 
     */
    public function baixarAction()
    {
        $id = $this->getRequest()->getParam('id', false);
        if(!$id){
            $this->_redirect('');
        }
        
        $arr_extensao = array(
                            "pdf"  => "application/pdf",
                            "exe"  => "application/octet-stream",
                            "zip"  => "application/zip",
                            "rar"  => "application/rar",
                            "doc"  => "application/msword",
                            "docx" => "application/msword",
                            "xls"  => "application/vnd.ms-excel",
                            "ppt"  => "application/vnd.ms-powerpoint",
                            "gif"  => "image/gif",
                            "png"  => "image/png",
                            "jpg"  => "image/jpg",
                            "jpeg" => "image/jpeg",
                            "mp3"  => "audio/mpeg",
                            "swf"  => "application/x-shockwave-flash",
                            "flv"  => "video/x-flv",
                            "wmv"  => "video/x-ms-wmv",
                            "xml"  => "text/xml",
                            "mpg"  => "video/mpeg",
                            "wma"  => "audio/x-ms-wma",
                            "avi"  => "video/x-msvideo",
                            "mp4"  => "video/mp4",
                            "odt"  => "application/vnd.oasis.opendocument.text",
                            "txt"  => "text/plain; charset=UTF-8",
                            "webm" => "video/webm",
                            "htm"  => "text/html; charset=UTF-8",
                            "html" => "text/html; charset=UTF-8",
                        );

        $usuario = $this->getLoggedUserObject();

        $options = array();
        $conteudoDigitalBo = new Aew_Model_Bo_ConteudoDigital();
        $conteudoDigitalBo->setId($id);
        $conteudo = $conteudoDigitalBo->select(1);

        if(!$conteudo){
            $this->_redirect(''); 
        }
            
        $extensao = $conteudo->getFormatoDownload()->getNome();
        $arquivo  = $conteudo->getConteudoDownloadUrl();

        $tamanho  = (filesize($conteudo->getConteudoDownloadPath()) ? filesize($conteudo->getConteudoDownloadPath()) : 0);
        $extensao = (array_key_exists($extensao, $arr_extensao) ? $arr_extensao[$extensao] : "application/force-download"); //application/octet-stream

        $conteudo->aumentarDownload($conteudo);

        //Aplica configurações de cabeçalho
        header('Content-Description: File Transfer');
        header('Content-Disposition: attachment; filename= "'.basename($arquivo).'"');
        header('Content-Type: application/octet-stream');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: '.$tamanho);
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Expires: 0');

        ob_clean();
        flush();
        readfile($conteudo->getConteudoDownloadPath());
        exit();
    }    
    
    /**
     * Lista Categorias dos Canais 
     * @return
     */
    public function categoriasCanalAction()
    {
        if(!$this->isAjax())
        {
            //$this->_redirect ('');
        }
        
        $this->disableLayout();
        $this->disableRender();
        
        $id = $this->getParam('idcanalcategoria',null);
        $canal = new Aew_Model_Bo_Canal();
        
        $canal->setId($id);
        
        $options['orderBy'] = "conteudodigitalcategoria.nomeconteudodigitalcategoria ASC";
        
        $categorias = $canal->selectCategoriasConteudo(0, 0, null, $options);
        $categorias = $canal->getAllForSelect('idconteudodigitalcategoria', 'nomeconteudodigitalcategoria',false,'idconteudodigitalcategoriapai',null,$categorias);
        $this->view->objetos = $categorias;
        
        echo $this->renderScript('_componentes/select-opcoes.php');
        
    }
    /**
     * Testando Libraria PHPVideoToolkit
     */
    function testeAction()
    {
        $conteudo = new Aew_Model_Bo_ConteudoDigital();
        
        $conteudo->setId(1830);
        $conteudo->selectAutoDados();
        
        
        $visualizaDirectory= $conteudo->getConteudoVisualizacaoDirectory();
        $imgAssocDirectory = $conteudo->getImgAssocSinopseDirectory();
        
        $f= new Sec_PhpVideoToolkit();
        $f->extractImgFromVideo($conteudo->getId(), $pathVideo, $imgSinopseDirectory);
        
        $this->view->conteudo= $conteudo;
    }
}
