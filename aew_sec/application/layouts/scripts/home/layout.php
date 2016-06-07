<?php echo $this->doctype();?>
<html lang="pt-BR">
    <?php echo $this->render('geral/_head.php');?>
    <body data-spy="scroll" <?php echo $this->corfundo;?>>
        <?php echo $this->showAvisoIe6();?>
        
        <?php echo $this->render('geral/_main.php');?>
        
        <?php echo $this->placeholder('secBar');?>
        <?php echo $this->placeholder('loginBar');?>
        
        <section class="container hidden-sm hidden-xs">
            <div class="pull-right" style="margin-top:-15px">
                <?php echo $this->ShowShareThis();?>
            </div>
        </section>
        
        <section class="container-main">
            <section class="container">
                <header class="page-header">
                    <?php echo $this->ShowBreadCrumb($this->pageTitle, $this->paginaPai);?>
                </header>
                <?php if($this->pageTitle != ""):?>
                    <header class="page-header">
                        <h3 class="menu-cinza headline"><b><?php echo $this->pageTitle;?></b></h3>
                    </header>
                <?php endif;?>

                <?php echo $this->layout()->content;?>

                <?php echo $this->ShowGoBack();?>
            </section>	
        </section>	

        <!--  renderização do rodapé -->
        <?php echo $this->placeholder('footer');?>

        <!--  renderização da  barra do Chat -->
        <?php echo $this->placeholder('barraChat');?>
        
        <!--- Subir ao Topo --->
        <a id="back-to-top" href="#" class="btn btn-default back-to-top" role="button" data-placement="left">
            <i class="fa fa-chevron-up fa-3x"></i>
        </a>	 

        <!-- Modal -->
        <?php echo $this->placeholder('modal'); ?>
                
        <!-- JS -->            
        <?php echo $this->headScript();?>
        <?php echo $this->jQuery();?>
		<?php echo $this->GoogleAnalytics();?>
		<?php echo $this->inlineScript();?>
</html>
