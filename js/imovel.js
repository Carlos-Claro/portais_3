function onSubmit(token) {
            formulario.onSubmit(token);
    return false;
}
function videoClick(){
    ifrm = document.createElement("IFRAME");
    ifrm.setAttribute("src", $('.video-data').data('video'));
    ifrm.style.width = "100%";
    ifrm.style.height = "550px";
    ifrm.style.border = "none";
    console.log(ifrm);
    $('#video').html(ifrm);
}
var setModal = {
    set: function(tipo){
        $.getJSON(URL_HTTP + 'get_' + tipo, function(data){
            $('#modal .titulo-modal').html('');
            $('#modal .titulo-modal').html(data.titulo);
            $('#modal .modal-body').html('');
            $('#modal .modal-body').html(data.texto);
        });
    },
}
$(document).ready(function(){
    setTimeout(function(){
        $.getScript("https://www.google.com/recaptcha/api.js", function( data, textStatus, jqxhr ) {
          if (LOCALHOST){
                console.log( data ); // Data returned
              console.log( textStatus ); // Success
              
          }
        });
    },4000);
    setTimeout(function(){
        var image = $('.primeira-image').attr('src');
        $('.primeira-image').attr('src',image.replace('destaque','vitrine'));
    },8000);
});
$(function(){
    $(window).scroll(function(e){
        y = $(this).scrollTop();
        if ( y > 500){
            setTimeout(function(){
                if ( $('.mapa').data('ativo') == 0 ){
                    ifrm = document.createElement("IFRAME");
                    ifrm.setAttribute("src", URL_HTTP + 'get_mapa?id_imovel='+$('.imovel').data('imovel'));
                    ifrm.style.width = "100%";
                    ifrm.style.height = 300+"px";
                    ifrm.style.border = "none";
                    $('.mapa').append(ifrm);
                    $('.mapa').data('ativo','1');
                }
                if ( $('.espaco-imoveis-relacionados').html() == '' ){
                    $('.espaco-imoveis-relacionados').html(' ');
                    $.get(URL_HTTP + 'relacionados',{'id_imovel':$('.imovel').data('imovel'),'empresa':0},function(res){
                        if ( res != '' ){
                            $('.espaco-imoveis-relacionados').html(res);
                        }
                    });
                }
                if ( $('.espaco-imoveis-relacionados-imobiliaria').html() == '' ){
                    $('.espaco-imoveis-relacionados-imobiliaria').html(' ');
                    setTimeout(function(){
                        $.get(URL_HTTP + 'relacionados',{'id_imovel':$('.imovel').data('imovel'),'empresa':1},function(res){
                            $('.espaco-imoveis-relacionados-imobiliaria').html(res);
                        });
                    },1000)
                }
            },1000);
        }
    });
    
    $('.politica').on('click',function(){
        setModal.set('politica');
    });
    $('.termos').on('click',function(){
        setModal.set('termos');
    });
    
    $('.filtro-atalho').on('click',function(){
        console.log('atalho filtro');
        window.location.replace(URI+'?filtro_aberto=1');
    });
    /**
     * valida email do cliente no cadastro pow
     */
    $('.email').on({
        blur : function(){
            var email = $(this).val();
            if ( email.length > 0 )
            {
                $.post(URI + 'consulta_cadastro',{'email':email},{},'json').done(function(data){
                    if ( data )
                    {
                        $('.nome').val(data.nome);
                        $('.fone').val(data.telefone);
                        $('.telefone').val(data.telefone);
                        $('.email').val(email);
                    }
                });
            }
        }
    });
    $('.ver-telefone').on({
        click:function(){
            $('.espaco-telefone').removeClass('hide');
            id = $('.ver-telefone').data('item');
            var url = URI + 'funcoes/get_empresa/' + id;
            $.getJSON(url,function(data){
                $('.espaco-telefone .portlet-body').html('<div class="alert alert-info"><p class="bold">Telefone ' + data.contato_telefone_link + ' fale com ' + data.contato_nome + '.</p><p class="bold"> Não se esqueça de dizer que viu este numero no ' + URI +'</p></div>');
            }).fail(function(){
                swal('','Problemas para adquirir os dados.','error');
            });
            
        }
    });
    
});








$(document).ready(function(){
    $("#carousel-master").swiperight(function() {  
        $(this).carousel('prev');  
    });  
    $("#carousel-master").swipeleft(function() {  
        $(this).carousel('next');  
    }); 
    $("#lightbox").swiperight(function() {  
        $(this).lightbox('lb-prev');  
    });  
    $("#lightbox").swipeleft(function() {  
        $(this).lightbox('lb-next');  
    }); 
    
    
});

