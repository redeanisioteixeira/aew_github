<?php
class Cache_Config
{	//configurações gerais das paginas completas e pedaços de pagina
	private $caching = true;
	private $tempo = 120; //--- tempo em segundos com a pagina em cache
	private $serializacao = true;
	//private $host = '10.100.246.11';
        private $host = '127.0.0.1';
	private $porta = 11211;

	/**
	* Retorna os dados configurados de maneira geral para o cache das paginas em comum
	*/

	public final function getCaching()
	{
		return $this->caching;
	}

	public final function getTempo()
	{
		return $this->tempo;
	}

	public final function getSerializacao()
	{
		return $this->serializacao;
	}

	public final function getHost()
	{
		return $this->host;
	}

	public final function getPorta()
	{
		return $this->porta;
	}
}
