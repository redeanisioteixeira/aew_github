<div class="panel panel-default">
    <div class="panel-body">
        <p class="page-header"><Label><i class="fa fa-tags"></i> Tag :</label> <?php echo $this->tag->getNome();?></p>

        <?php if($this->tagRef->qtdeusoCD):?>
            <p class="page-header"><Label><i class="link-azul fa fa-cubes"></i> <a href="/conteudos-digitais/conteudos/tags/id/<?php echo $this->tag->getId();?>">Conteúdos Digitais</a> :</label> Nº de vezes referenciados <b><?php echo $this->tagRef->qtdeusoCD;?></b></p>
        <?php endif;?>

        <?php if($this->tagRef->qtdeusoAA):?>            
            <p class="page-header"><Label><i class="link-amarelo fa fa-wrench"></i> <a href="/conteudos-digitais/conteudos/tags/id/<?php echo $this->tag->getId();?>">Apoio a Produção e Colaboração</a> :</label> Nº de vezes referenciados <b><?php echo $this->tagRef->qtdeusoAA;?></b></p>
        <?php endif;?>

    </div>

    <?php if(isset($this->href['editar_tag']) || isset($this->href['apagar_tag'])):?>
        <div class="panel-footer">
            <?php if(isset($this->href['editar_tag'])):?>
                <a class="btn btn-primary" href="<?php echo $this->href['editar_tag'].'/id/'.$this->tag->getId();?>" title="editar" data-toggle="tooltip" data-placement="top"><i class="fa fa-edit"></i> editar</a>
            <?php endif;?>

            <?php if(isset($this->href['apagar_tag']) && !$this->tagRef->qtdeusoCD && !$this->tagRef->qtdeusoAA):?>
                <a class="btn btn-danger" href="<?php echo $this->href['apagar_tag'].'/id/'.$this->tag->getId();?>" title="apagar" data-toggle="tooltip" data-placement="top"><i class="fa fa-trash"></i> apagar</a>
            <?php endif;?>
        </div>
    <?php endif;?>
</div>

