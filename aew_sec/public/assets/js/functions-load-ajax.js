/**submint form ecarrega umconteudoem uma div*/
//load-content-form = classe para submeter um formulario via ajax;
//idloadcontainer      = atributo do formulario que indica qual tag html ira receber o retorno da requisicao ajax
//link-action       = classe atribuida a um link para fazer riquisição via ajax
//load-scroll       = classe atribuida um elemento html que tera scroll dinamimco
//modal-confirm     = modal para confirmar a ação
//select-dinamic    = selec conteudo dinamico
var test ;
var searching = false;
var requisicaoPendente = false;
function loadScrollRegister()
{
    var divScroll = $('.load-scroll');
    divScroll.each(function(index,ele)
    {
        ele = $(ele);
        ele.attr('pag',2);
        var scrollEle = ele;
        if(!ele.attr('auto-scroll'))
        scrollEle = $(window);
        scrollEle.scroll(function()
        {
            if(!searching)
            if(scrollEle.scrollTop() >=  $(document).height()-scrollEle.height() )
            {
                var pag = parseInt(ele.attr('pag'));
                var url = ele.attr('rel')+'/pagina/'+pag;
                if(!requisicaoPendente)
                loadAjax(url,ele,pag);
            }
        });
    });
}

function loadAjax(url,ele,pag,data)
{
    if(ele.is('div') || ele.is('button') || ele.is('ul'))
    ele.append('<div id="loading-icon" class="text-center"><i class="fa fa-spinner fa-spin fa-2x"></i></div>');
    requisicaoPendente = true;
    $.ajax(
    {
        url: url,
        type: "POST",
        data: data,
        dataType: "html" ,
        success: function(response)
        {
            ele.find('#loading-icon').remove();
            actionLoad(ele,response);
            if(ele.attr('pag'))
            ele.attr('pag',pag+1);
            requisicaoPendente = false;
        },
        failed:function(error)
        {
            console.log(error);
        }
    });
}

function loadFormContentRegister()
{
    var form = $('div.load-content-form, form.load-content-form, section.load-content-form');
    form.each(function(index,divForm)
    {
        var divForm = $(divForm);
        var f = divForm.find('form');
        var button = f.find("submit, button[type=submit] ,input[type=submit]");
        if(button)
        {
            button.unbind();
            button.on('click',function(event)
            {
                event.preventDefault();
                if(f.hasClass('modal-confirm'))
                {
                    var text = f.attr('text')? f.attr('text'):"Confirmar?";
                    if(!window.confirm(f.attr('text')))
                    return;
                }
                var url = f.attr('action');
                loadAjax(url,divForm,0,f.serialize());
            });
        }
    });
}

function linkActionRegister()
{
    var link = $('a.link-action');
    link.each(function(index,l)
    {
        l = $(l);
        l.on('click',function()
        {
            if(l.hasClass('modal-confirm'))
            {
                var text = l.attr('text')? l.attr('text'):"Confirmar?";
                if(!window.confirm(l.attr('text')))
                return;
            }
            var url = l.attr('rel');
            loadAjax(url,l,0)
        });
    });
}

function registerItemSelect(element,input)
{
    var lis = $(element.find('li'));
    test = input;
    lis.each(function(index,li)
    {
        li = $(li)
        li.on('click',function()
        {
            setInputTags(input,element,li)
        });
    });
}

function setInputTags(input,element,li)
{
    var tags = input.val();
    var index = tags.lastIndexOf(',');
    var vir = ', ';
    input.val(tags.substring(0,index+1)+' '+li.text()+vir);
    
    input.focus();
    li.hide();
    element.hide();
}

