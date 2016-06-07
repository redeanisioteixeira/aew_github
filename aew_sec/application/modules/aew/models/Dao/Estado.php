<?php

/**
 * DAO da entidade Estado
 */

class Aew_Model_Dao_Estado extends Sec_Model_Dao_Abstract
{
    function __construct() 
    {
        parent::__construct('estado','idestado');
    }
    
    /***/
    public function exchangeArray($data){
        parent::exchangeArray($data);
        $this->setIdestado(isset($data['idestado'])? $data['idestado']: null);
        $this->setNome(isset($data['nomeestado'])? $data['nomeestado']: null);
        $this->setCodigoibgesiig(isset($data['codigoibgesiig'])? $data['codigoibgesiig']: null);
    }
    /**
     * Carrega a tabela de municipios
     */
    public function loadMunicipios()
    {
        set_time_limit(900);
        $estados = $this->getAll();

        foreach($estados as $estado){
            $this->loadMunicipiosByEstado($estado);
            unset($estado);
        }
        set_time_limit(60);
    }

    /**
     * Carrega a tabela de municipios por um estado
     * @param $estado
     */
    public function loadMunicipiosByEstado($estado)
    {
        $secWebService = new Sec_Service_Sec();
        $objetos = $secWebService->obterTodosMunicipiosPorEstado($estado['codigoIbgeSiig']);

        $munNum = 0;
        if($objetos != false){
            foreach($objetos as $objeto){
                if(is_object($objeto)){
                    $registro = $this->get($objeto->id);
                    if(false == $registro){
                        $munNum++;
                        $ob = new Municipio();
                        $ob['idMunicipio'] = ($objeto->codigoIbge != '') ? $objeto->codigoIbge : $objeto->id;
                        $ob['idEstado'] = $estado['idEstado'];
                        $ob['nome'] = ($objeto->nomeconteudodigitalcategoria != '') ? trim($objeto->nomeconteudodigitalcategoria) : 'Não definido';
                        $ob['codigoIbgeSiig'] = $objeto->id;
                        $ob->save();
                        unset($ob);
                    } else {
                        unset($registro);
                    }
                    unset($objeto);
                }
            }
            unset($objetos);
        }
        echo $munNum.' adicionados.';
        echo '<br/>';
    }

    /**
     * cria BO da entidade Estado
     * @return \Aew_Model_Bo_Estado
     */
    public function createModelBo() 
    {
        return new Aew_Model_Bo_Estado();
    }

}