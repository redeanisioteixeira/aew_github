<?php foreach($this->fotos as $foto): ?>
    <figure class="gallery-item" id="fotoalbum<?php echo $foto->getId() ?>">    
        <a id="foto" 
           href="/espaco-aberto/foto/exibir/<?php echo $this->usuarioPerfil->perfilTipo()?>/<?php echo $this->usuarioPerfil->getId() ?>/id/<?php echo $foto->getId() ?>/album/<?php echo $this->album->getId() ?>" 
           title="<?php echo $foto->getLegenda() ?>">
             <img class="img-thumbnail lazy" 
                  data-original="<?php echo $foto->getFotoCache(Aew_Model_Bo_Foto::$FOTO_CACHE_160X160,false,160,160,true); ?>"
                  width="160"
                  height="160"
                  alt="<?php echo $foto->getLegenda() ?>"
                  data-id="<?php echo $foto->getId() ?>"
                  >
        </a>
        <?php if($this->admin){?>
            <figcaption class="">
                <a class="btn btn-link" type-action="replace-action" 
                   href="/espaco-aberto/foto/editar/<?php echo $this->usuarioPerfil->perfilTipo()?>/<?php echo $this->usuarioPerfil->getId() ?>/id/<?php echo $foto->getId() ?>/album/<?php echo $this->album->getId()?>">
                    <i class="fa fa-edit"></i>
                </a>
                <a class="btn btn-link link-action modal-confirm" 
                   idloadcontainer="fotoalbum<?php echo $foto->getId() ?>" 
                   type-action="erase-action" 
                   text="Quer mesmo apagar foto?" 
                   rel="/espaco-aberto/foto/apagar/id/<?php echo $foto->getId()?>/<?php echo $this->usuarioPerfil->perfilTipo()?>/<?php echo $this->usuarioPerfil->getId()?>">
                    <i class="fa fa-trash"></i>
                </a>
            </figcaption>
        <?php }?>
    </figure>
    
<?php endforeach; ?>

