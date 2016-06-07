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
class AtomBuilderEntry extends AtomBuilderBase {
	protected $title;
	protected $links;
	protected $author;
	protected $contributors;
	protected $categories;
	protected $published;
	protected $updated;
	protected $id;
	protected $summary;
	protected $description;
	protected $publisher;
	protected $content;
	protected $rights;
	protected $source;

	function __construct($title = '', $url = '', $id = '') {
		$this->links = new AtomBuilderObjectList(0,1000);
		$this->categories = new AtomBuilderObjectList(0,1000);
		$this->setTitle($title);
		$this->addLink($url, $title, 'alternate', AtomBuilder::DEFAULT_ALTERNATE_TYPE);
		$this->setID($id);
		$this->setUpdated(time());
		parent::__construct();
	} // end constructor

	public function setTitle($string = '', $type = 'text') {
		if (parent::isFilledString($string)) {
			$this->title = new AtomBuilderText($string, $type);
		} // end if
	} // end function

	public function setRights($string = '') {
		return parent::setVar($string, 'rights', 'string');
	} // end function

	public function setSource(AtomBuilderEntry $source) {
		$this->source = $source;
	} // end function

	public function addLink($url = '', $title = '', $rel = '', $type = '', $hreflang = '') {
		$found = FALSE;

		if ($rel == 'alternate') {
			// only add alternate-link if it doesn't exist yet
			foreach ($this->links as $current_link) {
				if ($current_link->getRelation() == 'alternate' &&
					$current_link->getLinkType() == $type &&
					$current_link->getURLlang() == $hreflang) {
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

	public function setUpdated($datetime = '1970-01-01T00:00:00Z') {
		if (parent::isvalidDate($datetime) == TRUE) {
			return parent::setVar($datetime, 'updated', 'string');
		} // end if
		return FALSE;
	} // end function

	public function setPublished($datetime = '1970-01-01T00:00:00Z') {
		if (parent::isvalidDate($datetime) == TRUE) {
			return parent::setVar($datetime, 'published', 'string');
		} // end if
		return FALSE;
	} // end function

	public function setSummary($string = '', $type = 'text') {
		if (parent::isFilledString($string) == TRUE) {
			$this->summary = new AtomBuilderText($string, $type);
			return TRUE;
		} // end if
		return FALSE;
	} // end function

	public function setDescription($string = '', $type = 'text') {
		if (parent::isFilledString($string) == TRUE) {
			$this->description = new AtomBuilderText($string, $type);
			return TRUE;
		} // end if
		return FALSE;
	} // end function

	public function setPublisher($string = '', $type = 'text') {
		if (parent::isFilledString($string) == TRUE) {
			$this->publisher = new AtomBuilderText($string, $type);
			return TRUE;
		} // end if
		return FALSE;
	} // end function

	public function setContent($content = '', $type = 'text', $link = '') {
		if ($type != 'xhtml') {
			if (parent::isFilledString($content) == TRUE) {
				$this->content = new AtomBuilderContent($content, $type, $link);
				return TRUE;
			} // end if
		} else {
			$this->content = new AtomBuilderContent($content, $type, $link);
			return TRUE;
		} // end if
		return FALSE;
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

	public function setID($string = '') {
		return parent::setVar($string, 'id', 'string');
	} // end function

	public function getTitle() {
		return parent::getVar('title');
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

	public function getID() {
		return parent::getVar('id');
	} // end function

	public function getUpdated() {
		return parent::getVar('updated');
	} // end function

	public function getPublished() {
		return parent::getVar('published');
	} // end function

	public function getContributors() {
		return parent::getVar('contributors');
	} // end function

	public function getSummary() {
		return parent::getVar('summary');
	} // end function

	public function getDescription() {
		return parent::getVar('description');
	} // end function

	public function getPublisher() {
		return parent::getVar('publisher');
	} // end function

	public function getContent() {
		return parent::getVar('content');
	} // end function

	public function getRights() {
		return parent::getVar('rights');
	} // end function

	public function getSource() {
		return parent::getVar('source');
	} // end function
} // end class
?>
