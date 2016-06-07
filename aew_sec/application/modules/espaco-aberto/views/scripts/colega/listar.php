<?php if($this->usuarioLogado->isDonoPerfil($this->usuarioPerfil) || $this->usuarioLogado->isSuperAdmin()):?>

    <?php if (count($this->colegasPendentes)):?>

        <section class="box-solicitacoes-pendentes col-lg-12">
            <!-- Colegas Pendentes -->
            <header>
                <h4 class="headline-ea headline-ea-vermelho link-vermelho"><b><i class="fa fa-thumbs-up"></i> Solicitações pendentes</b></h4>
            </header>
            
            <div class="box box-verde">
                <div class="row">
                    <ul class="list-unstyled itens-isotope">
                       <?php 
                           $this->colegas = $this->colegasPendentes;
                           echo $this->render('colega/lista-colegas.php');       
                       ?>
                    </ul>
                </div>
            </div>
            
        </section>

    <?php endif;?>

<?php endif;?>

<?php if(count($this->meusColegas)):?>

    <!-- Meus Colegas -->
    <section class="box-meus-colegas col-lg-12">
        
        <header>
            <h4 class="headline-ea link-verde"><b><i class="fa fa-users"></i> Meus Colegas</h4>
            <span class="margin-left-20 box-badge">
                <span class="fa fa-users badge badge-bottom"> <?php echo $this->totalColegas;?></span>
            </span>
        </header>

        <div class="box">
                <!-- Filtro colegas -->
            <div class="input-group">
                <span class="input-group-addon menu-verde">
                    <i id="iconUsuario" class="fa fa-search"></i>
                </span>
                <input type-action='html-action'name="nomeusuario" type="search" class="form-control search-input" idloadcontainer="lista_colegas" rel="/espaco-aberto/colega/lista-colegas/usuario/<?php echo $this->usuarioPerfil->getId();?>" placeholder="Filtrar colegas..." icon="#iconUsuario">
            </div>

            <ul id='lista_colegas' class="itens-isotope margin-top-20 list-unstyled load-scroll" type-action='append-action' rel="/espaco-aberto/colega/lista-colegas/usuario/<?php echo $this->usuarioPerfil->getId();?>">
                <?php $this->colegas = $this->meusColegas;?>
                <?php echo $this->render('colega/lista-colegas.php');?>
            </ul>
        </div>
    </section>
    
<?php endif;?>
