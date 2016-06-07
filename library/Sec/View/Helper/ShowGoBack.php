<?php

class Sec_View_Helper_ShowGoBack
{
    protected $view;

    public function setView($view)
    {
            $this->view = $view;
    }

    public function ShowGoBack($home = false, $url="")
    {
        if ($url == ""){
            $href = $this->view->url(array('controller' => 'home','action' => 'home','module' => 'aew' ));
        }

        $href = ($home == false ? "javascript:history.go(-1)" : $href);
        
        $result = '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><a class="link-cinza-claro font-size-150 pull-right" href="'.$href.'"><i class="fa fa-arrow-circle-left"></i> <b>voltar</b></a></div>';
        
        return $result;
    }
}
?>
