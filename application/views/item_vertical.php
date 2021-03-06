<li class="imovel item-vertical <?php echo ( ($destaque) ? 'destaque' : 'organico' );?> elemento-<?php echo $item->_id;?>" data-item="<?php echo $item->_id;?>" itemscope itemtype="http://schema.org/Product" data-link="<?php echo $link?>" data-origem="ver-telefone-whats-lista">
    <div class="row c-margin-t-20">
        <div class="c-content-product-2 c-bg-white">
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12" >
                <?php echo $galeria;?>
            </div>
            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                <a class="" href="<?php echo $link;?>" title="<?php echo $item->nome;?>" target="_blank">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 espaco-descricao-titulo" data-item="<?php echo $item->_id;?>">
                        <div class="c-info-list">
                            <h2 class="c-title c-font-bold c-font-22 c-font-bold azul-pow" >
                                <?php echo $titulo['h2'];?>
                                <br>
                                <small>
                                <span class=" glyphicon glyphicon-map-marker"></span>
                                    <?php echo $titulo['localizacao'];?></small>
                            </h2>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 espaco-descricao" data-item="<?php echo $item->_id;?>">
                        <div class="c-desc c-font-thin c-font-16">
                            <?php 
                            if ( isset($item->descricao) && ! empty($item->descricao) ) :
                                $descricao = str_replace( array('<br>','<br />','<br/>', '</br>'), '-', tira_html($item->descricao));
                                echo '<p itemprop="description" class="c-font-16">'.ucfirst(mb_strtolower(word_limiter(strip_tags($descricao, '<a>'), 45))).'</p>';
                            endif;
                            ?>
                            <?php 
                            if ( ! empty($item->referencia) ) :
                                ?>
                                <span class="col-lg-12 col-md-12 col-sm-12 col-xs-12 c-font-13">Referência: <b><?php echo $item->referencia;?></b></span>
                                <?php
                            else:
                                ?>
                                <span class="col-lg-12 col-md-12 col-sm-12 col-xs-12">Código: <b><?php echo $item->_id;?></b></span>
                                <?php
                            endif;
                            ?>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <div class="c-price c-font-20 c-font-bold azul-pow">
                        <?php 
                        $valores = trim($valores);
                        if ( ! empty($valores) ) :
                            echo $valores; 
                        endif;
                        ?>
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                        <div class="opcionais azul-pow text-right">
                            <?php 
                            echo $opcionais;
                            echo $area;
                            ?>
                        </div>
                    </div>
                </a>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                    <ul class="list-inline list-unstyled">
                            <?php
                            $col= 6;
                            $link_whats = '';
                            if ( isset($item->imobiliaria_whatsapp) && ! empty($item->imobiliaria_whatsapp) ):
                                $col = 4;
                                ?>
                                <li class="dropup">
                                    
                                    <div class="btn-group btn-vertical">
                                        <button type="button" оnClick="ga('send', 'event', 'botao', 'clique');" class="btn btn-whats btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fa fa-whatsapp">WhatsApp</i> 
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a target="_blank" class="btn btn-whats hidden-xs ver-telefone-whats-lista" оnClick="ga('send', 'event', 'botao', 'clique');" href="https://api.whatsapp.com/send?phone=+55<?php echo $item->imobiliaria_whatsapp.'&text='. urlencode('Gostaria de saber mais sobre o imóvel: '.$link);?>" data-item="<?php echo $item->id;?>" data-log="<?php echo $origem;?>">Conversar com a imobiliária</a></li>
                                            <li><a target="_blank" class="btn btn-whats hidden-xs compartilha-telefone-whats-lista" оnClick="ga('send', 'event', 'botao', 'clique');" href="https://api.whatsapp.com/send?<?php echo '&text='. urlencode('Conheça esse imóvel: '.$link);?>" data-item="<?php echo $item->id;?>" data-log="<?php echo $origem;?>">Enviar para um amigo</a></li>
                                        </ul>
                                    </div>
                                </li>
                                <?php
                            endif;
                            ?>
                        <li class=" ">
                            <a class="btn btn-azul-pow c-btn-uppercase c-btn-bold detalhes" href="<?php echo $link;?>" title="<?php echo $item->nome;?>" target="_blank"> Mais detalhes </a>
                        </li>
                        <?php 
                        if ( $favoritos ) :
                            ?>
                        <a href="<?php echo $link;?>/print" target="_blank" class="btn btn-group-sm btn-favorito imprimir " type="button" data-item="<?php echo $item->_id;?>"><span class="glyphicon glyphicon-print"></span> Imprimir</a>
                            <?php
                        endif;
                        ?>
                        <li class=" ">
                            <button class="btn btn-vermelho-pow c-btn-uppercase c-btn-bold c-btn-square favoritar favorito-<?php echo $item->_id;?>" type="button" data-item="<?php echo $item->_id;?>">
                                <span class="glyphicon glyphicon-star<?php echo ( array_key_exists($item->_id, $itens_favoritos) ? '' : '-empty' );?>"></span> 
                                <span class="hidden-md hidden-sm texto"><?php echo ( array_key_exists($item->_id, $itens_favoritos) ? 'Retirar de' : 'Adicionar a' );?></span> favoritos
                            </button>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </div>
</li>
