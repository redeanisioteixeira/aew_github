<?php
	$usuario = Usuario::getLoggedUser();
	$acl = Sec_Acl::getInstance();
	$url = $this->baseUrl();
	echo $this->render('_componentes/_layout.php');

?>

<script type="text/javascript">
	$(document).ready(function() {
		$('#challenge').remove();
	});
</script>

<div class="listagem" style="width:720px;">
	<div class="acao-item" style="text-align: center">
		<a target="_blank" href="<?php echo $url;?>/conteudos/colaborativo/inscrição_colaborativo.doc">Baixar Ficha de Inscrição e Regulamento</a>
		<?php if($acl->isAllowed($usuario['usuarioTipo']['nome'], 'espaco-aberto', 'listar-participantes')):?>
			| <a href="/espaco-aberto/colaborativo/listar">Ver participantes</a>
		<?php endif;?>
	</div><br/>

	<!-- 
	<div class="ficha-regulamento">
		<a target="_blank" href="<?php echo $url;?>/conteudos/colaborativo/inscrição_colaborativo.doc">Baixar Ficha de Inscrição e Regulamento</a>
	</div>
	-->

	<?php echo $this->form_cadastro;?>

	<div class="dicas-regulamento">
		<p>
			<ul>
				<li class="descricao">
					<img src="/img/home/img_infomailespacoaberto01.png" style="float: left; margin-right: 5px; width: 64px;"/>A <strong><a target="_blank" href="http://educadores.educacao.ba.gov.br/tv_anisio_teixeira">TV Anísio Teixeira</a></strong> está selecionando Vídeos com o objetivo de compor um dos quadros do novo programa <strong>"O Intervalo"</strong> que é um quadro colaborativo, em que o participante também ajudará a escolher um nome.</br></br>
						<span>
						<strong>Informações de Contato:</strong><br>
						Telefone : (71) 3116-9061<br>
						E-mail :  <a href="mailto:tv.anisioteixeira@educacao.ba.gov.br">tv.anisioteixeira@educacao.ba.gov.br</a>
						</span>				
            </li>
			</ul>
		</p>

		<h4>Como Participar</h4>
		<p>
			<ul>
				<li>O participante deve baixar a <strong><a href="<?php echo $url;?>/conteudos/colaborativo/inscrição_colaborativo.doc">Ficha de Inscrição e Regulamento</a></strong>, preencher e enviar, juntamente com o vídeo</li>
				<li>Serão aceitos os vídeos com até 2 minutos de duração, nos formatos <strong>.AVI</strong> ou <strong>.MP4</strong>, padrão NTSC, com resolução mínima de <strong>720x480 pixels</strong></li>
			</ul>
		</p>
		<h4>É Proibido</h4>
		<p>
			<ul>
				<li>Conteúdo não original, falso ou sem direitos autorais e que não esteja de acordo com o <strong><a target="_blank" href="/home/termo-condicoesuso">termo de uso e compromisso</a></strong>.</li>
				<li>Conteúdo ofensivo, obsceno, de sexo explícito ou pornografia</li>
				<li>Imagens violentas ou que incitem comportamento violento.</li>
				<li>Conteúdo racista ou preconceituoso</li>
			</ul>
		</p>
	</div>
</div>

