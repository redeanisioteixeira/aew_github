<?php $tipo = "";?>

<?php foreach($this->resultados as $resultado):?>
    <?php if($resultado->getTipo() != $tipo):?>
        <li class="header text-info text-center grupo-busca"><?php echo $resultado->getTipo()?></li>
        <?php $tipo = $resultado->getTipo();?>
    <?php endif;?>

    <li class="media">
        <article class=" <?php echo $resultado->getTipo();?>"> 
            <figure class="media-left">
                <a class="media-object" href="<?php echo $resultado->getUrlPerfil();?>">
                    <img class="menu-verde img-<?php echo($resultado->getTipo() == 'colega' ? 'circle' : 'rounded');?> shadow-center" src="<?php echo $resultado->getUrlFoto();?>" width="60" height="60">
                </a>
            </figure>

            <div class="media-body">
                <a id="<?php echo $resultado->getId();?>" class="<?php echo $resultado->getTipo();?>" comunidade="<?php echo $resultado->getIdcomunidade();?>" usuario="<?php echo $resultado->getIdusuario();?>" href="<?php echo $resultado->getUrlPerfil();?>">
                    <h5 class="menu-verde uppercase"><b><?php echo $resultado->getNome();?></b></h5>
                </a>

                <p>
                    <?php echo $this->readMore($resultado->getDescricao(),500,NULL,$this->filtro);?>
                </p>
            </div>
        </article>
    </li>
<?php endforeach;?>

<li class="footer"><a href="/espaco-aberto/buscar/listar/filtro/<?php echo htmlspecialchars($this->filtro) ?>">Ver mais resultados</a></li>



