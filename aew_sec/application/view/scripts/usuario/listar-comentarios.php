<?php if(!$this->idDiv){ $this->idDiv = 'conteudo-comentarios';}?>
<div class="col-lg-12 headline margin-bottom-10">
    <div class="row">
        <span class='quantidade'><b><?php echo $this->comentarios->getTotalItemCount();?> comentário(s)</b></span>
    </div>
</div>

<div class="col-lg-12">
    <?php echo $this->showMessages();?>
</div>


<div class="col-lg-12">
    <?php echo $this->paginationControl($this->comentarios,'Sliding','_componentes/_pagination_ajax.php', array('divContent' => '#'.$this->idDiv,'divUrl' => $this->urlPaginator));?>
</div>

<ul class="col-lg-12 list-unstyled">

    <?php foreach($this->comentarios as $this->comentario):?>
        <?php  echo $this->render('/usuario/comentario.php');?>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     
    <?php endforeach;?>

</ul>

<div class="col-lg-12">
    <?php echo $this->paginationControl($this->comentarios,'Sliding','_componentes/_pagination_ajax.php', array('divContent' => '#'.$this->idDiv,'divUrl' => $this->urlPaginator));?>
</div>