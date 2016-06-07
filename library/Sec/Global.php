<?php
/**
 * Classe Global
 *
 * @author diego
 *
 */
class Sec_Global
{
    /**
     * Log uma mensagem no firebug
     * @param $message
     * @param $label
     * @return void
     */
	public static function fb($message, $label=null)
	{
	    if ($label!=null) {
	        $message = array($label,$message);
	    }

	    if(Zend_Registry::isRegistered('logger')){
	        Zend_Registry::get('logger')->debug($message);
	    }
	    echo $message;
	    echo "<br/>";
	}

	/**
	 * Remove espaços e caracterres especiais da string
	 * @param String $string
	 */
	public static function sanitizeSlug($str, $replace=array(), $delimiter='-')
	{
		if( !empty($replace) ) {
			$str = str_replace((array)$replace, ' ', $str);
		}

		$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
		$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
		$clean = strtolower(trim($clean, '-'));
		$clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

		return $clean;
	}

	/**
	 * Retorna o email do sistema
	 */
	public static function getSystemEmail()
	{
	    // TODO Colocar no config.ini
	    return 'aew@educacao.ba.gov.br';
	}

	/**
	 * Remove espaços desnecessários
	 */
	public static function limpaString($str) {
		return preg_replace('/ \s+/','',$str);
	}
}
