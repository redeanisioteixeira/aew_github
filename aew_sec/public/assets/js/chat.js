var htmTitulo   = document.title;
var msgTitulo   = '';
var tmrTitulo   = '';
var pos         = 0;
var focusPagina = true;
var id_anterior = 0;

var Chat     = '';
var Aviso    = '';
var Contato  = '';
var Alerta   = '';
var Busca    = '';

var tempoChat      = 1000;
var tempoBusca     = 2500;
var tempoContato   = 5000;
var tempoAviso     = 8000;
var tempoAlerta    = 10000;
var contadorCiclo  = false;

var buscaAnt = '';
var filtroAnt = '';

var statusChat = false;
var alerta  = true;
var online  = '';
var bloqueados  = '';
var ativo = false;
var html_iconemotions = '';

if (typeof String.prototype.trim != 'function'){
    String.prototype.trim = function () {
        return this.replace(/^\s+/, '').replace(/\s+$/, '');
    };
}

function checkConnect(ativar)
{
    $.post('/espaco-aberto/chat', {acao: 'online', status: ativar});
}

function verificaAlerta()
{
    var aJson = new Array('recados','colegas','comunidades','albuns','agenda','blog','online');
    var resultJson = '';
    
    $.post('/espaco-aberto/chat', {acao: 'alertas'}, function(data)
    {
        var json = eval('(' + data + ')');
        var alerta = false;

        for (i=0; i<7; i++)
        {
            var contador = '';
            var visualizar = false;

            contador = contador.concat('contador', i+1);
            resultJson = aJson[i];

            if(parseInt(json[resultJson])>0)
            {
                var contadorAtual = $('span#' + contador).text();
                alerta = (parseInt(contadorAtual) != parseInt(json[resultJson]) && resultJson != 'online' ? true : alerta);
                $('span#' + contador).removeClass('desativado');
                visualizar = true;
            }

            if(visualizar == false){
                $('span#' + contador).addClass('desativado');
            }

            $('span#' + contador).text(json[resultJson]);
        }

        if(alerta == true)
        {
            if (contadorCiclo == false)
            {
                var beepOne = $("audio#beep-chat-one")[0];
                beepOne.play();
            }
        }

        contadorCiclo = true;
    });
}

function scrollMSG(desativar){
    if(desativar==true){
        document.title = htmTitulo;
        clearTimeout(tmrTitulo);
        return;
    }

    document.title = msgTitulo.substring(pos, msgTitulo.length) + msgTitulo.substring(0, pos);
    pos++;
    if (pos > msgTitulo.length){
        pos = 0;
    }
    tmrTitulo = window.setTimeout("scrollMSG()",80);
}

function acaoConfigurar(id, acao){
    var nome  = $('div#jan_'+id+' span').html();

    if(acao == 0){
        $('div#jan_'+id+' div.configurar-mensagem').remove();
        $('div#jan_'+id+' #corpo').css('display','block');
        return;
    }

    if(acao == 1){
        $.post('/espaco-aberto/chat', {acao: 'bloquear', usuario_de: id});
        acaoConfigurar(id,0);
        acaoConfigurar(id,2);
    }

    if(acao == 2){
        var html_desbloquear = '<div id="'+id+' "class="configurar-mensagem text-center"><span>Deseja desbloquear a <strong>'+nome+'</strong>?</span><p>Se desbloquear, você poderá receber as mensagens através do chat.</p><div class="opcao-botao"><button id="bloquear" onclick="acaoConfigurar('+id+',3);" class="fa fa-check-circle-o"> desbloquear</button></div></div>';
        $('div#jan_'+id+' #corpo').css('display','none');
        $('div#jan_'+id).append(html_desbloquear);
    }

    if(acao == 3){
        $.post('/espaco-aberto/chat', {acao: 'desbloquear', usuario_de: id});
        acaoConfigurar(id,0);
    }
}

