<?php

/**
 * Goo.gl API
 *
 * Classe para encurtamento de URL utilizando a API do serviço goo.gl
 *
 * @author Thiago Belem <contato@thiagobelem.net>
 * @link http://blog.thiagobelem.net/
 * @version 1.0
 */
class Sec_GoogleUrlShort
{

	/**
	 * URL da API do Goo.gl
	 *
	 * @var string
	 */
	public static $api_url = 'http://goo.gl/api/url';

	/**
	 * User usado para acessar a API do Goo.gl
	 *
	 * Este é o user definido pela barra do Google
	 *
	 * @var string
	 */
	public static $user = 'redeanisioteixeira@gmail.com';

	/**
	 * Tempo limite (em segundos) para encurtar a URL
	 *
	 * @var integer
	 */
	public static $timeout = 10;

	/**
	 * Método construtor
	 *
	 * Verifica se existem as funções curl_init() e json_decode()
	 *  utilizadas pela classe
	 */
	public function __construct() {
		if (!function_exists('curl_init'))
			trigger_error('Please, enable the cURL library!');

		if (!function_exists('json_decode'))
			trigger_error('Please, enable the JSON library!');
	}

	/**
	 * Faz uma requisição HTTP utilizando cURL
	 *
	 * @param string $url URL a ser requisitada
	 * @param string $fields Campos a serem passados via POST
	 * @param string $headers Headers adicionais
	 *
	 * @return string O HTML resultado
	 */
	public function requestURL($url, $fields = '', $headers = false) {
		$curl = curl_init($url);

		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_TIMEOUT, Sec_GoogleUrlShort::$timeout);
		curl_setopt($curl, CURLOPT_USERAGENT, getenv('HTTP_USER_AGENT'));

		if (!empty($fields)) {
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $fields);
		}

		if ($headers)
			curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

		return curl_exec($curl);
	}

	/**
	 * Encurta uma URL utilizando a API do Goo.gl
	 *
	 * @param string|array $url URL a ser encurtada ou array de URLs
	 *  a serem encurtadas
	 *
	 * @return string|array URL encurtada ou array de URLs encurtadas
	 */
	public function shorten($url) {
		// Se for um array de URLs age recursivamente
		if (is_array($url)) {
			foreach ($url AS &$u)
				$u = $this->shorten($u);

			return $url;
		}

		// Se for uma URL válida
		if (filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED)) {

			// Monta a lista de parâmetros usados pela API
			$fields = array(
				'user' => Sec_GoogleUrlShort::$user,
				'url' => urlencode($url));

			// Converte o array de parâemtros em uma string GET
			$fields = urldecode(http_build_query($fields, '', '&'));

			// Se tudo der certo com a chamada à API...
			if ($result = $this->requestURL(Sec_GoogleUrlShort::$api_url, $fields)) {
				// Decodifica o resultado em jSON
				$result = json_decode($result);

				// Se recebeu alguma mensagem de erro, lança um erro
				if (isset($result->error_message))
					trigger_error('[goo.gl] ' . $result->error_message);

				// Ou retorna a URL encurtada
				else
					return $result->short_url;

			// ...caso contrário, retorna a URL original
			} else
				return $url;
		}
	}
}
?>
