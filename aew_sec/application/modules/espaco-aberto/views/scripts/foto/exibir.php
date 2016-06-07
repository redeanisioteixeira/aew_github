<header class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar"><span aria-hidden="true">&times;</span></button>
    <h3 class="modal-title"><?php echo $this->foto->getLegenda() ?></h3>
    
</header>
<div class="modal-body">
    <div class="row item-modal">
        <div class="col-lg-7">
            <figure class="center-block">
                <div class="thumbnail-user">
                    <img class="img-rounded" 
                         src="<?php echo $this->foto->getFotoCache(Aew_Model_Bo_Foto::$FOTO_CACHE_450X450,false,450,450,true); ?>" width="450" height="450">
                </div>
            </figure>
        </div>
        <aside class="col-lg-5 load-comment" >
            <ul class="list-unstyled load-scroll" id="comentarios-album">
            <!-- comentários da foto -->     
            <?php if(count($this->comentarios) == 0):?>
            <li class="box text-center">
                <span class="text-info">
                    <i class="fa fa-exclamation"></i>
                    Seja o primeiro em escrever um comentário
                </span>
            </li> 
            <?php else :?>
            <?php echo $this->render('usuario/listar-comentarios.php')?>
            <?php endif;?>
            </ul>
            
            <section class="box load-content-form" idloadcontainer="comentarios-album" type-action='html-action'>  
                <!-- formulario comentário -->
                <?php echo $this->formComentario ?>
            </section>
        </aside>
    </div>
</div>
    <script type="text/javascript">
        $(document).ready(function(){
            loads();
        })
    </script>
   
   


