<?php
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
interface AtomBuilderInterface {
	public function getAtomOutput();
	public function outputAtom();
	public function saveAtom();
} // end interface
?>