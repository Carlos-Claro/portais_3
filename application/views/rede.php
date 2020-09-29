<div class="container rede">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h1>Rede de Portais Imobiliários POW Internet</h1>
            <h2><?php echo $titulo;?></h2>
            <p>Basta clicar na cidade para conhecer o portal, lembrando que os imóveis podem ser consultados em qualquer um deles, bastando selecionar a cidade no mecanismo de busca.<p>
            <p>Imobiliárias de todo o Brasil reunidas em um só banco de dados ofertando milhares de imóveis para você.</p>
            <h3>Estado da Federação: <?php echo $cidade['uf'];?></h3>


            <ul class="list-group">
                    <li class="list-group-item col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <a href="<?php echo $capital[0]->portal;?>" title="<?php substr($capital[0]->portal, 11).( ( $capital[0]->libera == 0 ) ? ' em Lançamento. Aguarde!' : '' );?>" rel="nofollow">
                        <?php 
                        echo substr($capital[0]->portal, 11).( ( $capital[0]->libera == 0 ) ? ' em Lançamento. Aguarde!' : '' );
                        ?>
                        </a>
                    </li>
                <?php 
                if ( $itens && count( $itens ) > 0 ) :
                    foreach ( $itens as $portal) :
                        ?>
                        <li class="list-group-item col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <a href="<?php echo $portal->portal;?>" title="<?php substr($portal->portal, 11).( ( $portal->libera == 0 ) ? ' em Lançamento. Aguarde!' : '' );?>" rel="nofollow">
                            <?php 
                            echo substr($portal->portal, 11).( ( $portal->libera == 0 ) ? ' em Lançamento. Aguarde!' : '' );
                            ?>
                            </a>
                        </li>
                        <?php 
                    endforeach;
                endif;
                ?>   
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <a href="<?php echo base_url();?>anuncie" title="" class="btn btn-busca-submit">
                Imobiliárias e Corretores, anunciem seus imóveis conosco. CONTRATE JÁ
            </a>
        </div>
    </div>
</div>