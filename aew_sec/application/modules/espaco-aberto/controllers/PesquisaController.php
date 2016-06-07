<?php
require_once '../application/cache/Cache_Class.php';
class EspacoAberto_PesquisaController extends Sec_Controller_Action_EspacoAberto
{
    public function init()
    {
        parent::init();
	$acl = $this->getHelper('Acl');
	$amigoDaEscolaAction = array('home');
	$acl->allow(Aew_Model_Bo_UsuarioTipo::AMIGO_DA_ESCOLA, $amigoDaEscolaAction);
    }
    /**
     * Busca por ajax 
     * @return Zend_View
     */
    public function homeAction()
    {
        $limite = 80;
        if($this->isAjax())
        {
            $limite = 20;
            $this->disableLayout();
        }
	$cache = new Cache_Class();
	$filtro = $this->getParam('filtro');
        $resultado = array();
	if(trim($filtro) != '')
        {
            $bo = $cache->obtemObjeto(new Aew_Model_Bo_FeedDetalhe());
            $resultado = $bo->obtemResultado($filtro,$limite);
        }
        
        $this->view->resultados = $resultado;
        $this->view->filtro= $filtro;
    }
    /**
     * Sublinha ou destaca a palavra procurada
     * @param type $string
     * @return type
     */
    function marcarBusca($string)
    {
        $retorno = $string;
        if(strlen($string)>2)
        {
            $retorno = '<strong>'.$retorno.'</strong>';
        }
        return($retorno);
    }
}