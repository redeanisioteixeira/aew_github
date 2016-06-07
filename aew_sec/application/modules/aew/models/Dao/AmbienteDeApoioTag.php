<?php

class Aew_Model_Dao_AmbienteDeApoioTag extends Sec_Model_Dao_Abstract
{

    function __construct()
    {
        parent::__construct('ambientedeapoiotag',array('idambientedeapoio',"idtag"));
    }

    /**
     * @param array $data
     * @param int $num
     * @param int $offset
     * @param type $options
     * @return type
     */
    function buildQuery(array $data, $num = 0, $offset = 0, $options = null)
    {
        $q = parent::buildQuery($data, $num, $offset, $options);
        $q->joinLeft('tag', 'tag.idtag = '.$this->getName().'.idtag');
        return $q;
    }
    
    /**
     * @return \Aew_Model_Bo_Ambientedeapoiotag
     */
    protected function createModelBo()
    {
        return new Aew_Model_Bo_AmbienteDeApoioTag();
    }
}