jQuery(document).ready(function($){
    $('body').on('mouseover', 'ul.avaliacao li.avaliacao', function(){
        var id = $(this).attr('id-star-rating');
        var numero    = $(this).attr('avaliacao');
        
        $('ul#'+id+' i.estrela').removeClass('fa-star');
        $('ul#'+id+' i.estrela').addClass('fa-star-o');
        
        for(var i = 1; i <= numero; i++)
        {
            $('ul#'+id+' i.estrela_nota_'+i).removeClass('fa-star-o');
            $('ul#'+id+' i.estrela_nota_'+i).addClass('fa-star');
        }        
    });
    
    $('body').on('mouseout', 'ul.avaliacao li.avaliacao', function(){
        var id = $(this).attr('id-star-rating');
        var numero    = $(this).attr('nota');
        
        $('ul#'+id+' i.estrela').removeClass('fa-star');
        $('ul#'+id+' i.estrela').addClass('fa-star-o');
        
        for(var i = 1; i <= numero; i++)
        {
            $('ul#'+id+' i.estrela_nota_'+i).removeClass('fa-star-o');
            $('ul#'+id+' i.estrela_nota_'+i).addClass('fa-star');
        }
    });
});