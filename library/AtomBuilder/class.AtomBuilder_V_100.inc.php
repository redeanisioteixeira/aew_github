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
class AtomBuilder_V_100 extends AtomBuilder_V_abstract {

	function __construct(AtomBuilder $atomdata) {
		parent::__construct($atomdata);
	} // end constructor

	protected function getXhtmlContent($string = '') {
		$xhtmlcontent = new DomDocument();
		if (@$xhtmlcontent->loadXML('<div id="atomentry-content">' . $string . '</div>') == TRUE) {
			$xp = new DomXPath($xhtmlcontent);
			$res = $xp->query("//*[@id = '" . AtomBuilder::XHTML_CONTENT_ID . "']");
			$divelement = $res->item(0);
			if ($divelement instanceof DomElement) {
				$divelement->removeAttribute('id');
			} else {
				return FALSE;
			} // end if
		} else {
			$divelement = $xhtmlcontent->createElement('div');
			$divelement->appendChild($xhtmlcontent->createTextNode(AtomBuilder::XHTML_CONTENT_ERROR_MSG));
		} // end if
		$divelement->setAttribute('xmlns', 'http://www.w3.org/1999/xhtml');
		return $divelement;
	} // end function

	protected function getContent(AtomBuilderContent $content, $tagname = '', $parentnode) {
		$newnode = $this->xml->createElement($tagname);

		if ($content->getContentType() == 'text') {
			$newnode->setAttribute('type', $content->getContentType());
			$newnode->appendChild($this->xml->createTextNode($content->getData()));
		} elseif ($content->getContentType() == 'html') {
			$newnode->setAttribute('type', $content->getContentType());
			$newnode->appendChild($this->xml->createCDATASection($content->getData()));
		} elseif ($content->getContentType() == 'xhtml') {
			$newnode->setAttribute('type', $content->getContentType());

			$xhtmlcontent = $this->getXhtmlContent($content->getData());
			if ($xhtmlcontent instanceof DOMNode) {
				$newnode->appendChild($this->xml->importNode($xhtmlcontent, TRUE));
			} else {
				return FALSE;
			} // end if
		} else {
			if ($content->getLink() != FALSE && strlen(trim($content->getLink())) > 0) {
				$newnode->setAttribute('src', $content->getLink());
				$newnode->setAttribute('type', $content->getContentType());
			} else {
				$newnode->setAttribute('type', $content->getContentType());
				$newnode->appendChild($this->xml->createTextNode($content->getData()));
			}// end if
		} // end if

		$parentnode->appendChild($newnode);
		return TRUE;
	} // end function

	protected function getText(AtomBuilderText $text, $tagname = '', $parentnode) {
		if ($text->getContentType() == 'text' ||
			$text->getContentType() == 'html' ||
			$text->getContentType() == 'xhtml') {
			$newnode = $this->xml->createElement($tagname);
			$newnode->setAttribute('type', $text->getContentType());

			if ($text->getContentType() == 'xhtml') {
				$xhtmlcontent = $this->getXhtmlContent($content->getData());
				if ($xhtmlcontent instanceof DOMNode) {
					$newnode->appendChild($this->xml->importNode($xhtmlcontent, TRUE));
				} else {
					return FALSE;
				} // end if
			} elseif ($text->getContentType() == 'html') {
				$newnode->appendChild($this->xml->createCDATASection($text->getData()));
			} else {
				$newnode->appendChild($this->xml->createTextNode($text->getData()));
			} // end if

			$parentnode->appendChild($newnode);
			return TRUE;
		} // end if
		return FALSE;
	} // end function

	protected function getPerson(AtomBuilderPerson $person, $tagname = '', $parentnode) {
		$newnode = $this->xml->createElement($tagname);
		$parentnode->appendChild($newnode);
		$personname = $this->xml->createElement('name');
		$personname->appendChild($this->xml->createTextNode($person->getName()));
		$newnode->appendChild($personname);

		if ($person->getEmail() != FALSE) {
			$personmail = $this->xml->createElement('email');
			$personmail->appendChild($this->xml->createTextNode($person->getEmail()));
			$newnode->appendChild($personmail);
		} // end if

		if ($person->getURL() != FALSE) {
			$personurl = $this->xml->createElement('uri');
			$personurl->appendChild($this->xml->createTextNode($person->getURL()));
			$newnode->appendChild($personurl);
		} // end if
	} // end function

	protected function getLink(AtomBuilderLink $link, $parentnode) {
		$newnode = $this->xml->createElement('link');

		if ($link->getRelation() != FALSE) {
			$newnode->setAttribute('rel', $link->getRelation());
		} // end if

		if ($link->getLinkType() != FALSE) {
			$newnode->setAttribute('type', $link->getLinkType());
		} // end if

		$newnode->setAttribute('href', $link->getURL());
		if ($link->getTitle() != FALSE) {
			$newnode->setAttribute('title', $link->getTitle());
		} // end if

		if ($link->getURLlang() != FALSE) {
			$newnode->setAttribute('hreflang', $link->getURLlang());
		} // end if

		$parentnode->appendChild($newnode);
	} // end function

	protected function getCategory(AtomBuilderCategory $cat, $parentnode) {
		$newnode = $this->xml->createElement('category');
		$newnode->setAttribute('term', $cat->getTerm());

		if ($cat->getScheme() != FALSE) {
			$newnode->setAttribute('scheme', $cat->getScheme());
		} // end if

		if ($cat->getLabel() != FALSE) {
			$newnode->setAttribute('label', $cat->getLabel());
		} // end if

		$parentnode->appendChild($newnode);
	} // end function

