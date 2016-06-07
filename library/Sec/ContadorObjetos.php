<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Classe que implementa Countable para implementação da paginação
 *
 * @author tiagolns
 */
class Sec_ContadorObjetos implements Countable
{
    private $objetos;
    public function __construct(array $objetos) 
    {
        $this->objetos = $objetos;
    }
    public function count() 
    {
        return count($this->objetos);
    }

//put your code here
}
