<?php
/**
 * DAO da entidade MarcacaoAgenda
 */
class Aew_Model_Dao_FeedDetalhe extends Sec_Model_Dao_Abstract
{
   /**
    * Construtor
    */
    public function __construct()
    {
        parent::__construct('feeddetalhe','id');
    }

    public function selectFeedEspacoAberto(array $data,$num=0,$offset=0,$idfeed_min=0,$idfeed_max=0)
    {
        $where = "";
        if(isset($data["idusuarioremetente"])!=$data["idusuariodestinatario"])
        {
            $where .= " idfeedmensagem NOT IN(5,6,8,10,14,15,22) ";
        }
        else
        {
            $where .= " idfeedmensagem NOT IN(14,15) ";
        } 
        $limit = "";
        if($num)
        {
            $limit .= " limit ".$num;
            if($offset)
            $limit .= " offset ".$offset;
        }
        if($where)
        $where = " where ".$where;
        $where = "SELECT * FROM consultar_feed_espacoaberto(".$data["idusuariodestinatario"].", $idfeed_min, $idfeed_max) "
                . "inner join usuariofoto on usuariofoto.idusuario = idusuarioremetente "
                . " $where and mensagem IS NOT NULL AND mensagem NOT LIKE '%[%' ".$limit;
        return $this->createObjects($this->getAdapter()->query($where));
    }
    
    /**
     * @param string $filtro
     * @return array (Aew_Model_Bo_FeedDetalhe)
     */
    public function obtemResultado($filtro,$num=0,$pag=0)
    {   
        $limit = ""; $offset='';
        if($num)
        {
            $limit .= " limit ".$num;
            if($pag)
            $offset .= " offset ".$offset;
        }
        $where = "SELECT DISTINCT cb.*,CASE WHEN u.flativo IS NULL THEN TRUE ELSE u.flativo END FROM consulta_busca_conteudo('".$filtro."') AS cb LEFT JOIN usuario AS u ON(u.idusuario = cb.id AND cb.ordem = 1) ORDER BY 11,2 $limit $offset";
        
        echo $where;
        $resultado = $this->getAdapter()->query($where);
        $feeds = $this->createObjects($resultado);
	return $feeds;
    }

    public function createModelBo() 
    {
        return new Aew_Model_Bo_FeedDetalhe;
    }
}