<?php echo $this->doctype();?>
<html lang="pt-BR">
    <?php echo $this->render('geral/_head.php');?>
    <body data-spy="scroll" class="home" <?php echo $this->corfundo;?>>
        
        <?php echo $this->showAvisoIe6();?>
        
        <?php echo $this->render('geral/_main.php'); ?>
        
        <?php echo $this->placeholder('secBar');?>
        <?php echo $this->placeholder('loginBar');?>

        <?php if(!$this->isContainer):?>
            <main id="inicio" class="container-main">
                <?php echo $this->layout()->content;?>
            </main>
        <?php else:?>
            <?php echo $this->layout()->content;?>
        <?php endif;?>
        
        <!--  renderização do rodapé -->
        <?php echo $this->placeholder('footer');?>
        
        <!--  renderização da  barra do Chat -->
        <?php echo $this->placeholder('barraChat');?>

        <!-- Subir ao Topo -->
        <a id="back-to-top" href="#" class="btn btn-default back-to-top" role="button" data-placement="left">
            <i class="fa fa-chevron-up fa-3x"></i>
        </a>	 

        <!-- Modal -->
        <?php echo $this->placeholder('modal');?>
        
        <!-- JS -->
        <?php echo $this->headScript();?>
        <?php echo $this->jQuery();?>
        <?php echo $this->GoogleAnalytics();?>
        <?php echo $this->inlineScript();?>
        
    </body>
</html>


