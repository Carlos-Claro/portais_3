<?php 
if ( strstr($menu['url'], 'http://www.') )
{
    $a = str_replace('http://www.', '', $menu['url']);
    $b = explode('/', $a);
    if ( in_array($b[0],ARRAY_HTTPS ) )
    {
        $url_ = str_replace('http://www.','https://',$menu['url']);

    }
    else
    {
        $url_ = $menu['url'];
    }

}
else
{
    $url_ = $menu['url'];
}
unset($menu['url']);
$conta = 0;
foreach ( $menu as $chave => $valor ): 
    if ( count($valor['itens']) > 0 ):
        $titulo = ( $chave == 'venda' ) ? 'Imóveis à Venda' : 'Imóveis para Alugar';
    ?><div class="col-md-4"><ul class="dropdown-menu c-menu-type-inline"><li><a href="<?php echo str_replace('[tipo]-[oq]','imoveis-'.$chave,$url_); ?>" title="<?php echo $titulo;?>"><?php echo $titulo.' '.$sobre->nome; ?></a></li><?php 
                foreach($valor['itens'] as $v):
                    $array = array('[oq]','[tipo]');
                    $array_b = array($chave,$v->link);
                    ?><li><a href="<?php echo str_replace($array, $array_b, $url_);?>" title="<?php echo $valor['titulo'].' '.$v->descricao.' '.$sobre->nome;?>" class=""><?php echo $v->descricao;?> <div class="badge pull-right"><?php echo $v->qtde;?></div></a></li><?php
                endforeach;
                ?></ul></div><?php
    $conta++;
    endif;
endforeach;