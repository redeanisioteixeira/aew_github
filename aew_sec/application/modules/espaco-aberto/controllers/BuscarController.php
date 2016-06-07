<?php
class EspacoAberto_BuscarController extends Sec_Controller_Action_EspacoAberto
{
    public function init()
    {
        parent::init();
	$acl = $this->getHelper('Acl');
	$amigoDaEscolaAction = array('listar','colega','convidar', 'comunidade', 'participar');
	$acl->allow(Aew_Model_Bo_UsuarioTipo::AMIGO_DA_ESCOLA, $amigoDaEscolaAction);
    }
    /**
     * Método para listar resultado da busca
     * @return Zend_View
     */
    public function listarAction()
    {
        if($this->isAjax())
        {
            $this->disableLayout();
        }
	$filtro = $this->getParam('filtro', '');
        
        $bo = new Aew_Model_Bo_FeedDetalhe();
        $resultado = $bo->obtemResultado($filtro);
        
        $this->view->resultados = $resultado;
        $this->view->filtro= $filtro;
    }
    /**
     * Busca colegas
     * @return Zend_View
     */
    public function colegaAction()
    {
        $this->carregarPerfil();
        
	$searchFilter = '';		
	$this->setPageTitle('Espaço Aberto');
	$this->setPageSubTitle('Buscar colegas');

	$form = new EspacoAberto_Form_Buscar();
	$form->setAction('/espaco-aberto/buscar/colega');
	$form->getElement('filtro_buscar')->setAttrib('placeholder','buscar novos colegas');

	if($this->isPost()):
            if($form->isValid($this->getPost())):
		if($form->getValue('filtro_buscar') != ''):
                    $searchFilter = $form->getValue('filtro_buscar');
		endif;
            endif;
	endif;
	$pagina = $this->getParam('pagina', 0);
	$bo = new Aew_Model_Bo_Usuario();
        $bo->setNome($searchFilter);
	$objetos = $bo->select(6,$pagina);
    	$this->view->objetos = $objetos;
	$this->view->form_buscar = $form;
    }
    /**
     * Envia convite para ser colega
     * @return JSON
     */
    public function convidarAction()
    {
        $this->getHelper('layout')->disableLayout();
    	$mensagem = '';
	$usuario = $this->getLoggedUserObject();
	$convidado = $this->getParam('colega',null);
	if($convidado != null):
            $colega = new Aew_Model_Bo_UsuarioColega();
            if($usuario->isColega($colega)):
		$mensagem = '<strong style="text-transform: capitalize;">[nome]</strong> já foi convidado. Aguardando confirmação!';
            else:
                if($usuario->insertSolicitacaoColega($colega)):
                    $link1 = "<a href='". $this->view->url(array('module' => 'espaco-aberto', 'controller' => 'perfil','action' => 'exibir', 'usuario' => $usuario->getId()), null, true) ."'>";
                    $link2 = "<a href='". $this->view->url(array('module' => 'espaco-aberto', 'controller' => 'colega','action' => 'listar', 'usuario' => $this->getPerfilId()), null, true) ."'>";
                    $bo->avisarUsuario($usuario, $convidado, $link1, $link2);
                    $mensagem = 'Você convidou <strong style="text-transform: capitalize;">[nome]</strong> para ser parte de seu grupo de colegas.';
                else:
                    $mensagem = '<strong style="text-transform: capitalize;">[nome]</strong> já foi convidado. Aguardando confirmação!';
                endif;
            endif;
	endif;
	echo json_encode($mensagem);
	die();
    }
    /**
     * Procura comunidade 
     * @return Zend_View
     */
    public function comunidadeAction()
    {
	$this->carregarPerfil();
	$searchFilter = '';
	$this->setPageTitle('Espaço Aberto');
	$this->setPageSubTitle('Buscar comunidades');
	$form = new EspacoAberto_Form_Buscar();
	$form->setAction('/espaco-aberto/buscar/comunidade');
	$form->getElement('filtro_buscar')->setAttrib('placeholder','buscar novas comunidades');
	if($this->isPost()):
            if($form->isValid($this->getPost())):
            	if($form->getValue('filtro_buscar') != ''):
                    $searchFilter = $form->getValue('filtro_buscar');
		endif;
            endif;
	endif;
	$pagina = $this->getParam('pagina', 1);
	$comunidade = new Aew_Model_Bo_Comunidade();
        $comunidade->setNome($searchFilter);
	$this->view->comunidades = $comunidade->select(8,$pagina);
	$this->view->form_buscar = $form;
    }
    /**
     * Método para participar da comunidade
     * @return JSON
     */
    public function participarAction()
    {
        if(!$this->isAjax())
            $this->_redirect ('');
        $mensagem = '';
        $usuario = $this->getLoggedUserObject();
        $idComunidade = $this->getParam('idcomunidade',null);
        $comunidade = new Aew_Model_Bo_Comunidade();
        $comunidade->setId($idComunidade);
        if($comunidade->selectAutoDados())
     	{
            if ($comunidade->isBloqueado($usuario)):
                $mensagem = '<span class="text-info" comunidade="<?php echo $comunidade->getId() ?>" nome="<?php echo $comunidade->getNome() ?>">Você ja faz parte da comunidade</span>';
            else:
            if ($comunidade->getFlmoderausuario())
            {
                if($comunidade->insertRequisicaoMembro($usuario))
                $mensagem = '<span class="text-info" comunidade="<?php echo $comunidade->getId() ?>" nome="<?php echo $comunidade->getNome() ?>">Requisição feita com sucesso. Aguarde aprovação</span>';
            }
            else if($comunidade->insertMembro($usuario)){
                $mensagem = 'Você entrou na comunidade <strong style="text-transform: capitalize;">'.$comunidade->getNome().'</strong>.';
            }
            endif;
        }
        
        echo json_encode($mensagem);
        die();
    }
}