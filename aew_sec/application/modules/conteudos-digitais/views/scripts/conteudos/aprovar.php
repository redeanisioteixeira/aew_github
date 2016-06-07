<?php if($this->conteudosResumo):?>
    <section class="panel panel-default">
        <header class="panel-heading">
            <h4 class="text-center margin-none"><b>Estatistícas dos Conteúdos Digitais</b></h4>
        </header>

        <div class="panel-body">

            <div class="col-lg-<?php echo ($this->usuarioLogado->isAdmin() ? 8 : 12);?>">

				<div class="row">

				    <div class="col-lg-5">

						<div class="row">

							<!-- Dados para gerar gráfico -->
						    <div id="grafico-resumo-imagem" name="grafico-resumo" class="graphic shadow-center rounded" onload="gerateGraphic(this, 'PieChart');" width="370" height="370">
								<input id="grafico-resumo" type="hidden" name="title" value="<?php echo $this->tituloGrafico;?>">
								<input id="grafico-resumo" type="hidden" name="subtitle" axisX="Período" axisY="Quantidade">
								<?php foreach($this->conteudosResumo as $porcentagem => $qtd):?>
								    <input id="grafico-resumo" type="hidden" name="data" title="<?php echo $porcentagem;?>" value="<?php echo $qtd;?>">
								<?php endforeach;?>
							</div>
						</div>	

				    </div>
				    
				    <div class="col-lg-7">

				        <table class="table table-striped">
				            
				            <thead>
				                <tr class="menu-azul">
				                    <th class="text-center"><b>PERÍODO</b></th>
				                    <th class="text-center"><b>PERCENTUAL</b></th>
				                    <th class="text-center"><b>QUANTIDADE</b></th>
				                </tr>
				            </thead>
				            
				            <tbody>
				                <?php foreach($this->conteudosResumo as $periodo => $qtd):?>
				                    <tr>   
				                        <td><b><?php echo  $periodo;?></b></td>
				                        <td class="text-right"><?php echo number_format(round($qtd*100/$this->totalGeral,1),1,',','').'%';?></td>
				                        <td class="text-right"><?php echo number_format($qtd,0,'','.');?></td>
				                    </tr>
				                <?php endforeach;?>
				            </tbody>
				            <tfoot>   
				                <tr class="menu-cinza">
				                    <td colspan="2"><b>CONTEÚDOS PUBLICADOS :</b></td>
				                    <td class="text-right"><b><?php echo number_format($this->totalGeral, 0, '', '.');?></b></td>   
				                </tr>
				                <?php if($this->conteudos->getTotalItemCount()):?>
				                    <tr class="menu-vermelho">   
				                        <td colspan="2"><b>PENDENTES POR APROVAR :<b></td>
				                        <td class="text-right"><b><?php echo number_format($this->conteudos->getTotalItemCount(), 0, '', '.');?></b></td>
				                    </tr>
				                <?php endif;?>
				            </tfoot>
				        </table>

				    </div>

				</div>

			</div>

			<?php if($this->usuarioLogado->isAdmin()):?>
				<?php $espaco = array();?>
		        <div class="col-lg-4">
		            <table class="table table-striped">
		                
		                <thead>
		                    <tr class="menu-vermelho">
		                        <th colspan="2" class="text-center"><b><i class="fa fa-hdd-o" aria-hidden="true"></i> UTILIZAÇÃO ESPAÇO EM DISCO</b></th>
		                    </tr>
		                </thead>
		                
		                <tbody>
	                        <tr>
	                            <td><b>Espaço Utilizado</b></td>
	                            <td class="text-right"><?php echo Sec_Controller_Action::calcularEspacoDisco(2, $espaco[]);?></td>
	                        </tr>
	                        <tr>
	                            <td><b>Espaço Livre</b></td>
	                            <td class="text-right"><?php echo Sec_Controller_Action::calcularEspacoDisco(0, $espaco[]);?></td>
	                        </tr>

		                </tbody>
		                <tfoot>   
		                    <tr class="menu-cinza">
		                        <td><b>TOTAL ESPAÇO FÍSICO :</b></td>
		                        <td class="text-right"><b><?php echo Sec_Controller_Action::calcularEspacoDisco(1, $espaco[]);?></b></td>   
		                    </tr>
		                </tfoot>
		            </table>

					<!-- Dados para gerar gráfico -->		            
		            <div id="grafico-espaco-imagem" name="grafico-espaco" class="graphic shadow-center rounded" onload="gerateGraphic(this, 'PieChart');" width="350" height="190">
				        <input id="grafico-espaco" type="hidden" name="title" value="Utilização Espaço em disco">
						<input id="grafico-espaco" type="hidden" name="subtitle" axisX="Tipo" axisY="% Espaço">
						<input id="grafico-espaco" type="hidden" name="data" title="Utilizado" value="<?php echo $espaco[0]?>">
						<input id="grafico-espaco" type="hidden" name="data" title="Livre" value="<?php echo $espaco[1]?>">
					</div>

		        </div>

			<?php endif;?>

		    <div class="col-lg-12 margin-top-20">

				<div class="row">

				    <div class="col-lg-6">
				        <table class="table table-striped">
				            
				            <thead>
				                <tr class="menu-azul">
				                    <th class="text-center"><b>TIPO DE CONTEÚDO</b></th>
				                    <th class="text-center"><b>PERCENTUAL</b></th>
				                    <th class="text-center"><b>QUANTIDADE</b></th>
				                </tr>
				            </thead>
				            
				            <tbody>
								<?php $conteudoTipo = new Aew_Model_Bo_ConteudoTipo();?>
				                <?php foreach($this->conteudosResumoPorTipo as $resumoTipo):?>
									<?php $conteudoTipo->setNome($resumoTipo['nomeconteudotipo']);?>
				                    <tr>   
				                        <td>
											<label>
												<a target="_blank" href="/conteudos-digitais/conteudos/listar/tipos/<?php echo $resumoTipo['idconteudotipo'];?>">
													<img name="tipo-conteudo" class="menu-azul rounded margin-right-05" src="/assets/img/icones/<?php echo $conteudoTipo->getIconeTipo();?>.png" width="24" height="24"/>
													<?php echo $resumoTipo['nomeconteudotipo'];?>
												</a>
											</label>
										</td>
				                        <td class="text-right"><?php echo number_format(round($resumoTipo['qtdconteudos']*100/$this->totalGeral,1),1,',','').'%';?></td>
				                        <td class="text-right"><?php echo number_format($resumoTipo['qtdconteudos'],0,'','.');?></td>
				                    </tr>
				                <?php endforeach;?>
				            </tbody>
				            <tfoot>   
				                <tr class="menu-cinza">
				                    <td colspan="2"><b>TOTAL CONTEÚDOS :</b></td>
				                    <td class="text-right"><b><?php echo number_format($this->totalGeral, 0, '', '.');?></b></td>
				                </tr>
				            </tfoot>
				        </table>

					</div>

					<div class="col-lg-6">

						<!-- Dados para gerar gráfico -->		            
				        <div id="grafico-tipoconteudo-imagem" name="grafico-tipoconteudo" class="graphic shadow-center rounded" onload="gerateGraphic(this, 'BarChart');" width="700" height="530">
						    <input id="grafico-tipoconteudo" type="hidden" name="title" value="Resumo de Contéudos por Tipo">
							<input id="grafico-tipoconteudo" type="hidden" name="subtitle" title="Unidades" axisX="Tipo de Conteúdo" axisY="Quantidade">
							<?php foreach($this->conteudosResumoPorTipo as $resumoTipo):?>
							    <input id="grafico-tipoconteudo" type="hidden" name="data" title="<?php echo $resumoTipo['nomeconteudotipo'];?>" value="<?php echo $resumoTipo['qtdconteudos'];?>" color="<?php echo $this->ConverterHexRgba(null, null, true);?>">
							<?php endforeach;?>
						</div>

					</div>

				</div>

			</div>

        </div>

    </section>
<?php endif;?>
    
<?php echo $this->render('conteudos/listar.php');?>
