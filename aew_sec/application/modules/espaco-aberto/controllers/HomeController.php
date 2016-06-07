<?php
class EspacoAberto_HomeController extends Sec_Controller_Action
{
    public function init()
    {
        parent::init();
	$acl = $this->getHelper('Acl');
        $colaboradorActions = array('adicionar-amigo-da-escola', 'salvar-amigo-da-escola', 'convidar', 'enviar-convite','home');
	$acl->allow(Aew_Model_Bo_UsuarioTipo::AMIGO_DA_ESCOLA, $colaboradorActions);
	$acl->allow(Aew_Model_Bo_UsuarioTipo::EDITOR, $colaboradorActions);
	$this->setLinkListagem('espaco-aberto/home/home');
	$this->setActionConvidar('/espaco-aberto/home/enviar-convite');
	$this->view->comunidade = $this->getParam('comunidade', false);
    }
    /**
     * Redireciona ao mural de noticias (feeds)
     */
    public function homeAction()
    {	
    	$this->_redirect('espaco-aberto/perfil/feed');
    }
    /**
     * Adiciona perfil de amigo da escola
     * @return Zend_View
     */
    public function adicionarAmigoDaEscolaAction()
    {

	$this->setPageTitle('Convidar para a Rede');

    	$form = new Administracao_Form_AmigoDaEscola();
    	$form->setAction($this->getActionSalvar());
	if($this->isPost())
        {
	    $form->isValid($this->getPost());
	}
	$this->view->adicionar = $form;
    }
    /**
     * Renderiza o formulario para enviar convite para ser parte da rede social 
     * @return Zend_View
     */
    public function convidarAction()
    {
        $this->setPageTitle('Convidar para a Rede');
    	$form = new EspacoAberto_Form_Convidar();
    	$form->setAction($this->getActionConvidar());

	if($this->isPost())
        {
	    $form->isValid($this->getPost());
	}
	$this->view->convidar = $form;
    }
    /**
     * Envia convite por email
     *  @return Zend_View
     */
    public function enviarConviteAction()
    {
        $usuario = $this->getLoggedUserObject();
        $form = new EspacoAberto_Form_Convidar();
        if($this->isPost())
        {
            $bo = new Aew_Model_Bo_Usuario();
            if($form->isValid($this->getPost()))
            {
            	$valores = $form->getValues();
		if (trim($valores['emails'])=="")
                {
                    $this->flashMessage('Entre com os emails');
                    $this->_redirect($this->getLinkListagem());
		}
		$envio = $bo->enviarEmailDeConvite($usuario, trim($valores['emails']));
                if($envio == true)
                {
                    $this->flashMessage('Usuário(s) convidado(s) com sucesso');
                }
                else 
                {
                    $this->flashMessage('Houve um problema no envio, limite a lista de convite para até 10 emails');
                    $this->_redirect($this->getLinkListagem());
                }
             } 
             else 
             {
                $this->_redirect($this->getLinkListagem());
             }
         } 
         else 
         {
            $this->flashError('Nenhuma informação para salvar.');
         }
	 $this->_redirect($this->getLinkListagem());
     }
}