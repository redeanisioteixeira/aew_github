<?php

/**
 * DAO da entidade UsuarioAgenda
 */

class Aew_Model_Dao_UsuarioAgenda extends Sec_Model_Dao_Abstract
{
    /**
     * Nome da entidade do DAO
     * @var string
     */
    public $_entityName = "UsuarioAgenda";

    /**
     * Chave primária
     * @var string
     */
    protected $_primaryKey = "idUsuarioAgenda";

    protected $_options  = array('orderBy' => array('e.dataInicio DESC'),
                                'join' => array(
                                	'e.usuario' => 'us',
                                )
                           );

    function __construct() {
        parent::__construct('usuarioagen', 'idusuarioagenda');
    }
    
    public function obtemOrdemInversa()
    {
	$this->_options  = array('orderBy' => array('e.dataInicio ASC'),
                                'join' => array(
                               	'e.usuario' => 'us',
                                ));
    }

    public function obtemNomeMarcacao($id, array $options = null)
    {
    	$this->obtemOrdemInversa();
		$q = $this->getSelect($options)
                  ->addWhere('e.idUsuarioAgenda = ?', $id);

        $obj = $this->execute($q);
        return $obj;
    }

    public function createModelBo() {
        return new Aew_Model_Bo_UsuarioAgenda();
    }

}