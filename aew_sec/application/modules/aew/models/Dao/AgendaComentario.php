<?php

/**
 * DAO da entidade AgendaComentario
 */

class Aew_Model_Dao_AgendaComentario extends Sec_Model_Dao_Abstract
{
    /**
     * Nome da entidade do DAO
     * @var string
     */
    public $_entityName = "AgendaComentario";

    /**
     * Chave primária
     * @var string
     */
    protected $_primaryKey = "idAgendaComentario";

    protected $_options  = array('orderBy' => array('e.dataCriacao DESC'));
                           
	/**
     * Construtor
     */
    public function __construct($tipo)
    {
        $this->tipo = $tipo;
        parent::__construct('agendacomentario','idagendacomentario');
    }

    public function obtemComentario($id, $options = null)
    {
    	$q = $this->getSelect($options)
                  ->addWhere('e.idAgenda = ?', $id)
                  ->addWhere('e.tipo = ?', $this->tipo);
        $obj = $this->execute($q);
        return $obj;
    }
    
    public function getByIdAgendaComentario($id, array $options = null)
    {
        $q = $this->getSelect($options)
                  ->addWhere('e.idAgenda = ?', $id)
                  ->addWhere('e.tipo = ?', $this->tipo);
        $obj = $this->execute($q);
        return $obj;
    }

    public function createModelBo() 
    {
        return new Aew_Model_Bo_AgendaComentario();
    }
}