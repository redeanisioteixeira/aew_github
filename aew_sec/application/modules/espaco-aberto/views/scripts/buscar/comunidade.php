<div class="box">
    <?php echo $this->form_buscar;?>	
</div>
<?php if(count($this->comunidades)==0) : ?>
<div class="box text-center">
    <span class="text-danger">
        <i class="fa fa-exclamation"></i> Nenhuma comunidade encontrada.
    </span>
</div>
<?php else :?>
<ul class="media-list">
    <?php foreach($this->comunidades as $comunidade): ?>
        <li class="media box" id="<?php echo $comunidade->getId() ?>" >
            <figure class="media-left">
                <a class="thumbnail">
                    <img class="lazy" data-original="<?php echo $comunidade->getFotoPerfil()
                                       ->getFotocache(Aew_Model_Bo_Foto::$FOTO_CACHE_64X64,
                                        false,64,64,true) ?>"
                         width="64"
                         height="64"
                        >                
                </a>
                <figcaption>
                    <div class="estrelas" id="estrelas">
                        <?php echo $this->showEstrelas($comunidade)?>
                    </div>
                </figcaption> 
                <figcaption class="caption text-center">
                    <?php if(!$comunidade->isParticipante($this->usuarioLogado)){?>
                    <a class="btn btn-success btn-xs link-action" type-action='replace-action' rel="/espaco-aberto/buscar/participar/idcomunidade/<?php echo $comunidade->getId()?>" comunidade="<?php echo $comunidade->getId() ?>" nome="<?php echo $comunidade->getNome() ?>">Entrar na comunidade</a>
                    <?php } else{?>
                        <span class="text-info" comunidade="<?php echo $comunidade->getId() ?>" nome="<?php echo $comunidade->getNome() ?>">Você ja faz parte da comunidade</span>
                    <?php } ?>
                </figcaption>
            </figure>
            <section class="media-body">
                <h3 class="media-heading">
                    <a class="link-verde" href="<?php echo $comunidade->getLinkPerfil() ?>">
                        <?php echo $comunidade->getNome() ?>
                    </a>
                </h3>    
                <div><?php echo $this->readMore($comunidade->getDescricao(),200) ?></div>
                <hr>
                <div>Publicado por 
                    <b><?php echo $comunidade->getNome() ?></b> em <?php echo $comunidade->getDataCriacao() ?>
                </div>
            </section>
        </li> 
    <?php endforeach; ?>
    </ul>
    <div class="PaginacaoListar"><?php $this->comunidades ?></div>
<?php endif; ?>