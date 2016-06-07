<?php 
	$this->headScript()->appendFile($this->baseUrl().'/js/jquery/jquery.tablesorter.min.js');
?>
<table id="lista-arquivos" class="table table-hover tablesorter" border="0" cellspacing="1" cellpadding="0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Título</th>
            <th>Publicador</th>
            <th>Arquivo visualização</th>
            <th>Tamanho (MB)</th>
            <th>Arquivo download</th>
            <th>Tamanho (MB)</th>
        </tr>
    </thead>
    <tbody>
        <?php $conteudosD= $this->download; $conteudosV= $this->visualiza; ?>
        <?php foreach ($conteudosD as $conteudo):?>
        <tr>
            <td><?php echo $conteudo['id'];?> </td>
            <?php echo $conteudo['titulo']?>
            <td><a target="_blank" href="/conteudos-digitais/conteudo/exibir/id/<?php echo $conteudo['id'];?>"><?php echo $conteudo['titulo'] ?></a></td>
            <td><?php //echo $this->showUsuario($conteudo['usuarioPublicador']);?></td>
            <td>
                <?php if($conteudos['visualizacao'] != ""):?>
                <a class="cls-arquivo cls-arquivo-download" href="/conteudos/visualizacao/<?php echo $conteudos['visualizacao'];?>" target='_blank'><?php echo $conteudos['visualizacao'];?></a>
		<?php endif;?>
            </td>
	    <td class="cela-numero"><?php echo $conteudos['tamanhoVisualizacao'];?></td>
            <td>
                <a class="cls-arquivo cls-arquivo-download" href="<?php echo $conteudo['path'].'/'.$conteudo['arquivo'];?>" target='_blank'><?php echo $conteudo['arquivo'];?></a>
            </td>
            <td class="cela-numero"><?php echo $conteudo['tamanho'];?></td>
        </tr>
	<?php endforeach;?>
        
    </tbody>
</table>

<script>
	$(document).ready(function(){
		$("#lista-arquivos").tablesorter();

		$("#lista-arquivos").bind('sortEnd',function(){
			$('tr:odd').css('background-color','#F3F3F3');	
			$('tr:even').css('background-color','#FFFFFF');
		});

	});
</script>
