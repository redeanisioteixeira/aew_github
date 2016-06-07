<?php 
    if($this->lastItemNumber == 0)
	{
        return;
    }

    $view = new Zend_View();
    $ajax = new Zend_Controller_Request_Http();
    if(!$ajax->isXmlHttpRequest())
    {
        $view->headScript()->appendFile('/assets/js/pagination_ajax.js');
    }
    else
    {
        echo $view->headScript()->setFile('/assets/js/pagination_ajax.js');
    }
    
    $divContent = $this->divContent;
    
    $url = $this->url();
    if(isset($this->divUrl)){
        $url = $this->divUrl;
    }

    $request = Zend_Controller_Front::getInstance();
    if(isset($this->topicos) || $request->getRequest()->getParam('topicos'))
    {
        $url .= "/topicos/1";
    }

    if(strpos($url,"pagina")){
        $url = substr($url,0,strpos($url,"pagina")-1);
    }
    
    $url .=  "/pagina/";
    $method = (isset($this->params) ? "post" : "load");
?>

<div class="bar-pagination <?php echo (count($this->pagesInRange) > 1 ? 'text-center' : 'pull-right');?>">
    
    <?php if(isset($this->params)):?>
        <?php foreach($this->params as $key => $value):?>
            <?php if($key != $value && $key != 'module' && $key != 'controller' && $key != 'action' && !empty($value)):?>
                <input class="parametros" type="hidden" name="<?php echo $key;?>" value="<?php echo $value;?>">
            <?php endif;?>
        <?php endforeach;?>
    <?php endif;?>
                
    <ul class="pagination">
        
        <!-- First page link -->
        <?php if(count($this->pagesInRange) > 1):?>
            <?php if ($this->current != $this->first):?>
                <li class="active"><a class="item-pagination fa fa-angle-double-left cursor-pointer" metodo="<?php echo $method;?>" bloco="<?php echo $this->divContent;?>" pagina="<?php echo $this->first;?>" url="<?php echo $url.$this->first;?>" onclick="pagination(this)"></a></li>
            <?php else:?>
                <li class="disabled hidden-sm hidden-xs"><span class="fa fa-angle-double-left"></span></li>
            <?php endif;?>
        <?php endif;?>

        <!-- Previous page link -->
        <?php if(isset($this->previous)):?>
            <li class="active"><a class="item-pagination fa fa-angle-left cursor-pointer" metodo="<?php echo $method;?>" bloco="<?php echo $this->divContent;?>" pagina="<?php echo $this->previous;?>" url="<?php echo $url.$this->previous;?>" onclick="pagination(this)"></a></li>
        <?php else: ?>
            <li class="disabled"><span class="fa fa-angle-left"></span></li>
        <?php endif;?>

        <!-- Numbered page links -->
        <?php if(count($this->pagesInRange) > 1):?>
            <?php foreach ($this->pagesInRange as $page):?>
                <?php if ($page != $this->current): ?>
                    <li class="hidden-sm hidden-xs active"><a class="item-pagination cursor-pointer" metodo="<?php echo $method;?>" bloco="<?php echo $this->divContent;?>" pagina="<?php echo $page;?>" url="<?php echo $url.$page;?>" onclick="pagination(this)"><b><?php echo $page;?></b></a></li>
                <?php else:?>
                    <li class="disabled"><span><b><?php echo $page;?></b></span></li>
                <?php endif;?>
            <?php endforeach;?>
        <?php endif;?>

        <!-- Next page link -->
        <?php if(isset($this->next)):?>
            <li class="active"><a class="item-pagination fa fa-angle-right cursor-pointer" metodo="<?php echo $method;?>" bloco="<?php echo $this->divContent;?>" pagina="<?php echo $this->next;?>" url="<?php echo $url.$this->next;?>" onclick="pagination(this)"></a></li>
        <?php else:?>
            <li class="disabled"><span class="fa fa-angle-right"></span></li>
        <?php endif;?>

        <!-- Last page link -->
        <?php if(count($this->pagesInRange) > 1):?>
            <?php if($this->current != $this->last):?>
                <li class="active"><a class="item-pagination fa fa-angle-double-right cursor-pointer" metodo="<?php echo $method;?>" bloco="<?php echo $this->divContent;?>"  pagina="<?php echo $this->last;?>" url="<?php echo $url.$this->last;?>" onclick="pagination(this)"></a></li>
            <?php else: ?>
                <li class="disabled hidden-sm hidden-xs"><span class="fa fa-angle-double-right"></span></li>
            <?php endif;?>
        <?php endif;?>

        <?php if(count($this->pagesInRange) > 0):?>
                <li class="disabled"><span><b><?php echo $this->firstItemNumber.' - '.$this->lastItemNumber.' de '.$this->totalItemCount;?><i class="carregando desativado link-preto margin-left-05 fa fa-refresh fa-spin"></i></b></span></li>
        <?php endif;?>
    </ul>
</div>
