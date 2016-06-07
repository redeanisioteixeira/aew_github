<?php
class Cache_Pagina
{
	private $caching;
	private $tempo;
	private $serializacao;
	private $host;
	private $porta;
	private $cache;

	/**
	* Construtor
	*/
	public function __construct($caching, $tempo, $serializacao,$host, $porta)
	{
		$this->caching = $caching;
		$this->tempo = $tempo;
		$this->serializacao = $serializacao;
		$this->host = $host;
		$this->porta = $porta;
		$this->cache = null;
	}

	/**
	* executa o cache com os parametros solicitados
	*/
	public function executaCache($nomefrontend, $nomebackend, $idOutput = null)
	{
		$frontend = array('caching' => $this->caching, 'lifetime' => $this->tempo, 'automatic_serialization' => $this->serializacao);

		$backend = array('servers' => array(
													array('host' => $this->host, 'port' => $this->porta)),
							  'compression' => false);

		$this->cache = Zend_Cache::factory($nomefrontend, $nomebackend, $frontend, $backend);

		if ($nomefrontend=='Output')
			return $this->cache->start($idOutput);
		else
			return $this->cache->start();
    }

	/**
	* finaliza o cache
	*/
	public function finalizaCache()
	{
		$this->cache->end(); //finalizando a parte que será armazenada em cache
	}
}
