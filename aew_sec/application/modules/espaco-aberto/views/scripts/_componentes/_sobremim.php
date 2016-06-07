<article class="sobre-mim clearfix"">
    
    <?php if($this->tipoPagina == 1):?>
        <?php if($this->usuarioPerfil->getSobreMim()->getSobreMim()):?>
            <?php if($this->getController() != 'perfil'):?>
                    <p class="clearfix">
                        <?php echo $this->RetiraTagsHTML($this->usuarioPerfil->getSobreMim()->getSobreMim());?>
                    </p>

                    <?php if($this->usuarioPerfil->getSobreMim()->getCidadeNatal()):?>
                        <h5 class="link-cinza-escuro"><i class="fa fa-map-marker"></i> Cidade natal de <b><?php echo $this->usuarioPerfil->getSobreMim()->getCidadeNatal();?></b></h5>
                    <?php endif;?>
                        
                <?php else:?>
                    <h4 class="link-verde headline-ea"><b><i class="fa fa-<?php echo ($this->usuarioPerfil->getSexo() == 'm' ? 'male' : 'female');?>"></i> Sobre Mim</b></h4>
                    <div class="box">        
                        <p class="clearfix">
                            <?php echo $this->RetiraTagsHTML($this->usuarioPerfil->getSobreMim()->getSobreMim());?>
                        </p>

                        <?php if($this->usuarioPerfil->getSobreMim()->getCidadeNatal()):?>
                            <h5 class="link-cinza-escuro"><i class="fa fa-map-marker"></i> Cidade natal de <b><?php echo $this->usuarioPerfil->getSobreMim()->getCidadeNatal();?></b></h5>
                        <?php endif;?>

                    </div>
                <?php endif;?>
        <?php endif;?>
    <?php endif;?>

    <?php if($this->tipoPagina == 2):?>
        <?php if($this->usuarioPerfil->getDescricao()):?>
            <?php if($this->getController() != 'comunidade' || $this->getAction() == 'editar'):?>
                <p class="clearfix">
                    <?php echo $this->RetiraTagsHTML($this->usuarioPerfil->getDescricao());?>
                </p>
            <?php else:?>
                <h4 class="link-verde headline-ea"><i class="fa fa-comments-o"></i> Sobre a comunidade <b><?php echo $this->usuarioPerfil->getNome();?></b></h4>
                <div class="box"> 
                    <p class="clearfix">
                        <?php echo $this->usuarioPerfil->getDescricao();?>
                    </p>
                </div>
            <?php endif;?>                
        <?php endif;?>
    <?php endif;?>
            
</article>