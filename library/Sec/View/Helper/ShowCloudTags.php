<?php

class Sec_View_Helper_ShowCloudTags
{
    protected $view;

    public function setView($view)
    {
    	$this->view = $view;
    }

    public function showCloudTags()
    {
		$div = "";
		    
		$tagBo = new Aew_Model_Bo_Tag();

		$options = array();  
		$options['where']['tag.busca > ?'] = 0;
		$options['orderBy']['tag.busca DESC'] = '';
		$options['orderBy']['tag.nomebusca ASC'] = '';

		$tags_array = $tagBo->select(43, 0, $options);
		    
		if(!count($tags_array)):
		    return;
		endif;

		$ids = array();
		$tags = array();
		$sizes = array();
		    
		foreach($tags_array as $tag):
			array_push($ids, $tag->getId());	
		    array_push($tags, $tag->getNome());	
		    array_push($sizes, $tag->getBusca());
		endforeach;

        $ids = array_combine($ids, $tags);
        $tags = array_combine($tags, $sizes);

        $tags = $this->tag_cloud($tags);

        $div = "<div id='tags-cloud' class='col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-bottom-10'><ul class='nav navbar-nav'>";
        foreach ($tags as $tag => $percent):
			$id = array_keys($ids, $tag);

            $link_busca = $this->view->url(array('module' => 'conteudos-digitais', 'controller' => 'conteudos','action' => 'listar', 'busca' => $tag), null, true);
            $link_busca = $this->view->url(array('module' => 'conteudos-digitais', 'controller' => 'conteudos','action' => 'tags', 'id' => $id[0]), null, true);

            $percent = round(($percent * 12)/1200,1); 

            $tag = strtolower($tag);
            $div .= "<li><a  style='font-size:{$percent}em;' href='$link_busca'>$tag</a></li>";

        endforeach;
        
        $div .= "</ul></div>";

        return $div;
    }

    public function tag_cloud($words, $min = 0.8, $max = 2.8)
    {
		$maior = $words;
 
		arsort($words); // ordena por rank
		$high = array_shift($maior); // encontra item de maior rank

		$diff = $max - $min;

	    // Calcula o tamanho das tags
		foreach ($words as $word => &$rank):
        	$rank = number_format(((($rank * $diff) / $high) + $min) * 100, 2);
		endforeach;

		ksort($words);

		return $words;
	}
}
