<?php

/**
 * DAO da entidade MarcacaoAgenda
 */

class Aew_Model_Dao_MarcacaoAgenda extends Sec_Model_Dao_Abstract
{
    /**
     * Nome da entidade do DAO
     * @var string
     */
    public $_entityName = "marcacaoagenda";

    /**
     * Chave primária
     * @var string
     */
    protected $_primaryKey = 'idAgenda';

    protected $_options  = array('orderBy' => array('e.dataCriacao DESC'));

	/**
     * Construtor
     */
    public function __construct($tipo)
    {
        parent::__construct('marcacaoagenda', 'idagenda');
        $this->tipo = $tipo;
    }

public function obtemCofirmados($id, array $options = null)
    {
        $q = $this->getSelect($options)
                  ->addWhere('e.idAgenda = ?', $id)
                  ->addWhere('e.aceito = ?', true)
                  ->addWhere('e.tipo = ?', $this->tipo);
        $obj = $this->execute($q);

        return $obj;
    }

public function obtemConvidados($id, array $options = null)
    {
        $q = $this->getSelect($options)

                  ->addWhere('e.idAgenda = ?', $id)
                  ->addWhere('e.aceito = ?', false)
                  ->addWhere('e.tipo = ?', $this->tipo);

        $obj = $this->execute($q);

        return $obj;
    }
public function obtemConvidadosUsuario($id, array $options = null)
    {

        $q = $this->getSelect($options)
                  ->addWhere('e.idUsuario = ?', $id)
                  ->addWhere('e.aceito = ?', false);

        $obj = $this->execute($q);

        return $obj;
    }

public function obtemPorId($idUsuario, $idAgenda, array $options = null)
    {
    	//echo $idUsuario." , ".$idAgenda." , ".$this->tipo; die();
        $q = $this->getSelect($options)

                  ->addWhere('e.idAgenda = ?', $idAgenda)
                  ->addWhere('e.idUsuario = ?', $idUsuario);

        $obj = $this->execute($q);
        return $obj;
    }

public function obtemtodos($idUsuario, $idAgenda, array $options = null)
    {
    	//echo $idUsuario." , ".$idAgenda." , ".$this->tipo; die();
        $q = $this->getSelect($options)

                  ->addWhere('e.idAgenda = ?', $idAgenda)
                  ->addWhere('e.idUsuario = ?', $idUsuario)
                  ->addWhere('e.tipo = ?', $this->tipo);

        $obj = $this->execute($q);
        return $obj;
    }

public function obtemNaoVistos($id, array $options = null)
    {
    	//echo $idUsuario." , ".$idAgenda." , ".$this->tipo; die();
        $q = $this->getSelect($options)
                  ->addWhere('e.visto = ?', false)
                  ->addWhere('e.idUsuario = ?', $id);

        $obj = $this->execute($q);
        return $obj;
    }

public function obtemNaoVistosNum($id, array $options = null)
    {

$obj = Doctrine_Manager::getInstance()->getCurrentConnection()->fetchAssoc("select count(idAgenda) as total from marcacaoagenda where visto=false AND idUsuario=$id group by idUsuario");
if (empty($obj) ) return 0;
        return $obj[0]["total"];
    }

public function setaVistos($id)
    {
    	$this->getUpdate(array('e.visto = ?'=> true), array('e.idUsuario = ?' => $id));
    }

public function obtemPorUsuario($idUsuario, array $options = null)
    {
    	//echo $idUsuario." , ".$idAgenda." , ".$this->tipo; die();
        $q = $this->getSelect($options)
                  ->addWhere('e.idUsuario = ?', $idUsuario);

        $obj = $this->execute($q);
        return $obj;
    }

    public function dataToObject(\Sec_Model_Bo_Abstract $bo, \Doctrine_Record $data) {
        
    }

    public function createModelBo() {
        
    }

}
