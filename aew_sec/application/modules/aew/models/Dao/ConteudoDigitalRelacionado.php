<?php

/**
 * DAO da entidade ConteudoDigitalRelacionado
 */

class Aew_Model_Dao_ConteudoDigitalRelacionado extends Sec_Model_Dao_Abstract
{

    /**
     * Nome da entidade do DAO
     * @var string
     */
    public $_entityName = "ConteudoDigitalRelacionado";

    /**
     * Chave primária
     * @var string
     */
    protected $_primaryKey = "idConteudoDigitalRelacionado";

    protected $_options  = array();
    
    function __construct() {
        parent::__construct('conteudodigitalrelacionado', 'idconteudodigitalrelacionado');
    }

    /**
     * Retorna se dois conteudos são relacionados
     * @param int $conteudo1
     * @param int $conteudo2
     * @return bool
     */
    public function isRelacionado($conteudo1, $conteudo2)
    {
        $id1 = (int) $conteudo1;
        $id2 = (int) $conteudo2;

        $q = new Doctrine_RawSql();
        $q->select('{c.*}')
            ->from('conteudodigitalrelacionado c')
            ->addComponent('c','ConteudoDigitalRelacionado')
            ->where('(c.idconteudodigital = ? OR
                 	  c.idconteudodigitalrelacionado = ?)', array($id1, $id1))
            ->addWhere('(c.idconteudodigital = ? OR
                 	  c.idconteudodigitalrelacionado = ?)', array($id2, $id2))
			  ;

		$result = $q->execute();

		if(count($result->toArray()) > 0){
		    return true;
		}
		return false;
    }

    /**
     * Acha um registro pela chave primaria
     * @param int $id1
     * @param int $id2
     * @return Doctrine_Record
     */
    public function find($id1, $id2)
    {
        $q = new Doctrine_RawSql();
        $q->select('{c.*}')
            ->from('conteudodigitalrelacionado c')
            ->addComponent('c','ConteudoDigitalRelacionado')
            ->where('(c.idconteudodigital = ? OR
                 	  c.idconteudodigitalrelacionado = ?)', array($id1, $id1))
            ->addWhere('(c.idconteudodigital = ? OR
                 	  c.idconteudodigitalrelacionado = ?)', array($id2, $id2))
			  ;

        $result = $q->execute();

		if(count($result->toArray()) == 1){
		    return $result[0];
		}
		return false;
    }

    public function dataToObject(\Sec_Model_Bo_Abstract $bo, \Doctrine_Record $data) {
        
    }

    public function createModelBo() {
        
    }

}