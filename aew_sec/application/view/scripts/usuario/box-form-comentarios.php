<div id="form-comentario">     
    <div class="conteudo-comentarios-enviar">
	<?php if(!$this->usuarioLogado): ?>
            <div id="box-login" class="login" style="display:none;">
		<div class="col-lg-12">
                    <div class="panel panel-default">
			<div class="panel-body">
                            <div class="col-lg-6" style="border-right: 1px solid #CCC;">
                                <h4 class="link-verde page-header"><b>Bem-vindo ao nosso espaço!</b></h4>
                                <p>O Espaço Aberto é uma Rede Social Educacional voltada para estudantes e professores da escolas públicas baianas, tem como objetivo potencializar a construção e a troca de conhecimentos, estimulando a socialização e a colaboração no ambiente escolar.</p>
                                <ul class="list-unstyle list-inline">
                                    <li><a class="text-muted" href="<?php echo $this->urlCadastro ;?>"><i class="fa fa-list-alt fa-lg"></i> Ainda não sou cadastrado</a></li>
                                    <li><a class="text-muted" href="<?php echo $this->urlRecuperarSenha;?>"><i class="fa fa-medkit fa-lg"></i> Esqueci minha senha</a></li>
                                </ul>
                            </div>
                            <div class="col-lg-6">
                                <?php echo $this->form_login_comentario;?>
                            </div>
			</div>
                    </div>
		</div>
            </div>
        <?php endif;?>
	<?php echo $this->formComentarios;?>
    </div>
</div>