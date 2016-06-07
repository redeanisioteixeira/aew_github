<?php
class Sec_View_Helper_ReadMore
{
    protected $view;

    public function setView($view)
    {
        $this->view = $view;
    }

    public function readMore($str, $limite = 50, $ending = null , $marcarFiltro = null, $link = null, $cor = null, $legenda = null)
    {
        $ending  = (is_null($ending) ? '[...]' : $ending);
        $cor     = ($cor == null ? 'default' : $cor);
        $legenda = ($legenda == null ? 'Leia' : $legenda);

        $str = preg_split('/\s+/', strip_tags($str), -1, PREG_SPLIT_NO_EMPTY);

        $result_str = '';
        foreach ($str as $word)
        {
			$word = strip_tags($word);
			$word = htmlspecialchars_decode($word);

            if (strlen($word) > ($limite * 0.5))
            {
                $result_str .= substr($word, 0, $limite * 0.5) . $ending;
                break;
            }

            $result_str .= $word . ' ';
            if (strlen($result_str) >= $limite)
            {
                $result_str .= $ending;
                break;
            }
        }
        
        if($marcarFiltro)
        {
            $result_str = str_ireplace($marcarFiltro, "<b class='text-success'><u>$marcarFiltro</u></b>", $result_str);
        }
        
        if($link)
        {
            $result_str .= " <div class='clearfix margin-top-10 margin-bottom-10'><a class='pull-right btn btn-$cor btn-sm' href='$link' role='button'>$legenda <i class='fa fa-plus-circle fa-1x'></i></a></div>";
        }
        
        return $result_str;

    }
}
