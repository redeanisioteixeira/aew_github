<?php

/**
 * DAO da entidade Usuario
 */

class Aew_Model_Dao_FeedTabela extends Sec_Model_Dao_Abstract
{
    /**
     * Nome da entidade do DAO
     * @var string
     */
    public $_entityName = "FeedTabela";

    /**
     * Chave primária
     * @var string
     */
    protected $_primaryKey = "id";

    protected $_options  = array('orderBy' => 'nome ASC');

    function __construct() 
    {
        parent::__construct('feedtabela','id');
    }
    
    protected function createModelBo() 
    {
        return new Aew_Model_bo_FeedTabela();
    }
}