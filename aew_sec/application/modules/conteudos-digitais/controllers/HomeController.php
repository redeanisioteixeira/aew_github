<?php

class ConteudosDigitais_HomeController extends Sec_Controller_Action
{
    public function init()
    {
		/* @var $acl Sec_Controller_Action_Helper_Acl */
		$acl = $this->getHelper('Acl');
		$visitanteAction = array('home', 'teste', 'atribui', 'diretorio');
		$acl->allow(Aew_Model_Bo_UsuarioTipo::VISITANTE, $visitanteAction);
		$this->view->comunidade = $this->getParam('comunidade', false);
		$this->initViewDisp();
    }
    /**
     * Redireciona a conteúdos listar
     * @return  
     */
    public function homeAction()
    {
        $this->_redirect('conteudos-digitais/conteudos/listar');
    }
    
    public function testeAction()
    {
	$conte = new Aew_Model_Bo_ConteudoDigitalTag();
    }
}
