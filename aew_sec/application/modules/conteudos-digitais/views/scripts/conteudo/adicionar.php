<section class="row">
    <div class="col-lg-8 padding-none">

        <div class="<?php echo ($this->tipoDispositivo == 'desktop' ? 'padding-left-15' : '');?>">
            
            <div class="panel panel-default">
                
                <header class="panel-heading margin-none">
                    <small>
                        <i class="fa fa-calendar"></i> Publicando em <?php echo $this->SetupDate();?> como <b><?php echo $this->usuario->getNome();?></b>
                    </small>
                </header>
                
                <div class="panel-body">
                    <?php echo $this->adicionar;?>
                </div>    
                
            </div>
            
        </div>
        
    </div>
    
    <div class="col-lg-4">
        <?php echo $this->render('_componentes/_filtro-busca-avancada.php');?>
    </div>    
    
</section>

