<!-- <link href="<?php echo $this->baseUrl(); ?>/css/agenda/reset.css" type="text/css" rel="stylesheet" /> -->
<link href="<?php echo $this->baseUrl(); ?>/css/agenda/dp_calendar.css" type="text/css" rel="stylesheet" />
<link href="<?php echo $this->baseUrl(); ?>/css/agenda/demo.css" type="text/css" rel="stylesheet" />
<link href="<?php echo $this->baseUrl(); ?>/css/agenda/jquery.ui.all.css" type="text/css" rel="stylesheet" />

<script type="text/javascript" src="<?php echo $this->baseUrl(); ?>/js/agenda/jquery.ui.core.js"></script>
<script type="text/javascript" src="<?php echo $this->baseUrl(); ?>/js/agenda/jquery.ui.position.js"></script>
<script type="text/javascript" src="<?php echo $this->baseUrl(); ?>/js/agenda/jquery.ui.datepicker.js"></script>
<script type="text/javascript" src="<?php echo $this->baseUrl(); ?>/js/agenda/date.js"></script>
<script type="text/javascript" src="<?php echo $this->baseUrl(); ?>/js/agenda/jquery.dp_calendar.min.js"></script>
<!-- ADICIONADO POR ANGELO LEFUNDES PARA OCULTAR OU EXIBIR DIV DAS MARCAÇÕES -->
<script type="text/javascript">

function detalhe()
{
	elemento = document.getElementById("oculta-marcacoes");
        document.getElementById('oculta-marcacoes-todos').style.display = "";
	texto = document.getElementById("textoDescricao");

	if (elemento.style.display == "none"){
		elemento.style.display = "block";
		texto.innerText = "Marcações pendentes na agenda -";
		texto.textContent = "Marcações pendentes na agenda -";
	}
	else{
		elemento.style.display = "none";
                document.getElementById('oculta-marcacoes-todos').style.display = "none";
		texto.innerText = "Marcações pendentes na agenda +";
		texto.textContent = "Marcações pendentes na agenda +";
	}
}

</script>
<?php
    if($this->tipoPagina == Sec_Constante::USUARIO) {
        $nomeId = 'idUsuarioAgenda';
        $usuario = Usuario::getLoggedUser();
    }
    else{
        $nomeId = 'idComunidadeAgenda';
    }

?>
<script type="text/javascript">
spanMarcacao = document.getElementById('spanMarcacao');
spanMarcacao.innerText = null;
	</script>
<?php ?>

<?php
    $lista = "";
