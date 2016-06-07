<div class="acoes">
    <a href="<?php echo $this->amigo->getUrlAprovar($this->usuarioLogado) ?>" 
       title="Aprovar">Aprovar</a>
    <a href="<?php echo $this->amigo->getUrlReprovar($this->usuarioLogado) ?>"
       title="Reprovar" >Reprovar</a>
    <div class="clear"></div>
</div>
<div class=" conteudo-margin exibir">
    <div class="dados">
        <div >Nome: <?php echo $this->escape($this->amigo->getUsuarioCriado()->getNome()); ?></div>
        <div >Data de Nascimento: <?php echo $this->escape($this->date($this->amigo->getUsuarioCriado()->getDataNascimento())); ?></div>
        <div >Sexo: <?php echo strtoupper($this->amigo->getUsuarioCriado()->getSexo()); ?></div>
        <div >Telefone: <?php echo $this->escape($this->amigo->getUsuarioCriado()->getTelefone()); ?></div>
        <div >RG: <?php echo $this->escape($this->amigo->getUsuarioCriado()->getRg()); ?></div>
        <div >CPF: <?php echo $this->escape($this->amigo->getUsuarioCriado()->getCpf()); ?></div>
        <div >Endereço: <?php echo $this->escape($this->amigo->getUsuarioCriado()->getEndereco()); ?></div>
        <div >Número: <?php echo $this->escape($this->amigo->getUsuarioCriado()->getNumero()); ?></div>
        <div >Complemento: <?php echo $this->escape($this->amigo->getUsuarioCriado()->getComplemento()); ?></div>
        <div >Bairro: <?php echo $this->escape($this->amigo->getUsuarioCriado()->getBairro()); ?></div>
        <div >CEP: <?php echo $this->escape($this->amigo->getUsuarioCriado()->getCep()); ?></div>
        <div >Município: <?php echo $this->amigo->getUsuarioCriado()->getMunicipio()->getNome(); ?></div>
        <div >Estado: <?php echo $this->amigo->getUsuarioCriado()->getEstado()->getNome(); ?></div>
    </div>
</div>