<?php

/**
 * BO da entidade ComuRelacionada
 */

class Aew_Model_Bo_ComuRelacionada extends Aew_Model_Bo_Comunidade
{
    protected $idcomunidaderelacionada; //int(11)
    protected $idcomunidade; //int(11)
    

    /**
     * insere array de membros bloqueados na variavel local
     * @param array $membrosBloqueados
     */
    public function exchangeArray($data){
        parent::exchangeArray($data);
        $this->setIdcomunidaderelacionada(isset($data['idcomunidaderelacionada'])? $data['idcomunidaderelacionada']: null);
        $this->setIdcomunidade(isset($data['idcomunidade'])? $data['idcomunidade']: null);
    }
    
    /**
     * 
     * @return int
     */
    public function getIdcomunidaderelacionada()
    {
        return $this->idcomunidaderelacionada;
    }

    /**
     * 
     * @param int $idcomunidaderelacionada
     */
    public function setIdcomunidaderelacionada($idcomunidaderelacionada)
    {
        $this->idcomunidaderelacionada = $idcomunidaderelacionada;
    }

    /**
     * @return idcomunidade - int(11)
     */
    public function getIdcomunidade()
    {
    	return $this->idcomunidade;
    }

	
    /**
     * @param Type: int(11)
     */
    public function setIdcomunidade($idcomunidade){
	$this->idcomunidade = $idcomunidade;
    }
    
    /**
     * url para o perfiul da comunidade
     * @return string
     */
    public function getLinkPerfil() {
        return '/espaco-aberto/comunidade/exibir/comunidade/'.$this->getIdcomunidade();
    }

    /**
     * cria objeto de acesso ao banco de dados
     * @return \Aew_Model_Dao_ComuRelacionada
     */
    protected function createDao() 
    {
        return new Aew_Model_Dao_ComuRelacionada();
    }
}