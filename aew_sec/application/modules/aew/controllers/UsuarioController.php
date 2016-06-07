<?php
/**
 * Controle referente as ações do usuário
 * @author 
 */
class UsuarioController extends Sec_Controller_Action
{
    /**
     * Função chamada para iniciar o Controller, antes do pre-dispatch
     * @return void
     */
    public function init()
    {
        parent::init();
        $acl = $this->getHelper('Acl');
        $acl->allow(null);
    }

    /**
     * Ação caso um usuário tenha esquecido sua senha
     * @return Zend_View
     */
    public function esqueciASenhaAction()
    {
	$this->setPageTitle('Recuperar Senha');
        $form = new Aew_Form_EsqueciASenha();
        $this->view->formEsqueciASenha = $form;
        if($this->getRequest()->isPost())
        {
            $captcha = New Sec_View_Helper_ReCaptcha();
            if($captcha->validar($this->getRequest()->getPost()))
            {
                $this->getRequest()->setPost("recaptcha","OK");
            }
            if($form->isValid($this->getRequest()->getPost()))
            {
                $email = $form->getValue('email');
                $usuarioBo = new Aew_Model_Bo_Usuario();
                //--- Verifica primeiro no e-mail institucional
                $usuarioBo->setEmail($email);
                $usuarioBo = $usuarioBo->select(1);
                if(!$usuarioBo instanceof Aew_Model_Bo_Usuario)
                {
                    //--- Caso de não encontrado, verifica no e-mail pessoal
                    $usuarioBo = new Aew_Model_Bo_Usuario();
                    $usuarioBo->setEmailPessoal($email);
                    $usuarioBo = $usuarioBo->select(1);

                    if(!$usuarioBo instanceof Aew_Model_Bo_Usuario)
                    {
                        $this->flashError('Não foi encontrado um usuário com esse e-mail no sistema.');
                        return;
                    }
                }
                
                if(!$this->enviarRecuperarSenha($usuarioBo))
                {
                    $this->flashError('Não foi possível enviar o e-mail');
                    return;
                }
                
                $this->flashMessage('Um e-mail foi enviado para você com informações para recuperar sua senha.');
                $form->reset();
            }
        }

    }

    /**
     * @param Aew_Model_Bo_Usuario $usuario
     * @return Zend_View
     */
    private function enviarRecuperarSenha(Aew_Model_Bo_Usuario $usuario)
    {
        $email = $usuario->getEmail();
        if(!$usuario->getEmail())
            return false;
        
        $key = md5($email).md5($this->randString(6));
        $encodeEmail = base64_encode($email);
        
        if(!fopen(MEDIA_PATH.DS.'cache'.DS.$key, 'w'))
        {
            return false;

        }

        $this->setPageTitle("Processo de recuperação de senha");
        
        $this->view->urlRecuperar = $this->view->baseUrl().'/usuario/recuperar-senha/email/'.$encodeEmail.'/c/'.$key;
        $this->view->nomeUsuario = $usuario->getPrimeiroNome();
        
	$assunto = "[AEW] Solicitação para recuperar a minha senha";
        $mensagem = $this->view->render('usuario/esqueci-a-senha.email.php');
        $resultado = $this->enviarEmail($email, $mensagem, $assunto, $usuario->getNome());
        
	return $resultado;
    }
    
   
   
