<?php

class Sec_View_Helper_ShowComponentesRelatorio
{
    protected $view;

    public function setView($view)
    {
        $this->view = $view;
    }

    public function ShowComponentesRelatorio($componentes)
    {
        $result = "";
        if($componentes != null && count($componentes) > 0){
	        foreach($componentes as $componente){
	            $result .= $componente['nome']."(".$componente['nivelEnsino']['nome']."), ";
	        }
        }

        $result = substr($result, 0, -2);

        return $result;
    }
}
