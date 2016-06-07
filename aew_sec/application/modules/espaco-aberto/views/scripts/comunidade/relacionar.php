<?php
    if(!count($this->comunidadesRelacionar)):
        return;
    endif;
?>

<div class="col-lg-12">
    <div class="row">
        <header>
            <h4 class="headline-ea link-verde"><b><i class="fa fa-comments-o"></i> Comunidades existentes no Espaço Aberto</b></h4>
        </header>
        
        <div class="box">

            <div class="input-group">
                <span class="input-group-addon menu-verde">
                    <i id="iconComunidade" class="fa fa-search"></i>
                </span>
                <input type-action='html-action'name="nomecomunidade" type="search" class="form-control search-input" idloadcontainer="lista-comunidade-relacionar" rel="/espaco-aberto/comunidade/lista-relacionar" placeholder="Filtrar comunidades..." icon="#iconComunidade" value="<?php echo $this->filtro;?>">
            </div>

            <div id="lista-comunidade-relacionar" class="margin-top-10">
                <?php echo $this->render('comunidade/lista-relacionar.php');?>
            </div>
        </div>
    </div>
</div>