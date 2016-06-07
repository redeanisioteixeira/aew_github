var disciplina_ant = 0;
jQuery(document).ready(function($){ // No Conflict

    // Carrega sites temáticos
    $('a.site-tematico').on('click', function(e){
        var id_disciplina = $(this).attr('site');
        var area = $(this).attr('area');
        var posicao = $(this).attr('posicao');
        var controller = $(this).attr('controller');

        if(id_disciplina === disciplina_ant){
            return;
        }
        
        $('.site-tematico-opcao').removeClass('active');
        $('figure.site-tematico'+id_disciplina).append('<i class="site-tematico-carregando link-preto fa fa-cog fa-spin fa-2x"></i>');
        
		if(disciplina_ant == 0)
		{
			$('div#opcoes-sites-tematicos').removeAttr('class');
			$('div#opcoes-sites-tematicos').addClass('col-lg-2 col-md-2 col-sm-4 col-xs-4');
			$('div#opcoes-sites-tematicos ul.opcoes-sites-tematicos li').removeAttr('class');
			$('div#opcoes-sites-tematicos ul.opcoes-sites-tematicos li').addClass('col-lg-12 col-md-12 col-sm-12 col-xs-12');
		}

        disciplina_ant = id_disciplina;
		$('.lista-sites-tematicos').fadeOut(function(){
		    $('.lista-sites-tematicos' + area).load('/sites-tematicos/' + controller + '/listar/id/'+id_disciplina, function(){
		        $('.lista-sites-tematicos' + area).slideDown(function(){
		            $('#itens').isotope();

		            $('.site-tematico'+id_disciplina).addClass('active');
		            $('i.site-tematico-carregando').remove();

		            if(posicao>3){
		                $('a.scroll_nag').trigger('click');
		            }

		        });

		    });
		});
    });
});
