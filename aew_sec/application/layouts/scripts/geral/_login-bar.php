<?php

echo $this->render('geral/_menu-acesso.php');
echo $this->render('geral/_mensagens.php');
echo $this->render('_componentes/_barra-superior.php');
echo $this->render('_componentes/_busca-simples.php');
 
$this->placeholder('loginBar')->captureStart();?>
    <nav id="menu-principal" class="navbar-wrapper" aria-label="menu principal">
        
        <div class="<?php echo ($this->getModule() == 'espaco-aberto' && $this->usuarioLogado ? 'full fixed' : 'container');?>">

            <div class="shadow-bottom navbar navbar-<?php echo ($this->getModule() != 'espaco-aberto' ? 'default':'inverse');?> navbar-static-top" role="navigation">

                <div class="navbar-header">

                    <!-- LOGO -->
                    <a href="/" class="navbar-brand">
                        <span class="title"><b class="hidden-md hidden-sm">Ambiente Educacional Web</b></span>
                    </a>

                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse-menu">
                        <span class="sr-only">Menu principal</span>
                        <span class="fa fa-bars" aria-hidden="true"></span>
                    </button>
                    
                </div>

                <div class="navbar-collapse collapse navbar-collapse-menu">

                    <ul class="nav navbar-nav">

                        <?php echo $this->placeholder('barraMenuAcesso');?>

                        <?php if($this->getModule() == 'espaco-aberto'):?>
                            <li class="menu-espaco-aberto padding-all-10">
                                <?php echo $this->placeholder('barraSuperior');?>
                            </li>
                        <?php endif;?>
                        
                        <li class="dropdown">
                            <a class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-th-list"></i></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a class="faleConosco" data-toggle="modal" data-target="#modalGeral" href="<?php echo $this->urlFaleConosco;?>"><i class="fa fa-phone-square"></i>Fale conosco</a></li>
                                <li><a target="_top" title="" href="<?php echo $this->urlTermoUso;?>"><i class="fa fa-book"></i>Termos e condições de uso</a></li>
                                <li><a target="_top" title="" href="<?php echo $this->urlSobre;?>"><i class="fa fa-info-circle"></i>Sobre o AEW</a></li>
                                <li class="divider hidden-sm hidden-xs"></li>
                                <li><a target="_top" title="" href="<?php echo $this->urlAjuda;?>"><i class="fa fa-question-circle"></i>Ajuda</a></li>
                            </ul>
                        </li>

                        <li class="menu-azul"><a class="menu-opcao-meio" target="_top" href="<?php echo $this->urlModuleConteudoDigital;?>">Conteúdos Digitais</a></li>
                        <li class="menu-vermelho"><a class="menu-opcao-meio" target="_top" href="<?php echo $this->urlModuleSitesTematicos;?>">Sites Temáticos</a></li>
                        <li class="menu-marron"><a class="menu-opcao-meio" target="_top" href="<?php echo $this->urlModuleTVanisioteixeira;?>">TV Anísio Teixeira</a></li>
                        <li class="menu-roxo"><a class="menu-opcao-curta" href="<?php echo $this->urlModuleProfessorWeb;?>">Professor Web</a></li>
                        <li class="menu-amarelo"><a class="menu-opcao-longa" target="_top" href="<?php echo $this->urlModuleAmbienteDeApoio;?>">Apoio a Produção e Colaboração</a></li>
                        <li class="menu-verde"><a class="menu-opcao-curta" target="_top" href="<?php echo $this->urlModuleEspacoAberto;?>">Espaço Aberto</a></li>

                    </ul>
                </div> <!-- navbar-collapse --> 
            </div> <!-- navbar -->

            <?php echo $this->placeholder('buscaSimples');?>
            <?php echo $this->placeholder('mensagens');?>

        </div> <!-- container -->

    </nav> <!-- navbar-wrapper --> 

<?php $this->placeholder('loginBar')->captureEnd(); ?>
