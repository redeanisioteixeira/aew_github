<section id="box-destaque" class="box-video-destaque margin-top-10">
    <div class="container">

      <div class="box-conteudo-video text-center col-lg-6 col-md-6 col-sd-12 col-xs-12">
        <video id="video-destaque" class="trans90 rounded shadow-center" poster="/destaques/apresentacao-aew.png" controls preload>
            <source src="/destaques/apresentacao-aew<?php echo ($this->tipoDispositivo == 'desktop' ? '.1980p' : '');?>.webm" type="video/webm">
        </video>
      </div>  
        
      <div class="col-lg-6 col-md-6 col-sd-12 col-xs-12">
            <h3 class="menu-preto page-header"><b>O Ambiente Educacional Web agora também é mobile <i class="fa fa-exclamation"></i></b></h3>

            <p class="font-size-150">Descubra novas formas de aprender e ensinar no Ambiente Educacional Web. Acesse do seu  <b class="link-azul"><i class="fa fa-mobile-phone"></i> smartphone</b> ou <b class="link-roxo"><i class="fa fa-tablet"></i> tablet</b>, assista ao vídeo e compartilhe essa novidade.</p>
            <p class="font-size-150">É da Bahia, da rede pública de ensino, a licença é livre, feito pela comunidade escolar e para a comunidade escolar.</p>
            <p class="font-size-150 link-preto"><i class="fa fa-thumbs-up link-marron"></i> curta <i class="fa fa-comment link-amarelo"></i> comente <i class="fa fa-lightbulb-o link-vermelho"></i> colabore <i class="fa fa-share-alt link-verde"></i> compartilhe</p>
			<div class="compartilhar pull-right"><?php echo $this->ShowShareThis();?></div>
      </div>  
    </div>
</section>

<script>
	if(window.location.hash == '#box-destaque'){
		var video = document.getElementById('video-destaque').setAttribute('autoplay', true);
	}
</script>
