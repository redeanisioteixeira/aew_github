<?php $apagar = $this->comentario->getUrlApagar($this->usuarioLogado);?>
<li class="comentario-box row" <?php echo ($apagar ? ' idcomentario="'.$this->comentario->getId().'"' : '');?>>
    <figure class="col-lg-1">
        <?php if($this->usuarioLogado):?>
            <a title="<?php echo $this->comentario->getUsuarioAutor()->getNome();?>" onclick='location.href="<?php echo $this->showUsuario($this->comentario->getUsuarioAutor(),false);?>"'>
                <?php echo $this->comentario->getUsuarioAutor()->getFotoPerfil()->getFotoCache(Aew_Model_Bo_Foto::$FOTO_CACHE_30X30,false,40,40,false,'img-circle shadow-center margin-all-05');?>
            </a>
        <?php else:?>
            <?php echo $this->comentario->getUsuarioAutor()->getFotoPerfil()->getFotoCache(Aew_Model_Bo_Foto::$FOTO_CACHE_30X30,false,40,40,false,'img-circle shadow-center');?>
        <?php endif;?>
    </figure>

    <span class="col-lg-11 comentario-mensagem">

        <small class="link-cinza-claro">
            <i class="fa fa-calendar"></i> Enviado por 
                <b class="link-cinza-escuro">
                    <?php echo $this->comentario->getUsuarioAutor()->getNome();?>
                </b> em <?php echo $this->SetupDate($this->comentario->getDatacriacao());?>
        </small>

        <p class="link-cinza-escuro margin-top-05"><b><?php echo $this->escape($this->comentario->getComentario());?></b></p>

        <?php if($apagar):?>
            <?php if($this->usuarioPerfil){ $urlUsuario = '/'.$this->usuarioPerfil->perfilTipo().'/'.$this->usuarioPerfil->getId();}?>
            <a name='apagar-comentario' class="btn btn-link pull-right" rel="<?php echo $apagar.$urlUsuario?>" title="apagar comentário" alt="apagar comentário" idcomentario="<?php echo $this->comentario->getId();?>">
                <i class="fa fa-trash"></i> 
            </a>
        <?php endif;?>
            
    </span>

</li>
