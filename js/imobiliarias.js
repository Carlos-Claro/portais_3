//var URL_HTTP = 'http://' + window.location.host;

$(function(){
    $('.itens-imobiliarias').on('click','.ver-telefone-empresa',function(){
        var telefone = $(this).attr('data-telefone');
        var id_tabela = $(this).attr('data-item');
        estatistica.set_empresa(id_tabela,22);
        $(this).html(telefone);
    });
    $(document).ready(function(){
        $('#modal-contato').modal('hide');
    });
    $('.itens-imobiliarias').on('click', '.contato', function(){
        var item = $(this).attr('data-item');
        var titulo = $(this).attr('data-titulo');
        $('.id_empresa').val(item);
        $('.titulo-contato').html(titulo);
        $('#modal-contato').modal('show');
        estatistica.set_empresa(item);
    }); 
    
    
    
    $('.busca-imobiliaria').on({
        keyup: function(){
            var item = $(this).val();
            $('.itens-imobiliarias').html('<img src="'+URL_HTTP+'/images/loader_azul.gif" alt="carregando">');
            setTimeout(function(){
                console.log('tempo');
                //console.log(item, $('.busca-imobiliaria').val());
                if ( item == $('.busca-imobiliaria').val() )
                {
                    console.log('entrou');
                    if ( item.length > 0 ) 
                    {
                        var url = URL_HTTP + 'imobiliarias/imobiliarias_lista/' + item;
                        $.get(url).done(function(data){
                            var  d = $('.itens-imobiliarias').html();
                            $('.itens-imobiliarias').html(data);
                            
                        }); 
                    }
                    else
                    {
                            $('.itens-imobiliarias').html("<p class='alert'>Digite algo para obter uma pesquisa ou recarregue a página</p>");
//                        var url = URL_HTTP + 'imobiliarias/imobiliarias_lista/';
//                        $.get(url,function(data){
//                            $('.itens-imobiliarias').html(data);
//                        });
                    }
                }
            }, 200);
            
                    
            
        }, 
    });
     
});


