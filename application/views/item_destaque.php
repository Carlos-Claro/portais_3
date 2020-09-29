<div class="<?php echo ($email ? 'col-lg-12 col-md-12 col-sm-12 col-xs-12' : 'col-lg-3 col-md-3 col-sm-6 col-xs-12' );?> ">
    <div class="thumbnail <?php echo $tipo->classe;?>">
            <span  itemscope itemtype="http://schema.org/PriceSpecification">
            <a href="<?php echo $link;?>" title="<?php echo (isset($item->nome) ? $item->nome.' de '.$item->nome_empresa : '' );?>" rel="nofollow" target="_blank">
                <h3 itemprop="name"><?php echo $tipo->titulo;?></h3>
            </a>
            <a href="<?php echo $link;?>" title="<?php echo (isset($item->nome) ? $item->nome.' de '.$item->nome_empresa : '' );?>" rel="nofollow" target="_blank">
                <center><img itemprop="image" class="img-responsive" src="<?php echo $arquivo;?>" alt="<?php echo $alt_image;?>.'"></center>
            </a>
                <?php
                if ( ! empty($valor) ) :
                    ?>
                    <p class="valor_imovel" itemprop="price">R$ <?php echo $valor;?></p>
                    <?php
                endif;
                ?>
            </span>
            <div class="caption" itemscope itemtype="http://schema.org/PostalAddress">
                <span itemprop="name">
                    <?php 
                    if ( isset( $item->imoveis_tipos_titulo ) ) :
                        ?>
                        <h4><strong><?php echo $item->imoveis_tipos_titulo;?></strong></h4>
                        <?php
                    endif;
                    if (isset($item->bairro) && ! empty($item->bairro) ) :
                        ?>
                        <h4 class="bairro_"><strong><?php echo $item->bairro;?></strong></h4>
                        <?php
                    endif;
                    ?>
                </span>
                    <p class="border-bottom"><?php echo '<span itemprop="addressLocality">'.$item->cidade_nome.'</span>/<span itemprop="addressRegion">'.$item->uf.'</span>';?></p>
                <span itemprop="description">
                    <?php
                    if ( $item->area > 0 || $item->area_terreno > 0 )  :
                        ?>
                        <p>
                            <?php 
                            if ( $item->area > 0 ) :
                                ?>
                                <span class="bold"><?php echo $item->area;?></span> m&sup2; de área
                                <?php
                            else : 
                                ?>
                                <span class="bold"><?php echo $item->area_terreno;?></span> m&sup2; de Terreno
                                <?php
                            endif;
                            ?>
                        </p>
                        <?php
                    endif;
                    ?>
                    <p>
                        <?php 
                        if ( ($item->terreno) && ! empty($item->terreno) ) :
                            ?>
                        Terreno em Condominio
                        <?php 
                        else : 
                            if ( ! empty($item->quartos) && $item->quartos > 0 ) :
                                ?>
                                <span><?php echo $item->quartos;?></span> Dormitórios
                                <?php
                            endif;
                        endif;
                        ?>
                    </p>
                <?php 
                if ( (isset($item->garagens) && $item->garagens > 0) ) :
                    ?>
                    <p>
                        <strong><?php echo $item->garagens;?></strong> Vagas
                    </p>
                    <?php
                endif;
                
            ?>
            </span>
            <a href="<?php echo $link;?>" title="<?php echo (isset($item->nome) ? $item->nome.' de '.$item->nome_empresa : '' );?>" rel="nofollow" target="_blank">
                <div style="position:absolute; bottom:10px; right:<?php echo ( $email ? '40px' : '20px');?>; padding:2px; " href="<?php echo $link;?>" class="btn <?php echo ( $email ? 'btn-primary' : 'btn-link' );?> pull-right">Mais detalhes</div>
            </a>

            </div>
    </div>
</div>