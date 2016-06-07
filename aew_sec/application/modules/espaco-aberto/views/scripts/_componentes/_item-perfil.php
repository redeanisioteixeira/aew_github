<a class="media" href="<?php echo $this->itemPerfil->getLinkPerfil();?>">

    <figure class="media-left">
        <img class="img-<?php echo ($this->itemPerfil instanceof Aew_Model_Bo_Comunidade ? 'rounded' : 'circle');?>  shadow-center media-object" src="<?php echo $this->itemPerfil->getFotoPerfil()->getFotoCache(Aew_Model_Bo_Foto::$FOTO_CACHE_90X90,false,90,90,true);?>" width="30" height="30">
    </figure>

    <span class="media-body middle text-capitalize" alt ="Exibir perfil de <?php echo $this->itemPerfil->getNome()?>" title ="Exibir perfil de <?php echo $this->itemPerfil->getNome();?>">
        <?php echo strtolower($this->itemPerfil->getNome());?>
    </span>
</a>

<?php if( $this->itemPerfil instanceof Aew_Model_Bo_Usuario && $this->opcaoPendente):?>
    <?php if($this->usuarioLogado->isColegaPendente($this->itemPerfil)):?>
        <hr>
        <div class="clearfix text-center">
            <span><i class="fa fa-thumbs-up"></i> Responder ao convite<i class="fa fa-question"></i></span> 
            <div id="confirmacao" class="margin-top-10 btn-group btn-group-xs">
                <a class="btn btn-success" href="<?php echo $this->usuarioLogado->getUrlAceitarColega($this->itemPerfil);?>" data-toggle="tooltip" data-placement="top" title="Aceitar"><i class="fa fa-check" aria-hidden="true"></i> aceitar</a>
                <a class="btn btn-danger" href="<?php echo $this->usuarioLogado->getUrlRecusarColega($this->itemPerfil);?>" data-toggle="tooltip" data-placement="top" title="Recusar"><i class="fa fa-times" aria-hidden="true"></i> recusar </a>
            </div>
        </div>
    <?php endif;?>
<?php endif;?>