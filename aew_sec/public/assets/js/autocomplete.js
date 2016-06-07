var filter = '';

$(function(){
    function split(val){
            return val.split(/,\s*/);
    }

    function extractLast(term){
            return split(term).pop();
    }

    $("#tags")
        // don't navigate away from the field on tab when selecting an item
        .bind("keydown", function(event){
            if (event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active){
                event.preventDefault();
            }
        })
        .autocomplete({
            minLength: 3,
            source: function(request, response){
                $.getJSON("/aew/home/sugerir", {term: extractLast(request.term), by: filter}, response);
            },
            search: function(){
                // custom minLength
                var term = extractLast(this.value);
                
                if($(this).hasClass("opcao-busca-titulo"))
                {
                    filter = 'titulo';
                }
                
                if($(this).hasClass("opcao-busca-tag"))
                {
                    filter = 'tag';
                }
                
                if (term.length < 3){
                    return false;
                }
            },
            focus: function(){
                // prevent value inserted on focus
                return false;
            },
            select: function(event, ui){
                var terms = split(this.value);
                var separator = ($(this).hasClass("not-comma") ? "" : ", ");
                var term = ui.item.value;

                term = term.replace(/'/g,"");
                term = term.replace(/"/g,"");

                term = term.trim();

                // remove the current input
                terms.pop();

                // add the selected item
                terms.push(term);

                // add placeholder to get the comma-and-space at the end
                terms.push("");
                this.value = terms.join(separator);
                
                if($(this).hasClass("opcao-busca-titulo") || $(this).hasClass("opcao-busca-tag"))
                {
                    var busca = $("input[name=busca]").val();
                    if(typeof busca != "undefined")
                    {
                        $("input[name=busca]").val('"'+term+'"');
                        $("input[name=buscaportitulo]").val(filter);

                        executaAjax();
                    }
                }
                return false;
            }
    });
    
    $("textarea.not-enter").keyup(function (event) {
        if (event.keyCode == 13)
        {
            if(event.shiftKey)
            {
                return false;
            }
        }
    });    
    
    $("textarea.not-enter").keydown(function (event) {
        var terms = split(this.value)
        if ((event.keyCode == 188 || event.keyCode == 32) && terms.pop() == "")
        {
            return false;
        }
        
        if (event.keyCode == 13 || event.keyCode == 46 || event.keyCode == 58 || event.keyCode == 59 || event.keyCode == 190 || event.keyCode == 191 || (event.shiftKey && event.keyCode == 191))
        {
            return false;
        }
    });    
});
