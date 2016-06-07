<article class="col-lg-8">
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-body">
                <h6 class="page-publisher"><small><i class="fa fa-calendar"></i> Publicado em <?php echo $this->SetupDate($this->ambienteDeApoio->getDataCriacao());?> por <b><?php echo $this->ambienteDeApoio->getUsuarioPublicador()->getNome();?></b></small></h6>
                <div class="col-lg-3">
                    <div class="row">
                        <div class="col-style text-center">
                            <!-- Icone, estrelas, opções e código QR -->
                            <figure class="margin-bottom-10">
                                <a href="<?php echo $this->ambienteDeApoio->getAmbientedeApoioCategoria()->getUrlExibir();?>">
                                    <h6 class="uppercase" <?php echo $this->corfonte;?>><b><?php echo $this->ambienteDeApoio->getAmbientedeApoioCategoria()->getNome();?></b></h6>
                                </a>
                                <img class="img-rounded img-responsive margin-auto padding-all-10" src="<?php echo $this->ambienteDeApoio->getImagemAssociadaUrl().DS.$this->escape($this->ambienteDeApoio->getId()); ?>.png">
                            </figure>

                            <?php echo $this->showEstrelas($this->ambienteDeApoio);?>

                            <div class="row margin-bottom-10">
                                <span class="box-badge"><span class="badge badge-bottom" title="Visualizações" alt="Visualizações"><i class="fa fa-search"></i> (<?php echo $this->ambienteDeApoio->getAcessos();?>)</span></span>
                                <span class="box-badge"><span class="badge badge-bottom" title="Comentários" alt="Comentários"><i class="fa fa-comment"></i> (<?php echo count($this->ambienteDeApoio->getComentarios());?>)</span></span>
                            </div>
                            
                            <div class="btn-group-vertical btn-block" role="group">
                                <a class="btn btn-sm btn-danger uppercase" data-toggle="modal" data-target="#modalGeral" class="btn btn-primary" alt="Denunciar" href="/aew/home/denunciar"><i class="fa fa-times-circle"></i> Denunciar</a>
                               
                                <?php if($this->ambienteDeApoio->getUrlProjeto()):?>
                                    <a class="btn btn-sm btn-default uppercase" href="<?php echo $this->ambienteDeApoio->getUrlProjeto();?>" target="_blank"><i class="fa fa-link"></i> Ir para o site</a>
                                <?php endif;?>
                                    
                                <?php if($this->ambienteDeApoio->getUrl()):?>
                                    <a class="btn btn-sm btn-warning uppercase" href="<?php echo $this->ambienteDeApoio->getUrl();?>" target="_blank"><i class="fa fa-download"></i> Baixar projeto</a>
                                <?php endif;?>
                            </div>

                            <!--  Codigo QR -->
                            <figure>
                                <?php echo $this->chart?>
                            </figure>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9">

                    <?php echo $this->render('_componentes/_botoes-opcoes-usuario.php');?>
                    
                    <span class="row">
                        <label class="uppercase" <?php echo $this->corfonte;?>><i class="fa fa-ellipsis-v"></i> Ficha técnica</label> 
                        <p class="text-justify"><?php echo $this->escape($this->ambienteDeApoio->getDescricao());?></p>
                    </span>

                    <?php if($this->ambienteDeApoio->getUsoPedagogico()):?>
                        <span class="row">
                            <label class="uppercase" <?php echo $this->corfonte;?>><i class="fa fa-ellipsis-v"></i> Uso Pedagógico</label> 
                            <p class="text-justify"><?php echo $this->escape($this->ambienteDeApoio->getUsoPedagogico());?></p>
                        </span>
                    <?php endif;?>
                    
                    <span class="row">
                        <?php if($this->ambienteDeApoio->getSiteStatus()):?>                                
                            <a class="btn btn-primary livepreview" href="<?php echo $this->ambienteDeApoio->getUrlProjeto();?>" target="_blank" data-trigger="click" data-tag="#preview<?php echo $this->ambienteDeApoio->getId();?>" target="_blank"><i class='fa fa-eye'></i> pre-visualizar</a>
                            <div id="preview<?php echo $this->ambienteDeApoio->getId();?>"></div>
                        <?php else:?>
                            <i class="link-vermelho fa fa-chain-broken"> <b>fora do ar</b></i>
                        <?php endif;?>
                    </span>
                </div>

            </div> <!--- panel-body -->

        </div> <!--- panel -->
        
        <?php if(strlen(trim($this->relacionadosConteudos))):?>
            <div class="panel panel-default">
                <div class="panel-body">
                    <div id="relacionados">
                        <?php echo $this->relacionadosConteudos;?>
                    </div>
                </div>
            </div>
        <?php endif;?>
        
        <?php echo $this->render('/usuario/box-list-comentarios.php');?>
        
    </div>
    
</article>

<aside class="col-lg-4">
    <div clas="row">
        <div class="col-lg-12">
            <div class="row">
                <h4 class="uppercase" <?php echo $this->corfonte;?>><b><i class="fa fa-tags"></i> Tags</b></h4>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <?php echo $this->showTags($this->ambienteDeApoio->getTags(), true);?>
                    </div>
                </div>
            </div>
        </div>
        <?php if(count($this->relacionadosApoio)):?>
            <div class="col-lg-12">
                <div id="relacionados">
                    <?php echo $this->relacionadosApoio;?>
                </div>
            </div>
        <?php endif;?>
    </div>
</aside>
