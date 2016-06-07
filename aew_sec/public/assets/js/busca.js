/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$.fn.clearForm = function() 
{
    return this.each(function() 
    {
        var type = this.type, tag = this.tagName.toLowerCase();
        if (tag == 'form')
            return $(':input',this).clearForm();
        
        if (type == 'text' || type == 'password' || type == 'hidden'  || tag == 'textarea')
            this.value = '';
        else if (type == 'checkbox' || type == 'radio')
                this.checked = false;
            else if (tag == 'select')
                this.selectedIndex = -1;
    });
};

function executaAjax()
{
    $('button[name=buscar]').click();
}

function executaBusca()
{  
    var limpar = parseInt($('input[name=limpar]').val());
    var id_categoria = new Array();
    var id_nivelensino = new Array();
    var id_componentes = new Array();
    var id_licencas = new Array();
    var id_tipos = new Array();
    
    if(limpar)
    {
        $('form[name=busca]').clearForm();
        
        $('input[name=limpar]').val(1);
        $('input[name=quantidade]').val(15);
        $('input[name=ordenarPor]').val('avaliacao');
        $('input[name=pagina]').val(1);
        $('input[name=visualizacao]').val('column');
    }
    else
    {
        //--- Verifica se foram selecionados tipos de conteúdos
        $('input:checked[name=tipo-conteudo]').each(function(){
            var id = $(this).val();
            id_tipos.push(id);
        });

        //--- Verifica se foram selecionados componentes curriculares
        $('input:checked[name=opcao-item]').each(function(){
            var id = $(this).val();
            var licenca =  $(this).hasClass('licenca');
            var categoria =  $(this).attr('categoria');
            var nivelensino =  $(this).attr('nivel-ensino');
            
            if(licenca == true)
            {
                id_licencas.push(id);
            }
            else
            {
                id_componentes.push(id);
            }
            
            if(typeof categoria !== 'undefined')
            {
                if(parseInt(categoria) > 0 && id_categoria.indexOf(categoria))
                {
                    id_categoria.push(categoria);
                }
            }

            if(typeof nivelensino !== 'undefined')
            {
                if(parseInt(nivelensino) > 0 && id_nivelensino.indexOf(nivelensino))
                {
                    id_nivelensino.push(nivelensino);
                }
            }
        });

        $('input[name=tipos]').val(id_tipos.join());
        $('input[name=categorias]').val(id_categoria.join());
        $('input[name=niveisensino]').val(id_nivelensino.join());
        $('input[name=opcoes]').val(id_componentes.join());
        $('input[name=licencas]').val(id_licencas.join());
    }
}

function alturaBoxOpcoes()
{   
	var heightGroup     = $('section#box-busca-opcoes').height();
    var heightContainer = $('section#inicio').height();
    var heightFooter = $('footer').height();
/*    
    $('a.collapse-busca').each(function(){
        var grupo = $(this).attr('href');
        if($(this).hasClass('collapsed') === false)
        {
            heightGroup = heightGroup + $(grupo).height();
        }
    });
*/    
    if(heightGroup > 0)
    {
        heightGroup = (heightGroup >= heightContainer ? heightGroup : heightContainer) + heightFooter;
    }
    
    return heightGroup;
}