    /**
     * Ação para recuperar a senha
     * @return Zend_View 
     * 
    */
    public function recuperarSenhaAction()
    {
        $this->setPageTitle('Confirmação de recuperação de Senha');

        $email = $this->getParam('email', false);
        $confirmacao = $this->getParam('c', false);

        $email = utf8_decode(base64_decode($email));
        
        $usuarioBo = new Aew_Model_Bo_Usuario();
        $usuarioBo->setEmail($email);
        $usuarioBo = $usuarioBo->select(1);
            
        if(!$usuarioBo instanceof Aew_Model_Bo_Usuario)
        {
            $this->view->mensagem = "Não exite usuario cadastrado com esse email";
            $this->view->erro = true;
            return;
        }

        if(false == $email && false == $confirmacao)
        {
            $this->view->mensagem = "<b>E-mail</b> ou <b>chave de confirmação</b> da senha não válidos. Se deseja realizar uma nova solicitação <a href='/usuario/esqueci-a-senha'>clique aqui</a>.";
            $this->view->erro = true;
            return;
        }
        
        if(!is_file(MEDIA_PATH.DS.'cache'.DS.$confirmacao))
        {
            $this->view->mensagem = "Link para recuperação de senha expirado. Se deseja realizar uma nova solicitação <a href='/usuario/esqueci-a-senha'>clique aqui</a>.";
            $this->view->erro = true;
            return;
        }    
        
        // apaga token para evitar ser utilizado novamente
        unlink(MEDIA_PATH.DS.'cache'.DS.$confirmacao);
        
        $senha = $usuarioBo->geraSenha();
        if(!$usuarioBo->generateNewPassword($senha))
        {
            $this->view->mensagem = "Não foi possível gerar uma nova senha. Se deseja realizar uma nova solicitação <a href='/usuario/esqueci-a-senha'>clique aqui</a>.";
            $this->view->erro = true;
        }
        else
        {
            $assunto = "[AEW]  - Geração de nova senha de acesso ao Ambiente Educacional Web";
            $this->view->senha = $senha;
            $this->view->usuario = $usuarioBo;
            $mensagem = $this->view->render('usuario/recuperar-senha.email.php');

            if(!$this->enviarEmail($email, $mensagem, $assunto, $usuarioBo->getNome()))
            {
                $this->view->mensagem = "Não foi possível gerar uma nova senha. Se deseja realizar uma nova solicitação <a href='/usuario/esqueci-a-senha'>clique aqui</a>.";
                $this->view->erro = true;
            }
        }
    }
   
    /**
     * Ação caso um usuário tente se logar no sistema
     * @return Zend_View
     */
	public function loginAction()
	{
            $this->_redirect('');
	}
        /**
         * Action de acesso negado
         * @return Zend_View
         */
        public function acessoNegadoAction()
        {
            $this->flashError('Você não tem permissão para acessar esse recurso do sistema');
        }

    /**
     * Action de login com sucesso
     * @return Zend_View
     */
    public function loginSucessoAction()
    {
        Zend_Session::regenerateId();
        $usuario = $this->getLoggedUserObject();
        if(!$usuario):
            $this->flashError('Nome de usuário ou senha incorretos. Tente novamente');
            //$this->_redirect('');
        endif;
        
        $usuario->online();
        $this->_redirect('espaco-aberto');
    }

    /**
     * string no formato json dos usuarios
     * @return string 
     */
    public function jsonAction()
    {
    	$usuarioBo = new Aew_Model_Bo_Usuario();
        $usuarioLogado = $this->getLoggedUserObject();
        if(!$usuarioLogado || !$usuarioLogado->isSuperAdmin())
            $this->_redirect ('home');
        echo $this->jsonObjects($usuarioBo->select(10));
        die();
    }

    /**
    * Ação caso um usuário peça para sair do sistema
    * @return Zend_View
    */
    public function sairAction()
    {
        $usuario = $this->getLoggedUserObject();
        
        if($usuario)
        {
            $usuario->offline();
            if (!Zend_Auth::getInstance()->hasIdentity()) 
            {
                $this->_redirect($this->getRequest()->getHeader('REFERER'));
            }
        }
        
	Zend_Auth::getInstance()->clearIdentity();
	$this->_redirect($this->getRequest()->getHeader('REFERER'));
    }

