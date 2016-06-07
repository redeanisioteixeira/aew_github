<?php $this->render('home/slider/_slider-inicio.php');?>

<!-- Slides de destaque -->
<?php echo $this->placeholder('slidePrincipal');?>

<?php //echo $this->render('_componentes/_busca.php');?>

<?php echo $this->render('home/destaques/video-destaque.php');?>

<?php //echo $this->render('home/destaques/paralax-destaque.php');?>

<!-- Disciplinas -->
<section class="container">
    <h3 id="disciplinas" class="headline"><b>Encontre conteúdos digitais por disciplinas, temas transversais ou tipos de conteúdo</b></h3>
    <div class="panel panel-default">

        <div class="panel-body">

            <!-- Nav tabs -->
            <ul id="tab-disciplinas-temas" class="nav nav-pills nav-justified" role="tablist">
                <li role="presentation" class="active"><a href="#tab-disciplinas" aria-controls="tab-disciplinas" role="tab" data-toggle="tab"><h4 class="margin-none"><b><i class="fa fa-graduation-cap"></i> Disciplinas</b></h4></a></li>
                <li role="presentation"><a href="#tab-temas" aria-controls="tab-temas" role="tab" data-toggle="tab"><h4 class="margin-none"><b><i class="fa fa-venus-mars"></i> Temas transversais</b></h4></a></li>
                <li role="presentation"><a href="#tab-tipos" aria-controls="tab-tipos" role="tab" data-toggle="tab"><h4 class="margin-none"><b><i class="fa fa-dot-circle-o"></i> Tipos de conteúdos</b></h4></a></li>
            </ul>

            <div class="tab-content">

                <div id="tab-disciplinas" role="tabpanel" class="tab-pane fade active in">

                    <?php $i = 1;?>
                    <ul class="disciplina-main list-unstyled">
                        <?php foreach($this->disciplinas as $disciplina):?>

                            <?php if($disciplina->getId() != 31):?>
                                <li class="height-170 disciplina-opcao disciplina<?php echo $disciplina->getId();?> col-lg-2 col-md-2 col-sm-6 col-xs-12 text-center <?php echo ($i < 7 ? 'top':'bottom');?>">
                                    <a class="disciplina<?php echo ($this->tipoDispositivo != 'desktop' ? '-acesso' : '');?>" disciplina="<?php echo $disciplina->getId();?>" <?php echo ($this->tipoDispositivo != 'desktop' ? 'href="/conteudos-digitais/disciplinas/topicos/id/'.$disciplina->getId().'"' : '');?>>
										<figure class="disciplina<?php echo $disciplina->getId();?> img-formato rounded trans80 shadow-center menu-<?php echo $this->cor;?>" style="background-image: url('/assets/img/icones_disciplinas/icone_disciplina<?php echo $disciplina->getId();?>.png')" <?php echo $this->fundo;?>></figure>                                        
                                        <h5 class="menu-<?php echo $this->cor;?>"><b><?php echo $disciplina->getNome().($disciplina->getId() == 38 ? " e Literatura" : "");?></b></h5>
                                    </a>
                                </li>

                                <?php if($i == 6):?>
                                    <li class="disciplina-conteudo col-lg-12 margin-bottom-10 desativado"></li>
                                    <?php $this->cor = "preto";?>
                                <?php endif;?>

                                <?php $this->cor = ($this->cor == "preto" ? "cinza" : "preto"); $i++;?>

                            <?php endif;?>
                        <?php endforeach;?>

                    </ul>
                </div>

                <div id="tab-temas" role="tabpanel" class="tab-pane fade">
                    <?php $this->cor = "preto"; $i = 1;?>
                    <ul class="list-unstyled">
                        <?php foreach($this->temastransversais as $disciplina):?>
                            <li class="height-170 <?php echo($i == 7 ? 'col-lg-offset-3' : '');?> col-lg-2 col-md-2 col-sm-6 col-xs-12 text-center">
                                <label class="block">
									<input name="opcao-item" type="checkbox" class="desativado" id="componente<?php echo $disciplina->getId();?>" value="<?php echo $disciplina->getId();?>">
									<figure class="img-formato rounded trans80 shadow-center menu-<?php echo $this->cor;?>" style="background-image: url('/assets/img/icones_disciplinas/icone_disciplina<?php echo $disciplina->getId();?>.png')" <?php echo $this->fundo;?>></figure>
                                    <h5 class="menu-<?php echo $this->cor;?>"><b><?php echo $disciplina->getNome().($disciplina->getId() == 38 ? " e Literatura" : "");?></b></h5>
                                </label>
                            </li>

                            <?php if($i == 6):?>
                                <?php $this->cor = "preto";?>
                            <?php endif;?>

                            <?php $this->cor = ($this->cor == "preto" ? "cinza" : "preto"); $i++;?>
                        <?php endforeach;?>
                    </ul>
                </div>

				<div id="tab-tipos" role="tabpanel" class="tab-pane fade">
                    <?php $this->cor = "preto"; $i = 1;?>
                    <ul class="list-unstyled">
                        <?php foreach($this->tiposConteudo as $tipoConteudo):?>

                            <li class="height-170 <?php echo($i == 7 ? 'col-lg-offset-2' : '');?> col-lg-2 col-md-2 col-sm-6 col-xs-12 text-center">
		                        <label class="block trans80">
									<input name="tipo-conteudo" type="checkbox" class="desativado" id="tipoconteudo<?php echo $tipoConteudo->getId();?>" value="<?php echo $tipoConteudo->getId();?>">
									<figure class="img-formato shadow-center menu-<?php echo $this->cor;?>" style="background-image: url('/assets/img/icones/<?php echo $tipoConteudo->getIconeTipo();?>.png')" <?php echo $this->fundo;?>></figure>
									<h5 class="menu-<?php echo $this->cor;?>"><b><?php echo $tipoConteudo->getNome();?></b></h5>
		                        </label>
                            </li>

                            <?php if($i == 6):?>
                                <?php $this->cor = "preto";?>
                            <?php endif;?>

                            <?php $this->cor = ($this->cor == "preto" ? "cinza" : "preto"); $i++;?>
                        <?php endforeach;?>
                    </ul>

				</div>
            </div>
        </div>
    </div>
    
