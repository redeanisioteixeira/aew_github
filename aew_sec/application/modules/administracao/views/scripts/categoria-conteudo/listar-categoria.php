<tr>
    <?php if(!$this->dependencia):?>
        <td colspan="2">
            <a class="text-primary" href="/administracao/categoria-conteudo/exibir/id/<?php echo $this->categoria->getId();?>"><b><?php echo $this->categoria->getNome(); ?></b></a>
        </td>
    <?php else:?>
        <td></td>
        <td>
            <a class="text-muted" href="/administracao/categoria-conteudo/exibir/id/<?php echo $this->categoria->getId();?>"><?php echo $this->categoria->getNome();?></a>
        </td>
    <?php endif;?> 
        
    <td class="hidden-ms hidden-xs"><?php echo $this->readMore($this->categoria->getDescricao(),300);?></td>

    <td class="text-center">
        <img class="menu-cinza img-rounded img-responsive shadow-center" src="<?php echo $this->categoria->getConteudoImagem();?>">
        
        <?php if(isset($this->href['editar_categoria']) || isset($this->href['apagar_categoria'])):?>
            <div class="col-lg-12 margin-top-10">
                <?php if(isset($this->href['editar_categoria'])):?>
                    <a class="btn btn-primary btn-xs" href="<?php echo $this->href['editar_categoria']."/id/".$this->categoria->getId() ?>" title="editar" data-toggle="tooltip" data-placement="top"><i class="fa fa-edit fa-1x"></i></a>
                <?php endif;?>

                <?php if(isset($this->href['apagar_categoria'])):?>
                    <a class="btn btn-danger btn-xs" href="<?php echo $this->href['apagar_categoria']."/id/".$this->categoria->getId() ?>" title="apagar" data-toggle="tooltip" data-placement="top"><i class="fa fa-trash fa-1x"></i></a>
                <?php endif;?>
            </div>
        <?php endif;?>        
    </td>

    <td class="text-center">
        <input type="checkbox" name="ativo" <?php echo ($this->categoria->getFlativo() ? 'checked' : '');?> disabled>
    </td>
    
    <td class="text-center">
        <input type="checkbox" name="destacado" <?php echo ($this->categoria->getDestaque() ? 'checked' : '');?> disabled>
    </td>
</tr>

