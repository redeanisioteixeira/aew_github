<?php if(!count($this->comentarios)):?>
    <?php return;?>
<?php endif;?>

<div class="comentarios-lista">
    
    <div class="col-lg-12">
        <?php echo $this->paginationControl($this->comentarios,'Sliding','_componentes/_pagination_ajax.php', array('divContent' => '.comentarios-lista'));?>
    </div>
    
    <div class="col-lg-12">
        <!-- lista de comentarios topicos-->
        <ul class="media-list list-unstyled">
            <?php
                foreach($this->comentarios as $comentario):
                    $this->opacity = 0;
                    $this->comentario = $comentario;
                    echo $this->render('forum/comentario.php');
                endforeach;
            ?>
        </ul>
    </div>
    
    <div class="col-lg-12">
        <?php echo $this->paginationControl($this->comentarios,'Sliding','_componentes/_pagination_ajax.php', array('divContent' => '.'.$this->idDiv,'divUrl' => $this->urlPaginator));?>
    </div>
</div>