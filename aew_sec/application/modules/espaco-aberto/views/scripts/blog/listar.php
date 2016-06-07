<?php ?>

<aside class="box">
    
<?php
 $allowedDono = $this->isAllowed($this->usuarioLogado,'espaco-aberto', 'administrar') || $this->perfilDono;
 $allowedModerador = $this->isAllowed($this->usuarioLogado,'espaco-aberto', 'administrar') || $this->perfilModerador;
 if($allowedDono || $allowedModerador ):
?>
    
    <div class="pull-right">
        <a class="btn btn-circle-sm btn-success" 
           href="/espaco-aberto/blog/adicionar/<?php echo $this->perfilTipo.'/'.$this->perfilId ?>" 
           title="Adicione um Artigo a seu Blog"
           data-toggle="tooltip" 
           data-placement="top" 
           data-original-title="Adicionar Artigo ao seu blog"
           >
            <i class="fa fa-plus"></i>
        </a>
    </div>    
  
<?php endif; ?>
</aside>
<aside class="box">
    <input type-action='html-action' type="search" class="form-control search-input" idloadcontainer="lista-blogs" rel="/espaco-aberto/blog/lista-blogs/usuario/<?php echo $this->usuarioPerfil->getId()?>" placeholder="Filtrar Blogs">
</aside>

<?php if(count($this->blogs)== 0): ?>
<section class="box text-center">
        <span class="text-danger">
            <i class="fa fa-exclamation"></i> 
            Nenhum artígo encontrado 
        </span>
</section>
    <?php else:?>
<!-- lista de artígos do Blog -->
<article class="load-scroll" id="lista-blogs" rel='/espaco-aberto/blog/lista-blogs/usuario/<?php echo $this->usuarioPerfil->getId()?>'>
    <?php echo $this->render('blog/lista-blogs.php'); ?>
    <?php endif;?>
</article>
        
