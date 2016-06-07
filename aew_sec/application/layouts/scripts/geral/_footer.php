<?php $this->placeholder('footer')->captureStart();?>
<footer class="bs-footer navbar-inverse <?php echo $this->fixoAbaixo;?>" >
    <div class="container">
	
        <div class="row hidden-sm hidden-xs <?php echo $this->ocultar;?>" style="border-bottom:1px solid #777">
            <label class="link-branco"><i class="fa fa-tags"></i> Tags mais buscadas:</label>
            <?php echo $this->ShowCloudTags();?>
        </div>
        
        <div class="row">
            <div class="col-lg-2 col-md-6 col-sm-6 col-xs-5">
                <a href="http://creativecommons.org">
                    <img class="img-responsive" src="/assets/img/creative_commons.png" alt="creative commons logomarca"/>
                </a>
            </div>

            <div class="col-lg-2 col-md-6 col-sm-6 col-xs-7">
                <div class="row">
                    <?php echo $this->ShowAddThis();?>
                </div>
            </div>
		        
            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                <ul class="nav navbar-nav margin-none">
                    <li><a class="faleConosco <?php echo $this->linkCor;?>" data-toggle="modal" data-target="#modalGeral" href="<?php echo $this->urlFaleConosco;?>"><i class="fa fa fa-phone-square hidden-lg hidden-sm"></i> fale conosco</a></li>
                    <li class="divider-vertical hidden-sm hidden-xs <?php echo $this->linkCor;?>"></li>
                    <li><a class="faleDenunciar <?php echo $this->linkCor;?>" data-toggle="modal" data-target="#modalGeral" href="<?php echo $this->urlDenunciar;?>"><i class="fa fa fa-ban hidden-lg hidden-sm"></i> denunciar</a></li>
                    <li class="divider-vertical hidden-sm hidden-xs <?php echo $this->linkCor;?>"></li>
                    <li><a class="<?php echo $this->linkCor;?>" href="<?php echo $this->urlTermoUso;?>"><i class="fa fa-book hidden-lg hidden-sm"></i> termos e condições de uso</a></li>
                    <li class="divider-vertical hidden-sm hidden-xs <?php echo $this->linkCor;?>"></li>
                    <li><a class="<?php echo $this->linkCor;?>" href="<?php echo $this->urlSobre;?>"><i class="fa fa-info-circle hidden-lg hidden-sm"></i> sobre o AEW</a></li>
                    <li class="divider-vertical hidden-sm hidden-xs <?php echo $this->linkCor;?>"></li>
                    <li><a class="<?php echo $this->linkCor;?>" href="http://www.educacao.ba.gov.br/sites/default/files/private/midiateca/documentos/2014/cartilha-midias-e-tecnologias2014v4.pdf"><i class="fa fa-cogs hidden-lg hidden-sm"></i> mídias e tecnologias educacionais</a></li>
                </ul>

                <span class="copyleft pull-right clearfix">2013 | Todos os direitos e conteúdos deste Portal são de uso compartilhado</span>

            </div>
        </div>
    </div>
</footer>
<?php $this->placeholder('footer')->captureEnd();?>
