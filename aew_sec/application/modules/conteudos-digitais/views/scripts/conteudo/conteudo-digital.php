<?php
    $col = (!isset($this->colLargura) ? "col-lg-4 col-md-4 col-sm-6 col-xs-12" : 'col-lg-'.$this->colLargura.' col-md-'.$this->colLargura.' col-sm-12 col-xs-12');
    
    $favorito = "";
    if($this->usuarioLogado && $this->usuarioLogado->isConteudoFavorito($this->conteudo)):
        $favorito .= " favorito-usuario";
    endif;
    
    if($this->usuarioPerfil && $this->usuarioPerfil->isConteudoFavorito($this->conteudo)):
        if($this->usuarioPerfil instanceof Aew_Model_Bo_Comunidade):
            $favorito .= " favorito-comunidade";
        endif;        
    endif;
?>

<article class="item <?php echo $col?> margin-bottom-05">

	<?php echo $this->ShowMarcarNovo($this->conteudo->getDataPublicacao());?>

    <div class="row">
        <div class="margin-all-05">
            <div class="panel panel-<?php echo ($favorito ? 'default favorito' : $this->panelConteudo);?>" <?php //echo $this->fonte;?>>
                <div class="panel-heading panel-all-05">
                    <a href="<?php echo $this->conteudo->getLinkPerfil();?>" title="<?php echo $this->conteudo->getTitulo();?>" <?php echo ($this->topicos ? 'class="conteudo-topico"' : '');?>>
                        <h4 class="text-center margin-none" <?php echo $this->fonte;?>>
                            <b><?php echo $this->conteudo->getTitulo();?></b>
                        </h4>
                    </a>

                    <?php if(!$this->conteudo->getFlaprovado()):?>
                        <i class="fa fa-question fa-2x shadow-center img-rounded padding-all-05 superior-direita <?php echo $this->menu;?>"></i>
                    <?php endif;?>
                    
                    <?php if($this->conteudo->getDestaque()):?>
                        <i class="fa fa-bell fa-1x shadow-center img-circle padding-all-05 superior-direita <?php echo $this->menu;?>"></i>
                    <?php endif;?>
                        
                </div>
                <div class="panel-body <?php echo $favorito?>">

                    <h6 class="page-publisher"><small><i class="fa fa-calendar"></i> Publicado em <?php echo $this->SetupDate($this->conteudo->getDataPublicacao());?> por <b><?php echo $this->conteudo->getUsuarioPublicador()->getNome();?></b></small></h6>

                    <h6 class="page-publisher margin-bottom-10" <?php echo $this->fonte;?>><b><i class="fa fa-ellipsis-v"></i> <?php echo $this->conteudo->getFormato()->getConteudoTipo()->getNome()?></b></h6>                                        

                    <div class="col-lg-4 col-md-4 col-sm-5 col-xs-5 margin-bottom-20">
                        <figure class="row">
			                <a href="<?php echo $this->conteudo->getLinkPerfil();?>">
			                    <figure class="img-formato shadow-center" style="background-image: url('<?php echo $this->conteudo->getConteudoImagem();?>')" <?php echo $this->fundo;?>></figure>
			                </a>
						</figure>
	                </div>
                    
                    <div class="col-lg-8 col-md-8 col-sm-7 col-xs-7 text-center">

                        <div class="row">
                            
                            <span class="box-badge">
                                <span class="badge badge-bottom" title="Visualizações" alt="Visualizações"><i class="fa fa-search"></i> (<?php echo $this->conteudo->getAcessos();?>)</span>
                            </span>
                            <span class="box-badge">
                                <span class="badge badge-bottom" title="Comentários" alt="Comentários"><i class="fa fa-comment"></i> (<?php echo count($this->conteudo->getComentarios());?>)</span>
                            </span>
                            <span class="box-badge">
                                <span class="badge badge-bottom" title="Baixados" alt="Baixados"><i class="fa fa-download"></i> (<?php echo $this->conteudo->getQtddownloads();?>)</span>
                            </span>
                        </div>
                    </div>
                    
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="row">
                            <?php echo $this->showEstrelas($this->conteudo, $this->canal, false)?>
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        
                        <?php if(isset($this->href['remover_destaque'])):?>
                            <div class="row text-center">
                                <a class="btn btn-danger text-center" title="Remover dos destaques" alt="Remover dos destaques" href="<?php echo $this->href['remover_destaque'].$this->conteudo->getId();?>"><i class="fa fa-bell-slash-o"></i> remover</a>
                            </div>
                        <?php endif;?>
                        
                        <div class="row">
                            
                            <div id="info-conteudo<?php echo $this->conteudo->getId();?>" class="panel-group">

                                <div class="panel panel-none">
                                    <label class="panel-title font-size-90" <?php echo $this->fonte;?>>
                                        <i class="fa fa-caret-square-o-down"></i> <a class="option-accordion" data-toggle="collapse" data-parent="#info-conteudo<?php echo $this->conteudo->getId();?>" href="#info-conteudo-descricao<?php echo $this->conteudo->getId();?>">Ficha técnica</a>
                                    </label>
                                    <div id="info-conteudo-descricao<?php echo $this->conteudo->getId();?>" class="padding-all-05 panel-body panel-collapse collapse in">
                                        <p><?php echo $this->readMore($this->conteudo->getDescricao(), 300);?></p>
                                    </div>
                                </div>

                                <?php if($this->conteudo->selectComponentesCurriculares()):?>
                                    <div class="panel panel-none">
                                        <label class="panel-title font-size-90" <?php echo $this->fonte;?>>
                                            <i class="fa fa-caret-square-o-down"></i> <a class="option-accordion" data-toggle="collapse" data-parent="#info-conteudo<?php echo $this->conteudo->getId();?>" href="#info-conteudo-componentes<?php echo $this->conteudo->getId();?>">Componentes, Disciplina e/ou Ano/Série</a>
                                        </label>
                                        <div id="info-conteudo-componentes<?php echo $this->conteudo->getId();?>" class="padding-all-05 panel-body panel-collapse collapse in">
                                            <?php echo $this->showComponentes($this->conteudo->selectComponentesCurriculares());?>
                                        </div>
                                    </div>
                                <?php endif;?>
                                
                                <?php if($this->conteudo->getConteudoDigitalCategoria()->getNome()):?>
                                    <div class="panel panel-none">
                                        <label class="panel-title font-size-90" <?php echo $this->fonte;?>>
                                            <i class="fa fa-caret-square-o-down"></i> <a class="option-accordion" data-toggle="collapse" data-parent="#info-categoria<?php echo $this->conteudo->getConteudoDigitalCategoria()->getId();?>" href="#info-conteudo-categoria<?php echo $this->conteudo->getId();?>">Categoria</a>
                                        </label>
                                        <div id="info-conteudo-categoria<?php echo $this->conteudo->getId();?>" class="padding-all-05 panel-body panel-collapse collapse out">
                                            <?php echo $this->conteudo->getConteudoDigitalCategoria()->getNome();?>
                                        </div>
                                    </div>                                
                                <?php endif;?>

                                <?php if(!count($this->conteudo->selectTags())):?>

                                    <div class="panel panel-none">
                                        <label class="panel-title font-size-90" <?php echo $this->fonte;?>>
                                            <i class="fa fa-caret-square-o-down"></i> <a class="option-accordion" data-toggle="collapse" data-parent="#info-conteudo<?php echo $this->conteudo->getId();?>" href="#info-conteudo-tags<?php echo $this->conteudo->getId();?>">Tags</a>
                                        </label>

                                        <div id="info-conteudo-tags<?php echo $this->conteudo->getId();?>" class="padding-all-05 panel-body panel-collapse collapse out">
                                            <?php echo $this->showTags($this->conteudo->getTags(), true, 0, $this->panelConteudo);?>
                                        </div>
                                    </div>
                                <?php endif;?>
                                
                            </div> <!-- panel-group -->
                            
                        </div>
                        
                    </div> <!-- col-lg-12 -->

                </div> <!-- panel-body -->

                <?php if($this->conteudo->getSite() || $this->conteudo->getConteudoDownloadUrl() || $this->conteudo->getConteudoVisualizacaoUrl()):?>
                
                    <div class="panel-footer padding-all-05">
                        <div class="inline">

                            <div class="btn-group btn-group-sm">
                                <?php if($this->conteudo->getSite()):?>
                                    <a class="btn btn-default" target="_blank" href="<?php echo $this->conteudo->getSite();?>">
                                        <i title="ir ao site" alt="ir ao site" class="fa fa-link"></i>
                                    </a>
                                <?php endif;?>

                                <?php if($this->conteudo->getConteudoDownloadUrl() || $this->conteudo->getConteudoVisualizacaoUrl()):?>

									<?php if($this->conteudo->getIncorporarConteudoUrl()):?>
		                                <a class="btn btn-primary" data-toggle="modal" data-target="#modalGeral" href="<?php echo $this->conteudo->getIncorporarConteudoUrl();?>">
		                                    <i title="visualizar" alt="visualizar" class="fa fa-play"></i>
		                                </a>
									<?php endif;?>

                                    <?php if($this->conteudo->getConteudoDownloadUrl()):?>
                                        <a class="btn btn-<?php echo $this->panelConteudo;?>" href="<?php echo $this->conteudo->getConteudoDownloadUrl(true);?>">
                                            <i title="baixar arquivo" alt="baixar arquivo" class="fa fa-download"></i>
                                        </a>
                                    <?php endif;?>

                                <?php endif;?>
                            </div>

                            <?php if($this->conteudo->getConteudoDownloadUrl()):?>
                                <?php $attr = $this->conteudo->getAtributosArquivo($this->conteudo->getConteudoDownloadPath());?>

                                <div class="margin-left-05">
                                    <?php if(isset($attr["duration"])):?>
                                        <small title='Duração' class='link-cinza-escuro margin-right-10'><b><i class="fa fa-clock-o"></i> <?php echo $attr['duration'];?></b></small>
                                    <?php endif;?>

                                    <?php if(isset($attr["filesize"])):?>
                                       <small title="Tamanho arquivo" class='link-cinza-escuro margin-right-10'><b><i class="fa fa-hdd-o"></i> <?php echo $attr['filesize'];?></b></small>
                                    <?php endif;?>
                                </div>

                            <?php endif;?>
                            
                        </div>
                    </div> <!-- panel-footer -->
                    
                <?php endif;?>

            </div> <!-- col-style -->

        </div> 
    </div><!-- row -->
</article>
