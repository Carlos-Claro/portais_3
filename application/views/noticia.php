<!-- navegação principal, breadscrumb, favoritos, contato, imprimir, voltar -->
<div class="fundo-azul ">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo base_url();?>" title="Voltar para a Página Inicial do Portal Imóveis <?php  echo $sobre['nome']; ?>">
                            <img src="<?php echo base_url();?>images/ico-i-branco.png" alt="Voltar para a Página Inicial do Portal Imóveis <?php  echo $sobre['nome']; ?>">
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo base_url().'blog/';?>" title="Voltar para Blog">
                            Blog
                        </a>
                    </li>
                    <li class="active">
                        <?php
                        if ( isset($item->titulo) && ! empty($item->titulo) ) :
                            echo character_limiter(strip_tags($item->titulo), 100);
                        endif;
                        ?>
                    </li>
                </ol>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 pull-right">
                <button class="btn btn-default voltar pull-right" type="button" data-item="<?php echo ( isset($item->titulo) && ! empty($item->titulo) ) ? $item->id : 0; ?>"><span class="glyphicon glyphicon-chevron-left pull-left"></span> <span class="hidden-xs pull-right">&nbsp;Voltar</span></button>
            </div>
        </div>
    </div>
</div>
<!-- inicio das informações titulos, galerias, contato-->
<div class="container corpo noticia">
    <div class="row ">
        <div class="col-lg-10 col-sm-10 col-md-10 col-xs-12 ">
            <?php 
            echo ( isset($publicidade['topo']) ) ? $publicidade['topo'] : '';
            if ( isset($item->titulo) && ! empty($item->titulo) ) :
            ?>
            
            <div itemscope itemtype="http://schema.org/Article" class="thumbnail">
                <?php 
                if ( isset($item->foto) && ! empty($item->foto) ) :
                    echo '<img itemprop="image" class="pull-left img-responsive img-thumbnail" src="http://www.guiasjp.com/fotos_noticias/'.$item->foto.'" alt="'.$item->foto.'">';
                endif;
                ?>
                <h1 itemprop="name"><?php echo $item->titulo;?></h1>
                <span itemprop="articleBody">
                    <?php echo str_replace('<a ', '<a rel="nofollow"',nl2br($item->texto));?>
                </span>
                <br>
                <a class="pull-right" href="<?php echo base_url();?>"> <span itemprop="author" itemscope itemtype="http://schema.org/Person">
                        <span itemprop="name">Matéria publicada no Portal <?php  echo substr($sobre['portal'],11); ?></span></span> em <span itemprop="datePublished" content="<?php echo date('Y-m-d ', $item->data);?>"><?php echo date('d/m/Y ', $item->data);?></span></a>
            </div>
            <?php
            else :
                header("HTTP/1.0 404 Not Found");
                echo '<h1>A notícia não foi encontrada.</h1>';
            endif;
            ?>
            <div class="row">
                <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                    <h2>Leia mais sobre o Mercado Imobiliário: </h2>
                    <?php 
                    if ( isset($itens['itens']) ) :
                        foreach( $itens['itens'] as $item_ ) :
                            ?>
                            <div class="col-lg-4 col-sm-4 col-md-4 col-xs-4">
                                <a href="<?php echo base_url();?>blog/<?php echo urlencode( tira_especiais(strip_tags($item_->titulo)) ).'/'.$item_->id;?>">
                                    <div class="thumbnail">
                                        <div class="caption">
                                            <h3><?php echo strip_tags($item_->titulo);?></h3>
                                            <?php
                                            echo character_limiter(strip_tags($item_->descricao), 100);
                                            ?>
                                        </div>
                                    </div>
                                </a>
                            </div>  
                            <?php 
                        endforeach;
                    endif;
                    ?>
                            
                </div>
            </div>
            
            <br>
            <?php 
            echo ( isset($publicidade['meio']) ) ? $publicidade['meio'] : '';
            ?>
            
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
            <?php 
            echo ( isset($publicidade['lateral_nacional']) ) ? $publicidade['lateral_nacional'] : '';
            echo ( isset($publicidade['lateral']) ) ? $publicidade['lateral'] : '';
            ?>
        </div>
    </div><!-- .cabecalho -->
</div><!-- .corpo -->
