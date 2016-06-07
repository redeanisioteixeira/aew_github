/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function checkComponente()
{
    var id_componentes = new Array();
    $('input:checked[name=opcao-item]').each(function()
    {
        var id = $(this).val();
        id_componentes.push(id);
    });
    $('input[name=componentes]').val(id_componentes.join());
}

function addOpcoesComponentes()
{
    $("#opcoes-componentes input[type=checkbox]").change(function()
    {
        checkComponente();
    });
}

$(document).ready(function()
{
    var desejaCadastrar = $('input[name=desejaCadastrar]:checked').val();
    
    $('label[for=conteudov]').removeClass('optional');
    $('label[for=conteudod]').removeClass('optional');

    if(typeof desejaCadastrar == 'undefined')
    {
        $('#fieldset-group_enviar').addClass('desativado');
    }
    else
    {
        var group  = '#fieldset-group_' + desejaCadastrar;
        
        $('label[for=desejaCadastrar]').removeClass('optional');
        $('#fieldset-group_enviar').addClass('desativado');
        $('#fieldset-group_indicar').addClass('desativado');
        $(group).css('display','block');
    }
    
    $('input.cadastrar-origem-midia').on('click',function()
    {
        $('label[for=site]').addClass('optional');
        $('label[for=separador_arq]').addClass('optional');
        
        $('input.cadastrar-origem-midia').each(function(){
            var group = '#fieldset-group_' +  $(this).val();
            $(group).slideUp(); 
        });

        var group  = '#fieldset-group_' + $(this).val();
        $(group).slideDown(); 
    });
    
    $('input.apagar-arquivo').on('click', function(){
        var name = $(this).attr('name');
        if($(this).is(":checked"))
        {
            $(this).parent().addClass('col-lg-12 col-md-12 col-sm-12 col-xs-12 alert alert-danger');
            $(this).parent().append('<span id="' + name + '"class="margin-left-10">Este arquivo será excluído de forma permanente</span>');
        }
        else
        {
            $(this).parent().removeClass('col-lg-12 col-md-12 col-sm-12 col-xs-12 alert alert-danger');
            $('span#' + name).remove();
        }
    });
    
    $('label[for=flsitetematico], input#flsitetematico').hide();
    
    if($('#idconteudotipo').val() == 8)
    {
        $('label[for=flsitetematico]').parent().addClass('alert alert-danger margin-none margin-bottom-10');
        $('label[for=flsitetematico], input#flsitetematico').show();
    }
    
    $('#idconteudotipo').on('change',function(a){
        var idTipo = $(this).val();

        $('label[for=flsitetematico]').parent().removeClass('alert alert-danger margin-none margin-bottom-10');
        
        if(idTipo == 8)
        {
            $('label[for=flsitetematico]').parent().addClass('alert alert-danger margin-none margin-bottom-10');
            $('label[for=flsitetematico], input#flsitetematico').show();
        }
        else
        {
            $('label[for=flsitetematico], input#flsitetematico').hide();
            $('label[for=flsitetematico], input#flsitetematico').val(0);
        }
        
		if(idTipo)
		{
		    $.post('/conteudos-digitais/conteudo/filtrar-formato',{id : idTipo},function(data){
		        var formatos = data.toString(',');
		        
		        $('u.formatos-dinamico').each(function(){
		            $(this).children().html(formatos);
		        });
		    },'JSON');
		}
    })
  
	$('#idconteudotipo').trigger('change');

    checkComponente();
    addOpcoesComponentes();
})
