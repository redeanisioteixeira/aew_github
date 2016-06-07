<?php
/**
 * BO da entidade Categoria Componente Curricular
 */
class Aew_Model_Bo_CategoriaComponenteCurricular extends Sec_Model_Bo_Abstract
{
    protected $nomecategoriacomponentecurricular; //varchar(250)
    protected $componentesCurriculares = array();
    
    /**
     * Construtor
     */
    public function __construct()
    {
        $this->setDao(new Aew_Model_Dao_CategoriaComponenteCurricular());
    }
    
    /**
     * 
     * @return string
     */
    public function getNome()
    {
        return $this->nomecategoriacomponentecurricular;
    }

    /**
     * 
     * @param string $nome
     */
    public function setNome($nome)
    {
        $this->nomecategoriacomponentecurricular = $nome;
    }

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
     * sleciona os componetes curriculares desta categoria
     * @param int $num
     * @param int $offset
     * @param Aew_Model_Bo_ComponenteCurricular $componente
     * @return type
     */
    public function selectComponentesCurriculares($num=0,$offset = 0, Aew_Model_Bo_ComponenteCurricular $componente = null, $options = null)
    {
        if(!$componente)
        {
            $componente = new Aew_Model_Bo_ComponenteCurricular();
        }
        $componente->setCategoriaComponenteCurricular($this);
        $this->setComponentesCurriculares($componente->select($num, $offset, $options));
        return $this->getComponentesCurriculares();
    }

    /**
     * cria objeto de acesso ao banco de dados
     * @return \Aew_Model_Dao_CategoriaComponenteCurricular
     */
    protected function createDao() {
        $dao =  new Aew_Model_Dao_CategoriaComponenteCurricular();
        return $dao;
    }

}