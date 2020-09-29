<li class="imovel <?php echo $favoritos ? 'col-md-12 col-sm-12 favoritos' : ($destaque && $destaque == 'relacionado' ? 'col-md-4 col-sm-4' : 'col-md-4 col-sm-6 ');?>  item-grid <?php echo ( ($destaque) ? 'destaque' : 'organico' );?> elemento-<?php echo $item->_id;?>" data-item="<?php echo $item->_id;?>" itemscope itemtype="http://schema.org/Product" data-link="<?php echo $link?>" data-origem="<?php echo $origem;?>">
        <div class="c-content-product-2 c-bg-white box">
    <div class="row">
            <link itemprop="additionalType" href="http://www.productontology.org/id/<?php echo $item->imoveis_tipos_english;?>"/>
            <meta itemprop="name" content="<?php echo $titulo['h2'];?>"/>
            <span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                <span itemprop="seller" itemscope itemtype="http://schema.org/RealEstateAgent">
                    <meta itemprop="name" content="<?php echo $item->nome_empresa;?>"/>
                </span>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                    <?php $mais_images = ( isset($images) && ! empty($images) ) ? TRUE : FALSE; ?>
                    <div class="espaco-image slide carousel item-<?php echo $item->_id;?>" data-item="<?php echo $item->_id;?>" data-images="<?php echo $mais_images ? $images : '';?>">
                        <a class="" href="<?php echo $link;?>" title="<?php echo $item->nome;?>" target="_blank">
                        <div class="carousel-inner" role="listbox">
                            <div class="item active" data-atual="0">
                                <div class=" link-img center-block" title="<?php echo $item->nome;?>" >
                                    <center><img itemprop="image" data-src="<?php echo str_replace(array('http://www.powempresas.com/','https://www.powempresas.com/'), base_url(), $arquivo);?>" alt="<?php echo $alt_image;?>" src="<?php echo base_url();?>imagens/naodisponivel.jpg" alt="<?php echo $alt_image;?>" class="img-responsive"></center>
                                </div>
                            </div>
                        </div>
                        </a>
                        <?php 
                        if ( $mais_images ) :
                            ?>
                            <a class="left carousel-control" href="#carousel" role="button" data-slide="prev" data-item="<?php echo $item->_id;?>" data-atual="0">
                                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                <span class="sr-only">Anterior</span>
                            </a>
                            <a class="right carousel-control" href="#carousel" role="button" data-slide="next" data-item="<?php echo $item->_id;?>" data-atual="0">
                                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span> 
                                <span class="sr-only">Proximo</span>
                            </a>
                            <?php
                        endif;
                        ?>
                    </div>
                </div>
                <div class="infos row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 espaco-descricao-titulo" data-item="<?php echo $item->_id;?>">
                        <div class="c-info-list">
                            <a class="" href="<?php echo $link;?>" title="<?php echo $item->nome;?>" target="_blank">
                                <div class="espaco-titulo">
                                    <h2 class="c-title c-font-bold azul-pow" ><?php echo $titulo['h2'];?><br>
                                        <small>
                                            <span class=" glyphicon glyphicon-map-marker"></span>
                                            <?php echo $titulo['localizacao'];?>
                                        </small>
                                    </h2>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 espaco-descricao" data-item="<?php echo $item->_id;?>">
                        <a class="" href="<?php echo $link; ?>" title="<?php echo $item->nome; ?>" target="_blank">
                            <span itemprop="description" >
                                <div class="c-desc c-font-14 c-font-thin <?php echo $favoritos ? 'favorito-hide' : ''; ?>">
                                    <?php
                                    if (isset($item->descricao) && !empty($item->descricao)) :
                                        $descricao = str_replace(array('<br>', '<br />', '<br/>', '</br>'), '-', tira_html($item->descricao));
                                        echo '<p class="descricao">' . ucfirst(mb_strtolower(word_limiter(strip_tags($descricao, '<a>'), 25))) . '</p>';
                                    endif;
                                    ?>
                                </div>
                            </span>
                        </a>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 espaco-area <?php echo $favoritos ? 'favorito-hide' : '';?>" data-item="<?php echo $item->_id;?>">
                        <a class="" href="<?php echo $link;?>" title="<?php echo $item->nome;?>" target="_blank">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 espaco-preco">
                                    <div class="c-price c-font-16 azul-pow c-font-bold">
                                        <?php
                                        $valores = trim($valores);
                                        if (!empty($valores)) :
                                            echo $valores;
                                            ?>
                                            <meta itemprop="priceCurrency" content="BRL"/>
                                            <?php
                                        endif;
                                        ?>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 espaco-info">
                                    <span itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
                                        <div class="opcionais azul-pow <?php echo $favoritos ? 'favorito-hide' : ''; ?>">
                                            <?php echo $opcionais; ?>
                                        </div>
                                    </span>
                                    <p class="font-codigo"> <img src="<?php echo base_url();?>images/icones/codigo.png" >Código PI: <?php echo $item->_id;?></p>
                                </div>
                            </div>
                        </a>                
                    </div>
                        <div class="col-lg-12  col-md-12 col-sm-12 col-xs-12 pull-right espaco-btn">
                            <?php
                            $col= 6;
                            $link_whats = '';
                            if ( isset($item->imobiliaria_whatsapp) && ! empty($item->imobiliaria_whatsapp) ):
                                $col = 3;
                                ?>
                                            <div class="btn-group btn-vertical">
                                                <button type="button" class="btn btn-whats hidden-xs whats-desktop  btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fa fa-whatsapp"></i> WhatsApp
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a target="_blank" class="btn ver-telefone-whats-lista" оnClick="ga('send', 'event', 'botao', 'clique');" href="https://web.whatsapp.com/send?phone=+55<?php echo $item->imobiliaria_whatsapp.'&text='. urlencode('Gostaria de saber mais sobre o imóvel: '.$link);?>" data-item="<?php echo $item->id;?>" data-log="<?php echo $origem;?>">Conversar com a imobiliária</a>
                                                    </li>
                                                    <li>
                                                        <a target="_blank" class="btn ver-telefone-whats-lista" оnClick="ga('send', 'event', 'botao', 'clique');" href="https://web.whatsapp.com/send?<?php echo '&text='. urlencode('Conheça esse imóvel: '.$link);?>" data-item="<?php echo $item->id;?>" data-log="<?php echo $origem;?>">Enviar para um amigo</a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="btn-group btn-vertical">
                                                <button type="button" class="btn btn-whats whats-mobile btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fa fa-whatsapp">WhatsApp</i> 
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a target="_blank" class="btn  ver-telefone-whats-lista" оnclick="ga('send', 'event', 'botao', 'clique');" href="https://api.whatsapp.com/send?phone=+55<?php echo $item->imobiliaria_whatsapp.'&text='. urlencode('Gostaria de saber mais sobre o imóvel: '.$link);?>" data-item="<?php echo $item->id;?>" data-log="<?php echo $origem;?>">Conversar com a imobiliária</a></li>
                                                    <li><a target="_blank" class="btn  ver-telefone-whats-lista" оnclick="ga('send', 'event', 'botao', 'clique');" href="https://api.whatsapp.com/send?<?php echo '&text='. urlencode('Conheça esse imóvel: '.$link);?>" data-item="<?php echo $item->id;?>" data-log="<?php echo $origem;?>">Enviar para um amigo</a></li>
                                                </ul>
                                            </div>
                                <?php
                            endif;
                            ?>
                            <a class="btn btn-detalhes detalhes btn-azul-pow col-md-<?php echo $col;?> col-sm-<?php echo $col;?>  col-xs-<?php echo $col;?>" href="<?php echo $link;?>" title="<?php echo $item->nome;?>" target="_blank"> 
                                Detalhes 
                            </a>
                            <button class="btn btn-favorito btn-vermelho-pow favoritar favorito-<?php echo $item->_id;?>  col-md-<?php echo $col;?> col-sm-<?php echo $col;?>  col-xs-<?php echo $col;?>" type="button" data-item="<?php echo $item->_id;?>">
                                <span class="hidden-xs glyphicon glyphicon-star<?php echo ( array_key_exists($item->_id, $itens_favoritos) ? '' : '-empty' );?>"></span> 
                                 Favoritos
                            </button>
                        </div>
                <!-- </div> -->
            </span>
        </div>
        </div>
    </div>
</li>
