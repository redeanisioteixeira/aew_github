<div class="panel panel-default">
    <div class="panel-body">

        <p><?php echo $this->escape($this->licenca->getDescricao()); ?></p>
        
        <?php if($this->licencaRelacionada):?>
            <p><label class="label-separator">Licença Relacionada</label> <a href="/administracao/licenca/exibir/id/<?php echo $this->licencaRelacionada->getId();?>"><?php echo $this->licencaRelacionada->getNome();?></a></p>
        <?php endif;?>
        <p>
            <a href="<?php echo $this->licenca->getSiteconteudolicenca();?>" target="_top"><img class="menu-cinza img-rounded img-responsive shadow-center" src="<?php echo $this->licenca->getImagemAssociada();?>"></a>
        </p>
        
    </div>
    <?php if(isset($this->href['editar_licenca']) || isset($this->href['apagar_licenca'])):?>
        <div class="panel-footer">
            <?php if(isset($this->href['editar_licenca'])):?>
                <a class="btn btn-primary" href="<?php echo $this->href['editar_licenca'].'/id/'.$this->licenca->getId();?>" title="editar" data-toggle="tooltip" data-placement="top"><i class="fa fa-edit"></i> editar</a>
            <?php endif;?>

            <?php if(isset($this->href['apagar_licenca'])):?>
                <a class="btn btn-danger" href="<?php echo $this->href['apagar_licenca'].'/id/'.$this->licenca->getId();?>" title="apagar" data-toggle="tooltip" data-placement="top"><i class="fa fa-trash"></i> apagar</a>
            <?php endif;?>
        </div>
    <?php endif;?>
</div>