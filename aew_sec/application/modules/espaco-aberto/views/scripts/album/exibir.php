<header class="box">
    <h4 class="link-verde">
        <?php echo $this->escape($this->album->getTitulo()); ?>
    </h4>
    <span class="text-muted">
        Publicado em <?php  $this->album->getDataCriacao() ?>
    </span>
    <a class="btn btn-success btn-circle-sm pull-right"
        href="<?php echo $this->album->getUrlAdicionarFoto($this->usuarioPerfil)?>"
        data-toggle="tooltip"
        data-placement="top"
        title="Adicionar Foto"
    >
        <i class="fa fa-plus"></i>
    </a>
</header>

<section class="box">
    <div id="gallery" class="gallery load-scroll" rel="/espaco-aberto/album/lista-fotos/id/<?php echo $this->album->getId()?>">
        <!-- lista thumnails e ações -->
        <?php echo $this->render('album/lista-fotos.php') ?>
    </div>  
</section>
    
<!-- Formulario -->
<section class="box load-content-form" idloadcontainer="conteudo-comentarios" type-action="html-action">
    <h4 class="text-center">Deixe seu comentário</h4>
    <?php echo $this->albumComentario;?>
</section>

<?php  echo $this->render('/usuario/box-list-comentarios.php');?>
<div class="modal fade modal-gallery" tabindex="-1" role="dialog" aria-labelledby="info" style="z-index: 999999;">
    <div class="modal-dialog modal-lg" style="width: 80%;">
        <div class="modal-content">
            <div class="modal-body">
            
            </div>    
        </div>
    </div>
</div>