<div class="row" id="images">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 galeria-mobile" id="images">
        <?php if ( (isset($images['lista']) && count($images['lista']) > 0) || ( isset($item->video) && !empty($item->video) ) ) : 
            unset($images['lista']['principal']);
            ?>    
        <div id="carousel-master" class="fundo-imagens carousel slide" >
            <div class="carousel-inner fotos-ficha" aria-label="Carousel de imagens expandidas">
            <?php 
            $qtde_total = 0;
            $ol = '';
            $chave = 0;
            if ( ( isset($item->video) && !empty($item->video) ) ) :
                $qtde_total++;
                echo '<div class="item im active" data-ordem="'.$qtde_total.'">';
                ?>
                <div class="" id="video">
                    <center><iframe style="width:100%; height: auto; min-height: 550px;" class="video" src="<?php echo set_embed_video($item->video);?>" frameborder="0" allowfullscreen></iframe></center>
                </div>
                <?php
                echo '</div>';
                $ol .= '<li data-target="#carousel-master" data-slide-to="'.($chave + 1).'" >';
                $ol .= '<center>';
                $ol .= '<img class="img-responsive" alt="Logomarca do Youtube" src="'.base_url().'images/youtube.jpg">';
                $ol .= '</center>';
                $ol .= '</li>';
                $chave++;
            endif;
            foreach ( $images['lista']['lista'] as $chave_ => $image ) :
                if ( isset($image) && ! empty( $image->arquivo_local ) ) :
                    $qtde_total++;
                    $descricao_image = ( isset($image->titulo) && ! empty($image->titulo) ) ? $image->titulo : $item->imoveis_tipos_titulo;
                    echo '<div class="item im '.( ! $item->video ? ( ! $chave ? 'active' : '' ) : '' ).'" data-ordem="'.$qtde_total.'">';
                    $image_t5 = $image->arquivo_local;
                    $image_gr = str_replace('destaque','vitrine',$image->arquivo_local);
                    echo '<center><img itemprop="image" src="'.$image_gr;
                    echo '" alt="'.$descricao_image.'" class="img-responsive"></center>';
                    echo '</div>';
                    $ol .= '<li data-target="#carousel-master" data-slide-to="'.($chave).'" class="'.( ! $item->video ? ( $chave ? 'active' : '' ): '' ).'">';
                    $ol .= '<center><img itemprop="image" src="'.$image_t5;
                    $ol .= '" alt="'.$descricao_image.'" class="img-responsive"></center>';
                    $ol .= '</li>';
                    $chave++;
                endif;
            endforeach;
            ?>
            </div>
            <a aria-label="Anterior" class="carousel-control left icone-left" href="#carousel-master" data-slide="prev">
                <span  class="glyphicon glyphicon-chevron-left"></span>
            </a>
            <a aria-label="Próximo" class="carousel-control right" id="up" name="up" value="up" href="#carousel-master" data-slide="next">
                    <span  class="glyphicon glyphicon-chevron-right"></span>                            
            </a> 
            <div class="espaco-thumbs-lancador">
                <ol class="carousel-indicators thumbs-ficha" id="lista" aria-label="Miniaturas de todas imagens do imóvel">
                    <?php echo $ol;?>
                </ol>
            </div>
        </div>
    <?php endif;?>
    </div>
</div>



<!-----                                            -->
modelo 2
<div class="c-content-box c-size-md c-bg-white c-overflow-hide">
                
<div id="grid-container" class="cbp">
                    <?php 
                    if ( ( isset($item->video) && !empty($item->video) ) ) :
                        ?>
                        <div class="cbp-item ">
                            <div class="" id="video">
                                <center><iframe style="width:100%; height: auto; min-height: 550px;" class="video" src="<?php echo set_embed_video($item->video);?>" frameborder="0" allowfullscreen></iframe></center>
                            </div>
                        </div>
                        <?php
                    endif;
                    foreach ( $images['lista']['lista'] as $chave_ => $image ) :
                        ?>
                        <div class="cbp-item ">
                            <a href="<?php echo $image->arquivo_local;?>" class="cbp-caption cbp-lightbox" data-title="<?php echo ( isset($image->titulo) && ! empty($image->titulo) ) ? $image->titulo : $item->imoveis_tipos_titulo;?>">
                                <div class="cbp-caption-defaultWrap">
                                    <img src="<?php echo $image->arquivo_local;?>" alt="<?php echo ( isset($image->titulo) && ! empty($image->titulo) ) ? $image->titulo : $item->imoveis_tipos_titulo;?>">
                                </div>
                                <div class="cbp-caption-activeWrap">
                                    <div class="cbp-l-caption-alignLeft">
                                        <div class="cbp-l-caption-body">
                                            <div class="cbp-l-caption-title"><?php echo ( isset($image->titulo) && ! empty($image->titulo) ) ? $image->titulo : $item->imoveis_tipos_titulo;?></div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        
                        <?php
                    endforeach;
                    ?>
                </div>
            </div>




