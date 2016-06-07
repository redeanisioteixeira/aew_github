<?php echo $this->render('_componentes/_layout.php');

							if($this->tipoPagina == Sec_Constante::USUARIO) {
					    	$id = $this->objeto['idUsuario'];
						    $tipoPerfil = "usuario";

						    }
						    elseif($this->tipoPagina == Sec_Constante::COMUNIDADE){
							$id = $this->objeto['idComunidade'];
						    $tipoPerfil = "comunidade";
						    }

						    $corpo = $this->url(array('module' => 'espaco-aberto', 'controller' => 'perfil',
			           'action' => 'exibir', 'usuario' => '' ), null, true);
						    $boUsuario = new Aew_Model_Bo_Usuario();
?>
<script type="text/javascript">
	function retornaNome(val){
		i=val.length-1;

		for (;i>=0;i--){
			if(val.charAt(i-2) == '<' && val.charAt(i-1) == 'p' && val.charAt(i) == '>')
				return val.substring(0, i-2);
		}
		return val;
	}

	function obtemId(val) {
		i=val.length-1;
		for (;i>=0;i--){
			if(val.charAt(i-4) == '<' && val.charAt(i-3) == '/' && val.charAt(i-2) == 'd' && val.charAt(i-1) == 'i' && val.charAt(i) == 'v'){
				for (j=i-4;j>=0;j--){
					//alert(val.charAt(j));
					if(val.charAt(j) == '>'){
						return val.substring(j+1, i-4);
					}
				}
				return '';
			}
		}
		return val;
	}

    jQuery(document).ready(function() {

    	cities = ''; //fazendo com que o ajax trabalhe de maneira assincrona para carregar os dados inicialmente
        $.ajax({
                type: "GET",
                url: '<?php echo $this->baseUrl();?>/aew/usuario/json',
                async: false,
                dataType: "json",
                success: function(data) {
                    cities = data; }
                });

        $("#ce1").autocomplete(cities, {
                matchContains: true,
                scroll: false,
                hotkeymode:true,
                noresultsmsg: 'Nenhum resultado encontrado: <a href="#">{q}</a>'
        });

        $('[contenteditable]').autocomplete(cities, {
                matchContains: true,
                scroll: false,
                hotkeymode:true,
                formatResult: function(row) {

        	corpo = "<?php echo $this->url(array('module' => 'espaco-aberto', 'controller' => 'perfil',
			           'action' => 'exibir', 'usuario' => '' ), null, true);?>";
            auxRow = row[0];
            idUsuario = obtemId(auxRow);
            var UA = navigator.userAgent;
			textoSpan = '';
			fechaSpan = '';
			//firefox exige essa ação
            if (UA.indexOf('Firefox') > -1) {
            	textoSpan = '<span contenteditable="true">';
    			fechaSpan = '</span>';
            }
        return '<a style="background-color:#aaaaaa; padding:0" contenteditable="false" class="custom" href="'+corpo+idUsuario+'" tabindex="-1" >'+ textoSpan + retornaNome(row[0]) + fechaSpan +'</a>&nbsp;';},
        });

    });
