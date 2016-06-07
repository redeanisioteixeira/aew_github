<?php

class Aew_View_Helper_ActiveCache
{
	private $caching = true;

	public final function getCaching()
	{
		return $this->caching;
	}

	public function ActiveCache($objetoId = null) 
	{
		ob_start();

		$result = $objetoId;
		if ($this->getCaching() == false){
			return $result;
		}

		require_once '../application/cache/Cache_Pagina.php';
		require_once '../application/cache/Cache_Config.php';

		$config = new Cache_Config();

		/*--- instanciando a o cache com os dados da página ---*/
		$cache = new Cache_Pagina($config->getCaching(), $config->getTempo()	, $config->getSerializacao(), $config->getHost(), $config->getPorta());

		if (is_object($objetoId)){
			$objetoId->finalizaCache();
			return;
		}

		if(!$cache->executaCache('Output', 'Memcached',$objetoId)){
			$result = $cache;
		}

		ob_get_clean();
		return $result;
    }
}
