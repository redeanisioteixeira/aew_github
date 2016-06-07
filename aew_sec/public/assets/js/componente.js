/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function atribuiComponente()
{
    if( $("#idcomponentes :selected").val() ) 
    {
	var componenteValue = $("#idcomponentes :selected").val();
	var componenteText = $("#idcomponentes :selected").text();
	if ( ckeckExixtItem("componenteCurricularAdd", componenteValue) ) 
        {
            var nivel= $("#nivelEnsinoAdd option:selected").text();
            var text = componenteText + ":" + nivel;
            adicionarItem("componenteCurricularAdd", componenteValue, text, "");
	} 
        else
	alert("Este Item já existe!");
    } 
    else
    alert("Selecione um Item!");
}

function adicionarItem(select, val, tex, cla) {
    $("#"+select).append("<option value='"+val+"' class='"+cla+"'>"+tex+"</option>");
}

function removerItem(select) {
    $("#"+select+" option:selected").remove();
}

function removeComponente()
{
    $("#removeComponenteCurricular").click(function() 
    {
	removerItem("componenteCurricularAdd");
    });
}

function ckeckExixtItem(select, val) {
	
	var list = $('#'+ select +' option')
	for( var i = 0; i < list.length; i++ )
		if ( list[ i ].value == val )
			return false;
	
	return true;
}

function enviaComp()
{
    var box = $("#componenteCurricularAdd");
    var hidden = $("#AllComponentesCurriculares");
    var temp = "";
    for ( var i = 0; i < box[0].options.length; i++ )
    temp += box[0].options[ i ].value + "#";
    hidden.val( temp );
}