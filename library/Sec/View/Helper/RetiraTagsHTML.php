<?php
class Sec_View_Helper_RetiraTagsHTML
{
    protected $view;

    public function setView($view)
    {
        $this->view = $view;
    }

    public function RetiraTagsHTML($str, $limpar = true, $tagsPermitidas = array(), $attrPermitidas = array())
    {
	    $allowedTags = null;
	    $allowedAttributes = null;

		if(!$limpar)
		{
	        $allowedTags = array('a','span', 'p');  //--- tags permitidas
	        $allowedAttributes = array('href');     //--- attributes permitidos

		    $allowedTags = array_merge($allowedTags, $tagsPermitidas);
		    $allowedAttributes = array_merge($allowedAttributes, $attrPermitidas);
		}

        $stripTags = new Zend_Filter_StripTags($allowedTags, $allowedAttributes, false);
        $sanitized = $stripTags->filter($str);

        return $sanitized;
    }
}
