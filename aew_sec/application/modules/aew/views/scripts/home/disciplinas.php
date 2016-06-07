<section class="<?php echo($this->isAjax == true ? '' : 'container-main container-disciplina');?>">
    <?php if($this->isAjax == true):?>
        <h3 class="disciplina menu-cinza"><b><?php echo $this->pageTitle;?></b></h3>
        <hr>
        <?php echo $this->topicos;?>
    <?php else:?>
        <div class="container">
            <div class="row-offcanvas row-offcanvas-left" style="padding: 0 !important">
                <aside class="col-lg-4 col-xs-12 col-sm-4 sidebar-offcanvas sidebar-nav" id="sidebar" role="navigation" style="padding: 0 !important;">
                    <h4 class="disciplina menu-cinza"><b>Assuntos da disciplina:</b></h4>
                    <?php echo $this->topicos;?>
                </aside>
                <div class="col-lg-8 col-xs-12 col-sm-8" style="padding: 0 0 0 10px !important">
                    <h3 class="disciplina headline menu-azul"><b><?php echo $this->pageTitle;?></b></h3>
                    <div id="itens-disciplinas" class="items-isotope"></div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif;?>
</section>
