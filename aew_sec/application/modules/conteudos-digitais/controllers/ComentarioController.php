<?php
require_once '../application/cache/Cache_Class.php';
class ConteudosDigitais_ComentarioController extends Sec_Controller_Action
{
    /**
     * configura as permicoes de acesso as actions
     */
    public function init(){
        $acl = $this->getHelper('Acl');

        $visitanteAction = array('adicionar');
        $acl->allow(Aew_Model_Bo_UsuarioTipo::VISITANTE, $visitanteAction);
        
        $editorAction = array('apagar');
        $acl->allow(Aew_Model_Bo_UsuarioTipo::ADMINISTRADOR, $editorAction);
    }
    /**
     * Adiciona Comentário via ajax 
     * @return Zend_View 
     */
    public function adicionarAction()
    {
        if(!$this->isAjax()):
            $this->_redirect('');
        endif;
        $this->disableLayout();
        $comentarioForm = new ConteudosDigitais_Form_Comentario();
        $usuario = $this->getLoggedUserObject();
        if($this->getRequest()->isPost()):
            if($comentarioForm->isValid($this->getRequest()->getPost())):
                $conteudo = new Aew_Model_Bo_ConteudoDigital();
                $conteudo->exchangeArray($comentarioForm->getValues());
                if($usuario->insertComentarioConteudo($comentarioForm->getValue('comentario'), $conteudo)):
                    $this->flashMessage('Comentario inserido com sucesso!');
                else:
                    $this->flashError('Houve um problem ao tentar inserir seu comentário.');
                endif;
                $this->view->comentarios = $this->view->action('listar-comentarios','comentarios','conteudos-digitais', array('id' => $conteudo->getId()));
            endif;
        endif;
    }
    /**
     * Apaga Comentário via ajax
     * @return Zend_View
     */
    public function apagarAction()
    {
        if(!$this->isAjax()):
            $this->_redirect($this->getRequest()->getHeader('REFERER'));
        endif;
        $this->disableLayout();
        $comentarioBo = new Aew_Model_Bo_ConteudoDigitalComentario();//armazenando e extraindo do cache
        $id = $this->getRequest()->getParam('id', false);
        $comentarioBo->setId($id);
        if(!$comentarioBo->selectAutodados()){
            $this->flashError('Não foi possivel apagar mensagem');
        }
        else
        {
            $conteudoDigital = new Aew_Model_Bo_ConteudoDigital();
            $conteudoDigital->setId($comentarioBo->getIdconteudodigital());
            $id = $comentarioBo->getIdconteudodigital();
            if(!$comentarioBo->delete())
            {
                $this->flashError('Não foi possivel apagar mensagem!');
            } 
            else 
            {
                $this->flashMessage('Comentário apagado com sucesso');
            }
        }
        $this->view->comentarios = $this->view->action('listar-comentarios','comentarios','conteudos-digitais', array('id' => $id));
    }
}
