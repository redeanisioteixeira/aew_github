<?php
/**
 * Description of Convertem
 *
 * @author tiago-souza
 */
class Aew_View_Helper_AbreviaNome
{
    //put your code here
    function AbreviaNome($nomeCompleto, $cont)
    {
        if(strpos($this->escape($nomeCompleto), ' ') != false)
        {
            $nome = substr($this->escape($nomeCompleto), 0, strpos($this->escape($nomeCompleto), ' '));
        } 
        else 
        {
            $nome = $this->escape($nomeCompleto);
        }
        return $nome;
    }
}
