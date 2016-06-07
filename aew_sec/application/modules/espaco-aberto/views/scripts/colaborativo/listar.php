<?php
	echo $this->render('_componentes/_layout.php');
	$impar = true;	
?>

<div class="listagem" style="width:720px;">
	<table class="conteudo-colaborativo" border="0">
		<tr>
			<th colspan="2">Total de participantes: <?php echo count($this->arquivos);?></th>
		<tr>
		<?php foreach ($this->arquivos as $key => $value):?>

			<?php
				$link_arquivo = "/conteudos/colaborativo/".$value['arquivo'];
				$link_ficha   = "/conteudos/colaborativo/".$value['ficha'];
			?>

			<tr>
				<?php if($impar):?>
					<td class="video" rowspan="7"><?php echo $this->ShowPlayer($link_arquivo, "video", "EA", "355", "260");?></td>
					<td class="dados"><label>Nº Matrícula : </label><em><?php echo $value['matricula'];?></em></td>
				<?php else:?>
					<td class="dados"><label>Nº Matrícula : </label><em><?php echo $value['matricula'];?></em></td>
					<td class="video" rowspan="7"><?php echo $this->ShowPlayer($link_arquivo, "video", "EA", "355", "260");?></td>
				<?php endif;?>
				<?php $impar = ($impar==true ? false:true);?>
			</tr>
			<tr>
				<td class="dados"><label>Nome : </label><em class="nome"><a href="/espaco-aberto/perfil/feed/usuario/<?php echo $key;?>"><?php echo $value['nome'];?></a></em></td>
			</tr>
			<tr>
				<td class="dados"><label>E-mail : </label><em><?php echo $value['email'];?></em></td>
			</tr>
			<tr>
				<td class="dados"><label>Ficha Inscrição : </label><em><a class="cls-arquivo cls-arquivo-doc" href="<?php echo $link_ficha;?>"><?php echo $value['ficha'];?></a></em></td>
			</tr>
			<tr>
				<td class="dados"><label>Nome arquivo : </label><em><a class="cls-arquivo cls-arquivo-mpg" href="<?php echo $link_arquivo;?>" target="_blank"><?php echo $value['arquivo'];?></a></em></td>
			</tr>
			<tr>
				<td class="dados"><label>Tamanho : </label><em><?php echo $value['tamanho'];?></em></td>
			</tr>
			<tr>
				<td class="dados"><label>Data envio : </label><em><?php echo $value['dataEnvio'];?></em></td>
			</tr>

		<?php endforeach;?>
	</table>
</div>
