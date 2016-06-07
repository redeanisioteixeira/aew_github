<?php

/**
 * BO da entidade Tag
 */

class Aew_Model_Bo_FeedTipo extends Sec_Model_Bo_Abstract
{
    protected $id,$nomefeedtipo;
    
    /**
     * preenche o objeto com dados de uma array (de mapeamento chave-valor)
     * @param array $data
     */
    public function exchangeArray($data){
        parent::exchangeArray($data);
        $this->setId(isset($data['id'])? $data['id']: null);
        $this->setNomefeedtipo(isset($data['nomefeedtipo'])? $data['nomefeedtipo']: null);
    }

    /**
     * Constroi as tags para serem inseridas
     * @param string $tags
     * @return array
     */
    public function buildTags($tags)
    {
        $tags = explode(',', $tags);
        $result = array();
        foreach($tags as $tag){
        	$tagFinal = explode(';', $tag);
        	foreach($tagFinal as $tagFinalAux){
	            $tagFinalAux = trim($tagFinalAux);
	            $tagFinalAux = $this->create($tagFinalAux);
	            $result[] = $tagFinalAux;
        	}
        }
        return $result;
    }


    /**
     * 
     * @param int $id
     * @return array
     */
    public function retornaMensagem($id)
    {
        /* @var $dao Aew_Model_Dao_ConteudoDigital */
        $dao = $this->getDao();
        $result = $dao->retornaMensagem($id);
        return $result;
    }
     /**
     * Pega todos as tags para fazer nuvem de tags
     * @return Doctrine_Collection
     */
    public function getAllTagFromCloud(array $options = null)
    {
        /* @var $dao Aew_Model_Dao_ConteudoDigital */
        $dao = $this->getDao();
        $dataAtualizacao = new Sec_Date();
        $result = $dao->getAllTagFromCloud($dataAtualizacao->subDay(20)->toString(Sec_Date::DB_DATE), $options);
        return $result;
    }

    /**
     * Atualiza contador de busca da tag
     * @param $tag Tag
     * @return Tag
     */
    public function updateBuscaTag(Tag $tag)
    {
        $tag['busca'] = $tag['busca']+1;
        $dataUltimaBusca = new Sec_Date();
        $tag['dataAtualizacao'] = $dataUltimaBusca->toString(Sec_Date::DB_DATETIME);
        $tag->save();
        return $tag;
    }

    protected function createDao() {
        return new  Aew_Model_Dao_FeedTipo();
    }

}