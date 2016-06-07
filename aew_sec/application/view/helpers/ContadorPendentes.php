<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Sec_View_Helper_MontarLink
 *
 * @author tiagolns
 */
class Sec_View_Helper_ContadorPendentes extends Zend_View_Helper_Abstract
{
    function ContadorPendentes($tipo)
    {
        $arrTipos = array('recados','colegas','comunidades','albuns','agenda','blog','online');
	$contador = 0;
	return '<span id="contador'.$tipo.'" class="contador text-center'.( $contador == 0 ? ' desativado' : '').'" tipo="'.$arrTipos[$tipo-1].'">0</span>';
    }
}