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
class AtomBuilderObjectList extends AtomBuilderBase implements IteratorAggregate {

	protected $size = 20;
	protected $offset = 0;
	public $objects;
	protected $factory;

	function __construct($offset = 0, $size = 20) {
		parent::__construct();
		$this->setSize($size);
		$this->setOffset($offset);
	} // end constructor

	public function setSize($size = 20) {
		$this->size = (int) $size;
	} // end function

	public function setOffset($offset = 0) {
		$this->offset = (int) $offset;
	} // end function

	public function addObject($object) {
		if (is_object($object)) {
			$this->objects[] = $object;
			return (boolean) TRUE;
		} // end if
		return (boolean) FALSE;
	} // end function

	public function getSize() {
		return $this->size;
	} // end function

	public function getListSize() {
		return count($this->getList());
	} // end function

	public function getOffset() {
		return $this->offset;
	} // end function

	public function getList() {
		return $this->objects;
	} // end function

	public function setFactory($class_name = FALSE) {
		if (!isset($class_name) || $class_name === FALSE) {
			return (boolean) FALSE;
		} // end if

		$this->factory =& parent::getObjectFactory($class_name);
		return (boolean) TRUE;
	} // end function

	public function getIterator() {
		return new AtomBuilderObjectIterator($this);
	} // end function
} // end class
?>