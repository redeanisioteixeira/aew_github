<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of OrdenadorPorData
 *
 * @author tiago-souza
 */
class Sec_OrdenadorPorData
{
    //put your code here
    private $ordenaveisPorData = array();
    private $dadosEstatisticos = array();
    function __construct(array $ordenaveisPorData)
    {
        $this->setOrdenaveisPorData($ordenaveisPorData);
    }
    
    public function getDadosEstatisticos()
    {
        return $this->dadosEstatisticos;
    }

    public function setDadosEstatisticos($dadosEstatisticos)
    {
        $this->dadosEstatisticos = $dadosEstatisticos;
    }

    public function getOrdenaveisPorData()
    {
        return $this->ordenaveisPorData;
    }

    public function setOrdenaveisPorData($ordenaveisPorData)
    {
        $this->ordenaveisPorData = $ordenaveisPorData;
    }
    
    /**
     * retorna objeto com data mais antiga
     * @return Sec_OrdenavelPorData
     */
    public function maisAntigo()
    {
        $maisAntigo = new  DateTime("today");
        $ordenavelMaisAntigo = null;
        foreach ($this->ordenaveisPorData as $ordenavel) 
        {
            $dataMaisAntigo = new DateTime($ordenavel->getData());
            if($dataMaisAntigo<$maisAntigo)
            {
                $maisAntigo = $dataMaisAntigo;
                $ordenavelMaisAntigo = $ordenavel;
            }
        }
        return $ordenavelMaisAntigo;
    }
    
    public function maisRecente()
    {
        $maisRecente = new  DateTime("1964-12-12");
        $ordenavelMaisRecente = null;
        foreach ($this->ordenaveisPorData as $ordenavel) 
        {
            $dataOrdenavel = new DateTime($ordenavel->getData());
            if($dataOrdenavel>$maisRecente)
            {
                $maisRecente = $dataOrdenavel;
                $ordenavelMaisRecente = $ordenavel;
            }
        }
        return $ordenavelMaisRecente;
    }
    
    /**
     * 
     * @return array
     */
    function agruparPorAno()
    {
        $grupos = array();
        for($i=0; $i<count($this->ordenaveisPorData); $i++) 
        {
            $dataOrdenavel = new DateTime($this->ordenaveisPorData[$i]->getData());
            $anoOrdenavel = $dataOrdenavel->format('Y'); 
            $grupos[$anoOrdenavel][$i] = $this->ordenaveisPorData[$i];
        }
        return $grupos;
    }
    
    function graficoConteudoOrdenavel(array $grupos)
    {
        $ano = "";
        $data = array();
        foreach($grupos as $anos => $ordenaveis)
        {
            
            foreach($ordenaveis as $ordenavel)
            {
                $ano = new DateTime($ordenavel->getData());
                break;
            }
            $data[$ano->format("Y")] = count($ordenaveis);
            
        }
        ksort($data);
        $this->setDadosEstatisticos($data);
    }
}