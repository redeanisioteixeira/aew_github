<?php
require_once 'class.AtomBuilderBase.inc.php';
require_once 'class.AtomBuilderObjectList.inc.php';
require_once 'class.AtomBuilderText.inc.php';
require_once 'class.AtomBuilderPerson.inc.php';
require_once 'class.AtomBuilderLink.inc.php';
require_once 'class.AtomBuilderEntry.inc.php';
require_once 'class.AtomBuilderObjectIterator.inc.php';
require_once 'class.AtomBuilderContent.inc.php';
require_once 'class.AtomBuilder_V_abstract.inc.php';
require_once 'class.AtomBuilder_V_100.inc.php';

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
class AtomBuilder extends AtomBuilderBase {

	const GENERATOR_VERSION 		= '1.00RC';
	const GENERATOR_NAME 			= 'Flaimo.com AtomBuilder';
	const GENERATOR_URL 			= 'http://flaimo.com/';
	const OUTPUT_MIMETYPE 			= 'application/atom+xml';
	const OUTPUT_FILENAME_PREFIX 	= 'atom';
	const OUTPUT_FILENAME_SUFIX 	= 'xml';
	const DEFAULT_ALTERNATE_TYPE 	= 'text/html';
	const XHTML_CONTENT_ID			= 'atomentry-content';
	const XHTML_CONTENT_ERROR_MSG	= 'AtomBuilder-Error: This entry contains no valid XHTML-Code';

	protected $encoding = 'UTF-8';
	protected $language;
	protected $title;
	protected $subtitle;
	protected $id;
	protected $rights;
	protected $updated;
	protected $author;
	protected $filename;
	protected $icon;
	protected $logo;

	protected $links;
	protected $categories;
	protected $contributors;
	protected $entries;

	protected $versions = array('1.0.0' => '100');
	protected $version_objects = array();

	function __construct($title = '', $url = 'http://flaimo.com/', $id = '') {
		parent::__construct();
		$this->links = new AtomBuilderObjectList(0,1000);
		$this->entries = new AtomBuilderObjectList(0,1000);
		$this->categories = new AtomBuilderObjectList(0,1000);
		$this->setUpdated(date('c', time()));
		$this->setTitle($title);
		$this->setID($id);
		$this->setAuthor(self::GENERATOR_NAME, '', self::GENERATOR_URL);
		$this->addLink($url, $title, 'self', self::DEFAULT_ALTERNATE_TYPE);
		$this->filename = str_replace(' ','-', $this->getTitle()->getData()) . '.' . self::OUTPUT_FILENAME_SUFIX;
	} // end constructor

	public function setEncoding($string = '') {
		return parent::setVar($string, 'encoding', 'string');
	} // end function

	public function setLanguage($iso_string = 'en') {
		if (parent::isLanguage($iso_string) == TRUE) {
			return parent::setVar($iso_string, 'language', 'string');
		} // end if
		return FALSE;
	} // end function

	public function setTitle($string = '', $type = 'text') {
		if (parent::isFilledString($string)) {
			$this->title = new AtomBuilderText($string, $type);
		} // end if
	} // end function

	public function setSubtitle($string = '', $type = 'text') {
		if (parent::isFilledString($string)) {
			$this->subtitle = new AtomBuilderText($string, $type);
		} // end if
	} // end function

	public function setRights($string = '') {
		return parent::setVar($string, 'rights', 'string');
	} // end function

	public function setIcon($string = '') {
		return parent::setVar($string, 'icon', 'string');
	} // end function

	public function setLogo($string = '') {
		return parent::setVar($string, 'logo', 'string');
	} // end function

	public function setID($string = '') {
		return parent::setVar($string, 'id', 'string');
	} // end function

	public function setUpdated($datetime = '1970-01-01T00:00:00Z') {
		if (parent::isvalidDate($datetime) == TRUE) {
			return parent::setVar($datetime, 'updated', 'string');
		} // end if
		return FALSE;
	} // end function

	public function setAuthor($name, $email = '', $url = '') {
		$author = new AtomBuilderPerson($name);
		if (parent::isFilledString($email) == TRUE) {
			$author->setEmail($email);
		} // end if
		if (parent::isFilledString($url) == TRUE) {
			$author->setURL($url);
		} // end if

		$this->author = $author;
	} // end function

