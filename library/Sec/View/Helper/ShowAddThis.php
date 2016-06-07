<?php

class Sec_View_Helper_ShowAddThis
{
	protected $view;

	public function setView($view)
	{
		$this->view = $view;
	}

	public function ShowAddThis($compartilhar = true, $impressao = false)
	{
		if($this->view->getModule() == 'administracao'):
			return "";
		endif;

		$key  = "";

		$this->view->placeholder('sharethis')->captureStart();

		// $this->view->headScript()->appendFile('//s7.addthis.com/js/300/addthis_widget.js#pubid='.$key);

		$url   = $this->view->baseUrl();
		$color = "#005a25";

		/*bloco addthis*/
		$div  = '<script>';
		$div .= '   var addthis_config = {ui_language: "pt"};';
		$div .= '   var addthis_share  = {email_template: "My_Template"}';
		$div .= '</script>';
		$div .= '<div class="share-bar">';
		$div .= '         <div class="addthis_toolbox addthis_default_style addthis_32x32_style">';
		$div .= '            <a class="addthis_button_email" href="#"><i class="fa fa-envelope-square fa-3x"></i></a>';

		if($compartilhar == true):
			$div .= '        <a class="addthis_button_facebook"><i class="fa fa-facebook-square fa-3x"></i></a>';
			$div .= '        <a class="addthis_button_twitter"><i class="fa fa-twitter-square fa-3x"></i></a>';
			$div .= '        <a class="addthis_button_google_plusone_share"><i class="fa fa-google-plus-square fa-3x"></i></a>';
		endif;

		if($impressao == true):
			$div .= '        <a class="addthis_button_print"><i class="fa fa-print fa-3x"></i></a>';
		endif;

		$div .= '   </div>';
		$div .= '</div>';
		//$div .= '<script async type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid='.$key.'"></script>';

		$this->view->placeholder('sharethis')->captureEnd();
		return $div;
	}
}
