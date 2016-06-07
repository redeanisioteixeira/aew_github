<div class="btn-group-vertical btn-block" role="group">
    
    <a class="btn btn-sm btn-danger uppercase" data-toggle="modal" data-target="#modalGeral" class="btn btn-primary" alt="Denunciar" href="<?php echo $this->conteudo->getUrlDenunciar();?>"><i class="fa fa-ban"></i> Denunciar</a>

    <a class="btn btn-sm btn-default" href="/conteudos-digitais/conteudos/listar/publicador/<?php echo $this->conteudo->getUsuarioPublicador()->getId();?>">
        <img src="/assets/img/icones/icone-outras-publicacoes.png">
    </a>    
    
    <?php if($this->conteudo->getGuiaPedagogicoUrl()):?>
        <a class="btn btn-sm btn-default uppercase" href="<?php echo $this->conteudo->getGuiaPedagogicoUrl();?>" target="_blank">
            <i class="fa fa-book"></i> Guia pedagógico
        </a>
    <?php endif;?>

    <?php if($this->conteudo->getSite()):?>
        <a class="btn btn-sm btn-default uppercase" href="<?php echo $this->conteudo->getSite() ?>" target="_blank">
            <i class="fa fa-link"></i> Ir para o site
        </a>
    <?php endif;?>
        
    <?php if($this->href['incorporar_url']):?>
        <!--  Codigo incorporar -->
        <div class="btn btn-sm btn-default">
            <a class="uppercase dropdown-toggle link-branco" data-toggle="dropdown" alt="Incorporar">
                <i class="fa fa-external-link"></i> Incorporar
            </a>
            <textarea name="incorporar" class="dropdown-menu padding-all-10" cols="40" rows="8" onfocus="this.select()" onmouseover="this.focus()" readonly><iframe width='100%' src="<?php echo $this->href['incorporar_url'];?>" frameborder='0' scrolling='no' style="min-height: 520px" allowfullscreen></iframe></textarea>
        </div>
    <?php endif;?>
</div>
