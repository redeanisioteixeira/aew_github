<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UsuarioAmigo
 *
 * @author tiago-souza
 */
class Aew_Model_Dao_UsuarioAmigo extends Sec_Model_Dao_Abstract
{
    function __construct()
    {
        parent::__construct('usuarioamigo', 'idusuarioamigo');
    }
    protected function createModelBo()
    {
        return new Aew_Model_Bo_UsuarioAmigo();
    }
    
    function buildQuery(array $data, $num = 0, $offset = 0, $options = null)
    {
        $q = parent::buildQuery($data, $num, $offset, $options);
        $q->join('usuario', 'usuarioamigo.idusuario = usuario.idusuario');
        $q->joinLeft('usuario as usuarioindicou', 'usuarioamigo.idusuarioindicou = usuarioindicou.idusuario',array('idusuario as idusuarioindicou','nome as nomeusuarioindicou','flativo as flativousuarioindicou'));
        //echo $q; die();
        return $q;
    }

//put your code here
}
