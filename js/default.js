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
    
    setTimeout(function(){
        $.getScript(URL_HTTP + "plugins/font-awesome/css/font-awesome.min.css", function( data, textStatus, jqxhr ) {
          if (LOCALHOST){
                console.log( data ); // Data returned
              console.log( textStatus ); // Success
              
          }
        });
    },4000);
    fotos.inicia(3);
    $(".item-vertical .carousel").swiperight(function() {  
        $(this).carousel('prev');  
        fotos.set_images($(this), 'prev');
    });  
    $(".item-vertical .carousel").swipeleft(function() {  
        $(this).carousel('next');  
        fotos.set_images($(this), 'next');
    }); 
    $(".item-grid .carousel").swiperight(function() {  
        $(this).carousel('prev');  
        fotos.set_images($(this), 'prev');
    });  
    $(".item-grid .carousel").swipeleft(function() {  
        $(this).carousel('next');  
        fotos.set_images($(this), 'next');
    }); 
    $(document).on('click','.carousel-control',function(){
        fotos.set_images($(this));
    });
    $('.busca-id').on('blur',function(){
        window.location.href = URL_HTTP + '?id=' + $(this).val();
    });
    $(window).scroll(function(e){
        y = $(this).scrollTop();
        fotos.inicia((y/100)+2);
    });
});
var fotos = {
    url: URL_HTTP + 'get_images_por_imovel/',
    inicia: function(x){
        var y = 0; 
        $.each($('.carousel-inner img'), function(k,v){
            if (y < x){
                var preloadLink = document.createElement("link");
                img = $(v).data('src');
                    preloadLink.href = img;
                    preloadLink.rel = "preload";
                    preloadLink.as = "image";
                    document.head.appendChild(preloadLink);
                    $(v).attr('src',img);
            }
            y = y + 1;
        });
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
    set_foto_atual: function(a){
        $('.foto-atual').html(a);
    },
    set_images: function( campo, lado ){
        var atual = parseInt($('.foto-atual').html());
        var total = parseInt($('.foto-qtde').html());
        var slide = campo.data('slide');
        console.log(atual,slide, total);
        if (slide == 'next'){
            atual = atual + 1;
            if ( atual > total ){
                atual = 1
            }
        }else{
            atual = atual - 1;
            if ( atual < 1 ){
                atual = total;
            }
        }
        var item = campo.attr('data-item');
        var qtde_montado = $('.item-' + item + ' .carousel-inner .item').length;
        if ( qtde_montado === 1 )
        {
            $.getJSON(this.url + item,function(images){
                var item_lista = {id : item, itens : images};
//            var item_lista = fotos.verifica_lista(item);
                var itens = fotos.monta_lista(item_lista, item);
            });
        }
        setTimeout(function(){
            if ( lado === undefined )
            {
                var lado = campo.attr('data-slide');
            }
            $('.item-' + item + '.carousel').carousel(lado);
            $('.item-' + item + '.carousel').carousel('pause');
            $('.foto-atual').html(atual);
            $('.item-' + item + '.carousel-control').data('atual',atual);
        },1000);
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
//            var images = JSON.parse(decodeURIComponent($('.item-' + item).attr('data-images'))); // troca por assync
//                var array_item = {id : item, itens : images};
//                this.set_lista(array_item);
//                var retorno = array_item;
            $.getJSON(fotos.url + item,function(images){
                console.log(images);
                var array_item = {id : item, itens : images};
                this.set_lista(array_item);
                var retorno = array_item;
                return retorno;
            });
        }
        
    },
    get_images: function(item){
            return data;
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
        var retorno = '<div data-atual="0" class="item"><div rel="nofollow" title="' + item.titulo + '" class="pull-left link-img center-block"><center><img class="img-responsive" alt="' + item.titulo + '" src="'+(item.arquivo_local).replace('destaque','vitrine')+'" itemprop="image"></center></div></div>';
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
        $('#links-importantes').on('mouseover', function(){
           $.get(URL_HTTP + 'menu_principais',function(data){
                 $('.links-importantes').toggle();
               $('.links-importantes').html('');
               $('.links-importantes').html(data);
                 $('.links-importantes').toggle();
           });
            
        });
       $('#imoveis-menu').on('click', function(){
            $('.imoveis-menu').toggle();
       });
       $('#imoveis-menu').on('mouseover', function(){
           $.get(URL_HTTP + 'menu',function(data){
                 $('.imoveis-menu').toggle();
               $('.imoveis-menu .row').html('');
               $('.imoveis-menu .row').html(data);
                 $('.imoveis-menu').toggle();
           });
        });
       
        
    });