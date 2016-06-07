<?php 
if(!count($this->relacionados)):?>
    <?php return;?>
<?php endif;?>
    
<div class="row">
    <h4 class="uppercase cor-canal-fonte pull-left"><i class="fa fa-list-ul"></i> <b>Relacionados</b></h4>
    <?php echo $this->paginationControl($this->relacionados,"Sliding","_componentes/_pagination_ajax.php", array("divContent" => "#relacionados", "divUrl" => $this->url(array("module"=> "ambientes-de-apoio", "controller" => "ambiente", "action" => "relacionados", null))));?>
</div>

<div class="row">
    <?php foreach($this->relacionados as $ambienteDeApoio):?>
        <article class="panel panel-warning">

            <div class="panel-heading cor-canal-fundo">
                <a href="<?php echo $ambienteDeApoio->getUrlExibir($this->comunidade);?>">
                    <h5 class="margin-none text-center" title="<?php echo $ambienteDeApoio->getTitulo();?>" <?php echo $this->corfonte;?>><b><?php echo $ambienteDeApoio->getTitulo();?></b></h5>
                </a>
            </div>
            
            <div class="panel-body">
                <h6 class="page-publisher"><small><i class="fa fa-calendar"></i> Publicado em <?php echo $this->SetupDate($ambienteDeApoio->getDataCriacao());?> por <b><?php echo $ambienteDeApoio->getUsuarioPublicador()->getNome();?></b></small></h6>
 
                <a href="<?php echo $ambienteDeApoio->getAmbientedeApoioCategoria()->getUrlExibir();?>">
                    <h6 class="page-publisher uppercase" <?php echo $this->corfonte;?>><b><i class="fa fa-ellipsis-v"></i> <?php echo $ambienteDeApoio->getAmbientedeApoioCategoria()->getNome();?></b></h6>
                </a>
                
                <div class="col-lg-3 text-center">
                    <div class="row">
                        <figure class="margin-bottom-10">
                            <a href="<?php echo $ambienteDeApoio->getUrlExibir($this->comunidade);?>">
                                <img class="img-responsive img-rounded padding-all-05 lazy" data-original="<?php echo $ambienteDeApoio->getImagemAssociadaUrl().DS.$this->escape($ambienteDeApoio->getId());?>.png">
                            </a>
                        </figure>
                        <span class="box-badge"><span class="badge badge-bottom" title="Visualizações" alt="Visualizações"><i class="fa fa-search"></i> (<?php echo $ambienteDeApoio->getAcessos();?>)</span></span>
                        <span class="box-badge"><span class="badge badge-bottom" title="Comentários" alt="Comentários"><i class="fa fa-comment"></i> (<?php echo count($ambienteDeApoio->getComentarios());?>)</span></span>
                    </div>
                </div>
                
                <div class="col-lg-9">
                    <div class="text-left"><?php echo $this->readMore($ambienteDeApoio->getDescricao(), 240);?></div>
                    <div class="text-center">
                        <?php echo $this->showEstrelas($ambienteDeApoio, "", false);?>
                    </div>
                </div>

            </div>
                
        </article>

    <?php endforeach;?>
    
    <?php echo $this->paginationControl($this->relacionados,"Sliding","_componentes/_pagination_ajax.php", array("divContent" => "#relacionados", "divUrl" => $this->url(array("module"=> "ambientes-de-apoio", "controller" => "ambiente", "action" => "relacionados", null))));?>
</div>

<script>
    $(document).ready(function(){ 
        $('img.lazy').lazyload({effect : 'fadeIn'});
    });
</script>