	public function addLink($url = '', $title = '', $rel = '', $type = '', $hreflang = '') {
		$found = FALSE;

		if ($rel == 'alternate') {
			// only add alternate-link if it doesn't exist yet with the same type and hreflang
			foreach ($this->links as $current_link) {
				if ($current_link->getRelation() == 'alternate' &&
					$current_link->getLinkType() == $type &&
					$current_link->getURLlang() == $hreflang) {
					$found = TRUE;
					break;
				} // end if
			} // end foreach
		} elseif ($rel == 'logo') {
			foreach ($this->links as $current_link) {
				if ($current_link->getRelation() == $rel) {
					$found = TRUE;
					break;
				} // end if
			} // end foreach
		} elseif ($rel == 'icon') {
			foreach ($this->links as $current_link) {
				if ($current_link->getRelation() == $rel) {
					$found = TRUE;
					break;
				} // end if
			} // end foreach
		} // end if

		if ($found  == FALSE) {
			$newlink = new AtomBuilderLink($url);
			if (parent::isFilledString($title) == TRUE) {
				$newlink->setTitle($title);
			} // end if
			if (parent::isFilledString($hreflang) == TRUE) {
				$newlink->setURLlang($hreflang);
			} // end if
			if (parent::isFilledString($type) == TRUE) {
				$newlink->setLinkType($type);
			} // end if
			if (parent::isFilledString($rel) == TRUE) {
				$newlink->setRelation($rel);
			} // end if
			$this->links->addObject($newlink);
		} // end if
	} // end function

	public function addCategory($term = 'default', $scheme = '', $label = '') {
		$newcategory = new AtomBuilderCategory($term);
		if (parent::isFilledString($scheme) == TRUE) {
			$newcategory->setScheme($scheme);
		} // end if
		if (parent::isFilledString($label) == TRUE) {
			$newcategory->setLabel($label);
		} // end if
		$this->categories->addObject($newcategory);
	} // end function

	public function addContributor($name, $email = '', $url = '') {
		if (parent::isFilledString($name) == TRUE) {
			if (!isset($this->contributors)) {
				$this->contributors = new AtomBuilderObjectList(0,1000);
			} // end if

			$tmp_person = new AtomBuilderPerson($name);
			if (parent::isFilledString($email) == TRUE) {
				$tmp_person->setEmail($email);
			} // end if
			if (parent::isFilledString($url) == TRUE) {
				$tmp_person->setURL($url);
			} // end if
			$this->contributors->addObject($tmp_person);
		} // end if
	} // end function

	public function addEntry(AtomBuilderEntry &$entry = NULL) {
		$this->entries->addObject($entry);
	} // end function

	public function newEntry($title = '', $url = '', $issued = 0, $id = '') {
		return new AtomBuilderEntry($title, $url, $issued, $id);
	} // end function

	public function getEncoding() {
		return parent::getVar('encoding');
	} // end function

	public function getLanguage() {
		return parent::getVar('language');
	} // end function

	public function getTitle() {
		return parent::getVar('title');
	} // end function

	public function getSubtitle() {
		return parent::getVar('subtitle');
	} // end function

	public function getRights() {
		return parent::getVar('rights');
	} // end function

	public function getID() {
		return parent::getVar('id');
	} // end function

	public function getUpdated() {
		return parent::getVar('updated');
	} // end function

	public function getLinks() {
		return parent::getVar('links');
	} // end function

	public function getCategories() {
		return parent::getVar('categories');
	} // end function

	public function getAuthor() {
		return parent::getVar('author');
	} // end function

	public function getIcon() {
		return parent::getVar('icon');
	} // end function

	public function getLogo() {
		return parent::getVar('logo');
	} // end function

	public function getContributors() {
		return parent::getVar('contributors');
	} // end function

	public function getEntries() {
		return parent::getVar('entries');
	} // end function

	public function getFilename() {
		return parent::getVar('filename');
	} // end function

	protected function setVersionObject($version = '1.0.0') {
		if (array_key_exists($version, $this->versions)) {
			$classname = 'AtomBuilder_V_' . $this->versions[$version];
			$this->version_objects[$version] = new $classname($this);
		} // end if
	} // end function

	protected function prepareAtomRequest($version = '1.0.0') {
		$this->filename = self::OUTPUT_FILENAME_PREFIX . $this->versions[$version] . '---' . $this->filename;
		if (strlen($this->filename) > 255) {
			$this->filename = substr($this->filename, 0, 255);
		} // end if

		if (!isset($this->version_objects[$version])) {
			$this->setVersionObject($version);
		} // end if
	} // end function

	public function getAtomOutput($version = '1.0.0') {
		$this->prepareAtomRequest($version);
		return $this->version_objects[$version]->getAtomOutput();
	} // end function

	public function saveAtom($version = '1.0.0', $path = '') {
		$this->prepareAtomRequest($version);
		return $this->version_objects[$version]->saveAtom($path);
	} // end function

	public function outputAtom($version = '1.0.0') {
		$this->prepareAtomRequest($version);
		header('Content-type: ' . self::OUTPUT_MIMETYPE . ';charset=' . $this->getEncoding() . " \r\n");
		header('Content-Disposition: inline; filename=' . $this->getFilename());
		return $this->version_objects[$version]->outputAtom();
	} // end function
} // end class
?>
