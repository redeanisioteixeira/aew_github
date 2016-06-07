<section class="row">
    <div class="col-lg-8 padding-none">

        <div class="<?php echo ($this->tipoDispositivo == 'desktop' ? 'padding-left-15' : '');?>">
            
            <div class="panel panel-default">

                <header class="panel-heading margin-none">
                    <small>
                        <i class="fa fa-calendar"></i> Publicado em <?php echo $this->SetupDate($this->conteudo->getDataPublicacao());?> por <b><?php echo $this->conteudo->getUsuarioPublicador()->getNome();?></b>
                    </small>
                </header>

                <div class="btn-group btn-group-sm pull-right margin-all-05">
                    <a class="btn btn-default btn-xs" href="/conteudos-digitais/conteudo/exibir/id/<?php echo $this->conteudo->getId();?>"><i class="fa fa-eye"></i> Visualizar</a>
                </div>    
                
                <div class="panel-body">
                    <?php echo $this->editar;?>
                </div>
                
            </div>
            
        </div>
        
    </div>
    
    <div class="col-lg-4">
        <?php echo $this->render('_componentes/_filtro-busca-avancada.php');?>
    </div>    
    
</section>