function searchInputRegister()
{
    var searchInputs = $('.search-input');
    var separador = ',';//[',',';'];
    searching = false;
    var indexSelected = 0;
    searchInputs.each(function(index,search)
    {
        search = $(search);
        var container = search;
        if(search.attr('autocomplete'))
        {
            search.attr('autocomplete','off');
            var id = search.attr('id');
            var name = search.attr('name');
            var iddatalist = 'datalista-'+id+'-'+name;
            var ul = $("<ul class = 'search-input-datalist' id=\""+iddatalist+"\"> </ul>");
            ul.insertAfter(search);
            container = ul;
            search.on('keydown',function(e)
            {

                if(e.which===13)
                {
                    e.preventDefault();
                    var li = ul.find('li[value='+indexSelected+']');
                    setInputTags(search,ul,li);
                    return false;
                }
            })
        }
        else
        {
            var idcontainer = search.attr('idloadcontainer');
            var containerAjax = $('#'+idcontainer);
            if(containerAjax.length>0)
            {
                container = containerAjax;
            }
        }
        
        search.on('keyup',function(e)
        {
            e.preventDefault();
            var data = '';
            var url = search.attr('rel');
            var icon = search.attr('icon');
            var text = search.val();
            text = text.trim();
            keyUpKeyDown(e,indexSelected);
            if(search.attr('autocomplete'))
            if(text.length<3)
            {
                container.html('');
                return false;
            }
            if (!(e.which <= 90 && e.which >= 48) && (e.which!==8) && (e.which!==46)) //apenas alfanumerico na busca
            {
                return false;
            }
            searching = true; 
            if(search.attr('separador') || search.hasClass('multiple'))
            {
                var arText = text.split(separador);
                text = arText[arText.length-1];
            }
            text = text.trim();   
            if(((text.length>=3 || text.length===0 ) && (!requisicaoPendente)))
            { 
                searching = false;
                var url = url+'/filtro/'+text;
                
                requisicaoPendente = true;

                $.ajax(
                {
                    url: url,
                    type: "POST",
                    data: data,
                    dataType: "html",
                    beforeSend: function (xhr) 
                    {
                        $(icon).removeClass('fa-search');
                        $(icon).addClass('fa-spinner fa-spin');
                    },
                    success: function(response)
                    {
                        $('.notifications-menu').addClass('open');
                        
                        $(icon).removeClass('fa-spinner fa-spin');
                        $(icon).addClass('fa-search');

                        container.html(response);
                        indexSelected = 0;
                        container.show();
                        requisicaoPendente = false;
                        $( 'body' ).on('click',function(e)
                        {
                            if(e.target !== $('.notifications-menu'))
                            {
                                $('.notifications-menu').removeClass('open');
                            }
                        });
                        if(ul)
                        registerItemSelect(ul,search);    
                    },
                    failed:function(error)
                    {
                        console.log(error);
                    },
                    complete:function()
                    {
                        if(container.hasClass('itens-isotope'))
                        {
                            $(container).isotope();
                        }
                        requisicaoPendente = false;
                    }
                });
            }
        });
        
    });
}

function selectRegister()
{
    var selectsDiv = $('select.select-dinamic');
    selectsDiv.each(function(index,div)
    {
        var select = $(div);
        select.on('change',function()
        {
            var url = select.attr('rel')+'/'+select.attr('name')+'/'+select.val();
            loadAjax(url,select,0)
            var container = $('#'+select.attr('idloadcontainer'));
            container.attr('disabled',false);
        });
    });
}

