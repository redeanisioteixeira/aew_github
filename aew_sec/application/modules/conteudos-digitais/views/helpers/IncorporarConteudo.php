<?php

/**
 * Description of IncorporarConteudo
 *
 * @author tiago-souza
 */
class Zend_View_Helper_IncorporarConteudo
{
    //put your code here
    function incorporarConteudo(Aew_Model_Bo_ConteudoDigital $conteudo)
    {
        $tipoVisualizacao = $this->tipoVisualizacao($conteudo);
        $classe_espacoAberto = "";
        $tipoEmbed =  0;

        if(array_key_exists('HTTP_REFERER',$_SERVER)):
            if(strpos($_SERVER['HTTP_REFERER'],'espaco-aberto')):	
		$classe_espacoAberto = "class='espaco-aberto'";
            endif;
        endif;
        $width  = '98%';
        $height = '90%';

   }
   
   function conteudoYouTube()
   {
       
   }
}
