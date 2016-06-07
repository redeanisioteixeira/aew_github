<div id="jan_<?php echo  $this->usuarioContato->getId() ?>" class="janela">
    <div class="topo-chat" id="<?php echo  $this->usuarioContato->getId() ?>">
        <span title="minimizar"> <strong><?php echo  $this->usuarioContato->getPrimeiroNome() ?></strong> </span>
        <a class="opcao-chat" title="configurações" id="configurar"></a>
        <a class="opcao-chat " title="fechar" id="fechar"></a>
    </div>
    <div id="corpo">
        <div class="configurar" id="<?php echo  $this->usuarioContato->getId() ?>">
            <a id="bloquear">bloquear colega</a>
        </div>
        <div class="mensagens">
            <div class="area_emoticons" id="area_<?php echo  $this->usuarioContato->getId() ?>"></div>
            <ul class="listar" id="ul<?php echo  $this->usuarioContato->getId() ?>"></ul>
            <div class="aviso" id="aviso_<?php echo  $this->usuarioContato->getId() ?>"></div>
                
        </div>
        <textarea autofocus="" rows="1" maxlength="500" id="mensagem_<?php echo  $this->usuarioContato->getId() ?>" class="mensagem-chat"></textarea>
        <a class="chamada_icones" id="<?php echo  $this->usuarioContato->getId() ?>"></a>
    </div>
</div>