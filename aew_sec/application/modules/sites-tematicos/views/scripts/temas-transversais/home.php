<?php
$this->render('_componentes/_destaques.php');
$i = $j = 1;
?>

<?php echo $this->placeholder('barraDestaques');?>

<div class="panel panel-default">

	<div class="panel-body">

		<h5 class="page-header menu-cinza margin-bottom-10"><b><i class="fa fa-hand-pointer-o" aria-hidden="true"></i> Clique no icone e confira todos os conteúdos organizados por <i class="link-vermelho"><?php echo ($this->disciplinas ? 'disciplinas' : 'temas transversais');?></i>. Pesquise, estude e amplie seus conhecimentos!</b></h5>

		<div id="opcoes-sites-tematicos" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<a id="topo-conteudo" class="scroll_nag hidden" href="#inicio">topo</a>
			<ul class="opcoes-sites-tematicos list-unstyled row">
				<?php foreach($this->temastransversais as $disciplina):?>

					<li class="<?php echo ($i == 7 ? 'col-lg-offset-3' : '');?> col-lg-2 col-md-2 col-sm-12 col-xs-12">
					    <figure class="height-180 site-tematico-opcao site-tematico<?php echo $disciplina->getId();?> margin-bottom-10  arrow-left right">
					        <a class="site-tematico<?php echo ($i == 1 ? ' active' : '');?>" site="<?php echo $disciplina->getId();?>" posicao="<?php echo $i;?>" area="<?php echo $j;?>" controller="<?php echo $this->getController();?>">
					            <img class="site-tematico trans90 shadow-center cor-canal-fundo img-rounded img-responsive margin-auto" src="/assets/img/icones_disciplinas/icone_disciplina<?php echo $disciplina->getId();?>.png"/>
								<h5 class="menu-vermelho text-center margin-all-05"><b><?php echo $disciplina->getNome();?></b></h5>
					        </a>
					    </figure>
					</li>

			        <?php $i++;?>

				<?php endforeach;?>
			</ul>
		</div>

		<div id="lista-sites-tematicos" class="lista-sites-tematicos lista-sites-tematicos<?php echo $j;?> col-lg-10 col-md-10 col-sm-8 col-xs-8"></div>
	</div>
</div>
