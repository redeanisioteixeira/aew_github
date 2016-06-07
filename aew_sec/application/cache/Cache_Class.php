<?php
class Cache_Class
{
    private $caminho = '../application/cache_objetos/conteudos/';
    /**
     * Retorna o cache do objeto e armazena num arquivo
     */
    public function obtemObjeto($objeto)
    {
        
	$cache = Zend_Cache::factory('Class','Memcached',
			     array(
				   'lifetime' => 120,
				   'cached_entity' => $objeto),
			     array(
		    		'servers' =>array(
		        		array('host' => '127.0.0.1', 'port' => 11211)
		    			),
					    'compression' => false
					));

        return $cache;
    }

    function convertArrayToObject($array)
    {
	$object = new stdClass();
        if (is_array($array) && count($array) > 0) 
        {
            foreach ($array as $name=>$value) 
            {
                $name = strtolower(trim($name));
                if (!empty($name)) {
                    $object->$name = $value;
                }
            }
        }
        return $object;
    }

    function convertObjectToArray($object)
    {
        if ( count($object) > 1 ) 
        {
            $arr = array();
            for ( $i = 0; $i < count($object); $i++ ) 
            {
		$arr[] = get_object_vars($object[$i]);
            }
            return $arr;
	} 
        else 
        {
            return get_object_vars($object);
	}
    }

}