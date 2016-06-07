<?php
    if(!count($this->comunidadesPendentes)):
        return;
    endif;
?>

<div id="comunidades-pendentes" class="col-lg-12">
    <div class="box">
        <div class="col-lg-12">
            <?php echo $this->paginationControl($this->comunidadesPendentes,'Sliding','_componentes/_pagination_ajax.php', array('divContent' => '#comunidades-pendentes'));?>
        </div>

        <ul class="list-unstyled">
            <?php
                foreach ($this->comunidadesPendentes as $comunidade):
                    $comunidade->selectVotos();

                    $this->pendentes = true;
                    $this->comunidade = $comunidade;

                    echo $this->render('comunidade/comunidade.php');
                endforeach;
            ?>
        </ul>
    </div>
</div>