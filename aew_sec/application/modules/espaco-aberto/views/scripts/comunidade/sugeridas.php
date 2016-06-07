<?php
    if(!count($this->solicitacoes)):
        return;
    endif;
?>

<div id="comunidades-sugeridas" class="col-lg-12">
    <div class="box">
        <div class="col-lg-12">
            <?php echo $this->paginationControl($this->solicitacoes,'Sliding','_componentes/_pagination_ajax.php', array('divContent' => '#comunidades-sugeridas'));?>
        </div>

        <ul class="list-unstyled">
            <?php
                foreach ($this->solicitacoes as $comunidade):
                    $comunidade->selectVotos();

                    $this->sugeridas = true;
                    $this->comunidade = $comunidade;
                    echo $this->render('comunidade/comunidade.php');
                endforeach;
            ?>
        </ul>
    </div>
</div>