	protected function getEntry(AtomBuilderEntry $current_entry, $tagname = 'entry', $parentnode) {
		$entry = $this->xml->createElement($tagname);
		$this->getText($current_entry->getTitle(), 'title', $entry);
		$entryid = $this->xml->createElement('id');
		$entryid->appendChild($this->xml->createTextNode($current_entry->getID()));
		$entry->appendChild($entryid);
		$entryupdated = $this->xml->createElement('updated');
		$entryupdated->appendChild($this->xml->createTextNode($current_entry->getUpdated()));
		$entry->appendChild($entryupdated);

		if ($current_entry->getPublished() != FALSE) {
			$entrypublished = $this->xml->createElement('published');
			$entrypublished->appendChild($this->xml->createTextNode($current_entry->getPublished()));
			$entry->appendChild($entrypublished);
		} // end if

		if ($current_entry->getAuthor() != FALSE) {
			$this->getPerson($current_entry->getAuthor(), 'author', $entry);
		} // end if

		if(null!=$current_entry->getContributors()) {
			foreach ($current_entry->getContributors() as $entrycontributor) {
				$this->getPerson($entrycontributor, 'contributor', $entry);
			} // end foreach
		}

		foreach ($current_entry->getLinks() as $entrylink) {
			$this->getLink($entrylink, $entry);
		} // end foreach

		foreach ($current_entry->getCategories() as $cat) {
			$this->getCategory($cat, $entry);
		} // end foreach

		if ($current_entry->getSummary() != FALSE) {
			$this->getText($current_entry->getSummary(), 'summary', $entry);
		} // end if

		if ($current_entry->getDescription() != FALSE) {
			$this->getText($current_entry->getDescription(), 'description', $entry);
		} // end if

		if ($current_entry->getPublisher() != FALSE) {
			$this->getText($current_entry->getPublisher(), 'publisher', $entry);
		} // end if

		if ($current_entry->getContent() != FALSE) {
			$this->getContent($current_entry->getContent(), 'content', $entry);
		} // end if

		if ($current_entry->getRights() != FALSE) {
			$entryrights = $this->xml->createElement('rights');
			$entryrights->appendChild($this->xml->createTextNode($current_entry->getRights()));
			$entry->appendChild($entryrights);
		} // end if
/*
		if ($current_entry->getSource() != FALSE) {
			$this->getEntry($current_entry->getSource(), 'source', $entry);
		} // end if
*/
		$parentnode->appendChild($entry);
	} // end function


	protected function generateXML() {
		parent::generateXML();
		$feed = $this->xml->createElement('feed');
		$feed->setAttribute('xmlns', 'http://www.w3.org/2005/Atom');

		if ($this->atomdata->getLanguage() != FALSE) {
			$feed->setAttribute('xml:lang', $this->atomdata->getLanguage());
		} // end if

		$this->xml->appendChild($feed);
		$this->getText($this->atomdata->getTitle(), 'title', $feed);
		$generator = $this->xml->createElement('generator');
		$generator->appendChild($this->xml->createTextNode(AtomBuilder::GENERATOR_NAME));
		$generator->setAttribute('uri', AtomBuilder::GENERATOR_URL);
		$generator->setAttribute('version', AtomBuilder::GENERATOR_VERSION);
		$feed->appendChild($generator);

		if ($this->atomdata->getRights() != FALSE) {
			$rights = $this->xml->createElement('rights');
			$rights->appendChild($this->xml->createTextNode($this->atomdata->getRights()));
			$feed->appendChild($rights);
		} // end if

		if ($this->atomdata->getSubtitle() != FALSE) {
			$this->getText($this->atomdata->getSubtitle(), 'subtitle', $feed);
		} // end if

		if ($this->atomdata->getUpdated() != FALSE) {
			$updated = $this->xml->createElement('updated');
			$updated->appendChild($this->xml->createTextNode($this->atomdata->getUpdated()));
			$feed->appendChild($updated);
		} // end if

		if ($this->atomdata->getIcon() != FALSE) {
			$icon = $this->xml->createElement('icon');
			$icon->appendChild($this->xml->createTextNode($this->atomdata->getIcon()));
			$feed->appendChild($icon);
		} // end if

		if ($this->atomdata->getLogo() != FALSE) {
			$logo = $this->xml->createElement('logo');
			$logo->appendChild($this->xml->createTextNode($this->atomdata->getLogo()));
			$feed->appendChild($logo);
		} // end if

		if ($this->atomdata->getID() != FALSE) {
			$id = $this->xml->createElement('id');
			$id->appendChild($this->xml->createTextNode($this->atomdata->getID()));
			$feed->appendChild($id);
		} // end if

		if(null!=$this->atomdata->getAuthor())
			$this->getPerson($this->atomdata->getAuthor(), 'author', $feed);

		if(null!=$this->atomdata->getContributors()) {
			foreach ($this->atomdata->getContributors() as $contributor) {
				$this->getPerson($contributor, 'contributor', $feed);
			} // end foreach
		}

		foreach ($this->atomdata->getLinks() as $link) {
			$this->getLink($link, $feed);
		} // end foreach

		foreach ($this->atomdata->getCategories() as $cat) {
			$this->getCategory($cat, $feed);
		} // end foreach

		foreach ($this->atomdata->getEntries() as $current_entry) {
			$this->getEntry($current_entry, 'entry', $feed);
		} // end foreach
	} // function
} // end class
?>
