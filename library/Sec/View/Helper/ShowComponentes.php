<?php

class Sec_View_Helper_ShowComponentes
{
    protected $view;

    public function setView($view)
    {
        $this->view = $view;
    }

    public function ShowComponentes($componentes)
    {
        
        $result = "";
        $arrComp = array();

        if($componentes != null && count($componentes) > 0):
            $result = "<ul class='componentes list-inline list-unstyled'>";
            
            foreach($componentes as $componente):

                if(array_key_exists(trim($componente->getNome()), $arrComp) === false):
                    $arrComp[$componente->getNome()] = trim($componente->getNome());
                    $result .= "<li>".trim($componente->getNome())."</li>";
                endif;

            endforeach;
        endif;

        $result .= "</ul>";
        
        return $result;
    }
}