jQuery(document).ready(function($){ // No Conflict

/*
    $("section#inicio").height(alturaBoxOpcoes());
        
    $('a.collapse-busca').click(function(){
        $("section#inicio").height(alturaBoxOpcoes());
    });
*/    
    //verifica se tem componentes selecionados para visualizar abertas as opções de nivel-ensino
    $('input:checked[name=opcao-item]').each(function(){
        var id = $(this).attr('nivel-ensino');
        var grupo = $('ul#nivel-ensino' + id).attr('grupo');

        $('span#' + grupo).removeClass('fa-chevron-down');
        $('span#' + grupo).addClass('fa-chevron-up');
        $('ul.' + grupo).each(function(){
            $(this).slideDown();
        });
    });

    //Ativa seleção de "ordenarPor"
    $('ul[name=ordenarPor] li').on('click',function(){
        var opcao = $(this);
        var descricao = opcao.children().text();
        var valor = opcao.children().attr('value');

        opcao.parent().children().removeClass('active').children().removeClass('fa-check-square-o').addClass('fa-square-o');
        opcao.addClass('active').children().removeClass('fa-square-o').addClass('fa-check-square-o');

        $('label[name=ordenarPor]').text(descricao.toLowerCase());
        $('input[name=ordenarPor]').val(valor);
        
        executaAjax();
    });

    //Ativa seleção de "visualizarPor"
    $('button[name=visualizarPor]').on('click',function(){
        var view = $(this).attr('view');
        if($(this).hasClass('active'))
        {
            return;
        }
        
        $('input[name=visualizacao]').val(view);
        executaAjax();
    });

    //Ativa seleção de "Quantidade"
    $('ul[name=quantidade] li').on('click',function(){
        var opcao = $(this);
        var descricao = opcao.children().text();
        var valor = opcao.children().attr('value');

        opcao.parent().children().removeClass('active').children().removeClass('fa-check-square-o').addClass('fa-square-o');
        opcao.addClass('active').children().removeClass('fa-square-o').addClass('fa-check-square-o');

        $('span[name=quantidade]').text(descricao);
        $('label[name=quantidade]').text(descricao);
        $('input[name=quantidade]').val(valor);
        
        executaAjax();
    });
/*    
    $('body').on('change','input[name=busca]',function(){
        var busca = $(this).val();
        var tipo  = $('input:checked[name=opcao-busca-palavra]').val();
        if(!busca)
        {
            return;
        }
        if(tipo == 'titulo')
        {
            busca = busca.replace(/'/g,' ');
            $(this).val(busca);
            executaAjax();
        }
    });
    
    $('body').on('click','button[name=busca-avancada]',function(){
        var status = $('div.busca-avancada').hasClass('desativado');
        
        if(status){
            $('div.busca-avancada').slideDown('slow', function(){
                $('div#conector').removeClass('desativado');
                $(this).removeClass('desativado');
            });
            $('.itens-isotope').isotope();
        }
        else{
            $('div.busca-avancada').slideUp('slow', function(){
                $(this).addClass('desativado');
                $('div#conector').addClass('desativado');
            });
        }
    });
*/
    $('body').on('click','input[name=tipo-conteudo]',function(){
        var id = $(this).attr('id');
        var status = $(this).is(':checked');
        var id_tipos = new Array();
        
        if(status === true){
            $('label#' + id).removeClass('fa-square-o');
            $('label#' + id).removeClass('normal');
            $('label#' + id).addClass('fa-check-square-o');
            $('label#' + id).addClass('link-preto');
        }
        else
        {
            $('label#' + id).removeClass('fa-check-square-o');
            $('label#' + id).removeClass('link-preto');
            $('label#' + id).addClass('fa-square-o');
            $('label#' + id).addClass('normal');
        }
        
        //--- Verifica se foram selecionados tipos de conteúdos
        $('input:checked[name=tipo-conteudo]').each(function(){
            var id = $(this).val();
            id_tipos.push(id);
        });
    
        $('input[name=tipos]').val(id_tipos.join());
        
        executaAjax();
    });

    $('body').on('click','input[name=opcao-item]',function(){
        var id = $(this).attr('id');
        var status = $(this).is(':checked');
        
        if(status === true){
            $('label#' + id).removeClass('fa-square-o');
            $('label#' + id).removeClass('normal');
            $('label#' + id).addClass('fa-check-square-o');
            $('label#' + id).addClass('link-preto');
        }
        else
        {
            $('label#' + id).removeClass('fa-check-square-o');
            $('label#' + id).removeClass('link-preto');
            $('label#' + id).addClass('fa-square-o');
            $('label#' + id).addClass('normal');
        }
        
        executaAjax();
    });
    
    $('body').on('click','span[name=opcao-grupo]',function(){
        var id = $(this).attr('id');
        var status = $('ul.' + id).css('display');
        if(status === 'block')
        {
            $(this).removeClass('fa-chevron-up');
            $(this).addClass('fa-chevron-down');
            $('ul.' + id).each(function(){
                $(this).slideUp();
            });
        }
        else
        {
            $(this).removeClass('fa-chevron-down');
            $(this).addClass('fa-chevron-up');
            $('ul.' + id).each(function(){
                $(this).slideDown();
            });
        }
    });
   
    // Marca/desmarcxa todas os componentes pertencentes ao nivel-ensino ou categoria
    $('body').on('click','input[name=item-superior]',function(){
        var id = $(this).attr('id');
        var status = $(this).is(':checked');
        var opcoes = $('input.' + id);

        if(status === true){
            $('label#' + id).removeClass('fa-square-o');
            $('label#' + id).addClass('fa-check-square-o');
        }
        else
        {
            $('label#' + id).removeClass('fa-check-square-o');
            $('label#' + id).addClass('fa-square-o');
        }

        opcoes.each(function(){
            var id = $(this).attr('id'); 
            $(this).prop('checked',!status);
            $('input#' + id).click();
        });
    });

    $('body').on('click','label[name=opcao-busca-palavra]',function(){
        var tipo = $(this).attr('value');
        
        $(this).children('input[type=radio]').prop('checked', true);
        
        $('i.opcao-busca-palavra').removeClass('fa-dot-circle-o');
        $('i.opcao-busca-palavra').addClass('fa-circle-o');
        
        $('i[name=opcao-busca-palavra-'+tipo+']').removeClass('fa-circle-o');
        $('i[name=opcao-busca-palavra-'+tipo+']').addClass('fa-dot-circle-o');
        
        $('input[name=busca]').removeClass('opcao-busca-tag');
        $('input[name=busca]').removeClass('opcao-busca-titulo');
        
        $('input[name=busca]').addClass('opcao-busca-'+tipo);
        
        //availableTags = (tipo == 'tag' ? listTag : listTitle);
    });
    
    $('body').on('click','a[name=topico-tematransversal]',function(){
        var id = $(this).attr('disciplina');
        
        //Limpa qualquer seleção previa 
        $('input[name=opcao-item]').each(function(){
            $(this).prop('checked', false);
        });

        $('input#componente' + id).prop('checked', true);
        $('button[name=buscar]').click();
        
        //$('form[name=busca]').submit();
    });
    
    // Favoritos
    $('.opcao-favorito').on('click',function(){
        var favorito = $('input[name=favorito]').val();
        $('input[name=favorito]').val(favorito == 1  ? '' : '1');
        $('button[name=buscar]').click();
        //$('form[name=busca]').submit();
    });    
    
    //url curta
    $('.opcao-ulrcurta').on('click',function(e){
        e.preventDefault();
        var action = $(this).attr('data-urlcurta');
        var input = $('[name=copiar-link]');
        var urlatual = window.location.href;
        if(!$(this).hasClass('open'))
        {
			$.post(action, {url : urlatual},
				function(data){
					input.val(data);
				},'JSON');
        }
    });
});
