jQuery(document).ready(function($){    
    /* função resposta colega */
    
    $('body').on('click','a[name=responder-recado]', function(){
        var url = $(this).attr('rel');
        
        $('form.resposta').remove();
        $('div.recado-confirmar').remove();
        $('li.box-recado').removeClass('alert alert-danger');
        
        resposta = $(this).parent('.acao-item').next('.form-resposta');
        resposta.fadeIn().html('<i class="fa fa-spinner fa-spin"></i> Carregando...');
        resposta.load(url);
    });
    
    $(document).on('click', 'a[name=apagar-recado]', function()
    {
        var rel = $(this).attr('rel');
        var idrecado = $(this).attr('idrecado');
        
        var pergunta = "<div class='recado-confirmar col-lg-12 text-center'><span class='margin-right-10'>Este recado será apagado de forma definitiva. Confirma?</span><input type='submit' name='confirmar-apagar' class='btn btn-primary btn-xs margin-right-10' value='sim' idrecado='"+rel+"'><input type='submit' name='confirmar-apagar' class='btn btn-danger btn-xs' value='não'></div>";

        $('form.resposta').remove();
        $('li.box-recado').removeClass('alert alert-danger');
        $('div.recado-confirmar').remove();

        $(this).before(pergunta);
        $('li.box-recado[id="recado'+idrecado+'"').addClass('alert alert-danger');
    });

    $(document).on('click', 'input[name=confirmar-apagar]', function(e)
    {
        
        if($(this).val() == 'sim')
        {
            var url = $(this).attr('idrecado');
            $('div.comentarios-lista').load(url,function(){
                $('.flash-mensagens').fadeOut(5000);
                $('.flash-erros').fadeOut(5000);
            });
        }
        
        $('div.recado-confirmar').remove();
        $('li.box-recado').removeClass('alert alert-danger');
    }); 
    
    var contenedorForm = $('.form-resposta');
    var form = contenedorForm.find('form');
    
      
});     
