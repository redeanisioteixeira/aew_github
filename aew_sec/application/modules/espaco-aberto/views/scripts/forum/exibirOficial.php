<?php 	echo $this->render('_componentes/_layout.php');
 		echo $this->render('_componentes/_funcoes-forum-comunidade.php');
/*
$hist isso da corrente.
*/
$hist[0]="<img src='$this->baseUrl()/img/espaco_aberto/trans(2).gif' width='12' height='21'>";
$hist[1]="<img src='$this->baseUrl()/img/espaco_aberto/trans(2).gif' width='12' height='21'>";
$hist[2]="<img src='$this->baseUrl()/img/espaco_aberto/trans(2).gif' width='12' height='21'>";
$hist[3]="<img src='$this->baseUrl()/img/espaco_aberto/trans(2).gif' width='12' height='21'>";
$hist[4]="<img src='$this->baseUrl()/img/espaco_aberto/trans(2).gif' width='12' height='21'>";
$hist[5]="<img src='$this->baseUrl()/img/espaco_aberto/trans(2).gif' width='12' height='21'>";

$cont=0;

?>

<script type="text/javascript">

function detalheConteudo()
{
	elemento = document.getElementById("dados-conteudo");
	texto = document.getElementById("textoDescricao");

	if (elemento.style.display == "none"){
		elemento.style.display = "block";
		texto.innerText = "Descrição -";
	}
	else{
		elemento.style.display = "none";
		texto.innerText = "Descrição +";
	}
}


function detalhe(id, pai)
{
	elemento = document.getElementById(id);
	if (elemento.style.display == "none")
		elemento.style.display = "block";
	else{
		if(pai=='pai'){
			elementofilho = document.getElementById("Resp"+id);
			elementofilho.style.display = "none";
		}
		elemento.style.display = "none";
	}
}

function submitform(id)
{


	elemento = document.getElementById('Form'+id);
	elemento.submit();
}

</script>
<?php
    // Carrega permissões de dono e moderador do perfil
    $allowedParticipante = $this->participante;
?>
<div class="exibir forum">
    <div class="dados">
	<div class="titulo" ></div>
    <div class='dados-topo'"></div>
    <div class='dados-conteudo'>
        <div class="topico_tit" style="font-size:21px;"><?php echo $this->escape($this->objeto['titulo']); ?></div>
		<div class='autor' style='font-size:10px;'><i>Postado por:</i> <?php echo $this->escape($this->objeto['usuario']['nome']); ?></div>
		<div class='data' style='font-size:10px;'><?php echo $this->datetime($this->objeto['dataCriacao']); ?></div>
		<a href="javascript:void detalheConteudo()"><h2 id="textoDescricao" style='font-size:16px'>Descrição +</h2></a>
		<div class="texto" id="dados-conteudo" style="display: none;"><?php echo $this->objeto['mensagem']; ?></div>
		<div class="acao-item">
		<?php if($allowedParticipante): ?>
		<a href="javascript:void detalhe('Resp0')">Responder</a><?php endif; ?>
		</div>
        </div>
        <div class='dados-base'></div>


	<div style="display: none;" id=Resp0 class="resposta">
	<br/>
	<?php
		$this->form->setName("Form0");
		$this->form->getElement('idComuTopicoMsg')->setValue(0);
		$this->form->getElement('idComuTopico')->setValue($this->objeto['idComuTopico']);
		$this->form->getElement('mensagem')->setAttrib('id',"Text0");
		$this->form->getElement('ok')->setAttrib('onClick', "submitform('0')");
	 	echo $this->form;
	 ?>
	</div>
		<?php

/*
$hist isso da corrente.
*/

			$boMsgFilhoTeste = new Aew_Model_Bo_ComuTopicoMsg();
			$mensagenPai = $boMsgFilhoTeste->getQtdMensagensPai($this->objeto['idComuTopico']);

			$total = count($mensagenPai);

/*
Listas as correntes.
*/

//for ($i = $total; $i != 0; $i--) {
   prox_nivel($this->baseUrl(), 0, $this->objeto['idComuTopico'], $total, $hist, $cont, $mesg, 1, true);
//} // Fim for
//print_r($this->objeto);


?>
<br>
<br>
<table cellSpacing="0" cellPadding="0" width="100%" border="0">
  <?php