</section>

<section class="container">
    <!-- Destaques -->
    <?php if($this->rssDestaques):?>
        <div id="destaques">
            <h3 class="headline"><b>Destaques</b></h3>
            <div id="itens" class="itens-isotope">
                <?php foreach($this->rssDestaques as $entrada):?>

                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 text-center">
                        <div class="row">
                            <div class="col-style">
                                <a href="<?php echo $entrada['link'];?>">
									<figure class="img-formato img-formato-70 rounded shadow-center menu-<?php echo $entrada['color']?>" style="background-image: url(<?php echo $entrada['img']->url;?>)"></figure>
                                </a>
                                <a class="btn btn-readmore menu-<?php echo $entrada['color']?>" href="<?php echo $entrada['link']?>" role="button">Leia <i class="fa fa-plus-circle fa-1x"></i></a>
                                <h4 class="menu-<?php echo $entrada['color']?>"><b><?php echo $entrada['title'];?></b></h4>
                                <p><?php echo $this->readMore($entrada['description'],180);?></p>
                            </div>
                        </div>                                
                    </div>

                <?php endforeach;?>
            </div>
        </div>
    <?php endif;?>
    
    <!-- Recentes  -->
    <?php if($this->rssRecentes):?>
        <div id="recentes">
            <h3 class="headline"><b>Mais Recentes</b></h3>	
            <div id="itens" class="itens-isotope">
                <?php foreach($this->rssRecentes as $entrada):?>			

                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 text-center">
                        <div class="row">
                            <div class="col-style">
                                <a href="<?php echo $entrada['link'];?>">
									<figure class="img-formato img-formato-70 rounded shadow-center menu-<?php echo $entrada['color']?>" style="background-image: url(<?php echo $entrada['img']->url;?>)"></figure>
                                </a>
                                <a class="btn btn-readmore menu-<?php echo $entrada['color']?>" href="<?php echo $entrada['link']?>" role="button">Leia <i class="fa fa-plus-circle fa-1x"></i></a>
                                <h4 class="menu-<?php echo $entrada['color']?>"><b><?php echo $entrada['title'];?></b></h4>
                                <p><?php echo $this->readMore($entrada['description'],180);?></p>
                            </div>
                        </div>                                
                    </div>

                <?php endforeach;?>
            </div>
        </div>
    <?php endif;?>
    
    <div class="row">
        <hr>

        <!-- Mais Vistos -->
        <?php if($this->rssVistos):?>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div id="mais-vistos" class="panel panel-default">

                    <div class="panel-heading">
                        <h4 class="margin-none"><b><i class="fa fa-eye"></i> mais vistos</b></h4>
                    </div>

                    <ul class="panel-body list-inline list-unstyled margin-none padding-none">

                        <?php $posicao = 1;?>
                        <?php foreach ($this->rssVistos as $entrada):?>

                            <li class="border-bottom padding-bottom-10 padding-top-10 overflow-hidden col-lg-12">
                                <div class="col-lg-1 col-md-1 col-sm-2 col-xs-2 text-center">
                                    <b class="font-size-300 menu-cinza-claro shadow numero-destacado"><?php echo $posicao++;?></b>
                                </div>

                                <div class="col-lg-11 col-md-11 col-sm-10 col-xs-10">

                                    <h6 class="page-publisher"><small><i class="fa fa-calendar"></i> Publicado em <?php echo $this->SetupDate(date($entrada['published']));?> por <b><?php echo $entrada['author']['name'];?></b></small></h6>
                                    
                                    <div class="col-lg-3 col-md-4 col-sm-5 col-xs-12 margin-bottom-10">
                                        <div class="row">
                                            <a href="<?php echo $entrada['link'];?>">
                                                <figure class="img-formato rounded shadow-center menu-<?php echo $entrada['color']?>" style="background-image: url(<?php echo $entrada['img']->url;?>)"></figure>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="col-lg-9 col-md-8 col-sm-7 col-xs-12">
                                        <div class="row1">
                                            
                                            <?php if($entrada['categorianome']):?>
                                                <a class="link-amarelo" href="<?php echo $entrada['categoriaurl'];?>">
                                                    <h6 class="page-publisher uppercase border-none">
                                                        <b><i class="fa fa-ellipsis-v"></i> <?php echo $entrada['categorianome'];?></b>
                                                    </h6>
                                                </a>
                                            <?php endif;?>
                                            
                                            <a class="link-<?php echo $entrada['color']?>" href="<?php echo $entrada['link'];?>">
                                                <h4 class="margin-top-none"><b><?php echo $entrada['title'];?></b></h4>
                                            </a>
                                            
                                            <p><?php echo $this->readMore($entrada['description'], 150);?></p>

                                            <div class="text-center">
                                                <span class="box-badge"><span class="badge badge-bottom" title="Visualizações" alt="Visualizações"><i class="fa fa-search"></i> (<?php echo $entrada['acessos'];?>)</span></span>
                                                <span class='box-badge'><span class="badge badge-bottom" title='Baixados' alt='Baixados'><i class="fa fa-download"></i> (<?php echo $entrada['baixados'];?>)</span></span>
                                                <span class='box-badge'><span class="badge badge-bottom" title='Comentários' alt='Comentários'><i class="fa fa-comment"></i> (<?php echo $entrada['comentarios'];?>)</span></span>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </li>	
                        <?php endforeach;?>
                    </ul>
                </div>
            </div>
        <?php endif;?>

        <!-- Mais Votados -->
        <?php if($this->rssVotados):?>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div id="mais-votados" class="panel panel-default">

                    <div class="panel-heading">
                        <h4 class="margin-none"><b><i class="fa fa-star"></i> mais votados</b></h4>
                    </div>

                    <ul class="panel-body list-inline list-unstyled margin-none padding-none">

                        <?php $posicao = 1;?>
                        <?php foreach ($this->rssVotados as $entrada):?>

                            <li class="border-bottom padding-bottom-10 padding-top-10 overflow-hidden col-lg-12">
                                <div class="col-lg-1 col-md-1 col-sm-2 col-xs-2 text-center">
                                    <b class="font-size-300 menu-cinza-claro shadow numero-destacado"><?php echo $posicao++;?></b>
                                </div>

                                <div class="col-lg-11 col-md-11 col-sm-10 col-xs-10">

                                    <h6 class="page-publisher"><small><i class="fa fa-calendar"></i> Publicado em <?php echo $this->SetupDate(date($entrada['published']));?> por <b><?php echo $entrada['author']['name'];?></b></small></h6>
                                    
                                    <div class="col-lg-3 col-md-4 col-sm-5 margin-bottom-10">
                                        <div class="row">
                                            <a href="<?php echo $entrada['link'];?>">
                                                <figure class="img-formato rounded shadow-center menu-<?php echo $entrada['color']?>" style="background-image: url(<?php echo $entrada['img']->url;?>)"></figure>
                                            </a>
                                            <?php echo $this->ShowEstrelas($entrada['votos-media']);?>
                                        </div>
                                    </div>

                                    <div class="col-lg-9 col-md-8 col-sm-7">
                                        <div class="row1">
                                            
                                            <?php if($entrada['categorianome']):?>
                                                <a class="link-amarelo" href="<?php echo $entrada['categoriaurl'];?>">
                                                    <h6 class="page-publisher uppercase border-none">
                                                        <b><i class="fa fa-ellipsis-v"></i> <?php echo $entrada['categorianome'];?></b>
                                                    </h6>
                                                </a>
                                            <?php endif;?> 
                                            
                                            <a class="link-<?php echo $entrada['color']?>" href="<?php echo $entrada['link'];?>">
                                                <h4 class="margin-top-none menu-<?php echo $entrada['color']?>"><b><?php echo $entrada['title'];?></b></h4>
                                            </a>
                                            
                                            <p><?php echo $this->readMore($entrada['description'], 150);?></p>

                                            <div class="text-center">
                                                <span class="box-badge"><span class="badge badge-bottom" title="Visualizações" alt="Visualizações"><i class="fa fa-search"></i> (<?php echo $entrada['acessos'];?>)</span></span>
                                                <span class='box-badge'><span class='badge badge-bottom' title='Baixados' alt='Baixados'><i class="fa fa-download"></i> (<?php echo $entrada['baixados'];?>)</span></span>
                                                <span class='box-badge'><span class='badge badge-bottom' title='Comentários' alt='Comentários'><i class="fa fa-comment"></i> (<?php echo $entrada['comentarios'];?>)</span></span>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </li>	
                        <?php endforeach;?>
                    </ul>
                </div>
            </div>
        <?php endif;?>
        
    </div>
    
