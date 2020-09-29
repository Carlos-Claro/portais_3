<!DOCTYPE html>
<html lang="pt-BR"  itemscope itemtype="http://schema.org/WebPage">
<head>
	<meta charset="UTF-8" >
        <title><?php if ( isset( $titulo ) ) : echo $titulo; endif; ?></title>
	<?php
        if ( isset($canonical) ) : echo '<link rel="canonical" href="'.$canonical.'" />'; endif;
        if ( isset($header_extra) ) : echo $header_extra; endif;
        ?>
        <link rel="alternate" href="<?php echo base_url().( isset($_SERVER['PATH_INFO']) ? substr($_SERVER['PATH_INFO'],1) : '' );?>" hreflang="pt-br" />
        <meta http-equiv="content-language" content="pt-br">
        <meta name="description" itemprop="description" content="<?php if ( isset( $description ) ) : echo $description; endif; ?>" />
        <meta property="og:title" content="<?php if ( isset( $titulo ) ) : echo $titulo; endif; ?>" />
        <meta property="og:description" content="<?php if ( isset( $description ) ) : echo $description; endif; ?>" />
        <meta property="og:url" content="<?php echo base_url().( isset($_SERVER['PATH_INFO']) ? substr($_SERVER['PATH_INFO'],1) : '' );?>" />
        <meta property="og:locale" content="pt-br" />
        <meta property="og:site_name" content="Portais Imobiliários" />
        <link rel="apple-touch-icon" href="<?php echo base_url();?>images/favicon.png">
        <link rel="apple-touch-icon-precomposed" href="<?php echo base_url();?>images/favicon.png"> 
        <link rel="icon" href="<?php echo base_url();?>images/favicon.png" type="image/x-icon">
        <link rel="shortcut icon" href="<?php echo base_url();?>images/favicon.png" type="image/x-icon">
        <link rel="shortcut icon" href="<?php echo base_url();?>images/favicon.png" type="image/vnd.microsoft.icon">
	<meta name="author" content="POW Internet - http://www.powinternet.com/" />
        <meta http-equiv="expires" content="<?php echo date(DATE_RFC822,strtotime("1 month"));?>">
        <meta http-equiv="Pragma" content="public"> <!-- reconhecida por todas as versões do HTTP -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <script src="https://apis.google.com/js/platform.js" async defer>
            {lang: 'pt-BR'}
        </script>
        <?php 
//        if ( $robot ) :
//            echo '<meta name="robots" content="index,follow"/>'.PHP_EOL;
//        else :
//            echo '<meta name="robots" content="noindex,nofollow"/>'.PHP_EOL;
//        endif;
        
            if ( strstr($_SERVER['HTTP_USER_AGENT'], 'MSIE 8') || strstr($_SERVER['HTTP_USER_AGENT'], 'MSIE 7') ) :
                ?>
                    <meta http-equiv="X-UA-Compatible" content="IE=edge">
                    <style type="text/css">
                        @-ms-viewport       { width: device-width; }
                    </style>
                <?php 
            else :
                ?>
                    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
                <?php 
            endif;
        if ( isset( $image_destaque ) && ! empty( $image_destaque ) ) :
            ?>
            <meta property="og:url"             content="<?php echo $canonical;?>" /> 
            <meta property="og:title"           content="<?php echo (isset($titulo) ? $titulo : '')?>" />
            <meta property="og:image"           content="<?php echo $image_destaque;?>" />
            <meta property="og:image:height"    content="600" />
            <meta property="og:image:width"     content="315" />
            <meta property="og:description"     content="<?php echo (isset($description) ? $description : '')?>" />
            <link href="<?php echo $image_destaque;?>" rel="image_src" />
            <?php 
        endif;