if ($cont>0){
?>
<tr>
    <td  width="500" bgColor="#99cc99"><font color="#000000"> Histórico de Respostas</font></td>
    <td  noWrap width="110" bgColor="#99cc99"><font
      color="#000000">Data</font></td>
  </tr>
<?php
}
$cor = 1; // Variavel responsavel pelas cores alternadas
for ($i = 0; $i < $cont; $i++) {
   $cor = -$cor;
   if ($cor == -1) {
      $strcor = "#E6FFE6";
   } else {
      $strcor = "#FFFFD7";
   } // Fim if
?>
  <tr vAlign="center">
    <td bgColor=<?php echo $strcor;?>>
      <table cellSpacing="0" cellPadding="0" border="0" height="5" style="margin: 0 0 0 0;">
        <tbody>
          <tr style="margin:0 0 0 0;">
            <td noWrap bgColor=<?php echo $strcor;?>><?php echo $mesg[$i][0];?></td>
            <td noWrap bgColor=<?php echo $strcor;?>><div id="teste" style="width:10px;">
            <?php if ($mesg[$i][7] == true){?>
            	<a href="javascript:void detalhe('<?php echo $mesg[$i][2];?>','pai')"><font color="#000000"><?php echo $mesg[$i][1];?></font></a>
            <?php } else {?>
            	MENSAGEM APAGADA PELO USUÁRIO
            <?php }?>
            </div></td>
          </tr>
        </tbody>
      </table>
    </td>
    <td  noWrap bgColor=<?php echo $strcor;?>><?php echo $this->datetime($mesg[$i][3]);?></td>

  </tr>


    <tr>

      <td colspan="2" style="margin-top: 0;">

         <div class="respostas" style="display: none;" id="<?php echo $mesg[$i][2];?>">
         <div id="geral" style="display:table; height:auto;">
         <div class="linha">

         		<!--  coluna da foto -->
    			<div class="coluna">
		    			 <?php
		    			 	//imprimindo a foto do usuário que deixa resposta
                                                $abreLink = "<a href='". $this->url(array('module' => 'espaco-aberto', 'controller' => 'perfil',
								           'action' => 'exibir', 'usuario' => $mesg[$i][6]), null, true) ."'>";
                                                $fechaLink = "</a>";
		    			 	$linkFoto = Usuario::foto($mesg[$i][6], $this->baseUrl(), 62, 64);
		         			echo $abreLink . $linkFoto . $fechaLink;
		         		 ?>
    			</div>

         		<!--  coluna da resposta -->
    			<div class="coluna"  style="width:430px;"  >
		    			<?php
		    				//imprime a resposta do usuário
		         			echo $mesg[$i][4];
		         		?>
    			</div>
<?php if($allowedParticipante): ?>
    		<span> <?php
    		if($mesg[$i][6] == $this->usuario['idUsuario'])
    		 echo "<a style='font-size:12px' href='". $this->url(array('module' => 'espaco-aberto', 'controller' => 'forum',
						   'action' => 'apagarmsg', 'id' => $this->objeto['idComuTopico'], 'mensagem' => $mesg[$i][2], 'comunidade' => $this->objeto['idComunidade']), null, true) ."'>Apagar</a> |";
    		?>
    		<a style='font-size:12px' href="javascript:void detalhe('<?php echo "Resp".$mesg[$i][2];?>','filho')">Responder</a></span>
<?php endif; ?>

		</div> <!--  fecha div da linha 133 (linha) -->

			      <!--  Editor(form) para usuário responder -->
			      <div id="editorConteudo" style="display:table; height:auto;" align="center">
			      <div style="display: none;" id=Resp<?php echo $mesg[$i][2];?> align="center">
						<?php
						 $this->form->setName("Form".$mesg[$i][2]);
						 $this->form->getElement('idComuTopicoMsg')->setValue($mesg[$i][2]);
						 $this->form->getElement('idComuTopico')->setValue($this->objeto['idComuTopico']);
						 $this->form->getElement('mensagem')->setAttrib('id',"Text".$mesg[$i][2]);
						 $this->form->getElement('ok')->setAttrib('onClick', "submitform('".$mesg[$i][2]."')");
						 echo "<span align='center'>".$this->form."</span>";
						 ?>
				 </div> </div>
</div>
	  </div> <!--  Fecha div respostas -->
            </td>

            </tr>

<?php
} // Fim for linha 101
?>
</table>
</div> </div>
