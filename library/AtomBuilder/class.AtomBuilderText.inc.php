<?php
require_once 'class.AtomBuilderBase.inc.php';

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
class AtomBuilderText extends AtomBuilderBase {
	protected $data;
	protected $type;
	protected $allowed_types = array('xhtml', 'text', 'html');

	function __construct($data = '', $type = 'text') {
		parent::__construct();
		$this->setData($data);
		$this->type = 'text';
		$this->setContentType($type);
	} // end constructor


	public function setData($data = '') {
		$this->data = $data;
	} // end function

	public function setContentType($string = '') {
		if (in_array($string, $this->allowed_types) == TRUE) {
			return parent::setVar($string, 'type', 'string');
		} // end if
		return FALSE;
	} // end function
/*
	public function setMode($string = 'escaped') {
		if (array_key_exists($string, $this->allowed_modes) == TRUE) {
			if (!isset($this->type)) {
				return parent::setVar($string, 'mode', 'string');
			} // end if

			foreach ($this->allowed_modes as $mode => $types) {
				if (in_array($this->type, $types) == TRUE) {
					return parent::setVar($mode, 'mode', 'string');
				} // end if
			} // end foreach

			return FALSE;
		} // end if
	} // end function
*/
	public function getData() {
		return $this->data;
	} // end function

	public function getContentType() {
		return parent::getVar('type');
	} // end function
/*
	public function getMode() {
		return parent::getVar('mode');
	} // end function
*/
} // end class
?>