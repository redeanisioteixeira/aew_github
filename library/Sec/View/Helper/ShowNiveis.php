<?php

class Sec_View_Helper_ShowNiveis
{
	protected $view;
	public function setView($view)
	{
		$this->view = $view;
	}

    /**
     * 
     * @param array $niveis array de objetos Aew_Model_Bo_NivelEnsino
     * @return string
     */
    public function ShowNiveis($niveis)
    {
        $result = "";
        $arrNivel = array();

        if($niveis != null && count($niveis) > 0):
            //$result = "<ul class='niveis-ensino list-inline list-unstyled'>";
            foreach($niveis as $nivel):

                if(array_key_exists(trim($nivel->getNome()), $arrNivel) === false && trim($nivel->getNome()) != ""):
                    $arrNivel[trim($nivel->getNome())] = trim($nivel->getNome());
                    $result = (!$result ? "<ul class='niveis-ensino list-inline list-unstyled'>" : $result);
                    $result .= "<li>".trim($nivel->getNome())."</li>";
                endif;

            endforeach;

            $result .= ($result ? "</ul>" : "");
        endif;

        return $result;
    }
}