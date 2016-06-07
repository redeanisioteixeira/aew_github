var disciplina_ant = 0;

jQuery(document).ready(function($){ // No Conflict 
    // Apaga campo extra quando apresenta re-captcha
    $('#challenge').remove();
    
    // Activa Tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    // Ativa LazyLoad carrega com retardo para evitar multiplas requisições de imagens
    $('img.lazy').lazyload({effect : 'fadeIn'});    
    
    // Ativa Isotope para ajustar areas 
    $('.itens-isotope').isotope({
        layoutMode: 'masonry',
        masonry: 
        {
            gutter: 0
        }
    });
    
    //Acordion items isotope
    $('a.option-accordion').on('click',function()
	{
        $('.itens-isotope').each(function(){
            $(this).isotope();
        });
    });
    
    //Altera icone de + para - e - para +
    $('a.subtopico').on('click', function(e){
        var status = $(this).attr('class');
        var id = $(this).attr('idtopico');

        if(status.search('collapsed') !== -1){
                $('.fa-id'+id).removeClass('fa-plus-circle');
                $('.fa-id'+id).addClass('fa-minus-circle');
        } else {
                $('.fa-id'+id).removeClass('fa-minus-circle');
                $('.fa-id'+id).addClass('fa-plus-circle');
        }
    });

    //Altera icone de "volume" para "mute"
    $('#volume-video').on('click', function(e){
        var status = $(this).attr('class');
        var vid = document.getElementById("videoDestaque");
        vid.volume = 0.9;

        if(status.search('fa-volume-off') !== -1){
            vid.currentTime = 0.1;
            $(this).removeClass('fa-volume-off');
            $(this).addClass('fa-volume-up');
            $(this).attr('title','mudo');
            $(this).attr('alt','mudo');
            $('img.equalizer').css('display','block');
            $('video.video-bg').css('opacity','0.8');
            vid.muted = false;
        } else {
            $(this).removeClass('fa-volume-up');
            $(this).addClass('fa-volume-off');
            $(this).attr('title','ouvir');
            $(this).attr('alt','ouvir');
            $('img.equalizer').css('display','none');
            $('video.video-bg').css('opacity','0.4');
            vid.muted = true;
        }
    });
    
    //Go Main
    /* ======= Scrollspy ======= */
    $('body').scrollspy({ target: '.top', offset: 400});
        $('a.scroll_nag').on('click', function(e){
		//store hash
		var target = this.hash;
		e.preventDefault();
        $('body').scrollTo(target, 800, {offset: 0, 'axis':'y', easing:'easeOutQuad'});
    });
    
    // Inicia tabs
    //$('#tab-disciplinas-temas a:first').tab('show');
    
    // Carrega disciplinas
    $('a.disciplina').on('click', function(e){
        var id_disciplina = $(this).attr('disciplina');
		var tipo = $(this).attr('name');

		if(tipo == 'topico-tematransversal')
		{
			location.href = '/conteudos-digitais/conteudos/listar/opcoes/'+id_disciplina;
			return; 
		}

        $('a.scroll_nag').trigger('click');
        
        if(id_disciplina === disciplina_ant){
            status = $('li.disciplina-conteudo').css('display');
            if(status.search('none') !== -1){
                $('li.disciplina'+id_disciplina).addClass('active');
                $('.disciplina-conteudo').slideDown(function(){
                    $('.disciplina-grupos').isotope();
                });
            } else {
                $('.disciplina-conteudo').slideUp(function(){
                    $('li.disciplina'+id_disciplina).removeClass('active');
                });
            }
            return;
        }

        $('li.disciplina-opcao').removeClass('active');
        $('i.disciplina-carregando').remove();

        $('li.disciplina-conteudo').slideUp();
        $('figure.disciplina'+id_disciplina).append('<i class="disciplina-carregando link-preto fa fa-cog fa-spin fa-2x"></i>');
        $('li.disciplina-conteudo').html('');
        
        $('li.disciplina-conteudo').load('/conteudos-digitais/disciplinas/topicos',{id: id_disciplina, resumo: 1}, function(){
            $('.disciplina-conteudo').slideDown(function(){
                $('.disciplina-grupos').isotope();
                $('i.disciplina-carregando').remove();
                $('li.disciplina'+id_disciplina).addClass('active');
                disciplina_ant = id_disciplina;
                
            });
        });

    });
    
    // Topicos
    $('a[name=opcao-topico]').on('click', function(){
        var href = $(this).attr('bitly');
        var topico = $(this).text();

        $('a[name=opcao-topico]').removeClass('active');

        $(this).addClass('active');
        $('body,html').animate({scrollTop: 0}, 800);
        $('#box-canvas-topicos').removeClass('active');
        
        $('h5[name=topico]').html('<b>Assunto selecionado : </b>'+topico);
        $('#lista_conteudos').html('<i class="link-preto fa fa-cog fa-spin fa-3x"></i>');

		$.post('/conteudos-digitais/conteudos/urlextendida',{url : href}, function(href){
				if(href.search('listar') != -1)
				{
				    $.get(href, {topicos: 1, limpar: 1}, function(data){
				        $('#lista_conteudos').hide().html(data).fadeIn('fast');
				    });
				}
				else
				{
				    $.get(href,{topicos: 1}, function(data){
				        $('#lista_conteudos').hide().html(data).fadeIn('fast');
				    });
				}
		},'JSON');

        return false;
    });
    
    // Carrega pagina completa de conteúdo digital
    $('body').on('click', 'a.conteudo-topico', function(){
        var href = $(this).attr('href');

        $('body,html').animate({scrollTop: 0}, 800);
        $('#lista_conteudos').html('<i class="link-preto fa fa-cog fa-spin fa-3x"></i>');

        $.get(href, {topicos: 1}, function(data){
            $('#lista_conteudos').hide().html(data).slideDown();
            $('#box-relacionados').isotope();
        });
        return false;
    });
    
    // Carrega tópicos se estiver pre-selecionado o item
    $('a.disciplina-topico.active').trigger('click');
    
    $('input.editar-topico').focusout(function(){
        var idd  = $(this).attr('iddisciplina');
        var idt  = $(this).attr('idtopico');
        var idta = $(this).attr('idanterior');
        var idtp = $(this).val();

        if(idta !== idtp){
            $('#mensagem-topico').load('/conteudos-digitais/disciplinas/salvar',{id: idd, topico: idt, pai: idtp}, function(){
                var mensagem = $(this).html();
            });
        }
    }); // fim topicos
    
    //Carousel
    $(function() {
        $('#carousel-home .item').first().addClass('active');
        $('#carousel-home').carousel({
            interval: 5000
        });

        $('#carousel-destaques .item').first().addClass('active');
        $('#carousel-destaques').carousel({
            interval: 5000
        });
    });
  
    // Off canvas function
    $(function() {
        $('[data-toggle=offcanvas]').click(function() {
            $('.row-offcanvas').toggleClass('active');
        });
    });    

    // Erros eliminados automaticamente
    $(function() {
        $('.flash-erros').delay(4000).fadeOut( 'slow', function() {
            $('.flash-erros').prop('disabled', false);
        });
        $('.flash-mensagens').delay(4000).fadeOut('slow', function(){
            $('.flash-mensagens').delay(4000).prop('disable', false);
        });
    });    
   
    // Subir ao topo
    $(function() {
            $('#back-to-top').hide();
                $(window).scroll(function () {

                if ($(this).scrollTop() > 50) {
                    $('#back-to-top').fadeIn();
                } else {
                    $('#back-to-top').fadeOut();
                }
            });
            // scroll body to 0px on click
            $('#back-to-top').click(function () {
            $('body,html').animate({
                scrollTop: 0
                }, 800);
                return false;
            });
    });
    
    /* Carrega conteudos digitais */
    $(function () {
        $('#modalGeral').on('hidden.bs.modal', function () {

        $(this).removeData('iframe');
        $(this).removeData('bs.modal');
        $('div.modal-content').children().remove();
        });
    });

    // Adiciona efeito slide na clase dropdown
    /*
    $(function(){
        $('.dropdown').on('show.bs.dropdown', function(e){
            $(this).find('.dropdown-menu').slideDown('slow');
        });

        $('.dropdown').on('hide.bs.dropdown', function(e){
            $(this).find('.dropdown-menu').slideUp('slow');
        });
    });
    */
   
    // Audio e Video 
    $('audio.audio-conteudo').on('play', function(){
        var id = $(this).attr('idaudio');
        $('img#equalizer'+id).removeClass('desativado');
    });
    
    $('audio.audio-conteudo').on('pause', function(){
        var id = $(this).attr('idaudio');
        $('img#equalizer'+id).addClass('desativado');
    });
    
    $('.carousel-inner .item:first').addClass('active');
    $('#carousel-foto').carousel({
        interval: 6000
    });

    $('.box-loading-ajax').click(function(){
        var message = $(this).attr('data-message');

        if(typeof message !== 'undefined')
        {
                $('b.menssage-box-ajax').text(message);
        }

        $('div.box-ajax').addClass('in');
        $('div.box-ajax').css('display', 'block');
    });
});