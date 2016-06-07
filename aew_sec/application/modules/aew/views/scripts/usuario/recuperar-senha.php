<div class="alert alert-<?php echo($this->erro ? 'warning' : 'success');?>">
    <h3 class="text-center">
        <?php if(!$this->erro):?>
            <i class="fa fa-user-secret"></i> Parabéns, a sua nova senha foi enviada para o seu e-mail com sucesso.
        <?php else:?>
            <i class="fa fa-exclamation-triangle"></i> <?php echo $this->mensagem;?>
        <?php endif;?>
    </h3>
</div>
