<?php
/**
 * controler responsavel pelas url's referentes a edicao e atualizacao de denuncias
 */
class Administracao_DenunciasController extends Sec_Controller_Action
{
    public function init(){
        /* @var $acl Sec_Controller_Action_Helper_Acl */
        parent::init();
        $acl = $this->getHelper('Acl');

        $administradorAction = array('listar', 'historico');
        $acl->allow(Aew_Model_Bo_UsuarioTipo::ADMINISTRADOR, $administradorAction);
    }
    /**
     * Lista as denuncias
     * @return Zend_View
     */
    public function listarAction()
    {
        $usuarioLogado = $this->getLoggedUserObject();
        $this->setPageTitle('Denúncias');
        $denunciaBo = new Aew_Model_Bo_Denuncia();

    	$pagina = $this->getRequest()->getParam('pagina', 1);
    	$limite = 10;
        $denuncias = $usuarioLogado->selectDenuncias();
        if(count($denuncias)==0)
        {
            $denuncias = $denunciaBo->select ( $limite,$pagina);
        }
        $denunciasPaged = $denunciaBo->getAsPagination($denuncias);
        $this->view->denuncias = $denunciasPaged;
    }
    /**
     * Historico de denuncias
     * @return Zend_View
     */
    public function historicoAction()
    {
        $this->setPageTitle('Histórico de Denúncias');
        $denunciaBo = new Aew_Model_Bo_Denuncia();
    	$pagina = $this->getRequest()->getParam('pagina', 1);
    	$limite = 10;
        $denuncias = $denunciaBo->select($limite, $pagina);
        $denunciasPaged = $denunciaBo->getAsPagination($denuncias);
        $this->view->denuncias = $denunciasPaged;
    }
}