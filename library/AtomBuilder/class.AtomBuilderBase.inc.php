<?php
function __autoload($class = ''){
	require_once('class.' . $class . '.inc.php');
} // end function

/**
* Class for creating an Atom-Feed
* @author Michael Wimmer <flaimo@gmx.net>
* @category FLP
* @copyright Copyright © 2002-2006, Michael Wimmer
* @license Free for non-commercial use
* @link http://flp.sf.net/
* @package Atom
* @version 1.00
*/
abstract class AtomBuilderBase {
	protected $allowed_datatypes = array('string', 'int', 'boolean',
								   'object', 'float', 'array');

	function __construct() {
	} // end constructor

	protected function setVar($data = FALSE, $var_name = '', $type = 'string') {
		if (!in_array($type, $this->allowed_datatypes) ||
			$type != 'boolean' && ($data === FALSE ||
			$this->isFilledString($var_name) === FALSE)) {
			return (boolean) FALSE;
		} // end if

		switch ($type) {
			case 'string':
				if ($this->isFilledString($data) === TRUE) {
					$this->$var_name = (string) trim($data);
					return (boolean) TRUE;
				} // end if
			case 'int':
				if (is_numeric($data)) {
					$this->$var_name = (int) $data;
					return (boolean) TRUE;
				} // end if
			case 'boolean':
				if (is_bool($data)) {
					$this->$var_name = (boolean) $data;
					return (boolean) TRUE;
				}  // end if
			case 'object':
				if (is_object($data)) {
					$this->$var_name =& $data;
					return (boolean) TRUE;
				} // end if
			case 'array':
				if (is_array($data)) {
					$this->$var_name = (array) $data;
					return (boolean) TRUE;
				} // end if
		} // end switch
		return (boolean) FALSE;
	} // end function

	protected function getVar($var_name = 'dummy') {
		return (isset($this->$var_name)) ? $this->$var_name: FALSE;
	} // end function

	public static function isFilledString($string = '', $min_length = 0) {
		if ($min_length == 0) {
			return !ctype_space($string);
		} // end if

		return (boolean) (strlen(trim($string)) > $min_length) ? TRUE : FALSE;
	} // end function

	public static function isvalidDate($string = '') {
		return (boolean) ((preg_match('(^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}(\.[0-9]+){0,1}(Z|([\+\-]\d{2}:\d{2}){0,1})$)', $string) > 0) ? TRUE : FALSE);
	} // end function

	public static function isLanguage($iso_string = '') {
		return (preg_match('(^[a-zA-Z]{2}$)', $iso_string) > 0) ? TRUE : FALSE;
	} // end function
} // end class
?>