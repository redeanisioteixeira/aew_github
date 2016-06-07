<script type="text/javascript" >
	$('body').delegate('a.convidar-colega', 'click', function(){
		var pai  = $(this).parent().parent().parent().parent();
		var id   = $(this).attr('colega');
		var nome = $(this).attr('nome');
		var nome = nome.toLowerCase();	

		$.post('/espaco-aberto/buscar/convidar',{colega: id},function(mensagem){
			var mensagem = mensagem.replace('[nome]',nome);
			var mensagem = '<div class="mensagem" style="font-size:13px !important;">'+mensagem+'</div>';

			$('div.resultado-acao').html(mensagem);

			$('div.mensagem').fadeOut(6000,function(){
				$(this).html('');
			});

			$('div#'+id+'.foto-item').fadeOut(2000,function(){
				$(this).remove();
				if(pai.html()==''){
					pai.remove();				
				}
			});

		},'JSON');
	})
</script>
<?php
$adminPost = $this->url(array('module' => 'espaco-aberto', 'controller' => 'buscar', 'action' => 'convidar-colega', 'usuario' => $this->usuarioLogado->getId()), null, true);
$adminPost = "<a class='convidar-colega' href='".$adminPost."'>convidar colega</a>";
$adminPost = "<a class='convidar-colega' colega='".$this->usuarioLogado->getId()."' nome='".$this->usuarioLogado->getNome()."'>convidar colega</a>";
$adminPost = $this->url(array('module' => 'espaco-aberto', 'controller' => 'colega', 'action' => 'remover', 'id' => $this->usuarioLogado->getId(), 'usuario' => $this->usuarioLogado->getId()), null, true);
$adminPost = "<a class='remover-colega' href='".$adminPost."' style='background-color:#CC0000'>remover colega</a>";
$adminPost = "<div class='acao-item'>".$adminPost."</div>";
$linkFoto = $this->usuarioLogado->getFotoPerfil()->getUrl();?>

<div class='mosaico-fotos' style='text-align: center;'>
    <div id='".$this->usuarioLogado->getId()."' class='foto-item".(fmod($i,2)==0?" foto-item-par":" foto-item-impar")."'>$linkFoto
	<span class='foto-data' style='display:none;padding-bottom:4px;'>$adminPost</span>
            <h1 class='foto-legenda' style='display:none'><a alt='Exibir perfil de $nomeCompleto' title='Exibir perfil de $nomeCompleto' href='$url_carregar_feed'>$nomeCompleto</a></h1>
    </div>
</div>
<div class='PaginacaoListar'>$this->objetos."</div>
<div class='conteudo-margin'>
    <span class='nao-encontrado'>Nenhum usuário encontrado</span>";
</div>
<div class="listagem">
    <div id="area-buscar">
	<?php echo $this->form_buscar;?>	
    </div>
    <div class="resultado-acao"></div>
</div>