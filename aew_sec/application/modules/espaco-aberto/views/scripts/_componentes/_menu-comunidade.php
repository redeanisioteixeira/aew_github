<?php
    $controle = $this->getController();
    $acao = $this->getAction();
?>
<!-- Menu -->
<nav class="list-group" role="menu" aria-label="opções da comunidade">
    
    <?php if ($this->usuarioPerfil->getUrlConfigurar($this->usuarioLogado)):?>
        <a class="list-group-item <?php echo ($controle == 'comunidade' && $acao == 'editar' ? 'menu-laranja-claro' : '')?>" href="<?php echo $this->usuarioPerfil->getUrlConfigurar($this->usuarioLogado); ?>">
            <i class="fa fa-gears link-laranja"></i> <b class="uppercase">Configurações</b>
        </a>
    <?php endif;?>
        
    <a class="list-group-item <?php echo ($controle == 'membro' ? 'menu-laranja-claro' : '')?>" href="<?php echo $this->usuarioPerfil->getUrlListaMembros();?>">
        <i class="fa fa-users link-laranja"></i> <b class="uppercase">Membros</b>
    </a>
        
    <a class="list-group-item <?php echo ($controle == 'album' ? 'menu-laranja-claro' : '')?>" href="<?php echo $this->usuarioPerfil->getUrlListaAlbuns();?>">
        <i class="fa fa-file-image-o link-laranja"></i> <b class="uppercase">Álbuns</b>
    </a>
    
    <a class="list-group-item <?php echo ($controle == 'blog' ? 'menu-laranja-claro' : '')?>" href="<?php echo $this->usuarioPerfil->getUrlListaBlogs();?>">
        <i class="fa fa-rss-square link-laranja"></i> <b class="uppercase">Blog</b>
    </a>
    
    <a class="list-group-item <?php echo ($controle == 'forum' ? 'menu-laranja-claro' : '')?>" href="<?php echo $this->usuarioPerfil->getUrlListaForum();?>"> 
        <i class="fa fa-commenting link-laranja"></i> <b class="uppercase"> Fórums</b>
    </a>
    
    <?php if ($this->usuarioPerfil->getUrlModerar($this->usuarioLogado)): ?>
        <a class="list-group-item <?php echo ($controle == 'moderador' ? 'menu-laranja-claro' : '')?>" href="<?php echo $this->usuarioPerfil->getUrlModerar($this->usuarioLogado) ?>">
            <i class="fa fa-gavel link-laranja"></i> <b class="uppercase"> Moderação</b>            
        </a>
    <?php endif; ?>
        
    <a class="list-group-item" data-toggle="modal" data-target="#modalGeral" href="/home/denunciar">
        <i class="fa fa-ban link-vermelho"></i> <b class="link-vermelho uppercase">Denuncie</b>
    </a>
    
</nav>