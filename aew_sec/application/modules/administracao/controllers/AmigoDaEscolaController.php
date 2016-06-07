<?php

/**
 * controller que gerencia url's referentes a adição de colaboradores no AEW
 */
class Administracao_AmigoDaEscolaController extends Sec_Controller_Action
{
    public function init()
    {
        /* @var $acl Sec_Controller_Action_Helper_Acl */
        parent::init();
        $acl = $this->getHelper('Acl');
        $administradorAction = array('pendentes', 'aprovar', 'reprovar', 'adicionar', 'editar', 'salvar', 'exibir');
        $acl->allow(Aew_Model_Bo_UsuarioTipo::COORDENADOR, $administradorAction);
        $this->setLinkListagem('administracao/amigo-da-escola/pendentes');
        $this->setLinkExibicao('administracao/amigo-da-escola/exibir/id/');
        $this->setActionApagar('/administracao/amigo-da-escola/reprovar/id/');
        $this->setActionSalvar('/administracao/amigo-da-escola/salvar/id/');
    }
    /**
     * Carrega JS e CSS para o método HeadScript
     * @return Zend_View_Helper_HeadScript 
     */
    function initScripts()
    {
        $this->view->headScript()->appendFile('/assets/js/functions-load-ajax.js','text/javascript');
    }
    /**
     * Usuários pendentes por aprovar
     * @return Zend_View
     */
    public function pendentesAction()
    {
        $this->setPageTitle('Amigos da Escola pendentes');
    	$pagina = $this->getParam('pagina', 1);
    	$limite = 10;
    	$pendente = new Aew_Model_Bo_UsuarioAmigo();
        $pendente->getUsuarioCriado()->setFlativo('FALSE');
    	$objetosPagination = $pendente->getAsPagination($pendente->select($limite,$pagina),$pagina,10);
	$this->view->pendentes = $objetosPagination;
    }
    /**
     * Exibe usuário
     * @return Zend_View
     */
    public function exibirAction()
    {
        $this->setPageTitle('Exibir Amigo da Escola');
    	$id = $this->getParam('id', false);
        $amigo = new Aew_Model_Bo_UsuarioAmigo();
        $amigo->setId($id);
        if(!$amigo->selectAutoDados())
        {
            $this->flashError('ID do Registro não encontrado.');
            $this->_redirect($this->getLinkListagem());
        }
        $this->view->amigo = $amigo;
    } 
    /**
     * Aprova usuário como amigo da escola
     * @return Zend_View flash message ou redireciona
     */
    public function aprovarAction()
    {
        $usuario = $this->getLoggedUserObject();
        $id = $this->getParam('id', false);
        $amigoDaEscola = new Aew_Model_Bo_UsuarioAmigo();
        $amigoDaEscola->setId($id);
        $amigoDaEscola->setFlativo("FALSE");
        if (!$amigoDaEscola->selectAutoDados())
        {
            $this->flashError('Nenhum registro passado.');
            $this->_redirect($this->getLinkListagem());
        }
        $amigoDaEscola->setFlativo(TRUE);
        if($amigoDaEscola->update())
        $this->flashMessage('Usuário aprovado com sucesso.', $this->getLinkListagem());
    }
    /**
     * Reprova usuário como amigo da escola
     * @return flash message ou redireciona
     */
    public function reprovarAction()
    {
        $usuario = $this->getLoggedUserObject();;
        $this->setPageTitle('Reprovar Amigo da Escola');
        $form = new Aew_Form_Apagar();
        $id = $this->getParam('id', false);
        $amigoDaEscola = new Aew_Model_Bo_UsuarioAmigo();
        $amigoDaEscola->setId($id);
        $amigoDaEscola->setFlativo("FALSE");
        if (!$amigoDaEscola->selectAutoDados())
        {
            $this->flashError('Nenhuma registro passado.');
            $this->_redirect($this->getLinkListagem());
        }
        $form->setAction($this->getActionApagar($id));
        if($this->isPost())
        {
            if(false != $this->getPost('nao'))
            {
                $this->_redirect($this->getLinkExibicao($id));
            }
            try 
            {
                $result = $amigoDaEscola->delete();
            } 
            catch (Exception $ex) 
            {
                $this->flashError('Houve um problema ao tentar apagar o registro.');
                $this->_redirect($this->getLinkListagem());
            }

            if(true == $result)
            {
                $this->flashMessage('Registro reprovado com sucesso.');
                $this->_redirect($this->getLinkListagem());
            } 
            else 
            {
                $this->flashError('Houve um problema ao tentar apagar o registro.');
                $this->_redirect($this->getLinkListagem());
            }
        }
        $this->view->form = $form;
        $this->view->objeto = $amigoDaEscola;
    }
    
    /**
     * Adiciona um novo usuário amigo da escola
     * @return Zend_View
     */
    public function adicionarAction()
    {
        $this->setPageTitle('Adicionar novo Amigo da Escola');
    	$form = new Administracao_Form_AmigoDaEscola();
    	$form->setAction($this->getActionSalvar());
	if($this->isPost())
        {
	    $form->isValid($this->getPost());
	}

	$this->view->adicionar = $form;
    }
    /**
     * Salva mudanças
     * @return Zend_View
     */
    public function salvarAction()
    {
        $usuario = $this->getLoggedUserObject();;
        $form = new Administracao_Form_AmigoDaEscola();
        if($this->isPost())
        {
            $amigoDaEscola = new Aew_Model_Bo_UsuarioAmigo();
            if($form->isValid($this->getPost()))
	    {
                if($form->getValue('idusuarioamigo') > 0)
                {
                    $txt = 'editado';
                } 
                else 
                {
                    $txt = 'inserido';
                }
                $amigoDaEscola->exchangeArray($form->getValues());
                $amigoDaEscola->getUsuarioCriado()->getUsuarioTipo()->setId(6);//TIPO AMIGO-DA-ESCOLA
                $amigoDaEscola->getUsuarioIndicou()->setId($usuario->getId());
                if($amigoDaEscola->save())
                {
                    $this->flashMessage('Registro '.$txt.' com sucesso.');
                }
                else
                {
                    $this->flashError('erro ao inserir registro');
                }
                $this->_redirect($this->getLinkListagem());
            }
            else 
            {
               if($form->getValue('idusuarioamigo') > 0)
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
}