if($this->solicitacoes != null){
if($this->solicitacoes->count() > 0):
$bo = new Aew_Model_Bo_MarcacaoAgenda(1);
$bo->setaVistos($usuario['idUsuario']);
?>
<a href="javascript:void detalhe()"><h2 id="textoDescricao" style='font-size:16px'>Marcações pendentes na agenda -</h2></a>

<div style="margin-top:10px; margin-left:0px; margin-bottom: 10px; background-color: #DDDDDD" align="left">

<div id="oculta-marcacoes">
<ul id="facebook">
            	<?php

        	foreach($this->solicitacoes as $solicitacao):
                ?>
	<li id="list1">
<?php
					if($solicitacao['tipo']=='1') {

                                            $nomeMarcacao = 'idUsuario';
                                            $boNome = new Aew_Model_Bo_Usuario();
                                            $bo = new Aew_Model_Bo_UsuarioAgenda();
                                            $txt = "de usuário";
                                            $tipoMarcacao = "usuario";
			        	}
			        	else{

                                            $nomeMarcacao = 'idComunidade';
                                            $boNome = new Aew_Model_Bo_Comunidade();
                                            $bo = new Aew_Model_Bo_ComunidadeAgenda();
                                            $txt = "de comunidade";
                                            $tipoMarcacao = "comunidade";
			        	}
					$objetos = $bo->obtemNomeMarcacao($solicitacao['idAgenda']);
					$abreLink = "<a href='". $this->url(array('module' => 'espaco-aberto', 'controller' => 'agenda',
						           'action' => 'exibir', $tipoMarcacao => $objetos[0][$nomeMarcacao], 'id' => $solicitacao['idAgenda']), null, true) ."'>";
					$fechaLink = "</a>";
                                        if($solicitacao['tipo']=='1')
                                            $linkFoto = Usuario::fotoCache($objetos[0][$nomeMarcacao], $this->baseUrl(), 64);
			        	else
                                            $linkFoto = Comunidade::fotoCache($objetos[0][$nomeMarcacao], $this->baseUrl(), 64);
					$abreLinkUsuario = "<a href='". $this->url(array('module' => 'espaco-aberto', 'controller' => 'perfil',
						           'action' => 'exibir', $tipoMarcacao => $objetos[0][$nomeMarcacao]), null, true) ."'>";


            		echo $abreLink . substr_replace($objetos[0]['evento'],'', 25). $fechaLink;
                        $nome = $boNome->obtemNome($objetos[0][$nomeMarcacao]);
            ?>
		<?php echo $abreLinkUsuario.$linkFoto.$fechaLink; ?> <!--<span class="del"><a style='font-size:12px' href="" title="Exibir Todos" id="1">Exibir Todos</a></span>-->
		<a href="#" class="user-title"><?php echo $nome[0]['nome']; ?> marcou você nesse evento <?php echo $txt; ?>. Deseja aceitar?</a>
		<span class="addas"><a style='font-size:12px' href="<?php echo $this->url(array('module' => 'espaco-aberto', 'controller' => 'agenda',
    							           'action' => 'aceitar', $tipoMarcacao =>  $objetos[0][$nomeMarcacao],
            			                   'id' => $solicitacao['idAgenda']), null, true); ?>"
    							           title="Aceitar marcação" id="1">Aceitar</a>
                &nbsp;|&nbsp;
                <a style='font-size:12px' href="<?php echo $this->url(array('module' => 'espaco-aberto', 'controller' => 'agenda',
    							           'action' => 'recusar', $tipoMarcacao =>  $objetos[0][$nomeMarcacao],
    									   'id' => $solicitacao['idAgenda']), null, true); ?>"
    							           title="Recusar marcação" id="1">Recusar</a>
                </span>
	</li>
            <?php endforeach; ?>

        </ul>

    </div>

   </div>
<div id="oculta-marcacoes-todos"><span><a style='font-size:12px;' href="<?php echo $this->url(array('module' => 'espaco-aberto', 'controller' => 'agenda',
    							           'action' => 'marcacao', 'usuario' =>  $usuario['idUsuario']), null, true); ?>" title="Exibir Todos" id="1">Exibir todas as marcações</a></span></div>
<?php
	endif;
}?>
<script type="text/javascript">
jQuery(document).ready(function(){

	var events_array = new Array(
	<?php
	$flag = false;//identificando o primeiro elemento para retirar a virgula
				foreach($this->objetos as $agenda):

				//formata para exibir a data e mês na lsitagem dos eventos
				$d_for = explode('-', $agenda['dataInicio']);
				$ano = $d_for[0];
				$mes = $d_for[1] - 1;
				$dia = substr("$d_for[2]", 0, -9);
				$d_hora = explode(' ', $d_for[2]);
				$d_hora = explode(':', $d_hora[1]);
				$hora = $d_hora[0];
				$minuto = $d_hora[1];

				$d_for = explode('-', $agenda['dataFim']);
				$anoFim = $d_for[0];
				$mesFim = $d_for[1] - 1;
				$diaFim = substr("$d_for[2]", 0, -9);
				$mensagens = $this->boMsg->getByIdAgendaComentario($agenda[$nomeId]);
		    	$mensagenstotal = $mensagens->count();

				$agendaEvento = $agenda['evento'];
				$descricaoAgendaEvento =  substr_replace($agenda['mensagem'],'...', 200);

				$abreLink = "<a href='". $this->url(array('module' => 'espaco-aberto', 'controller' => 'agenda',
						           'action' => 'exibir', $this->perfilTipo => $this->perfilId, 'id' => $agenda[$nomeId]), null, true) ."'>";
				$fechaLink = "</a>";



				if ($flag)
					echo ",";
				else
					$flag = true;
				echo "{
					startDate: new Date($ano, $mes, $dia, $hora, $minuto),
					endDate: new Date($anoFim, $mesFim, $diaFim),
					title: \"$agendaEvento\",
					description: \"<p>$descricaoAgendaEvento</p><p>$abreLink Leia mais... $fechaLink</p>".$mensagenstotal." Comentário(s)\",
					priority: 1, // 1 = Low, 2 = Medium, 3 = Urgent
					frecuency: 0 // 1 = Daily, 2 = Weekly, 3 = Monthly, 4 = Yearly
				}";

				endforeach;
?>

	);

	jQuery("#calendar").dp_calendar({
		events_array: events_array
	});
});
</script>


