<div class="box">
    <?php if($this->usuarioLogado->getUrlAdicionarAlbum($this->usuarioPerfil)){ ?>
        <a class="btn btn-success btn-circle-sm pull-right"
            href="<?php echo $this->usuarioLogado->getUrlAdicionarAlbum($this->usuarioPerfil)?>" 
            title="Adicionar Álbum"
        >
        <i class="fa fa-plus"></i>
        </a>
    <?php } ?>
</div>
<?php if ($this->nAlbums==0): ?>
    <section class="box text-center">
        <span class="text-danger">
            <i class="fa fa-exclamation"></i>
            Sem albums de fotos
        </span>
    </section>
    
    <?php else:?>
    <section class="box">
        <?php foreach($this->albuns as $album):
            $this->album = $album;
            echo $this->render('album/album.php');
        endforeach;  ?>
        
    </section>    
    <?php endif; ?>
    
</section>