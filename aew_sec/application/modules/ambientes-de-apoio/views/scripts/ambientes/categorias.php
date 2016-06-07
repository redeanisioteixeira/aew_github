<div id="tab-ambiente-apoio" role="tabpanel" class="col-lg-12">
    <div class="row">
        <div class="panel panel-warning">
            <div class="panel-body">
        
                <!-- Nav tabs -->
                <ul class="nav nav-pills" role="tablist">
                    <li role="presentation"><a href="#destaques" aria-controls="destaques" role="tab" data-toggle="tab"><h4 class="menu-amarelo margin-none"><b><i class="fa fa-lightbulb-o"></i> destaques</b></h4></a></li>
                    <li role="presentation"><a href="#mais-recentes" aria-controls="mais-recentes" role="tab" data-toggle="tab"><h4 class="menu-amarelo margin-none"><b><i class="fa fa-plus-circle"></i> mais recentes</b></h4></a></li>
                    <li role="presentation"><a href="#mais-vistos" aria-controls="mais-vistos" role="tab" data-toggle="tab"><h4 class="menu-amarelo margin-none"><b><i class="fa fa-eye"></i> mais vistos</b></h4></a></li>
                </ul>

                <!-- content tabs -->
                <div class="tab-content margin-top-10">
                    <div id="destaques" role="tabpanel" class="tab-pane fade">
                        <div id="itens">
                            <?php foreach($this->rssDestaques as $entrada):?>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 text-center">
                                    <div class="row">
                                        <div class="col-style col-style-rss">
                                            <a class="link-cinza-escuro text-left" href="/ambientes-de-apoio/ambientes/categorias/id/<?php echo $entrada["type_id"];?>">
                                                <h6 class="page-publisher uppercase margin-none margin-bottom-10"><b><i class="fa fa-ellipsis-v"></i> <?php echo $entrada["type"];?></b></h6>
                                            </a>
                                            
                                            <a href="<?php echo $entrada["link"];?>">
                                                <img class="img-circle shadow cor-canal-fundo padding-all-10" src="<?php echo $entrada["img"]->url;?>"  width="140" height="140" />
                                            </a>
                                            <a class="btn btn-readmore menu-<?php echo $entrada["color"]?>" href="<?php echo $entrada["link"]?>" role="button">Leia <i class="fa fa-plus-circle fa-1x"></i></a>
                                            <h4 class="menu-<?php echo $entrada["color"]?>"><b><?php echo $entrada["title"];?></b></h4>
                                            <p><?php echo $this->readMore($entrada["description"], 240);?></p>
                                        </div>
                                    </div>                                
                                </div>
                            <?php endforeach;?>
                        </div>
                    </div>

                    <div id="mais-recentes" role="tabpanel" class="tab-pane fade">
                        <div id="itens">
                            <?php foreach($this->rssRecentes as $entrada):?>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 text-center">
                                    <div class="row">
                                        <div class="col-style col-style-rss">
                                            <a class="link-cinza-escuro text-left" href="/ambientes-de-apoio/ambientes/categorias/id/<?php echo $entrada["type_id"];?>">
                                                <h6 class="page-publisher uppercase margin-none margin-bottom-10"><b><i class="fa fa-ellipsis-v"></i> <?php echo $entrada["type"];?></b></h6>
                                            </a>
                                            
                                            <a href="<?php echo $entrada["link"];?>">
                                                <img class="img-circle shadow cor-canal-fundo padding-all-10" src="<?php echo $entrada["img"]->url;?>"  width="140" height="140" />
                                            </a>
                                            <a class="btn btn-readmore menu-<?php echo $entrada["color"]?>" href="<?php echo $entrada["link"]?>" role="button">Leia <i class="fa fa-plus-circle fa-1x"></i></a>
                                            <h4 class="menu-<?php echo $entrada["color"]?>"><b><?php echo $entrada["title"];?></b></h4>
                                            <p><?php echo $this->readMore($entrada["description"], 240);?></p>
                                        </div>
                                    </div>                                
                                </div>
                            <?php endforeach;?>
                        </div>
                    </div> 

                    <div role="tabpanel" class="tab-pane fade" id="mais-vistos">
                        <div id="itens">
                            <?php foreach($this->rssVistos as $entrada):?>
                                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 text-center">
                                    <div class="row">
                                        <div class="col-style col-style-rss">
                                            <a class="link-cinza-escuro text-left" href="/ambientes-de-apoio/ambientes/categorias/id/<?php echo $entrada["type_id"];?>">
                                                <h6 class="page-publisher uppercase margin-none margin-bottom-10"><b><i class="fa fa-ellipsis-v"></i> <?php echo $entrada["type"];?></b></h6>
                                            </a>
                                            
                                            <a href="<?php echo $entrada["link"];?>">
                                                <img class="img-circle shadow cor-canal-fundo padding-all-10" src="<?php echo $entrada["img"]->url;?>"  width="140" height="140" />
                                            </a>
                                            <a class="btn btn-readmore menu-<?php echo $entrada["color"]?>" href="<?php echo $entrada["link"]?>" role="button">Leia <i class="fa fa-plus-circle fa-1x"></i></a>
                                            <h4 class="menu-<?php echo $entrada["color"]?>"><b><?php echo $entrada["title"];?></b></h4>
                                            <p><?php echo $this->readMore($entrada["description"], 240);?></p>
                                        </div>
                                    </div>                                
                                </div>
                            <?php endforeach;?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php if(count($this->categorias)==0):?>
    <div class="margin-top-50">
        <div class="alert alert-info" role="alert">Nenhuma categoria de Ambiente de Apoio encontrada</div>
    </div> 
    <?php return;?>
