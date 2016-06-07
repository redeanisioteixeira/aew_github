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
		texto.innerText = "Descrição -";
	}
	else{
		elemento.style.display = "none";
                document.getElementById('oculta-marcacoes-todos').style.display = "none";
		texto.innerText = "Descrição +";
	}
}

</script>
<?php
    if($this->tipoPagina == Sec_Constante::USUARIO) {
        $nomeId = 'idUsuarioAgenda';
        $usuario = Usuario::getLoggedUser();
		$bo = new Aew_Model_Bo_MarcacaoAgenda(1);
		$bo->setaVistos($usuario['idUsuario']);
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
if($this->solicitacoes->count() > 0): ?>

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
    <div class='PaginacaoListar'><?php echo $this->solicitacoes; ?></div>
   </div>        <?php
                    endif;
                }
                else{
?>
<div class="conteudo-margin">
	Nenhum evento encontrado.
</div>
<?php
}
?>