function actionLoad(ele,response)
{
    var loadContainer = null;
    if(ele.attr('idloadcontainer'))
    {
        loadContainer = $('#'+ele.attr('idloadcontainer'));
        console.log(loadContainer)
    }
    try 
    {
        response = JSON.parse(response);
    } 
    catch (e) 
    {
        
    }
    var container = (loadContainer) ? loadContainer : ele;

    switch(ele.attr('type-action'))
    {
        case 'append-action' :
            if(response.html)
            {
                if(container.hasClass('itens-isotope'))
                {
                    container.isotope('insert',$(response.html));
                }
                else
                {
                    container.append(response.html);
                }
            }
            else
            {
                if(container.hasClass('itens-isotope'))
                {
                    container.isotope('insert',$(response));
                    
                }
                else
                {
                    container.append(response);
                }
            }
        break;
        case 'html-action'   : 
            container.attr('pag',1)
            if(response.html)
            {
                if(container.hasClass('itens-isotope'))
                {
                    container.html('');
                    container.isotope('insert',$(response.html));
                    var elem = document.querySelector('#'+container.attr('id'));
                    var iso = new Isotope( elem, {
                      // options
                      layoutMode: 'fitRows'
                    });
                }
                else
                {
                    container.html(response.html);
                }
            }
            else
            {
                if(container.hasClass('itens-isotope'))
                {
                    container.html('');
                    container.isotope('insert',$(response));
                    var elem = document.querySelector('#'+container.attr('id'));
                    var iso = new Isotope( elem, {
                      // options
                      layoutMode: 'fitRows'
                    });
                }
                else
                {
                    container.html(response);
                }
            }
            break;
        case 'erase-action'  :container.html('');break;
        case 'prepend-action':response.html?container.prepend(response.html):container.prepend(response); break;
        case 'replace-action':response.html?container.replaceWith(response.html):container.replaceWith(response); break;
    }
}

function  uploadFormRegister()
{
    var divForms = $('div.upload-form');
    divForms.each(function(index,ele)
    {
        var divForm = $(ele);
        var form = $(ele).find('form');
        var btn = form.find("submit, button[type=submit] ,button[name=submit],input[type=submit], button.btn-submit");
        var porcentagem = $(divForm.find('#porcentagem'));
        var action = form.attr('action');
        var respPanel = $(divForm.find('.resposta'));
        var progressBar = $(divForm.find('progress'));
        btn.click(function(e)
        {
            e.preventDefault();
            if(divForm.hasClass('modal-confirm'))
            {
                var text = divForm.attr('text')? divForm.attr('text'):"Confirmar?";
                if(!window.confirm(divForm.attr('text')))
                return;
            }
            form.ajaxForm(
            {
                uploadProgress: function(event, position, total, percentComplete) 
                {
                    progressBar.attr('value',percentComplete);
                    porcentagem.html(percentComplete+'%');
                },        
                success: function(data) 
                {
                    data  = JSON.parse(data);
                    if(data.success === true)
                    {
                        progressBar.attr('value','100');
                        porcentagem.html('100%');  
                        respPanel.html(data.html);
                    }
                    else
                    {
                        progressBar.attr('value','0');
                        porcentagem.html('0%');
                        respPanel.show();
                        respPanel.html(data.html);
                    }                
                }  ,
                error : function(resp)
                {
                    var errorMessage = resp.html ? resp.html: resp.text;
                    progressBar.attr('value','0');
                    porcentagem.html('0%');
                    respPanel.html(errorMessage);
                }   ,
                dataType: 'html',
                url: action,
                resetForm: true
            }).submit();
        });
    });
}

function keyUpKeyDown(e,indexSelected)
{
    //KEY UP OR KEY DOWN
    if(e.which===40)
    {
        indexSelected += 1;
        var li = ul.find('li[value='+indexSelected+']');
        var li2 = ul.find('li[value='+(indexSelected-1)+']');
        li2.removeClass('search-input-datalist-selected');
        li.addClass('search-input-datalist-selected');
    }
    else if(e.which===38)
    {
        indexSelected = indexSelected-1;
        var li = ul.find('li[value='+indexSelected+']');
        var li2 = ul.find('li[value='+(indexSelected+1)+']');
        li.addClass('search-input-datalist-selected');
        li2.removeClass('search-input-datalist-selected');
        if(indexSelected<0)
            indexSelected = 0;
            }
}

function selectOnAutocomplete()
{
    
}

function loads()
{
    loadFormContentRegister();
    selectRegister();
    linkActionRegister();
    uploadFormRegister();
    loadScrollRegister();
    searchInputRegister();
}
loads();


// bloqueia enter do formulario
$('#filtro-busca').on('keyup keypress', function(e) {
  var code = e.keyCode || e.which;
  if (code === 13) { 
    e.preventDefault();
    return false;
  }
});