<?php
class EspacoAberto_ComunidadeController extends Sec_Controller_Action_EspacoAberto
{
    public function init()
    {
        parent::init();
        $acl = $this->getHelper('Acl');
        $amigoDaEscolaAction = array('home', 'exibir', 'sugerir', 'listar', 'participar', 'sair', 'votar', 'adicionar', 'editar', 'sugeridas',
                                     'relacionar', 'lista-relacionar', 'adicionar-relacao', 'remover-relacao', 'aceitar-sugestao', 'recusar-sugestao',
                                     'listar-comunidades-relacionadas', 'salvar', 'bloquear', 'pendentes', 'aceitar', 'recusar','lista-comunidades');
        
        $acl->allow(Aew_Model_Bo_UsuarioTipo::AMIGO_DA_ESCOLA, $amigoDaEscolaAction);
        
        $this->setLinkListagem('espaco-aberto/comunidade/listar');
        $this->setLinkExibicao('espaco-aberto/comunidade/exibir');
        $this->setActionBloquear('/espaco-aberto/comunidade/bloquear/id/');
        
        $this->view->perfil = $this->usuarioPerfil;
    }
    /**
     * Carrega JS e CSS para o método HeadScript
     * @return Zend_View_Helper_HeadScript 
     */
    function initScripts()
    {
        parent::initScripts();
        $this->view->headLink()
                    ->appendStylesheet('/assets/plugins/sugerir/jquery.tokenize.css');
        $this->view->headScript()
                    ->appendFile('/assets/plugins/sugerir/jquery.tokenize.js','text/javascript')
                    ->appendFile('/assets/js/espaco-aberto/sugerir.js','text/javascript')
                    ->appendFile('/assets/js/plugins/estrelas/chainedselect.js','text/javascript')
                    ->appendFile('/assets/js/jquery.form.js','text/javascript');
    }
    /**
     * Redireciona a listar 
     */
    public function homeAction()
    {
        $this->_forward('listar');
    }
    /**
     * Action que Lista todas as comunidades do usuário
     * @return Zend_View 
     */
    public function listarAction()
    {
        // breadcrums
        $perfil = $this->getPerfiluserObject();
        
        $this->setPageTitle('Minhas Comunidades');
        
        $paginaPai[] = array("titulo"=>$perfil->getNome(),"url"=>$perfil->getLinkPerfil());
        $this->view->paginaPai = $paginaPai;
        
        $this->carregarPerfil();
        
        //verificando apenas se existem comunidades pendentes para a exibição do botão
	$form = new EspacoAberto_Form_Buscar();
        
	$form->setAction('/espaco-aberto/buscar/comunidade');
	$form->getElement('filtro_buscar')->setAttrib('placeholder','buscar novas comunidades');
        
	$this->view->form_buscar = $form;
        $this->view->urlPaginator = $this->view->url(array('module' => 'espaco-aberto', 'controller' => 'comunidade','action' => 'lista-comunidades'));

        $this->listaComunidadesAction();
    }
    /**
     * Renderiza o bloco comunidade
     * @return Zend_View
     */
    public function listaComunidadesAction()
    {
        if($this->isAjax())
        {
            $this->disableLayout();
        }
        
        $usuarioperfil = $this->getPerfiluserObject();
        $pagina = $this->getParam('pagina', 1);
        $filtro = $this->getParam('filtro');
        
        $comunidade = new Aew_Model_Bo_Comunidade();
        if($filtro)
        {
            $comunidade->setNome($filtro);
            $comunidade->setDescricao($filtro);
        }

        $limite = 5;
        $options = array();
        $options['where']['comunidade.flpendente = ?'] = 'FALSE';
        $options['where']['comunidade.ativa = ?'] = 'TRUE';
        
        if($usuarioperfil && ($usuarioperfil instanceof Aew_Model_Bo_Usuario))
        { 
            $comunidades = $usuarioperfil->selectComunidadesParticipo($limite, $pagina, $comunidade, $options);
            $this->view->solicitacoes = $usuarioperfil->selectComunidadesSugeridas();
            
            if($usuarioperfil->isCoordenador())
            {
                $options['where']['comunidade.flpendente = ?'] = 'TRUE';
                $this->view->ComunidadesPendentes = $comunidade->select(0, 0, $options);
            }
        }
        else
        {
            $comunidades = $comunidade->select($limite, $pagina, $options, true);
        }

        $this->carregarPerfil();
        $this->view->comunidades = $comunidades;
    }
    
