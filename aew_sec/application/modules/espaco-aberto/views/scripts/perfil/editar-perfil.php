<h4 class="link-verde headline-ea"><b><i class="fa fa-<?php echo ($this->usuarioPerfil->getSexo() == 'm' ? 'male' : 'female');?>"></i> Informações sobre meu perfil</b></h4>
<div id="tab-informacoes-perfil" class="box padding-all-02">
    
    <ul class="nav nav-tabs nav-justified" role="tablist">
        <li role="presentation"><a href="#sobre-mim" aria-controls="sobremim" role="tab" data-toggle="tab"><h4 class="margin-none"><i class="fa fa-<?php echo ($this->usuarioPerfil->getSexo() == 'm' ? 'male' : 'female');?>"></i> Sobre mim</h4></a></li>
        <li role="presentation"><a href="#redes-sociais" aria-controls="redes-sociais" role="tab" data-toggle="tab"><h4 class="margin-none"><i class="fa fa-commenting"></i> Redes Sociais</h4></a></li>
        <li role="presentation"><a href="#alterar-senha" aria-controls="alterar-senha" role="tab" data-toggle="tab"><h4 class="margin-none"><i class="fa fa-key"></i> Alterar sua senha</h4></a></li>
    </ul>

    <div class="tab-content margin-top-10 padding-all-10">
        <!-- Dados pessoais -->
        <article id="sobre-mim" class="box-sobre-mim tab-pane fade" role="tabpanel">
            <?php echo $this->sobreMim;?>
        </article>

        <!-- Redes Sociais -->
        <article id="redes-sociais"  class="box-redes-sociais tab-pane fade" role="tabpanel">
            <?php echo $this->formMinhasRedesSociais ?>

            <ul class="list-inline adicionar-rede">
                <?php foreach ($this->redesSociais as $rede):?>
                    <li>
                        <div class="btn-group dropup">
                            <button type="button" class="btn btn-primary" title="<?php echo $rede->getUrl();?>" disabled>
                                <i class="fa fa-<?php echo $rede->getRede();?>"></i> 
                            </button>
                            <button type="button" class="btn btn-danger apagar-rede" data-id="<?php echo $rede->getId();?>" data-url="/espaco-aberto/perfil/apagar-minha-rede-social" data-toggle="tooltip" data-placement="top" title="apagar rede social">
                                <i class="fa fa-close"></i>
                            </button>
                        </div>
                    </li>
                <?php endforeach ?>
            </ul>
        </article>

        <!-- Trocar Senha -->
        <article id="alterar-senha"  class="box-alterar-senha tab-pane fade" role="tabpanel">
            <p class="text-warning">Digite e confirme a nova senha de acesso. Sugerimos alterar a su senha para uma mais adequada e de fácil memorização para você</p>
            <?php echo $this->formTrocarSenha;?>
        </article>
        
    </div>
</div>
