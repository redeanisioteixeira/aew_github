<?php if(isset($this->conteudo)):?>
    <div class="block-tags panel panel-default col-lg-12">
        <h4 class="headline" <?php echo $this->corfonte;?>><b>Tags</b></h4>
        <?php echo $this->showTags($this->conteudo->selectTags(),true);?>
    </div>
<?php endif;?>

<?php if(isset($this->relacionados)):?>
    <?php echo $this->render("relacionados/listar.php");?>
<?php endif;?>
