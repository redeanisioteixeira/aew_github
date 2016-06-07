<?php
    $arrTipos = array();
    
    
    if($this->session->tipos != "")
        $arrTipos = explode(",",$this->session->tipos);

    $ordenarPor = $this->getOrdenarPor();
    $opcao_gerarRelatorio = $this->url(array('module' => 'conteudos-digitais', 'controller' => 'conteudos','action' => 'relatorio'), null, true);

    $tipoConteudo = new Aew_Model_Bo_ConteudoTipo();
    $tipoConteudo = $tipoConteudo->select();
    
    if(!$this->panel):
        $cor = "info";
        switch ($this->getModule()):
            case "sites-tematicos":
                    $cor = "danger";
                    break;

            case "conteudos-digitais":
                    $cor =  "info";
                    break;

            case "tv-anisio-teixeira" || "ambientes-de-apoio":
                    $cor =  "warning";
                    break;
        endswitch;
    else:
        $cor = $this->panel;
    endif;
    
    $this->placeholder('filtroBusca')->captureStart();
?>

<div class="col-lg-12">
    
    <div class="row">
        <form id="formBusca" name="busca" method="post" action="/conteudos-digitais/conteudos/listar" class="form-horizontal" role="form" onsubmit="executaBusca();">

            <div class="panel panel-body panel-default">

                <input type="hidden" name="quantidade" value="<?php echo $this->session->quantidade;?>">
                <input type="hidden" name="opcoes" value="">
                <input type="hidden" name="tipos" value="<?php echo $this->session->tipos;?>">
                <input type="hidden" name="ordenarPor" value="<?php echo $this->session->ordenarPor;?>">
                <input type="hidden" name="favorito" value="<?php echo $this->session->favorito;?>">
                <input type="hidden" name="por" value="">
                <input type="hidden" name="limpar" value="0">
                <input type="hidden" name="pagina" value="">

                <div class="col-lg-12">

                    <div class="row">

                        <label for="buscar" class="optional hidden-xs hidden-sm">Se deseja pesquisar conteúdos por palavras específicas utilize aspas (""). Por exemplo: "ação"</label> 
                        <div class="input-group">
                            <input type="text" name="busca" placeholder="buscar conteúdos digitais ou sites temáticos" class="form-control" value='<?php echo $this->session->busca;?>'>
                            <span class="input-group-btn">
                                <button name="buscar" class="btn btn-<?php echo $cor;?>" title="Buscar" alt="Buscar" onclick="$('input[name=limpar]').val(0)"><i class="fa fa-search fa-lg"></i></button>
                                <button name="limpar" class="btn btn-primary" title="Limpar" alt="Limpar" onclick="$('input[name=limpar]').val(1)"><i class="fa fa-trash-o fa-lg"></i></button>
                            </span>
                        </div>

                    </div>

                </div>
                <!-- Filtro -->
                <div class="col-lg-12 margin-top-05">
                    <div class="row">
                        <div class="col-lg-5 col-md-6 col-sm-12 col-xs-12">
                            <div class="row margin-top-05">
                                <?php if(count($tipoConteudo)>0):?>
                                    <div name="filtroTipo" class="btn-group col-lg-12" data-toggle="buttons">
                                        <label name="tipo-conteudo" class="btn btn-primary padding-none <?php echo (!count($arrTipos) ? "active" : "");?>" title="Todos os conteúdos" alt="Todos os conteúdos" tipo-conteudo="0">
                                            <img name="tipo-conteudo" class="hidden-sm hidden-xs <?php echo (!count($arrTipos) ? "active" : "");?>" title="Todos" alt="Todos" src="/assets/img/icones/icone-todos-<?php echo (count($arrTipos) == 0 ? "in" : "out");?>.png" width="80" height="32" tipo-conteudo="0" data-toggle="tooltip"  data-placement="top"/>
                                            <img name="tipo-conteudo" class="hidden-lg hidden-md <?php echo (!count($arrTipos) ? "active" : "");?>" title="Todos" alt="Todos" src="/assets/img/icones/icone-todos-<?php echo (count($arrTipos) == 0 ? "in" : "out");?>_responsive.png" width="27" height="27" tipo-conteudo="0" data-toggle="tooltip" data-placement="top"/>
                                        </label>
                                        <?php foreach($tipoConteudo as $conteudo):?>
                                            <label name="tipo-conteudo" class="btn btn-primary padding-none <?php echo(strlen(array_search($conteudo->getId(), $arrTipos)) ? "active" : "");?>" tipo-conteudo="<?php echo $conteudo->getId();?>">
                                                <img name="tipo-conteudo" class="hidden-sm hidden-xs <?php echo(strlen(array_search($conteudo->getId(), $arrTipos)) ? 'active' : '');?>" title="<?php echo $conteudo->getNome();?>" alt="<?php echo $conteudo->getNome();?>" src="/assets/img/icones/<?php echo $conteudo->getIconeTipo();?>.png" width="32" height="32" tipo-conteudo="<?php echo $conteudo->getId();?>" data-toggle="tooltip"  data-placement="top"/>
                                                <img name="tipo-conteudo" class="hidden-lg hidden-md <?php echo(strlen(array_search($conteudo->getId(), $arrTipos)) ? 'active' : '');?>" title="<?php echo $conteudo->getNome();?>" alt="<?php echo $conteudo->getNome();?>" src="/assets/img/icones/<?php echo $conteudo->getIconeTipo();?>.png" width="27" height="27" tipo-conteudo="<?php echo $conteudo->getId();?>" data-toggle="tooltip"  data-placement="top"/>
                                            </label>
                                        <?php endforeach;?>
                                    </div>
                                <?php endif;?>
                            </div> 
                        </div>
                        <!-- Favorito Copiar-link -->
                        <div class="col-lg-7 col-md-6 col-sm-12 col-xs-12">
                            <div class="row margin-top-05">                        
                                <div class="btn-group col-lg-12" data-toggle="buttons">
                                    
                                    <?php if($this->href['relatorio_usuario']):?>
                                        <label name="relatorio-pdf" class="btn btn-danger padding-none"><a href="<?php echo $this->href['relatorio_usuario'];?>"><img title="Gerar relatório .PDF" alt="Gerar relatório .PDF" src="/assets/img/icones/icone-relatorio-pdf.png" width="32" height="32"/></a></label>
                                    <?php endif;?>
                                            
                                    <?php if($this->usuarioLogado): ?>
                                        <label name="favorito" class="btn btn-<?php echo $cor;?> opcao-favorito padding-none <?php echo ($this->session->favorito ? 'active' : '');?>"><img title="Meus favoritos" alt="Meus favoritos" src="/assets/img/icones/icone-favoritos.png" width="32" height="32"/></label>
                                    <?php endif; ?>
                                        
                                    <label name="copiar-link" class="btn btn-<?php echo $cor;?> opcao-ulrcurta padding-none" style="z-index: 3000;" data-urlcurta="/conteudos-digitais/conteudos/urlextendida">
                                        <img name="copiar-link" class="dropdown-toggle" data-toggle="dropdown" title="Copiar link" alt="Copiar link" src="/assets/img/icones/icone-copiar-link.png" width="32" height="32"/>
                                        <ul name="copiar-link" class="copiar-link dropdown-menu padding-all-05" role="menu">
                                            <li><input name="copiar-link" class="form-control" type="text" onfocus="this.select()" onmouseover="this.focus()" value="" readonly></li>
                                        </ul>	
                                    </label>

                                    <!-- Ordernar Por -->
                                    <div class="input-group-btn">
                                        <div class="btn-group">
                                            <button type="button" name="ordenarPor" class="btn btn-<?php echo $cor;?> dropdown-toggle" data-toggle="dropdown" tabindex="-1">
                                                <i class="fa fa-sort-alpha-asc fa-lg"></i> <span class="hidden-xs">Ordenado por <label name="ordenarPor" class="margin-none"><?php echo $ordenarPor[$this->session->ordenarPor];?></label></span>
                                                <span class="caret"></span>
                                                <span class="sr-only">Menu Ordenar</span>
                                            </button>

                                            <ul name="ordenarPor" class="ordenarpor dropdown-menu" role="menu">
                                                <?php foreach($ordenarPor as $key=>$value):?>
                                                    <li<?php echo ($this->session->ordenarPor == $key ? " class='active'" : "");?>><a class="fa <?php echo ($this->session->ordenarPor == $key ? "fa-check-square-o" : "fa-square-o");?>" value="<?php echo $key;?>"> <?php echo $value;?></a></li>
                                                <?php endforeach;?>
                                            </ul>
                                        </div>
                                        
                                        <div class="btn-group hidden-sm hidden-xs">
                                            <button type="button" name="quantidade" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" tabindex="-1" title="conteúdos por página" alt="conteúdos por página">
                                                <span name="quantidade"><i class="fa fa-list"></i> <?php echo $this->session->quantidade;?></span>
                                                <span class="caret"></span>
                                                <span class="sr-only">Menu conteúdos por página</span>
                                            </button>

                                            <ul name="quantidade" class="quantidade dropdown-menu" role="menu">
                                                <?php for($qtde = 15; $qtde <= 60; $qtde += 15):?>
                                                    <li<?php echo ($this->session->quantidade == $qtde ? " class='active'" : "");?>><a class="fa <?php echo ($this->session->quantidade == $qtde ? "fa-check-square-o" : "fa-square-o");?>" value="<?php echo $qtde;?>"> <?php echo $qtde;?></a></li>
                                                <?php endfor;?>
                                            </ul>
                                        </div>
                                            
                                        <?php if(!$this->editando):?>
                                            <button type="button" name="busca-avancada" class="btn btn-<?php echo ($this->session->opcoes ? 'primary' : $cor);?>">
                                                <i class="fa <?php echo ($this->session->opcoes ? 'fa-check-square-o' : 'fa-square-o');?>"><div id="conector" class="desativado"></div></i>
                                                <span class="hidden-md hidden-sm hidden-xs">busca avançada</span> 
                                                <span class="sr-only">busca avançada</span>
                                            </button>
                                        <?php endif;?>
                                            
                                    </div> <!-- input-group-btn -->
                                    
                                </div>
                                    
                            </div>

                        </div>
                        
                    </div>
                </div>
                
            </div> <!-- panel -->

            <?php if(!$this->editando):?>            
                <div class="busca-avancada col-lg-12 col-md-12 col-sm-12 col-xs-12 desativado">
                    <div class="row">
                        <?php echo $this->render('_componentes/_filtro-busca-avancada.php');?>
                    </div>
                </div>
            <?php endif;?>
            
        </form> <!-- form --> 

    </div> <!-- row -->

</div> <!-- col-lg-12 -->

<?php $this->placeholder('filtroBusca')->captureEnd();;?>