</section>

<section class="container">
    <div id="blog_pw">
        <h3 class="headline"><b>Blog do Professor Web</b></h3>
        
        <div id="itens" class="itens-isotope">
            
            <?php foreach($this->rssPw as $entrada):?>

                <div class="page-header col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12 margin-bottom-10 text-center">
                            <a href="<?php echo $entrada['link']?>">
								<figure class="img-formato shadow-center" style="background-image: url(<?php echo $entrada['img'];?>)"></figure>
                                <img class="desativado img-rounded shadow lazy" data-original="<?php echo $entrada['img'];?>" alt="<?php echo $this->readMore($entrada['title'],30);?>" width="100" height="100">
                            </a>
                        </div>

                        <div class="col-lg-9 col-md-12 col-sm-12 col-xs-12">
                            
                            <h6 class="page-publisher"><small><i class="fa fa-calendar"></i> <?php echo $entrada["author"];?></small></h6>
                            <a href="<?php echo $entrada['link']?>">
                                <h4 class="menu-<?php echo $entrada['color']?>"><b><?php echo $entrada['title'];?></b></h4>
                            </a>

                            <p><?php echo $this->readMore($entrada['description'], 240);?></p>

                            <?php if(array_search('não categorizado',$entrada["category"]) === false):?>

                            <label><b><i class="fa fa-tags"></i> Tags</b></label>
                                <ul class="tags list-inline list-unstyled ">
                                    <?php foreach($entrada['category'] as $key=>$value):?>
                                        <li><a class="btn btn-xs btn-roxo menu-<?php echo $entrada['color']?>" href="https://oprofessorweb.wordpress.com/category/<?php echo $this->RetiraAcentuacao($value, true);?>"><?php echo $value;?></a></li>
                                    <?php endforeach;?>
                                </ul>

                            <?php endif;?>

                        </div>
                    </div>
                </div>
            
            <?php endforeach;?>
        </div>
    </div>
    
</section>
