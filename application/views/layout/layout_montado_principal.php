<!DOCTYPE html!>
<html lang=pt-br>
<head>
	<meta charset="UTF-8" >
        <link rel="apple-touch-icon" href="<?php echo base_url();?>images/favicon.png">
        <link rel="apple-touch-icon-precomposed" href="<?php echo base_url();?>images/favicon.png"> 
        <link rel="icon" href="<?php echo base_url();?>images/favicon.png" type="image/x-icon">
        <link rel="shortcut icon" href="<?php echo base_url();?>images/favicon.png" type="image/x-icon">
        <link rel="shortcut icon" href="<?php echo base_url();?>images/favicon.png" type="image/vnd.microsoft.icon">
	<meta name="description" content="<?php if ( isset( $description ) ) : echo $description; endif; ?>" />
	<meta name="keywords" content="<?php if ( isset( $keywords ) ) : echo $keywords; endif;?>" />
	<meta name="author" content="POW Internet - http://www.powinternet.com/" />
        <link href='http://fonts.googleapis.com/css?family=Droid+Sans:400,700' rel='stylesheet' type='text/css'>
	<title><?php if ( isset( $titulo ) ) : echo $titulo; endif; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php 
	echo ( isset($includes) ? $includes : '' );
        echo ( isset($analytics) ? $analytics : '' );
?>
        
