<?php

/**
 * Helper utilizado para retornar nome do tipo de visualizaçao
 * a ser apresentado na view do conteudo digital
 */
class Zend_View_Helper_TipoVisualizacao extends Zend_View_Helper_Abstract
{
    public function tipoVisualizacao(Aew_Model_Bo_ConteudoDigital $conteudo)
    {
	$arr_extensaovalida = array(1 => 'mp4', 2 => 'flv', 3 => 'webm', 4 => 'aac', 5 => 'mp3', 6 => 'vorbis');

	$formato = $conteudo->getFormato()->getNome();
	if($formato == "")
        {
            if(array_search($conteudo->getFormatoDownload()->getNome(), $arr_extensaovalida))
            {
		$formato = $conteudo->getFormatoDownload()->getNome();
            }    
        }
	switch($formato)
        {
            case "flv":
            case "mp4":	$tipo = "video";   	break;
            case "mp3":	$tipo = "sound";	break;
            case "jpg":
            case "gif":
            case "png": $tipo = "image"; 	break;
            case "swf":	$tipo = 'swf';		break;
            case "youtube":$tipo = 'youtube';	break;
            default: $tipo = null;
            if($conteudo->getSite() != "")
            {
                $arr_site = array('tvescola' => 'tvescola.mec.gov.br','irdeb' => 'irdeb.ba.gov.br', 'dominiopublico' => 'dominiopublico.gov.br');
		foreach($arr_site as $key => $value)
                {
                    $pos = strpos($conteudo->getSite(),$value);
                    if($pos)
                    {
                        $tipo = $this->retiraAcentuacao(strtolower($conteudo->getFormato()->getConteudoTipo()->getNome()));
			$tipo .= "-$key";
			if($key == 'dominiopublico')
                        {
                            $formato = strtolower(end(explode(".",$conteudo->getSite())));
                            if(array_search($formato, $arr_extensaovalida) == false)
                            {
				$tipo = "";
                            }    
                        }
			break;
                    }    
                }
            }
            break;
        }
	return $tipo;
    }

    public function retiraAcentuacao($palavra)
    {
	$a = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
	$b = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
	$string = utf8_decode($palavra);
	$string = strtr($string, utf8_decode($a), $b); //substitui letras acentuadas por "normais"
	$string = strtolower($string); // passa tudo para minusculo
	return utf8_encode($string);
    }
}
