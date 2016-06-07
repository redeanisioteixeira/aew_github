<section class="box-blog">
    <header>
        <h3 class="headline-ea link-verde" role="titulo"><b><i class="fa fa-rss-square"></i> <?php echo $this->post->getTitulo();?></b></h3>
    </header>    

    <article class="box">

        <h6 class="page-publisher"><small class="fa fa-calendar"> Publicado em <?php echo $this->SetupDate($this->post->getDataCriacao(),1);?></small></h6>


        <section class="box-body margin-top-30"><?php echo $this->post->getTexto() ?></section>
        <footer class="box-footer margin-top-30">



            <ul class="list-inline">
                <li><a class="" 
                             href="/espaco-aberto/blog/editar/<?php echo $this->perfilTipo.'/'.$this->perfilId ?>/id/<?php  echo $this->post->getId()?>"
                             >
                              <i class="fa fa-edit"></i> Editar
                    </a>
                </li>  
                <li>
                    <a class="" 
                       href="/espaco-aberto/blog/apagar/<?php echo $this->perfilTipo.'/'.$this->perfilId ?>/id/<?php  echo $this->post->getId()?>"
                       >
                        <i class="fa fa-trash"></i> Apagar
                    </a>
                </li>
            </ul>

        </footer>
    </article>
</section>

<!-- Formulario Comentários-->
<section class="box-comentarios">
    <header>
        <h4 class="headline-ea link-verde" role="titulo"><b><i class="fa fa-rss-square"></i> <?php echo $this->post->getTitulo();?></b></h34>
    </header>    
    <?php echo $this->form;?>
</section>
<!-- Listagem Comentários -->
<section class="margin-top-10">
<h3 class="text-center">Comentários</h3>
<?php if (count($this->comentarios) == 0): ?>
    <div class="box text-center ">
        <span class="text-danger">
            <i class="fa fa-exclamation"></i> 
            Nenhum comentário encontrado
        </span>
    </div>
<?php else : ?>
<?php foreach ($this->comentarios as $comentario): ?>    
    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-2">
        <figure class="thumbnail">
                    <a href="<?php $comentario->getUsuarioAutor()->getLinkPerfil() ?>">
                        <img class="img-circle" 
                             src="<?php echo  $comentario->getUsuarioAutor()
                                     ->getFotoPerfil()
                                     ->getFotoCache(Aew_Model_Bo_Foto::$FOTO_CACHE_134X134,
                                     false, 30, 30, true) ?>" 
                             alt="Imagem usuário" width="30" height="30">
                    </a>
        </figure>
    </div>
    <div class="col-lg-11 col-md-11 col-sm-11 col-sm-11">
        <div class="panel panel-default">
            <div class="panel-heading arrow-left">
                <strong class="text-uppercase clearfix">
                    <?php echo $comentario->getUsuarioAutor()->getNome(); ?>
                </strong> 
                <span class="text-muted">
                    <?php echo $comentario->getDataCriacao() ?>
                </span>
            </div>
            <div class="panel-body">
                <?php echo $comentario->getComentario(); ?>
                <hr>
                <a class="btn btn-default pull-right"
                   title="Apagar comentário"
                   data-toggle="tooltip"
                   data-placement="auto"
                   href="<?php echo  $comentario->getUrlApagar($this->usuarioLogado); ?>"
                    >
                      <i class="fa fa-trash"></i>
                </a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>    
    <?php endif; ?>
</section>
    


