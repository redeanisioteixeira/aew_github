<div class="panel panel-default">
    <div class="panel-body">

        <!-- foto da comunidade -->
        <figure class="text-center margin-bottom-20">
            <a href="<?php echo $this->usuarioPerfil->getLinkPerfil();?>">
                <img class="img-rounded shadow lazy" data-original="<?php echo $this->usuarioPerfil->getFotoPerfil()->getFotoCache(Aew_Model_Bo_Foto::$FOTO_CACHE_134X134, false, 134, 134,true);?>" width="134" height="134">
            </a>
        </figure>

        <h6 class="page-publisher"><small><i class="fa fa-calendar"></i> Criado em <?php echo $this->SetupDate($this->usuarioPerfil->getDataCriacao());?> por <b class="capitalize"><?php echo $this->showUsuario($this->usuarioPerfil->getUsuario());?></b></small></h6>

        <header class="text-center">
            <a href="<?php echo $this->usuarioPerfil->getLinkPerfil();?>">
                <h5 class="link-verde capitalize"><b><?php echo strtolower($this->usuarioPerfil->getNome());?></b></h5>
            </a>
        </header>

        <?php if($this->getController() != 'comunidade'):?>
            <?php echo $this->render('_componentes/_sobremim.php');?>
        <?php endif;?>

        <?php echo $this->showEstrelas($this->usuarioPerfil, 'espaco-aberto');?>

        <?php if($this->usuarioPerfil->getQtdVisitas()):?>
            <div class="margin-bottom-10 text-center">
                <span class="box-badge"><span class="fa fa-search badge badge-bottom" title="Visualizações" alt="Visualizações"> (<?php echo $this->usuarioPerfil->getQtdVisitas();?>)</span></span>
            </div>
        <?php endif;?>

    </div>

    <div class="panel-footer text-center">
        <div class="btn-group btn-group-xs">
            <?php if ($this->usuarioPerfil->getUrlTrocarImagem($this->usuarioLogado)) : ?>                
                <a class="btn btn-primary" title="Trocar imagem" data-toggle="modal" data-target="#modalTrocarImagem" data-toggle="modal">
                    <i class="fa fa-camera"></i> atualizar
                </a>
            <?php endif;?>

            <?php if ($this->usuarioPerfil->getUrlEntrar($this->usuarioLogado)) : ?>
                <a class="btn btn-success" title="Entrar na comunidade" href="<?php echo $this->usuarioPerfil->getUrlEntrar($this->usuarioLogado);?>" data-toggle="tooltip" data-placement="top">
                    <i class="fa fa-sign-in"></i> entrar
                </a>
            <?php endif; ?>

            <?php if ($this->usuarioPerfil->getUrlSair($this->usuarioLogado)):?>
                <a class="btn btn-warning" title="Sair da comunidade" href="<?php echo $this->usuarioPerfil->getUrlSair($this->usuarioLogado);?>" data-toggle="tooltip" data-placement="top">
                    <i class="fa fa-sign-out"></i> sair
                </a>
            <?php endif;?>

            <?php if ($this->usuarioPerfil->getUrlModerar($this->usuarioLogado)):?>
                <a class="btn btn-danger" href="<?php echo $this->usuarioPerfil->getUrlBloquear($this->usuarioLogado);?>" data-toggle="tooltip" data-placement="top">
                    <i class="fa fa-ban"></i> bloquear
                </a>
            <?php endif;?>
        </div>
    </div>

</div>


<?php echo $this->render('_componentes/_menu-comunidade.php');?>    

<?php if(count($this->usuarioPerfil->selectTags())):?>
    <section class="box-tags">
        <h4 class="uppercase link-verde"><b><i class="fa fa-tags"></i> Tags</b></h4>
        <div class="panel panel-default">
            <div class="panel-body">
                <?php echo $this->showTags($this->usuarioPerfil->selectTags());?>
            </div>
        </div>
    </section>
<?php endif;?>

<!-- Modal trocar imagem -->
<section id="modalTrocarImagem" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <?php echo $this->render('perfil/trocar-imagem.php');?>
        </div>
    </div>    
</section>
