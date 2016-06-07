<li>
    <div class="btn-group dropup">
        <button type="button" 
                class="btn btn-default" 
                title="<?php echo $this->rede->getSite() ?>"
                disabled>
            <i class="fa fa-<?php echo $this->rede->getRede() ?>"></i> 
        </button>
        <button type="button" 
                class="btn btn-danger apagar-rede" 
                data-id="<?php echo $this->rede->getId() ?>"
                data-url="/espaco-aberto/perfil/apagar-minha-rede-social"
                data-toggle="tooltip" 
                data-placement="top" 
                title="apagar rede social">
            <i class="fa fa-close"></i>
        </button>
    </div>
</li>