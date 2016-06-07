var categoria_ant = 0;
jQuery(document).ready(function($){ // No Conflict
    // Inicia tabs
    $('#tab-ambiente-apoio a:first').tab('show')
    
    // Carrega ambiemntes de apoio
    $('a.ambiente-apoio').on('click', function(e){
        var id_categoria = $(this).attr('categoria');
        var area = $(this).attr('area');
        var posicao = $(this).attr('posicao');

        $('i.ambiente-apoio-carregando').remove();
        
        if(id_categoria === categoria_ant){
            return;
        }
        
        categoria_ant = id_categoria;
        $('.lista-ambientes-apoio').slideUp();
        $('.ambiente-apoio-opcao').removeClass('active');
        $('figure.ambiente-apoio'+id_categoria).append('<i class="ambiente-apoio-carregando link-preto fa fa-cog fa-spin fa-2x"></i>');
        
        $('.lista-ambientes-apoio' + area).load('/ambientes-de-apoio/ambientes/listar/id/'+id_categoria, function(){
            $('.lista-ambientes-apoio' + area).slideDown(function(){
                $('img.lazy').lazyload({effect : 'fadeIn'});
                $('.itens').isotope();
            });
            
            $('.ambiente-apoio'+id_categoria).addClass('active');
            $('i.ambiente-apoio-carregando').remove();
        });
    });
    // Carrega ambientes de apoio inicial
    $('a.ambiente-apoio.active').trigger('click');
    $('.livepreview').livePreview({viewWidth: 380, viewHeight: 260, position: 'top', trigger: 'click'});
    $(document).on('click', 'a[name=apagar-categoria]', function()
    {
        var rel = $(this).attr('rel');
        var message = $('div.categoria-confirmar');
        var pergunta = "<div class='categoria-confirmar col-lg-12 text-center alert alert-danger'><p class='link-cinza-escuro'>Esta categoria será apagada de forma definitiva. Confirma?</p><p><input type='submit' name='confirmar-apagar' class='btn btn-primary btn-xs margin-right-10' value='sim' idcategoria='"+rel+"'><input type='submit' name='confirmar-apagar' class='btn btn-danger btn-xs' value='não'></p></div>";

        if(message.length){
            $('div.categoria-confirmar').remove();
        } else {
            $(this).parent().before(pergunta);
        }
        
        return false;
    });
});