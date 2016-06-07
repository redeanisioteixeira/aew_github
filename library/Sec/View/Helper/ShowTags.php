<?php

class Sec_View_Helper_ShowTags
{
    protected $view;

    public function setView($view)
    {
        $this->view = $view;
    }

    public function showTags($tags, $link = false, $large = 0, $cor = '')
    {	
        if(!$cor):
            $cor = $this->view->corfundo;

            switch($this->view->getModule()):
                case "espaco-aberto":
                        $cor = "success";
                        break;
                
                case "tv-anisio-teixeira":
                        $cor = "warning";
                        break;

                case "ambientes-de-apoio":
                        $cor = "warning";
                        break;

                case "conteudos-digitais":
                        if(strpos($cor, "marron")):
                            $cor = "warning";
                        else:
                            $cor = (strpos($cor, "vermelho") ? "danger" : "info");
                        endif;
                        break;
                case "sites-tematicos":
                        $cor = "danger";
                        break;

            endswitch;
        endif;
        
        $total_large = 0;
        $result = "";
        $reticence = false;	

        if($tags == null || count($tags) == 0):
            return $result;
        endif;

        foreach($tags as $tag):


            if($this->view->getModule() == "tv-anisio-teixeira"):
				$url_tag  = $this->view->url(array('module' => 'tv-anisio-teixeira', 'controller' => 'programas','action' => 'tags', 'id' => $tag->getId()), null, true);
            elseif($this->view->getModule() == "ambientes-de-apoio"):
					$url_tag  = $this->view->url(array('module' => 'ambientes-de-apoio', 'controller' => 'ambientes','action' => 'tags', 'id' => $tag->getId()), null, true);
                else:        
                    $url_tag  = $this->view->url(array('module' => 'conteudos-digitais', 'controller' => 'conteudos','action' => 'listar', 'busca' => $tag->getNome()), null, true);
                endif;

            $href = ($link == true ? "href='$url_tag'" : "");
            $nome_tag = "<a class='keyword btn btn-$cor btn-xs ".($link == false ? "cursor-normal" : "")."' $href>".$tag->getNome()."</a>";

            $total_large += strlen($tag->getNome());
            if ($large > $total_large || $large == 0):
                $result .= "<li>$nome_tag</li>";
            else:
                $reticence = true;
            endif;

        endforeach;

        $result = "<ul class='tags list-inline list-unstyled ".($reticence == true ? "reticense":"")."'>$result</ul>";

        return $result;
    }
}
