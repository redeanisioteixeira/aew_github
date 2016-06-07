<li class="header"> Resultados da Busca <i class="fa fa-align-justify"></i></li>
<?php $tipo = ""; foreach($this->resultados as $resultado): ?>
  <?php  $url = $resultado->getUrlFoto();  ?>
    <?php if($resultado->getTipo()!=$tipo): $tipo = $resultado->getTipo();?>
<li class="header text-info text-center grupo-busca"><?php echo $resultado->getTipo() ?></li>
    <?php endif;?>
<li>
    <ul class="menu media-list">
        
        <li class="media" style="padding: 10px;">
            <article class=" <?php echo $resultado->getTipo() ?>"> 
                <figure class="media-left">
                    <a class="media-object" href="<?php  echo $resultado->getUrlPerfil() ?>">
                        <img class="thumbnail"
                                 src="<?php echo $url ?>" 
                                 alt="Foto do Usuário" 
                                 width="30" 
                                 height="30">
                    </a>
                </figure>
                <div class="media-body">
                    <h5 class="media-heading">
                        <a class="<?php echo $resultado->getTipo() ?>" 
                           id="<?php echo $resultado->getId() ?>" 
                           comunidade="<?php echo $resultado->getIdusuario()?>" 
                           usuario="<?php echo $resultado->getIdcomunidade()?>" 
                           href="<?php echo $resultado->getUrlPerfil() ?>"
                          >  
                           <?php echo $resultado->getNome() ?>
                        </a>
                    </h5>
                    <span>
                        
                    </span>
                    <span class="">
                        <?php echo $this->readMore($resultado->getDescricao(),500,NULL,$this->filtro) ?>
                    </span>
                </div>
            </article>
        </li>
        </a>
    </ul>        
</li>
<?php endforeach; ?>
<li class="footer"><a href="/espaco-aberto/pesquisa/home/filtro/<?php echo htmlspecialchars($this->filtro) ?>">Ver mais resultados</a></li>
