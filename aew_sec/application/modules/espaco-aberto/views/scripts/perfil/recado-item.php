<h6 class="page-publisher"><small><i class="fa fa-calendar"></i> Enviado em <?php echo $this->SetupDate($this->itemPerfil->getDataenvio());?></small></h6>

<div class="media">
    <a href="<?php echo $this->itemPerfil->getUsuarioAutor()->getLinkPerfil() ?>" title="Visualizar perfil de <?php echo  $this->itemPerfil->getUsuarioAutor()->getNome();?>">
        <figure class="media-left">
            <img class="img-circle shadow-center" src="<?php echo $this->itemPerfil->getUsuarioAutor()->getFotoPerfil()->getFotoCache(Aew_Model_Bo_Foto::$FOTO_CACHE_90X90,false,90,90,true);?>" width="30" height="30">
        </figure>
        <h5 class="media-body middle">
            <b class="text-capitalize text-verde"><?php echo strtolower($this->itemPerfil->getUsuarioAutor()->getNome());?></b>
        </h5>
    </a>
    <p class="link-cinza-escuro"><?php echo $this->itemPerfil->getComentario();?></p>
</div>