?>
</head>
<body data_url="<?php echo base_url();?>" onload="<?php echo isset($on_load) ? $on_load : ''; ?>">
    
    
    
    <?php 
    echo isset($google_tag) ? $google_tag : '';
    ?>
    <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a href="<?php echo base_url();?>" class="navbar-brand " title="<?php  echo substr($sobre['portal'], 11); ?> | As imobiliárias e imóveis de <?php  echo $sobre['nome']; ?>  reunidos aqui!">
                        <img src="<?php echo str_replace('/m','',base_url()).'imagens/'.$logo;?>" alt="<?php  echo substr($sobre['portal'], 11); ?> | As imobiliárias e imóveis de <?php  echo $sobre['nome']; ?>  reunidos aqui!" class="img-responsive">
                    </a>
                </div>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse pull-right" id="navbar-collapse">
                            <ul class="nav navbar-nav pull-left">
                                <?php echo $menu;?>
                            </ul>
                            <span class="pull-right botoes-topo">
                                <button type="button" class="btn btn-topo favoritos"><span class="glyphicon glyphicon-star-empty"></span><span class="hidden-sm">&nbsp;&nbsp;Favoritos</span></button>
                                <a href="<?php echo base_url();?>encontre" class="btn btn-topo com-image nao-encontrei"><img src="<?php echo base_url();?>images/icones/nao_encontrei.png" ><span class="hidden-sm">&nbsp;&nbsp;Não Encontrei</span></a>
                                <a href="<?php echo base_url();?>anuncie" class="btn btn-topo com-image anuncie"><img src="<?php echo base_url();?>images/icones/ico_anunci.png" ><span class="hidden-sm">&nbsp;&nbsp;Anuncie</span></a>
                            </span>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>
    <?php 
    echo isset($conteudo) ? $conteudo : '';
    ?>
    <footer>
        <?php 
        if ( ( isset($sobre['mostra']) && $sobre['mostra'] ) && isset($sobre['sobre']) && !empty($sobre['sobre']) ) :
            ?>
            <div class="row descricao-cidade container">
                <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                    <p class="titulo">Sobre <?php echo $sobre['nome']; ?></p>
                    <p><?php  echo $sobre['sobre']; ?></p>
                </div>
            </div>
            <?php
        endif;
        ?>
        
        <div class="fundo-azul">
            <div class="row container">
                <div class="col-lg-4 col-sm-4 col-md-4 col-xs-6 ">
                    <?php $negocio = 'venda';?>
                    <p class="titulo"><a href="<?php echo base_url().'imoveis-'.$negocio.'-'.$sobre['link_cidade'];?>" title="Comprar imóveis em <?php  echo $sobre['nome']; ?>">Imóveis à venda em <?php  echo $sobre['nome']; ?></a></p>
                    <p><a href="<?php echo base_url().'imoveis'.'-'.$negocio.'-'.$sobre['link_cidade'];?>" title="Comprar imóveis em <?php  echo $sobre['nome']; ?>">Comprar imóveis em <?php  echo $sobre['nome']; ?></a></p>
                    <p><a href="<?php echo base_url().'apartamento-'.$negocio.'-'.$sobre['link_cidade'];?>" title="Comprar Apartamento em <?php  echo $sobre['nome']; ?>">Apartamentos à venda em <?php  echo $sobre['nome']; ?></a></p>
                    <p><a href="<?php echo base_url().'casa-'.$negocio.'-'.$sobre['link_cidade'];?>" title="Comprar Casas em <?php  echo $sobre['nome']; ?>">Casas à venda em <?php  echo $sobre['nome']; ?></a></p>
                    <p><a href="<?php echo base_url().'sobrado-'.$negocio.'-'.$sobre['link_cidade'];?>" title="Comprar Sobrados em <?php  echo $sobre['nome']; ?>">Sobrados à venda em <?php  echo $sobre['nome']; ?></a></p>
                    <p><a href="<?php echo base_url().'lote_terreno-'.$negocio.'-'.$sobre['link_cidade'];?>" title="Comprar Terrenos em <?php  echo $sobre['nome']; ?>">Terrenos à venda em <?php  echo $sobre['nome']; ?></a></p>
                </div>
                <div class="col-lg-4 col-sm-4 col-md-4 col-xs-6">
                    <?php $negocio = 'locacao'; ?>
                    <p class="titulo"><a href="<?php echo base_url().'imoveis-'.$negocio.'-'.$sobre['link_cidade'];?>">Imóveis para Alugar em <?php  echo $sobre['nome']; ?></a></p>
                    <p><a href="<?php echo base_url().'imoveis'.'-'.$negocio.'-'.$sobre['link_cidade'];?>/">Alugar imóveis em <?php  echo $sobre['nome']; ?></a><p>
                    <p><a href="<?php echo base_url().'apartamento-'.$negocio.'-'.$sobre['link_cidade'];?>">Apartamento para alugar em <?php  echo $sobre['nome']; ?></a></p>
                    <p><a href="<?php echo base_url().'casa-'.$negocio.'-'.$sobre['link_cidade'];?>">Casas para alugar em <?php  echo $sobre['nome']; ?></a></p>
                    <p><a href="<?php echo base_url().'sobrado-'.$negocio.'-'.$sobre['link_cidade'];?>">Sobrados para alugar em <?php  echo $sobre['nome']; ?></a></p>
                    <p><a href="<?php echo base_url().'lote_terreno-'.$negocio.'-'.$sobre['link_cidade'];?>">Terrenos para alugar em <?php  echo $sobre['nome']; ?></a></p>
                </div>
                <?php
                /*
                <div class="col-lg-3 col-sm-3 col-md-3 col-xs-6">
                    <?php 
                    if ( isset($link_bairro) && count($link_bairro['itens']) > 0 ) :
                        ?>
                    <p class="titulo" style="font-size: 14px;">Bairros mais buscados em <?php  echo $sobre['nome']; ?></p>
                    <?php
                        foreach ($link_bairro['itens'] as $link) :
                        ?>
                    <p><a href="<?php echo base_url().'imoveis/'.$link_bairro['link_cidade'].'/0/0/'.$link->id;?>/"><?php echo $link->descricao;?></a></p>
                        <?php
                        endforeach; 
                    endif;
                    ?>
                </div>
                 * 
                 */
                ?>
                <div class="col-lg-4  col-sm-4 col-md-4 col-xs-6">
                    <p class="titulo">Links Importantes</p>
                    <p><a href="<?php echo base_url().'por/comprar/tipo/';?>">Comprar por Tipo</a></p>
                    <p><a href="<?php echo base_url().'por/comprar/bairro/';?>">Comprar por Bairro</a></p>
                    <p><a href="<?php echo base_url().'por/alugar/tipo/';?>">Alugar por Tipo</a></p>
                    <p><a href="<?php echo base_url().'por/alugar/bairro/';?>">Alugar por Bairro</a></p>
                    <?php 
                    if ( $sobre['link_prefeitura'] )
                    {
                        ?>
                    <p><a href="<?php echo $sobre['link_prefeitura'];?>" title="Prefeitura da Cidade de <?php  echo $sobre['nome']; ?>"  target="_blank" rel="nofollow">Prefeitura de <?php  echo $sobre['nome']; ?></a></p>
                        <?php
                    }
                    ?>
                    <p><a href="http://www8.caixa.gov.br/siopiinternet/simulaOperacaoInternet.do?method=inicializarCasoUso" title="Simulador da Caixa" target="_blank" rel="nofollow">Simulador da Caixa</a></p>
                    <p><a href="http://www.caixa.gov.br/voce/habitacao/minha-casa-minha-vida/Paginas/default.aspx" title="Programa Minha Casa Minha Vida" target="_blank" rel="nofollow">Minha Casa Minha Vida</a></p>
                    <p><a href="<?php echo $sobre['portal'];?>/imobiliarias/" title="Encontre as imobiliárias de <?php echo $sobre['nome'];?>" target="_blank">Imobiliárias em <?php echo $sobre['nome']; ?></a></p>
                    <!-- <a href="https://www42.bb.com.br/portalbb/creditoImobiliario/Proposta,2,2250,2250.bbx" title="Simulador do Banco do Brasil" target="_blank">Simulador do Banco do Brasil</a> -->
                </div>
            </div>
        </div><!-- .fundo-azul -->
        <div class="container nav-sub">
            <div class="row ">
                <div class="col-lg-5 col-sm-5 col-md-5 col-xs-12">  
                    <ul class="nav navbar-nav ">
                        <li class="pull-left"><a href="<?php echo base_url();?>imobiliarias" class="" role="button" >Imobiliárias</a></li>
                        <li class="pull-left"><a href="<?php echo base_url();?>quem_somos" class="Saiba mais sobre nós">| Quem Somos</a> </li>
                        <li class="pull-left"> <a href="<?php echo base_url();?>rede" title="Rede de Portais no seu estado">| Rede</a></li>
                        
                        <!--
                        <li class="pull-left"><a href="#" class="">| Termos de uso</a> </li>
                        <li class="pull-left"><a href="#" class="">| Politica de privacidade</a> </li>
                        <li class="pull-left"><a href="<?php echo base_url();?>mapa_do_site" class="" title="Mapa do Site - Ajuda de navegação">| Mapa do site</a> </li>
                        -->
                    </ul>
                </div>
                <div class="col-lg-1 col-sm-1 col-md-1 col-xs-6">
                        

                        <div class="col-lg-2 col-sm-6 col-md-6 col-xs4 hidden-md hidden-sm">
                            <a href="https://www.youtube.com/user/Portaisimobiliarios" target="_blank" class="nav-ico" rel="nofollow">
                                <img src="<?php echo base_url();?>images/ico-youtube-preto.png" alt="Assine nosso Canal">
                            </a>
                        </div>
                        <?php 
                        if ( isset($plus) ) :
                            ?>
                            <div class="col-lg-2 col-sm-6 col-md-6 col-xs4 plus-rodape visible-lg" >
                                <?php 
                                echo $plus;
                                ?>
                            </div>
                            <?php
                        endif;
                        ?>
                    <?php 
                        if ( isset($facebook) ) :
                            //echo '<div class="col-lg-2 col-sm-6 col-md-6 col-xs4 facebook-rodape">'.$facebook.'</div>';
                        endif;
                        ?>
                 </div>
                 <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                     <a href="http://www.pow.com.br/ope" target="_blank" class="btn btn-topo com-image"><img src="<?php echo base_url();?>images/icones/ico_painel.png" ></span>&nbsp;&nbsp;Acesso ao painel do anunciante</a>
                     <a href="<?php echo base_url();?>anuncie" class="btn btn-topo laranja com-image"><img src="<?php echo base_url();?>images/icones/ico_anunci.png" ></span>&nbsp;&nbsp;Anuncie seus imóveis aqui</a>
                </div>
            </div>
        </div>
        <div class="divider-modo1">
            
        </div>
        
        <div class="row container">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 logos pull-right">
                <?php
                /*
                <a href="<?php echo base_url();?>" class="navbar-brand col-lg-5 col-sm-5 col-md-5 col-xs-5">
                    <img src="<?php echo str_replace('/m','',base_url()).'imagens/'.$logo;?>" alt="Os imóveis e as Imobiliárias da cidade reunidos aqui!" class="img-responsive" >
                </a>
                */
                ?>
                <a href="http://www.portaisimobiliarios.com.br" title="Visite a rede de portais imobiliários" target="_blank" class="col-lg-3 col-sm-3 col-md-3 col-xs-3">
                    <img src="<?php echo base_url();?>/images/logo_portais_imobiliarios.png" alt="Visite a rede de portais imobiliários" class="img-responsive " target="_blank">
                </a>
                <a href="http://www.pow.com.br" title="POW Internet" target="_blank" class="col-lg-4 col-sm-4 col-md-4 col-xs-4">
                    <img src="<?php echo base_url();?>/images/logopow2.png" alt="POW Internet" class="img-responsive" target="_blank" style="margin-top: 10px;">
                </a>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 chamada pull-left">
                <p><?php  echo ucfirst($sobre['gentilico']); ?>, o Portal <?php  echo substr($sobre['portal'], 11); ?> | Imóveis <?php  echo $sobre['nome']; ?> divulga <a href="<?php echo base_url().'imobiliarias/'?>">imobiliárias em <?php  echo $sobre['nome']; ?></a> e região, é integrante da rede <a href="http://www.portaisimobiliarios.com.br" title="Rede Portais Imobiliários">Portais Imobiliários</a>, que reúne imobiliárias e imóveis em todo o Brasil. Acesse nossa <a href="<?php echo base_url();?>estatistica">página de estatística</a> e veja os valores médios dos imóveis em <?php  echo $sobre['nome']; ?> por tipo de imóvel e bairro.</p>
                    
            </div>
            
        </div>
        <div class="divider-modo2">
        </div>
        
        <div class="row container">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 consideracoes">
                <p class="text-center"><i>"Graça seja convosco, e paz, da parte de Deus nosso Pai, e do Senhor Jesus Cristo." 1CO 1:3</i></p>
                <p class="text-center"><i>O sangue de Jesus Cristo, filho do Deus vivo, te purifica de todos os pecados.</i></p>
            </div>
        </div>
        
            
    </footer>


<?php 
    if ( isset($tag_adwords) ) : 
        echo $tag_adwords;
    endif;
    ?>
<div class="modal fade reserva-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php
    echo ( isset($includes) ? $includes : '' );
    echo ( isset($analytics) ? html_entity_decode($analytics) : '' );
    ?>
</body>

</html>