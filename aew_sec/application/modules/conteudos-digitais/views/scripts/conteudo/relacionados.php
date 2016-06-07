<?php $col = ($this->tags ? "col-lg-6 itens-isotope" : "col-lg-12")?>

<?php if(!count($this->relacionados)):?>
    <?php return;?>
<?php endif;?>

<div class="col-lg-12">
    <div class="row">
        <h4 class="pull-left" <?php echo $this->corfonte;?>><i class="fa <?php echo ($this->tags? "fa-hand-o-right" : "fa-list-ul");?>"></i> <b class="uppercase"><?php echo ($this->tags ? "Conteudos Digitais " : "");?>Relacionados</b></h4>
        <?php echo $this->paginationControl($this->relacionados,"Sliding","_componentes/_pagination_ajax.php", array("divContent" => "#relacionados", "divUrl" => $this->url(array("module"=> "conteudos-digitais", "controller" => "conteudo", "action" => "relacionados")), 'tags' => $this->tags));?>
    </div>
</div>

<div class="col-lg-12">
    <div class="row">
    <?php foreach($this->relacionados as $conteudo):?>

        <?php if($this->tags):?>
            <div class="col-lg-6">
        <?php endif;?>

        <?php
            if($conteudo->getConteudoDigitalCategoria()->getCanal()->getId() == 1):
                $this->panelConteudo = 'warning';

                $cor = "menu-marron";
                $this->menu  = $cor;
                $this->fundo = "bgcolor = '$cor'";
                $this->fonte = "fcolor  = '$cor'";
                $this->canal = "tv-anisio-teixeira"; 
            else:
                $this->panelConteudo = ($conteudo->getFlSiteTematico() == true ? "danger" : "info");

                $cor = "menu-".($conteudo->getFlSiteTematico() ? "vermelho" : "azul");
                $this->menu  = $cor;
                $this->fundo = "bgcolor = '$cor'";
                $this->fonte = "fcolor  = '$cor'";
                $this->canal = ($conteudo->getFlSiteTematico() ? "sites-tematicos" : "conteudos-digitais");
            endif;

            $this->eRelacionado = true;
            $this->conteudo = $conteudo;
            $this->colLargura = ($this->topicos ? 6 : 12);

            echo $this->render('conteudo/conteudo-digital.php');
        ?>

        <?php if($this->tags):?>
            </div> <!--- col-lg-6 -->
        <?php endif;?>
            
    <?php endforeach;?>
    </div>
</div> <!-- col-lg-12 -->
<?php echo $this->paginationControl($this->relacionados,"Sliding","_componentes/_pagination_ajax.php", array("divContent" => "#relacionados", "divUrl" => $this->url(array("module"=> "conteudos-digitais", "controller" => "conteudo", "action" => "relacionados", null))));?>
