<div id="conteudo-ambientes-apoio" class="col-lg-12">
    <div class="row">

        <div class="col-lg-12 margin-top-05 margin-bottom-05">
            <?php echo $this->paginationControl($this->ambientesDeApoio,"Sliding","_componentes/_pagination_ajax.php", array("divContent" => "#conteudo-ambientes-apoio"));?>
        </div>

        <?php foreach($this->ambientesDeApoio as $ambienteDeApoio):?>

            <article class="page-header amarelo col-lg-12 col-md-12 col-sm-12 col-xs-12">

                <div class="row">
                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 text-center margin-bottom-10">

                        <a href="<?php echo $ambienteDeApoio->getUrlExibir($this->comunidade);?>">
                            <figure class="img-formato img-rounded" style="background-image: url(<?php echo $ambienteDeApoio->getImagemAssociadaUrl().DS.$ambienteDeApoio->getId();?>.png)"></figure>
                        </a>

                        <?php echo $this->showEstrelas($ambienteDeApoio, "", false);?>

                        <div class="col-lg-12">
                            <div class="row">
                                <span class="box-badge"><span class="fa fa-search badge badge-bottom"  title="Visualizações" alt="Visualizações"> (<?php echo $ambienteDeApoio->getAcessos();?>)</span></span>
                                <span class="box-badge"><span class="fa fa-comment badge badge-bottom" title="Comentários" alt="Comentários"> (<?php echo count($ambienteDeApoio->getComentarios());?>)</span></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
                        <h6 class="page-publisher margin-none"><small><i class="fa fa-calendar"></i> Publicado em <?php echo $this->SetupDate($ambienteDeApoio->getDataCriacao());?> por <b><?php echo $ambienteDeApoio->getUsuarioPublicador()->getNome();?></b></small></h6>

                        <a href="<?php echo $ambienteDeApoio->getAmbientedeApoioCategoria()->getUrlExibir();?>">
                            <small class="uppercase link-amarelo" <?php echo $this->corfonte;?>><b><i class="fa fa-ellipsis-v"></i> <?php echo $ambienteDeApoio->getAmbientedeApoioCategoria()->getNome();?></b></small>
                        </a>

                        <a href="<?php echo $ambienteDeApoio->getUrlExibir($this->comunidade);?>">
                            <h4 class="menu-marron" title="<?php echo $ambienteDeApoio->getTitulo();?>"><b><?php echo $ambienteDeApoio->getTitulo();?></b></h4>
                        </a>

                        <a class="btn btn-primary btn-sm" href="<?php echo $ambienteDeApoio->getUrlProjeto();?>" target="_blank"><i class='fa fa-external-link'></i> Ir ao site</a>

                        <?php if($ambienteDeApoio->getSiteStatus()):?>                                
                            <a class="btn btn-default btn-sm livepreview" href="<?php echo $ambienteDeApoio->getUrlProjeto();?>" target="_blank" data-trigger="click" data-tag="#preview<?php echo $ambienteDeApoio->getId();?>" target="_blank"><i class='fa fa-eye'></i> pre-visualizar</a>
                            <div id="preview<?php echo $ambienteDeApoio->getId();?>"></div>
                        <?php else:?>
                            <i class="link-vermelho fa fa-chain-broken"> <b>fora do ar</b></i>
                        <?php endif;?>

                        <p class="margin-top-10"><?php echo $this->readMore($ambienteDeApoio->getDescricao(), 400);?></p>

                        <div class="col-lg-12">
                            <label><b><i class="fa fa-tags"></i> Tags</b></label>
                            <span><?php echo $this->showTags($ambienteDeApoio->selectTags(),true);?></span>
                        </div>

                    </div>
                </div>
            </article>
        <?php endforeach;?>

        <div class="col-lg-12 margin-top-05 margin-bottom-05">
            <?php echo $this->paginationControl($this->ambientesDeApoio,"Sliding","_componentes/_pagination_ajax.php", array("divContent" => "#conteudo-ambientes-apoio"));?>
        </div>
        
    </div>
    
</div>