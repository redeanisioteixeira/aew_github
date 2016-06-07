<?php $corDestaque = ($this->usuarioLogado->isDonoPerfil($this->colega) ? 'danger' : 'default');?>
<li class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
    <div class="row">
        
        <div class="panel panel-<?php echo (($this->usuarioLogado->isColega($this->colega) && !$this->usuarioLogado->isDonoPerfil($this->usuarioPerfil)) || $this->usuarioLogado->isDonoPerfil($this->usuarioPerfil) ? 'success' : 'default');?> margin-all-05">

            <?php if($this->usuarioLogado->isColega($this->colega) && !$this->usuarioLogado->isDonoPerfil($this->usuarioPerfil)):?>
                <i class="fa fa-user fa-1x shadow-center img-circle padding-all-05 superior-direita menu-verde" title="Meu colega" data-toggle="tooltip" data-placement="top"></i>
            <?php endif;?>
            
            <header class="panel-heading">
                <h6 class="text-center margin-none cursor-pointer" onclick="location.href='<?php echo $this->colega->getLinkPerfil();?>'">
                    <b class="text-capitalize"><?php echo strtolower($this->colega->getNome());?></b>
                </h6>
            </header>

            <div class="panel-body padding-all-02 margin-top-10">
                
                <figure class="trans60 text-center col-lg-10 col-md-10 col-sm-10 col-xs-10">
                    <a href="<?php echo $this->colega->getLinkPerfil();?>">
                        <img class="img-circle shadow-center" src="<?php echo $this->colega->getFotoPerfil()->getFotoCache(Aew_Model_Bo_Foto::$FOTO_CACHE_134X134,false, 134, 134,true);?>" width="120" height="120" alt="imagem de <?php echo $this->colega->getNome();?>">
                    </a>
                </figure>
    
                <?php echo $this->render('_componentes/_menu-opcoes-colega.php');?>
                
                <?php if($this->usuarioLogado->isColegaPendente($this->colega)):?>
                    <div class="text-center margin-top-10">
                        <div class="btn-group btn-group-sm" role="group">
                            <a class="btn btn-success" href="<?php echo $this->usuarioLogado->getUrlAceitarColega($this->colega) ?>" data-toggle="tooltip" data-placement="top" title="Aceitar">
                                <i class="fa fa-check" aria-hidden="true"></i> aceitar
                            </a>
                            <a class="btn btn-danger" href="<?php echo $this->usuarioLogado->getUrlRecusarColega($this->colega)?>" data-toggle="tooltip" data-placement="top" title="Recusar">
                                <i class="fa fa-times" aria-hidden="true"></i> recusar
                            </a>
                        </div>
                    </div>
                <?php endif;?>

                <?php if($this->aprovarPendentes):?>

                    <div class="text-center">
                        <div class="btn-group btn-group-sm" role="group">
                            <?php if($this->comunidade->getUrlAprovar($this->usuarioLogado, $this->membro)):?>
                                <a class="btn btn-success" href="<?php echo $this->comunidade->getUrlAprovar($this->usuarioLogado, $this->membro);?>" data-toggle="tooltip" data-placement="top" title="Aprovar colega" data-original-title="Aprovar colega">
                                    <i class="fa fa-thumbs-o-up" aria-hidden="true"></i> aprovar
                                </a>
                            <?php endif;?>

                            <?php if($this->comunidade->getUrlReprovar($this->usuarioLogado, $this->membro)):?>
                                <a class="btn btn-danger" href="<?php echo $this->comunidade->getUrlReprovar($this->usuarioLogado, $this->membro);?>" data-toggle="tooltip" data-placement="top" title="Aprovar colega" data-original-title="Aprovar colega">
                                    <i class="fa fa-thumbs-o-down" aria-hidden="true"></i> recusar
                                </a>
                            <?php endif;?>
                        </div>
                    </div>
                
                <?php endif;?>
                
            </div>
                
            <!-- Ações envia recado ou remove colega -->
            <div class="panel-footer padding-all-02">
                <nav class="btn-group-vertical btn-block" role="menu" aria-label="opções gerenciar colegas">

                    <?php if(!$this->usuarioLogado->isColega($this->colega) && !$this->usuarioLogado->isDonoPerfil($this->usuarioPerfil)):?>
                        <?php if($this->usuarioLogado->getUrlAdicionarColega($this->colega)):?>
                            <a class="btn btn-success btn-sm" title="convidar colega" data-toggle="tooltip" data-placement="top" href="<?php echo $this->usuarioLogado->getUrlAdicionarColega($this->colega);?>">
                                <i class="fa fa-envelope-o"></i> convidar
                            </a>
                        <?php endif;?>
                    <?php endif;?>
                    
                    <?php if($this->usuarioLogado->getUrlRemoverColega($this->colega) && !$this->removerMembro):?>
                        <a class="btn btn-danger btn-sm" href="<?php echo $this->usuarioLogado->getUrlRemoverColega($this->colega)?>" data-toggle="tooltip" data-placement="top" title="Remover colega" data-original-title="Remover colega">
                            <i class="fa fa-user-times" aria-hidden="true"></i> remover colega
                        </a>
                    <?php endif;?>
                    
                    <?php if(!$this->aprovarPendentes):?>
                        <?php if($this->removerMembro):?>
                            <?php if(!$this->usuarioPerfil->isDono($this->colega) && $this->usuarioPerfil->getUrlRemoverMembro($this->usuarioLogado, $this->membro)):?>
                                <a class="btn btn-danger btn-sm" href="<?php echo $this->usuarioPerfil->getUrlRemoverMembro($this->usuarioLogado, $this->membro);?>" data-toggle="tooltip" data-placement="top" title="Remover da comunidade" data-original-title="Remover da comunidade"> 
                                    <i class="fa fa-user-times" aria-hidden="true"></i> remover da comunidade
                                </a>
                            <?php endif;?>
                        <?php endif;?>

                        <?php if($this->removerModerador):?>
                            <?php if(!$this->usuarioPerfil->isDono($this->colega) && $this->comunidade->getUrlRemoverModerador($this->usuarioLogado, $this->colega)):?>
                                <a class="btn btn-danger btn-sm" href="<?php echo $this->comunidade->getUrlRemoverModerador($this->usuarioLogado, $this->colega);?>" data-toggle="tooltip" data-placement="top" title="Remover moderador" data-original-title="Remover moderador"> 
                                    <i class="fa fa-user-times" aria-hidden="true"></i> remover moderador
                                </a>
                            <?php endif;?>
                        <?php endif;?>

                        <?php if($this->adicionarDesbloquear):?>
                            <?php if($this->comunidade->getUrlDesbloquearMembro($this->usuarioLogado, $this->membro)):?>
                                <a class="btn btn-warning btn-sm" href="<?php echo $this->comunidade->getUrlDesbloquearMembro($this->usuarioLogado, $this->membro);?>" data-toggle="tooltip" data-placement="top" title="Desbloquear usuário" data-original-title="Desbloquear usuário"> 
                                    <i class="fa fa-unlock" aria-hidden="true"></i> desbloquear membro
                                </a>
                            <?php endif;?>
                    
                        <?php else:?>

                            <?php if($this->adicionarModerador):?>
                                <?php if($this->comunidade->getUrlAdicionarModerador($this->usuarioLogado, $this->membro)):?>
                                    <a class="btn btn-primary btn-sm" href="<?php echo $this->comunidade->getUrlAdicionarModerador($this->usuarioLogado, $this->membro);?>" data-toggle="tooltip" data-placement="top" title="Adicionar moderador" data-original-title="Adicionar moderador"> 
                                        <i class="fa fa-gavel" aria-hidden="true"></i> adicionar moderador
                                    </a>
                                <?php endif;?>
                            <?php endif;?>
                    
                            <?php if($this->adicionarBloquear):?>
                                <?php if($this->comunidade->getUrlBloquearMembro($this->usuarioLogado, $this->membro)):?>
                                    <a class="btn btn-warning btn-sm" href="<?php echo $this->comunidade->getUrlBloquearMembro($this->usuarioLogado, $this->membro);?>" data-toggle="tooltip" data-placement="top" title="Bloquear usuário" data-original-title="Bloquear usuário"> 
                                        <i class="fa fa-lock" aria-hidden="true"></i> bloquear membro
                                    </a>
                                <?php endif;?>
                            <?php endif;?>
                        <?php endif;?>
                    
                    <?php endif;?>
                    
                </nav>
                
            </div>

        </div>
    </div>
</li>