jQuery(document).ready(function($) //No Conflict
{
    var perfil  = new Array();	
    var janelas = new Array();
    var antes   = -1;
    var depois  = 0;

    function ativarChat(ativar){
        var statusAlerta = $('input[name=alerta]').val();
        
        if(ativar == false){

            window.clearInterval(Chat);
            window.clearInterval(Aviso);
            window.clearInterval(Contato);
            
            if(statusAlerta == false){
                window.clearInterval(Alerta);
            } else {
                Alerta = window.setInterval(function(){
                            verificaAlerta();
                        },tempoAlerta);
            }

        } else {

            statusContatos(true);
            verificaAlerta();
            
            Chat = window.setInterval(function(){
                        if(antes != depois){
                            verificar();
                        }
                    }, tempoChat);

            Aviso = window.setInterval(function(){
                        verificarAviso();
                    }, tempoAviso);

            Contato = window.setInterval(function(){
                        statusContatos();
                    }, tempoContato);

            Alerta = window.setInterval(function(){
                        verificaAlerta();
                    },tempoAlerta);
        }
        
        $.post('/espaco-aberto/chat', {acao: 'ativar-chat', status: (ativar === true ? 1 : 0)});

    } /*--- fim ativar/chat ---*/ 

    function add_janelas(id, nome){
        var html_configurar = '<div id="'+id+'" class="configurar"><a id="bloquear" class="fa fa-ban"> bloquear colega</a></div>';
        var html_add = '<div class="janela panel panel-default" id="jan_'+id+'"><div id="'+id+'" class="topo-chat panel-heading padding-none"><span title="minimizar">'+nome+'</span><a id="fechar" title="fechar" class="opcao-chat fa fa-close"></a><a id="configurar" title="configurações" class="opcao-chat fa fa-cogs"></a></div><div id="corpo" class="corpo-chat">'+html_configurar+'<div class="mensagens"><div id="area_'+id+'" class="area_emoticons"></div><ul id="ul'+id+'" class="listar"></ul><div id="aviso_'+id+'" class="aviso desativado"></div></div><textarea class="mensagem-chat" id="mensagem_'+id+'" maxlength="500" rows="1" autofocus></textarea><a id="'+id+'" class="chamada_icones fa fa-smile-o"></a></div></div>';
        var bloco = $('ul.contatos li#'+id).attr('class');

        $('#janelas').append(html_add);
        $('#jan_'+id+' textarea').focus();

        if(bloco.search('bloqueado')!=-1){
            acaoConfigurar(id,2);
        }
    } /*--- fim add_janelas ---*/ 

    function abrir_janelas(x){
        $('#contatos ul li a.contato').each(function(){
            var link = $(this);
            var id = link.attr('id');

            if(id == x){
                link.click();
            }
        });
    } /*--- fim abrir_janelas ---*/ 

    function minimizaJanela(id){
        $.post('/espaco-aberto/chat',{acao: 'lidos', usuario_de: id},'jSON');
        scrollMSG(true);
    } /*--- fim minimizaJanela ---*/ 

    function carregarBusca(){
        var filtro_busca = $('textarea[name=filtrar_busca]').val();
        var campo = $('textarea[name=filtrar_busca]');
        var filtro_busca = filtro_busca.trim();
        buscaAnt = buscaAnt.trim();

        if(buscaAnt == filtro_busca){
            return;
        }

        campo.addClass('carregando');
        $.post('/espaco-aberto/pesquisa', {filtro: filtro_busca},function(resultado){
            $('#resultado_busca').css('display','none');
            $('#resultado_busca').html(resultado);
            $('#resultado_busca').fadeIn();
            campo.removeClass('carregando');
        }, 'jSON');
        buscaAnt = filtro_busca;
    } /*--- fim carregarBusca ---*/ 

    function statusContatos(atualiza){
        var filtro_usuario = $('textarea[name=filtrar_usuario]').val();

        if(filtro_usuario == undefined){
            var filtro_usuario = filtro_usuario.trim();
        }

        beforeSend: antes = depois;

        if(filtroAnt != filtro_usuario){
            $('textarea[name=filtrar_usuario]').addClass('carregando');
            filtroAnt = filtro_usuario;
        }

        $.post('/espaco-aberto/chat', {acao: 'contatos', filtro: filtro_usuario}, function(data){
            var conteudo_atual = $('ul.contatos li.contatos').length;

            if(data.bloqueados != bloqueados || data.online != online || data.quantidade != conteudo_atual || atualiza == true){
                $('textarea[name=filtrar_usuario]').removeClass('carregando');
                $('ul.contatos').addClass('desativado');
                $('ul.contatos').html(data.contatos).fadeIn();
                
                online = data.online;
                bloqueados = data.bloqueados;

                var n = janelas.length;
                for(i=0; i<n; i++){
                    var posicao = online.search(janelas[i]);

                    if(posicao==-1){
                        $('#jan_'+janelas[i]).addClass('offline');
                    } else {
                        $('#jan_'+janelas[i]).removeClass('offline');
                    }
                }

                div_perfil = data.perfil;
                perfil = new Array();
                for(id in div_perfil){
                    perfil[id] = div_perfil[id];
                }
            }
            depois += 1;
        },'jSON');
    } /*--- fim statusContatos ---*/ 

    function carregarEmoticons(id){
        //if(html_iconemotions == ''){
            $.post('/espaco-aberto/chat', {acao: 'emoticons', janela: id}, function(data){
                html_iconemotions = data;
            },'jSON');
        //}
        $('#area_'+id).html(html_iconemotions);
    } /*--- fim carregarEmoticons ---*/ 

    function verificarAviso(){
        if(janelas != ''){
            $.post('/espaco-aberto/chat', {acao: 'alertar', ids: janelas}, function(data){
                var aviso = data.alertas;
                for(id in aviso){
                    if(aviso[id] == true){
                        $('#aviso_'+id).removeClass('desativado');
                    } else {
                        $('#aviso_'+id).addClass('desativado');
                    }
                }
            },'JSON');
        } 
    } /*--- fim verificar Aviso ---*/ 

    function atualizarAviso(to, acao_status){
        $.post('/espaco-aberto/chat',{
            acao: 'avisar',
            para: to,
            processo: acao_status
        },'jSON');
    } /*--- fim atualizarAviso ---*/ 

    function verificar(){
        var mensagens = new Array();

        if(janelas.length>0){
            for(j = 0; j < janelas.length; j++){
                mensagens[j] = 0;
                if($('ul#ul'+janelas[j]).html() != ''){
                    mensagens[j] = $('ul#ul'+janelas[j]+' li:last-child').attr('id');
                }
            }
        }

        beforeSend: antes = depois;
        $.post('/espaco-aberto/chat', {acao: 'verificar', ids: janelas, idm: mensagens}, function(data){
            var arr = data.nao_lidos;
            depois += 1;
            
            if(arr != ''){
                for(i in arr){
                    var janela_aberta = false;

                    for(j = 0; j < janelas.length; j++){
                        if(janelas[j] == i){
                            janela_aberta = true;
                        }
                    }

                    if(janela_aberta == false){ 
                        abrir_janelas(i);
                    }

                    var topo = $('#jan_'+i).children();
                    
                    if(topo.attr('class') != undefined){
                        
                        if(topo.attr('class').search('fixar') > 0){
                            topo.addClass('notificar');
                            if(topo.html().search('contador_notificar')==-1){
                                topo.append('<span class="contador_notificar contador_notificar'+i+'"></span>');
                            }
                            $('.contador_notificar'+i).text(arr[i]);
                        }
                    }
                }

                if(alerta == true){
                    var beepOne = $("#beep-chat-one")[0];
                    beepOne.play();
                    alerta = false;

                    /*--- visualiza no navegador scroll ---*/
                    var nome = topo.children('span').text();
                    var nome = nome.substring(0,nome.search(' '));

                    msgTitulo = htmTitulo+' ('+nome+' disse...):::';
                    scrollMSG();
                }
            }

            if(janelas.length > 0){
                var mens = data.mensagens;
                if(mens != ''){
                    qtde_mens = data.qtd_mensagens;
                    tempo_mens = data.tempo;

                    for(i in mens){
                        if(qtde_mens[i]>0){
                            $('#jan_'+i+' ul.listar li:last-child').removeAttr('id');
                            $('#jan_'+i+' ul.listar').append(mens[i]);
                            $('#jan_'+i+' ul.listar li:last-child').attr('id',tempo_mens[i]);
                            $('#jan_'+i+' ul.listar').scrollTop(10000);
                            
                            atualizarAviso(i,'');
                            verificarAviso();
                        }
                    }
                }
            }
        },'jSON');
    } /*--- fim verificar() ---*/ 

    $('div#aviso-chat-desativado').delay(8000).fadeOut(3000,function(){
        $(this).remove();
    });

    $('#botao-chat').click(function() {
        var $righty = $('#area-chat');
        var beepOne = $("#beep-chat-two")[0];

        $righty.animate({right: (parseInt($righty.css('right'),10) == 0 ? $righty.outerWidth()*-1 : 0)+'px'});

        $righty.removeClass();

        if(parseInt($righty.css('right')) == 0){
            $righty.addClass('ativar');
            $('#botao-chat').addClass('abrir-chat');
            $('#botao-chat').attr('title','abrir chat');
            janelas = new Array();			
            $('#janelas').children().remove();

            ativarChat(false);

        } else {
            $righty.addClass('shadow-left desativar');
            $('#botao-chat').removeClass('abrir-chat');
            $('#botao-chat').attr('title','fechar chat');
            $('#aviso-chat-desativado').remove();
            
            statusContatos();
            ativarChat(true);
            
            beepOne.play();

        }

    }); /*--- fim #botao-chat ---*/ 

    $('body').on('click', '.opcao-perfil', function(){
        var id = $(this).attr('id');
        var link = $(this).attr('link');
        link = '/espaco-aberto/'+link.concat('/listar/usuario/',id);
        location.href = link;
    }); /*--- fim body(opcao-perfil) ---*/ 

    $('body').on('click', '.perfil h6', function(){
        var id = $(this).attr('id');
        link = '/espaco-aberto/perfil/feed/usuario/'+id;
        location.href = link;
    }); /*--- fim body(perfil-h6) ---*/ 

    $('body').on('click', 'a.comecar', function(){
        var id = $(this).attr('id');
        var nome = $(this).attr('nome');
        var posicao = online.search(id);

        if(posicao==-1){
            return;
        }

        if(janelas.length>3){
            var id_primeiro = janelas[0];
            $('#jan_'+id_primeiro).remove();
            $('#contatos a#'+id_primeiro).addClass('comecar');
		
            var n = janelas.length;
            for(i = 0; i < n; i++){
                if(janelas[i] != undefined){
                    if(janelas[i] == id_primeiro){
                        delete janelas[i];
                    }
                }
            }

            var beepOne = $("#beep-chat-one")[0];
            beepOne.play();
        }

        for(var i = 0; i < janelas.length; i++){
            if(janelas[i] == undefined){
                janelas.splice(i, 1);
                i--;
            }
        };

        var n = janelas.length;
        var existe = false;
        for(i=0;i<n; i++){
            if(janelas[i] == id){
                existe = true;
            }
        }

        if(existe == true){
            return;
        }

        janelas.push(id);
        add_janelas(id, nome);			

        $(this).removeClass('comecar');
        return false;
    }); /*--- fim body(a.comecar) ---*/ 
	
    $('body').on('click', 'a#fechar', function(){
        var id = $(this).parent().attr('id');
        var parent = $(this).parent().parent().remove();

        var posicao = online.search(id);
        if(posicao>-1){
            $('#contatos a#'+id+'').addClass('comecar');
        }

        var n = janelas.length;
        for(i = 0; i < n; i++){
            if(janelas[i] != undefined){
                if(janelas[i] == id){
                    delete janelas[i];
                }
            }
        }
        minimizaJanela(id);
    }); /*--- fim body(a#fechar) ---*/ 

    $('body').on('click', 'a#configurar', function(){
        var pai = $(this).parent();
        var id = pai.attr('id');
        var bloco = $('div#jan_'+id+' #corpo');
        var heighty = parseInt(bloco.children('.configurar').css('height')) <= 1 ? '30px' : '0px';
        var html_mensagem = $('div#jan_'+id+' #corpo div.configurar-mensagem');

        if(html_mensagem.html() != undefined){
            return;
        }

        bloco.children('.configurar').animate({height:heighty});
    }); /*--- fim body(a#configurar) ---*/ 

    $('body').on('click', 'a#bloquear', function(){
        var pai = $(this).parent();
        var id = pai.attr('id');
        var nome  = $('div#jan_'+id+' span').html();

        var html_bloquear = '<div id="'+id+' "class="configurar-mensagem text-center"><span>Deseja bloquear a <strong>'+nome+'</strong>?</span><p>Se bloquear, você não receberá mais mensagens através do chat.</p><div class="opcao-botao"><button id="bloquear" onclick="acaoConfigurar('+id+',1);" class="fa fa-ban"> bloquear</button><button id="cancelar" onclick="acaoConfigurar('+id+',0);" class="fa fa-close"> cancelar</button></div></div>';

        $('div#jan_'+id+' #corpo').css('display','none');
        $('div#jan_'+id).append(html_bloquear);
    }); /*--- fim body(a#bloquear) ---*/ 

    $('body').on('click', 'ul.listar', function(){
        var id = $(this).attr('id');
        $('#area_'+id).children().remove();
    }); /*--- fim body(ul.listar) ---*/ 
        
    $('body').on('mouseover', 'ul.contatos li', function(){
        var id = $(this).attr('id');
        var offset = $(this).position();
        var offset = offset.top-10;

        if(id_anterior == id) 
            return;

        $('div.perfil').remove();
        $(this).prepend(perfil[id]);
        $('div.perfil').css('top',offset+'px');

        $('div.perfil div.quadro').css('display','none');
        $('#perfil_'+id+' div.quadro').fadeIn();

        id_anterior = id;
    }); /*--- fim body(ul.contatos li) ---*/ 

    $('body').on('click', '.topo-chat span', function(){
        var pai = $(this).parent().parent();
        var isto = $(this).parent();
        var id = isto.attr('id');

        if(pai.children('#corpo').is(':hidden')){
            $(this).attr('title','minimizar');
            pai.removeClass('fixar');
            isto.removeClass('fixar');
            isto.removeClass('notificar');

            if(isto.html().search('contador_notificar')>0){
                    $('.contador_notificar'+id).remove();
            }

            $('#jan_'+id+' a#configurar').css('visibility','visible');
            $('#jan_'+id+' ul.listar').scrollTop(10000);
            $('#jan_'+id+' textarea').focus();

            alerta = true;
        }else{
            $(this).removeAttr('title');
            pai.addClass('fixar');
            isto.addClass('fixar');
            $('#area_'+id).children().remove();
            minimizaJanela(id);
            $('#jan_'+id+' a#configurar').css('visibility','hidden');
            alerta = true;
        }
        pai.children('#corpo').toggle();
    }); /*--- fim body(.topo-chat span) ---*/ 
	
    $('body').on('keydown', '.mensagem-chat', function(e){
        var mensagem = $(this).val();
        var to = $(this).attr('id');
        var to = to.replace('mensagem_','');

        if(e.keyCode == 13 && mensagem != ''){
            scrollMSG(true);
            beforeSend: antes = depois;
            $.post('/espaco-aberto/chat',{
                acao: 'inserir',
                mensagem: mensagem,
                para: to
            },function(){
                depois += 1;
            },'jSON');
        }
    }) /*--- fim body(.mensagem-chat) ---*/ 

    $('body').on('keyup', '.mensagem-chat', function(e){
        var to = $(this).attr('id');
        var to = to.replace('mensagem_','');

        if(e.keyCode == 13){
            $(this).val('');
        }

        var mensagem = $(this).val();
        if(mensagem != ''){
            atualizarAviso(to,'A');
        } else {
            atualizarAviso(to,'');
        }

    }) /*--- fim body(.mensagem-chat) ---*/ 

    $('body').on('click', '.mensagem-chat', function(){
        var id = $(this).attr('id');
        var id = id.replace('mensagem_','');
        $('#area_'+id).children().remove();
    }) /*--- fim body(.mensagem-chat) ---*/ 

    $('body').on('keydown', '.filtrar_busca', function(e){
        if(e.keyCode == 13 || (e.keyCode == 32 && $('textarea[name=filtrar_busca]').val() == '')){
            return false;
        }
    }) /*--- fim body(.mensagem-chat) ---*/ 

    $('body').on('keyup', '.filtrar_busca', function(e){
        var filtro_busca = $(this).val();
        filtro_busca = filtro_busca.trim();

        if(e.keyCode == 13 || (e.keyCode == 32 && $('textarea[name=filtrar_busca]').val() == '')){
            return false;
        }

        if(filtro_busca != ''){
            if(ativo == false){
                Busca = window.setInterval(function(){carregarBusca()},tempoBusca);
                ativo = true;	
            }
        } else {
            ativo = false;
            window.clearInterval(Busca);
            $('#resultado_busca').children().remove();
            $('#resultado_busca').css('display','none');			
            $('textarea[name=filtrar_busca]').val('');
        }
    }) /*--- fim body(.filtrar_busca) ---*/ 

    $('body').on('click', '#resultado_busca a', function(){
        var url = '';
        var id = $(this).attr('id');
        var idcomunidade = $(this).attr('comunidade');
        var idusuario    = $(this).attr('usuario');

        switch ($(this).attr('class'))
        {
            case "colega":
                url = '/espaco-aberto/perfil/feed/usuario/'+id;
                break;

            case "comunidade":
                url = '/espaco-aberto/comunidade/exibir/comunidade/'+id;
                break;

            case "colega-blog":
                url = '/espaco-aberto/blog/exibir/usuario/'+idusuario+'/id/'+id;
                break;

            case "comunidade-blog":
                url = '/espaco-aberto/blog/exibir/comunidade/'+idcomunidade+'/id/'+id;
                break;

            case "comunidade-forum":
                url = '/espaco-aberto/forum/exibir/comunidade/'+idcomunidade+'/id/'+id;
                break;

            case "conteudo-digital":
                url = '/conteudos-digitais/conteudo/exibir/id/'+id;
                break;

            case "ambiente-apoio":
                url = '/ambientes-de-apoio/ambiente/exibir/id/'+id;
                break;
        }

        location.href = 'http://'+location.hostname+url;
    }) /*--- fim body(#resultado_busca a) ---*/ 

    $('body').on('click', 'a.emoticon', function(){
        var idicon = $(this).attr('id');
        var id = $(this).parent().parent().parent().parent().parent().attr('id');
        if(id == undefined){
            return;
        }

        $('#'+id).children().remove();
        id = id.replace('area_','');
        var mensagem = $('#mensagem_'+id).val();
        if(mensagem.length == 0){
            mensagem = idicon; 			
        } else {
            mensagem = mensagem + ' '+ idicon;		
        }

        mensagem = mensagem + ' ';
        $('#mensagem_'+id).focus().val(mensagem);
    }) /*--- fim body(a.emoticon) ---*/

    $('body').on('click', 'a.chamada_icones', function()
    {
        var id = $(this).attr('id');
        if($(this).attr('class').search('aberto')>0)
        {
            $('#area_'+id).children().remove();
            $(this).removeClass('aberto');
        } 
        else 
        {
            carregarEmoticons(id);
            $(this).addClass('aberto');
        }
    })  /*--- fim body(a.chamada_icones) ---*/

    $('body').on('click', '.mais_icones', function()
    {
        var id = $(this).attr('id');
        var id = id.replace('mais_icones_','');
        if($(this).attr('class').search('menos')>-1)
        {
            $('a#mais_icones_'+id).text('mais');
            $('a#mais_icones_'+id).removeClass('menos');
            $('table#emoticons_'+id).removeClass('aberto');
            $('table#emoticons_'+id+' tr.final').removeClass('aberto');
            $('table#emoticons_'+id+' tr.outros').css('display','none');
        } 
        else 
        {
            $('a#mais_icones_'+id).text('menos');
            $('a#mais_icones_'+id).addClass('menos');
            $('table#emoticons_'+id).addClass('aberto');
            $('table#emoticons_'+id+' tr.final').addClass('aberto');
            $('table#emoticons_'+id+' tr.outros').css('display','table-row');
        }
    }) /*--- fim body(.mais_icones) ---*/

    $('div#contatos ul.contatos').mouseover(function()
    {
        $('div#contatos ul.contatos').css('overflow-y','auto');
    }); /*--- fim div#contatos ul.contatos ---*/

    $('div#contatos ul.contatos').mouseout(function(){
        $('div#contatos ul.contatos').css('overflow-y','hidden');
    });

    $('body').on('click', 'a[name=alerta]', function()
    {
        var idalerta = $(this).attr('idalerta');
        
        $.post('/espaco-aberto/chat', {acao: 'alertas', id: idalerta });
    });
    
    var statusChat = $('#area-chat').attr('class');
    
    if(statusChat == undefined){
        statusChat = false;
    } else {
        statusChat = (statusChat.search('desativar') != -1  ? true : false);
    }

    //if(statusChat == true){
    //    statusContatos(true);
    //}

    carregarEmoticons();
    ativarChat(statusChat);

    $(window).on('blur', function(){focusPagina = false;});
    $(window).on('load', function(){checkConnect(1)});
    $(window).on('beforeunload', function(){checkConnect(0)});
    $(window).on('focus', function(){
        if(focusPagina == false){
            scrollMSG(true);
            focusPagina = true;
        }
    });

}) /*--- $(document).ready() --- */