<div class="listagem">
<div class="agendaCompleta">
        <div class="filtro">
            <div class="filtros">
                <p>Ordenar por</p>
                <ul>
                    <li class="data">
                        <?php echo "<a href=\"".$this->baseUrl().$this->url."data\">DATAS</a>";?>
                    </li>
                    <!--
                    <li class="evento">
                        <a class="" href="javascript:;">EVENTO</a>
                    </li>
                    -->
                    <li class="artista">
                        <?php echo "<a href=\"".$this->baseUrl().$this->url."anteriores\">JÁ ACONTECERAM</a>";?>
                    </li>
                    <li class="estilo">
                        <?php echo "<a href=\"".$this->baseUrl().$this->url."total\">ESTÃO AGENDADOS</a>";?>
                    </li>
                </ul>
                <div class="clear"></div>
            </div>
            <!--<div class="limparFiltros">
                <a href="javascript:;" class="close">
                    X
                </a>
            </div>-->
            <div class="clear"></div>
        </div>
        <div id="festasAjax">
            <div class="lista"></div>
            <div class="eventos"></div>
        </div>
    </div>
<?php

if ($this->listagem=='data'){
	echo "<div id='calendar'></div>";
}
else{

?>

<?php
if($this->tipoPagina == Sec_Constante::USUARIO) {
        echo "<span style='font-size:11px'><b>*</b>Eventos com a letra amarela correspondem aos marcados de outra agenda.</span>";
    }
?>
	<div class="acoes">

		<div class="clear"></div>
	</div>

<?php

    // Carrega permissões de dono e moderador do perfil
    $allowedDono = $this->isAllowed('espaco-aberto', 'administrar') || $this->perfilDono;
    $allowedModerador = $this->isAllowed('espaco-aberto', 'administrar') || $this->perfilModerador;

    if($allowedModerador):
?>
	<div class="acoes">
	<?php if($allowedModerador): ?>
		<a href="<?php echo $this->url(array('module' => 'espaco-aberto', 'controller' => 'agenda',
							           'action' => 'adicionar', $this->perfilTipo => $this->perfilId), null, true); ?>" title="Adicionar Evento">Adicionar Evento</a>
	<?php endif; ?>
	</div>
<?php endif; ?>

