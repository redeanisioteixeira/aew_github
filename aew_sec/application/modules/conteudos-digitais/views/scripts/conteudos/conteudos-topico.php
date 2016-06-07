<?php if(!count($this->conteudos)):?>
    <?php echo $this->render('_componentes/_nao-encontrado.php');?>
<?php endif;?>  

<?php if(!$this->isAjax):?>
    <div id="lista_conteudos" class="panel panel-default">
<?php endif;?>

        <?php if($this->conteudos):?>
            <?php echo $this->paginationControl($this->conteudos,"Sliding","_componentes/_pagination_ajax.php", array("divContent" => "#lista_conteudos", "topicos" => 1));?>
        <?php endif;?>

        <?php foreach($this->conteudos as $conteudo):?>

            <?php
                $this->corfundo = "bgcolor = 'menu-".($conteudo->getFlSiteTematico() == true ? "vermelho" : "azul")."'";
                $this->corfonte = "fcolor  = 'menu-".($conteudo->getFlSiteTematico() == true ? "vermelho" : "azul")."'";
                $this->canal    = null;
	
                if($conteudo->getConteudoDigitalCategoria()->getId()):
                    $this->corfundo = "bgcolor = 'menu-marron'";
                    $this->corfonte = "fcolor  = 'menu-marron'";
                    $this->canal    = "tv-anisio-teixeira";
                endif; 
            ?>

        <?php endforeach;?>

<?php if(!$this->isAjax):?>
    </div>
<?php endif;?>