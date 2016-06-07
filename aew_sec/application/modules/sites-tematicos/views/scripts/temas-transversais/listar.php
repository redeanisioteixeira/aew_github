<div class="row">
    <div id="lista-sites-tematicos" class="panel panel-danger">

        <h4 class="panel-heading margin-none text-center">Sites temáticos referidos a <b class="link-vermelho"><?php echo $this->disciplina->getNome().($this->disciplina->getId() == 38 ? " e Literatura" : "");?></b></h4>

        <div class="panel-body">

            <?php if($this->conteudos):?>
                <?php echo $this->paginationControl($this->conteudos,"Sliding","_componentes/_pagination_ajax.php", array("divContent" => "#lista-sites-tematicos"));?>
            <?php endif;?>

            <?php foreach($this->conteudos as $conteudo):?>

                <article class="col-lg-12 col-md-12 col-sm-12 col-xs-12 page-header vermelho">

                    <div class="col-lg-3 col-md-3 col-sd-3 text-center">
                        <figure class="col-lg-12">
                            <div class="row">
                                <a href="<?php echo $conteudo->getLinkPerfil();?>">
                                    <img class="img-circle img-responsive cor-canal-fundo shadow-center margin-auto" src="/assets/img/icones/<?php echo $conteudo->getFormato()->getConteudoTipo()->getIconeTipo('png')?>.png" width="50%">
                                </a>
                            </div>
                        </figure>

                        <div class="col-lg-12">
                            <div class="row">
                                <?php echo $this->showEstrelas($conteudo, '', false);?>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="row">
                                <span class="box-badge"><span class="badge badge-bottom" title="Visualizações" alt="Visualizações"><i class="fa fa-search"></i> (<?php echo $conteudo->getAcessos();?>)</span></span>
                                <span class="box-badge"><span class="badge badge-bottom" title="Comentários" alt="Comentários"><i class="fa fa-comment"></i> (<?php echo count($conteudo->getComentarios());?>)</span></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-9 col-md-9 col-sd-9 margin-top-10">
                        <div class="row">
                            <h6 class="page-publisher">Publicado em <?php echo $this->SetupDate($conteudo->getDataCriacao());?> por <b><?php echo $conteudo->getUsuarioPublicador()->getNome();?></b></h6>

                            <a href="<?php echo $conteudo->getLinkPerfil();?>">
                                <h4 class="menu-vermelho" title="<?php echo $conteudo->getTitulo();?>"><b><?php echo $conteudo->getTitulo();?></b></h4>
                            </a>

                            <div class="page-header">
                                <a class="btn btn-xs btn-primary" href="<?php echo $conteudo->getSite()?>" target="_blank"><i class="fa fa-external-link"></i> Ir ao site</a>

                                <?php if($conteudo->getSiteStatus()):?>                                
                                    <a class="btn btn-xs btn-default livepreview" href="<?php echo $conteudo->getSite()?>" target="_blank" data-trigger="click" data-tag="#preview<?php echo $conteudo->getId();?>" target="_blank"><i class="fa fa-eye"></i> pre-visualizar</a>
                                    <div id="preview<?php echo $conteudo->getId();?>"></div>
                                <?php else:?>
                                    <b class="link-vermelho"><i class="fa fa-chain-broken"></i> fora do ar</b>
                                <?php endif;?>

                            </div>    

                            <div class="text-justify">
                                <?php echo $this->readMore($conteudo->getDescricao(), 600, null , null, $conteudo->getLinkPerfil(), 'danger', "Saiba mais");?>
                            </div>

						    <?php if($conteudo->selectComponentesCurriculares()):?>
						        <div class="col-lg-12 margin-top-10">
						            <div class="row">
						                <label class="link-preto">
						                    <b><i class="fa fa-graduation-cap"></i> Componentes, Disciplina e/ou Ano/Série</b>
						                </label>
						                <div>
						                    <?php echo $this->showComponentes($conteudo->selectComponentesCurriculares());?>
						                </div>
						            </div>
						        </div>
						    <?php endif;?>

						    <?php if($conteudo->selectTags()):?>
		                        <div class="col-lg-12 margin-top-10">
									<div class="row">
		                            	<label class="link-preto"><b><i class="fa fa-tags"></i> Tags</b></label>
			                            <span><?php echo $this->showTags($conteudo->selectTags(), true);?></span>
									</div>
		                        </div>
						    <?php endif;?>

                        </div>
                    </div>
                </article>
            <?php endforeach;?>

            <?php if($this->conteudos):?>
                <?php echo $this->paginationControl($this->conteudos,"Sliding","_componentes/_pagination_ajax.php", array("divContent" => "#lista-sites-tematicos"));?>
            <?php endif;?>

        </div>

    </div>
</div>
