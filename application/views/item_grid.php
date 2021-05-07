<li class="imovel <?php echo $favoritos ? 'col-md-12 col-sm-12 favoritos' : ($destaque && $destaque == 'relacionado' ? 'col-md-4 col-sm-4' : 'col-md-4 col-sm-6 ');?>  item-grid <?php echo ( ($destaque) ? 'destaque' : 'organico' );?> elemento-<?php echo $item->_id;?>" data-item="<?php echo $item->_id;?>" itemscope itemtype="http://schema.org/Product" data-link="<?php echo $link?>" data-origem="<?php echo $origem;?>">
    <div class="c-content-product-2 c-bg-white box">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
            <?php echo $galeria;?>
        </div>
        <div class="infos row">
            <a class="" href="<?php echo $link; ?>" title="<?php echo $item->nome; ?>" target="_blank">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 espaco-descricao-titulo" data-item="<?php echo $item->_id;?>">
                <div class="c-info-list espaco-titulo">
                    <h2 class="c-title c-font-bold azul-pow" ><?php echo $titulo['h2'];?><br>
                        <small>
                            <span class=" glyphicon glyphicon-map-marker"></span>
                            <?php echo $titulo['localizacao'];?>
                        </small>
                    </h2>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 espaco-descricao c-desc c-font-14 c-font-thin <?php echo $favoritos ? 'favorito-hide' : ''; ?>" data-item="<?php echo $item->_id;?>">
                <?php
                if (isset($item->descricao) && !empty($item->descricao)) :
                    $descricao = str_replace(array('<br>', '<br />', '<br/>', '</br>'), '-', tira_html($item->descricao));
                    echo '<p class="descricao">' . ucfirst(mb_strtolower(word_limiter(strip_tags($descricao, '<a>'), 25))) . '</p>';
                endif;
                ?>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 espaco-area <?php echo $favoritos ? 'favorito-hide' : '';?>" data-item="<?php echo $item->_id;?>">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 espaco-preco c-price c-font-16 azul-pow c-font-bold">
                    <?php
                    $valores = trim($valores);
                    if (!empty($valores)) :
                        echo $valores;
                    endif;
                    ?>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 espaco-info">
                    <div class="opcionais azul-pow <?php echo $favoritos ? 'favorito-hide' : ''; ?>">
                        <?php echo $opcionais; ?>
                    </div>
                    <p class="font-codigo"> <img src="<?php echo base_url();?>images/icones/codigo.png" width="23" height="23">Código PI: <?php echo $item->_id;?></p>
                </div>
            </div>
            </a>                
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
        </div>
    </div>
</li>
