
<div class="box">
    <span class="text-azul"><?php echo $this->recado->getUsuarioAutor()->getNome() ?></span>
            <div class="scroll">
                <ul class="media-list">
                    <?php for($i=0;$i<count($this->recado);$i++ ): ?>
                    <li class="media">
                        <div class="media-left">
                            <img class="media-object lazy" 
                                 data-original="<?php echo $this->recado
                                                                ->getUsuarioAutor()
                                                                ->getFotoPerfil()
                                                                ->getFotoCache(Aew_Model_Bo_Foto::
                                                                $FOTO_CACHE_30X30, 
                                                                false, 30, 30,true) ?>"
                                 width="30"
                                 height="30">
                        </div>
                        <div class="media-body">
                            <?php echo $this->recado->getComentario() ?>
                        </div>
                    </li>
                    <?php foreach ($this->recado->selectComentariosRelacionados(6) as $recadoRelacionado) { ?>
                    <li class="media">
                        <div class="media-body">
                            <span class="pull-right"><?php echo $recadoRelacionado->getComentario() ?></span>
                        </div>
                        <div class="media-right">
                            <a href="<?php echo $recadoRelacionado->getUsuarioAutor()->getLinkPerfil() ?>">
                            <img class="media-object lazy" 
                                 data-original="<?php echo $recadoRelacionado->getUsuarioAutor()
                                                                              ->getFotoPerfil()
                                                                              ->getFotoCache(Aew_Model_Bo_Foto:: 
                                                                              $FOTO_CACHE_30X30,
                                                                              false,30,30,true) ?>"
                                 width="30"
                                 height="30">
                            </a>
                        </div>
                    </li>
                    <?php } ?>
                    <?php endfor; ?>
                </ul>
            </div>
        </div>
        <section class="box">
            <div class="form-resposta">
                <?php echo $this->render('recado/enviar-resposta.php'); ?>
                
            </div>
        </section>


