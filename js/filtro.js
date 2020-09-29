
var $ =jQuery.noConflict();
toastr.options = {
  "closeButton": true,
  "debug": false,
  "positionClass": "toast-top-right",
  "onclick": null,
  "showDuration": "500",
  "hideDuration": "500",
  "timeOut": "500",
  "extendedTimeOut": "100",
  "showEasing": "swing",
  "hideEasing": "linear",
  "showMethod": "fadeIn",
  "hideMethod": "fadeOut"
};

$(function(){
    filtro.inicia();
    
    $('.buscar, .mobiliado, .condominio, .quartos, .vagas, .banheiros, .comercial, .residencial').on('click',function(){
        console.log('buscando');
        valores = pesquisa.getValores(true);
        filtro.fechaFiltro();
    });
    $('.valor, .area').on('change',function(){
        console.log('buscando');
        valores = pesquisa.getValores(true);
        filtro.fechaFiltro();
    });
    $('.limpar').on('click',function(){
        window.location.href = URL_HTTP;
    });
    $('.tipo-negocio').on('click',function(){
        tipo = $(this).attr('data-tipo');
        setTimeout(function(){
            pesquisa.getValores(true);
        },200);
    });
    $(document).on('click','.id_empresa',function(){
        item = $(this).data('item');
        $('.id_empresa.' + item).remove();
        $('.empresa').remove();
        $('.banner-imobiliarias').remove();
        pesquisa.getValores(true, true);
    });
    $(document).on('click','.localidade.bairro',function(){
        item = $(this).data('item');
        $('.localidade.bairro.' + item).remove();
        pesquisa.getValores(true);
    });
    $(document).on('click','.localidade.cidade',function(){
        item = $(this).data('item');
        $('.localidade.bairro').remove();
        $('.localidade.cidade').remove();
        pesquisa.getValores(true);
    });
    $(document).on('click','.tipo',function(){
        item = $(this).data('item');
        $('.tipo.' + item).remove();
        pesquisa.getValores(true);
    });
    $('.tipos select').on('change',function(){
        data = {'id':$(this).find(':selected').val(), 'text':$(this).find(':selected').html()};
        item = filtro.setItem(data,'tipo'); 
        if (item !== '')
        {
            filtro.addItem(item, '.tipos', false);        
            pesquisa.getValores(true);
        }
    });
    $('.lista-tipo').on('click',function(){
        $('.lista-tipo').removeClass('active');
        $(this).addClass('active');
        pesquisa.getValores(true);
    });
    $('.ordem').on('change',function(){
        pesquisa.getValores(true);
    });
    $('.busca-lg').on('click',function(){
        filtro.setZero();
    });
    $('.filtro-atalho').on('click',function(){
        filtro.abreFiltro();
    });
    $(window).scroll(function(e){
        y = $(this).scrollTop();
        if ( y > 20 )
        {
            $('.busca-lg').removeClass('hide');
        }
        else
        {
            $('.busca-lg').addClass('hide');
        }
        if ( y > 400  )
        {
            if ( $('body').data('is-mobile') )
            {
//                filtro.fechaFiltro();
            }
            
        }
        coeficiente = $('.lista-tipo.active').data('tipo') === 'lista' ? 1200 : 42;
        y_ver =  y % 3 ;
        if ( ! y_ver )
        {
            if ( ! pesquisa.carregando )
            {
                qtde = $('.imoveis li.organico').length;
                ultimo = $('.ultimo').html('<div class="btn-azul-pow"><center><i class="fa fa-spinner fa-spin"></i></center></div>').offset().top;
                if ( ultimo > 0 )
                {
                    if( y > ( ultimo - coeficiente ) )
                    {
                        pesquisa.carregando = true; 
                        pesquisa.paginacao();
                        toastr.info('Buscando novos itens: + 12 de ' + ( $('.total').html() - qtde  ), 'Paginando...');
                    }
                }
            }
        }
    });
    
    var $localizacaoSelect = $('.localidade-select').select2({
        tags: false,
        placeholder: 'Clique e pesquise a localidade ',
        amdLanguageBase: 'js/i18n/',
        language: 'pt-BR.js',
        closeOnSelect: true,
        ajax: {
            url: function(params){
                cidade  = $('.localidade.cidade').attr('data-item');
                if ( cidade !== undefined && cidade !== '' )
                {
                    bairro_data = $('.localidade.bairro').attr('data-item');
                    bairro = (bairro_data !== undefined ) ? 1 : 0;
                    return URI + 'funcoes/set_bairros_por_cidade_select2/' + cidade + '/' + bairro + '' + (params.term === undefined ? params.term : '');
                    
                }
                else
                {
                    return URI + 'cidades/busca_cidade_json/' + (params.term !== undefined ? params.term : '');
                }
            },
            dataType: 'json',
            delay: 100,
            processResults: function(data) {
                return {
                    results: data
                };
            } 
        }
    });
    $localizacaoSelect.on('select2:select', function(e){
        var cidade = $('.localidade.cidade').data('item');
        if ( cidade !== undefined && cidade !== '' )
        {
            var text = e.params.data.text;
            var id = e.params.data.id;
            if ( text.indexOf('*') >= 0 )
            {
                filtro.setSelectbairro(e.params.data,true);
            }
            else
            {
                filtro.setSelectbairro(e.params.data,false);
            }
        }
        else
        {
            var i = e.params.data.id;
            if ( i.indexOf(';') > 0 )
            {
                filtro.setSelectcidade(e.params.data,true);
            }
            else
            {
                filtro.setSelectcidade(e.params.data,false);
                $('.localizacao').attr('data-cidade',e.params.data.id);
            }
        }
    });
    $localizacaoSelect.on('select2:unselect', function(e){});
        
    
   $('.icon-voltar').on('click', function(e){
        filtro.abreFiltro();
   });
});