</script>

					    <div class='interacaoVoltar'>
					    <li><a href="<?php echo $this->baseUrl();?>/espaco-aberto/agenda/listar/<?php echo $tipoPerfil; ?>/<?php echo $id; ?>">Voltar</a>

					    </div>

  <div class="promocao">
            <div class="promocao-interna">
    <div class="titulo-preto-relacionada"><?php echo $this->escape($this->objeto['evento']); ?></div>
	<div>
        <strong>Local:</strong> <?php echo nl2br($this->objeto['local']); ?>
        <br />
        <strong>Data/Hora início:</strong> <?php echo Sec_Date::getPresentationDate($this->objeto['dataInicio'], Sec_Date::DATETIME); ?>
        <br />
        <strong>Data/Hora fim:</strong> <?php echo Sec_Date::getPresentationDate($this->objeto['dataFim'], Sec_Date::DATETIME); ?>
        <br />
        <strong>Colegas convidados:</strong> <?php
			foreach($this->marcacaoConvidado as $marcacao):
			$usuario = $boUsuario->obtemNomeUsuario($marcacao['idUsuario'], null);
        		echo '<a style="background-color:#aaaaaa; padding:0" contenteditable="false" class="custom" href="'.$corpo.$marcacao['idUsuario'].'" tabindex="-1" >'.$usuario[0]['nome'].'</a>&nbsp;';

        		endforeach;?>
        <br />

        <strong>Colegas confirmados:</strong> <?php foreach($this->marcacaoConfirmado as $marcacao):
        		$usuario = $boUsuario->obtemNomeUsuario($marcacao['idUsuario'], null);
        		echo '<a style="background-color:#aaaaaa; padding:0" contenteditable="false" class="custom" href="'.$corpo.$marcacao['idUsuario'].'" tabindex="-1" >'.$usuario[0]['nome'].'</a>&nbsp;';
        		endforeach;?>
    </div>

    <div class="linha-separa-texto"></div>

    <div class="texto">
    	<div>&nbsp;</div>

    		<div><?php echo nl2br($this->objeto['mensagem']); ?></div>
    		<div><br></div>

    		<div style="word-wrap:break-word; text-align:left">Evento: <?php echo $this->escape($this->objeto['evento']); ?></div>
    		<div><br></div>

    		<div>Local: <?php echo nl2br($this->objeto['local']); ?></div>
    		<div><br></div>

<?php
    // Carrega permissões de dono e moderador do perfil
    $allowedDono = $this->isAllowed('espaco-aberto', 'administrar') || $this->perfilDono;
    $allowedModerador = $this->isAllowed('espaco-aberto', 'administrar') || $this->perfilModerador;

    if($allowedModerador){
echo $this->formMarcacao;
    }
?>

    	</div>
	</div>
</div>

					    <?php

					    if($this->tipoPagina == Sec_Constante::USUARIO) {
						        $nomeId = 'idUsuarioAgenda';
						    } else {
						        $nomeId = 'idComunidadeAgenda';
						    }
					    	$mensagens = $this->boMsg->getByIdAgendaComentario($this->objeto[$nomeId]);
						    $mensagenstotal = $mensagens->count();
						 ?>

	<div class='agenda-do-dia'>
	        <div class="comentario">
    			<h2 class="blue-claro">Deixe seu comentário</h2>
    		</div>
			<div id="comentarios" class='unidade-agenda-exibir-form' align='center'>
			<?php echo $this->form; ?>
			</div>
	</div>


<div class="noticias-relacionadas" id="mensagens">
    <h2 class="blue-claro"><?php echo $mensagenstotal; ?>&nbsp;Coment&aacute;rio<?php if($mensagenstotal > 1){ print 's';}?></h2>


    			        <?php

					    		foreach($this->comentarios as $topico):
					    			$usuario = $boUsuario->obtemNomeUsuario($topico['idUsuario'], null);


		    			 	//imprimindo a foto do usuário que deixa comentario
		    			 	$linkFoto = Usuario::foto($topico['idUsuario'], $this->baseUrl(), 81, 81);
		         			$abreLink = "<a href='". $this->url(array('module' => 'espaco-aberto', 'controller' => 'perfil',
								        'action' => 'exibir', 'usuario' => $topico['idUsuario']), null, true) ."'>";
							$fechaLink = "</a>";

					    ?>


    <div class="relacionada">
        <div class="imagem-noticia"><?php echo $abreLink . $linkFoto . $fechaLink; ?></div>
        <div class="data-comentario"><?php echo $this->datetime($topico['dataCriacao']); ?></div>
        <div class="dono-do-comentario"><b>Postado por:&nbsp;</b><?php echo $usuario[0]['nome']; ?></div>
        <div class="descricao-noticia"><?php echo $topico['mensagem']; ?></div>
        <div class="clearFloat"></div>

    </div>

	<?php endforeach; ?>

</div>
