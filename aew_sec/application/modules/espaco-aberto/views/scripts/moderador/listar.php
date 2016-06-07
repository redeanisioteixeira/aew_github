<?php if(count($this->moderadores)):?>
    <!--- Moderadores --->
    <div class="box-moderadores">
        <h4 class="headline-ea link-verde"><b><i class="fa fa-gavel"></i> Moderadores da comunidade</b></h4>

        <ul class="list-unstyled itens-isotope margin-top-10">
            <?php
                foreach($this->moderadores as $moderador):
                    $this->colega = $moderador;
                    $this->removerModerador = true;
                    echo $this->render('colega/colega.php');
                endforeach;
                $this->removerModerador = false;
            ?>
        </ul>
    </div>
<?php endif;?>
    
<?php if(count($this->membros)):?>
    <!--- Membros ativos --->
    <div class="box-membros">
        <h4 class="headline-ea link-verde"><b><i class="fa fa-users"></i> Membros ativos da comunidade</b></h4>
        <?php
            $this->adicionarModerador = true;
            $this->adicionarBloquear = true;
            echo $this->render('membro/listar.php');
        ?>
    </div>
<?php endif;?>
    
<?php if(count($this->bloqueados)):?>
    <!-- Bloqueados -->
    <div class="box-membros">
        <h4 class="headline-ea link-verde"><b><i class="fa fa-lock"></i> Membros bloqueados</b></h4>
        <?php
            $this->adicionarDesbloquear = true;
            $this->membros = $this->bloqueados;
            echo $this->render('membro/listar.php');
        ?>
    </div>
<?php endif;?>
    
<?php if(count($this->pendentes)):?>    
    <!-- Pendentes -->
    <div class="box-membros">
        <h4 class="headline-ea headline-ea-vermelho link-vermelho"><b><i class="fa fa-thumbs-o-up"></i> Membros pendentes por aprovar</b></h4>
        <?php
            $this->aprovarPendentes = true;
            $this->membros = $this->pendentes;
            echo $this->render('membro/listar.php');
        ?>
    </div>
<?php endif;?>