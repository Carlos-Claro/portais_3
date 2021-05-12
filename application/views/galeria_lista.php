
<?php 
$mais_images = ( isset($images['images']) && ! empty($images['images']) ) ? TRUE : FALSE;
?>
<div class="espaco-image slide carousel item-<?php echo $imovel->_id;?>" data-item="<?php echo $imovel->_id;?>" >
    <div class="carousel-inner" role="listbox">
        <div class="item active" data-atual="0">
            <div class=" link-img center-block" title="<?php echo $imovel->nome;?>" >
                <img data-src="<?php echo str_replace('650F_', '', $images['arquivo']);?>" alt="<?php echo $images['alt_image'];?>" src="<?php echo base_url();?>imagens/naodisponivel.jpg" title="<?php echo $images['alt_image'];?>" class="img-responsive">
            </div>
        </div>
    </div>
    <?php
    if ( $mais_images ){
        ?>
        <a class="left carousel-control" href="#carousel" role="button" data-slide="prev" data-item="<?php echo $imovel->_id;?>" data-atual="0"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span><span class="sr-only">Anterior</span></a>';
        <a class="right carousel-control" href="#carousel" role="button" data-slide="next" data-item="<?php echo $imovel->_id;?>" data-atual="0"><span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span> <span class="sr-only">Proximo</span></a>';
        <?php
    }
    ?>
</div>
<!--
    <a class="amplia-images" data-item="<?php //echo $imovel->_id;?>" href="#" title="<?php //echo $imovel->nome;?>">
    </a>
-->