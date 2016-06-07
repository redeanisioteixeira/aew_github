<div class="media box">
    <!-- Imagem, estrelas e açoes -->    
    <figure class="media-left">
        <a href="<?php echo $this->blog->getLinkPerfil() ?>"> 
             <!-- Chama foto Default ou do post -->
             <img class="thumbnail-user" 
                  src="/assets/img/icones_apc/10.png" 
                  style="background-color: rgba(92, 184, 92, 0.7);"
                  >
        </a>
    </figure>
    <section class="media-body">
            <!-- Titulo do Post e data publicação-->
            <header class="media-heading">
                <h3 class="headline-ea">
                    <a href="<?php echo $this->blog->getLinkPerfil() ?>"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="Leia a postagem completa"
                        >
                        <?php echo $this->blog->getTitulo() ?>
                    </a>
                </h3>
                <span class="details pull-right">Publicado <?php echo $this->SetupDate($this->blog->getDataCriacao(), 1); ?></span>
            </header> 
            <section class="media-middle padding-top-20">
                <!-- conteúdo do post -->
                <span class="break-word text-justify "><?php echo $this->readMore($this->blog->getTexto(), 400) ?></span>
                <hr>
                <div class="pull-right ">
                    <a class="btn btn-success btn-sm" 
                       href="<?php echo $this->blog->getLinkPerfil() ?>" 
                       data-toggle="tooltip" 
                       data-placement="top" 
                       data-original-title="Leia a postagem completa"
                       title="Leia mais"
                       >
                        Leia Mais
                    </a>
                   
                    
                    <a class="btn btn-primary btn-sm" 
                        href="/espaco-aberto/blog/editar/<?php echo ($this->perfilTipo) ?>/<?php echo ($this->perfilId) ?>/id/<?php  echo $this->blog->getId()?>"
                        data-toggle="tooltip" 
                        data-placement="top" 
                        title="Editar Postagem" 
                        data-original-title="Edite esta Postagem" 
                        >
                         <i class="fa fa-edit"></i>
                    </a>
                    <a class="btn btn-primary btn-sm" 
                       href="/espaco-aberto/blog/apagar/<?php echo ($this->perfilTipo) ?>/<?php echo ($this->perfilId) ?>/id/<?php  echo $this->blog->getId()?>" 
                        data-toggle="tooltip" 
                        data-placement="top" 
                        title="Apagar Postagem" 
                        data-original-title="Apagar esta Postagem" 
                        >
                         <i class="fa fa-trash-o"></i>
                    </a>
                </div>
            </section>
        </section>
</div>
