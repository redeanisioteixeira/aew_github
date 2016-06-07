<?php
$i = 1;
if(!count($this->itensPerfil)):
    return;
endif;
?>

<section class="box-<?php echo $this->RetiraAcentuacao($this->titulo, '-');?> margin-bottom-10">

    <?php if(count($this->itensPerfil)>6):?>
        <span class="box-badge absolute absolute-right margin-top-05-">
            <span class="fa <?php echo $this->icone;?> badge badge-bottom"> <?php echo count($this->itensPerfil);?></span>
        </span>
    <?php endif;?>
    
    <header>
        <a href="<?php echo $this->linkTitulo;?>" title="<?php echo $this->titulo;?>" data-toggle="tooltip" data-placement="top">
            <h5 class="headline-ea headline-ea-<?php echo $this->cor;?> link-<?php echo $this->cor;?>"><b><i class="fa <?php echo $this->icone;?>"></i> <?php echo $this->titulo;?></b></h5>
        </a>
    </header>

    <!-- Listagem -->
    <div class="box box-verde">
        
        <ul class="list-unstyled">
            <?php foreach ($this->itensPerfil as $itemPerfil):?>
            <li class="padding-bottom-05 padding-top-05 border-bottom">
                <?php
                    $this->itemPerfil = $itemPerfil;
                    if($itemPerfil instanceof Aew_Model_Bo_Blog):
                        $view = 'perfil/blog-item.php';
                    elseif($itemPerfil instanceof Aew_Model_Bo_UsuarioRecado):
                        $view = 'perfil/recado-item.php';

                    elseif($itemPerfil instanceof Aew_Model_Bo_ItemPerfil):
                        $view = '_componentes/_item-perfil.php';
                    endif;
                    
                    echo $this->render($view);
                    
                    if($i++ > 5):
                        break;
                    endif;
               ?>

            </li>
          <?php endforeach;?>
        </ul>
    </div> 
</section>