    /**
     * Método para exibir perfil da comunidade
     * @return Zend_View
     */
    public function exibirAction()
    {
        $this->carregarPerfil();
        $comunidadePerfil = $this->getPerfiluserObject();
        
        
        $this->setPageTitle($comunidadePerfil->getNome());
        if(!$comunidadePerfil instanceof Aew_Model_Bo_Comunidade || !$comunidadePerfil->getAtiva())
        {
            $this->flashMessage('Nenhum registro encontrado.',$this->getLinkListagem());
        }
        
        $form = new EspacoAberto_Form_FotoPerfil();
    	$form->setAction('/espaco-aberto/perfil/salvar-foto'.$this->getPerfilUrl());
        $form->foto->setDestination(Aew_Model_Bo_ComunidadeFoto::getFotoDirectory());
        $form->populate($comunidadePerfil->getFotoPerfil()->toArray());
        $form->getElement('idfoto')->setValue($comunidadePerfil->getFotoPerfil()->getId());
        
        $comunidadePerfil->aumentarAcesso();
        $this->view->trocarImagemForm = $form;
        
    	// Membros
        $options = array();
        $options['orderBy'] = 'usuario.nomeusuario ASC';
        
        $this->view->membros = $comunidadePerfil->selectMembrosAtivos(0, 0, null, $options);
        $this->view->comunidadesRelacionadas = $comunidadePerfil->selectComunidadesRelacionadas();
        $this->view->comunidadesRelacionadasTag = $comunidadePerfil->selectComunidadesRelacionadasTag();
        
        // Forum
    	$this->view->topicos = $comunidadePerfil->selectTopicos();
        
        //moderadores
        $this->view->perfilModeradores = $comunidadePerfil->selectModeradores();
        
        // Blog
	$this->view->blogs = $comunidadePerfil->selectBlogs();
        
        $comunidadePerfil->selectVotos();
        
        $this->view->comunidade = $comunidadePerfil;
        $this->view->usuarioPerfil = $comunidadePerfil;
        $this->view->usuarioLogado = $this->getLoggedUserObject();
        
        $this->listarFavoritosAction();
        
        // lista de colegas para sugerir 
        $formSugerir = new EspacoAberto_Form_ComunidadeSugerida();
        
        $arrayColegas = $this->listaColegas($this->view->membros);
        
        $formSugerir->setAttrib('id', 'formSugerir');
        $formSugerir->populate($comunidadePerfil->toArray());
        $formSugerir->getElement('colegas')->setMultiOptions($arrayColegas);
        
        $this->view->formSugerir = $formSugerir;
    }
    /**
     * Método para criar comunidade
     * @return Zend_View
     */
    public function adicionarAction()
    {
	$this->setPageTitle('Criar Comunidade');
	$this->carregarPerfil();
        $usuario=  $this->getLoggedUserObject();
	if(!$usuario->isCoordenador())
        {
            $this->flashError('Você não possui permissão para executar essa ação.',
            $this->getLinkListagem());
	}
	$form = new EspacoAberto_Form_ComunidadeAdicionar();
	$form->setAction('/espaco-aberto/comunidade/salvar');
	if($this->isPost())
        {
            $form->isValid($this->getPost());
	}
        
	$form->removeElement('idUsuario');
	$this->view->adicionar = $form;
    }
    