var pesquisa = {
    inicia:function(){
        pesquisa.carregando = true;
        var data_valores = $('.data-valores').html();
        pesquisa.data = JSON.parse(data_valores);
        delete pesquisa.data['tipo_selecionado'];
        delete pesquisa.data['localidade_selecionado'];
        valores = filtro.getElementos();
        pesquisa.data = valores;
        filtro.setFiltro();
        
        pesquisa.getPesquisa();
        
    },
    paginacao:function(){
        valores  = filtro.getElementos();
        valores['offset'] = ( $('.imoveis li.organico').length )++;
        total = parseInt($('.qtde .total').html());
        if ( valores['offset'] >= total ){
            $('.imoveis').append('<li class="nao-encontrei col-lg-12 col-sm-12 col-md-12 col-xs-12"><a href="' + URI + 'nao_encontrei"><img src="' + pesquisa.banner_nao_encontrei + '"></a></li>');
        }
        else{
            pesquisa.data = valores;
            pesquisa.getItens(false);
            u = document.documentURI;
            tem_offset = u.indexOf('qtde_itens');
            tem_i = u.indexOf('?');
            tem_i_q = u.indexOf('?qtde_itens');
            if ( tem_offset >= 0 ){
                e = u.split('&qtde_itens=');
                e.pop();
                u = e.join('&');
                ur = u + (tem_i >= 0 && tem_i_q < 0 ? '&qtde_itens=' : '?qtde_itens=') + $('.imoveis li.organico').length;
            }
            else{
                ur = u + (tem_i >= 0 && tem_i_q < 0 ? '&qtde_itens=' : '?qtde_itens=') + $('.imoveis li.organico').length;
            }
            setTimeout(function(){
                document.documentURI = ur;
                window.history.pushState(undefined,undefined, ur);
            },700);
            
        }
            
    },
    data: {},
    qtde: 0,
    scroll: 0,
    carregando: false,
    urlPesquisa: URI + 'pesquisa',
    urlImoveis: URI + 'get_itens',
    getPesquisa:function(muda_url, empresa){
        if ( empresa === undefined ){
            empresa = false;
        }
            
        $.post(pesquisa.urlPesquisa,pesquisa.data,function(data){
            $('h1.titulo').html(data.titulo);
            $('.qtde').html('<span class="total">' + data.total + '</span> Imóveis encontrados');
            if ( muda_url ){
//                console.log(navigator.userAgent);
//                console.log(navigator.userAgent.toLowerCase().indexOf('chrome'));
                if ( navigator.userAgent.toLowerCase().indexOf('safari') >= 0 && navigator.userAgent.toLowerCase().indexOf('chrome') < 0 ){
                    window.location.href = URI + data.url;
                }
                else {
                    if ( empresa ){
                        document.documentURI = URI + data.url;
                        window.history.pushState(data.titulo,data.titulo, URI + data.url);
                    }
                    else{
                        document.documentURI = data.url;
                        window.history.pushState(data.titulo,data.titulo, data.url);
                    }
                }
            }
            window.title = data.titulo;
            document.title = data.titulo;
            $('.imoveis').html('');
            if ( data.total > 0 && parseInt($('.imoveis li.imovel').length) <= data.total ){
                pesquisa.getItens(true);
            }
            else{
                //buscar mais itens
                $('.imoveis').html('<li class="nao-encontrei col-lg-12 col-sm-12 col-md-12 col-xs-12"><a href="' + URI + 'nao_encontrei"><img src="' + pesquisa.banner_nao_encontrei + '"></a></li>');
                toastr.info('Nenhum imóvel carregado para esta busca.', 'Sem imóveis...');
            }
            pesquisa.carregando = false;
        },'json');
    },
    banner_nao_encontrei: URI + 'images/banner/nao-encontrei.jpg',
    getItens: function(novo){
        pesquisa.carregando = true;
        $.post(pesquisa.urlImoveis,pesquisa.data,function(data_){
            console.log(data_.status);
            console.log($('.imoveis'));
            if ( novo )
            {
                try {
                    $('.imoveis').html(data_.data);
                    $('.imoveis').append('<li class="ultimo col-lg-12 col-sm-12 col-md-12 col-xs-12"></li>');
                    toastr.info('12 Imóveis carregados de ' + $('.total').html(), 'Buscando...');
                }catch(e){
                    alert(e);
                }
            }
            else
            {
                var tipo_lista = $('.imoveis').data('lista-tipo');
                $('.imoveis .ultimo').addClass('pagina btn-azul-pow').removeClass('ultimo').html('<center>' + ( $('.imoveis li.imovel').length ) + ' Imóveis</center>');
                try{
                    t = data_.data+'"';
                    $('.imoveis').append(t);
                }catch (exception) {
                    console.log(exception);
                }
                try{
                    $('.imoveis').append('<li class="ultimo col-lg-12 col-sm-12 col-md-12 col-xs-12"></li>');
                }catch (e){
                    console.log(e)
                }
                toastr.info($('.imoveis li.imovel').length + ' Imóveis carregados de ' + $('.total').html(), 'Carga concluída...');
                estatistica.set();

            }
            setTimeout(function(){
                fotos.inicia();
                pesquisa.carregando = false;
            },500);
        },'json');
    },
    getValores: function(muda_url, empresa){
        if ( muda_url === undefined ){
            muda_url = true;
        }
        if ( empresa === undefined ){
            empresa = false;
        }
        valores = filtro.getElementos();
        pesquisa.data = valores;
        pesquisa.getPesquisa(muda_url, empresa);
    }
    
};
var filtro = {
    inicia:function(){
        var data_valores = $('.data-valores').html();
        var data = JSON.parse(data_valores);
        this.setElementos(data);
        this.setTipo();
        this.setLocalidade();
        filtro.setFiltro();
    },
    setFiltro: function(){
        if ( $('body').data('is-mobile') )
        {
            filtro.fechaFiltro();
        }
    },
    setZero:function(){
        $.smoothScroll({
            offset: 0,

            // one of 'top' or 'left'
            direction: 'top',

            // only use if you want to override default behavior or if using $.smoothScroll
            scrollTarget: null,

            // automatically focus the target element after scrolling to it
            // (see https://github.com/kswedberg/jquery-smooth-scroll#focus-element-after-scrolling-to-it for details)
            autoFocus: false,

            // string to use as selector for event delegation
            delegateSelector: null,

            // fn(opts) function to be called before scrolling occurs.
            // `this` is the element(s) being scrolled
            beforeScroll: function() {},

            // fn(opts) function to be called after scrolling occurs.
            // `this` is the triggering element
            afterScroll: function() {},

            // easing name. jQuery comes with "swing" and "linear." For others, you'll need an easing plugin
            // from jQuery UI or elsewhere
            easing: 'swing',

            // speed can be a number or 'auto'
            // if 'auto', the speed will be calculated based on the formula:
            // (current scroll position - target scroll position) / autoCoefficient
            speed: 400,

            // autoCoefficent: Only used when speed set to "auto".
            // The higher this number, the faster the scroll speed
            autoCoefficient: 2,

            // $.fn.smoothScroll only: whether to prevent the default click action
            preventDefault: true
        });
    },
    abreFiltro: function(){
        $('.c-shop-filter-search-1').removeClass('hide').addClass('show');
        setTimeout(function(){
            $('.c-quick-search .c-theme-link').trigger('click');
            filtro.setZero();
        }, 300);
        
    },
    fechaFiltro: function(){
        if ( $('body').data('is-mobile') )
        {
            var url = window.location.href;
            console.log(url.indexOf('filtro_aberto'));
            if ( url.indexOf('filtro_aberto') < 0 )
            {
                     if (window.innerWidth < 992)
                     {
                          $('.c-shop-filter-search-1').addClass('hide').removeClass('show');
                     }
            }
            else
            {
                setTimeout(function(){
                    $('.c-shop-filter-search-1').removeClass('hide').addClass('show');
                }, 500);
            }
        }
        
    },
    setTipo: function(){
        var item = '';
        $.each(filtro.valores['tipo'],function(k,v){
            item += filtro.setItem(v,'tipo'); 
        });
        filtro.addItem(item, '.tipos');
    },
    setLocalidade: function(){
        var item = '';
        $.each(filtro.valores['cidade'],function(k,v){
            item += filtro.setItem(v,'localidade cidade'); 
        });
        $.each(filtro.valores['bairro'],function(k,v){
            item += filtro.setItem(v,'localidade bairro'); 
        });
        filtro.addItem(item, '.localidades');
    },
    addItem:function(valor,classe, limpa){
        if ( classe === undefined ) {
            classe = '.localidades';
        }
        if ( limpa === undefined )
        {
            limpa = true;
        }
        html = '';
        if ( ! limpa )
        {
            html += $(classe + ' .itens').html();
        }
        html += valor;
        $(classe + ' .itens').html(html);
    },
    delItem:function(valor,classe){
        
    },
    setItem:function(data, tipo){
        if ( tipo === undefined )
        {
            tipo = 'localidade bairro';
        }
        tem = $('.' + tipo + '.' + data.id).length;
        if ( ! tem )
        {
            return '<li class="' + tipo + ' ' + data.id + ' c-margin-t-5" data-item="' + data.id + '" title="clique para remover ' + (data.descricao !== undefined ?  data.descricao : data.text) + '"><div class="btn btn-azul-pow-reverso c-btn-uppercase c-btn-bold c-btn-border-2x btn-block"><span class="c-theme-link pull-left"> x </span>' + (data.descricao !== undefined ?  data.descricao : data.text) + '</div></li>';
        }
        return '';
    },
    setSelectcidade: function(valor, asterisco){
        if ( asterisco === undefined ){
            asterisco = false;
        }
        if ( asterisco )
        {
            i = valor.id;
            a = i.split(';');
            texto = valor.text;
            t = texto.split('*');
            ba = t[2].split(',');
            item = '';
            data = {'id':a[0],'text':t[1]};
            item += filtro.setItem(data,'localidade cidade');
            b = a[1].split(',');
            $.each(b,function(k,v){
                if ( v !== '' )
                {
                    data = {'id':v,'text':ba[k]};
                    item += filtro.setItem(data,'localidade bairro');
                }
            });
            item = filtro.setItem(valor,'localidade cidade');
        }
        else
        {
            item = filtro.setItem(valor,'localidade cidade');
        }
        filtro.addItem(item, '.localidades', false);
        pesquisa.getValores();
    },
    setSelectbairro: function(valor, asterisco){
        if ( asterisco === undefined ){
            asterisco = false;
        }
        if ( asterisco )
        {
            text = valor.text;
            id = valor.id;
            t = text.split('*');
            c = [];
            bi = id.indexOf(',') >= 0 ? id.split(',') : [id];
            bt = t[1].indexOf(',') >= 0 ? t[1].split(',') : [t[1]];
            item = '';
            $.each(bi,function(k,v){
                if ( v !== '' )
                {
                    data = {'id':v,'text':bt[k]};
                    item += filtro.setItem(data,'localidade bairro');
                }
            });
        }
        else
        {
            item = filtro.setItem(valor,'localidade bairro');
        }
        filtro.addItem(item, '.localidades', false);
        pesquisa.getValores();
    },
    setGeral: function(){
        
    },
    setElementos: function(valores) {
        $.each(this.elementos, function(k,v){
            if ( v.campo_selecionado !== undefined )
            {
                if ( valores[v.campo_selecionado] !== undefined )
                {
                    if ( v.array )
                    {
                        filtro.valores[k] = valores[v.campo_selecionado][k];
                    }
                    else
                    {
                        filtro.valores[k] = valores[v.campo_selecionado];
                    }
                }
            }
            else
            {
                if ( valores[k] !== undefined )
                {
                    filtro.valores[k] = valores[k];
                }
            }
        });
        return this;
    },
    getElementos: function(){
        var retorno = {};
        $.each(filtro.elementos,function(k,v){
            switch(v.tipo)
            {
                case 'li':
                    console.log($('li' + v.campo));
                    retorno[k] = [];
                    $.each($('li' + v.campo),function(a,b){
                        retorno[k][a] = $(this).data('item');
                    });
                    break;
                case 'attr_active':
                    retorno[k] = $('' + v.campo + '.active').data('tipo');
                    break;
                case 'val':
                    if ( $(v.campo).val() !== undefined && $(v.campo).val() > 0 )
                    {
                        retorno[k] = $('' + v.campo).val();
                    }
                    break;
                case 'val_text':
                    if ( $(v.campo).val() !== undefined )
                    {
                        retorno[k] = $('' + v.campo).val();
                    }
                    break;
                case 'check':
                    if ( $('' + v.campo + ':checked').length > 0 )
                    {
                        retorno[k] = $('' + v.campo + ':checked').length;
                    }
                    break;
                case 'url':
                    retorno[k] = $('.' + k + '').data('item');
                    break;
                case 'range':
                    var z = [];
                    $( v.campo + ':checked').each(function( a, b ){
                        z.push($(b).val());
                    });
                    retorno[k] = z;
                    break;
            }
        });
        return retorno;
    },
    valores: {},
    elementos: {
        'id_empresa':       {'campo': '.id_empresa',           'url':true,   'tipo':'li'},
        'tipo_negocio':     {'campo': '.tipo-negocio',      'url':true,    'tipo':'attr_active'},
        'tipo':             {'campo': '.tipo',              'url':true,    'tipo':'li', 'campo_selecionado' : 'tipo_selecionado',   'array': false},
        'cidade':           {'campo': '.localidade.cidade', 'url':true,    'tipo':'li', 'campo_selecionado' : 'localidade_selecionado',              'array': true},
        'bairro':           {'campo': '.localidade.bairro', 'url':true,    'tipo':'li', 'campo_selecionado' : 'localidade_selecionado',              'array': true},
        'valor_min':        {'campo': '.valor-min',         'url':false,   'tipo':'val'},
        'valor_max':        {'campo': '.valor-max',         'url':false,   'tipo':'val'},
        'area_min':         {'campo': '.area-min',          'url':false,   'tipo':'val'},
        'area_max':         {'campo': '.area-max',          'url':false,   'tipo':'val'},
        'residencial':      {'campo': '.residencial',       'url':false,   'tipo':'check'},
        'condominio':       {'campo': '.condominio',        'url':false,   'tipo':'check'},
        'mobiliado':        {'campo': '.mobiliado',         'url':false,   'tipo':'check'},
        'comercial':        {'campo': '.comercial',         'url':false,   'tipo':'check'},
        'quartos':          {'campo': '.quartos',           'url':true,    'tipo':'range'},
        'banheiros':        {'campo': '.banheiros',         'url':false,   'tipo':'range'},
        'vagas':            {'campo': '.vagas',             'url':false,   'tipo':'range'},
        'lista_tipo':       {'campo': '.lista-tipo',        'url':false,   'tipo':'attr_active'},
        'ordem':            {'campo': '.ordem',             'url':false,   'tipo':'val_text'},
    }
};

$(function(){
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
    
    
//    $(document).on('click','.amplia-images',function(){
//        item = $(this).data('item');
//        url = URI + 'imovel_modal/' + item;
//        $.get(url,function(data){
//            $('.reserva-modal .modal-body').html(data);
//            $('.modal').modal('show');
//        });
//    });
});
