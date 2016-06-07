<li id="comunidade-panel-<?php echo $this->comunidade->getId()?>">
    <header>
        <h4 class="headline-ea cursor-pointer link-verde" onclick="location.href='<?php echo $this->comunidade->getLinkPerfil();?>'"><b><i class="fa fa-comments-o"></i> <?php echo $this->comunidade->getNome();?></b></h4>
    </header>
    
    <div class="box">
        <div class="media-left text-center">

            <figure>
                <a href="<?php echo $this->comunidade->getLinkPerfil(); ?>">
                    <img class="img-rounded shadow-center " src="<?php echo $this->comunidade->getFotoPerfil()->getFotoCache(Aew_Model_Bo_Foto::$FOTO_CACHE_90X90,false,90,90,true) ?>" title="Imagem da comunidade" width="90" height="90">
                </a>
            </figure>

            <?php echo $this->showEstrelas($this->comunidade, null, false);?>

            <?php if($this->comunidade->getQtdVisitas()):?>
                <div class="margin-bottom-10">
                    <span class="box-badge"><span class="fa fa-search badge badge-bottom" title="Visualizações" alt="Visualizações"> (<?php echo $this->comunidade->getQtdVisitas();?>)</span></span>
                </div>
            <?php endif;?>
            
            <?php if(count($this->comunidade->selectMembrosAtivos())):?>
                <div class="margin-bottom-10">
                    <span class="box-badge"><span class="fa fa-users badge badge-bottom" title="Participantes" alt="Participantes"> (<?php echo count($this->comunidade->selectMembrosAtivos());?>)</span></span>
                </div>
            <?php endif;?>
            
        </div>

        <div class="media-body padding-left-20">
            <h6 class="page-header"><small><i class="fa fa-calendar"></i> Publicado em <?php echo $this->SetupDate($this->comunidade->getDataCriacao());?> por <b><?php echo $this->ShowUsuario($this->comunidade->getUsuario());?> </b></small></h6>

            <p><?php echo $this->readMore($this->comunidade->getDescricao(),700, null, $this->filtro, $this->comunidade->getLinkPerfil(), 'success','ver comunidade');?></p>
            
            <?php if(count($this->comunidade->selectTags())):?>
                <label class="fa fa-tags"><b>Tags</b></label>
                <span><?php echo $this->showTags($this->comunidade->selectTags());?></span>
            <?php endif;?>
            
            <hr>

            <?php if($this->pendentes):?>
                <div class="btn-group btn-group-sm pull-left" role="group">
                    <?php if($this->comunidade->getUrlAceitarComunidade($this->usuarioLogado)):?>
                        <a class="btn btn-success" href="<?php echo $this->comunidade->getUrlAceitarComunidade($this->usuarioLogado);?>" data-toggle="tooltip" data-placement="top" title="aceitar comunidade">
                             <i class="fa fa-thumbs-up"></i> aprovar
                        </a>
                        <a class="btn btn-danger" href="<?php echo $this->comunidade->getUrlRecusarComunidade($this->usuarioLogado);?>" data-toggle="tooltip" data-placement="top" title="recusar comunidade">
                             <i class="fa fa-thumbs-down"></i> reprovar
                        </a>
                    <?php endif;?>
                </div>
            <?php endif;?>
            
            <div class="btn-group btn-group-sm pull-right" role="group">

                <?php if($this->adicionarRelacao || $this->removerRelacao):?>
                
                    <?php if($this->adicionarRelacao && $this->comunidade->getUrlAdicionarRelacao($this->usuarioLogado, $this->usuarioPerfil)):?>
                        <a class="btn btn-warning" href="<?php echo $this->comunidade->getUrlAdicionarRelacao($this->usuarioLogado, $this->usuarioPerfil);?>">
                            <i class="fa fa-link"></i> relacionar
                        </a>
                    <?php endif;?>

                    <?php if($this->removerRelacao && $this->comunidade->getUrlRemoverRelacao($this->usuarioLogado, $this->usuarioPerfil)):?>
                        <a class="btn btn-danger" href="<?php echo $this->comunidade->getUrlRemoverRelacao($this->usuarioLogado, $this->usuarioPerfil);?>">
                            <i class="fa fa-chain-broken"></i> remover relação
                        </a>
                    <?php endif;?>
                
                <?php else:?>

                    <?php if(!$this->pendentes):?>
                        <?php if($this->comunidade->getUrlSair($this->usuarioLogado)):?>
                            <a class="btn btn-warning link-action" type-action='html-action' idloadcontainer="comunidade-panel-<?php echo $this->comunidade->getId()?>" rel="<?php echo $this->comunidade->getUrlSair($this->usuarioLogado);?>">
                                <i class="fa fa-sign-out"></i> sair da comunidade
                            </a>
                        <?php endif;?>
                    <?php endif;?>
                
                    <?php if($this->comunidade->getUrlConfigurar($this->usuarioLogado) && $this->usuarioPerfil->isDonoPerfil($this->usuarioLogado)):?>
                        <a class="btn btn-primary" href="<?php echo $this->comunidade->getUrlConfigurar($this->usuarioLogado);?>" data-toggle="tooltip" data-placement="top" title="Editar comunidade" data-original-title="Editar comunidade">
                            <i class="fa fa-edit"></i> editar
                        </a>
                    <?php endif;?>

                    <?php if($this->comunidade->getUrlApagarComunidade($this->usuarioLogado) && $this->usuarioPerfil->isDonoPerfil($this->usuarioLogado)):?>
                        <a class="btn btn-danger" href="<?php echo $this->comunidade->getUrlApagarComunidade($this->usuarioLogado);?>" data-toggle="tooltip" data-placement="top" title="Apagar comunidade" data-original-title="Apagar comunidade">
                            <i class="fa fa-trash-o"></i> apagar
                        </a>
                    <?php endif;?>
                
                <?php endif;?>
                
            </div>
            
            <?php if($this->sugeridas):?>
                <div class="text-center">
                    <span class="clearfix">
                        <img class="img-circle shadow-center lazy" data-original="<?php echo $this->comunidade->getUsuarioConvite()->getFotoPerfil()->getFotoCache(Aew_Model_Bo_Foto::$FOTO_CACHE_30X30,false,30,30,true)  ?>" width="30" height="30"> <?php echo $this->showUsuario($this->comunidade->getUsuarioConvite());?> sugeriu esta comunidade para você.
                    </span> 
                    <hr>
                    <h5 class="clearfix">Deseja aceitar a sugestão?</h5>
                    <div class="btn-group btn-group-sm clearfix" role="group">
                        <a class="btn btn-success" href="/espaco-aberto/comunidade/aceitar-sugestao/idcomunidade/<?php echo $this->comunidade->getIdcomunidade()?>/idusuarioconvite/<?php echo $this->comunidade->getUsuarioConvite()->getId()?>" title="Aceitar comunidade sugerida"><i class="fa fa-thumbs-up"></i> aceitar</a>
                        <a class="btn btn-danger" href="/espaco-aberto/comunidade/recusar-sugestao/idcomunidade/<?php echo $this->comunidade->getIdcomunidade()?>/idusuarioconvite/<?php echo $this->comunidade->getUsuarioConvite()->getId()?>" title="Recusar comunidade sugerida"><i class="fa fa-thumbs-down"></i> recusar</a>
                    </div>
                </div>
            <?php endif;?>

        </div>
    </div>
</li>