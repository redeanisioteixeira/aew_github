<div id="formatos-lista">
    
    <div class="col-lg-12">
        <?php echo $this->paginationControl($this->formatos,'Sliding','_componentes/_pagination_ajax.php', array('divContent' => '#formatos-lista'));?>
    </div>
    
    <table class="table table-striped table-responsive">

        <thead class="menu-cinza">
            <tr>
                <th class="text-center" width="30%"><b>TIPO CONTEÚDO</b></th>
                <th class="text-center" width="10%"><b>FORMATOS</b></th>
                <th class="text-center" width="20%"><b>QTDE. ARQUIVOS</b></th>
                <th class="text-left" width="40%">
                    <?php if(isset($this->href['adicionar_formato'])):?>
                        <a class="btn btn-default btn-xs" href="<?php echo $this->href['adicionar_formato'];?>" title="adicionar" data-toggle="tooltip" data-placement="top"><i class="fa fa-plus-circle"></i> Adicionar</a>
                    <?php endif;?>
                </th>
            </tr>
        </thead>

        <tbody>
            <?php $idtipoconteudoant = '';?>
            <?php foreach($this->formatos as $formato):?>

                <?php
                    $conteudosBo = new Aew_Model_Bo_ConteudoDigital();
                        
                    $options = array();
                    $options['where']['conteudodigital.idformato = ?'] = $formato->getId();
                    $conteudosArqV =  $conteudosBo->select(0, 0, $options);

                    $options = array();
                    $options['where']['conteudodigital.idformatodownload = ?'] = $formato->getId();
                    $conteudosArqD =  $conteudosBo->select(0, 0, $options);
                ?>
            
                <?php if($idtipoconteudoant != $formato->getConteudoTipo()->getId()):?>
                    <tr>
                        <td colspan="4">
                            <img class="menu-cinza img-circle shadow-center margin-right-10" src="/assets/img/icones/<?php echo $formato->getConteudoTipo()->getIconeTipo();?>.png" width="48" height="48">
                            <b><?php echo $formato->getConteudoTipo()->getNome();?></b>
                        </td>
                    </tr>
                    <?php $idtipoconteudoant = $formato->getConteudoTipo()->getId();?>
                <?php endif;?>
                    
                <tr>
                    <td></td>
                    <td><b class="link-preto">.<?php echo $formato->getNome();?></b></td>
                    <td class="text-center">

						<?php if(count($conteudosArqV)):?>
	                        <span class="box-badge"><span class="badge badge-bottom" title="Visualização" alt="Visualização"><i class="fa fa-eye"></i> (<?php echo count($conteudosArqV);?>)</span></span>
						<?php endif;?>

						<?php if(count($conteudosArqD)):?>
                        	<span class="box-badge"><span class="badge badge-bottom" title="Download" alt="Download"><i class="fa fa-download"></i> (<?php echo count($conteudosArqD);?>)</span></span >
						<?php endif;?>

                    </td>
                    <td>
                         <?php if(isset($this->href['editar_formato']) || isset($this->href['apagar_formato'])):?>

							<div class="btn-group btn-group-xs">

		                         <?php if(isset($this->href['editar_formato'])):?>
		                             <a class="btn btn-primary" href="<?php echo $this->href['editar_formato']."/id/".$formato->getId() ?>" title="editar" data-toggle="tooltip" data-placement="top"><i class="fa fa-edit"></i></a>
		                         <?php endif;?>

		                         <?php if(isset($this->href['apagar_formato']) && !count($conteudosArqV) && !count($conteudosArqD)):?>
		                             <a class="btn btn-danger" href="<?php echo $this->href['apagar_formato']."/id/".$formato->getId() ?>" title="apagar" data-toggle="tooltip" data-placement="top"><i class="fa fa-trash"></i></a>
		                         <?php endif;?>

                             </div>

                         <?php endif;?>
                     </td>                
                    
                </tr>

                
            <?php endforeach;?>
        </tbody>
    </table>
</div>
