<?php
class Sec_View_Helper_CheckURI
{
	protected $view;

	public function setView($view)
	{
		$this->view = $view;
	}

	private function CheckURI($site)
	{
		$agent= 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';

		//$url = base64_encode(urlencode($site));
		//$site = 'http://homologa.ambiente.educacao.ba.gov.br/home/url/id/'.$url;
		//$site

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_VERBOSE, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, $agent);
		curl_setopt($ch, CURLOPT_URL,$site);

		$result = curl_exec($ch);
		return $result;
	}
}
?>
