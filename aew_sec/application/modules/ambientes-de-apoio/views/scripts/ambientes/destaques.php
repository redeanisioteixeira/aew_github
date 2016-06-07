<div id="conteudo-ambientes-apoio">

    <div class="conteudo-ambientes-apoio col-lg-12">
        <div class="margin-top-05 margin-bottom-05">
            <?php echo $this->paginationControl($this->ambientesDeApoio,"Sliding","_componentes/_pagination_ajax.php", array("divContent" => "#conteudo-ambientes-apoio"));?>
        </div>

        <div id="itens" class="itens itens-isotope">

            <?php foreach($this->ambientesDeApoio as $ambienteDeApoio):?>

                <article class="page-header col-lg-6 col-md-6 col-sm-12 col-xs-12">

                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sd-3 text-center">
                            <figure class="col-lg-12 margin-bottom-10">
                                <div class="row">
                                    <a href="<?php echo $ambienteDeApoio->getUrlExibir($this->comunidade);?>">
                                        <img class="img-rounded img-responsive padding-all-05 lazy" data-original="<?php echo $ambienteDeApoio->getImagemAssociadaUrl().DS.$ambienteDeApoio->getId()?>.png">
                                    </a>
                                </div>
                            </figure>

                            <?php if(isset($this->href['remover_destaque'])):?>
                                <div class="col-lg-12">
                                    <div class="row text-center">
                                        <a class="btn btn-danger btn-xs text-center" title="Remover dos destaques" alt="Remover dos destaques" href="<?php echo $this->href['remover_destaque'].DS.'id'.DS.$ambienteDeApoio->getId();?>"><i class="fa fa-bell-slash-o"></i> remover</a>
                                    </div>
                                </div>
                            <?php endif;?>

                            <div class="col-lg-12">
                                <div class="row">
                                    <?php echo $this->showEstrelas($ambienteDeApoio, "", false);?>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="row">
                                    <span class="box-badge"><span class="badge badge-bottom" title="Visualizações" alt="Visualizações"><i class="fa fa-search"></i> (<?php echo $ambienteDeApoio->getAcessos();?>)</span></span>
                                    <span class="box-badge"><span class="badge badge-bottom" title="Comentários" alt="Comentários"><i class="fa fa-comment"></i> (<?php echo count($ambienteDeApoio->getComentarios());?>)</span></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-9 col-md-9 col-sd-9 margin-top-10">
                            <div class="row">
                                <h6 class="page-publisher"><small><i class="fa fa-calendar"></i> Publicado em <?php echo $this->SetupDate($ambienteDeApoio->getDataCriacao());?> por <b><?php echo $ambienteDeApoio->getUsuarioPublicador()->getNome();?></b></small></h6>

                                <a href="<?php echo $ambienteDeApoio->getAmbientedeApoioCategoria()->getUrlExibir();?>">
                                    <h6 class="page-publisher uppercase link-laranja border-none">
                                        <b><i class="fa fa-ellipsis-v"></i> <?php echo $ambienteDeApoio->getAmbientedeApoioCategoria()->getNome();?></b>
                                    </h6>
                                </a>
                                
                                <a href="<?php echo $ambienteDeApoio->getUrlExibir($this->comunidade);?>">
                                    <h4 class="menu-amarelo" title="<?php echo $ambienteDeApoio->getTitulo();?>"><b><?php echo $ambienteDeApoio->getTitulo();?></b></h4>
                                </a>

                                <div class="pull-left">
                                    <a class="btn btn-primary btn-sm" href="<?php echo $ambienteDeApoio->getUrlProjeto()?>" target="_blank"><i class='fa fa-external-link'></i> Ir ao site</a>

                                    <?php if($ambienteDeApoio->getSiteStatus()):?>                                
                                        <a class="btn btn-default btn-sm livepreview" href="<?php echo $ambienteDeApoio->getUrlProjeto();?>" target="_blank" data-trigger="click" data-tag="#preview<?php echo $ambienteDeApoio->getId();?>" target="_blank"><i class='fa fa-eye'></i> pre-visualizar</a>
                                        <div id="preview<?php echo $ambienteDeApoio->getId();?>"></div>
                                    <?php else:?>
                                        <i class="link-vermelho fa fa-chain-broken"> <b>fora do ar</b></i>
                                    <?php endif;?>
                                </div>
                                
                                <div class="pull-left margin-top-10">
                                    <p><?php echo $this->readMore($this->escape($ambienteDeApoio->getDescricao()), 400);?></p>
                                </div>
                                
                                <div class="pull-right">
                                    <a class="btn btn-warning btn-sm" href="<?php echo $ambienteDeApoio->getUrl()?>" target="_blank"><i class='fa fa-download'></i> Baixar</a>
                                </div> 
                                
                            </div>
                            
                        </div>
                    </div>
                </article>
            <?php endforeach;?>

        </div>

        <div class="margin-top-05 margin-bottom-05">
            <?php echo $this->paginationControl($this->ambientesDeApoio,"Sliding","_componentes/_pagination_ajax.php", array("divContent" => "#conteudo-ambientes-apoio"));?>
        </div>
        
    </div>
</div>