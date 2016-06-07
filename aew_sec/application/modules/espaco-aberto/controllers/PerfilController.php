<?php
class EspacoAberto_PerfilController extends Sec_Controller_Action_EspacoAberto
{
    public function init()
    {
        parent::init();
        $acl = $this->getHelper('Acl');
	$amigoDaEscolaAction = array('feed', 'feed-listar', 'exibir', 'bloquear', 'trocar-imagem', 'trocar-senha', 'editar-perfil', 'exibir-perfil', 'sobre-mim', 'exibir-sobre-mim', 'minhas-redes-sociais', 'salvar-sobre-mim', 'salvar-minha-rede-social', 'apagar-minha-rede-social', 'configuracoes', 'salvar-foto', 'salvar-senha','listar-favoritos','teste');
	$acl->allow(Aew_Model_Bo_UsuarioTipo::AMIGO_DA_ESCOLA, $amigoDaEscolaAction);
	$this->setLinkListagem('espaco-aberto/perfil/feed');
    }
    /**
     * Redireciona para feedAction
     */    
    public function feedListarAction()
    {
        if($this->isAjax())
        {
            $this->disableLayout ();
        }
        $this->feedAction();
    }
    /**
     * Lista no mural atualizações da conta
     * @return Zend_View 
     */
    public function feedAction()
    {
        $usuarioLogado = $this->getLoggedUserObject();
        $usuarioPerfil = $this->getPerfiluserObject();
        $this->carregarPerfil(); 
        $this->setPageTitle($this->usuarioPerfil->getNome());
        $pagina = $this->getRequest()->getParam("pagina", 0);
        $posicao = $this->getRequest()->getParam("posicao", 0);
        $limite = 10;
        $pagina = $limite * $pagina;
        $this->view->feeds =  $this->usuarioPerfil->selectFeedsDetalhe($limite,$pagina,null,$posicao);
	// Favoritos 
        $this->listarFavoritosAction();
        if($this->usuarioLogado->getId() == $this->usuarioPerfil->getId())
        {
            $this->view->solicitacoes = $this->usuarioPerfil->selectColegasPendentes();
	}
    }
    /**
     * Redireciona a exibir perfil
     */
    public function exibirSobreMimAction()
    {
        $this->_forward('exibir'); 
    }
    /**
     * Edita perfil do usuário sobre mim, redes sociais, senha 
     * @return Zend_View
     */
    public function editarPerfilAction()
    {
        $usuario = $this->getPerfiluserObject();
	$this->setPageTitle('Editar meu Perfil');
	$this->carregarPerfil();
	if($this->getTipoPagina() == Sec_Constante::USUARIO):
            $this->setPageSubTitle('Editar perfil');
	endif;
        $sobreMim = new EspacoAberto_Form_SobreMimPerfil();
        $form = new EspacoAberto_Form_FotoPerfil();
        $form->setAction('/espaco-aberto/perfil/salvar-foto'.$this->getPerfilUrl());
        $form->populate($usuario->getFotoPerfil()->toArray());
        $form->getElement('idfoto')->setValue($usuario->getFotoPerfil()->getId());
        $componentes = $usuario->selectComponentesCurriculares();
        $data = $usuario->getSobreMim()->toArray();
        $i = 0;
        foreach ($componentes as $componente)
        {
            $data['componenteCurricular'][$i] = $componente->toArray();
            $i++;
        }
        /* Trocar senha */
        $formTrocarSenha = new EspacoAberto_Form_TrocarSenha();
    	$formTrocarSenha->setAction('/espaco-aberto/perfil/salvar-senha');
        /* Redes Sociais */
        $formMinhasRedes= new EspacoAberto_Form_MinhasRedesSociais();
        $formMinhasRedes->setAction('/espaco-aberto/perfil/salvar-minha-rede-social'.$this->getPerfilUrl());
        /* Sobre mim */
        $sobreMim->populate($data);
        $sobreMim->setAction('/espaco-aberto/perfil/salvar-sobre-mim/usuario/'.$usuario->getId());
        
        $this->view->trocarImagemForm = $form;
        $this->view->formTrocarSenha = $formTrocarSenha; 
        $this->view->formMinhasRedesSociais = $formMinhasRedes;
        $this->view->sobreMim = $sobreMim;
        
        
        $rede = new Aew_Model_Bo_UsuarioRedeSocial();
        $rede->select();
        $this->view->rede = $rede ;
        
        
        $this->view->headScript()->appendFile('/assets/js/espaco-aberto/editar-perfil.js');
    }
    
