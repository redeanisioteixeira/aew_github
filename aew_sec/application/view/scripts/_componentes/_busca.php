<?php
    $this->inlineScript()
		->appendFile('/assets/js/busca.js',"text/javascript")
		->appendFile('/assets/js/autocomplete.js','text/javascript');

    if($this->editando):
        return;
    endif;
    
    $request = Zend_Controller_Front::getInstance();

	$opcaoPalavraBusca = $request->getRequest()->getParam("opcao-busca-palavra", $this->session->opcaoBuscaPalavra);
	$visualizacao = $request->getRequest()->getParam("visualizacao", $this->session->visualizacao);
    //$visualizacao = ($this->session->visualizacao == "" ? "column" : $this->session->visualizacao);

    if($this->getModule() == 'conteudos-digitais' && $this->getController() == 'conteudos' && $this->getAction() == 'listar'):

		if($this->session->publicador):
		    $usuario = new Aew_Model_Bo_Usuario();
		    $usuario->setId($this->session->publicador);
		    $usuario = $usuario->select(1);
		    
		    if($usuario):
		        $paginaPai[] = array('titulo' => 'Conteúdos publicados por <b>'.$this->ShowUsuario($usuario, true, 'link-cinza-escuro').'</b>', 'filho' => true);
		        $this->paginaPai = $paginaPai;
		    endif;
		endif;

		if($this->session->tag):
		    $tag = new Aew_Model_Bo_Tag();
		    $tag->setId($this->session->tag);
		    $tag = $tag->select(1);
		    
		    if($tag):
		        $paginaPai[] = array('titulo' => 'Resultado da busca por <b class="link-cinza-escuro"><i>'.$tag->getNome().'</i></b>', 'filho' => true);
		        $this->paginaPai = $paginaPai;
		    endif;
		endif;

		if($this->session->busca):
			$titulo = $this->session->busca;
			if($opcaoPalavraBusca == 'titulo' && ($titulo[0] == "\"" && $titulo[count($titulo)-1] == "\"")):
				$this->session->busca = '';
			endif;
						
			if($this->session->busca):
		        $paginaPai[] = array('titulo' => 'Resultado da busca por <b class="link-cinza-escuro"><i>'.$this->session->busca.'</i></b>', 'filho' => true);
		        $this->paginaPai = $paginaPai;
			endif;
		endif;

    endif;

    $cor = "cinza";
    $corfundoavancada = "#767676";
    if(!$this->panel):
        switch ($this->getModule()):
            case "aew":
                    $cor = "cinza";
                    break;
        
            case "sites-tematicos":
                    $cor = "vermelho";
                    $corfundoavancada = "#E74C3C";
                    break;

            case "conteudos-digitais":
                    $cor =  "azul";
                    $corfundoavancada = "#2AABD2";
                    break;

            case "ambientes-de-apoio":
                    $cor =  "amarelo";
                    $corfundoavancada = "#F1C40F";
                    break;
                
            case "tv-anisio-teixeira":
                    $cor =  "marron";
                    $corfundoavancada = "#F09D00";
                    break;
        endswitch;
    else:
        switch ($this->panel):
            case "danger":
		            $cor = "vermelho";
		            $corfundoavancada = "#E74C3C";
		            break;
        endswitch;
    endif;

    $fundodegrade = $this->ConverterHexRgba("#FFFFFF", 1);
    $corfundoavancada = $this->ConverterHexRgba($corfundoavancada, 0.1);
    
    $collapsed = array();

    $arrTipos = array();
    if($this->session->tipos != "")
    {
        $arrTipos = explode(",",$this->session->tipos);
    }
    
    $arrComponentes = array();
    if($this->session->opcoes)
    {
        $arrComponentes = explode(",",$this->session->opcoes);
    }

    $arrLicencas = array();
    if($this->session->licencas)
    {
        $arrLicencas = explode(",",$this->session->licencas);
    }
   
    
    $ordenarPor = $this->getOrdenarPor();

    $licencas = new Aew_Model_Bo_ConteudoLicenca();
    $options = array();
    $options['where']['conteudolicenca.idconteudolicencapai IS NULL'] = "";
    $options['where']['EXISTS(SELECT * FROM conteudodigital WHERE conteudodigital.idlicencaconteudo = conteudolicenca.idconteudolicenca)'] = "";
    $options['orderBy'] = 'conteudolicenca.nomeconteudolicenca ASC';

    $licencas = $licencas->select(0,0,$options);
    foreach($licencas as $licenca):
        if(strlen(array_search($licenca->getId(), $arrLicencas))):
            $collapsed['licencas'] = true;
            break;
        endif;
    endforeach;

    $tipoConteudo = new Aew_Model_Bo_ConteudoTipo();
    $options = array();
    $options['orderBy'] = 'LOWER(sem_acentos(conteudotipo.nomeconteudotipo)) ASC';
    $tipoConteudo = $tipoConteudo->select(0,0,$options);

    foreach($tipoConteudo as $tipo):
        if(strlen(array_search($tipo->getId(), $arrTipos))):
            $collapsed['tipos-conteudo'] = true;
            break;
        endif;
    endforeach;
    
    $categoriaComponente = new Aew_Model_Bo_CategoriaComponenteCurricular();
    $options = array();
    $options['orderBy'] = 'categoriacomponentecurricular.idcategoriacomponentecurricular ASC';
    
    $categoriasComponentes = $categoriaComponente->select(0,0,$options);
    
    $options = array();
    $options['where'] = 'EXISTS(SELECT * FROM conteudodigitalcomponente WHERE conteudodigitalcomponente.idcomponentecurricular = componentecurricular.idcomponentecurricular)';

    for($i = 0; $i<count($categoriasComponentes); $i++):

        $componentes = $categoriasComponentes[$i]->selectComponentesCurriculares(0, 0, null, $options);

        foreach($componentes as $componente):
            if(strlen(array_search($componente->getId(), $arrComponentes))):
                $collapsed['categoria'.$categoriasComponentes[$i]->getId()] = true;
                break;
            endif;
        endforeach;
        
    endfor;
    
    $nivelEnsino = new Aew_Model_Bo_NivelEnsino();
    
    $arrNivelEnsino = array(6,3,4,11,12,13,7,8,10,9);
    $options = array();
    $options['orderBy'] = 'nivelensino.idnivelensino ASC';
    
    $niveisEnsino = $nivelEnsino->select(0,0,$options);
    
    for($i=0;$i<count($niveisEnsino);$i++):
        $componentes = $niveisEnsino[$i]->selectComponentesCurriculares(0, 0, null, $options);
        foreach( $componentes as $componente):
            if(strlen(array_search($componente->getId(), $arrComponentes))):
                $collapsed['nivel-ensino-'.$niveisEnsino[$i]->getId()] = true;
                if($niveisEnsino[$i]->getId() != 5):
                    $collapsed['busca-avançada'] = true;
                    break;
                endif;
            endif;
        endforeach;
    endfor;
