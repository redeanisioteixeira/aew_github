<?php
/**
 * BO da entidade Canal
 *
 * @author tiago
 */
class Aew_Model_Bo_Canal extends Sec_Model_Bo_Abstract
{
    protected $nomecanal;
    protected $categorias = array();
    
    function getNomecanal() {
        return $this->nomecanal;
    }

    /**
     * 
     * @return array
     */
    function getCategorias() {
        return $this->categorias;
    }

    /**
     * 
     * @param array $categorias
     */
    function setCategorias($categorias) {
        $this->categorias = $categorias;
    }

    /**
     * 
     * @param string $nomecanal
     */
    function setNomecanal($nomecanal) {
        $this->nomecanal = $nomecanal;
    }
    
    /**
     * @param type $num
     * @param type $offset
     * @param Aew_Model_Bo_ConteudoDigitalCategoria $categoria
     * @param array $options
     * @return type
     */
    function selectCategoriasConteudo($num=0,$offset=0,Aew_Model_Bo_ConteudoDigitalCategoria $categoria=null,array $options = null)
    {
        if(!$this->getId())
            return array();
        if(!$categoria)
            $categoria = new Aew_Model_Bo_ConteudoDigitalCategoria();
        $categoria->setCanal($this);
        $this->setCategorias($categoria->select($num, $offset, $options));
        return $this->getCategorias();
    }

    /**
     * cria objeto de acesso ao banco de dados
     * @return \Aew_Model_Dao_Canal
     */
    protected function createDao() {
            
        return new Aew_Model_Dao_Canal();
    }

}