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
* @version 1.00RC2
*/
class AtomBuilderContent extends AtomBuilderText {

	protected $link;

	function __construct($data = '', $type = 'text', $link = '') {
		parent::__construct($data, $type);
		if (parent::isFilledString($link)) {
			$this->setLink($link);
		} // end if
	} // end constructor

	public function setLink($string = '') {
		return parent::setVar($string, 'link', 'string');
	} // end function

	public function setContentType($string = '') {
		if (in_array($string, $this->allowed_types) == TRUE || preg_match('(^[a-zA-Z0-9]+/[a-zA-Z0-9]+$)', $string) > 0) {
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

			return parent::setVar($string, 'mode', 'string');
		} // end if
	} // end function
*/
	public function getLink() {
		return parent::getVar('link');
	} // end function
} // end class
?>