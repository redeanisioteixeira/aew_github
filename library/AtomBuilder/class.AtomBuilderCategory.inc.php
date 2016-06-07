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
class AtomBuilderCategory extends AtomBuilderBase {
	protected $term;
	protected $scheme;
	protected $label;

	function __construct($term = 'default') {
		parent::__construct();
		$this->setTerm($term);
	} // end constructor

	public function setTerm($string = '') {
		return parent::setVar($string, 'term', 'string');
	} // end function

	public function setScheme($string = '') {
		return parent::setVar($string, 'scheme', 'string');
	} // end function

	public function setLabel($string = '') {
		return parent::setVar($string, 'label', 'string');
	} // end function


	public function getTerm() {
		return parent::getVar('term');
	} // end function

	public function getScheme() {
		return parent::getVar('scheme');
	} // end function

	public function getLabel() {
		return parent::getVar('label');
	} // end function
} // end class
?>