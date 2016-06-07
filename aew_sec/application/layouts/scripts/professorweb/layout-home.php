<!DOCTYPE html>
<html lang="pt-BR">
    <?php echo $this->render('geral/_head.php');?>
    <body data-spy="scroll" <?php echo $this->corfundo;?>>
        <?php echo $this->showAvisoIe6();?>
        <?php echo $this->render('geral/_main.php'); ?>
        <?php echo $this->placeholder('secBar'); ?>
        <?php echo $this->placeholder('loginBar'); ?>

        <section class="container hidden-sm hidden-xs">
            <div class="pull-right" style="margin-top:-15px">
                <?php echo $this->ShowShareThis();?>
            </div>
        </section>
        
        <!---- JS --->            
        <?php echo $this->headScript();?>
        <?php echo $this->jQuery();?>
    </body>
</html>
<?php //echo $this->GoogleAnalytics();?>
