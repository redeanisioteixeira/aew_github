<?php

/**
 * DAO da entidade Servidor
 */

class Aew_Model_Dao_Servidor extends Sec_Model_Dao_Abstract
{

    /**
     * Nome da entidade do DAO
     * @var string
     */
    public $_entityName = "Servidor";
    protected $_options  = array('orderBy' => 'idServidor ASC');

    /**
     * Chave primária
     * @var string
     */
    protected $_primaryKey = "idServidor";

    function __construct() {
        parent::__construct('servidor', 'idservidor');
    }
    public function createModelBo() {
        return new Aew_Model_Bo_Servidor();
    }

}