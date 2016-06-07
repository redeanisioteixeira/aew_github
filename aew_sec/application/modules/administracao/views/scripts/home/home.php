<?php
	$url_adm_usuarios  = $this->url(array('module' => 'administracao', 'controller' => 'usuario','action' => 'listar'), null, true);
	$url_adm_amigos    = $this->url(array('module' => 'administracao', 'controller' => 'amigo-da-escola','action' => 'pendentes'), null, true);
	$url_adm_denuncias = $this->url(array('module' => 'administracao', 'controller' => 'denuncias','action' => 'listar'), null, true);
	$url_adm_licencas  = $this->url(array('module' => 'administracao', 'controller' => 'licenca','action' => 'listar'), null, true);
?>

<aside class="col-lg-3">
    <div class="list-group">
        <h3 class="acoes">Menu</h3>
        <?php // if($this->isAllowed($this->usuarioLogado,'administracao', 'usuarios')):?>
        <a class="list-group-item" href="<?php echo $url_adm_usuarios;?>" title="Usuários">Usuários</a>
        <?php // endif;?>
        <?php // if($this->isAllowed($this->usuarioLogado,'administracao', 'aprovar-amigo-da-escola')):?>
        <a class="list-group-item" href="<?php echo $url_adm_amigos;?>" title="Amigos da Escola">Amigos da Escola</a>
        <?php //endif;?>
        <?php// if($this->isAllowed($this->usuarioLogado,'administracao', 'denuncias')):?>
        <a class="list-group-item" href="<?php echo $url_adm_denuncias;?>" title="Denuncias">Denuncias</a>
        <?php// endif;?>
        <a class="list-group-item" href="<?php echo $url_adm_licencas;?>" title="Licenças">Licencias</a>
        
    </div>
</aside>
<section class="col-lg-9">
    
</section>

