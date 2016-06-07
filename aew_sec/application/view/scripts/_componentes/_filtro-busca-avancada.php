<?php
    $arrNivelEnsino = array(5,6,3,4,11,12,13,7,8,10,9);
    $arrComponentes = array();
    
    $categoriaComponente = new Aew_Model_Bo_CategoriaComponenteCurricular();
    $options = array();
    $options['orderBy'] = 'categoriacomponentecurricular.idcategoriacomponentecurricular ASC';
    
    $categoriasComponentes = $categoriaComponente->select(0,0,$options);
    
    for($i=0;count($categoriasComponentes)>$i; $i++):
        $categoriasComponentes[$i]->selectComponentesCurriculares();
    endfor;

    $nivelEnsino = new Aew_Model_Bo_NivelEnsino();
    $niveisEnsino = $nivelEnsino->select();
    for($i=0;count($niveisEnsino)>$i;$i++):
        $niveisEnsino[$i]->selectComponentesCurriculares(0, 0, null, $options);
    endfor;
    
    $nivelEnsino->setId(5);
    $disciplinas  = $nivelEnsino->selectComponentesCurriculares(0, 0, null, $options);
    
    $categoriaComponente->setId(3);
    $temastransversais = $categoriaComponente->selectComponentesCurriculares(0, 0, null, $options);
    
    $componentesArray = array();
    $componentesConteudo = array();
    if($this->conteudo)
    {
        $componentesConteudo = $this->conteudo->selectComponentesCurriculares();
    }

    foreach($componentesConteudo as $componenteConteudo)
    {
        $idcompoentecurricular = $componenteConteudo->getId();
        $componentesArray[$i] = $idcompoentecurricular[0];
        $i++;
    }
    $arrComponentes = $componentesArray;
    
    $row = 1;
    $grupo = 1;
    $abrir = true;
    $fechar = true;
    $cor = "preto";
    
    $this->placeholder("filtroAvancado")->captureStart();
?>

