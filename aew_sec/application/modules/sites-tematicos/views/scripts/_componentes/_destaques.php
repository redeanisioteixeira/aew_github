<?php $this->render('_componentes/_filtro-categoria.php');?>

<?php echo $this->placeholder('barraDestaques')->captureStart();?>
	<div class="panel panel-default margin-bottom-05">

		<?php echo $this->placeholder('barraFiltro');?>

		<div class="panel-body">

		    <!-- Nav tabs -->
		    <ul id="tab-disciplinas-temas" class="nav nav-pills nav-justified" role="tablist">
		        <li role="presentation" class="active"><a href="#destaques" aria-controls="destaques" role="tab" data-toggle="tab"><h4 class="margin-none"><b><i class="fa fa-lightbulb-o"></i> destaques</b></h4></a></li>
		        <li role="presentation"><a href="#mais-recentes" aria-controls="mais-recentes" role="tab" data-toggle="tab"><h4 class="margin-none"><b><i class="fa fa-plus-circle"></i> mais recentes</b></h4></a></li>
		        <li role="presentation"><a href="#mais-vistos" aria-controls="mais-vistos" role="tab" data-toggle="tab"><h4 class="margin-none"><b><i class="fa fa-eye"></i> mais vistos</b></h4></a></li>
		    </ul>

		    <!-- content tabs -->
		    <div class="tab-content margin-top-10">

		        <div id="destaques" role="tabpanel" class="tab-pane fade active in">
			        <?php foreach($this->rssDestaques as $entrada):?>

			            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 text-center">
			                <div class="row">
			                    <div class="col-style">
			                        <a href="<?php echo $entrada['link'];?>">
										<figure class="img-formato img-formato-70 rounded shadow-center menu-<?php echo $entrada['color']?>" style="background-image: url(<?php echo $entrada['img']->url;?>)"></figure>
			                        </a>
			                        <a class="btn btn-readmore menu-<?php echo $entrada['color']?>" href="<?php echo $entrada['link']?>" role="button">Leia <i class="fa fa-plus-circle fa-1x"></i></a>
			                        <h4 class="menu-<?php echo $entrada['color']?>"><b><?php echo $entrada['title'];?></b></h4>
			                        <p><?php echo $this->readMore($entrada['description'],180);?></p>
			                    </div>
			                </div>                                
			            </div>

			        <?php endforeach;?>
				</div>

		        <div id="mais-recentes" role="tabpanel" class="tab-pane fade">

			        <?php foreach($this->rssRecentes as $entrada):?>

			            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 text-center">
			                <div class="row">
			                    <div class="col-style">
			                        <a href="<?php echo $entrada['link'];?>">
										<figure class="img-formato img-formato-70 rounded shadow-center menu-<?php echo $entrada['color']?>" style="background-image: url(<?php echo $entrada['img']->url;?>)"></figure>
			                        </a>
			                        <a class="btn btn-readmore menu-<?php echo $entrada['color']?>" href="<?php echo $entrada['link']?>" role="button">Leia <i class="fa fa-plus-circle fa-1x"></i></a>
			                        <h4 class="menu-<?php echo $entrada['color']?>"><b><?php echo $entrada['title'];?></b></h4>
			                        <p><?php echo $this->readMore($entrada['description'],180);?></p>
			                    </div>
			                </div>                                
			            </div>

			        <?php endforeach;?>

				</div>

		        <div id="mais-vistos" role="tabpanel" class="tab-pane fade">

			        <?php foreach($this->rssVistos as $entrada):?>

			            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 text-center">
			                <div class="row">
			                    <div class="col-style">
			                        <a href="<?php echo $entrada['link'];?>">
										<figure class="img-formato img-formato-70 rounded shadow-center menu-<?php echo $entrada['color']?>" style="background-image: url(<?php echo $entrada['img']->url;?>)"></figure>
			                        </a>
			                        <a class="btn btn-readmore menu-<?php echo $entrada['color']?>" href="<?php echo $entrada['link']?>" role="button">Leia <i class="fa fa-plus-circle fa-1x"></i></a>
			                        <h4 class="menu-<?php echo $entrada['color']?>"><b><?php echo $entrada['title'];?></b></h4>
			                        <p><?php echo $this->readMore($entrada['description'],180);?></p>
			                    </div>
			                </div>                                
			            </div>

			        <?php endforeach;?>

				</div>

			</div>

		</div>
	</div>
<?php echo $this->placeholder('barraDestaques')->captureEnd();?>
