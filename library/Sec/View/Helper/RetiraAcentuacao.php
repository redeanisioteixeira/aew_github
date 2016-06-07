<?php
class Sec_View_Helper_RetiraAcentuacao extends Zend_View_Helper_Abstract
{
    public function RetiraAcentuacao($palavra, $separador = false)
    {
        $a = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
        $b = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';

        $string = utf8_decode($palavra);

        //substitui letras acentuadas por "normais"
        $string = strtr($string, utf8_decode($a), $b);

        // passa tudo para minusculo
        $string = strtolower($string);

        $string = ($separador ? str_replace(" ","-", $string) : $string);
        return utf8_encode($string);
    }
}
