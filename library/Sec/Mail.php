<?php
/**
 * Sec_Mail
 *
 * @author diego
 *
 */
class Sec_Mail extends Zend_Mail
{
	/**
	* Public constructor
	*
	* @param string $charset
	*/
	public function __construct($charset = 'UTF-8')
	{
		parent::__construct($charset);
	}

	/**
	* @overrides
	*/
	public function send($transport = NULL)
	{
		// configure and create the SMTP connection
		$config = array('port' => '25','auth' => '','username' => '','password' => '');
		$transport = new Zend_Mail_Transport_Smtp('envio.ba.gov.br', $config);
		return parent::send($transport);
	}

	/**
         * 
         * @global type $server
         * @param string $email
         * @return string
         */
	function validarMail($email)
	{  
		global $server;

		$server  = $_SERVER["HTTP_HOST"];
		$timeout = 10;

		$resultado = array();
		$resultado[0] = true;
		$resultado['code'] = "200";

		if (!eregi("^[a-z0-9]+([_\\.-][a-z0-9]+)*@([a-z0-9]+([\.-][a-z0-9]+))*$", $email)):
			$resultadoado[0] = false;
			$resultado['code'] = "702";
			return $resultado;  
		endif;

		list($username, $dominio) = split("@",$email);

		if(!checkdnsrr($dominio)):
			$resultadoado[0] = false;
			$resultado['code'] = "600";
			return $resultado;  
		endif;

		$conecta_dominio =  (getmxrr($dominio, $MXHost) ? $MXHost[0] : $dominio);

		$conectar = fsockopen($conecta_dominio, 25, $errno, $errstr, $timeout);
		if ($errno):
			$resultadoado[0] = false;
			$resultado['code'] = "000";
			return $resultado;
		endif;

		if ($conectar):
			$ver = fgets($conectar);
			if (ereg("^220", $ver)):
				fputs($conectar, "HELO $server\r\n");

				$ver = fgets($conectar);
				fputs($conectar, "MAIL FROM: <{$email}>\r\n");

				$From = fgets($conectar, 512);
				fputs ($conectar, "RCPT TO: <{$email}>\r\n");

				$To = fgets($conectar);
				fputs ($conectar, "QUIT\r\n");  

				$Help = fgets($conectar);
				fputs ($conectar, "HELP\r\n");  

				fclose($conectar);

				if (!ereg ("^250", $From) || !ereg("^250", $To) || (!ereg("^250", $Help) && !ereg("^221", $Help))):
					$resultado[0] = false;  
					$resultado['code'] = "700";  
				endif;
			else:
				$resultado[0] = false;  
				$resultado['code'] = "Død";  
			endif;
		else:
			$resultado[0] = false;  
			$resultado['code'] = "701";
		endif;

		return $resultado;
	}

	function getmxrr($hostname, &$mxhosts) 
	{ 
		$mxhosts = array();
		exec('nslookup -type=mx '.$hostname, $result_arr); 
		foreach($result_arr as $line)  
		{ 
		  if (preg_match("/.*mail exchanger = (.*)/", $line, $matches))  
		      $mxhosts[] = $matches[1]; 
		} 
		return( count($mxhosts) > 0 ); 
	}
}
