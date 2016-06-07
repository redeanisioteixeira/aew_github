<div class="col-lg-12">
    <div class="box">
        <?php echo $this->editar;?>
    </div>

    <?php $this->removerRelacao = true;?>

    <?php if(count($this->comunidadesRelacionadas)):?>
        <div class="col-lg-12">
            <div class="row">
                <header>
                    <h4 class="headline-ea link-verde"><b><i class="fa fa-comments-o"></i> Comunidades relacionadas</b></h4>
                </header>

                <div class="box">
                    <?php echo $this->render('comunidade/listar-comunidades-relacionadas.php');?>
                </div>
            </div>
        </div>
    <?php endif;?>

    <?php $this->removerRelacao = false;?>
    <?php echo $this->render('comunidade/relacionar.php');?>
</div>