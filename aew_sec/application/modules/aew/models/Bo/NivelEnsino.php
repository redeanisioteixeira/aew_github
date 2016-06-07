<?php
/**
 * BO da entidade Usuario Tipo
 */
class Aew_Model_Bo_NivelEnsino extends Sec_Model_Bo_Abstract
{
    protected $nomenivelensino,$componentesCurriculares=array();
    static $ENSINO_MEDIO = 5,$EDUCACAO_ESCOLAR_INDIGINA=11,$EDUCACAO_JOVENS_ADULTOS_1=9,$EDUCACAO_JOVENS_ADULTOS_2 = 10,
           $ENSINO_FUNDAMENTAL_INICIAL = 3, $ENSINO_FUNDAMENTAL_FINAL=4,$EDUCAOCAO_PROFISSIONAL=7,$ENSINO_SUPERIOR=8,
           $EDUCACAO_CAMPO=12,$EDUCACAO_ESPECIAL=13;
    
    /**
     * 
     * @return array
     */
    public function getComponentesCurriculares()
    {
        return $this->componentesCurriculares;
    }

    /**
     * 
     * @param array $componentesCurriculares
     */
    public function setComponentesCurriculares($componentesCurriculares)
    {
        $this->componentesCurriculares = $componentesCurriculares;
    }

    /**
     * 
     * @return string
     */
    public function getNome() {
        return $this->nomenivelensino;
    }

    /**
     * 
     * @param string $nome
     */
    public function setNome($nome) {
        $this->nomenivelensino = $nome;
    }
    
    /**
     * seleciona componentes curriculares no banco de dados
     * @param type $num
     * @param type $offset
     * @param Aew_Model_Bo_ComponenteCurricular $componente
     * @param type $options
     * @return type
     */
    function selectComponentesCurriculares($num=0,$offset=0,Aew_Model_Bo_ComponenteCurricular $componente = null, $options = null)
    {
        if(!$this->getId())
            return null;
        
        if(!$componente)
        $componente = new Aew_Model_Bo_ComponenteCurricular();
        $componente->setNivelEnsino($this);
        $this->setComponentesCurriculares($componente->select($num, $offset, $options));
        return $this->getComponentesCurriculares();
    }   
    
    /**
     * cria objeto de acesso ao banco de dados
     * @return \Aew_Model_Dao_NivelEnsino
     */
    protected function createDao() {
        $dao =  new Aew_Model_Dao_NivelEnsino();
        return $dao;
    }

}