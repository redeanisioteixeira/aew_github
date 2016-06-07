<?php
	if(!$this->buscaSimples):
		return;
	endif;

    $this->inlineScript()->appendFile('/assets/js/busca.js',"text/javascript");
?>

<?php $this->placeholder('buscaSimples')->captureStart();?>
	<div class="pull-right margin-right-05">
		<form id="custom-search-form" class="form-horizontal shadow-bottom padding-all-05 padding-left-10" method="post" action="/conteudos-digitais/conteudos/listar" onsubmit="executaBusca();" role="form">
		    <input name="busca" type="text" class="search-query" placeholder="Buscar..." autocomplete="off"/>
			<button name="buscar" class="box-loading-ajax font-size-150" title="Buscar" alt="Buscar" onclick="$('input[name=limpar]').val(0)"><b><i class="fa fa-search"></i></b></button>
 
			<input type="hidden" name="tipos" value="">
            <input type="hidden" name="opcoes" value="">
            <input type="hidden" name="limpar" value="1">

		</form>
	</div>	
<?php $this->placeholder('buscaSimples')->captureEnd();?>
