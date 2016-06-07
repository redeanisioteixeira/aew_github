<h2>Cadastro realizado com sucesso</h2>

<?php
    echo $this->showMessages();
?>
<div class="conteudo-margin">
<p>Parabéns <?php echo $this->usuario['nome']; ?>,<br/>Você agora está cadastrado no
Ambiente Educacional Web!<br/><br/>
As informações para entrar no sistema foram enviadas para o seu e-mail.</p>
<br/>
  <p class="pdb"><strong><a href="<?php echo $this->url(array('controller'=>'home',
			'action' => 'home', 'module' => 'aew')); ?>"
			title="Clique aqui para ir para a página inicial">
	Clique aqui para ir para a página inicial</a></strong></p>
</div>