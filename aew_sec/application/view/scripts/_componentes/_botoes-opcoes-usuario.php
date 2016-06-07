<?php
    if(!$this->usuarioLogado):
        return;
    endif;
    
    if($this->conteudos):
        $aprovado = $this->conteudos->getFlAprovado();
    endif;
?>

<div class="col-lg-12 text-center hidden-xs">
    <div class="btn-group btn-group-sm row margin-top-10 margin-bottom-10">

        <?php if($this->href['aprovar_conteudo'] && $this->href['reprovar_conteudo']):?>
            <a class="btn btn-success" title="Aprovar conteúdo" alt="Aprovar conteúdo" href="<?php echo $this->href['aprovar_conteudo'];?>"><i class="fa fa-thumbs-up"></i> aprovar</a>
            <a class="btn btn-danger" title="Reprovar conteúdo" alt="Reprovar conteúdo" href="<?php echo $this->href['reprovar_conteudo'];?>"><i class="fa fa-thumbs-down"></i> reprovar</a>
        <?php else:?>
            <?php if($this->href['adicionar_favorito']):?>
                <a class="btn btn-success" title="Adicionar ao favorito" alt="Adicionar ao favorito" href="<?php echo $this->href['adicionar_favorito'];?>"><i class="fa fa-heart-o"></i> adicionar favorito</a>
            <?php endif;?>

            <?php if($this->href['remover_favorito']):?>
                <a class="btn btn-danger" title="Remover do favorito" alt="Remover do favorito" href="<?php echo $this->href['remover_favorito'];?>"><i class="fa fa-heart"></i> remover favorito</a>
            <?php endif;?>

            <?php if($this->href['adicionar_destaque']):?>
                <a class="btn btn-warning" title="Adicionar aos destaques" alt="Adicionar aos destaques" href="<?php echo $this->href['adicionar_destaque'];?>"><i class="fa fa-bell-o"></i> marcar destaque</a>
            <?php endif;?>

            <?php if($this->href['remover_destaque']):?>
                <a class="btn btn-danger" title="Remover dos destaques" alt="Remover dos destaques" href="<?php echo $this->href['remover_destaque'];?>"><i class="fa fa-bell-slash-o"></i> remover destaque</a>
            <?php endif;?>
        <?php endif;?>
                
        <?php if($this->href['editar_conteudo']):?>
            <a class="btn btn-primary" title="Editar conteúdo" alt="Editar conteúdo" href="<?php echo $this->href['editar_conteudo'];?>"><i class="fa fa-pencil-square-o"></i> editar</a>
        <?php endif;?>

        <?php if($this->href['apagar_conteudo']):?>
            <a class="btn btn-danger" title="Apagar conteúdo" alt="Apagar conteúdo" href="<?php echo $this->href['apagar_conteudo'];?>"><i class="fa fa fa-trash"></i> apagar</a>
        <?php endif;?>
    </div>
</div>

<div class="col-lg-12 margin-bottom-10 hidden-lg hidden-md hidden-sm">
    
    <div class="row">
        
        <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            <span>Ações</span>  
            <span class="caret"></span>
        </button>
        
        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
            <?php if($this->href['adicionar_favorito']):?>
                <li><a title="Adicionar ao favorito" alt="Adicionar ao favorito" href="<?php echo $this->href['adicionar_favorito'];?>"><i class="fa fa-heart"></i> adicionar favorito</a></li>
            <?php endif;?>

            <?php if($this->href['remover_favorito']):?>
                <li><a title="Remover do favorito" alt="Remover do favorito" href="<?php echo $this->href['remover_favorito'];?>"><i class="fa fa-heart-o"></i> remover favorito</a></li>
            <?php endif;?>

            <?php if($this->href['adicionar_destaque']):?>
                <li><a title="Adicionar aos destaques" alt="Adicionar aos destaques" href="<?php echo $this->href['adicionar_destaque'];?>"><i class="fa fa-bell-o"></i> marcar como destaque</a></li>
            <?php endif;?>

            <?php if($this->href['remover_destaque']):?>
                <li><a title="Remover dos destaques" alt="Remover dos destaques" href="<?php echo $this->href['remover_destaque'];?>"><i class="fa fa-bell-slash-o"></i> remover como destaque</a></li>
            <?php endif;?>

            <?php if($this->href['editar_conteudo']):?>
                <li><a title="Editar conteúdo" alt="Editar conteúdo" href="<?php echo $this->href['editar_conteudo'];?>"><i class="fa fa-pencil-square-o"></i> editar</a></li>
            <?php endif;?>

            <?php if($this->href['apagar_conteudo']):?>
                <li><a title="Apagar conteúdo" alt="Apagar conteúdo" href="<?php echo $this->href['apagar_conteudo'];?>"><i class="fa fa-times-circle-o"></i> apagar</a></li>
            <?php endif;?>
        </ul>
    </div>
</div>
