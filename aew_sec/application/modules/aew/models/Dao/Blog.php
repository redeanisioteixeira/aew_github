<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Aew_Model_Bo_Blog
 *
 * @author tiagolns
 */
abstract class Aew_Model_Dao_Blog extends Sec_Model_Dao_Abstract
{
    
    public function buildQuery(array $data, $num=0,$offset=0,$options=null) 
    {
        $q = parent::buildQuery($data, $num,$offset,$options);
        if(isset($data['titulo']))
        {
            $q->orWhere($this->getName().'.titulo = ?',$data['titulo'].'%');
        }
        if(isset($data['texto']))
        {
            $q->orWhere($this->getName().'.texto = ?',$data['texto'].'%');
        }
        return $q;
    }

}
