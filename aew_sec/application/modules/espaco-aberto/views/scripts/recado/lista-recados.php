<?php if(!count($this->recados)):?>
    <?php return;?>
<?php endif;?>

<div id="recados-lista">
    
    <div class="col-lg-12">
        <?php echo $this->paginationControl($this->recados,'Sliding','_componentes/_pagination_ajax.php', array('divContent' => '#'.$this->idDiv,'divUrl' => $this->urlPaginator));?>
    </div>
    
    <div class="col-lg-12">
        <!-- lista de recados -->
        <ul class="media-list list-unstyled">
            <?php
                foreach($this->recados as $recado):
                    $this->opacity = 0;
                    $this->recado = $recado;
                    echo $this->render('recado/recado-usuario.php');
                endforeach;
            ?>
        </ul>
    </div>
    
    <div class="col-lg-12">
        <?php echo $this->paginationControl($this->recados,'Sliding','_componentes/_pagination_ajax.php', array('divContent' => '#'.$this->idDiv,'divUrl' => $this->urlPaginator));?>
    </div>
</div>