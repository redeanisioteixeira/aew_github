<?php ?>
<div class="exibir">
    <div class="dados">
	<div class="data">
	    <?php echo Sec_Date::getPresentationDate($this->enquete['dataInicio'], Sec_Date::DATETIME); ?>
		    -
	    <?php echo Sec_Date::getPresentationDate($this->enquete['dataFim'], Sec_Date::DATETIME); ?>
	</div>
	<div style='font-size:14px' class="titulo"><?php echo $this->escape($this->enquete['pergunta']); ?></div>

		<?php if(isset($this->form)): ?>
			<div style='font-size:12px' class="perguntas">
				<?php echo $this->form; ?>
			</div>
		<?php else: ?>
			<ul class="perguntas">

			<?php $qtdRespostas = count( $this->enquete['enqueteOpcaoResposta'] ); ?>

			<?php foreach($this->enquete['enqueteOpcao'] as $opcao): ?>
				<li>
				<?php
				    $contResposta = 0;
				    foreach ($this->enquete['enqueteOpcaoResposta'] as $resposta){
				        if($resposta['idEnqueteOpcao'] == $opcao['idEnqueteOpcao']){
				            $contResposta++;
				        }
				    }
					$porcento = ( $qtdRespostas > 0 ) ? ( $contResposta * 100 ) / $qtdRespostas : "0";
					echo "<div style='font-size:14px' class='opcaoEnquete'>";
					echo "<b>" . $opcao['opcao'] . "</b> (" . $contResposta . " votos) <br />";
				    echo "<span class='porcentoEnquete' style='width:". $porcento ."px;'></span>" . $porcento . "% ";
					echo "</div>";
				?>
				</li>
			<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
</div>