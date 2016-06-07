<?php echo $this->doctype();?>
<html lang="pt-BR">
    <?php echo $this->render('geral/_head.php');?>
    <body class="margin-none">
        <section class="padding-all-10">

            <div class="pull-right">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"><i class="fa fa-times-circle"></i></span><span class="sr-only">Fechar</span></button>
            </div>

            <?php if($this->pageTitle != ""):?>
                <header class="page-header">
                    <h4 class="menu-cinza headline margin-none"><b><?php echo $this->pageTitle;?></b></h4>
                </header>
            <?php endif;?>

            <?php echo $this->layout()->content;?>
            
        </section>
        
    </body>
</html>