<?php endif;?>

<?php $i = 1; $j = 1; $offset = "col-lg-offset-1";?>
<div class="col-lg-12">
    <a id="topo-conteudo" class="scroll_nag hidden" href="#inicio">topo</a>

    <div class="row">
        
        <div class="panel panel-warning">
            <div class="panel-body">
            <?php foreach($this->categorias as $categoria):?>

                <figure class="ambiente-apoio-opcao ambiente-apoio<?php echo $categoria->getId();?> <?php echo $offset;?>  col-lg-2 col-md-12 col-sm-12 col-sm-12 col-xs-12 arrow-top bottom">
                    <div class="col-style">
                        
                        <?php $ativo = "";?>
                        <?php if($this->categoriaSelecionada):?>
                            <?php $ativo = ($this->categoriaSelecionada == $categoria->getId() ? "active" : "");?>
                        <?php else:?>
                            <?php $ativo = ($i == 1 ? "active" : "");?>
                        <?php endif;?>
                        
                        <a class="ambiente-apoio <?php echo $ativo;?>" categoria="<?php echo $categoria->getId();?>" posicao="<?php echo $i;?>" area="<?php echo $j;?>">
                            <h5 class="menu-amarelo text-center margin-none margin-bottom-05" style="min-height: 30px;"><b><?php echo $categoria->getNome();?></b></h5>
                            <img class="ambiente-apoio trans90 cor-canal-fundo img-rounded img-responsive" src="<?php echo $categoria->getImagemAssociada();?>" width="130"/>
                        </a>
                    </div>
                </figure>

                <?php $offset = "";?>
                <?php if($i % 5 == 0):?>
                    <div class="lista-ambientes-apoio lista-ambientes-apoio<?php echo $j;?> col-lg-12 col-md-12 col-sd-12 col-xs-12 margin-bottom-10"></div>
                    <?php $j++; $offset = "col-lg-offset-1";?>
                <?php endif;?>

                <?php $i++;?>

            <?php endforeach;?>

            <?php if(($i-1) % 5 != 0):?>
                <div class="lista-ambientes-apoio lista-ambientes-apoio<?php echo $j;?> col-lg-12 col-md-12 col-sd-12 col-xs-12 margin-bottom-10"></div>
            <?php endif;?>
            </div> 
        </div>
    </div>
</div>
