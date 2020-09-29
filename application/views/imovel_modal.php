<div class="item-vertical <?php echo ( ($destaque) ? 'destaque' : 'organico' );?> elemento-<?php echo $item->_id;?>" data-item="<?php echo $item->_id;?>" itemscope itemtype="http://schema.org/Product" data-link="<?php echo $link?>" data-origem="<?php echo $origem;?>">
    <div class="row">
            <link itemprop="additionalType" href="http://www.productontology.org/id/<?php echo $item->imoveis_tipos_english;?>"/>
            <meta itemprop="name" content="<?php echo $titulo['h2'];?>"/>
            <span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                <span itemprop="seller" itemscope itemtype="http://schema.org/RealEstateAgent">
                    <meta itemprop="name" content="<?php echo $item->nome_empresa;?>"/>
                </span>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
                    <div class="espaco-image slide carousel item-<?php echo $item->_id;?>" data-item="<?php echo $item->_id;?>" data-images="<?php echo $mais_images ? $images : '';?>">
                        <div class="carousel-inner" role="listbox">
                            <?php 
                                if ( (isset($item->images) && count($item->images) > 0) || $video ) :
                                    ?>    
                                    <div id="carousel-master" class="carousel slide" >
                                        <div class="carousel-inner ">
                                            <?php 
                                            $qtde_total = 0;
                                            $ol = '';
                                            $chave = 0;
                                            if ( $video ) :
                                                $qtde_total++;
                                                echo '<div class="item active" data-ordem="'.$qtde_total.'">';
                                                ?>
                                                <div class="" id="video">
                                                    <center><iframe style="width:100%; height: auto; min-height: 300px;" src="<?php echo set_embed_video($item->video);?>" frameborder="0" allowfullscreen></iframe></center>
                                                </div>
                                                <?php
                                                echo '</div>';
                                                $ol .= '<li data-target="#carousel-master" data-slide-to="'.($chave + 1).'" >';
                                                $ol .= '<img src="'.base_url().'images/youtube.jpg">';
                                                $ol .= '</li>';
                                                $chave++;
                                            endif;
                                            foreach ( $item->images as $chave_ => $image ) :
                                                if ( isset($image) && ! empty( $image->arquivo ) ) :
                                                    $qtde_total++;
                                                    $descricao_image = ( isset($image->titulo) && ! empty($image->titulo) ) ? $image->titulo : $item->imoveis_tipos_titulo;
                                                    echo '<div class="item '.( ! $video ? ( ! $chave ? 'active' : '' ) : '' ).'" data-ordem="'.$qtde_total.'">';
                                                    $image_t5 = LOCALHOST ? $image->arquivo : str_replace(array('http://www.powempresas.com/','http://admin.powempresas.com/'),base_url(),$image->arquivo);
                                                    if ( isset($image->original) )
                                                    {
                                                        //$image->original = LOCALHOST ? $image->arquivo : str_replace(array('http://www.powempresas.com/','http://admin.powempresas.com/'),base_url(),$image->original);
                                                        $image_gr = set_arquivo_image($item->id, $image->original, $item->id_empresa, 1, '', $image->id, '650F');
                                                    }
                                                    else
                                                    {
                                                        $image_gr = str_replace('F_','650F_F_',str_replace(array('http://www.powempresas.com/','http://admin.powempresas.com/'),base_url(),$image->arquivo));
                                                    }
                                                    echo '<a data-lightbox="a" href="'.$image_gr.'" title="'.$descricao_image.'">';
                                                    echo '<center><img itemprop="image" src="'.$image_gr;
                                                    echo '" alt="'.$descricao_image.'" class="img-responsive"></center>';
                                                    echo '</a>';
                                                    echo '</div>';
                                                    $ol .= '<li data-target="#carousel-master" data-slide-to="'.($chave).'" class="'.( ! $video ? ( $chave ? 'active' : '' ): '' ).'">';
                                                    $ol .= '<center><img itemprop="image" src="'.$image_gr;
                                                    $ol .= '" alt="'.$descricao_image.'" class="img-responsive"></center>';
                                                    $ol .= '</li>';
                                                $chave++;
                                                endif;
                                            endforeach;
                                        ?>
                                    </div>
                                        <a class="carousel-control left" href="#carousel-master" data-slide="prev">
                                            <img src="<?php echo base_url();?>images/icones/seta_foto_esquerda.png" class="icon-prev">
                                        </a>
                                        <a class="carousel-control right" href="#carousel-master" data-slide="next">
                                            <img src="<?php echo base_url();?>images/icones/seta_foto_direita.png" class="icon-next">
                                        </a> 
                                        <div class="espaco-thumbs-lancador">

                                            <ol class="carousel-indicators">
                                                <?php echo $ol;?>
                                            </ol>

                                        </div>
                                        <!--
                                            <center>
                                                <p><span class="atual">1</span> de <?php echo $qtde_total;?></p>
                                            </center>
                                        -->
                                </div>
                            <?php 

                            endif;
                            ?>
                        </div>
                        <a class="left carousel-control" href="#carousel" role="button" data-slide="prev" data-item="<?php echo $item->_id;?>" data-atual="0">
                            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                            <span class="sr-only">Anterior</span>
                        </a>
                        <a class="right carousel-control" href="#carousel" role="button" data-slide="next" data-item="<?php echo $item->_id;?>" data-atual="0">
                            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span> 
                            <span class="sr-only">Proximo</span>
                        </a>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-6 col-xs-12 espaco-descricao-titulo" data-item="<?php echo $item->_id;?>">
                    <div class="c-info-list">
                        <a class="" href="<?php echo $link;?>" title="<?php echo $item->nome;?>" target="_blank">
                            <div class="espaco-titulo">
                                <h2 class="c-title c-font-bold c-font-22 c-font-dark" >
                                    <?php echo $titulo['h2'];?>
                                    <br>
                                    <span itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
                                        <small>
                                            <span class=" glyphicon glyphicon-map-marker"></span>
                                            <?php echo $titulo['localizacao'];?>
                                        </small>
                                    </span>
                                </h2>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-5 col-md-5 col-sm-6 col-xs-12 espaco-descricao" data-item="<?php echo $item->_id;?>">
                        <a class="" href="<?php echo $link;?>" title="<?php echo $item->nome;?>" target="_blank">
                            <div class="espaco-titulo">
                                <div class="c-desc c-font-16 c-font-thin">
                                <span itemprop="description" >
                                        <?php 
                                if ( isset($item->descricao) && ! empty($item->descricao) ) :
                                    $descricao = str_replace( array('<br>','<br />','<br/>', '</br>'), '-', tira_html($item->descricao));
                                    echo '<p>'.ucfirst(mb_strtolower(word_limiter(strip_tags($descricao, '<p><a>'), 40))).'</p>';
                                endif;
                                //echo $opcionais;
                                ?>
                                </span>
                                </div>
                                <div class="c-price c-font-26 c-font-thin">
                                <?php 
                                $valores = trim($valores);
                                if ( ! empty($valores) ) :
                                    echo $valores; 
                                    ?>
                                    <meta itemprop="priceCurrency" content="BRL"/>
                                    <?php
                                endif;
                                ?>
                                </div>
                                <div class="opcionais">
                                    <?php echo $opcionais;?>
                                </div>

                            </div>
                        </a>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 espaco-area" data-item="<?php echo $item->_id;?>">
                            <a class="" href="<?php echo $link;?>" title="<?php echo $item->nome;?>" target="_blank">
            <?php
                            echo $area;
                            ?>
                            <p> <img src="<?php echo base_url();?>images/icones/codigo.png" >Código PI: <?php echo $item->_id;?></p>
                            <?php 
                            if ( ! empty($item->referencia) ) :
                                ?>
                            <p class="hidden-xs"><img src="<?php echo base_url();?>images/icones/referencia.png" > Referência: <?php echo $item->referencia;?></p>
                                <?php
                            endif;
                                if ( LOCALHOST || isset($_GET['debug']) )
                                {
                                    echo $item->imobiliaria_nome_seo.' - '.$item->ordem;
                                    if ( $destaque )
                                    {
                                        echo $destaque;
                                    }
                                }
                                ?>


                            </a>                
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                            <div class="btn-group ">
                                <!--<a class="btn btn-group-sm btn-contato contato" href="<?php echo $link;?>" title="<?php echo $item->nome;?>" target="_blank"><span class="glyphicon glyphicon-envelope"></span> &nbsp;&nbsp;&nbsp;Contato</a>-->
                                <a class="btn btn-default c-btn-uppercase c-btn-bold detalhes" href="<?php echo $link;?>" title="<?php echo $item->nome;?>" target="_blank"> Mais detalhes </a>
                                <?php 
                                if ( $favoritos ) :
                                    ?>
                                <a href="<?php echo $link;?>/print" target="_blank" class="btn btn-group-sm btn-favorito imprimir " type="button" data-item="<?php echo $item->_id;?>"><span class="glyphicon glyphicon-print"></span> Imprimir</a>
                                    <?php
                                endif;
                                ?>
                                <button class="btn btn-default c-btn-uppercase c-btn-bold favoritar" type="button" data-item="<?php echo $item->_id;?>">
                                    <span class="glyphicon glyphicon-star<?php echo ( array_key_exists($item->_id, $itens_favoritos) ? '' : '-empty' );?>"></span> 
                                    <span class="hidden-md hidden-sm texto"><?php echo ( array_key_exists($item->_id, $itens_favoritos) ? 'Retirar de' : 'Adicionar a' );?></span> favoritos
                                </button>
                            </div>
                        </div>
                <!-- </div> -->
            </span>
        
        </div>
    <div class="c-margin-t-20"></div>
</div>
