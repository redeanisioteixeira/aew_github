<?php
/**
 * controller principal para modulo de administracao
 */
class Administracao_HomeController extends Sec_Controller_Action
{
    /**
     * configura permissoes de acesso
     */
    public function init()
    {
        /* @var $acl Sec_Controller_Action_Helper_Acl */
        $acl = $this->getHelper('Acl');
        $administradorAction = array('home');
        $acl->allow(Aew_Model_Bo_UsuarioTipo::ADMINISTRADOR, $administradorAction);
    }

    /**
     * pagina principal da administracao
     * @return Zend_View
     */
    public function homeAction()
    {
        $this->setPageTitle('Administração do Ambiente');
        $this->view->usuarioLogado = $this->getLoggedUserObject();
    }
}