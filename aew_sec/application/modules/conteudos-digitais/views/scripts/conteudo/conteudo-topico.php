<article class="panel panel-default<?php //echo $this->panelConteudo;?> margin-bottom-10">

    <div class="panel-body padding-none" style="background-color: <?php echo $this->fundodegrade;?>">

        <div class="col-lg-3 text-center margin-bottom-20">
            <div class="row">
                
                <figure class="text-center">
                    <h6 <?php echo $this->fonte;?>><b><?php echo $this->conteudo->getFormato()->getConteudoTipo()->getNome()?></b></h6>
                    
                    <a class="conteudo-topico" href="<?php echo $this->conteudo->getLinkPerfil();?>">
                        <img class="img-circle padding-all-05 shadow-center" src="/assets/img/icones/<?php echo $this->conteudo->getFormato()->getConteudoTipo()->getIconeTipo('png')?>.png" <?php echo $this->fundo;?> width="50%">
                    </a>
                </figure>

                <div class="col-lg-12">
                    <?php echo $this->showEstrelas($this->conteudo, $this->canal, false);?>
                </div>

                <div class="col-lg-12">
                    <span class="box-badge"><span class="badge badge-bottom" title="Visualizações" alt="Visualizações"><i class="fa fa-search"></i> (<?php echo $this->conteudo->getAcessos();?>)</span></span>
                    <span class="box-badge"><span class="badge badge-bottom" title="Comentários" alt="Comentários"><i class="fa fa-comment"></i> (<?php echo count($this->conteudo->getComentarios());?>)</span></span>
                    <?php if($this->conteudo->getQtddownloads()):?>
                        <span class="box-badge"><span class="badge badge-bottom" title="Baixados" alt="Baixados"><i class="fa fa-download"></i> (<?php echo $this->conteudo->getQtddownloads();?>)</span></span>
                    <?php endif;?>
                </div>

                <?php if($this->conteudo->getSite()):?>
                    <div class="col-lg-12 margin-top-10">
                        <a class="btn btn-xs btn-default btn-block uppercase" href="<?php echo $this->conteudo->getSite() ?>" target="_blank"><i class="fa fa-link"></i> Ir para o site</a>
                    </div>
                <?php endif;?>

                <div class="col-lg-12 text-center margin-top-10">
                    <a href="<?php echo $this->conteudo->getConteudoLicenca()->getSiteconteudolicenca();?>">
                        <img class="img-rounded menu-cinza shadow-center" src="<?php echo $this->conteudo->getConteudoLicenca()->getImagemAssociada();?>" height="50"/>
                    </a>
                </div>
                
            </div>
        </div>

        <div class="col-lg-9 padding-all-30 padding-top-10 padding-bottom-10 background-branco">

			<?php echo $this->ShowMarcarNovo($this->conteudo->getDataPublicacao());?>

            <div class="row">
                <h6 class="page-publisher"><small><i class="fa fa-calendar"></i> Publicado em <?php echo $this->SetupDate($this->conteudo->getDataPublicacao());?> por <b><?php echo $this->conteudo->getUsuarioPublicador()->getNome();?></b></small></h6>

                <?php if($this->conteudo->getConteudodigitalCategoria()->getNome()):?>
                    <h6 class="page-publisher uppercase border-none menu-cinza"><b><i class="fa fa-ellipsis-v"></i> <?php echo $this->conteudo->getConteudodigitalCategoria()->getNome();?></b></h6>
                <?php endif;?>

                <a class="<?php echo ($this->topicos ? 'conteudo-topico' : '');?>" href="<?php echo $this->conteudo->getLinkPerfil();?>">
                    <h4 title="<?php echo $this->conteudo->getTitulo();?>" <?php echo $this->fonte;?>>
                        <b><?php echo $this->conteudo->getTitulo();?></b>
                    </h4>
                </a>


                <div class="page-publisher col-lg-12 margin-top-10">
                    <div class="row text-justify">
                        <?php echo $this->readMore($this->conteudo->getDescricao(), 600, null , null, $this->conteudo->getLinkPerfil(), $this->panelConteudo, "Saiba mais");?>
                    </div>
                </div>

                <?php if($this->conteudo->selectComponentesCurriculares()):?>
                    <div class="page-publisher col-lg-12 margin-top-10">
                        <div class="row">
                            <label <?php echo $this->fonte;?>>
                                <b><i class="fa fa-graduation-cap"></i> Componentes, Disciplina e/ou Ano/Série</b>
                            </label>
                            <div>
                                <?php echo $this->showComponentes($this->conteudo->selectComponentesCurriculares());?>
                            </div>
                        </div>
                    </div>
                <?php endif;?>

                <div class="col-lg-12 margin-top-10">
                    <?php echo $this->ShowVisualizar($this->conteudo);?>
                </div>						
                        
                <div class="col-lg-12 margin-top-10">
                    <div class="row">
                        <label <?php echo $this->fonte;?>><b><i class="fa fa-tags"></i> Tags</b></label>
                        <span><?php echo $this->showTags($this->conteudo->selectTags(), true, 0, $this->panelConteudo);?></span>
                    </div>
                </div>
                        
            </div>
        </div>

    </div>

</article>
