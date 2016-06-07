function offlineLinks()
{
    var links = $('a[href^=http]');
    links.each(function(index,link){
        link = $(link)
        link.on('click', function() 
        {   	
            var baseUrl = window.location.host;
            var l = link.attr('href');
            l.replace(/\+/g, '');
            if(l.indexOf(baseUrl) == -1)
            {
                $.ajax(
                {
                    url: '/usuario/flash-message/text/Site offline!',
                    type: "POST",
                    dataType: "html" ,
                    success: function(response)
                    {
                        var container = $(".flash-messages");
                        container.html(response);
                        $('#flash-mensagens').delay(2000).fadeOut('slow', function()
                        {
                            $('#flash-mensagens').delay(2000).prop('disable', false);
                        });
                        $('#flash-erros').delay(2000).fadeOut('slow', function()
                        {
                            $('#flash-erros').delay(2000).prop('disable', false);
                        });

                    },
                    failed:function(error)
                    {

                    }
                });
                return false;
            }
        });
    });
}

$(document).ready(function() 
{ 
    $('#area-usuario').remove();
    $('form#form-comentario').remove();
    
    offlineLinks();
});