?>

<section id="box-busca-geral" class="padding-bottom-120">
    <section id="box-busca-texto" class="absolute index2 margin-top-10 col-lg-12 col-md-12 col-sm-12 col-xs-12">

        <div class="menu-<?php echo $cor;?> row">
            <form id="formBusca" name="busca" method="get" action="/conteudos-digitais/conteudos/listar" class="form-horizontal" role="form" onsubmit="executaBusca();">

                <div class="container padding-all-10 padding-bottom-02">

                    <div class="col-lg-3 hidden-xs">
                        <div class="row">
                            <span><b class="padding-left-05 middle">Encontre conteúdos digitais ou sites temáticos</b></span>
                        </div>
                    </div>

                    <div class="col-lg-9">
                        <div class="row">
                            <div class="input-group">
                                <input id="tags" type="text" name="busca" placeholder="Se deseja pesquisar conteúdos por palavras específicas utilize aspas (&quot;&quot;). Por exemplo: &quot;ação&quot;" class="form-control not-comma opcao-busca-<?php echo $opcaoPalavraBusca;?>" value='<?php echo $this->session->busca;?>'/>
                                <span class="input-group-btn">
                                    <button name="buscar" class="btn btn-cinza link-<?php echo $cor;?> box-loading-ajax" title="Buscar" alt="Buscar" onclick="$('input[name=limpar]').val(0)"><i class="fa fa-search"></i></button>
                                    <button name="limpar" class="btn btn-cinza box-loading-ajax" title="Limpar" alt="Limpar" onclick="$('input[name=limpar]').val(1)"><i class="fa fa-trash-o"></i></button>
                                </span>
                            </div>

                            <div class="margin-top-05">
								<div class="hidden-sm hidden-xs">
		                            <label>sugerir por :</label>
		                            <div class="btn-group btn-group-xs" data-toggle="buttons">
		                                <label name="opcao-busca-palavra" class="link-branco btn btn-link <?php echo($opcaoPalavraBusca == 'tag' ? 'active' : '');?>" value="tag" <?php echo($opcaoPalavraBusca == 'tag' ? 'checked' : '');?>>
		                                    <input type="radio" name="opcao-busca-palavra" value="tag" <?php echo($opcaoPalavraBusca == 'tag' ? 'checked' : '');?>/><i name="opcao-busca-palavra-tag" class="opcao-busca-palavra fa fa<?php echo($opcaoPalavraBusca == 'tag' ? '-dot' : '');?>-circle-o"></i> Tag
		                                </label> 
		                                <label name="opcao-busca-palavra" class="link-branco btn btn-link <?php echo($opcaoPalavraBusca == 'titulo' ? 'active' : '');?>" value="titulo" <?php echo($opcaoPalavraBusca == 'titulo' ? 'checked' : '');?>>
		                                    <input type="radio" name="opcao-busca-palavra" value="titulo" <?php echo($opcaoPalavraBusca == 'titulo' ? 'checked' : '');?>/><i name="opcao-busca-palavra-titulo" class="opcao-busca-palavra fa fa<?php echo($opcaoPalavraBusca == 'titulo' ? '-dot' : '');?>-circle-o"></i> Título
		                                </label> 
		                            </div>
								</div>
                            </div>
                            
                        </div>
                    </div>
                </div>

                <input type="hidden" name="opcoes" value="<?php echo $this->session->opcoes;?>">
                <input type="hidden" name="quantidade" value="<?php echo $this->session->quantidade;?>">
                <input type="hidden" name="ordenarPor" value="<?php echo $this->session->ordenarPor;?>">
                <input type="hidden" name="favorito" value="<?php echo $this->session->favorito;?>">
                <input type="hidden" name="tipos" value="<?php echo $this->session->tipos;?>">
                <input type="hidden" name="categorias" value="">
                <input type="hidden" name="niveisensino" value="">
                <input type="hidden" name="licencas" value="">
                <input type="hidden" name="publicador" value="<?php echo $this->session->publicador;?>">
                <input type="hidden" name="visualizacao" value="<?php echo $visualizacao;?>">
                <input type="hidden" name="tag" value="<?php echo $this->session->tag;?>">
                <input type="hidden" name="limpar" value="">
                <input type="hidden" name="pagina" value="<?php echo $this->session->pagina;?>">
                
            </form>
            
        </div>
        
    </section>
    
    <?php if($this->getAction() != "editar" && $this->getAction() != "adicionar"):?>
    <section id="box-busca-opcoes" class="absolute trans90 index1 margin-top-05 height-none col-lg-12 col-md-12 col-sm-12 col-xs-12">

            <div class="height-none <?php echo (Sec_TipoDispositivo::isDesktop() ? 'container' : '');?>">

                <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12" role="tablist" aria-multiselectable="false">

					<div class="<?php echo (Sec_TipoDispositivo::isDesktop() ? 'not-row' : 'row');?>">

		                <ul id="box-busca-opcoes" class="list-unstyled panel panel-group shadow-center padding-top-<?php echo (Sec_TipoDispositivo::isDesktop() ? '80' : '60');?>">

		                    <?php if(!$this->filtraBusca):?>

		                    <li class="panel transparent">
		                        <div id="busca-agrupado-cabecalho" class="panel-heading padding-all-05" role="tab">
		                            <h6 class="margin-none">
		                                <a class="collapse-busca" role="button" data-toggle="collapse" data-parent="#accordion" href="#busca-agrupado" aria-expanded="false" aria-controls="busca-agrupado"><b class="link-<?php echo $cor;?>"><i class="link-<?php echo $cor;?> fa fa-filter"></i> Filtro de busca<i class="link-<?php echo $cor;?> fa <?php echo(count($collapsed) ? 'fa-check-square-o font-size-125' : 'fa-angle-down');?> pull-right"></i></b></a>
		                            </h6> 
		                        </div>

		                        <div id="busca-agrupado" class="panel-collapse collapse" role="tabpanel" aria-labelledby="busca-agrupado-cabecalho">
		                            <ul class="panel-body padding-none">

		                    <?php endif;?>

		                                <li class="panel transparent">

		                                    <div id="busca-grupo-02-cabecalho" class="panel-heading padding-all-05" role="tab">
		                                        <h6 class="margin-none">
		                                            <a class="collapse-busca <?php echo(!count($arrTipos) ? 'collapsed' : '');?>" role="button" data-toggle="collapse" data-parent="#accordion" href="#busca-grupo-02" aria-expanded="<?php echo(count($arrTipos) ? 'true' : 'false');?>" aria-controls="busca-grupo-02"><b class="link-cinza-escuro"><i class="link-<?php echo $cor;?> fa fa-dot-circle-o"></i> Tipos de Mídias<i class="link-<?php echo $cor;?> fa fa-angle-down pull-right"></i></b></a>
		                                        </h6> 
		                                    </div>

		                                    <div id="busca-grupo-02" class="panel-collapse collapse <?php echo(count($arrTipos) ? 'in' : '');?>" role="tabpanel" aria-labelledby="busca-grupo-02-cabecalho">
		                                        <div class="panel-body padding-all-05">
		                                            <ul class="list-unstyled margin-none padding-left-10">
		                                                <?php foreach($tipoConteudo as $conteudo):?>
		                                                    <li class="border-bottom padding-top-02 padding-bottom-02">
		                                                        <img name="tipo-conteudo" class="menu-<?php echo $cor;?> rounded margin-right-05" src="/assets/img/icones/<?php echo $conteudo->getIconeTipo();?>.png" width="24" height="24" tipo-conteudo="<?php echo $conteudo->getId();?>"/>
		                                                        <label id="tipoconteudo<?php echo $conteudo->getId()?>" class="inline opcao-item cursor-pointer fa <?php echo(strlen(array_search($conteudo->getId(), $arrTipos)) ? "fa-check-square-o link-preto" : "fa-square-o normal");?>">
		                                                            <input name="tipo-conteudo" type="checkbox" class="margin-right-05 desativado" id="tipoconteudo<?php echo $conteudo->getId();?>" value="<?php echo $conteudo->getId();?>" <?php echo(strlen(array_search($conteudo->getId(), $arrTipos)) ? "checked" : "");?>> <h6 class="margin-none"><?php echo $conteudo->getNome();?></h6>
		                                                        </label>
		                                                    </li>
		                                                <?php endforeach;?>
		                                            </ul>
		                                        </div>
		                                    </div>

		                                </li>

		                                <li class="panel transparent">
		                                    
		                                    <?php foreach($niveisEnsino as $nivelEnsino):?>

		                                        <?php if($nivelEnsino->getId() == 5):?>

		                                            <?php $componentesRelacionados = $nivelEnsino->selectComponentesCurriculares();?>

		                                            <?php if(count($componentesRelacionados)>0):?>
		                                                <div id="busca-grupo-01-<?php echo $nivelEnsino->getId()?>-cabecalho" class="panel-heading padding-all-05" role="tab">
		                                                    <h6 class="margin-none">
		                                                        <a class="collapse-busca <?php echo (!isset($collapsed['nivel-ensino-5']) ? 'collapsed' : '');?>" role="button" data-toggle="collapse" data-parent="#accordion" href="#busca-grupo-01-<?php echo $nivelEnsino->getId();?>" aria-expanded="<?php echo (isset($collapsed['nivel-ensino-5']) ? 'true' : 'false');?>" aria-controls="busca-grupo-01-<?php echo $nivelEnsino->getId();?>"><b class="link-cinza-escuro"><i class="link-<?php echo $cor;?> fa fa-graduation-cap"></i> Disciplinas do Ensino Médio<i class="link-<?php echo $cor;?> fa fa-angle-down pull-right"></i></b></a>
		                                                    </h6> 
		                                                </div>

		                                                <div id="busca-grupo-01-<?php echo $nivelEnsino->getId()?>" class="panel-collapse collapse <?php echo (isset($collapsed['nivel-ensino-5']) ? 'in' : '');?>" role="tabpanel" aria-labelledby="busca-grupo-01-<?php echo $nivelEnsino->getId()?>-cabecalho">
		                                                    <div class="panel-body padding-all-05">
		                                                        <ul class="list-unstyled margin-none padding-left-10">
		                                                            <?php foreach($componentesRelacionados as $disciplina):?>
		                                                                <li class="border-bottom padding-top-02">
		                                                                    <label  id="componente<?php echo $disciplina->getId()?>" class="inline opcao-item cursor-pointer fa <?php echo(strlen(array_search($disciplina->getId(), $arrComponentes)) ? "fa-check-square-o link-preto" : "fa-square-o normal");?>">
		                                                                        <input name="opcao-item" type="checkbox" class="margin-right-05 desativado" id="componente<?php echo $disciplina->getId();?>" value="<?php echo $disciplina->getId();?>" categoria="0" nivel-ensino="<?php echo $nivelEnsino->getId();?>" <?php echo(strlen(array_search($disciplina->getId(), $arrComponentes)) ? "checked" : "");?>> <h6 class="margin-none"><?php echo $disciplina->getNome();?></h6>
		                                                                    </label>
		                                                                </li>
		                                                            <?php endforeach;?>
		                                                        </ul>
		                                                    </div>
		                                                </div>
		                                                    
		                                            <?php endif;?>
		                                                    
		                                        <?php endif;?>
		                                                    
		                                    <?php endforeach;?>

		                                </li>
		                                
		                                <?php $arrCategoria = array(array(1,'book'),array(3,'venus-mars'),array(2,'paint-brush'),array(6,'users'),array(5,'th-list'),array(4,'child'));?>

		                                <?php for($i=0;$i<6;$i++):?>

		                                    <?php foreach($categoriasComponentes as $categoriaComponente):?>
		                                
		                                        <?php if($categoriaComponente->getComponentesCurriculares()):?>

		                                            <?php if($categoriaComponente->getId() == $arrCategoria[$i][0]):?>
		                                                <li class="panel transparent">

		                                                    <div id="busca-grupo-03-<?php echo $categoriaComponente->getId();?>-cabecalho" class="panel-heading padding-all-05" role="tab">
		                                                        <h6 class="margin-none">
		                                                            <a class="collapse-busca <?php echo(!isset($collapsed['categoria'.$categoriaComponente->getId()]) ? 'collapsed' : '');?>" role="button" data-toggle="collapse" data-parent="#accordion" href="#busca-grupo-03-<?php echo $categoriaComponente->getId();?>" aria-expanded="<?php echo(isset($collapsed['categoria'.$categoriaComponente->getId()]) ? 'true' : 'false');?>" aria-controls="busca-grupo-03-<?php echo $categoriaComponente->getId();?>"><b class="link-cinza-escuro"><i class="link-<?php echo $cor;?> fa fa-<?php echo $arrCategoria[$i][1];?>"></i> <?php echo $categoriaComponente->getNome();?><i class="link-<?php echo $cor;?> fa fa-angle-down pull-right"></i></b></a>
		                                                        </h6> 
		                                                    </div>

		                                                    <div id="busca-grupo-03-<?php echo $categoriaComponente->getId();?>" class="panel-collapse collapse <?php echo(isset($collapsed['categoria'.$categoriaComponente->getId()]) ? 'in' : '');?>" role="tabpanel" aria-labelledby="busca-grupo-03-<?php echo $categoriaComponente->getId();?>-cabecalho">
		                                                        <div class="panel-body padding-all-05" style="overflow-y: auto; max-height: 220px;">
		                                                            <ul class="list-unstyled margin-none padding-left-10">
		                                                                <?php foreach($categoriaComponente->getComponentesCurriculares() as $componente):?>
		                                                                    <li class="border-bottom">
		                                                                        <label id="componente<?php echo $componente->getId()?>" class="inline opcao-item cursor-pointer fa <?php echo(strlen(array_search($componente->getId(), $arrComponentes)) ? "fa-check-square-o link-preto" : "fa-square-o normal");?>">
		                                                                            <input name="opcao-item" type="checkbox" class="margin-right-05 desativado" id="componente<?php echo $componente->getId();?>" value="<?php echo $componente->getId();?>" categoria="<?php echo $categoriaComponente->getId()?>" nivel-ensino="0" <?php echo(strlen(array_search($componente->getId(), $arrComponentes)) ? "checked" : "");?>> <h6 class="margin-none"><?php echo $componente->getNome();?></h6>
		                                                                        </label>
		                                                                    </li>
		                                                                <?php endforeach;?>
		                                                            </ul>
		                                                        </div>
		                                                    </div>

		                                                </li>
		                                            <?php endif;?>
		                                
		                                        <?php endif;?>
		                                    <?php endforeach;?>

		                                <?php endfor;?>

		                                <li class="panel transparent">

		                                    <div id="busca-grupo-04-cabecalho" class="panel-heading padding-all-05" role="tab">
		                                        <h6 class="margin-none">
		                                            <a class="collapse-busca <?php echo(!count($arrLicencas) ? 'collapsed' : '');?>" role="button" data-toggle="collapse" data-parent="#accordion" href="#busca-grupo-04" aria-expanded="<?php echo(count($arrLicencas) ? 'true' : 'false');?>" aria-controls="busca-grupo-04"><b class="link-cinza-escuro"><i class="link-<?php echo $cor;?> fa fa-gavel"></i> Licenças de Uso<i class="link-<?php echo $cor;?> fa fa-angle-down pull-right"></i></b></a>
		                                        </h6> 
		                                    </div>

		                                    <div id="busca-grupo-04" class="panel-collapse collapse <?php echo(count($arrLicencas) ? 'in' : '');?>" role="tabpanel" aria-labelledby="busca-grupo-04-cabecalho">
		                                        <div class="panel-body padding-all-05" style="overflow-y: auto; max-height: 260px;">
		                                            <ul class="list-unstyled margin-none padding-left-10">
		                                                <?php foreach($licencas as $licenca):?>
		                                                    <li class="padding-none">
		                                                        <label id="licenca<?php echo $licenca->getId()?>" class="inline opcao-item cursor-pointer fa <?php echo(strlen(array_search($licenca->getId(), $arrLicencas)) ? "fa-check-square-o link-preto" : "fa-square-o normal");?>">
		                                                            <input name="opcao-item" type="checkbox" class="licenca margin-right-05 desativado" id="licenca<?php echo $licenca->getId();?>" value="<?php echo $licenca->getId();?>" <?php echo(strlen(array_search($licenca->getId(), $arrLicencas)) ? "checked" : "");?>> <h6 class="margin-none"><?php echo $licenca->getNome();?></h6>
		                                                        </label>
		                                                    </li>
		                                                    <li class="border-bottom padding-bottom-05 text-center <?php echo (!Sec_TipoDispositivo::isDesktop() ? 'hidden': '');?>">
		                                                        <img name="opcao-item" id="licenca<?php echo $licenca->getId();?>" value="<?php echo $licenca->getId();?>" class="img-rounded menu-cinza hidden-sm hidden-xs" src="<?php echo $licenca->getImagemAssociada();?>" height="55"/>
		                                                    </li>
		                                               <?php endforeach;?>
		                                            </ul>
		                                        </div>
		                                    </div>
		                                </li>

										<?php if(Sec_TipoDispositivo::isDesktop()):?>
		                                
				                            <li class="panel transparent">

				                                <div id="busca-grupo-05-cabecalho" class="panel-heading padding-all-05" role="tab">
				                                    <h6 class="margin-none">
				                                        <a class="collapse-busca <?php echo (!isset($collapsed['busca-avançada']) ? 'collapsed' : '');?>" role="button" data-toggle="collapse" data-parent="#accordion" href="#busca-grupo-05" aria-expanded="<?php echo (isset($collapsed['busca-avançada']) ? 'true' : 'false');?>" aria-controls="busca-grupo-05"><b class="link-cinza-escuro"><i class="link-<?php echo $cor;?> fa fa-search"></i> Outras modalidades/níveis de ensino<i class="link-<?php echo $cor;?> fa fa-angle-down pull-right"></i></b></a>
				                                    </h6> 
				                                </div>

				                                <div id="busca-grupo-05" class="panel-collapse collapse <?php echo (isset($collapsed['busca-avançada']) ? 'in' : '');?>" role="tabpanel" aria-labelledby="busca-grupo-05-cabecalho">
				                                    <div class="panel-body padding-all-05" style="background-color:<?php echo $corfundoavancada;?>">
				                                        <ul class="list-unstyled margin-none padding-none">
				                                            <?php foreach($arrNivelEnsino as $nivel):?>
				                                            
				                                                <?php foreach($niveisEnsino as $nivelEnsino):?>
				                                            
				                                                    <?php if($nivelEnsino->getId() == $nivel):?>

				                                                        <?php $componentesRelacionados = $nivelEnsino->selectComponentesCurriculares();?>
				                                                        
				                                                        <?php if(count($componentesRelacionados)>0):?>
				                                                            <li>
				                                                                <div id="busca-grupo-05-<?php echo $nivelEnsino->getId()?>-cabecalho" class="panel-heading padding-all-05" role="tab">
				                                                                    <h6 class="margin-none">
				                                                                        <a class="collapse-busca <?php echo (!isset($collapsed['nivel-ensino-'.$nivelEnsino->getId()]) ? 'collapsed' : '');?>" role="button" data-toggle="collapse" data-parent="#accordion" href="#busca-grupo-05-<?php echo $nivelEnsino->getId();?>" aria-expanded="<?php echo(isset($collapsed['ninel-ensino-'.$nivelEnsino->getId()]) ? 'true' : 'false');?>" aria-controls="busca-grupo-05-<?php echo $nivelEnsino->getId()?>"><b class="link-cinza-escuro"><i class="link-<?php echo $cor;?> fa fa-caret-right"></i> <?php echo $nivelEnsino->getNome();?></b><i class="link-<?php echo $cor;?> fa fa-angle-down pull-right"></i></a>
				                                                                    </h6>
				                                                                </div>

				                                                                <div id="busca-grupo-05-<?php echo $nivelEnsino->getId()?>" class="padding-left-10 panel-collapse collapse <?php echo (isset($collapsed['nivel-ensino-'.$nivelEnsino->getId()]) ? 'in' : '');?>" role="tabpanel" aria-labelledby="busca-grupo-05-<?php echo $nivelEnsino->getId()?>-cabecalho">
				                                                                    <div class="panel-body padding-all-05" style="overflow-y: auto; max-height: 150px;">
				                                                                        <ul class="list-unstyled margin-none">
				                                                                            <?php foreach($componentesRelacionados as $disciplina):?>
				                                                                                <li class="border-bottom padding-top-02">
				                                                                                    <label  id="componente<?php echo $disciplina->getId()?>" class="inline opcao-item cursor-pointer fa <?php echo(strlen(array_search($disciplina->getId(), $arrComponentes)) ? "fa-check-square-o link-preto" : "fa-square-o normal");?>">
				                                                                                        <input name="opcao-item" type="checkbox" class="margin-right-05 desativado" id="componente<?php echo $disciplina->getId();?>" value="<?php echo $disciplina->getId();?>" categoria="0" nivel-ensino="<?php echo $nivelEnsino->getId();?>" <?php echo(strlen(array_search($disciplina->getId(), $arrComponentes)) ? "checked" : "");?>> <h6 class="margin-none"><?php echo $disciplina->getNome();?></h6>
				                                                                                    </label>
				                                                                                </li>
				                                                                            <?php endforeach;?>
				                                                                        </ul>
				                                                                    </div>
				                                                                </div>
				                                                                
				                                                            </li>
				                                                        <?php endif;?>
				                                                            
				                                                    <?php endif;?>
				                                                            
				                                                <?php endforeach;?>
				                                            <?php endforeach;?>
				                                        </ul>
				                                    </div>
				                                </div>

				                            </li>
										<?php endif;?>

		                    <?php if(!$this->filtraBusca):?>
		                            </ul>
		                        </div>
		                    </li>
		                    <?php endif;?>
		                </ul>

					</div>
                </div>
                
				<div class="col-lg-9 col-md-8 col-sm-12 col-xs-12">
		            <ul class="row list-inline list-unstyled margin-top-05 pull-<?php echo (Sec_TipoDispositivo::isDesktop() ? 'right padding-top-80' : 'left');?>">
		                
		                <li class="padding-none <?php echo ($this->getModule() == 'aew' ? 'hidden': '');?>">
		                    <div class="btn-group btn-group-sm">
		                        <button type="button" name="busca-assuntos" class="btn btn-cinza dropdown-toggle" data-toggle="dropdown" tabindex="-1" title="Busca por assuntos" alt="Busca por assuntos">
		                            <i class="fa fa-binoculars"></i> <span class="hidden-lg hidden-md">Assuntos</span><span class="hidden-sm hidden-xs">Encontre conteúdos do ensino médio por assuntos e ano/serie</span>  
		                            <span class="caret"></span>
		                            <span class="sr-only">buscar por assuntos</span>
		                        </button>

		                        <div name="busca-assuntos" class="dropdown-menu" role="menu" style="background-color:<?php echo $fundodegrade;?>">
		                            <ul class="list-unstyled padding-all-05">
		                                <?php $cor1= "preto";?>
		                                
		                                <?php foreach($niveisEnsino as $nivelEnsino):?>

		                                    <?php if($nivelEnsino->getId() == 5):?>

		                                        <?php $componentesRelacionados = $nivelEnsino->selectComponentesCurriculares();?>

		                                        <?php if(count($componentesRelacionados)>0):?>
		                                
		                                            <?php foreach($componentesRelacionados as $disciplina):?>
		                                                <?php if($disciplina->getId() != 31):?>
		                                                    <li class="disciplina disciplina<?php echo $disciplina->getId();?> padding-all-05 col-lg-4 col-md-4 col-sm-6 col-xs-6">
		                                                        <div class="row text-center">
		                                                            <a name="topico-disciplina" class="box-loading-ajax disciplina" disciplina="<?php echo $disciplina->getId();?>" href="/conteudos-digitais/disciplinas/topicos/id/<?php echo $disciplina->getId();?>">
		                                                                <figure class="trans80 shadow-center img-formato rounded menu-<?php echo $cor1;?>" style="background-image : url('/assets/img/icones_disciplinas/icone_disciplina<?php echo $disciplina->getId();?>.png')"></figure>
		                                                                <h6 class="margin-all-02 menu-<?php echo $cor1;?>"><b><?php echo $disciplina->getNome().($disciplina->getId() == 38 ? " e Literatura" : "");?></b></h6>
		                                                            </a>
		                                                        </div>
		                                                    </li>
		                                                    <?php $cor1 = ($cor1 == "preto" ? "cinza" : "preto");?>
		                                                <?php endif;?>

		                                            <?php endforeach;?>

		                                        <?php endif;?>

		                                    <?php endif;?>

		                                <?php endforeach;?>
		                                                    
		                            </ul>
		                        </div>
		                    </div>
		                </li>
		                
		                <li class="padding-none hidden-sm hidden-xs">

		                    <div class="btn-group btn-group-sm">

		                        <button type="button" name="copiar-link" class="opcao-ulrcurta btn btn-cinza dropdown-toggle" data-toggle="dropdown" tabindex="-1" title="copiar link" alt="copiar link" data-urlcurta="/conteudos-digitais/conteudos/urlcurta">
		                            <i class="fa fa-copy"></i>
		                            <span class="caret"></span>
		                            <span class="sr-only">URL curta para copiar</span>
		                        </button>

		                        <ul name="copiar-link" class="dropdown-menu padding-all-05" role="menu">
		                            <li><input name="copiar-link" class="form-control" type="text" onfocus="this.select()" onmouseover="this.focus()" value="" readonly></li>
		                        </ul>

		                        <?php if($this->usuarioLogado):?>
		                            <button type="button" name="favorito" title="Meus favoritos" class="btn btn-cinza opcao-favorito <?php echo ($this->session->favorito ? 'active' : '');?>">
		                                <b><i class="fa fa-heart"></i></b>
		                            </button>
		                        <?php endif;?>

		                        <?php if(isset($this->href['relatorio_usuario'])):?>
		                            <button type="button" name="relatorio-pdf" class="btn btn-danger" onclick="location.href='<?php echo $this->href['relatorio_usuario'];?>'">
	                                    <b><i class="link-branco fa fa-file-pdf-o"></i></b>
		                            </button>
		                        <?php endif;?>

		                    </div>

		                </li>

		                <li class="padding-none <?php echo ($this->getModule() == 'aew' && !Sec_TipoDispositivo::isDesktop() ? 'hidden': '');?>">
		                    <div class="btn-group btn-group-sm">

		                        <button type="button" name="ordenarPor" class="btn btn-cinza dropdown-toggle" data-toggle="dropdown" tabindex="-1">
		                            <i class="fa fa-sort-alpha-asc"></i>
		                            <span class="hidden-xs">Ordenado por <label name="ordenarPor" class="link-<?php echo $cor;?> margin-none"><?php echo strtolower($ordenarPor[$this->session->ordenarPor]);?></label></span>
		                            <span class="caret"></span>
		                            <span class="sr-only">Menu Ordenar</span>
		                        </button>

		                        <ul name="ordenarPor" class="ordenarpor dropdown-menu" role="menu">
		                            <?php foreach($ordenarPor as $key=>$value):?>
		                                <li<?php echo ($this->session->ordenarPor == $key ? " class='active'" : "");?>><a class="fa <?php echo ($this->session->ordenarPor == $key ? "fa-check-square-o" : "fa-square-o");?>" value="<?php echo $key;?>"> <?php echo $value;?></a></li>
		                            <?php endforeach;?>
		                        </ul>
		                    </div>
		                </li>

		                <li class="padding-none hidden-sm hidden-xs">
		                    <div class="btn-group btn-group-sm">
		                        <button type="button" name="quantidade" class="btn btn-cinza dropdown-toggle" data-toggle="dropdown" tabindex="-1" title="conteúdos por página" alt="conteúdos por página">
		                            <i class="fa fa-list"></i> 
		                            <label name="quantidade" class="link-<?php echo $cor;?> margin-none"><?php echo $this->session->quantidade;?></label>
		                            <span class="caret"></span>
		                            <span class="sr-only">Menu conteúdos por página</span>
		                        </button>

		                        <ul name="quantidade" class="quantidade dropdown-menu" role="menu">
		                            <?php for($qtde = 15; $qtde <= 60; $qtde += 15):?>
		                                <li<?php echo ($this->session->quantidade == $qtde ? " class='active'" : "");?>><a class="fa <?php echo ($this->session->quantidade == $qtde ? "fa-check-square-o" : "fa-square-o");?>" value="<?php echo $qtde;?>"> <?php echo $qtde;?></a></li>
		                            <?php endfor;?>
		                        </ul>
		                    </div>
		                </li>

		                <li class="padding-none <?php echo ($this->getModule() == 'aew' && !Sec_TipoDispositivo::isDesktop() ? 'hidden': '');?>">
		                    <div class="btn-group btn-group-sm">
		                        <button type="button" name="visualizarPor" class="btn btn-cinza <?php echo ($visualizacao == 'column' ? 'active' : '');?>" view="column">
		                            <i class="fa fa-th-large"></i>
		                        </button>
		                        <button type="button" name="visualizarPor" class="btn btn-cinza <?php echo ($visualizacao == 'list' ? 'active' : '');?>" view="list">
		                            <i class="fa fa-bars"></i>
		                        </button>
		                    </div>
		                </li>

		            </ul>

				</div>

            </div>
            
        </section>
    
    <?php endif;?>
    
</section>
