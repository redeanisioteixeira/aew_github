<?php
class Cache_Usuario
{
	private $caminho = '../application/cache_objetos/usuarios/';
	/**
     * Retorna o cache do objeto e armazena num arquivo
     */

    public function obtemObjeto($objeto)
    {

		$cache = Zend_Cache::factory('Class',
			     'File',
			     array(
				   'lifetime' => 60,
				   'cached_entity' => $objeto),
			     array(
				   'cache_dir' => $this->caminho)
			     );

        return $cache;
    }

}
?>