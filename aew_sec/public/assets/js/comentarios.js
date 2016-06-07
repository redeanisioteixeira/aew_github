$(document).ready(function(){
    $('form#login-comentario').ajaxForm(
    {
        beforeSubmit: function()
        {
            $('#flash-erros').remove();     
            var r = $('form#login-comentario input[name=username]').val() != '' || $('form#login-comentario input[name=senha]').val() != '' ? true : false;
            return r 
        },
        complete: function(xhr, status, erro)
        {
            var bloco = xhr.responseText;
            if(bloco.search('flash-erros') != -1)
            {
                $('#box-login').before(bloco);
                $('#flash-erros').fadeOut(5000);
                $('form#login-comentario input[name=username]').focus();
            } 
            else 
            {
                location.href = location.href;
            }
        }
    });
    
    $('form#form-comentario.logar').ajaxForm(
    {
        beforeSubmit: function()
        {
            $('#box-login').css('display','block');
            $('form#form-comentario').css('display','none');
            $("input[name=username]").focus();
            return false;
        }
    });
    
    $('form#form-comentario.ativo').ajaxForm(
    {
        beforeSubmit: function(){
                return $('form#form-comentario textarea[name=comentario]').val() == '' ? false : true;
        },
        beforeSend: function(){
        },
        success: function(){
        },
        complete: function(xhr)
        {
            var bloco = xhr.responseText;
            if(bloco.search("flash-erros") != -1)
            {
                $('#box-login').before(bloco);
                $('#flash-erros').fadeOut(5000);
            } 
            else 
            {
                $('div#conteudo-comentarios').html(bloco);
                $('textarea[name=comentario]').val('');
                $('textarea[name=comentario]').focus();
                $('#flash-mensagens').fadeOut(5000);
                $('[data-toggle="tooltip"]').tooltip();
            }
        }
    });

    $(document).on('click', 'a[name=apagar-comentario]', function()
    {
        var rel = $(this).attr('rel');
        var idcomentario = $(this).attr('idcomentario');
        
        var pergunta = "<div class='comentario-confirmar col-lg-12 text-center'><span class='margin-right-10'>Este comentário será apagado de forma definitiva. Confirma?</span><input type='button' name='confirmar-apagar' class='btn btn-danger btn-xs margin-right-10' value='sim' idcomentario='"+rel+"'><input type='button' name='confirmar-apagar' class='btn btn-danger btn-xs' value='não'></div>";

        $('li.comentario-box').removeClass('alert alert-danger');
        $('div.comentario-confirmar').remove();

        $(this).before(pergunta);
        $('li.comentario-box[idcomentario="'+idcomentario+'"').addClass('alert alert-danger');
    });

    $(document).on('click', 'input[name=confirmar-apagar]', function(e)
    {
        e.preventDefault();
        if($(this).val() == 'sim')
        {
            var url = $(this).attr('idcomentario');

            $('div#conteudo-comentarios').load(url,function(){
                $('.flash-mensagens').fadeOut(5000);
                $('.flash-erros').fadeOut(5000);
            });
        }
        
        $('div.comentario-confirmar').remove();
        $('li.comentario-box').removeClass('alert alert-danger');
    });

    $('textarea[name=comentario]').bind('paste', function(e){
        e.preventDefault();
        return false;
    });
    $('.flash-mensagens').fadeOut(5000);
    $('.flash-erros').fadeOut(5000);
});
