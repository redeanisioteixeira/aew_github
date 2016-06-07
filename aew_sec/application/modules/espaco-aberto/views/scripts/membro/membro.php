
<li class="col-lg-3 col-md-4 col-sm-6 col-xs-12" >
    <section class="box-user">
        <figure class="">
            <div class="thumbnail-user">
                <a class="" href="<?php echo $this->membro->getLinkPerfil() ?>">
                    <img class="img-circle" 
                         src="<?php $this->membro->selectFotoPerfil(); echo $this->membro->getFotoPerfil()
                                                      ->getFotoCache(Aew_Model_Bo_Foto::
                                                      $FOTO_CACHE_64X64, 
                                                      false, 64, 64, true) ?>"
                         width="64"
                         height="64"
                    >
                </a>
            </div>
            <figcaption class="media-heading text-center">
                    <span class="capitalize text-muted" 
                          title="Exibir perfil de" 
                          alt="Exibir perfil de <?php echo  $this->membro->getNome() ?>"
                    >
                        <?php echo strtolower($this->membro->getNome()) ?>
                    </span>
            </figcaption>

            <?php if ($this->usuarioPerfil->getUrlRemoverMembro($this->usuarioLogado, $this->membro)): ?>
            <div class="">
                
                <a class="btn btn-danger btn-xs btn-block padding-all-05" href="<?php echo $this->usuarioPerfil->getUrlRemoverMembro($this->usuarioLogado, $this->membro) ?>">
                    Remover Membro
                </a>
            </div>
            <?php endif; ?>
        </figure>
    </section>    
</li>