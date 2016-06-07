<?php if(!count($this->conteudos)):?>
    <?php echo $this->render('_componentes/_nao-encontrado.php'); ?>
<?php endif;?>
  
<?php if(!$this->isAjax):?>
    <div id="lista_conteudos" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
<?php endif;?>
        
    <div class="row">

        <?php if($this->conteudos):?>
            <?php echo $this->paginationControl($this->conteudos,"Sliding","_componentes/_pagination_ajax.php", array("divContent" => "#lista_conteudos"));?>
        <?php endif;?>

        <div id="itens" class="itens-isotope<?php echo ($this->topicos || $this->visualizacao == "list" ? '-not': '');?>">
            <?php
                foreach($this->conteudos as $conteudo):
                
                    if($conteudo->getConteudoDigitalCategoria()->getCanal()->getId() == 1):
                        $this->panelConteudo = 'warning';

                        $cor = "menu-marron";
                        $fundodegrade = "#F09D00";
                        
                        $this->menu  = $cor;
                        $this->fundo = "bgcolor = '$cor'";
                        $this->fonte = "fcolor  = '$cor'";
                        $this->canal = "tv-anisio-teixeira"; 
                        $this->fundolateral = "bgcolor = '$cor'";
                    else:
                        $this->panelConteudo = ($conteudo->getFlSiteTematico() == true ? "danger" : "info");

                        $cor = "menu-".($conteudo->getFlSiteTematico() ? "vermelho" : "azul");
                        
                        $fundodegrade = ($conteudo->getFlSiteTematico() ? "#E74C3C" : "#2AABD2");
                        
                        $this->menu  = $cor;
                        $this->fundo = "bgcolor = '$cor'";
                        $this->fonte = "fcolor  = '$cor'";
                        $this->canal = ($conteudo->getFlSiteTematico() ? "sites-tematicos" : "conteudos-digitais");
                    endif;

                    $this->fundodegrade = $this->ConverterHexRgba($fundodegrade, 0.14);
                    
                    $conteudo->selectComentarios();
                    $this->conteudo = $conteudo;
                    
                    if($this->topicos || $this->visualizacao == "list"):
                        echo $this->render('conteudo/conteudo-topico.php');
                    else:
                        echo $this->render('conteudo/conteudo-digital.php');
                    endif;
                    
                endforeach;
            ?>
        </div>

        <?php if($this->conteudos):?>
            <?php echo $this->paginationControl($this->conteudos,"Sliding","_componentes/_pagination_ajax.php", array("divContent" => "#lista_conteudos"));?>
        <?php endif;?>

    </div>
        
<?php if(!$this->isAjax):?>
    </div>
<?php endif;?>