<div class="opcoes-busca panel panel-default">

    <div class="panel-heading">
        <span class="text-info">Selecione o(s) <b>componente(s) curricular(es)</b> ou <b>disciplina(s)</b> que mais se adequem ao contéudo:</span>
    </div>

    <ul id="opcoes-componentes" class="list-unstyled panel-body padding-top-05">
        
        <!-- Lista categorias -->
        <li class="col-lg-12">

            <div class="row">
                
                <div id="opcoes-categoria">
                    <h4 class="link-preto"><b><i class="fa fa-ellipsis-v"></i> Áreas específicas/Séries</b></h4>
                        
                    <?php foreach($categoriasComponentes as $categoriaComponente):?>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="row page-publisher">
                                <label id="categoria<?php echo $categoriaComponente->getId();?>" class="inline item-superior cursor-pointer fa fa-square-o" <?php echo $this->corfonte;?>>
                                    <input id="categoria<?php echo $categoriaComponente->getId();?>" type="checkbox" value="<?php echo $categoriaComponente->getId();?>" name="item-superior" categoria="<?php echo $categoriaComponente->getId();?>" nivel-ensino="0" class="desativado">
                                    <h5 class="margin-none"><b><?php echo $categoriaComponente->getNome();?></b></h5>
                                </label>

                                <ul id="categoria<?php echo $categoriaComponente->getId();?>" class="list-unstyled padding-left-10 margin-bottom-10">
                                    <?php foreach($categoriaComponente->getComponentesCurriculares() as $componente):?>
                                    <li>
                                        <label  id="componente<?php echo $componente->getId()?>" class="inline opcao-item cursor-pointer fa <?php echo(strlen(array_search($componente->getId(), $arrComponentes)) ? "fa-check-square-o link-preto" : "fa-square-o normal");?>">
                                            <input id="componente<?php echo $componente->getId();?>"  type="checkbox"  value="<?php echo $componente->getId();?>" categoria="<?php echo $categoriaComponente->getId()?>" nivel-ensino="0" name="opcao-item" class="categoria<?php echo $categoriaComponente->getId();?> margin-right-05 desativado" <?php echo(strlen(array_search($componente->getId(), $arrComponentes)) ? "checked" : "");?>> <h6 class="margin-none"><?php echo $componente->getNome();?></h6>
                                        </label>
                                    </li>
                                    <?php endforeach;?>
                                </ul>

                            </div>
                        </div>
                    <?php endforeach;?>
                </div>
            </div>
            
        </li>
        
        <!-- Lista componentes curriculares -->
        <li class="col-lg-12">
            
            <div class="row">
            
                <div id="opcoes-componentes">
                    
                    <h4 class="link-preto"><b><i class="fa fa-ellipsis-v"></i> Componente curricular/Disciplina</b></h4>

                    <?php foreach($arrNivelEnsino as $nivel):?>
                        <?php foreach($niveisEnsino as $nivelEnsino):?>

                            <?php if($nivelEnsino->getId() == $nivel):?>
                                <?php $componentesRelacionados = $nivelEnsino->selectComponentesCurriculares();?>

                                <?php if(count($componentesRelacionados)>0):?>

                                    <?php $abrir  = ($row == 1 ? true : false);?>
                                    <?php $fechar = ($row == 1 ? true : false);?>

                                    <?php if($abrir):?>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <span id="grupo<?php echo $grupo;?>" name="opcao-grupo" class="hidden-ms hidden-xs link-preto cursor-pointer fa fa-chevron-down pull-left" style="margin:-3px 0 0 -20px"></span>
                                    <?php endif;?>

                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="row page-publisher">
                                                        <label id="nivel-ensino<?php echo $nivelEnsino->getId();?>" class="inline item-superior cursor-pointer fa fa-square-o" <?php echo $this->corfonte;?>>
                                                            <input id="nivel-ensino<?php echo $nivelEnsino->getId();?>" type="checkbox" name="item-superior" value="<?php echo $nivelEnsino->getId();?>" categoria="0" nivel-ensino="<?php echo  $nivelEnsino->getId()?>" class="desativado">
                                                            <h5 class="margin-none"><b><?php echo $nivelEnsino->getNome();?></b></h5>
                                                        </label>
                                                        <span id="nivel-ensino<?php echo $nivelEnsino->getId();?>" name="opcao-grupo" class="link-preto cursor-pointer fa fa-chevron-down hidden-lg hidden-md"></span>
                                                        <ul id="nivel-ensino<?php echo $nivelEnsino->getId();?>" class="list-unstyled padding-left-10 margin-bottom-10 desativado grupo<?php echo $grupo;?> nivel-ensino<?php echo $nivelEnsino->getId();?>" grupo="grupo<?php echo $grupo;?>">
                                                            <?php foreach($componentesRelacionados as $componente):?>
                                                                <li>
                                                                    <label id="componente<?php echo $componente->getId()?>" class="inline opcao-item cursor-pointer fa <?php echo(strlen(array_search($componente->getId(), $arrComponentes)) ? "fa-check-square-o link-preto" : "fa-square-o normal");?>"><input id="componente<?php echo $componente->getId()?>" type="checkbox" value="<?php echo $componente->getId()?>" categoria="0" nivel-ensino="<?php echo $nivelEnsino->getId();?>" name="opcao-item" class="nivel-ensino<?php echo $nivelEnsino->getId();?> margin-right-05 desativado" <?php echo(strlen(array_search($componente->getId(), $arrComponentes)) ? "checked" : "");?>> <h6 class="margin-none"><?php echo $componente->getNome();?></h6></label>
                                                                </li>
                                                            <?php endforeach;?>
                                                        </ul>
                                                    </div>
                                                </div>
                                            <?php $row++;?>

                                    <?php if($fechar):?>
                                        </div>
                                        <?php $row = 1; $grupo++;?>
                                    <?php endif;?>

                                <?php endif;?>

                            <?php endif;?>

                        <?php endforeach;?>

                    <?php endforeach;?>
                    
                </div>
                
            </div>
        </li>
    </ul>
</div>

<?php
    $this->placeholder('filtroAvancado')->captureEnd();
    echo $this->placeholder('filtroAvancado');
?>
