<?php
/**
 * controler responsavel pelas url's referentes aos formularios de denuncia
 */
class Administracao_DenunciaController extends Sec_Controller_Action
{
    protected $_linkExibicao = 'administracao/denuncia/exibir/id/';
    protected $_linkListagem = 'administracao/denuncias/listar';
    protected $_actionApagar = '/administracao/denuncia/apagar/id/';

    /**
     * 
     */
    public function init()
    {
        /* @var $acl Sec_Controller_Action_Helper_Acl */
        parent::init();
        $acl = $this->getHelper('Acl');
        $administradorAction = array('exibir', 'apagar', 'visualizada');
        $acl->allow(Aew_Model_Bo_UsuarioTipo::ADMINISTRADOR, $administradorAction);
    }
    /**
     * Exibe denuncia de conteúdo inapropriado
     * @return Zend_View
     */
    public function exibirAction()
    {
        $this->setPageTitle('Dados da Denúncia');
        $denunciaBo = new Aew_Model_Bo_Denuncia();
        $id = $this->getRequest()->getParam('id', false);
        $denunciaBo->setId($id);
        if(!$denunciaBo->selectAutoDados()){
            $this->flashError('Denúncia não encontrada.');
            $this->_redirect($this->_linkListagem);
        }
        $this->view->denuncia = $denunciaBo;
    }
    /**
     * Apaga denuncia
     * @return Zend_View
     */
    public function apagarAction()
    {
        $this->setPageTitle('Apagar Denúncia');
        $denuncia = new Aew_Model_Bo_Denuncia();
        $form = new Aew_Form_Apagar();
        $id = $this->getRequest()->getParam('id', false);
        $denuncia->setId($id);
        if (!$denuncia->selectAutoDados()){
            $this->flashError('Nenhuma registro passado.');
            $this->_redirect($this->_linkListagem);
        }
        $form->setAction($this->_actionApagar.$id);
        if($this->getRequest()->isPost())
        {
            if(false != $this->getRequest()->getPost('nao')){
                $this->_redirect($this->_linkExibicao.$id);
            }
            if($denuncia->delete()){
                $this->flashMessage('Registro apagado com sucesso.');
                $this->_redirect($this->_linkListagem);
            } else {
                $this->flashError('Houve um problema ao tentar apagar o registro.');
                $this->_redirect($this->_linkListagem);
            }
        }
        $this->view->form = $form;
        $this->view->denuncia = $denuncia;
    }
    /**
     * Marca denuncia como visualizada
     * @return Zend_View
     */
    public function visualizadaAction()
    {
        $denuncia = new Aew_Model_Bo_Denuncia();
        $id = $this->getRequest()->getParam('id', false);
        $denuncia->setId($id);
        if (!$denuncia->selectAutoDados())
        {
            $this->flashError('Nenhuma registro passado.');
            $this->_redirect($this->_linkListagem);
        }
        $denuncia->setFlvisualizada(true);
        $denuncia->update();
        $this->flashMessage('Denúncia marcada como visualizada.');
        $this->_redirect($this->_linkListagem);
    }
}