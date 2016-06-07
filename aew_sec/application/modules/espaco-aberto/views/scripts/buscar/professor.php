<?php ?>
<div class="listagem">
<ul class='listaConteudosDigitais listaFotoUsuario'>
    <?php foreach($this->objetos as $usuario){ ?>
	<a ostyle='font-size:12px' href= '<?php echo $usuario->getLinkPerfil()?>'>
	    <li class='' >
		<div class='foto'>".
		     <?php echo $usuario->getFotoPerfil()->getFotoCache(Aew_Model_Bo_Foto::$FOTO_CACHE_30X30)?><br/>
                     <span><?php echo $this->escape($usuario->getNome()) ?> </span>
                </div>
            </li>
        </a>
    <?php }?>
</ul>
<div style='font-size:12px' class='PaginacaoListar'> <?php echo $this->objetos ?> </div>
<?php if(count($this->objetos)==0) {?>
<div style='font-size:14px' class="conteudo-margin">
	Nenhum usuário encontrado.
</div>
<?php }?>
</div>