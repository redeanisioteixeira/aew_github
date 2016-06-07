<figure class="thumbnail-user">
    <a href="<?php echo $this->album->getUrlExibir($this->usuarioPerfil)?>">
        <img class="img-thumbnail" 
             src="<?php echo $this->foto->getFotoCache(Aew_Model_Bo_Foto::$FOTO_CACHE_160X160,false, 160, 160,true) ?>"
             width="160"  
             height="160"
             alt="<?php echo $this->foto->getLegenda() ?>"
             >   
    </a>    
</figure>
<div class="box text-center">
    <?php if($this->album->getUrlEditar($this->usuarioLogado)):?>
    <a class="btn btn-default btn-sm" 
       href="<?php echo $this->album->getUrlEditar($this->usuarioLogado)?>"
       title="Editar album"
       data-toggle="tooltip"
       data-placement="top"
       >
        <i class="fa fa-edit"></i>
    </a>

    <a class="btn btn-danger btn-sm" 
       href="<?php echo $this->album->getUrlApagar($this->usuarioLogado)?>" 
       title="Apagar"
       data-toggle="tooltip"
       data-placement="top"
       >
        <i class="fa fa-trash"></i>
    </a>
    <?php endif; ?>
</div>