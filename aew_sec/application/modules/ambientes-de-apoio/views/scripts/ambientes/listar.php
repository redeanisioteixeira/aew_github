<div id="conteudo-ambientes-apoio<?php echo $this->categoria->getId();?>" class="conteudo-ambientes-apoio">

    <h4 class="margin-none text-center cor-canal-fundo padding-all-05"><b>Ambientes de apoio referidos a <?php echo $this->categoria->getNome();?></b></h4>    

    <?php if($this->usuarioLogado):?>
        <div class="padding-all-10">
            <div class=" btn-group btn-group-sm pull-right">
                <?php if($this->href['editar_categoria']):?>
                    <a name='editar-categoria' class="btn btn-primary" title="Editar categoria" alt="Editar conteúdo" href="<?php echo $this->href['editar_categoria'];?>"><i class="fa fa-pencil-square-o"></i> editar</a>
                <?php endif;?>

                <?php if($this->href['apagar_categoria'] && !count($this->ambientesDeApoio)):?>
                    <a name='apagar-categoria' class="btn btn-danger" title="Apagar categoria" alt="Apagar categoria" rel="<?php echo $this->href['apagar_categoria'];?>" idcategoria="<?php echo $this->categoria->getId();?>"><i class="fa fa-times-circle-o"></i> apagar</a>
                <?php endif;?>
            </div>
        </div>
    <?php endif;?>

    <div class="margin-top-05 margin-bottom-05">
        <?php echo $this->paginationControl($this->ambientesDeApoio,"Sliding","_componentes/_pagination_ajax.php", array("divContent" => "#conteudo-ambientes-apoio".$this->categoria->getId()));?>
    </div>

    <div id="itens" class="itens itens-isotope">

        <?php foreach($this->ambientesDeApoio as $ambienteDeApoio):?>

            <article class="col-lg-6 col-md-6 col-sm-12 col-xs-12 page-header amarelo">

                <div class="row">
                    
                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 text-center margin-top-10">
                        <a href="<?php echo $ambienteDeApoio->getUrlExibir($this->comunidade);?>">
                            <figure class="img-formato img-rounded" style="background-image: url(<?php echo $ambienteDeApoio->getImagemAssociadaUrl().DS.$ambienteDeApoio->getId();?>.png)"></figure>
                        </a>

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

                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 margin-top-10">
                        <h6 class="page-publisher"><small><i class="fa fa-calendar"></i> Publicado em <?php echo $this->SetupDate($ambienteDeApoio->getDataCriacao());?> por <b><?php echo $ambienteDeApoio->getUsuarioPublicador()->getNome();?></b></small></h6>

                        <a href="<?php echo $ambienteDeApoio->getUrlExibir($this->comunidade);?>">
                            <h4 class="menu-amarelo" title="<?php echo $ambienteDeApoio->getTitulo();?>"><b><?php echo $ambienteDeApoio->getTitulo();?></b></h4>
                        </a>

                        <a class="btn btn-primary btn-sm" href="<?php echo $ambienteDeApoio->getUrlProjeto()?>" target="_blank"><i class='fa fa-external-link'></i> Ir ao site</a>

                        <?php if($ambienteDeApoio->getSiteStatus()):?>                                
                            <a class="btn btn-default btn-sm livepreview" href="<?php echo $ambienteDeApoio->getUrlProjeto();?>" target="_blank" data-trigger="click" data-tag="#preview<?php echo $ambienteDeApoio->getId();?>" target="_blank"><i class='fa fa-eye'></i> pre-visualizar</a>
                            <div id="preview<?php echo $ambienteDeApoio->getId();?>"></div>
                        <?php else:?>
                            <i class="link-vermelho fa fa-chain-broken"> <b>fora do ar</b></i>
                        <?php endif;?>

                        <p class="margin-top-10">
                            <?php echo $this->readMore($ambienteDeApoio->getDescricao(), 400);?>
                        </p>

                        <div class="pull-right">
                            <a class="btn btn-warning btn-sm" href="<?php echo $ambienteDeApoio->getUrl()?>" target="_blank"><i class='fa fa-download'></i> Baixar</a>
                        </div> 

                    </div>
                </div>
            </article>
        <?php endforeach;?>

    </div>

    <div class="margin-top-05 margin-bottom-05">
        <?php echo $this->paginationControl($this->ambientesDeApoio,"Sliding","_componentes/_pagination_ajax.php", array("divContent" => "#conteudo-ambientes-apoio".$this->categoria->getId()));?>
    </div>
    
</div>

<script>
    $(document).ready(function(){ 
        $('.livepreview').livePreview({viewWidth: 250, viewHeight: 160, position: 'top', trigger: 'click'});
        $('img.lazy').lazyload({effect : 'fadeIn'});
    });
</script>