    /**
     * Ação para cadastrar um usuário
     * @return Zend_View
     */
    public function cadastroAction()
    {
        $this->setPageTitle('Cadastro de usuários');

        if ($this->getLoggedUserObject()) {
            $this->_redirect('usuario/login-sucesso');
        }
	$alunoForm = new Aew_Form_CadastroAluno();
	$servidorForm = new Aew_Form_CadastroServidor();

        if($this->isPost())
        {
            $post = $this->getPost();
            if('servidor' == $post['tipo'])
            {
                $this->_cadastrarServidor($servidorForm, $post);
            } 
            elseif ('aluno' == $post['tipo']) 
            {
                $this->_cadastrarAluno($alunoForm, $post);
            }
        }
        $this->view->formAluno = $alunoForm;
        $this->view->formServidor = $servidorForm;
        
        $this->view->headLink()->appendStylesheet('/assets/js/plugins/datepicker/datedropper.css');
        $this->view->headScript()->appendFile('/assets/js/plugins/datepicker/datedropper.min.js')
                                 ->appendFile('/assets/js/espaco-aberto/adicionar.js');
    }

    /**
    * Ação onde o usuário preenche seus dados
    * @return Zend_View
    */
    public function preencherDadosAction()
    {
	$this->setPageTitle('Cadastro do Aluno');
        $cadastroSession = new Zend_Session_Namespace('usuario_cadastro');
        $form = new Aew_Form_PreencherDados();
        if(false == (isset($cadastroSession->usuario) || isset($cadastroSession->usuarios))){
            $this->flashError('Informações inválidas, tente novamente.');
            $this->_redirect('/usuario/cadastro');
        }
        if(false == isset($cadastroSession->usuario)){
            $usuario = new Aew_Model_Bo_Usuario();
        } 
        else 
        {
            $usuario = $cadastroSession->usuario;
        }

        if($this->isPost())
        {
            if($form->isValid($this->getPost()))
            {
                $valores = $form->getValues();

                if(null != $valores['matriculas'])
                {
		     $usuarios = $cadastroSession->usuarios;
		     $usuario->exchangeArray($valores);
                }
                $usuario->exchangeArray($valores);
                $cadastroSession->password = $valores['password'];
                $cadastroSession->usuario = $usuario;
                $this->_redirect('usuario/finalizar-cadastro');
            }
        }

        $numElementos = count($form->getElements());

        // Usuario possui multiplas matriculas
        if(isset($cadastroSession->usuarios))
        {
            $usuarios = $cadastroSession->usuarios;
	    foreach($usuarios as $idUsuario => $usuarioServidor)
            {
	         $form->addMatricula($idUsuario, $usuarioServidor->matricula);
	    }
        } 
        else 
        {
            $form->removeElement('matriculas');
            $numElementos--;
        }
        
        if($numElementos <= 1){
            $this->_redirect('usuario/finalizar-cadastro');
        }
        $this->view->form = $form;
    }

    /**
     * Finaliza o cadastro do usuário
     * @return Zend_View
     */
    public function finalizarCadastroAction()
    {
        $cadastroSession   = new Zend_Session_Namespace('usuario_cadastro');
        if(!isset($cadastroSession->usuario)){
            $this->flashError('Informações inválidas, tente novamente.');
            $this->_redirect('/usuario/cadastro');
        }
        $usuario = $cadastroSession->usuario;
        $usuario->insert();
        $cadastroSession->unsetAll();
        $this->view->usuario = $usuario;
    }

