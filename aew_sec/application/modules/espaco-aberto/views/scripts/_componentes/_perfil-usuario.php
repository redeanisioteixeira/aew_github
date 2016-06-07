<?php
    if($this->usuarioLogado->getUrlEditarPerfil($this->usuarioPerfil))
        $href['editar_perfil'] = $this->usuarioLogado->getUrlEditarPerfil($this->usuarioPerfil);
    
    if($this->usuarioLogado->getUrlRemoverColega($this->usuarioPerfil))
        $href['remover_colega'] = $this->usuarioLogado->getUrlRemoverColega($this->usuarioPerfil);
    
    if($this->usuarioLogado->getUrlAdicionarColega($this->usuarioPerfil))
        $href['adicionar_colega'] = $this->usuarioLogado->getUrlAdicionarColega($this->usuarioPerfil);
    
    if($this->usuarioLogado->getUrlBloquear($this->usuarioPerfil))
        $href['bloquear'] = $this->usuarioLogado->getUrlBloquear($this->usuarioPerfil);
?>

<div class="panel panel-default">
    <div class="panel-body">
        <!--   Imagem do usuário  -->
        <figure class="text-center">
            <a href="<?php echo $this->usuarioPerfil->getLinkPerfil();?>">
                <img class="img-circle shadow lazy" data-original="<?php echo $this->usuarioPerfil->getFotoPerfil()->getFotoCache(Aew_Model_Bo_Foto::$FOTO_CACHE_134X134, false, 134, 134,true);?>" data-toggle="tooltip" data-placement="auto" width="134" height="134" title="ver perfil usuario" alt="<?php echo $this->usuarioPerfil->getNome();?>">
            </a>
            
            <?php if($this->usuarioLogado->isDonoPerfil($this->usuarioPerfil)):?>
                <figcaption class="clearfix text-right">
                    <a class="btn btn-link" data-toggle="modal" data-target="#modalTrocarImagem" title="Trocar Imagem">
                        <i class="fa fa-picture-o"></i> atualizar
                    </a>
                </figcaption>
            <?php endif;?>
        </figure>

        <h5 class="text-center">
            <b><?php echo $this->showUsuario($this->usuarioPerfil, true, "link-verde");?></b>
        </h5>
        
        <?php if($this->getController() != 'perfil'):?>
            <?php echo $this->render('_componentes/_sobremim.php');?>
        <?php endif;?>
        
        <?php echo $this->render('_componentes/_redes-sociais.php');?>
        
    </div>

    <?php if(count($href) || $this->usuarioLogado->isColegaPendente($this->usuarioPerfil)):?>
        <div class="panel-footer">    

            <?php if(count($href)):?>
                <div class="text-center">
                    <div class="btn-group btn-group-xs">
                        <?php if($href['editar_perfil']):?> 
                            <!-- Editar perfil -->
                            <a class="btn btn-primary" title="editar minhas informações" data-toggle="tooltip" data-placement="top" href="<?php echo $href['editar_perfil'];?>"><i class="fa fa-edit"></i> editar</a>
                        <?php endif; ?>

                        <?php if($href['remover_colega']):?>
                            <!-- Remover colega -->
                            <a class="btn btn-warning" title="remover colega" data-toggle="tooltip"  data-placement="top" href="<?php echo $href['remover_colega'];?>"><i class="fa fa-user-times"></i> remover</a>
                        <?php endif;?>

                        <?php if($href['adicionar_colega']):?>
                            <!-- Adicionar colega -->
                            <a class="btn btn-success" title="convidar colega" data-toggle="tooltip" data-placement="top" href="<?php echo $href['adicionar_colega'];?>"><i class="fa fa-user-plus"> convidar</i></a>
                        <?php endif;?>

                        <?php if ($href['bloquear']):?>
                            <!-- Bloquear -->
                            <a class="btn btn-danger" title="bloquear usuário" data-toggle="tooltip" data-placement="top" href="<?php echo $href['bloquear'];?>"><i class="fa fa-lock" aria-hidden="true"></i> bloquear</a>
                        <?php endif;?>
                    </div>
                </div>
            <?php endif;?>
            
            <?php if($this->usuarioLogado->isColegaPendente($this->usuarioPerfil)):?>
                
                <?php if(count($href)):?>
                    <hr>
                <?php endif;?>
                
                <div class="clearfix text-center">
                    <span><i class="fa fa-thumbs-up"></i> Responder ao convite <i class="fa fa-question"></i></span> 
                    <div id="confirmacao" class="margin-top-10 btn-group btn-group-xs">
                        <a class="btn btn-success" href="<?php echo $this->usuarioLogado->getUrlAceitarColega($this->usuarioPerfil);?>" data-toggle="tooltip" data-placement="top" title="Aceitar"><i class="fa fa-check" aria-hidden="true"></i> aceitar</a>
                        <a class="btn btn-danger" href="<?php echo $this->usuarioLogado->getUrlRecusarColega($this->usuarioPerfil);?>" data-toggle="tooltip" data-placement="top" title="Recusar"><i class="fa fa-times" aria-hidden="true"></i> recusar </a>
                    </div>
                </div>
            <?php endif;?>
        </div>
    <?php endif;?>
                
</div>

<?php echo $this->render('_componentes/_menu-usuario.php');?>

<!-- Modal mudar imagem perfil -->
<div id="modalTrocarImagem" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <?php echo $this->render('perfil/trocar-imagem.php') ?>
        </div>
    </div>
</div>