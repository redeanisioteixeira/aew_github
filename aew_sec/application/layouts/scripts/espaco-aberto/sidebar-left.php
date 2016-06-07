<?php $this->placeholder('sidebarLeft')->captureStart();?>

    <?php if($this->tipoPagina == Sec_Constante::USUARIO):?>
        <!-- Renderiza links do usuario, blog, comunidade, mural, etc-->
        <?php echo $this->render('_componentes/_perfil-usuario.php');?>
    <?php endif;?>
    
    <?php if($this->tipoPagina == Sec_Constante::COMUNIDADE):?>
        <!-- Renderiza menu lateral comunidade -->
        <?php echo $this->render('_componentes/_perfil-comunidade.php');?>
    <?php endif;?>
     
<?php $this->placeholder('sidebarLeft')->captureEnd();?>  