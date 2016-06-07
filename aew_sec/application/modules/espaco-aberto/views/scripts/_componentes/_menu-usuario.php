<?php $acao = $this->getController();?>

<!-- Menu -->
<nav class="list-group" role="menu" aria-label="opções do usuario">
    <a class="list-group-item <?php echo ($acao == 'recado' ? 'menu-verde-claro' : '')?>" href="<?php echo $this->usuarioPerfil->getUrlListaRecados();?>" rel="Recados">
        <i class="fa fa-envelope link-verde"></i> <b class="uppercase">Recados</b>
    </a>
    
    <?php if(count($this->colegas) || $this->usuarioPerfil->isDonoPerfil($this->usuarioLogado)):?>
        <a class="list-group-item <?php echo ($acao == 'colega' ? 'menu-verde-claro' : '')?>" href="<?php echo $this->usuarioPerfil->getUrlListaColegas() ?>" rel="Meus colegas">
            <i class="fa fa-users link-verde"></i> <b class="uppercase">Colegas</b>
        </a>
    <?php endif;?>

    <?php if(count($this->comunidades) || $this->usuarioPerfil->isDonoPerfil($this->usuarioLogado)):?>
        <a class="list-group-item <?php echo ($acao == 'comunidade' ? 'menu-verde-claro' : '')?>" href="<?php echo $this->usuarioPerfil->getUrlListaComunidades();?>" rel="Minhas comunidades">
            <i class="fa fa-comments-o link-verde"></i> <b class="uppercase">Comunidades</b>
        </a>
    <?php endif;?>
    
    <?php if(count($this->blogs) || $this->usuarioPerfil->isDonoPerfil($this->usuarioLogado)):?>
        <a class="list-group-item <?php echo ($acao == 'blog' ? 'menu-verde-claro' : '')?>" href="<?php echo $this->usuarioPerfil->getUrlListaBlogs() ?>" rel="Meus blogs">
            <i class="fa fa-rss-square link-verde"></i> <b class="uppercase">Blog</b>
        </a>
    <?php endif;?>

    <?php if(count($this->albuns) || $this->usuarioPerfil->isDonoPerfil($this->usuarioLogado)):?>
        <a class="list-group-item <?php echo ($acao == 'album' ? 'menu-verde-claro' : '')?>" href="<?php echo $this->usuarioPerfil->getUrlListaAlbuns() ?>" rel="Meus Albuns">
            <i class="fa fa-camera link-verde"></i> <b class="uppercase">Albuns</b>
        </a>
    <?php endif;?>
    
    <a class="list-group-item" data-toggle="modal" data-target="#modalGeral" href="/home/denunciar">
        <i class="fa fa-ban link-vermelho"></i> <b class="link-vermelho uppercase">Denuncie</b>
    </a>
</nav>