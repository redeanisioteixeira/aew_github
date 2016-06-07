<?php

/**
 * @category   Sec
 * @package    Sec_Paginator
 * @copyright  Copyright (c) 2009 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Sec_Paginator_Adapter_Iterator extends Zend_Paginator_Adapter_Iterator
{
    /**
     * Iterator
     *
     * @var Doctrine_Collection
     */
    protected $_iterator = null;

    /**
     * Item count
     *
     * @var integer
     */
    protected $_count = null;

    /**
     * Constructor.
     *
     * @param array|Doctrine_Record $array Elements to paginate
     * @param integer $count Quantidade de registros
     */
    public function __construct($iterator, $count)
    {
        if (!$iterator instanceof Countable) {
            /**
             * @see Zend_Paginator_Exception
             */
            require_once 'Zend/Paginator/Exception.php';

            throw new Zend_Paginator_Exception('Iterator must implement Countable');
        }

        $this->_iterator = $iterator;
        $this->_count = $count;
    }

    /**
     * Returns an iterator of items for a page.
     *
     * @param  integer $offset Page offset
     * @param  integer $itemCountPerPage Number of items per page
     * @return LimitIterator
     */
    public function getItems($offset, $itemCountPerPage)
    {
        if ($this->_count == 0) {
            return array();
        }

        return $this->_iterator;
    }

    /**
     * Returns the total number of rows in the iterator.
     *
     * @return integer
     */
    public function count()
    {
        return $this->_count;
    }
}