<!------------------  galeria lista <!-- comment -->

$galeria = '';
            $galeria .= '';
            $mais_images = ( isset($data['images']) && ! empty($data['images']) ) ? TRUE : FALSE;
            // data-images="'.($mais_images ? $data['images'] : '').'"
            $galeria .= '<div class="espaco-image slide carousel item-'.$item->_id.'" data-item="'.$item->_id.'" >';
//            $galeria .= '   <a class="amplia-images" data-item="'.$item->_id.'" href="#" title="'.$item->nome.'">';
            $galeria .= '       <div class="carousel-inner" role="listbox">';
            $galeria .= '           <div class="item active" data-atual="0">';
            $galeria .= '               <div class=" link-img center-block" title="'.$item->nome.'" >';
            $galeria .= '<center><img '
                    . 'itemprop="image" '
                    . 'data-src="'.str_replace(['http://www.powempresas.com/','https://www.powempresas.com/', 'http://201.22.56.213/portais_3/','https://201.22.56.213/portais_3/'], base_url(), str_replace('650F_', '', $data['arquivo']) ).'" '
                    . 'alt="'.$data['alt_image'].'" src="'.base_url().'imagens/naodisponivel.jpg" title="'.$data['alt_image'].'" class="img-responsive"></center>';
            $galeria .= '               </div>';
            $galeria .= '           </div>';
            $galeria .= '       </div>';
//            $galeria .= '   </a>';
            if ( $mais_images )
            {
                $galeria .= '<a class="left carousel-control" href="#carousel" role="button" data-slide="prev" data-item="'.$item->_id.'" data-atual="0"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span><span class="sr-only">Anterior</span></a>';
                $galeria .= '<a class="right carousel-control" href="#carousel" role="button" data-slide="next" data-item="'.$item->_id.'" data-atual="0"><span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span> <span class="sr-only">Proximo</span></a>';
            }
            $galeria .= '</div>';
            return $galeria;
            
            
            /*<!-- whats do form -->*/
            
            <ul class="list-unstyled list-inline">
                                                    <?php
                                                    $col= 6;
                                                    $link_whats = '';
                                                        if ( isset($item->imobiliaria_whatsapp) && ! empty($item->imobiliaria_whatsapp) ):
                                                            $col = 4;
                                                            ?>
                                                            <li class="dropup">
                                                                <div class="btn-group btn-vertical">
                                                                    <button type="button" class="btn btn-form hidden-xs whats-desktop  btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                        <i class="fa fa-whatsapp"></i> WhatsApp
                                                                    </button>
                                                                    <ul class="dropdown-menu">
                                                                        <li>
                                                                            <a target="_blank" class="btn ver-telefone-whats-lista"  href="https://web.whatsapp.com/send?phone=+55<?php echo $item->imobiliaria_whatsapp.'&text='. urlencode('Gostaria de saber mais sobre o imóvel: '.$url);?>" data-item="<?php echo $item->id;?>" data-log="<?php echo $origem;?>">Conversar com a imobiliária</a>
                                                                        </li>
                                                                        <li>
                                                                            <a target="_blank" class="btn compartilha-telefone-whats-lista"  href="https://web.whatsapp.com/send?<?php echo '&text='. urlencode('Conheça esse imóvel: '.$url);?>" data-item="<?php echo $item->id;?>" data-log="<?php echo $origem;?>">Enviar para um amigo</a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                                <div class="btn-group btn-vertical">
                                                                    <button type="button" class="btn btn-form whats-mobile  btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                        <i class="fa fa-whatsapp">WhatsApp</i> 
                                                                    </button>
                                                                    <ul class="dropdown-menu">
                                                                        <li><a target="_blank" class="btn ver-telefone-whats-lista" href="https://api.whatsapp.com/send?phone=+55<?php echo $item->imobiliaria_whatsapp.'&text='. urlencode('Gostaria de saber mais sobre o imóvel: '.$url);?>" data-item="<?php echo $item->id;?>" data-log="<?php echo $origem;?>">Conversar com a imobiliária</a></li>
                                                                        <li><a target="_blank" class="btn compartilha-telefone-whats-lista"  href="https://api.whatsapp.com/send?<?php echo '&text='. urlencode('Conheça esse imóvel: '.$url);?>" data-item="<?php echo $item->id;?>" data-log="<?php echo $origem;?>">Enviar para um amigo</a></li>
                                                                    </ul>
                                                                </div>
                                                            </li>
                                                                    <?php
                                                                endif;
                                                                ?>
                                                                </li>
                                                                <li>
                                                                    <a type="button" data-item="<?php echo $item->id_empresa;?>" class="btn btn-form btn-secondary  btn-topo" target="_blank" href="<?php echo base_url().'imobiliaria/'.urlencode( tira_especiais($item->imobiliaria_nome) ).'-'.$item->id_empresa;?>/" > + Imóveis <span class="hidden-xs">do anunciante</span></a>
                                                                </li>
                                                </ul>