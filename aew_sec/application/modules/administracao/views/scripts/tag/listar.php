<?php if(!$this->isAjax):?>
    <div class="panel panel-default margin-none">
        <div class="panel-body">
            <?php echo $this->formFiltrar;?>
        </div>
    </div>

    <div id="lista-tags" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
<?php endif;?>
        
    <div class="col-lg-12">
        <?php echo $this->paginationControl($this->tags,'Sliding','_componentes/_pagination_ajax.php', array('divContent' => '#lista-tags', 'params' => $this->params));?>
    </div>

    <table  class="table table-striped table-responsive">

        <thead class="menu-cinza">
            <tr>
                <th class="text-center" width="30%">
                    <b>TAG</b>
                </th>
                <th class="text-center" width="10%">
                    <b>Nº VEZES BUSCADA</b>
                </th>
                <th class="text-center" width="15%">
                    <b>QTDE. REFERÊNCIAS</b>
                </th>

                <th class="text-center" width="20%">
                    <b>ÚLTIMA CONSULTA</b>
                </th>

                <th class="text-center" width="20%">
                    <?php if(isset($this->href['adicionar_tag'])):?>
                        <a class="btn btn-default btn-xs" href="<?php echo $this->href['adicionar_tag'];?>" title="adicionar" data-toggle="tooltip" data-placement="top"><i class="fa fa-plus-circle"></i> Adicionar</a>
                    <?php endif;?>
					<a id="apagar-multiplo" class="desativado btn btn-danger btn-xs" title="apagar multiplo" data-toggle="tooltip" data-placement="top"><i class="fa fa-trash"></i> Apagar seleção</a>
                </th>
                <th class="text-center" width="5%">
					<?php if(isset($this->href['apagar_tag'])):?>
						<input name="marcar-todos" type="checkbox" title="Todos" data-toggle="tooltip" data-placement="top">
					<?php endif;?>
                </th>				
            </tr>
        </thead>

        <tbody>

			<?php if(isset($this->href['apagar_tag'])):?>
				<tr id="apagar-multiplo" class="desativado">
					<td colspan="6">
						<div class="alert alert-danger"><?php echo $this->formApagar;?></div>
					</td> 
				</tr>
			<?php endif;?>

            <?php foreach($this->tags as $tag):?>
                <tr>
                    <td><a href="/administracao/tag/exibir/id/<?php echo $tag->getId();?>"><?php echo $tag->getNome();?></a></td>

                    <td class="text-center">
						<?php if($tag->getBusca()):?>
							<span class="box-badge"><span class="badge badge-bottom" title="Pesquisada" alt="Pesquisada"><i class="fa fa-search"></i> (<?php echo $tag->getBusca();?>)</span></span>
						<?php endif;?>
					</td>

                    <td class="text-left">

                        <?php if($tag->qtdeusoCD):?>
                            <a target="_blank" href="/conteudos-digitais/conteudos/listar/tag/<?php echo $tag->getId();?>" title="Conteúdos Digitais" data-toggle="tooltip" data-placement="top"><span class="inline-block margin-right-10"><i class="link-azul fa fa-cubes"></i> <?php echo $tag->qtdeusoCD;?></span></a>
                        <?php endif;?>

                        <?php if($tag->qtdeusoAA):?>
                            <a target="_blank" href="/ambientes-de-apoio/ambientes/tags/id/<?php echo $tag->getId();?>" title="Apoio a Produção e Colaboração" data-toggle="tooltip" data-placement="top"><span class="inline-block"><i class="link-amarelo fa fa-wrench"></i> <?php echo $tag->qtdeusoAA;?></span></a>
                        <?php endif;?>

                    </td>

					<td>
                        <?php if($tag->getDataAtualizacao()):?>
							<span><i class="fa fa-clock-o"></i> <?php echo $this->SetupDate($tag->getDataAtualizacao(),4);?></span>
                        <?php endif;?>

					</td>

                    <td>
                         <?php if(isset($this->href['editar_tag']) || isset($this->href['apagar_tag'])):?>

                             <?php if(isset($this->href['editar_tag'])):?>
                                 <a class="btn btn-primary btn-xs" href="<?php echo $this->href['editar_tag'].'/id/'.$tag->getId();?>" title="editar" data-toggle="tooltip" data-placement="top"><i class="fa fa-edit"></i></a>
                             <?php endif;?>

                             <?php if(isset($this->href['apagar_tag']) && !$tag->qtdeusoCD && !$tag->qtdeusoAA):?>
                                 <a class="btn btn-danger btn-xs" href="<?php echo $this->href['apagar_tag'].'/id/'.$tag->getId();?>" title="apagar" data-toggle="tooltip" data-placement="top"><i class="fa fa-trash"></i></a>
                             <?php endif;?>

                         <?php endif;?>
					</td>                
					<td class="text-center">
						<?php if(isset($this->href['apagar_tag']) && !$tag->qtdeusoCD && !$tag->qtdeusoAA):?>
							<input name="apagar-tag" type="checkbox" value="<?php echo $tag->getId();?>">
						<?php endif;?>
					</td>

                </tr>

            <?php endforeach;?>
        </tbody>

    </table>

    <div class="col-lg-12">
        <?php echo $this->paginationControl($this->tags,'Sliding','_componentes/_pagination_ajax.php', array('divContent' => '#lista-tags'));?>
    </div>

<?php if(!$this->isAjax):?>
    </div>
<?php endif;?>
