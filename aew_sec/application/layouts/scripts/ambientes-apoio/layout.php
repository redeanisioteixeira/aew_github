<?php echo $this->doctype();?>
<html lang="pt-BR">
    <?php echo $this->render('geral/_head.php');?>
    <body data-spy="scroll" <?php echo $this->corfundo;?>>
        <?php echo $this->render('geral/_main.php'); ?>
        <?php echo $this->placeholder('secBar'); ?>
        <?php echo $this->placeholder('loginBar'); ?>

        <section class="container hidden-sm hidden-xs">
            <div class="pull-right" style="margin-top:-15px">
                <?php echo $this->ShowShareThis();?>
            </div>
        </section>

        <main class="container-main">
            
            <?php echo $this->render('_componentes/_busca.php');?>
            
            <section id="inicio" class="container">
                <header class="page-header">
                    <?php echo $this->ShowBreadCrumb($this->pageTitle, $this->paginaPai);?>
                </header>

                <?php if($this->pageTitle != ""):?>
                    <header>
                        <h3 class="headline" <?php echo $this->corfonte;?>><b><?php echo $this->pageTitle;?></b></h3>
                    </header>
                <?php endif;?>

                <?php //echo $this->placeholder('filtroBusca');?>

                <div class="col-lg-<?php echo ($this->sidebar == true ? '8' : '12');?>">

                    <div class="row">
                        <?php echo $this->render('geral/_mensagens.php');?>
                        <?php echo $this->layout()->content;?>
                    </div>

                </div>

                <?php if($this->sidebar == true):?>
                    <aside class="col-lg-4">
                        <div class="row">
                            <?php echo $this->render('conteudos-digitais/sidebar-left.php');?>
                        </div>					
                    </aside>
                <?php endif;?>
                
            </section>
        </main>
        
        <!-- Renderiza footer -->
        <?php echo $this->placeholder('footer'); ?>
        
        <!--- Subir ao Topo --->
        <a id="back-to-top" href="#" class="btn btn-default back-to-top" role="button" data-placement="left">
            <i class="fa fa-chevron-up fa-3x"></i>
        </a>
        
        <!-- Renderiza footer -->	 
        <?php echo $this->placeholder('modal') ?>
        
        <!--  renderização da barra do Chat -->
        <?php echo $this->placeholder('barraChat');?>
        
        <!--- Js --->
	<?php echo $this->headScript();?>  
	<?php echo $this->jQuery();?>
        <?php echo $this->GoogleAnalytics();?>
        <?php echo $this->inlineScript();?>
        
    </body>
</html>