var liga_imobiliaria = function(formatted_number, mobile_number) {
    console.log(formatted_number, mobile_number);
    // formatted_number: número a apresentar, no mesmo formato que
    //        o número transmitido a _googWcmGet().
    //        (neste caso, '1-800-123-4567')
    // mobile_number: number formatted for use in a clickable link
    //        com tel:-URI (neste caso, '+18001234567')
    var e = document.getElementById("ligacao");
    e.href = "tel:" + mobile_number;
    e.innerHTML = "";
    e.appendChild(document.createTextNode(formatted_number));
}

/**
 */
$(function(){
    var tem_adwords = $('.tem-adwords');
    var tem_facebook = $('.tem-facebook');
    console.log(tem_adwords);
    if ( tem_facebook.length > 0 )
    {
        console.log('tem-facebook');
        fbq('track', 'ViewContent', {
          value: 0.10,
          currency: 'BRL',
        });
    }
    else
    {
        console.log('nao-tem-facebook');
        
    }
    if ( tem_adwords.length > 0 )
    {
        console.log('tem-adwords');
    }
    else
    {
        console.log('nao-tem-adwords');
    }
    $('.ver-mais-empresa').on('click',function(){
        $('.identificacao-empresa').removeClass('hide').addClass('show');
    });
//    if ( $(window).width() < 991) 
//    {
//        var formulario = $('.formulario').html();
//        $('.formulario').html('');
//        formulario += $('.empresa').html();
//        $('#modal-formulario-xs .modal-body').html(formulario).addClass('formulario');
//    }

    $('.mapa').click(function () {
        $('.mapa').css("pointer-events", "auto");
    });
    /*
     * Aparelhamento do carousel
     */
    $('.carousel').carousel('pause');
    var qtde_thumbs = $('#carousel-master .carousel-indicators li').length;
    var tamanho = qtde_thumbs*102;
    $('#carousel-master .carousel-indicators').css('width',tamanho+'px');
    $('#carousel-master').on('slid.bs.carousel', function () {
        var item_ativo = $('#carousel-master .active').data('ordem');
        switch (true) {
            case ( item_ativo < 3 ):
                $('.espaco-thumbs-lancador').scrollLeft(0);
                break;
            case ( item_ativo > 3 ):
                $('.espaco-thumbs-lancador').scrollLeft(item_ativo*100);
                break;
            default:
                console.log(item_ativo);
                break;
        }
    });
});

var formulario = {
    posicionar: function(modal){
        var formulario = $('.formulario').html();
//        formulario += $('.empresa').html();
        if ( modal ){
            $('.reserva-modal .modal-body').html('');
            $('.reserva-modal .modal-body').html('<div class="formulario">'+formulario+'</div>');
            $('.formulario-original').html('');
        }else{
            $('.formulario-original').html(formulario);
            $('.reserva-modal .modal-body').html('');
        }
    },
    getCampos: function(){
        data = {'erro':[],'post':{}}; 
        $.each(formulario.campos(),function(c,v){
            var valor = $('.' + c).val();
            if ( v.required && valor == '' && valor != undefined )
            {
                data.erro.push(c);
            }
            switch (v.tipo) {
                case 'text':
                    data.post[c] = $('.' + c).val();
                    break;
                case 'checkbox':
                    data.post[c] = $('.' + c + ':checked').val();
                    break;
                case 'checkbox_group':
                    data.post[c] = [];
                    $.each($('.' + c + ':checked'),function(ch,va){
                        data.post[c][ch] = $(va).val();
                    });
            console.log(data);
                    break;
            }
        });
        return data;
    },
    
    onSubmit: function(token){
        data = formulario.getCampos();
        post = data.post;
        post['tipo'] = $('.tipo-contato.active').data('item');
        post['token'] = token;
        post['log'] = $('.imovel').attr('data-log');
        erro = data.erro;
        $('.reserva-modal').modal('hide');
        if ( erro.length > 0 )
        {
            
            swal("Temos campos Obrigatórios!!", "Preencha os campos " + erro.join(', ') + " do formulário e tente novamente.",'error');
            grecaptcha.reset();
        }
        else
        {
            swal({
                title: "",
                text: "Enviando sua mensagem, aguarde alguns segundos...",
                type: "info",
                timer:20,
                showConfirmButton: false,
                showLoaderOnConfirm: true,
            }, function(){
                    var url = URI + 'contato';
                console.log(url,post);
                    $.post(url, post, function(data){
                        if ( data.status )
                        {
                            swal('Parabéns! ','Mensagem enviada com sucesso.','success');
                            window.dataLayer = window.dataLayer || [];
                            dataLayer.push({'event': 'formEnviado'});
                            var tem_adwords = $('.tem-adwords');
                            if ( tem_adwords.length > 0 )
                            {
                                var u = $('.imprimir').attr('href');
                                var url_ = u.replace('print','');
                            }
//                            if ( tem_facebook.length > 0 )
//                            {
//                                fbq('track', 'Lead');
//                            }
                        }
                        else
                        {
                            swal('Erro',data.debugger,'error');
                        }
                        grecaptcha.reset();
                    },'json').fail(function(e){
                        swal('Erro',e.message,'error');
                    });
                });
        }
        $('.form-contato').submit(false);
        
        return false;
    },
    campos: function(){
        var retorno = {}
        retorno.id_empresa = {tipo:'text',required:true};
        retorno.id_imovel={tipo:'text',required:true};
        retorno.local={tipo:'text',required:true};
        retorno.assunto={tipo:'text',required:true};
        retorno.mensagem={tipo:'text',required:true};
        retorno.email={tipo:'text',required:true};
        retorno.nome={tipo:'text',required:true};
        retorno.fone={tipo:'text',required:false};
        retorno.check_telefone={tipo:'checkbox',required:false};
        retorno.check_whatsapp={tipo:'checkbox',required:false};
        retorno.check_email={tipo:'checkbox',required:false};
        retorno.valor_entrada = {tipo:'text',required:false};
        retorno.horario = {tipo:'checkbox_group',required:false};
        retorno.parcelas = {tipo:'checkbox_group',required:false};
        return retorno;
    },
            
};

