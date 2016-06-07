<?php
class Sec_View_Helper_GetOrdenarPor
{
    protected $view;

    public function setView($view)
    {
        $this->view = $view;
    }

    public function getOrdenarPor()
    {
        $result = array("avaliacao" => "Avaliação", "popularidade" => "Popularidade", "titulo" => "Título", "data" => "Data de Publicação");
        return $result;
    }
}
