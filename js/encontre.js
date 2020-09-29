//montagem de box

$(function(){
    $('.email').on({
        blur : function(){
            var email = $(this).val();
            if ( email.length > 0 )
            {
                $.getJSON(URL_HTTP + '/consulta_cadastro/'+email+'/').done(function(data){
                    if ( data !== 0 )
                    {
                        $('.nome').val(data.nome);
                        $('.cidade').val(data.cidade);
                        $('.estado').val(data.uf);
                        $('.telefone').val(data.telefone);
                        $('.telefone').focus();
                    }
                    else
                    {
                        $('.nome').val('');
                        $('.cidade').val('');
                        $('.estado').val('');
                        $('.telefone').val('');
                        $('.telefone').focus();
                    }
                });
            }
        }
    });
    
    $(document).on('click','.localidade.bairro',function(){
        item = $(this).data('item');
        $('.localidade.bairro.' + item).remove();
        pesquisa.getValores();
    });
    $(document).on('click','.localidade.cidade',function(){
        item = $(this).data('item');
        $('.localidade.bairro').remove();
        $('.localidade.cidade').remove();
        pesquisa.getValores();
    });
    $(document).on('click','.tipo',function(){
        item = $(this).data('item');
        $('.tipo.' + item).remove();
        pesquisa.getValores();
    });
    $('.tipos select').on('change',function(){
        data = {'id':$(this).find(':selected').val(), 'text':$(this).find(':selected').html()};
        item = formulario.setItem(data,'tipo'); 
        if (item !== '')
        {
            formulario.addItem(item, '.tipos', false);        
            pesquisa.getValores();
        }
    });
    $('.lista-tipo').on('click',function(){
        $('.lista-tipo').removeClass('active');
        $(this).addClass('active');
        pesquisa.getValores();
    });
    
    $localizacaoSelect.on('select2:select', function(e){
        var cidade = $('.localidade.cidade').data('item');
        if ( cidade !== undefined && cidade !== '' )
        {
            var text = e.params.data.text;
            var id = e.params.data.id;
            if ( text.indexOf('*') >= 0 )
            {
                formulario.setSelectbairro(e.params.data,true);
            }
            else
            {
                formulario.setSelectbairro(e.params.data,false);
            }
        }
        else
        {
            var i = e.params.data.id;
            if ( i.indexOf(';') > 0 )
            {
                formulario.setSelectcidade(e.params.data,true);
            }
            else
            {
                formulario.setSelectcidade(e.params.data,false);
                $('.localizacao').attr('data-cidade',e.params.data.id);
            }
        }
    });
    $localizacaoSelect.on('select2:unselect', function(e){});

    
});
var $localizacaoSelect = $('.localidade-select').select2({
    tags: false,
    placeholder: 'Busque uma cidade, bairro, nome da rua que busca seu imóvel ',
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


function onSubmit(token) {
    formulario.onSubmit(token);
    return false;
}

var formulario = {
        getElementos: function(){
        var retorno = {};
        $.each(formulario.elementos_avancado,function(k,v){
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
    elementos_avancado: {
        'tipo':             {'campo': '.tipo',             'url':true,    'tipo':'li', 'campo_selecionado' : 'tipo_selecionado',   'array': false},
        'cidade':           {'campo': '.localidade.cidade', 'url':true,    'tipo':'li', 'campo_selecionado' : 'localidade_selecionado',              'array': true},
        'bairro':           {'campo': '.localidade.bairro', 'url':true,    'tipo':'li', 'campo_selecionado' : 'localidade_selecionado',              'array': true},
        'valor_min':        {'campo': '.valor-min',         'url':false,   'tipo':'val'},
        'valor_max':        {'campo': '.valor-max',         'url':false,   'tipo':'val'},
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
                case 'select':
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
                    break;
            }
        });
        data.post['complemento'] = formulario.getElementos();
        return data;
    },
    
    onSubmit: function(token){
        data = formulario.getCampos();
        
        post = data.post;
        post['token'] = token;
        erro = data.erro;
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
                    var url = URI + 'contato/nao_encontrei';
                console.log(url,post);
                    $.post(url, post, function(data){
                        if ( data.status )
                        {
                            swal('Parabéns! ','Mensagem enviada com sucesso.','success');
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
        retorno.email={tipo:'text',required:true};
        retorno.nome={tipo:'text',required:true};
        retorno.telefone={tipo:'text',required:false};
        retorno.estado={tipo:'text',required:false};
        retorno.cidade={tipo:'text',required:false};
        retorno.oq={tipo:'select',required:false};
        retorno.cidade_={tipo:'text',required:false};
        retorno.observacao={tipo:'text',required:false};
        retorno.aceito={tipo:'checkbox',required:false};
        console.log(retorno);
        return retorno;
    },
    setItem:function(data, tipo = 'localidade bairro'){
        tem = $('.' + tipo + '.' + data.id).length;
        if ( ! tem )
        {
            return '<li class="' + tipo + ' ' + data.id + ' c-margin-t-20" data-item="' + data.id + '" title="clique para remover ' + (data.descricao !== undefined ?  data.descricao : data.text) + '"><div class="btn btn-azul-pow-reverso c-btn-uppercase c-btn-bold c-btn-border-2x btn-block"><span class="c-theme-link pull-left"> x </span>' + (data.descricao !== undefined ?  data.descricao : data.text) + '</div></li>';
        }
        return '';
    },
    setSelectcidade: function(valor, asterisco = false){
        if ( asterisco )
        {
            i = valor.id;
            a = i.split(';');
            texto = valor.text;
            t = texto.split('*');
            ba = t[2].split(',');
            item = '';
            data = {'id':a[0],'text':t[1]};
            item += formulario.setItem(data,'localidade cidade');
            b = a[1].split(',');
            $.each(b,function(k,v){
                if ( v !== '' )
                {
                    data = {'id':v,'text':ba[k]};
                    item += formulario.setItem(data,'localidade bairro');
                }
            });
            item = formulario.setItem(valor,'localidade cidade');
        }
        else
        {
            item = formulario.setItem(valor,'localidade cidade');
        }
        formulario.addItem(item, '.localidades', false);
    },
    setSelectbairro: function(valor, asterisco = false){
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
                    item += formulario.setItem(data,'localidade bairro');
                }
            });
        }
        else
        {
            item = formulario.setItem(valor,'localidade bairro');
        }
        formulario.addItem(item, '.localidades', false);
    },
    addItem:function(valor,classe = '.localidades', limpa = true){
        html = '';
        if ( ! limpa )
        {
            html += $(classe + ' .itens').html();
        }
        html += valor;
        $(classe + ' .itens').html(html);
    },
};