$(function(){
    rolagem.on();
    console.log('verifica mobile', rolagem.mobile);
    if ( rolagem.mobile === 1 ) 
    {
        formulario.posicionar(true);
    }
    if ( rolagem.mobile === 1 ) 
    {
//        console.log('rolando mobile');
//        rolagem.on();
//        $(document).on('scroll',function(){
//            rolagem.rolando_mobile($(this).scrollTop());
//        });
        
    }
    else
    {
        console.log('rolando');
        //rolagem.on();
        $(document).on('scroll',function(){
          //  rolagem.rolando($(this).scrollTop());
        });
    }
});


var rolagem = {
    primeira:0,
    ultima:0,
    height:300,
    height_parcial:0,
    height_tela:0,
    form_0:0,
    form_1:0,
    mobile:0,
    on: function(){
        rolagem.mobile = $('body').data('is-mobile');
        rolagem.form_0 = $('.form-0').offset().bottom;
        rolagem.form_1 = $('.form-1').offset().top;
        rolagem.primeira = $('.primeira-linha').offset().top;
        rolagem.ultima = $('.ultima-linha').offset().top;
        rolagem.height = $('.formulario').height();
        rolagem.height_tela = window.innerHeight;
        rolagem.height_footer = $('footer').height();
        rolagem.height_parcial = rolagem.height_footer + ( rolagem.height - rolagem.height_tela ) ;
    },
    rolando: function(y){
        if ( y > rolagem.primeira ){
            if (  y  > rolagem.height_parcial ){
        console.log('rolando relative');
                $('.formulario').css({
                    position: 'relative',
                    top: rolagem.height_parcial - 150,
                });
            }
            else{
            console.log('rolando');
                $('.formulario').css({
                    position: 'fixed',
                    top: 100,
                });
            }
        }else{
            console.log('parado');
            $('.formulario').css({
                position: 'relative',
                top: 0,
            });
            
        }
    },
    rolando_mobile: function(y){
        console.log('rolando mobile');
        if ( y > rolagem.form_0 ){
            if ( ( y + rolagem.height_tela )  > rolagem.form_0 ){
                formulario.posicionar(true);
                $('.atalho-xs').addClass('hide').removeClass('show');
            }
            else{
                $('.atalho-xs').addClass('show').removeClass('hide');
                formulario.posicionar(false);
            }
        }else{
                $('.atalho-xs').addClass('show').removeClass('hide');
                formulario.posicionar(false);
        }
    },
};
//function id( el ){
//return document.getElementById( el );
//}
//var sT = 80;
//function rollUp( el ){
//if( el.scrollHeight>=sT ) sT += 80;
//
//el.scrollTop = sT;
//}
//window.onload = function(){
//id('up').onclick = function(){
//	rollUp( id('lista') );
//};
//};