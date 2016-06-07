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
class AtomBuilderPerson extends AtomBuilderBase {
	protected $name;
	protected $url;
	protected $email;

	function __construct($name = '') {
		parent::__construct();
		$this->setName($name);
	} // end constructor

	protected function isValidMail($mail = '') {
		$regex =
		  '/^'.
		  '[_a-z0-9-]+'.        /* One or more underscore, alphanumeric,
		                           or hyphen charactures. */
		  '(\.[_a-z0-9-]+)*'.  /* Followed by zero or more sets consisting
		                           of a period and one or more underscore,
		                           alphanumeric, or hyphen charactures. */
		  '@'.                  /* Followed by an "at" characture. */
		  '[a-z0-9-]+'.        /* Followed by one or more alphanumeric
		                           or hyphen charactures. */
		  '(\.[a-z0-9-]{2,})+'. /* Followed by one or more sets consisting
		                           of a period and two or more alphanumeric
		                           or hyphen charactures. */
		  '$/';
		  return preg_match($regex, $mail);
	} // end function

	public function setName($string = '') {
		return parent::setVar($string, 'name', 'string');
	} // end function

	public function setURL($string = '') {
		return parent::setVar($string, 'url', 'string');
	} // end function

	public function setEmail($string = '') {
		if ($this->isValidMail($string) == TRUE) {
			return parent::setVar($string, 'email', 'string');
		} // end if
	} // end function

	public function getName() {
		return parent::getVar('name');
	} // end function

	public function getURL() {
		return parent::getVar('url');
	} // end function

	public function getEmail() {
		return parent::getVar('email');
	} // end function
} // end class

?>
