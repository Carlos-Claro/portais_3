
$(document).ready(function(){
    publicidade.set_publicidade();
//    publicidade.set_publicidade_home();
});
$(function(){
    $('.img-flash').mouseup(function(){
        var item = $(this);
        publicidade.click(item, '_parent');
    });
    
    $(document).on('click', '.img-publicidade',function(){
        
        var item = $(this);
        publicidade.click(item, '_blank');
    
    });
    $(document).on('click', '.publicidade-inicial',function(){
        
        var item = $(this);
        publicidade.click(item, '_blank');
    
    });
    
    $('.publicidade-contato').on('click', function(){
        var item = $(this);
        publicidade.get_item(item, 'contato');
    });
    $(document).on('click', '.publicidade-inicial',function(){
        //publicidade.salva_view(item, '_blank');
    
    });
    
});

var publicidade = {
    salva_click: function(item){
        var url = URL_HTTP + 'publicidade/salva_click/' + item ;
        $.getJSON(url, function(data){
            console.log(data);
        });
    },
    salva_view: function(item){
        var url = URL_HTTP + 'publicidade/salva_view/' + item ;
        $.getJSON(url, function(data){
            console.log(data);
        });
    },
    set_view: function(){
        var item = new Array;
        $('.resultado .img-publicidade').each(function(a){
            item = $(this).attr('data-item');
            var salvo = $('.campanha-' + item).attr('data-salvo');
            //console.log(salvo);
            if ( salvo === undefined )
            {
                publicidade.salva_view(item);
                $('.campanha-' + item).attr('data-salvo',1);
            }
            
        });
    },
    click: function(item, tipo){
        console.log(item.attr('data-item'));
        if ( item.attr('data-item') !== "200" )
        {
            publicidade.salva_click(item.attr('data-item'));
            publicidade.redireciona(item.attr('data-url'), tipo);
        }
        else
        {
            publicidade.redireciona(URI + 'imoveis-venda-' + $('.localizacao').attr('data-cidade'), tipo);
        }
    },
    redireciona: function(url, tipo){
        //console.log(tipo);
        var navegador = navigator.userAgent;
        if ( navegador.search('Chrome') > 0 )
        {
            window.open(url,tipo);
        }
        else
        {
            window.open(url,'_blank');
        }
        
    },
    set_publicidade: function(){
        setTimeout(function(){
            var tem_publicidade = $('.imoveis .publicidade').html();
            var tem_empresa = $('.filtro .id_empresa').val();
            if ( tem_publicidade != undefined && tem_empresa === undefined )
            {
                var url = URL_HTTP + 'publicidade/set_publicidade';
                $.getJSON(url, function(data){
                    console.log(data);
                    publicidade.get_publicidade(data, false);
                });

            }
            
        },600);
    },
    set_publicidade_home: function(){
        var tem_publicidade = $('.imoveis .publicidade').html();
        if ( tem_publicidade === undefined )
        {
            var url = URL_HTTP + 'publicidade/set_publicidade';
            $.getJSON(url, function(data){
                publicidade.get_publicidade(data, true);
            });
            
        }
        
        
    },
    get_publicidade: function(itens, home){
        var a = {};
        var b = 1;
        
        $.each(itens,function(chave, valor){
            var eharray = Array.isArray(valor);
            if ( eharray )
            {
                $.each(valor,function(chave_, valor_){
                    $('.publicidade-' + chave).html(valor_).removeClass('hide').addClass('show');
                    b++;
                });
            }
            else
            {
                $('.publicidade-' + b).html(valor).removeClass('hide').addClass('show');
                b++;
            }
        });
        publicidade.set_view();
    }
};