    /**
     * Salva as mudaças no banco de dados via AJAX
     * @return Zend_View
     */
    public function salvarAction()
    {
    	$this->carregarPerfil();
        $usuario = $this->getLoggedUserObject();
        $comunidade = new Aew_Model_Bo_Comunidade();
        if(!$usuario->isCoordenador())
        {
            $this->flashError('Você não possui permissão para executar essa ação.',$this->getLinkListagem());
        }
        
        $form = new EspacoAberto_Form_ComunidadeAdicionar();
        if($this->isPost())
        {
            if(false == $this->getPerfilDono())
            {
                $this->flashError('Você não possui permissão para executar essa ação.',
                $this->getLinkListagem());
            }
            
            if($form->isValid($this->getPost()))
            {
                $comunidade->exchangeArray($form->getValues());
                if(!$comunidade->getId())
                {
                    $comunidade->setUsuario($usuario);
                }
                
                if($comunidade->save())
                {
                    $this->flashMessage('Comunidade salva com sucesso',
                    $this->getLinkExibicao('/comunidade/'.$comunidade->getId(), false));
                } 
                else 
                {
                    $this->flashError('Erro ao criar/atualizar comunidade','espaco-aberto');
                }
            } 
            else 
            {
                if($form->getValue('idcomunidade') > 0)
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
     * Método para editar as opções da comunidade
     * @return Zend_View
     */
    public function editarAction()
    {
        $comunidade = $this->getPerfiluserObject();

    	if(!$comunidade)
        {
            $this->flashError('Nenhuma comunidade passada.', $this->getLinkListagem()); // ++++
    	}
        
        $this->carregarPerfil();

        if(!$this->getPerfilDono())
        {
            $this->flashError('Você não possui permissão para executar essa ação.');
            $this->_redirect($this->getLinkListagem());
        }
        
    	$form = new EspacoAberto_Form_ComunidadeAdicionar();
        $form->setAction('/espaco-aberto/comunidade/salvar');
        
    	$form->populate($comunidade->toArray());
	if($this->isPost())
        {
	    $form->isValid($this->getPost());
	}
        
	$usuarioLogado = $this->getLoggedUserObject();
	if($usuarioLogado->getUsuarioTipo()->getNome() != Aew_Model_Bo_UsuarioTipo::SUPER_ADMINISTRADOR)
        {
            $form->removeElement('idusuario');
	}
        
        $pagina = $this->getParam('pagina', 1);
        $limite = 10;
        
        $id = $this->getRequest()->getParam('comunidade');
        
        $relacionadas = new Aew_Model_Bo_Comunidade();
        
	$this->view->comunidade = $comunidade;
	$this->view->editar = $form;
        $this->view->perfil = $this->usuarioPerfil;
        
        $this->listarComunidadesRelacionadasAction();
        $this->relacionarAction();
        
        $this->setPageTitle('Configuraões');        
    }
    /**
     * Action para sair da comunidade
     * @return Zend_View JSON string
     */
    public function sairAction()
    {
        $comunidade = $this->getPerfiluserObject();
        $this->setPageTitle('Sair da comunidade ' . $comunidade->getNome());
        
        $this->carregarPerfil();
        $usuario = $this->getLoggedUserObject();
        $comuusuario = new Aew_Model_Bo_ComuUsuario();
        $comuusuario->setIdusuario($usuario->getId());
        $form = new Aew_Form_Apagar();
        
        if (!$comunidade instanceof Aew_Model_Bo_Comunidade)
        {
            $this->flashError('Nenhuma registro passado.');
            $this->_redirect($this->getLinkListagem());
        }
        
        $form->setAction('/espaco-aberto/comunidade/sair'.$this->getPerfilUrl());
        if($this->isAjax())
        {
            $this->disableLayout();
            $comuusuario->setIdusuario($this->getParam('idusuario'));
            $comuusuario = $comuusuario->select(1);
            if($comuusuario instanceof Aew_Model_Bo_Usuario && $comunidade->deleteMembro($comuusuario))
            {
                echo Zend_Json::encode(array('html'=>'Você saiu da comunidade '.$comunidade->getNome().'.','success'=>true));
                die();
            }
            else 
            {
                echo Zend_Json::encode(array('html'=>'Erro ao executar ação '.$comunidade->getNome().'.','success'=>false));
                die();
            }
        }
        if($this->isPost())
        {
            if($this->getPost('Nao'))
            {
                $this->_redirect($this->getLinkExibicao());die();
            }
            else if($comunidade->deleteMembro($comuusuario->select(1)))
            {
                $this->flashMessage('Você saiu da comunidade.');
                $this->_redirect($this->getLinkExibicao());
            } 
            else 
            {
                $this->flashError('Houve um problema ao tentar bloquear o registro.');
                $this->_redirect($this->getLinkExibicao());
            }
        }

        $this->view->form = $form;
        $this->view->comunidade = $comunidade;
    }
    /**
     * Método petição para ser membro da comunidade
     * @return flash message ou redireciona
     */
    public function participarAction()
    {
        $this->carregarPerfil();
        $usuario = $this->getLoggedUserObject();
        $comunidade = $this->getPerfiluserObject(); // ++++
        if ($comunidade->isBloqueado($usuario))
        {
            $this->flashMessage('Usuário bloqueado da comunidade, participação vedada.',
            $this->getLinkExibicao());
        }
        if($comunidade->isParticipante($usuario))
        {
            $this->flashMessage('Usuário ja pertence a comunidade.',
            $this->getLinkExibicao());
        }
        else 
        {
            if($this->getTipoPagina() == Sec_Constante::COMUNIDADE)
            {
                if ($comunidade->getFlmoderausuario())   
                {
                    $comunidade->insertRequisicaoMembro($usuario);
                    $this->flashMessage('Você entrou em uma nova comunidade. Aguarde aprovação.',
                    $this->getLinkExibicao());
                }
                else 
                {
                    if($comunidade->insertMembro($usuario))
                    {
                        $this->flashMessage('Você entrou em uma nova comunidade.',
                        $this->getLinkExibicao());
                    }
                }
	    }
            
        }
    }
    
    /**
     * Método para bloquear membro da comunidade
     * @return Zend_View
     */
    public function bloquearAction()
    {
        $comunidade = $this->getPerfiluserObject();
        $this->setPageTitle('Bloquear comunidade '. $comunidade->getNome());
        
        $this->carregarPerfil();
        
        if(false == $this->getPerfilDono()){
            $this->flashError('Você não possui permissão para executar essa ação.',
            $this->getLinkListagem());
        }
        $form = new Aew_Form_Apagar();
        if (!$comunidade instanceof Aew_Model_Bo_Comunidade)
        {
            $this->flashError('Nenhum registro encontrado.');
            $this->_redirect($this->getLinkListagem());
        }
        $form->setAction('/espaco-aberto/comunidade/bloquear'.$this->getPerfilUrl());
        if($this->isPost())
        {
            if($this->getPost('nao'))
            {
                $this->_redirect($this->getLinkListagem());
            }
            else if($this->getPost('sim'))
            {
                $comunidade->setAtiva(false);
                if($comunidade->update())
                {
                    $this->flashMessage('Comunidade bloqueada com sucesso!.');
                    $this->_redirect($this->getLinkListagem());
                }
                else 
                {
                    $this->flashError('Houve um problema ao tentar apagar o registro.');
                    $this->_redirect($this->getLinkListagem());
                }
            } 
            
        }
        $this->view->form = $form;
        $this->view->objeto = $comunidade;
        $this->view->comunidade = $comunidade;
    }
    /**
     * Método para aceitar sugestão de comunidade
     * @return flash message ou redireciona
     */
    public function aceitarSugestaoAction()
    {
        $this->carregarPerfil();
        $usuario = $this->getLoggedUserObject();
        $idcomunidade = $this->getParam('idcomunidade', false);
        $comunidadeSugerida = new Aew_Model_Bo_ComunidadeSugerida();
        $comunidadeSugerida->setId($idcomunidade);
        if(!$comunidadeSugerida->selectAutoDados())
        {
            $this->flashError('Nenhum registro passado.');
            $this->_redirect($this->getLinkListagem());
        }
        $comunidadeSugerida->setVisto(true);
        $result = $comunidadeSugerida->update();
        if($result)
        {
            $comunidade = new Aew_Model_Bo_Comunidade();
            $comunidade->setId($idcomunidade);
            $result = $comunidade->insertMembro($comunidadeSugerida->getUsuario());
            if($result)
            $this->flashMessage('Marcação aceita com sucesso.',
            					'espaco-aberto/comunidade/participar/comunidade/'.$idcomunidade);
            else 
            {
                $this->flashMessage('Houve um erro na operação', $this->getLinkListagem());
            }
    	} 
        else 
        {
    	    $this->flashMessage('Houve um erro na operação', $this->getLinkListagem());
    	}
    }
    /**
     * Método para recusar sugestão de comunidade
     * @return flash message ou redireciona
     */
    public function recusarSugestaoAction()
    {
    	$this->carregarPerfil();
        $usuario = $this->getLoggedUserObject();
        $idcomunidade = $this->getParam('idcomunidade', false);
        $comunidadeSugerida = new Aew_Model_Bo_ComunidadeSugerida();
        $comunidadeSugerida->setId($idcomunidade);
        if(!$comunidadeSugerida->selectAutoDados())
        {
            $this->flashError('Nenhum registro passado.');
            $this->_redirect($this->getLinkListagem());
        }
        $result = $comunidadeSugerida->delete();
        if(true == $result)
        {
            
                $this->flashMessage('Marcação recusada com sucesso.',
                                'espaco-aberto/comunidade/listar/usuario/'.$usuario->getId());
        }
        else 
        {
    	    $this->flashMessage('Houve um erro na operação', $this->getLinkListagem());
    	}

    }
    /**
     * Método para valorar (votar) a comunidade
     * @return Zend_View
     */
    public function votarAction()
    {
        $ajax = false;
        if($this->isAjax())
        $ajax = true;
    	$this->carregarPerfil();
        $usuario = $this->getLoggedUserObject();
        $request = $this->getRequest();
        $comunidade = new Aew_Model_Bo_Comunidade();
        $acl = Sec_Acl::getInstance();
        $comunidade->setId($request->getParam('comunidade'));
        $voto = $request->getParam('voto');
        
        if(!$comunidade->selectAutoDados())
        {
            $this->flashError('Objeto não identificado, por favor tente novamente.');
            $this->_redirect($this->getLinkListagem());
        }
        if(false == $acl->isAllowed($usuario->getUsuarioTipo()->getNome(), 'espaco-aberto', 'votar'))
        {
            $this->flashError('Você não tem permissão para votar.');
            $this->_redirect($this->getLinkExibicao());
    	}
        $result = $comunidade->saveAvaliacao($usuario, $voto);
        if($result)
        {
            $this->flashMessage('Voto computado com sucesso.');
            if($ajax)
            {
                echo Zend_Json::encode(array('html'=>'Voto computado com sucesso.','success'=>true));die();
            }
            $this->_redirect($this->getLinkExibicao());
        }
        else 
        {
            return Zend_Json::encode(array('html'=>'Erro ao inserir.','success'=>false));
        }
        return Zend_Json::encode(array('html'=>'Erro ao inserir.','success'=>false));
    }
    
    /**
     * Action para relacionar comunidades
     * @ajax
     */
    public function relacionarAction()
    {
        if($this->isAjax())
        {
            $this->disableLayout();
        }

        $this->view->urlPaginator = $this->view->url(array('module' => 'espaco-aberto', 'controller' => 'comunidade','action' => 'lista-relacionar'));
        $this->listaRelacionarAction();
    }
    
    /**
     * Método para relacionar comunidades de forma manual
     * @return Zend_View
     */
    public function listaRelacionarAction()
    {
        if($this->isAjax())
        {
            $this->disableLayout();
        }

        $this->carregarPerfil();
        
        $options = array();
        $limite  = 5;
            
        $idcomunidade = $this->getRequest()->getParam('comunidade', null);
    	$pagina       = $this->getParam('pagina', 1);
        $filtro       = $this->getParam('filtro');

        $comunidade = new Aew_Model_Bo_Comunidade();
        $comunidade->setId($idcomunidade);
        //$comunidade->select(1);
        if($filtro)
        {
            $options['where']['(lower(sem_acentos(comunidade.nomecomunidade)) LIKE lower(sem_acentos(?)) OR lower(sem_acentos(comunidade.descricao)) LIKE lower(sem_acentos(?)))'] = "%".$filtro."%";
        }   
        
        $options['orderBy'] = array('comunidade.qtdvisitas DESC','comunidade.nomecomunidade ASC');
        
        $comunidadeRelacionar = $comunidade->selectComunidadesARelacionar($limite, $pagina, $comunidade, $options);
        $comunidadeRelacionar = $comunidade->getAsPagination($comunidadeRelacionar, $pagina, $limite, 10);
        
        $this->view->comunidadesRelacionar = $comunidadeRelacionar;
        $this->view->filtro = $filtro;
    }
    
    /**
     * Adiciona relação entre comunidades
     * @return flash message ou redireciona
     *  
     */
    public function adicionarRelacaoAction()
    {
	if($this->isAjax())
        {
            $this->disableLayout();
        }
        
        $id = $this->getRequest()->getParam('comunidade', false);
        $relacionar = $this->getRequest()->getParam('relacionar', false);
        
        $comunidade = new Aew_Model_Bo_Comunidade();
        $comunidade->setId($id);
        
        $comunidadeRelacionada = new Aew_Model_Bo_Comunidade();
        $comunidadeRelacionada->setId($relacionar);
        
	if(!$comunidade->selectAutoDados() || !$comunidadeRelacionada->selectAutoDados())
        {
	    return false;
	}
        
	$comunidade->insertComunidadeRelacionada($comunidadeRelacionada);
        $this->flashMessage('Relação adicionada com sucesso.', '/espaco-aberto/comunidade/editar/comunidade/'.$id);
        die();
    }
    /**
     * Remove a relação da comunidade
     * @return Zend_View
     */
    public function removerRelacaoAction()
    {
        $this->disableLayout();
        // Parametros URL
        $id = $this->getRequest()->getParam('comunidade', false);
        $relacionado = $this->getRequest()->getParam('relacionado', false);
        
        $comunidadeBo = new Aew_Model_Bo_Comunidade();
        $comunidadeBo->setId($id);
        
        $comunidadeRelacionada = new Aew_Model_Bo_Comunidade();
        $comunidadeRelacionada->setId($relacionado);
        
        if($comunidadeBo->isRelacionado($comunidadeRelacionada))
        {
            if($comunidadeBo->deleteComunidadeRelacionada($comunidadeRelacionada))
            {
               $this->flashMessage('Relação removida com sucesso.');
               $this->_redirect('/espaco-aberto/comunidade/editar/comunidade/'.$id);
            }else 
            {
                $this->flashError("Não foi possível remover comunidade. Tente novamente.");
                $this->_redirect('/espaco-aberto/comunidade/editar/comunidade/'.$id);
            }
        }    
        
        
        $this->view->id = $id;
    }
    /**
     * Lista de comunidades relacionadas
     * @return Zend_View
     */
    public function listarComunidadesRelacionadasAction()
    {
        if($this->isAjax())
        {
            $this->disableLayout();
        }
        
        $this->setPageTitle('Comunidades relacionadas');
        $this->carregarPerfil();
        
    	$limite = 10;

        $id = $this->getRequest()->getParam('comunidade', false);
        $pagina = $this->getRequest()->getParam('pagina', 1);
        
        $paginaPai[] = array("titulo" => $this->usuarioPerfil->getNome(), "url" => $this->usuarioPerfil->getLinkPerfil());
        $this->view->paginaPai = $paginaPai;

        $comunidade = new Aew_Model_Bo_Comunidade();
    	$comunidade->setId($id);
        
        $comunidadesRelacionadas = $comunidade->selectComunidadesRelacionadas($limite, $pagina);
        $comunidadesRelacionadas = $comunidade->getAsPagination($comunidadesRelacionadas,$pagina,$limite);
        
        $this->view->urlPaginatorRelacionadas = $this->view->url(array('module' => 'espaco-aberto', 'controller' => 'comunidade','action' => 'listar-comunidades-relacionadas'));
        $this->view->comunidadesRelacionadas = $comunidadesRelacionadas;
    }
    
    /**
     * Lista de comunidades pendentes por aprovar
     * @return Zend_View
     */
    public function pendentesAction()
    {
        $pagina = $this->getParam('pagina', 1);
        $this->setPageTitle('Comunidades pendentes por aprovar');
        $this->carregarPerfil(true);
        
        if(false == $this->isAllowed('espaco-aberto', 'inserir-comunidades-ilimitadas'))
        {
            $this->flashError('Você não possui permissão para executar essa ação.');
            $this->_redirect($this->getLinkListagem());
        }
        
    	$limite = 10;
        $comunidade = new Aew_Model_Bo_Comunidade();
        $comunidade->setFlpendente(true);
        
        $comunidadesPendentes = $comunidade->select($limite, $pagina, null, true);
        $comunidadesPendentes = $comunidade->getAsPagination($comunidadesPendentes, $pagina, $limite, 5);
        $this->view->comunidadesPendentes = $comunidadesPendentes;
    }
    /**
     * Aceita convite de usuario para participar na comunidade
     * @return Zend_View
     */
    public function aceitarAction()
    {
    	$this->carregarPerfil();
        if(false == $this->isAllowed('espaco-aberto', 'inserir-comunidades-ilimitadas'))
        {
            $this->flashError('Você não possui permissão para executar essa ação.');
            $this->_redirect($this->getLinkListagem());
        }
        $id = $this->getParam('id', false);
        $comunidade = new Aew_Model_Bo_Comunidade();
        $comunidade->setId($id);
        if(!$comunidade->selectAutoDados())
        {
            $this->flashError('Nenhuma registro passado.');
            $this->_redirect($this->getLinkListagem());
        }
        $comunidade->setFlpendente("FALSE");
    	if(!$comunidade->update())
        {
            $this->flashMessage('Comunidade liberada com sucesso.',
                                'espaco-aberto/comunidade/pendentes'.$this->getPerfilUrl());
    	} 
        else 
        {
    	    $this->flashMessage('Houve um erro na operação',
    	                        'espaco-aberto/comunidade/pendentes' . $this->getPerfilUrl());
    	}
        $this->view->comunidade = $comunidade;
    }
    /**
     * Recusa convite de partivipar na comunidade
     * @return flash message ou redireciona
     * 
     */
    public function recusarAction()
    {
        $this->carregarPerfil();
        if(false == $this->isAllowed('espaco-aberto', 'inserir-comunidades-ilimitadas'))
        {
            $this->flashError('Você não possui permissão para executar essa ação.');
            $this->_redirect($this->getLinkListagem());
        }
        $id = $this->getParam('id', false);
        $comunidade = new Aew_Model_Bo_Comunidade();
        $comunidade->setId($id);
        if(!$comunidade->selectAutoDados())
        {
            $this->flashError('Nenhuma registro passado.');
            $this->_redirect($this->getLinkListagem());
        }
    	if(!$comunidade->delete())
        {
            $this->flashMessage('Comunidade recusada com sucesso.',
                                'espaco-aberto/comunidade/pendentes' . $this->getPerfilUrl());
    	} 
        else 
        {
    	    $this->flashMessage('Houve um erro na operação',
    	                        'espaco-aberto/comunidade/pendentes' . $this->getPerfilUrl());
    	}
    }
    /**
     * Action que lista as comunidades sugeridas por seus colegas
     * @return Zend_View
     */
    public function sugeridasAction()
    {
        if($this->isAjax())
        {
            $this->disableLayout();
        }
            
        $this->carregarPerfil();
        
        $pagina = $this->getParam('pagina', 1);
        $usuario = $this->getLoggedUserObject();
        
    	$limite = 5;
        $solicitacoes = $usuario->selectComunidadesSugeridas($limite, $pagina);
	$solicitacoes = $usuario->getAsPagination($solicitacoes, $pagina, $limite, 5);
        
	$this->view->solicitacoes = $solicitacoes;
    }
    
    /**
     * Action para sugerir uma comunidade AJAX
     * @return JSON string
     */
    public function sugerirAction()
    {
        $this->disableLayout();
        
        $idComunidade=$this->getParam('idcomunidade');
        $idColegas= $this->getParam('colegas');  

        if ($this->adicionarMarcacao($idColegas, $idComunidade))
        {
            echo '<small class="sucesso alert alert-success padding-all-05"><b>Colega(s) convidado(s) com sucesso</b></small>';
        } 
        else 
        {
            echo '<small class="erro alert alert-danger padding-all-05"><b>Problema no envio do(s) convite(s)</b></small>';
        }
        die();
    }
    
    /**
     * Adiciona uma sugerencia de comunidade 
     * @param type $idColegas
     * @param type $idComunidade
     * @return null
     */
    private function adicionarMarcacao($idColegas, $idComunidade)
    {
    	$usuario = $this->getLoggedUserObject();
        $result = null;
        $count = count($idColegas);
        
        if($count!=0)
        {
            for($i=0;$i<$count;$i++)
            {
                $comunidadeRelacionar = new Aew_Model_Bo_Comunidade();
                $comunidadeRelacionar->setId($idComunidade);
                $usuarioConvidado= new Aew_Model_Bo_Usuario;
                $usuarioConvidado->setId($idColegas[$i]);
                
                $result = $usuario->insertComunidadeSugerida($comunidadeRelacionar, $usuarioConvidado);
            }
            return $result;
        }
    }

    /*
     *retorna array com ids e nome dos colegas para plugin jquery 
     * @return array('id'=>'nomecolega')
     */
    private function listaColegas($membros)
    {
        $arrayMembro = array();
        foreach($membros as $membro)
        {
            array_push($arrayMembro, $membro->getId());
        }
        
        $options = array();
        $options['orderBy'] = 'usuario.nomeusuario ASC';
        
        $usuario = $this->getLoggedUserObject();
        $colegas = $usuario->selectColegas(0, 0, null, $options);
        
        $arrayColegas = array();
        foreach($colegas as $colega)
        {  
            if(!array_key_exists($colega->getId(), $arrayMembro))
            {
                $img = $colega->getFotoPerfil()->getFotoCache(Aew_Model_Bo_Foto::$FOTO_CACHE_30X30,false, 24, 24, false, 'img-circle shadow-center margin-right-10');
                $nome = strtoupper($this->retiraAcentuacao($colega->getNome()));
                $arrayColegas[$nome] = array($colega->getId() => $img.strtolower($colega->getNome()));
            }
        }
        
        return ($arrayColegas);
    }
}
