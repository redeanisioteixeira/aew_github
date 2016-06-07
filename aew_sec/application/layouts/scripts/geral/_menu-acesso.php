<?php $this->placeholder('barraMenuAcesso')->captureStart();?>

    <li id="area-usuario" class="dropdown">
    
        <a class="dropdown-toggle<?php echo ($this->menuAcesso == false ? "" : " menu-acesso");?>" data-toggle="dropdown">
            <?php if($this->menuAcesso == false):?>
                <i class="fa fa-user"></i>
            <?php else:?>
                <figure class="img-circle">
                    <?php echo $this->usuarioLogado->getFotoPerfil()->getFotoCache(Aew_Model_Bo_Foto::$FOTO_CACHE_30X30,false,30);?>
                </figure>
            <?php endif;?>
        </a>
    
        <ul class="dropdown-menu">
            <?php if($this->menuAcesso == false):?>
                <li><?php echo $this->form_login;?></li>
                <li class="divider hidden-sm hidden-xs"></li>
                <li>
                    <a href="<?php echo $this->urlCadastro;?>">
                    <i class="fa fa-list-alt fa-lg"></i> Ainda não sou cadastrado</a>
                </li>
                <li>
                    <a href="<?php echo $this->urlRecuperarSenha;?>">
                    <i class="fa fa-medkit fa-lg"></i> Esqueci minha senha</a>
                </li>
            <?php else:?>
                <?php foreach($this->menuAcesso as $key=>$value):?>
                    <?php if($key == "espaco-aberto"):?>
                        <?php if($value[0] != ""):?>
                            <li class="padding-left-10">
                                <h4><b class="uppercase"><i class="fa fa-ellipsis-v"></i> <?php echo $value[0];?></b></h4>
                            </li>
                        <?php endif;?>
                            
                        <?php if($value[1]):?>
                            <?php foreach($value[1] as $subMenu=>$link):?>
                                <li><a class="padding-left-10" onclick="location.href='<?php echo "/$key/$link";?>'"><?php echo $subMenu;?></a></li>
                            <?php endforeach;?>
                         
                            <li class="divider hidden-sm hidden-xs"></li>
                        <?php endif;?>
                            
                    <?php endif;?>
                <?php endforeach;?>

                <li><a target="_top" onclick="location.href='<?php echo $this->urlSair?>'"><i class="fa fa-sign-out"></i> Sair</a></li>

            <?php endif;?>
        </ul>
    </li>

    <?php if($this->menuAcesso == true && count($this->menuAcesso)>1):?>
        <li class="dropdown">
            
            <a class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-cog"></i>
            </a>
            
            <ul class="dropdown-menu">
                
                <?php foreach($this->menuAcesso as $key=>$value):?>
                    <?php if($key != "espaco-aberto"):?>
                
                        <?php if($value[0] != ""):?>
                            <li class="padding-left-10"><b class="uppercase"><i class="fa fa-ellipsis-v"></i> <?php echo $value[0];?></b></li>
                        <?php endif;?>

                        <?php foreach($value[1] as $subMenu=>$link):?>
                            <li><a class="padding-left-10" onclick="location.href='<?php echo "/$key/$link";?>'"><?php echo $subMenu;?></a></li>
                        <?php endforeach;?>
                            
                        <li class="divider hidden-sm hidden-xs"></li>
                    <?php endif;?>
                <?php endforeach;?>
                        
            </ul> 
        </li>
    <?php endif;?>
<?php $this->placeholder('barraMenuAcesso')->captureEnd();?>