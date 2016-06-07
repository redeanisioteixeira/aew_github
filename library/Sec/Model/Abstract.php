<?php

/**
 * Classe abstrata para Model
 * 
 * @author diegop
 *
 */
class Sec_Model_Abstract 
{
    
    /**
     * Constructor
     * 
     * @param array
     * @return void
     */
    public function __construct($array = null)
    {
        if(is_array($array)){
            foreach ($array as $key => $value) {
            $normalized = ucfirst($key);

	            $method = 'set' . $normalized;
	            if (method_exists($this, $method)) {
	                $this->$method($value);
	            }
	        }
	    }
	}
}