</head>
<body>
    <header>
        <div class="container">
            <div class="row">
                <div class="brand col-lg-5 col-sm-5 col-md-5 col-xs-12">
                    <a href="<?php echo base_url();?>" class="navbar-brand">
                        <img src="<?php echo str_replace('/m','',base_url()).'imagens/'.$logo;?>" alt="Os imóveis e as Imobiliárias da cidade reunidos aqui!">
                    </a>
                    <a href="http://www.portaisimobiliarios.com.br" title="Visite a rede de portais imobiliários" target="_blank" >
                        <img src="<?php echo base_url();?>/images/logo_portais_imobiliarios.png" alt="Visite a rede de portais imobiliários" class="padding10">
                    </a>
                </div>
                <div class="col-lg-7 col-sm-7 col-md-7 col-xs-12">
                    <div class="row">
                        <div class="col-lg-7 col-lg-offset-5 col-sm-7 col-sm-offset-5 col-md-7 col-md-offset-5 col-xs-12 padding-no-right">
                            <ul class="list-inline pull-right menu-login">
                                <li class="text-right favoritos btn"><span class="glyphicon glyphicon-star-empty"></span> Imóveis Favoritos</li>
                                <li class="divider"></li>
                                <li class="text-right login btn">Login</li>
                                <li class="divider"></li>
                                <li class="text-right cadastre btn">Cadastre-se</li>
                            </ul>
                        </div>
                    </div>
                    <div class="row">
                        <nav class="navbar navbar-default" role="navigation">
                            <div class="navbar-header">
                                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                                    <span class="sr-only">Navegação reduzida</span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>
                            </div>
                            <div class="collapse navbar-collapse pull-right" id="bs-example-navbar-collapse-1">
                                <ul class="nav navbar-nav">
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Comprar <b class="caret"></b></a>
                                        <ul class="dropdown-menu">
                                            <li><a href="#">Apartamento</a></li>
                                            <li><a href="#">Área</a></li>
                                            <li><a href="#">Sobrado</a></li>
                                        </ul>
                                    </li>
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Alugar <b class="caret"></b></a>
                                        <ul class="dropdown-menu">
                                            <li><a href="#">Apartamento</a></li>
                                            <li><a href="#">Área</a></li>
                                            <li><a href="#">Sobrado</a></li>
                                        </ul>
                                    </li>
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Alugar Dia <b class="caret"></b></a>
                                        <ul class="dropdown-menu">
                                            <li><a href="#">Apartamento</a></li>
                                            <li><a href="#">Casa</a></li>
                                        </ul>
                                    </li>
                                    
                                    <li>
                                        <a href="#">Encontre para mim</a>
                                    </li>
                                    <li>
                                        <a href="#">Blog</a>
                                    </li>
                                    <li>
                                        <a href="#">Anuncie</a>
                                    </li>
                                    <li >
                                        <a href="#" class="nav-ico">
                                            <img src="<?php echo base_url();?>images/ico-facebook.png" alt="Curta nosso Facebook">
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="nav-ico">
                                            <img src="<?php echo base_url();?>images/ico-youtube.png" alt="Assine nosso Canal">
                                        </a>
                                    </li>
                                    <li >
                                        <a href="#" class="nav-ico">
                                            <img src="<?php echo base_url();?>images/ico-google+.png" alt="Adicione aos seus circulos">
                                        </a>
                                    </li>
                                </ul>
                            </div><!-- /.navbar-collapse -->
                        </nav>
                    </div>
                </div>
            </div>
        </div>
            
    </header>
    <div class="divider-modo1"></div>
    
    <div class="filtro-horizontal">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm5 col-xs-12">
                    <h1 class="titulo-cidade">
                        <small><strong>Encontre os imóveis que desejar em</strong> </small><br>São José dos Pinhais / PR <small class="mudar_cidade">(mudar cidade)</small> <span class="caret"></span>
                    </h1>
                </div>
                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12 ">
                        <label for="quero">
                            Eu quero:
                        </label>
                        <button class="btn btn-busca" data-toggle="modal" data-target="#modal-quero"><span class="pull-left">Selecione</span> <span class="caret pull-right"></span></button>
                    </div>
                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12 form-group">
                        <label for="quero">
                            Tipo de imóvel:
                        </label>
                        <button class="btn btn-busca" data-toggle="modal" data-target="#modal-quero"><span class="pull-left">Selecione</span> <span class="caret pull-right"></span></button>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 form-group">
                        <button class="btn btn-busca-submit">Encontre</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container">
        <div class="row">
            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-8">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 publicidade">
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <h6>Publicidade</h6>
                                <h3>Rocco Imóveis São José</h3>
                                <button class="btn btn-contato-email">Contato por e-mail</button>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                <img src="<?php echo base_url();?>images/banner-publicidade.png" class="img-responsive">
                            </div>
                        </div>
                    </div>
                </div>
                
                
                <div class="row lista">
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 ">
                        <div class="thumbnail venda">
                            <a href="#">
                                <h4>Venda</h4>
                                <img src="<?php echo base_url();?>images/coringa180.png" class="img-responsive">
                                <div class="caption">
                                    <h3>Barreirinha</h3>
                                    <p>Casa - Casa de condominio</p>
                                    <hr>
                                    <p><strong>70 m</strong> de área útil</p>
                                    <p><strong>3</strong> dormitórios</p>
                                    <p><strong>1</strong> Vaga</p>
                                    <a href="#" class="btn btn-link">Mais detalhes</a>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 ">
                        <div class="thumbnail locacao">
                            <a href="#">
                                <h4>Locação</h4>
                                <img src="<?php echo base_url();?>images/coringa180.png" class="img-responsive">
                                <div class="caption">
                                    <h3>Barreirinha</h3>
                                    <p>Casa - Casa de condominio</p>
                                    <hr>
                                    <p><strong>70 m</strong> de área útil</p>
                                    <p><strong>3</strong> dormitórios</p>
                                    <p><strong>1</strong> Vaga</p>
                                    <a href="#" class="btn btn-link">Mais detalhes</a>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 ">
                        <div class="thumbnail venda">
                            <a href="#">
                                <h4>Venda</h4>
                                <img src="<?php echo base_url();?>images/coringa180.png" class="img-responsive">
                                <div class="caption">
                                    <h3>Barreirinha</h3>
                                    <p>Casa - Casa de condominio</p>
                                    <hr>
                                    <p><strong>70 m</strong> de área útil</p>
                                    <p><strong>3</strong> dormitórios</p>
                                    <p><strong>1</strong> Vaga</p>
                                    <a href="#" class="btn btn-link">Mais detalhes</a>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 ">
                        <div class="thumbnail locacao">
                            <a href="#">
                                <h4>Locação</h4>
                                <img src="<?php echo base_url();?>images/coringa180.png" class="img-responsive">
                                <div class="caption">
                                    <h3>Barreirinha</h3>
                                    <p>Casa - Casa de condominio</p>
                                    <hr>
                                    <p><strong>70 m</strong> de área útil</p>
                                    <p><strong>3</strong> dormitórios</p>
                                    <p><strong>1</strong> Vaga</p>
                                    <a href="#" class="btn btn-link">Mais detalhes</a>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 publicidade">
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <h6>Publicidade</h6>
                                <h3>Corteze Imóveis São José</h3>
                                <button class="btn btn-contato-email">Contato por e-mail</button>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                <img src="<?php echo base_url();?>images/banner-publicidade2.png" class="img-responsive">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row lista">
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 ">
                        <div class="thumbnail venda">
                            <a href="#">
                                <h4>Venda</h4>
                                <img src="<?php echo base_url();?>images/coringa180.png" class="img-responsive">
                                <div class="caption">
                                    <h3>Barreirinha</h3>
                                    <p>Casa - Casa de condominio</p>
                                    <hr>
                                    <p><strong>70 m</strong> de área útil</p>
                                    <p><strong>3</strong> dormitórios</p>
                                    <p><strong>1</strong> Vaga</p>
                                    <a href="#" class="btn btn-link">Mais detalhes</a>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 ">
                        <div class="thumbnail locacao">
                            <a href="#">
                                <h4>Locação</h4>
                                <img src="<?php echo base_url();?>images/coringa180.png" class="img-responsive">
                                <div class="caption">
                                    <h3>Barreirinha</h3>
                                    <p>Casa - Casa de condominio</p>
                                    <hr>
                                    <p><strong>70 m</strong> de área útil</p>
                                    <p><strong>3</strong> dormitórios</p>
                                    <p><strong>1</strong> Vaga</p>
                                    <a href="#" class="btn btn-link">Mais detalhes</a>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 ">
                        <div class="thumbnail venda">
                            <a href="#">
                                <h4>Venda</h4>
                                <img src="<?php echo base_url();?>images/coringa180.png" class="img-responsive">
                                <div class="caption">
                                    <h3>Barreirinha</h3>
                                    <p>Casa - Casa de condominio</p>
                                    <hr>
                                    <p><strong>70 m</strong> de área útil</p>
                                    <p><strong>3</strong> dormitórios</p>
                                    <p><strong>1</strong> Vaga</p>
                                    <a href="#" class="btn btn-link">Mais detalhes</a>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 ">
                        <div class="thumbnail locacao">
                            <a href="#">
                                <h4>Locação</h4>
                                <img src="<?php echo base_url();?>images/coringa180.png" class="img-responsive">
                                <div class="caption">
                                    <h3>Barreirinha</h3>
                                    <p>Casa - Casa de condominio</p>
                                    <hr>
                                    <p><strong>70 m</strong> de área útil</p>
                                    <p><strong>3</strong> dormitórios</p>
                                    <p><strong>1</strong> Vaga</p>
                                    <a href="#" class="btn btn-link">Mais detalhes</a>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                
                
                
                
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 publicidade-vertical">
                    <h6>Publicidade nacional</h6>
                    <h3>POW Internet</h3>
                    <button class="btn btn-contato-email">Contato por e-mail</button>
                    <img src="<?php echo base_url();?>images/banner-publicidade-pq1.png" class="img-responsive">
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 publicidade-vertical">
                    <h6>Publicidade local</h6>
                    <h3>Solaris Imóveis</h3>
                    <button class="btn btn-contato-email">Contato por e-mail</button>
                    <img src="<?php echo base_url();?>images/banner-publicidade-pq2.png" class="img-responsive">
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 publicidade-vertical">
                    <h6>Publicidade local</h6>
                    <h3>Capellini Imóveis</h3>
                    <button class="btn btn-contato-email">Contato por e-mail</button>
                    <img src="<?php echo base_url();?>images/banner-publicidade-pq3.png" class="img-responsive">
                </div>
            </div>
        </div>
    </div>
    
    
    
    
    