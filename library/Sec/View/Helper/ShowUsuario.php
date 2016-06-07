<?php
class Sec_View_Helper_ShowUsuario
{
    protected $view;

    public function setView($view)
    {
        $this->view = $view;
    }

    public function showUsuario(Aew_Model_Bo_ItemPerfil $usuario, $link = true, $class = '')
    {		
	if($usuario->getFlativo() == false):
            $class .= 'desativado';
	endif;
        
        $href = $this->view->url(array('module' => 'espaco-aberto','controller' => 'perfil','action' => 'feed','usuario' => $usuario->getId()),null, true);
        if($link == true):
            $href = '<a href="'.$href.'" title="Visualizar perfil de '.$usuario->getNome().'" class="text-capitalize '.$class.'"> '.$this->view->escape(strtolower($usuario->getNome())).'</a>';
        endif;
        
	return $href;
    }
}