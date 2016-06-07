<?php

class Sec_View_Helper_ShowShareThis
{
    protected $view;

    public function setView($view)
    {
        $this->view = $view;
    }

    public function ShowShareThis($compartilhar = true, $impressao = false)
    {
        if($this->view->getModule() == 'administracao'):
            return "";
        endif;

        //$key  = "ra-4f74cf0637c08cc9";
		$key  = "ra-52179aa34288e831";

        $this->view->placeholder('sharethis')->captureStart();

        $url   = $this->view->baseUrl();
        $color = "#005a25";

        /*bloco addthis*/
        $div  = '<div class="share-bar">';
        $div .= '         <div class="addthis_toolbox addthis_default_style addthis_32x32_style">';
        $div .= '            <a class="addthis_button_email"><i class="fa fa-envelope-square fa-3x"></i></a>';

        if($compartilhar == true):
            $div .= '        <a class="addthis_button_facebook"><i class="fa fa-facebook-square fa-3x"></i></a>';
            $div .= '        <a class="addthis_button_twitter" title="Twitter"><i class="fa fa-twitter-square fa-3x"></i></a>';
            $div .= '        <a class="addthis_button_google_plusone_share"><i class="fa fa-google-plus-square fa-3x"></i></a>';
        endif;

        if($impressao == true):
            $div .= '        <a class="addthis_button_print"><i class="fa fa-print fa-3x"></i></a>';
        endif;

        $div .= '   </div>';
        $div .= '</div>';

        $template = 'var addthis_config = {ui_language: "pt"};var addthis_share  = {email_template: "My_Template"}';

        $this->view->inlineScript()
				->appendFile('//s7.addthis.com/js/300/addthis_widget.js#pubid='.$key)
				->appendScript($template,'text/javascript');

        $this->view->placeholder('sharethis')->captureEnd();

        
        return $div;
    }
}
