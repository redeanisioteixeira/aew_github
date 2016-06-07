<div class="col-lg-12">
    <div class="row">
        <?php if(!$this->usuarioPerfil->isDonoPerfil($this->usuarioLogado)):?>
            <div class="box">
                <?php echo $this->form_recado;?>
            </div>
        <?php endif;?>

        <?php echo $this->render('recado/lista-recados.php');?>
    </div>
</div>