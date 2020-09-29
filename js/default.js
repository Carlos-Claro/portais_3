var base_url = '';
var base = new String(document.baseURI);
var n = base.indexOf("localhost");
var n2 = base.indexOf("192.168");
var n3 = base.indexOf("201.16");
var n4 = base.indexOf("189.4");
var n5 = base.indexOf("powempresas");
var LOCALHOST = false;
if ( ( n ) >= 0 || ( n2 ) >= 0 || ( n3 ) >= 0 || ( n4 ) >= 0 || ( n5 ) >= 0 )
{
    LOCALHOST = true;
}
base_url = $('body').attr('data_url');
var URL_HTTP = base_url;
var URI = base_url;
var URL_ADMIN = base_url;

$(function(){
    fotos.inicia();
    $(document).on('click','.carousel-control',function(){
        fotos.set_images($(this));
    });
    $('.busca-id').on('blur',function(){
        window.location.href = URL_HTTP + '?id=' + $(this).val();
    });
});
var fotos = {
    inicia: function(){
//        console.log('fotos.inicia');
//        console.log(LOCALHOST);
//        if ( ! LOCALHOST )
//        {
            $.each($('.carousel-inner img'), function(k,v){
                img = $(v).data('src');
                $(v).attr('src',img);
            });
//        }
    },
    lista: {},
    get_lista : function(){
        return fotos.lista;
    },
    set_lista : function( item ){
        var lista = {};
        if ( this.lista.length > 0 )
        {
            this.lista.push(item);
        }
        else
        {
            this.lista = item;
        }
    },
    set_images: function( campo, lado ){
        var item = campo.attr('data-item');
        var qtde_montado = $('.item-' + item + ' .carousel-inner .item').length;
        if ( qtde_montado === 1 )
        {
            var item_lista = fotos.verifica_lista(item);
            var itens = this.monta_lista(item_lista, item);
        }
        if ( lado === undefined )
        {
            var lado = campo.attr('data-slide');
        }
        $('.item-' + item + '.carousel').carousel(lado);
        $('.item-' + item + '.carousel').carousel('pause');
    },
    verifica_lista: function( item ){
        $.each(this.get_lista(),function(k,v){
            if ( v.id == item )
            {
                var retorno = v;
            }
        });
        if ( retorno == undefined )
        {
            var images = JSON.parse(decodeURIComponent($('.item-' + item).attr('data-images')));
            var array_item = {id : item, itens : images};
            this.set_lista(array_item);
            var retorno = array_item;
        }
        
        return retorno;
    },
    monta_lista: function( itens, item ){
        var retorno = '';
        var contador = 0;
        $.each(itens.itens,function(k,v){
            if ( contador > 0 )
            {
                retorno += fotos.item(v);
            }
            contador ++;
        });
        $('.item-' + item + ' .carousel-inner').append(retorno);
        
        
    },
    item: function(item){
//        console.log(item);
        var retorno = '<div data-atual="0" class="item"><div rel="nofollow" title="' + item.titulo + '" class="pull-left link-img center-block"><center><img class="img-responsive" alt="' + item.titulo + '" src="'+item.arquivo_local+'" itemprop="image"></center></div></div>';
        return retorno;
    },
};

//   $(document).ready(function($) {
//        console.log('preloader');
//        var Body = $('body');
//        Body.addClass('preloader-site');
//    });
//
//    $(function(){
//        console.log("to aqui");
//        $(window).on('load',function() {
//                console.log('preloader down');
//                $('.preloader-wrapper').fadeOut();
//                $('body').removeClass('preloader-site');
//        });
//    });
    
   $(function(){

        $('#links-importantes').on('click', function(){
         $('.links-importantes').toggle();
        });
         $('#imoveis-menu').on('click', function(){
         $('.imoveis-menu').toggle();
        });
    });