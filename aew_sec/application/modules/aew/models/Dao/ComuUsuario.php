<?php
/**
 * DAO da entidade ComuUsuario
 */
class Aew_Model_Dao_ComuUsuario extends Sec_Model_Dao_Abstract
{
    function __construct()
    {
        parent::__construct('comuusuario','idcomuusuario');
    }
    
    /**
     * constroi o sql da consulta
     * @param array $data
     * @param int $num
     * @param int $offset
     * @param array|string $options
     * @return Zend_Db_Select
     */
    function buildQuery(array $data, $num = 0, $offset = 0, $options = null)
    {
        $q = parent::buildQuery($data, $num, $offset, $options);
        $q->join('comunidade', 'comunidade.flpendente = FALSE AND comunidade.ativa = TRUE AND comunidade.idcomunidade = '.$this->getName().'.idcomunidade',array('idusuario as idusuariodono','idcomunidade','descricao','nomecomunidade','qtdvisitas'));
        
        if(isset($data['idcomunidade']))
        {
            $q->join('usuario','usuario.idusuario = '.$this->getName().'.idusuario');
        }
        else 
        {
            $q->join('usuario','usuario.idusuario = comunidade.idusuario');
        }
        
        $q->joinLeft("usuariofoto", "usuariofoto.idusuario = usuario.idusuario",array('idusuariofoto','extensao'));
        $q->joinLeft('comunidadefoto', 'comunidadefoto.idcomunidade = '.$this->getName().'.idcomunidade',array('idcomunidadefoto','extensao'));
        
        return $q;
    }
    
    /**
     * cria entidade BO
     * @return \Aew_Model_Bo_ComuUsuario
     */
    public function createModelBo() 
    {
        return new Aew_Model_Bo_ComuUsuario();
    }
}
