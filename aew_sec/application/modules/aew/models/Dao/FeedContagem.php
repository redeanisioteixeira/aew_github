<?php
/**
* DAO da entidade feedcontagem
*/
class Aew_Model_Dao_FeedContagem extends Sec_Model_Dao_Abstract
{
    function __construct() 
    {
        parent::__construct('feedcontagem','idfeedcontagem');
    }
    
    /**
     * 
     * @return array
     */
    public function obtemUsuariosOnline()
    {
        $query = "SELECT COUNT(*) AS conectados FROM feedcontagem WHERE flacesso IS TRUE AND dataacesso >= (CURRENT_TIMESTAMP - INTERVAL'1 days')";
	$obj = $this->getAdapter()->query($query);
        return $this->createObjects($obj);
    }
    
    /**
     * 
     * @param array $data
     * @param type $num
     * @param type $offset
     * @param type $options
     * @return type
     */
    function buildQuery(array $data, $num = 0, $offset = 0, $options = null) 
    {
        $q = parent::buildQuery($data, $num, $offset, $options);
        $q->joinleft('dispositivo', 'dispositivo.iddispositivo = feedcontagem.iddispositivo');
        
        return $q;
    }

    /**
     * cria BO da entidade FeedContagem
     * @return \Aew_Model_Bo_FeedContagem
     */
    public function createModelBo() 
    {
        return new Aew_Model_Bo_FeedContagem();
    }
}