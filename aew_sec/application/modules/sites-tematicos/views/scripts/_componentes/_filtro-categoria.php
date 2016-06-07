<?php echo $this->placeholder('barraFiltro')->captureStart();?>

<div class="panel-heading padding-all-05">
	<ul class="list-unstyled list-inline margin-none">
		<li class="padding-all-05 hidden-sm hidden-xs"><h5>Filtrar por:</h5></li>
		
		<li class="btn-group" role="group">
			<?php if($this->disciplinas):?>
			    <button type="button" class="btn btn-xs btn-cinza link-vermelho disabled"><h5><b><i class="fa fa-graduation-cap"></i> Disciplinas</b></h5></button>
			<?php else:?>
			    <button type="button" class="box-loading-ajax btn btn-xs btn-cinza" onclick="location.href='/sites-tematicos/disciplinas'"><h5><b><i class="fa fa-graduation-cap"></i> Disciplinas</b></h5></button>
			<?php endif;?>

			<?php if($this->temastransversais):?>
			    <button type="button" class="btn btn-xs btn-cinza link-vermelho disabled"><h5><b><i class="fa fa-venus-mars"></i> Temas Tranversais</b></h5></button>
			<?php else:?>
			    <button type="button" class="box-loading-ajax btn btn-xs btn-cinza" onclick="location.href='/sites-tematicos/temas-transversais'"><h5><b><i class="fa fa-venus-mars"></i> Temas Tranversais</b></h5></button>
			<?php endif;?>

		</li>
	</ul>
</div>
<?php echo $this->placeholder('barraFiltro')->captureEnd();?>
