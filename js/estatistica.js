
$(document).ready(function(){
        estatistica.set();
});

$(function(){
    $('.ver-telefone-celular').on({
        click :  function(){
                data = {};
                data.imovel = $('.imovel').attr('data-imovel');
                data.tipo = 'ligacao_celular';
                data.origem = $('.imovel').attr('data-log');
                var url_t =  URI + 'set_log';
                $.post( url_t, data, function(data_){ console.log(data_); } );
            }
    });
    $('.ver-telefone-whats').on({
        click :  function(){
                data = {};
                data.imovel = $('.imovel').attr('data-imovel');
                data.tipo = 'ligacao_whatsapp';
                data.origem = $('.imovel').attr('data-log');
                var url_t =  URI + 'set_log';
                $.post( url_t, data, function(data_){ console.log(data_); } );
                ga('send', 'event', 'Botão WhatsApp (Curitiba)', 'clique');
            }
    });
    $('.ver-telefone-whats-lista').on({
        click :  function(){
                data = {};
                data.imovel = $(this).attr('data-item');
                data.tipo = 'ligacao_whatsapp';
                data.origem = $(this).attr('data-log');
                var url_t =  URI + 'set_log';
                $.post( url_t, data, function(data_){ console.log(data_); } );
                ga('send', 'event', 'Botão WhatsApp (Curitiba)', 'clique');
            }
    });
    $('.compartilha-telefone-whats-lista').on({
        click :  function(){
                data = {};
                data.imovel = $(this).attr('data-item');
                data.tipo = 'compartilhado_whatsapp';
                data.origem = $(this).attr('data-log');
                var url_t =  URI + 'set_log';
                $.post( url_t, data, function(data_){ console.log(data_); } );
                ga('send', 'event', 'Botão WhatsApp (Curitiba)', 'clique');
            }
    });
    $('.ver-telefone-hover, .ver-telefone').on({
        click :  function(){
                data = {};
                data.imovel = $('.imovel').attr('data-imovel');
                data.tipo = 'ligacao';
                data.origem = $('.imovel').attr('data-log');
                var url_t =  URI + 'set_log';
                $.post( url_t, data, function(data_){ console.log(data_); } );
                
            }
    });
});

var estatistica = {
    set_tipo: function(tipo){
        
    },
    set_empresa: function(id, local, tipo){
        if ( local === undefined){
            local = 22;
        }
        if ( tipo === undefined){
            tipo = 'lista_imobiliaria';
        }
        var url = URI + 'log/empresa/';
        var array_ = {};
        array_.id_empresa = id;
        array_.origem = local;
        array_.tipo = tipo;
        $.post(url,array_,function(data){console.log(data)});
    },
    set: function(){
        setTimeout(function(){
            var imoveis = $('.item-vertical, .item-grid');
            var url = URI + 'set_log';
            if ( imoveis.length > 0 )
            {
                $.each($('.item-vertical, .item-grid'), function(c,v){
                    if ( $(this).attr('data-salvo') === undefined )
                    {
                        var array_ = {}
                        array_.imovel = $(this).attr('data-item');
                        array_.origem = $(this).attr('data-origem');
                        array_.tipo = 'views';
                        $.post(url,array_,function(data){});
                        $(this).attr('data-salvo',1);
                    }
                });
            }
            var imovel = $('.imovel');
            if ( imovel.length > 0 )
            {
                    var array_ = {}
                    array_.imovel = $(imovel).attr('data-imovel');
                    array_.origem = $(imovel).attr('data-log');
                    array_.tipo = 'clicks';
                    $.post(url,array_,function(data){console.log(data)});
                
            }
        },1000);
    },
};
