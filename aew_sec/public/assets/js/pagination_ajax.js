function pagination(page)
{
    var url   = $(page).attr('url');
    var bloco   = $(page).attr('bloco');
    var metodo = $(page).attr('metodo');

    $('i.carregando').removeClass('desativado');

    if(metodo == 'post')
    {
        var param = new Array();
        $('input.parametros').each(function(){
            param.push({name: $(this).attr('name'), value: $(this).val()});
        });
        
        $.post(url, param, function(data, status){
            var isotope = $('.itens-isotope');

            $(bloco).html(data);

            $('i.carregando').addClass('desativado');
            $('img.lazy').lazyload({effect:'fadeIn'});

            if(isotope != undefined)
            {
                $('.itens-isotope').isotope();
            }
        });
    }

    if(metodo == 'load')
    {
        $(bloco).load(url, function(){
            var isotope = $('.itens-isotope');

            $('i.carregando').addClass('desativado');
            $('img.lazy').lazyload({effect:'fadeIn'});

            if(isotope != undefined)
            {
                $('.itens-isotope').isotope();
            }
            equalizeraudio();
        });
    }
    
    
}

function equalizeraudio()
{
    $('audio.audio-conteudo').on('play', function(){
        var id = $(this).attr('idaudio');
        $('img#equalizer'+id).removeClass('desativado');
    });

    $('audio.audio-conteudo').on('pause', function(){
        var id = $(this).attr('idaudio');
        $('img#equalizer'+id).addClass('desativado');
    });
}
