<?php $allowedModerador = $this->isAllowed($this->usuarioLogado,'espaco-aberto', 'administrar') || $this->perfilModerador;?>
<div class="col-lg-12">
    <div class="box">

        <?php if($allowedModerador):?>

            <div class="text-right">

                <div class="btn-group btn-group-sm">
                    <?php if($this->usuarioLogado->getUrlCriarComunidade()):?>
                        <a class="btn btn-success" href="<?php echo  $this->usuarioLogado->getUrlCriarComunidade();?>" data-toggle="tooltip" data-placement="top" title="Criar comunidade"  data-original-title="Criar comunidade">
                            <i class="fa fa-plus"></i> criar comunidade
                        </a>
                    <?php endif;?>

                    <?php if ($this->ComunidadesPendentes):?>
                        <a class="btn btn-primary" href="<?php echo $this->usuarioPerfil->getUrlComunidadesPendentes();?>" data-toggle="tooltip" data-placement="top" title="Comunidades pendentes" data-original-title="Comunidades Pendentes">
                             <i class="fa fa-hand-stop-o"></i> pendentes por aprovar
                        </a>
                    <?php endif;?>
                </div>
            </div>
            <hr>
        <?php endif;?>

        <?php if(count($this->solicitacoes) && $this->usuarioLogado->isDonoPerfil($this->usuarioPerfil)):?>
            <h5 class="inline link-branco well well-sm">
                Exibir as <a href="<?php echo  $this->usuarioLogado->getUrlComunidadesSugeridas() ?>" title="Exibir comunidades sugeridas"><b class="link-verde"><i class="fa fa-comments-o"></i> comunidades sugeridas</b></a> por seus colegas<i class="fa fa-question"></i>
            </h5>
            <hr>
        <?php endif;?>

        <?php if(count($this->comunidades)):?>
                <!-- Lista de Comunidades -->
                <div class="input-group margin-bottom-10">
                    <span class="input-group-addon menu-verde">
                        <i id="iconComunidade" class="fa fa-search"></i>
                    </span>
                    <input type-action='html-action' name="nomecomunidade" type="search" class="form-control search-input" idloadcontainer="lista-comunidades" rel="/espaco-aberto/comunidade/lista-comunidades/usuario/<?php echo $this->usuarioPerfil->getId()?>" placeholder="Filtrar comunidades" icon="#iconComunidade">
                </div>

                <ul id="lista-comunidades" class="list-unstyled load-scroll" type-action='append-action' rel="/espaco-aberto/comunidade/lista-comunidades/usuario/<?php echo $this->usuarioPerfil->getId();?>">
                    <?php echo $this->render('comunidade/lista-comunidades.php');?>
                </ul>

        <?php endif;?>

    </div>
</div>