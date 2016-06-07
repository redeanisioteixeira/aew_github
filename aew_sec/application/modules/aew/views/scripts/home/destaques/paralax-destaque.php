<?php
	if(!Sec_TipoDispositivo::isDesktop()):
		return;
	endif;

	$this->inlineScript()->appendFile('/assets/plugins/parallax.js-1.3.1/parallax.min.js')->setIndent(8); 
?>
<section id="box-paralax" class="box-paralax-destaque parallax-window margin-top-10" data-parallax="scroll" data-image-src="/<?php echo $this->imagens[0];?>">

    <div class="container">
        <h3 class="absolute link-branco text-shadow col-lg-3"><b>Exposição fotográfica do Ambiente Educacional Web</b></h3>

        <div class="parallax-shadow col-lg-3 shadow-center pull-right">
            <h4 class="menu-preto page-header text-center"><b>Bahia: <b class="link-vermelho">pessoas</b>, <b class="link-verde">lugares</b>, <b class="link-roxo">culturas</b> e <b class="link-marron">olhares</b></b></h4>
            <p class="link-preto">Caso seja fotógrafo e considere que sua região ou cultura ainda não estejam representados nesta exposição entre em contato através do <a class="faleConosco " data-toggle="modal" data-target="#modalGeral" href="/home/faleconosco"><b>fale conosco</b></a> para ampliarmos este acervo e oferecer subsídios para os professores e estudantes contarem com mais material para conhecer nossas “Bahias”.</p>
            <p class="link-preto">Acesse a nossa <a href="/home/exposicao-fotos"><b class="link-azul"><i class="fa fa-camera"></i> Exposição de Fotos</b></a> do Ambiente Educacional Web</p>
        </div>

        <div class="go-main hidden-sm hidden-xs relative" style="top: 290px;">
            <a class="scroll_nag" href="#disciplinas"><i class="fa fa-chevron-down fa-3x"></i></a>
        </div>
        
    </div>
    
</section>