    /**
     * Opção do administrador ou superAdministrador bloqueia usuario
     * @return Zend_View
     */
    public function bloquearAction()
    {
        $this->carregarPerfil();
	$usuarioLogado = $this->getLoggedUserObject();
        $this->setPageTitle('Bloquear usuário'.$usuarioLogado->getNome());
        if (!$usuarioLogado->isSuperAdmin())
        {
            $this->flashError('Você não possui permissão para executar essa ação.',
            $this->getLinkListagem());
        }
        $form = new Aew_Form_Apagar();
        $usuario = $this->getPerfiluserObject();
        if (!$usuario)
        {
            $this->flashError('Nenhum registro encontrado.');
            $this->_redirect($this->getLinkListagem());
        }
        $form->setAction('/espaco-aberto/perfil/bloquear'.$this->getPerfilUrl());
        if($this->isPost())
        {
            if(false != $this->getPost('Nao'))
            {
                $this->_redirect($this->getLinkListagem());
            }
            if($usuario->bloquearUsuario())
            {
                $this->flashMessage('Registro apagado com sucesso.');
                $this->_redirect('espaco-aberto');
            } 
            else 
            {
                $this->flashError('Houve um problema ao tentar apagar o registro.');
                $this->_redirect($this->getLinkListagem());
            }
        }
        $this->view->form = $form;
        $this->view->objeto = $usuario;
    }
    /**
     * Exibe sobre mim em mural
     * @return Zend_View
     */
    public function sobreMimAction()
    {    
         if($this->isAjax())
         $this->disableLayout();
         $this->carregarPerfil();
         $usuario = $this->getPerfiluserObject();
         $sobreMimForm = new EspacoAberto_Form_SobreMimPerfil();
         //$sobreMimUsuario = $usuario->getSobreMim();
         $redesSociais = $usuario->selectRedesSociais();
         $minhasRedesSociaisForm = new EspacoAberto_Form_MinhasRedesSociais();
         $minhasRedesSociaisForm->setAction('/espaco-aberto/perfil/salvar-minha-rede-social'.$this->getPerfilUrl());
         
         $this->view->redesSociais = $redesSociais;
         $this->view->formMinhasRedesSociais = $minhasRedesSociaisForm;
         $this->view->usuario = $usuario;
         $this->view->sobreMimForm = $sobreMimForm;
         
         //$this->view->sobreMimUsuario = $sobreMimUsuario;
    }
    /**
     * Redireciona a sobre mim
     */
    public function minhasRedesSociaisAction()
    {
        $this->_forward('sobre-mim');
    }
    /**
     * Salva sobre mim
     * @return mensagem de informação ou redireciona 
     */
    public function salvarSobreMimAction()
    {
        $this->carregarPerfil();
        $usuarioPerfil = $this->getPerfiluserObject();
        $usuarioLogado = $this->getLoggedUserObject();
        $form = new EspacoAberto_Form_SobreMimPerfil();
        if($this->isPost())
        {
            if($usuarioLogado->isSuperAdmin()||$usuarioLogado->isDonoPerfil($usuarioPerfil))
            if($form->isValid($this->getPost()))
            {
                $usuarioPerfil->getSobreMim()->exchangeArray($form->getValues());
                $usuarioPerfil->selectComponentesCurriculares(); 
                $usuarioPerfil->deleteComponentesCurriculares(); 
                $usuarioPerfil->setComponentesCurriculares($form->getValue('AllComponentesCurriculares'));
                $usuarioPerfil->setComponentesCurriculares(array());
                $usuarioPerfil->insertComponentes();
                $usuarioPerfil->getSobreMim()->setDataenvio(date('Y-m-d h:i:s'));
                $sobremim = $usuarioPerfil->getSobreMim(); 
                if($usuarioPerfil->getSobreMim()->save())
                    $this->flashMessage('Perfil atualizado com sucesso..');
                else
                    $this->flashError('Problema na atualização. Verifique se esse link já existe.');
            } 
        } 
        else 
        {
            $this->flashError('Nenhuma informação para salvar.');
        }
        $this->_forward('editar-perfil');
    }
    /**
     * Salva url da rede social 
     * @return boolean para proceso por javascript
     */
    public function salvarMinhaRedeSocialAction()
    {
        $this->carregarPerfil();
        $usuario = $this->getLoggedUserObject();
        
        $urlSocial = $this->getParam('url');
        $rede = $this->validaUrlRedeSocial($urlSocial);
        
        if($rede instanceof Aew_Model_Bo_RedeSocial)
        {
            if($usuario->insertRedeSocial($rede,$urlSocial))
            {
                $this->_redirect('espaco-aberto/perfil/editar-perfil');
            }
            else
            {
                die("false");
            }    
        }
       else
        {
            die("false");
        }
         
    }
    /**
     * Apaga url da rede social método acessado por AJAX
     * @return boolean para proceso por javascript
     */
    public function apagarMinhaRedeSocialAction()
    {
        $this->disableLayout();
        $id = $this->getParam('id');
        $usuario = $this->getPerfiluserObject();
        $rede = new Aew_Model_Bo_UsuarioRedeSocial(); // ++++
        $rede->setId($id);
        if($rede->selectAutoDados())
        {
            $result =  $usuario->deleteRede($rede);
            
            if ($result){$this->flashMessage('Perfil atualizado com sucesso. ');
            }else{$this->flashError('Problema na atualização.');}
        } 
        else 
        {
            $this->flashError('Nenhuma informação para salvar.');
        }
        $this->_redirect('espaco-aberto/perfil/editar-perfil');
    }
    /**
     * Não está sendo utilizado 
     */
    public function configuracoesAction()
    {
        $this->carregarPerfil();
        if(false == $this->getPerfilModerador())
        {
            $this->flashError('Você não possui permissão para executar essa ação.');
            $this->_redirect($this->getLinkListagem());
        }
    	$formTrocarSenha = new EspacoAberto_Form_TrocarSenha();
    	$formTrocarSenha->setAction('/espaco-aberto/perfil/salvar-senha');
	if($this->isPost())
        {
	    $formTrocarSenha->isValid($this->getPost());
	}
	$this->view->adicionarSenha = $formTrocarSenha;
        if($this->getTipoPagina() == Sec_Constante::USUARIO)
        {
            $this->setPageSubTitle('Alterar foto do perfil');
    	} 
        elseif($this->getTipoPagina() == Sec_Constante::COMUNIDADE)
        {
    	    $this->setPageSubTitle('Alterar foto da comunidade');
    	}

        if(false == $this->getPerfilModerador())
        {
            $this->flashError('Você não possui permissão para executar essa ação.');
            $this->_redirect($this->getLinkListagem());
        }

    	$formFotoPerfil = new EspacoAberto_Form_FotoPerfil();
    	$formFotoPerfil->setAction('/espaco-aberto/perfil/salvar-foto'.$this->getPerfilUrl());

	if($this->isPost())
        {
	    $formFotoPerfil->isValid($this->getPost());
	}

	$this->view->adicionarImagem = $formFotoPerfil;
	$this->view->usuario = $this->getLoggedUserObject();

    }
    /**
     * Trocar Imagem de perfil
     * @return Zend_View
     */
    public function trocarImagemAction()
    {
        if($this->isAjax())
            $this->disableLayout();
        
        $this->setPageTitle('Espaço Aberto');
        $this->carregarPerfil();
        
        $usuarioLogado = $this->getLoggedUserObject();
        $usuarioPerfil = $this->getPerfiluserObject();
        
        if($this->getTipoPagina() == Sec_Constante::USUARIO)
        {
            $this->setPageSubTitle('Alterar foto do perfil');
        } 
        elseif($this->getTipoPagina() == Sec_Constante::COMUNIDADE)
        {
    	    $this->setPageSubTitle('Alterar foto da comunidade');
    	}
        
        if(false == $this->getPerfilModerador())
        {
            $this->flashError('Você não possui permissão para executar essa ação.');
            $this->_redirect($this->getLinkListagem());
        }
        
    	$form = new EspacoAberto_Form_FotoPerfil();
    	$form->setAction('/espaco-aberto/perfil/salvar-foto'.$this->getPerfilUrl());
        $form->populate($usuarioPerfil->getFotoPerfil()->toArray());
        if($this->isPost())
        {
            $form->isValid($this->getPost());
	}
	
        $this->view->trocarImagemForm = $form;
	$this->view->usuario = $usuarioLogado;
    }
    /**
     * Salva foto o arquivo e em banco
     * @return Bolean JSON string 
     */
    public function salvarFotoAction()
    {
        $this->carregarPerfil();
        $usuarioPerfil = $this->getPerfiluserObject();
        if(false == $this->getPerfilModerador())
        {
            $this->flashError('Você não possui permissão para executar essa ação.');
            $this->_redirect($this->getLinkListagem());
        }
        $form = new EspacoAberto_Form_FotoPerfil();
        if($this->isPost())
        {
            if($form->isValid($this->getPost()))
            {   
                $foto = new Aew_Model_Bo_UsuarioFoto();
                $foto->exchangeArray($form->getValues());
                $idfoto = $form->getValue('idfoto',false);
                if(!$idfoto)
                {
                    $foto = $usuarioPerfil->selectFotoPerfil ();
                }
                else
                {
                    $foto->setId($idfoto);
                }
                $foto->setFotoFile($form->foto);
                if($usuarioPerfil->saveFotoPerfil($foto))
                {
                    if($this->isAjax())
                    {
                        echo Zend_Json::encode(array('success'=>true,'html'=>'Imagem inserida com sucesso.','url'=>$foto->getUrl()));
                        die();
                    }
                }
                else
                {
                    echo Zend_Json::encode(array('success'=>false,"html"=>'Erro ao salvar foto'));
                    die();
                }
            } 
            else 
            {   
                $mensagenErro = implode('\n',$form->getMessages('foto'));
                echo Zend_Json::encode(array('success'=>false,"html"=>$mensagenErro)); die();
            }
        } 
        else 
        {
            $this->flashError('Nenhuma informação para salvar.');
            if($this->getTipoPagina() == Sec_Constante::USUARIO)
            {
                $this->_redirect($this->getLinkListagem());
	    } 
            elseif($this->getTipoPagina() == Sec_Constante::COMUNIDADE)
            {
	        $this->_redirect('espaco-aberto/comunidade/exibir'.$this->getPerfilUrl());
	    }
        }
        die();
    }
    /**
     * Troca senha do perfil
     * @return  Zend_View
     */
    public function trocarSenhaAction()
    {   
        $this->setPageSubTitle('Trocar senha');

        if(false == $this->getPerfilModerador()){
            $this->flashError('Você não possui permissão para executar essa ação.');
            $this->_redirect($this->getLinkListagem());
        }

    	$form = new EspacoAberto_Form_TrocarSenha();
    	$form->setAction('/espaco-aberto/perfil/salvar-senha');
	if($this->isPost())
        {
	    $form->isValid($this->getPost());
	}
	$this->view->formMudarSenha = $form;
    }
    /**
     * Salva à senha enviada 
     * @return 
     */
    public function salvarSenhaAction()
    {
        $this->carregarPerfil();
        if(false == $this->getPerfilModerador())
        {
            $this->flashError('Você não possui permissão para executar essa ação.');
            $this->_redirect($this->getLinkListagem());
        }
        $form = new EspacoAberto_Form_TrocarSenha();
        $usuario = $this->getLoggedUserObject();
        if($this->isPost())
        {
            if($form->isValid($this->getPost()))
            {
                $result = $usuario->trocarSenha($form->getValue('senhaAtual'), $form->getValue('novaSenha'));
                if($result)
                {
                    $this->flashMessage('Senha alterada com sucesso.');
                    $this->_redirect($this->getLinkListagem());
                } 
                else 
                {
                    $this->flashError('A senha atual fornecida é inválida.');
                    $this->_forward('trocar-senha');
                }
            } 
            else 
            {
                $this->_forward('trocar-senha');
            }
        } 
        else 
        {
            $this->flashError('Nenhuma informação para salvar.');
            $this->_redirect($this->getLinkListagem());
        }
    }
    /**
     * inicializa as urls exibidas pela pagina de perfil do usuario
     * @param Aew_Model_Bo_Usuario $usuario
     */
    function initUrlsPerfil(Aew_Model_Bo_Usuario $usuario)
    {
        $this->view->urlListarColegas = $this->view->url(array('module'=>'espaco-aberto','controller'=>'colega','action'=>'listar','usuario'=>$usuario->getId()));
        $this->view->urlListaRecados = $this->view->url(array('module'=>'espaco-aberto','controller'=>'recado','action'=>'listar','usuario'=>$usuario->getId()));
    }
    /**
     * Retorna true ou false se url coincide com rede social do banco de dados
     * @param type $redeSocialUrl
     * @return boolean
     */
    private function validaUrlRedeSocial($redeSocialUrl)
    {   
        // pega o dominio
        $host = parse_url($redeSocialUrl,PHP_URL_HOST);
        
        // tira subdominios -> en.example.com
        $subdominio= explode('.', $host);
        $count = count($subdominio);
        
        switch ($count) {
            case 2:
                    $host= implode('.', $subdominio);
                break;
            case 3:
                    if($subdominio[0]=="plus")
                    {
                        $host= implode('.', $subdominio); // excepção para google plus
                    }
                    elseif($subdominio[0] =="www")
                    {
                        $subdominio = array_slice($subdominio,1); // tira primer elemento www
                        $host = implode('.', $subdominio);
                    }
                    else{
                        $subdominio = array_slice($subdominio,1); // tira primer elemento subdominio
                        $host = implode('.', $subdominio);
                    }     
                break;
            default :
                break;
        }    
        
        
        $rede = new Aew_Model_Bo_RedeSocial();
        foreach ($rede->select() as $rede)
        {
           $exp = $rede->getSite();
           
           if(strcmp ($exp , $host ) == 0 )
           {
               return $rede;
           }
        }
        
    }
}
