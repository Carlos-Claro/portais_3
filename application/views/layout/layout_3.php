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
        if ( $robot ) :
            echo '<meta name="robots" content="index,follow"/>'.PHP_EOL;
        else :
            echo '<meta name="robots" content="noindex,nofollow"/>'.PHP_EOL;
        endif;
        
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
            <meta name="theme-color" content="#285384">
            <link rel="manifest" href="/manifest.json">
</head>
<body data-page="" data_url="<?php echo base_url();?>" onload="<?php echo isset($on_load) ? $on_load : ''; ?>" class="c-layout-header-fixed c-layout-header-mobile-fixed c-layout-header-topbar c-layout-header-topbar-collapse" data-is-mobile="<?php echo $mobile;?>">
    <?php echo isset($google_tag) ? $google_tag : '';?>
    
    <header class="c-layout-header c-layout-header-4 c-layout-header-default-mobile" data-minimize-offset="80">
        <div class="c-topbar c-topbar-light c-solid-bg">
            <div class="container-fluid">
                <nav class="c-top-menu c-pull-right">
                    <ul class="c-links c-theme-ul">
                        <li><a href="<?php echo base_url();?>nao_encontrei">Não Encontrei</a></li>
                        <li class="c-divider">|</li>
                        <li><a href="<?php echo base_url();?>estatistica">Estatisticas</a></li>
                        <li class="c-divider">|</li>
                        <li><a href="http://www.portaisimobiliarios.com.br/" target="_blank">Rede</a></li>
                        <li class="c-divider">|</li>
                        <li><a href="http://www.portaisimobiliarios.com.br/oprojeto.php" target="_blank">Quem somos</a></li>
                        <li class="c-divider">|</li>
                        <li><a href="http://www.portaisimobiliarios.com.br/anuncio.php" target="_blank" class="vermelho-pow">Anuncie em nosso portal</a></li>
                        <li class="c-divider">|</li>
                        <li><a href="https://admin.powempresas.com" class="azul-pow">Área do assinante</a></li>
                    </ul>
                </nav>
            </div>
        </div>
        <div class="c-navbar">
            <div class="container-fluid">
                <div class="c-navbar-wrapper clearfix">
                    <div class="c-brand c-pull-left">
                        <a href="<?php echo base_url();?>" title="<?php  echo substr($sobre['portal'], 11); ?> | As imobiliárias e imóveis de <?php  echo $sobre['nome']; ?>  reunidos aqui!" class="c-logo">
                            <img src="<?php echo str_replace('/m','',base_url()).'imagens/'.$logo;?>" alt="<?php  echo substr($sobre['portal'], 11); ?> | As imobiliárias e imóveis de <?php  echo $sobre['nome']; ?>  reunidos aqui!" class="c-desktop-logo">
                            <img src="<?php echo str_replace('/m','',base_url()).'imagens/'.$logo;?>" alt="<?php  echo substr($sobre['portal'], 11); ?> | As imobiliárias e imóveis de <?php  echo $sobre['nome']; ?>  reunidos aqui!"  class="c-desktop-logo-inverse">
                            <img src="<?php echo str_replace('/m','',base_url()).'imagens/'.$logo;?>" alt="<?php  echo substr($sobre['portal'], 11); ?> | As imobiliárias e imóveis de <?php  echo $sobre['nome']; ?>  reunidos aqui!"  class="c-mobile-logo">
                        </a>
                        <button class="c-hor-nav-toggler" type="button" data-target=".c-mega-menu">
                            Menu
                        </button>
                        <button class="c-topbar-toggler" type="button">
                            Portal
                        </button>
                        <button class="c-search-toggler filtro-atalho" type="button">
                          <i class="fa fa-filter"></i> Filtrar imóveis
                        </button>
                    </div>
                    <form class="c-quick-search" action="<?php echo base_url();?>" method="get">
                        <input type="text" name="id" placeholder="Digite o código do imóvel e aperte enter." value="" class="form-control busca-id" autocomplete="off">
                        <span class="c-theme-link">&times;</span>
                    </form>
                    <nav class="c-mega-menu c-pull-right c-mega-menu-light c-mega-menu-light-mobile c-fonts-uppercase c-fonts-bold">
                        <ul class="nav navbar-nav c-theme-nav"> 
                            <li >
                                <a href="return false" data-toggle="dropdown" id="imoveis-menu" class="c-link dropdown-toggle js-activated hold-on-click vermelho-pow">Imóveis<span class="c-arrow c-toggler"></span></a>
                                <div class="imoveis-menu dropdown-menu c-menu-type-mega c-menu-type-fullwidth" style="min-width: auto">
                                    <div class="row">
                                        <?php echo $menu;?>
                                    </div>
                                </div>
                            </li>
                            <li >
                                <a href="<?php echo base_url();?>imobiliarias" class="c-link azul-pow">Imobiliárias</a>
                            </li>
                            <li><a class="c-link dropdown-toggle azul-pow" href="<?php echo base_url();?>nao_encontrei">Não Encontrei</a></li>
                            <li >
                                <a href="javascript:;" id="links-importantes" class="c-link dropdown-toggle azul-pow">Links Importantes<span class="c-arrow c-toggler"></span></a>
                                <ul class="dropdown-menu links-importantes c-menu-type-mega c-menu-type-fullwidth" style="min-width: auto">
                                    <li>
                                        <ul class="dropdown-menu c-menu-type-inline">
                                            <li>
                                                <h3>Venda</h3>
                                            </li>
                                            <li>
                                                <a href="<?php echo base_url();?>">Imóveis à Venda em <?php  echo $sobre['nome']; ?></a>
                                            </li>
                                            <li>
                                                <a href="#">Apartamentos à Venda em <?php  echo $sobre['nome']; ?></a>
                                            </li>
                                            <li>
                                                <a href="#">Casas à Venda em <?php  echo $sobre['nome']; ?></a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <ul class="dropdown-menu c-menu-type-inline">
                                            <li>
                                                <h3>Alugar</h3>
                                            </li>
                                            <li>
                                                <a href="#">Imóveis para alugar em <?php  echo $sobre['nome']; ?></a>
                                            </li>
                                            <li>
                                                <a href="#">Apartamentos para alugar em <?php  echo $sobre['nome']; ?></a>
                                            </li>
                                            <li>
                                                <a href="#">Casas para alugar em <?php  echo $sobre['nome']; ?></a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <ul class="dropdown-menu c-menu-type-inline">
                                            <li>
                                                <h3>Links da Cidade</h3>
                                            </li>
                                            <?php 
                                            if ( $sobre['link_prefeitura'] )
                                            {
                                                ?>
                                                <li>
                                                    <a href="<?php echo $sobre['link_prefeitura'];?>" title="Prefeitura da Cidade de <?php  echo $sobre['nome']; ?>"  target="_blank" rel="nofollow">Prefeitura de <?php  echo $sobre['nome']; ?></a>
                                                </li>
                                                <?php
                                            }
                                            ?>
                                            <li>
                                                <a href="http://www8.caixa.gov.br/siopiinternet/simulaOperacaoInternet.do?method=inicializarCasoUso" title="Simulador da Caixa" target="_blank" rel="nofollow">Simulador da Caixa</a>
                                            </li>
                                            <li>
                                                <a href="http://www.caixa.gov.br/voce/habitacao/minha-casa-minha-vida/Paginas/default.aspx" title="Programa Minha Casa Minha Vida" target="_blank" rel="nofollow">Minha Casa Minha Vida</a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                            <li class="c-quick-sidebar-toggler-wrapper">	
                                <a href="#" class="c-quick-sidebar-toggler c-link azul-pow btn-favorito">		     		
                                    Favoritos
                                </a>
                            </li>
                            <li class="c-search-toggler-wrapper codigo-busca">
                                <a  href="#" class=" c-quick-sidebar-toggler c-link azul-pow c-search-toggler">Por código</a>
                            </li>
                            <li class="c-search-toggler-wrapper icon-busca">
                                <a  href="#" class="c-btn-icon icon-voltar c-layout-go2top azul-pow"><i class="fa fa-arrow-circle-up"></i></a>
                            </li>
                        </ul>
                    </nav>
                </div>			
            </div>
        </div>
    </header>
    <nav class="c-layout-quick-sidebar">
        <div class="c-header">
            <button type="button" class="c-link c-close">
                <i class="icon-login"></i>		
            </button>
            <a href="javascript:;" class="page-quick-sidebar-toggler">
                <i class="icon-login"></i>
            </a>
        </div>
        <div class="c-content">
            <div class="c-section">
                <h3>Seus itens favoritos</h3>
                <div class="c-settings c-demos c-bs-grid-reset-space">	
                    <div class="row">
                        <div class="col-md-12">
                            <a href="#" class="c-demo-container c-demo-img-lg">
                                <div class="c-demo-thumb active">
                                    <button class="btn btn-info comparar-favoritos" type="button">Comparar</button>
                                    <button class="btn btn-blue comunicar-favoritos" type="button">Entrar em contato com os selecionados</button>
                                </div>
                            </a>	
                        </div>
                    </div>
                </div>
                <ul class="list-unstyled lista-favoritos"></ul>
                <div class="c-settings c-demos c-bs-grid-reset-space">	
                    <div class="row">
                        <div class="col-md-12">
                            <a href="#" class="c-demo-container c-demo-img-lg">
                                <div class="c-demo-thumb active">
                                    <button class="btn btn-blue comunicar-favoritos" type="button">Entrar em contato com os selecionados</button>
                                </div>
                            </a>	
                        </div>
                    </div>
                </div>
            </div>	
        </div>
    </nav><!-- END: LAYOUT/SIDEBARS/QUICK-SIDEBAR -->
    <div class="c-layout-page">
        <div class="row">
            <div class="col-md"></div>
        </div>
    <?php 
    echo isset($conteudo) ? $conteudo : '';
    ?>
    </div>
    <a name="footer"></a>
    <footer class="c-layout-footer c-layout-footer-2">
        <?php if ( ( isset($sobre['mostra']) && $sobre['mostra'] ) && isset($sobre['sobre']) && !empty($sobre['sobre']) ) :?>
        <div class="c-content-box c-size-md">
            <div class="container-fluid">
                <div class="c-content-bar-1 c-opt-1">
                    <h3 class="c-font-uppercase c-font-bold titulo-ficha c-font-center">Sobre <?php echo $sobre['nome']; ?></h3>
                    <div class="c-line-center" style="margin-left: auto; margin-right: auto;"></div>
                    <p class="c-font-center c-font-black">
                        <?php  echo $sobre['sobre']; ?>
                    </p>
                    <?php if ( $sobre['link_prefeitura'] ):?>
                        <a class="link-prefeitura" href="<?php echo $sobre['link_prefeitura'];?>" title="Visite o site da Prefeitura da Cidade de <?php  echo $sobre['nome']; ?>"  target="_blank" class="">Visite o site da Prefeitura de <?php  echo $sobre['nome']; ?></a>
                    <?php endif;?>
                </div>
            </div> 
            <div class="container-fluid">
                <div class="c-content-bar-1 c-opt-1">
                    <h3 class="c-font-uppercase c-font-bold titulo-ficha c-font-center">O <?php  echo ucfirst($sobre['gentilico']); ?></h3>
                    <div class="c-line-center" style="margin-left: auto; margin-right: auto;"></div>
                    <p class="c-font-center c-font-black"><?php  echo ucfirst($sobre['gentilico']); ?>, o Portal <?php  echo substr($sobre['portal'], 11); ?> | Imóveis <?php  echo $sobre['nome']; ?> divulga <a href="<?php echo base_url().'imobiliarias/'?>">imobiliárias em <?php  echo $sobre['nome']; ?></a> e região, é integrante da rede <a href="http://www.portaisimobiliarios.com.br" title="Rede Portais Imobiliários">Portais Imobiliários</a>, que reúne imobiliárias e imóveis em todo o Brasil. Acesse nossa <a href="<?php echo base_url();?>estatistica">página de estatística</a> e veja os valores médios dos imóveis em <?php  echo $sobre['nome']; ?> por tipo de imóvel e bairro.</p>
                </div>
            </div> 
        </div><!-- END: CONTENT/BARS/BAR-1 -->
        <?php endif;?>
        <div class="container-fluid fundo-portais">
                <div class="c-copyright">
                    <p class="c-font-oswald c-font-14 c-font-white">"Graça seja convosco, e paz, da parte de Deus nosso Pai, e do Senhor Jesus Cristo." 1CO 1:3</p>
                    <p class="c-font-oswald c-font-14 c-font-white">O sangue de Jesus Cristo, filho do Deus vivo, te purifica de todos os pecados.</p>
                    <p class="c-font-oswald c-font-14 c-font-white">Copyright &copy; Orionweb informações e tecnologias.</p>
                    <p class="c-font-oswald c-font-14 c-font-white">Tempo de processamento desta página: <span class="time"><?php echo $time;?></span>s</p>
                </div>
        </div>
    </footer>
    
<style type="text/css">
.center-e {
    text-align: center;
    color: #1f374a;
}
.center-e a{
    text-align: center;
    color: #1f374a;
    font-weight: bold;
}
.center-e a{
    text-align: center;
    color: #1f374a;
    font-weight: bold;
}
</style>
<div class="c-layout-go2top">
    <i class="fa fa-arrow-circle-up"></i>
</div>
<?php 

    if ( isset($tag_adwords) && ! LOCALHOST ) : 
        echo $tag_adwords;
    endif;
    ?>
<div class="modal fade container reserva-modal" tabindex="-1" >
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
    </div>
</div><!-- /.modal -->

<div class="modal fade container" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">                
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel"><span class="titulo-modal"></span><button style="float: right;" type="button" class="btn btn-default" data-dismiss="modal">Fechar</button></h4>
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
    echo ( isset($analytics) && ! LOCALHOST ? html_entity_decode($analytics) : '' );
    ?>
</body>

</html>