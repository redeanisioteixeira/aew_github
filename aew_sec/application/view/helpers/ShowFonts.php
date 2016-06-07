<?php

class Aew_View_Helper_ShowFonts
{
    protected $view;

    public function setView($view)
    {
        $this->view = $view;
    }

    public function ShowFonts()
    {
        $div  = ""; 	
        $div .= '<div class="pull-right">';
        $div .= '   <a class="inc-font"><b class="font-size-100"><i class="fa fa-font">+</i></b></a>';
        $div .= '   <a class="dec-font"><b class="font-size-80"><i class="fa fa-font">-</i></b></a>';
        $div .= '</div>';

        $div  = ""; 
        return $div;
    }
}
?>
