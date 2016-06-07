<div id="box-comentarios" class="panel panel-default">
    <div class="panel-body">
        <?php echo $this->render('/usuario/box-form-comentarios.php');?>
        <div id="conteudo-comentarios">
            <?php echo $this->render('/usuario/listar-comentarios.php');?>
        </div>
    </div>
</div>