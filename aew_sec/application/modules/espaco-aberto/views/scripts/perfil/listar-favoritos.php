<?php if(!$this->conteudosFavoritos && !$this->ambientesFavoritos):?>
    <?php return;?>
<?php endif;?>

<section class="favoritos">
    <h4 class="headline-ea link-verde"><b><i class="fa fa-heart"></i> Favoritos</b></h4>
    <div id="tab-favoritos" class="box padding-all-05">
        <ul class="nav nav-tabs nav-justified" role="tablist">

            <?php if($this->conteudosFavoritos):?>
                <li role="presentation"><a href="#favoritos-cd" aria-controls="favoritos conteúdos digitais" role="tab" data-toggle="tab"><h4 class="margin-none link-cinza-escuro"><i class="fa fa-cubes"></i> conteúdos digitais</h4></a></li>
            <?php endif;?>

            <?php if($this->ambientesFavoritos):?>
                <li role="presentation"><a href="#favoritos-ap" aria-controls="favoritos ambientes de apoio" role="tab" data-toggle="tab"><h4 class="margin-none link-cinza-escuro"><i class="fa fa-wrench"></i> apoio a produção e colaboração</h4></a></li>
            <?php endif;?>

        </ul>

        <div class="tab-content scroll-x padding-top-10" style="max-height: 300px;">
            <ul id="favoritos-cd" role="tabpanel" class="list-unstyled tab-pane fade">
                <?php foreach ($this->conteudosFavoritos as $favorito):
                        if($favorito->getConteudoDigitalCategoria()->getCanal()->getId() == 1):
                            $corfundo = "bgcolor = 'menu-marron'";
                            $corfonte = "fcolor  = 'menu-marron'";
                        else:
                            $corfundo = "bgcolor = 'menu-".($favorito->getFlSiteTematico() == true ? "vermelho" : "azul")."'";
                            $corfonte = "fcolor  = 'menu-".($favorito->getFlSiteTematico() == true ? "vermelho" : "azul")."'";
                        endif;
                    ?>
                    <li class="border-bottom padding-bottom-10 padding-top-10 col-lg-12">
                        <div class="row">
                            <figure class="col-lg-2 col-md-2 col-sm-12 col-xs-12 text-center margin-bottom-20">
                                <a href="<?php echo $favorito->getLinkPerfil()?>">
                                    <img class="img-rounded img-responsive shadow" src="<?php echo $favorito->getConteudoImagem()?>" alt="icone arquivo" <?php echo $corfundo;?>>
                                </a>
                            </figure>
                            <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
                                <h6 class="page-publisher">
                                    <small><i class="fa fa-calendar"></i> Publicado em <?php echo $this->SetupDate($favorito->getDataCriacao());?> por <b><?php echo $favorito->getUsuarioPublicador()->getNome();?></b>
                                    </small>
                                </h6>
                                <a href="<?php echo $favorito->getLinkPerfil()?>">
                                    <h5 <?php echo $corfonte;?>><b><?php echo $favorito->getTitulo();?></b></h5>
                                </a>
                                <p>
                                    <?php echo $this->readMore($favorito->getDescricao(),140);?>
                                </p> 
                            </div>
                        </div>
                    </li>
                <?php endforeach;?>
            </ul>
            <ul id="favoritos-ap" role="tabpanel" class="list-unstyled tab-pane fade">
                <?php foreach ($this->ambientesFavoritos as $favorito): ?>
                    <?php
                        $corfundo = "bgcolor = 'menu-amarelo'";
                        $corfonte = "fcolor  = 'menu-amarelo'";
                    ?> 

                    <li class="border-bottom padding-bottom-10 padding-top-10 col-lg-12">
                        <div class="row">
                            <figure class="col-lg-2 col-md-2 col-sm-12 col-xs-12 text-center margin-bottom-20">
                                <a href="<?php echo $favorito->getLinkPerfil()?>">
                                    <img class="img-rounded shadow img-responsive" src="<?php echo $favorito->getImagemAssociadaUrl().DS.$favorito->getIdAmbientedeApoio();?>.png" alt="icone arquivo" <?php echo $corfundo;?>>
                                </a>
                            </figure>

                            <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
                                <h6 class="page-publisher"><small><i class="fa fa-calendar"></i> Publicado em <?php echo $this->SetupDate($favorito->getDataCriacao());?> por <b><?php echo $favorito->getUsuarioPublicador()->getNome();?></b></small></h6>

                                <a href="<?php echo $favorito->getLinkPerfil()?>">
                                    <h5 <?php echo $corfonte;?>><b><?php echo $favorito->getTitulo();?></b></h5>
                                </a>

                                <p>
                                    <?php echo $this->readMore($favorito->getDescricao(),140);?>
                                </p>
                            </div>
                        </div>
                    </li>

                <?php endforeach;?>
            </ul>

        </div>
            
    </div>
</section>
