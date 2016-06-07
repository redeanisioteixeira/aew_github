<?php if(!count($this->feeds)):?>
    <?php return;?>
<?php endif;?>

<section class="feed-usuario">
    
    <div class="text-center hidden" onClick="carregaFeed(1,'/home/carrega-feed/usuario/<?php echo $this->usuarioPerfil->getId()?>');">
        <a href="#" class="btn btn-success btn-circle-sm">
            <i class="fa fa-refresh fa-spin"></i> 
        </a>
    </div>
    
    <ul class="media-list load-scroll" rel="/espaco-aberto/perfil/feed-listar" type-action='append-action' id="lista-feed">
        <?php echo  $this->render("perfil/feed-listar.php")?>
    </ul>
    
</section>