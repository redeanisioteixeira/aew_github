<?php
	$url_adicionar_amigo = $this->url(array('module' => 'espaco-aberto', 'controller' => 'home', 'action' => 'adicionar-amigo-da-escola'), null, true);
	echo $this->showMessages();
?>
<div class="conteudo-margin">
	<h2 style="width: 450px;">Trocar imagem do perfil</h2>
	<div class="Adicionar">
		Você pode enviar arquivos JPG, GIF ou PNG (tamanho máximo de 1MB). Não envie fotos que contenham imagens de personagens de desenho animado, pessoas famosas, nudez, trabalho artístico ou material protegido por direitos autorais. O tamanho mínimo de imagem é de 32x32
		<?php echo $this->adicionarImagem;?>
	</div>
	<hr/>
	<div class="Adicionar">
	<h2 style="width: 450px;"> Trocar senha </h2>
		 <?php echo $this->adicionarSenha; ?>
	</div>
	<hr/>
	<?php if($this->isAllowed($this->usuarioLogado,'administracao', 'adicionar-amigo-da-escola')):?>
		<span class="linkForaMenu" style='font-size:12px'>
			<a href="<?php echo $url_adicionar_amigo;?>"> Convidar para a rede</a>
		</span>
	<?php endif;?>
</div>
