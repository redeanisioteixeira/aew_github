jQuery(document).ready(function($){
   
    // Plugin tags
    $('.tokenize').tokenize({
        placeholder: 'Sugerir comunidade para meus colegas...',
        newElements: false
    });
    
    $('input[name=enviarConvite]').click(function(){
        var form = $('form#formSugerir');
        var url = form.attr('action');
        var opt =  $( "select#colegas option:selected").length;

        if(!opt){
            return false;
        }
        
        $( ".resp" ).html('<span><b><i class="fa fa-spinner fa-spin"></i> Enviando</b></span>');

        $.ajax({
                method: "POST",
                url: url,
                data: form.serialize(),
                success: function(data){
                    $( ".resp" ).html(data);
                    
                    $('.erro').delay(1500).fadeOut( 'slow', function() {
                        $('.erro').delay(1500).prop('disabled', false);
                    });
                    
                    $('.sucesso').delay(1500).fadeOut('slow', function(){
                        $('.sucesso').delay(1500).prop('disable', false);
                    });
                    
                    $('li.Token').remove();
                }
        });
        
        return false;
    });
    
    // RELACIONADOS
    var container= $('.load-content');
    var urlRelacionados= container.attr('data-load-url');
        container.html('<div class="text-center margin-top-50"><i class="fa fa-spinner fa-spin fa-3x"></i></div>');
    //função ajax geral
    function ajaxLoad (url,dados){
       return $.ajax({
            url         :   url,
            data        :   dados
            });
    }
    
    //carrega relacionados
    ajaxLoad(urlRelacionados).done(function (data) {
            
            var relacionadas = new Array();
            $(data).find('.lista-relacionados li').each(function() { // for each para procurar ids relacionadas
                relacionadas.push($(this).attr("id")); // Pega os Id's das comunidades relacionadas
            });
            eliminaComunidade(relacionadas);
            container.html(data);
        }).fail(function () {
            // em caso de erro
            console.log('Erro');
        });
    
    // remove itens ja relacionados
    
    function eliminaComunidade(rel){
        // rel comunidades relacionadas
        $.each( rel, function(index,elem){
            // foreach para eliminar ids de comunidades não relacionadas
            $('.lista-comunidades li').each(function() {
                var idNoRelacionada = $(this).data("comuid");
                console.warn(elem + "==" + idNoRelacionada);
                if(elem == idNoRelacionada)
                    $(this).remove();
                else
                    $(this);
            });
        });
        
    };
});

