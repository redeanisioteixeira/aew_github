<tr>
    <?php if(!$this->dependencia):?>
        <td colspan="2">
            <a class="text-primary" href="/administracao/licenca/exibir/id/<?php echo $this->licenca->getId();?>"><b><?php echo $this->licenca->getNome(); ?></b></a>
        </td>
    <?php else:?>
        <td></td>
        <td>
            <a class="text-muted" href="/administracao/licenca/exibir/id/<?php echo $this->licenca->getId();?>"><?php echo $this->licenca->getNome(); ?></a>
        </td>
        
    <?php endif;?> 
        
    <td class="hidden-ms hidden-xs"><?php echo $this->readMore($this->licenca->getDescricao(),300);?></td>

    <td class="text-center">
        <a href="<?php echo $this->licenca->getSiteconteudolicenca();?>" target="_top"><img class="menu-cinza img-rounded img-responsive shadow-center" src="<?php echo $this->licenca->getImagemAssociada();?>"></a>
    </td>

    <td class="text-center">
        <?php if(isset($this->href['editar_licenca']) || isset($this->href['apagar_licenca'])):?>
            <?php if(isset($this->href['editar_licenca'])):?>
                <a class="btn btn-primary btn-xs" href="<?php echo $this->href['editar_licenca']."/id/".$this->licenca->getId() ?>" title="editar" data-toggle="tooltip" data-placement="top"><i class="fa fa-edit"></i></a>
            <?php endif;?>

            <?php if(isset($this->href['apagar_licenca'])):?>
                <a class="btn btn-danger btn-xs" href="<?php echo $this->href['apagar_licenca']."/id/".$this->licenca->getId() ?>" title="apagar" data-toggle="tooltip" data-placement="top"><i class="fa fa-trash"></i></a>
            <?php endif;?>
        <?php endif;?>
    </td>
</tr>