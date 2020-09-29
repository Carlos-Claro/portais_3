

$(function(){
    favorito.inicia();
    $('.c-quick-sidebar-toggler').on('click',function(){
        favorito.lista();
    });
    $(document).on('click','.favoritar',function(){
        var item = $(this).attr('data-item');
        favorito.insere(item, true);
    });
    $('.comparar-favoritos').on('click',function(){
        console.log('compara');
        favorito.compara();
    });
    $('.limpar-favoritos').on('click',function(){
        favorito.deleta_todos();
    });
    $('.linha-favoritos').on('click','.imprimir', function(){
        favorito.imprimir(true);
    });
    $('.linha-favoritos').on('click', '.indique', function(){
        favorito.indicar();
    });
});



var favorito = {
    inicia :  function(){
        
    },
    insere : function(id, imovel){
        var url = URI + 'favoritar/' + id + '/' + $('.elemento-' + id).data('data-origem');
        $.getJSON(url,function(data){
            if (data.status)
            {
                toastr.success(data.mensagem, 'Inserido...');
                favorito.inicia();
                
            }
            else
            {
                toastr.danger(data.mensagem, 'Erro...');
                favorito.inicia();
            }
        });
    },
    deleta : function(id){
        var url = URL_HTTP + '/index/deleta_favorito/' + id;
        $.get(url,function(data){
            window.location.href = URL_HTTP + '/index/favoritos';
        });
    },
    deleta_todos: function(){
        var url = URL_HTTP + '/index/deleta_favorito/';
        $.get(url,function(data){
            window.location.href = URL_HTTP;
            
        });
        
    },
    lista : function() {
        var url = URI + 'favoritos/lista/';
        $.get(url,function(data){
            $('.lista-favoritos').html(data);    
            fotos.inicia();
        });
    },
    compara : function() {
        var url = URI + 'favoritos/compara/';
        window.location.href = url;
//        $.get(url,function(data){
//            $('.modal .modal-title').html('');    
//            $('.modal .modal-body').html(data);    
//            $('.modal').modal('show');
//            fotos.inicia();
//        });
    },
    imprimir: function( todos ){
        if ( todos !== undefined )
        {
            $('.media').attr('data-item',function(a,b){
                var link = $('.elemento-' + b + ' .detalhes').attr('href') + '/print';
                window.open(link,'_blank');
                
            });
        }
    },
    indicar: function(){
        var item = new Array;
        $('.media').attr('data-item',function( a, b ){
            item[a] = b;
        });
        var colado = item.join('-');
        $('.imoveis').val(colado);
        //$('#modal-indicar').modal('show');
    },
    
};