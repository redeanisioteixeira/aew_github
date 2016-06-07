<?php

/**
 * DAO da entidade ComunidadeAgenda
 */

class Aew_Model_Dao_ComunidadeAgenda extends Sec_Model_Dao_Abstract
{
    /**
     * Nome da entidade do DAO
     * @var string
     */
    public $_entityName = "ComunidadeAgenda";

    /**
     * Chave primária
     * @var string
     */
    protected $_primaryKey = "idComunidadeAgenda";

    protected $_options  = array('orderBy' => array('e.dataInicio DESC'),
                                'join' => array(
                                	'e.comunidade' => 'co',)
                           );
    
    public function __construct() {
        parent::__construct('comunidadeagenda', 'idcomunidadeagenda');
    }

    public function obtemOrdemInversa()
    {
		$this->_options  = array('orderBy' => array('e.dataInicio ASC'),
                                'join' => array(
                                	'e.comunidade' => 'co',
                                )
                           );
    }

    public function obtemNomeMarcacao($id, array $options = null)
    {
    	$this->obtemOrdemInversa();
		$q = $this->getSelect($options)
                  ->addWhere('e.idComunidadeAgenda = ?', $id);

        $obj = $this->execute($q);
        return $obj;
    }

    public function dataToObject(\Sec_Model_Bo_Abstract $bo, \Doctrine_Record $data) {
        
    }

    public function createModelBo() {
        
    }

}