<?php

/**
 * BO da entidade Servidor
 */

class Aew_Model_Bo_Servidor extends Sec_Model_Bo_Abstract
{
    protected $idservidor,$pathservidor;
    
    /**
     * retorna os parametro do objeto em um array
     * onde a chave e o nome da variavel de instancia e tambem
     * o nome do campo na tabela
     * @return array
     */
    function toArray()
    {
        $data = parent::toArray();
        if($this->getPathservidor())
        {
            $data['pathservidor '] = $this->getPathservidor();
        }
        return $data;
    }
    
    /**
     * preenche o objeto com dados de uma array (de mapeamento chave-valor)
     * @param array $data
     */
    function exchangeArray($data)
    {
        parent::exchangeArray($data);
        $this->setPathservidor(isset($data['pathservidor'])?$data['pathservidor']:null);
    }
    
    /**
     * 
     * @return int
     */
    public function getIdservidor()
    {
        return $this->idservidor;
    }

    /**
     * @return string
     */
    public function getPathservidor()
    {
        return $this->pathservidor;
    }

    /**
     * @param int $idservidor
     */
    public function setIdservidor($idservidor)
    {
        $this->idservidor = $idservidor;
    }

    /**
     * 
     * @param string $pathservidor
     */
    public function setPathservidor($pathservidor)
    {
        $this->pathservidor = $pathservidor;
    }

    /**
     * cria objeto de acesso ao banco de dados
     * @return \Aew_Model_Dao_Servidor
     */
    protected function createDao() {
        $dao =  new Aew_Model_Dao_Servidor();
        return $dao;
    }

}