<?php if($this->topicos):?>
	<?php if($this->pageTitle != ""):?>
		<header class="page-header">
		    <h4 class="headline" <?php echo $this->corfonte;?>><b><?php echo $this->pageTitle;?></b></h4>
		</header>
	<?php endif;?>
<?php endif;?>

<article id="lista_conteudos" class="col-lg-<?php echo ($this->topicos ? '12' : '8');?>">
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-body">

				<?php echo $this->ShowMarcarNovo($this->conteudo->getDataPublicacao());?>

                <h6 class="page-publisher">

                    <small>
                        <i class="fa fa-calendar"></i> Publicado em <?php echo $this->SetupDate($this->conteudo->getDataPublicacao());?> por <b><?php echo $this->conteudo->getUsuarioPublicador()->getNome();?></b>
                    </small>
                </h6>
                
                <?php echo $this->ShowVisualizar($this->conteudo);?>

                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    
                    <div class="row">
                        
                        <div class="col-style text-center">
                            <!-- Icone, estrelas, opções e código QR -->
                            <div class="col-lg-12 col-md-4 col-sm-4 col-xs-12">
                                <figure class="row">
                                    <h6 class="uppercase" <?php echo $this->corfonte;?>><b><?php echo $this->conteudo->getFormato()->getConteudoTipo()->getNome();?></b></h6>
                                    <img class="img-circle padding-all-05" src="/assets/img/icones/<?php echo $this->escape($this->conteudo->getFormato()->getConteudoTipo()->getIconeTipo())?>.png" <?php echo $this->corfundo;?> width="64" height="64" alt="icone arquivo">
                                </figure>
                            </div>

                            <div class="margin-top-10 col-lg-12 col-md-8 col-sm-8 col-xs-12">
                                <span class="box-badge"><span class="badge badge-bottom" title="Visualizações" alt="Visualizações"><i class="fa fa-search"></i> (<?php echo $this->conteudo->getAcessos();?>)</span></span>
                                <span class="box-badge"><span class="badge badge-bottom" title="Baixados" alt="Baixados"><i class="fa fa-download"></i> (<?php echo $this->conteudo->getQtdDownloads();?>)</span></span >
                                <span class="box-badge"><span class="badge badge-bottom" title="Comentários" alt="Comentários"><i class="fa fa-comment"></i> (<?php echo count($this->conteudo->getComentarios());?>)</span></span>
                            </div>

                            <div class="margin-top-10 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <?php echo $this->showEstrelas($this->conteudo);?>
                            </div>

                            <!--  Codigo QR -->
                            <div class="margin-top-10 margin-bottom-10 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <figure><?php echo $this->ShowQRCode($this->conteudo->getLinkPerfil(true), array('height'=>100, 'width'=>100), $this->conteudo->getId())?></figure>
                            </div>
                            
                            <?php echo $this->render('_componentes/_menu-lateral-conteudo.php');?>                            
                            
                        </div>
                        
                    </div>
                    
                </div>

                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 <?php echo (!$this->conteudo->getFlaprovado() ? "nao-aprovado" : "");?>">

                    <?php echo $this->render('_componentes/_botoes-opcoes-usuario.php');?>

                    <span class="row">
                        <label class="uppercase" <?php  echo $this->corfonte;?>><i class="fa fa-ellipsis-v"></i> Ficha técnica</label> 
                        <p class="text-justify">
                            <?php echo $this->conteudo->getDescricao();?>
                        </p>
                    </span>

                    <?php if($this->conteudo->getSite()):?>
                        <span class="row">
                            <?php if($this->conteudo->getSiteStatus()):?>                                
                                <a class="btn btn-sm btn-default livepreview" href="<?php echo $this->conteudo->getSite();?>" data-trigger="click" data-tag="#preview<?php echo $this->conteudo->getId();?>" target="_blank"><i class='fa fa-eye'></i> Pre-Visualizar</a>
                                <div id="preview<?php echo $this->conteudo->getId();?>"></div>
                            <?php else:?>
                                <i class="link-vermelho fa fa-chain-broken"> <b>fora do ar</b></i>
                            <?php endif;?>
                        </span>
                    <?php endif;?>

					<?php if(count($this->conteudo->getNiveisEnsino())):?>
		                <span class="row">
		                    <label class="uppercase" <?php echo $this->corfonte;?>><i class="fa fa-ellipsis-v"></i> Nível de Ensino/Modalidade</label>
		                    <?php echo $this->showNiveis($this->conteudo->getNiveisEnsino());?>
		                </span>
                    <?php endif;?>

                    <span class="row">
                        <label class="uppercase" <?php echo $this->corfonte;?>><i class="fa fa-ellipsis-v"></i> Componentes, Disciplina e/ou Ano/Série</label>
                        <?php echo $this->showComponentes($this->conteudo->getComponentesCurriculares());?>
                    </span>

                    <?php if($this->conteudo->getAutores()):?>
                        <span class="row">
                            <label class="uppercase" <?php echo $this->corfonte;?>><i class="fa fa-ellipsis-v"></i> Autores</label>
                            <p><?php echo $this->escape($this->conteudo->getAutores());?></p>
                        </span>
                    <?php endif;?>

                    <?php  if($this->conteudo->getFonte()):?>
                        <span class="row">
                            <label class="uppercase" <?php echo $this->corfonte;?>><i class="fa fa-ellipsis-v"></i> Fonte</label>
                            <p><?php echo $this->escape($this->conteudo->getFonte());?></p>
                        </span>
                    <?php endif;?>

                    <?php if($this->conteudo->getAcessibilidade()):?>
                        <span class="row">
                            <label class="uppercase" <?php echo $this->corfonte;?>><i class="fa fa-ellipsis-v"></i> Acessibilidade</label>
                            <p><?php echo $this->escape($this->conteudo->getAcessibilidade());?></p>
                        </span>
                    <?php endif;?>
                    
                    <?php if($this->conteudo->getDataCriacao()):?>
                        <span class="row">						
                            <label class="uppercase" <?php echo $this->corfonte;?>><i class="fa fa-ellipsis-v"></i> Data de criação</label>
                            <p><?php echo $this->SetupDate($this->conteudo->getDataCriacao(), 2);?></p>
                        </span>
                    <?php endif;?>
                    
                    <span class="row">
                        <label class="uppercase" <?php echo $this->corfonte;?>><i class="fa fa-ellipsis-v"></i> Tipo de Licença</label>
                        <p><?php echo $this->conteudo->getConteudoLicenca()->getNome();?></p>
                        <p>
                            <a href="<?php echo $this->conteudo->getConteudoLicenca()->getSiteconteudolicenca();?>">
                                <img class="img-rounded menu-cinza shadow-center" src="<?php echo $this->conteudo->getConteudoLicenca()->getImagemAssociada();?>" height="60"/>
                            </a>
                        </p>
                    </span>

                    
                    <?php if($this->conteudo->getSite()):?>
                        <div class="menu-cinza text-center padding-all-10 rounded shadow">
                            <h5><b><i class="fa fa-hand-paper-o"></i> Este conteúdo é um link externo e não está sob responsabilidade dos administradores deste sistema</b></h5>
                        </div>
                    <?php endif;?>
                    
                </div>
                
            </div>
            
        </div>
        
        <?php if($this->conteudo->getFlaprovado() && !$this->topicos):?>
            <?php echo $this->render('/usuario/box-list-comentarios.php');?>
        <?php endif;?>
        
    </div>
    
</article>

<aside class="col-lg-<?php echo ($this->topicos ? '12' : '4');?>">
    <div class="col-lg-12">
        <div class="row">
            <h4 class="uppercase" <?php echo $this->corfonte;?>>
                <b><i class="fa fa-tags"></i> Tags</b>
            </h4>
            <div class="panel panel-default">
                <div class="panel-body">
                    <?php echo $this->showTags($this->conteudo->getTags(), true);?>
                </div>
            </div>
        </div>
    </div>

    <?php if(count($this->relacionados)):?>
        <div class="col-lg-12">
            <div id="relacionados" class="row">
                <?php echo $this->relacionados;?>
            </div>
        </div>
    <?php endif;?>
    
</aside>
