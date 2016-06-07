<?php
/**
 * Description of ConteudoDigitalCategoria
 *
 * @author tiago-souza
 */
class Aew_Model_Dao_ConteudoDigitalCategoria extends Sec_Model_Dao_Abstract
{
    function __construct()
    {
        parent::__construct("conteudodigitalcategoria", "idconteudodigitalcategoria");
    }
    
    function buildQuery(array $data, $num = 0, $offset = 0, $options = null) {
        $q = parent::buildQuery($data, $num, $offset, $options);
        $q->joinLeft('canal', 'canal.idcanal='.$this->getName().'.idcanal');
        return $q;
    }
    
    
    function selectAvgAttr($data,$attr="*")
    {
        $sql = "SELECT avg($attr) FROM conteudodigitalcategoria 
                join conteudodigital on conteudodigital.idconteudodigitalcategoria = conteudodigitalcategoria.idconteudodigitalcategoria
                WHERE (conteudodigital.idconteudodigitalcategoria in (
                select idconteudodigitalcategoria from conteudodigitalcategoria where idconteudodigitalcategoriapai = ".$data['idconteudodigitalcategoria']." or idconteudodigitalcategoria=".$data['idconteudodigitalcategoria']."))";
        $result = $this->getAdapter()->query($sql);
        foreach($result as $data)
        {
            return $data['avg'];
        }
    }
            
    function selectSumaAttr($data,$attr="*")
    {
        $sql = "SELECT sum($attr) FROM conteudodigitalcategoria 
                join conteudodigital on conteudodigital.idconteudodigitalcategoria = conteudodigitalcategoria.idconteudodigitalcategoria
                WHERE (conteudodigital.idconteudodigitalcategoria in (
                select idconteudodigitalcategoria from conteudodigitalcategoria where idconteudodigitalcategoriapai = ".$data['idconteudodigitalcategoria']." or idconteudodigitalcategoria=".$data['idconteudodigitalcategoria']."))";
        $result = $this->getAdapter()->query($sql);
        foreach($result as $data)
        {
            return $data['sum'];
        }
    }

    //put your code here
    protected function createModelBo()
    {
        return new Aew_Model_Bo_ConteudoDigitalCategoria();
    }
}