<?php echo $this->doctype();?>
<html lang="pt-br">
    <?php echo $this->render('geral/_head.php'); ?>
    <body data-spy="scroll" <?php echo $this->corfundo;?>>
        <?php echo $this->render('geral/_main.php'); ?>
        <?php echo $this->placeholder('secBar'); ?>
	<?php echo $this->placeholder('loginBar'); ?>

        <main class="container-main">
            <section class="container">
                
                <!-- breadcrums -->
                <header class="page-header">
                    <?php echo $this->ShowBreadCrumb($this->pageTitle, $this->paginaPai);?>
                </header>
                
                <div class="row-offcanvas row-offcanvas-left">
                    <!-- sidebar -->
                    <aside id="sidebar" class="col-lg-3 col-md-3 col-sm-12 col-xs-12 sidebar-offcanvas">
                        <div class="row">
                            <?php echo $this->placeholder('sidebarLeft');?>
                        </div>
                    </aside> 
                    
                    <!-- conteúdo da view -->
                    <section class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                        <?php echo $this->render('/buscar/exibir.php');?>
                        <div class="row">
                            <?php echo $this->layout()->content;?>
                        </div>
                    </section>
                
                </div>
            </section>
        </main>
        
        <!--  renderização da  barra do Chat -->
        <?php echo $this->placeholder('barraChat');?> 
        
        <!--  renderização do Footer -->  
        <?php echo $this->placeholder('footer'); ?>
        
        <!-- ativa offcanvas menu -->
        <a id="botao-perfil" class="shadow-left visible-sm visible-xs" data-toggle="offcanvas">
            <i class="link-verde fa fa-list-alt fa-2x" title="Meu perfil"></i>
        </a>
        
        <!--- Subir ao Topo --->
        <a id="back-to-top" href="#" class="btn btn-default back-to-top" role="button" data-placement="left">
            <i class="fa fa-chevron-up fa-3x"></i>
        </a>

	<?php echo $this->placeholder('modal'); ?>
        
        <!-- Js (para evitar bloqueio na renderização do html e css)-->                    
        <?php echo $this->headScript() ?>  
        <?php echo $this->jQuery() ?>  
    </body>
</html>
