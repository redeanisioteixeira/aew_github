<div class="panel panel-default">
    <div class="panel-body">
        <div class="col-lg-3">
            <img class="menu-cinza img-rounded img-responsive shadow-center" src="<?php echo $this->categoria->getConteudoImagem();?>">
        </div>

        <div class="col-lg-9">        
            <p><?php echo $this->categoria->getDescricao();?></p>

            <?php if($this->categoriaRelacionada):?>
                <p><label class="label-separator">Categoria Relacionada</label> <a href="/administracao/categoria-conteudo/exibir/id/<?php echo $this->categoriaRelacionada->getId();?>"><?php echo $this->categoriaRelacionada->getNome();?></a></p>
            <?php endif;?>

            <p>
                <label class="label-separator">Canal</label>
                <?php echo $this->categoria->getCanal()->getNomecanal();?>
            </p>
                
            <p>
                <label class="label-separator">Ativo</label>
                <input type="checkbox" name="ativo" <?php echo ($this->categoria->getFlativo() ? 'checked' : '');?> disabled>
            </p>
            
            <p>
                <label class="label-separator">Destacado</label>
                <input type="checkbox" name="ativo" <?php echo ($this->categoria->getDestaque() ? 'checked' : '');?> disabled>
            </p>

            <?php if($this->categoria->getImagemVideoUrl()):?>
                <label>Vídeo de destaque :</label> 
                <video class="shadow rounded col-lg-12" poster="<?php echo $this->categoria->getConteudoImagem();?>" preload controls>
                    <source src="<?php echo $this->categoria->getImagemVideoUrl();?>" type="video/webm">
                </video>
            <?php endif;?>
            
        </div>
        
    </div>
    
    <?php if(isset($this->href['editar_categoria']) || isset($this->href['apagar_categoria'])):?>
        <div class="panel-footer">
            <?php if(isset($this->href['editar_categoria'])):?>
                <a class="btn btn-primary" href="<?php echo $this->href['editar_categoria'].'/id/'.$this->categoria->getId();?>" title="editar" data-toggle="tooltip" data-placement="top"><i class="fa fa-edit"></i> editar</a>
            <?php endif;?>

            <?php if(isset($this->href['apagar_categoria'])):?>
                <a class="btn btn-danger" href="<?php echo $this->href['apagar_categoria'].'/id/'.$this->categoria->getId();?>" title="apagar" data-toggle="tooltip" data-placement="top"><i class="fa fa-trash"></i> apagar</a>
            <?php endif;?>
        </div>
    <?php endif;?>
    
</div>