<?php


    $lista = "";

    if($this->objetos->getCurrentItemCount()):

        $lista .= "<div class='agenda-do-dia'>";
        $countLine = 0;
        $corLinha = "cinza";

		foreach($this->objetos as $agenda):

		$abreLinkSemClass = "<a href='". $this->url(array('module' => 'espaco-aberto', 'controller' => 'agenda',
						           'action' => 'exibir', $this->perfilTipo => $this->perfilId, 'id' => $agenda[$nomeId]), null, true) ."#mensagens'>";
				$fechaLinkSemClass = "</a>";

		    if ( $countLine++ == 0 ) $corLinha = ( $corLinha == "" ) ? "cinza" : "";
		    if ( $countLine == 1 ) $countLine = 0;

		    $class = $corLinha;

		    $abreLink = "<a href='". $this->url(array('module' => 'espaco-aberto', 'controller' => 'agenda',
						           'action' => 'exibir', $this->perfilTipo => $this->perfilId, 'id' => $agenda[$nomeId]), null, true) ."'>";

		    $adminPost = "";
		    if($allowedModerador){
		        $adminPost = "<div class='acao-item'><a href='". $this->url(array('module' => 'espaco-aberto', 'controller' => 'agenda',
						           'action' => 'editar', $this->perfilTipo => $this->perfilId, 'id' => $agenda[$nomeId]), null, true) ."'>editar</a>";
		        $adminPost .= " | <a href='". $this->url(array('module' => 'espaco-aberto', 'controller' => 'agenda',
						           'action' => 'apagar', $this->perfilTipo => $this->perfilId, 'id' => $agenda[$nomeId]), null, true) ."'>apagar</a></div>";
		    }

		    $fechaLink = "</a>";

		    $abreLinkComentario = "<a href='". $this->url(array('module' => 'espaco-aberto', 'controller' => 'agenda',
						           'action' => 'exibir', $this->perfilTipo => $this->perfilId, 'id' => $agenda[$nomeId]), null, true) ."#comentarios'>";
		    $fechaLinkComentario = "</a>";

		//formata para exibir a data e mês na lsitagem dos eventos
		$d_for = explode('-', $agenda['dataInicio']);
		$ano = $d_for[0];
		$mes = $d_for[1];
		$dia = substr("$d_for[2]", 0, -9);

		$dataEvento = $ano."-".$mes."-".$dia;
		$dataAtual = date('Y-m-d');

		switch($mes){
							case 1:
				$mes = "Jan";
				break;
							case 2:
				$mes = "Fev";
				break;
							case 3:
				$mes = "Mar";
				break;
							case 4:
				$mes = "Abr";
				break;
							case 5:
				$mes = "Mai";
				break;
							case 6:
				$mes = "Jun";
				break;
							case 7:
				$mes = "Jul";
				break;
							case 8:
				$mes = "Ago";
				break;
							case 9:
				$mes = "Set";
				break;
							case 10:
				$mes = "Out";
				break;
							case 11:
				$mes = "Nov";
				break;
							case 12:
				$mes = "Dez";
				break;

		}

			$mensagens = $this->boMsg->getByIdAgendaComentario($agenda[$nomeId]);
		    $mensagenstotal = $mensagens->count();

			$lista .= "<div class='unidade-agenda'>";

			if($dataAtual > $dataEvento){
		    $lista .= "<div class='hora-agenda-cinza'>";
			}else{
			$lista .= "<div class='hora-agenda-verde'>";
			}
			$cor='';
			if($this->tipoPagina == Sec_Constante::USUARIO) {
				if ($agenda['marcacao'] == true)
					$cor = 'style="color:#ddcc00"';
			}
		    $lista .="
					    	<div class='mes' $cor><li>".$mes."</li></div>
					    	<div class='dia'><li $cor>".$dia."<li></div>
					    </div>

					    <div class='descricao-agenda'>
					            <h1 style='color: #31911E'>". $abreLink . substr_replace($agenda['evento'],'...', 25). $fechaLink . "</h1>
					            <div class='resumo'>". $abreLink . substr_replace($agenda['mensagem'],'...', 200).$fechaLink . "</div>

					    </div>

					    <div class='interacaoSemClass'>
					    	<li>". $abreLinkSemClass . $mensagenstotal." Comentário(s) " . $fechaLinkSemClass . "</li>
					    </div>

					    <div class='interacao'>
					        <li>" .$abreLinkComentario . "Comentar". $fechaLinkComentario ."</li>
					    </div>

					    <div class='adminPostAgenda'>" . $adminPost . "</div>
					    </div>

					    ";
		endforeach;
		$lista .= "</div>";
		echo $lista;
		echo "<div class='PaginacaoListar'>" . $this->objetos . "</div>";

    else:
?>
<div class="conteudo-margin">
	Nenhum evento encontrado.
</div>
<?php
    endif;
}
?>
</div>