    /**
     * Faz o primeiro processo de cadastro de um servidor
     * @param $form
     * @param $post
     * @return void
     */
    protected function _cadastrarServidor($form, $post)
    {
        $usuarioBo = new Aew_Model_Bo_Usuario();
        $cadastroSession = new Zend_Session_Namespace('usuario_cadastro');
        $cadastroSession->unsetAll();

        if($form->isValid($post))
        {
            $usuarioJaExiste = $usuarioBo->getUsuarioServidor($form->getValue('cpf'),
                                                              $form->getValue('sexo'));
            // Existe no AEW?
	    if(false != $usuarioJaExiste) 
            {
                $this->flashError('Servidor já cadastrado no ambiente');
                return;
	    }

            try 
            {
    	        // Obter Servidor
    	        $servidores = $usuarioBo->getUsuarioServidorSec($form->getValue('cpf'),
        		                                                $form->getValue('sexo'),
        		                                                $form->getValue('nascimentoServidor'),
        		                                                $form->getValue('email'));
	    } 
            catch (SoapFault $e) 
            {
	        $this->flashError($e);
	        return;
	    }

            // Existe 1 ou mais matriculas?
            if(false == $servidores) 
            {
                $this->flashError('Servidor não encontrado no sistema');
                return;
            }

            // Obter usuario para cada matricula
            foreach($servidores as $servidor)
            {
                $servidor['categoria'] = 's';
                //$servidor['username'] = $servidor['email'];
                $servidor['username'] = $form->getValue('email');
            }

            // Cadastrado no RASEA?
            if (1 < count($servidores)) 
            {
                // Não, usuário possui mais de uma matricula
		$cadastroSession->usuarios = $servidores;
            } 
            else 
            {
                // Não, usuário possui apenas uma matricula
		$cadastroSession->usuario = $servidor;
            }

            $this->_redirect('/usuario/preencher-dados');
        }
    }

    /**
     * Faz o primeiro processo de cadastro de um aluno
     * @param $form
     * @param $post
     * @return void
     */
    protected function _cadastrarAluno($form, $post)
    {
        $usuarioBo = new Aew_Model_Bo_Usuario();
        $cadastroSession = new Zend_Session_Namespace('usuario_cadastro');
        $cadastroSession->unsetAll();
        if($form->isValid($post))
        {
            $result = $usuarioBo->getUsuarioAluno($form->getValue('matricula'));
            // Existe no AEW?
	    if(false != $result) 
            {
                $this->flashError('Aluno já cadastrado no ambiente');
                return;
	    }
            try 
            {
    	        // Obter aluno por matricula
    	        $aluno = $usuarioBo->getUsuarioAlunoSec($form->getValue('matricula'),
    	                                                $form->getValue('nascimentoAluno'),
    	                                                $form->getValue('sexo'),
    	                                                $form->getValue('email'));
            } 
            catch (SoapFault $e) 
            {
	        $this->flashError('Erro de comunicação com a base da dados da SEC, tente novamente.');
	        return;
	    }
	    // Existe matricula?
            if(false == $aluno) 
            {
                $this->flashError('Aluno não encontrado no sistema');
                return;
            }
            $aluno['categoria'] = 'a';
            $aluno['username'] = $aluno['email'];
            $cadastroSession->usuario = $aluno;
            $this->_redirect('/usuario/preencher-dados');
        }
    }
    
    /**
     * @ajax
     * @return Zend_View
     */
    public function componentesAction()
    {
        $this->disableLayout();
        $this->disableRender();
        $idnivel = $this->getParam('idnivel',null);
        if(!$idnivel)
        {
           return;
        }
        $nivel = new Aew_Model_Bo_NivelEnsino();
        $nivel->setId($idnivel);
        $componentes = $nivel->selectComponentesCurriculares();        
        $this->view->objetos = $nivel->getAllForSelect('idcomponentecurricular', 'nomecomponentecurricular',false,null,null,$componentes);
        echo $this->renderScript('_componentes/select-opcoes.php');
        
    }
    
    /**
     * exibe pagina de erros para links externos
     * @return Zend_View
     */
    public function modoOfflineAction()
    {
        if($this->isAjax())
            $this->disableLayout ();
        $text  = $this->getParam('text');
        $error = $this->getParam('error',true);
        if(!$error)
        $this->flashMessage($text);
        else 
        {
            $this->flashError($text);
        }
    }
}
