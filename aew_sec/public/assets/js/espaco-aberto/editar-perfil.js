jQuery(document).ready(function($){ 
    
    var form= $('#redesocialform');
    var action= form.attr('action');
    var input= $('.validar');
    var btnEnviar = $('#btnEnviarRede');
    
    
    // adicionar rede social 
    btnEnviar.on('click',function(event){
                event.preventDefault();
                var btn =$(this);
                btn.addClass('loading');
                var urlRede = input.val();
                
                if(urlRede === ""){
                    $(input).parent().addClass('has-error has-feedback');
                    btn.removeClass('loading');
                }else
                $.ajax({
                    method:"POST",
                    url:action,
                    data:{url:urlRede},
                    success: function (data, textStatus) {
                        if(data === "false"){
                            $(input).parent().addClass('has-error has-feedback');
                            btn.removeClass('loading');
                        }else{
                            var ulList = $(data).find('.adicionar-rede'); //procura o ultimo filho para inserir na lista 
                            var liRede = $(ulList).children().last(); 
                            var container = $('.adicionar-rede');  //adiciona filho no final da lista 
                            btn.removeClass('loading');
                            container.append(liRede);
                        }
                    }
                });
    });
    
    
    /* apagar rede social */    
    var btnApagarRede = $('.adicionar-rede');
    btnApagarRede.on('click','.apagar-rede', function(e){
        e.preventDefault();
        var urlApagar = $(this).data('url');
        var id = $(this).data('id');
        var btn = $(this);
        var icone = $(btn).children();
        $(icone).removeClass('fa-close');
        $(icone).addClass('fa-spinner fa-spin');
        $.ajax({url:urlApagar,data:{id:id}}).done(function(data){
            $(icone).removeClass('fa-spinner fa-spin');
            $(icone).addClass('fa-check');
            $(btn).parent().parent().remove(); /* elimina tag li */
        }).fail(function(xhr){
            console.error(xhr);
        });
    });


});


