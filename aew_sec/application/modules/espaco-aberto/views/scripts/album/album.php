<div class="col-lg-3 col-md-4">  
    <h4 class="text-center">
        <a href="<?php echo $this->album->getUrlExibir($this->usuarioPerfil)?>">
            <?php echo $this->album->getTitulo()?>
        </a>
        <span class="text-muted clearfix">
            <?php echo date("d/m/Y",strtotime($this->album->getDatacriacao()))?>
        </span> 
    </h4>      

    <?php if(count($this->album->selectFotos())==0): ?>
    <section class="box text-center">
        <span class="text-danger">
            <i class="fa fa-exclamation"></i>
            Album sem Fotos 
        </span>
    </section>
    <?php else :?>
    <span class="text-center">
        Fotos (<?php echo $nFotos=count($this->album->selectFotos()) ?>)
    </span>
    <?php foreach($this->album->selectFotos() as $foto) :
        $this->foto = $foto;
        echo $this->render('album/foto-album.php');break;
    endforeach; ?>
    <?php endif; ?>
</div>