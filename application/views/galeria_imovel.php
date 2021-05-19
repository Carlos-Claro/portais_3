<?php
//var_dump(count($images['lista']['lista']));die();
?>
<div class="portlet light bordered">
    <div class="portlet-title tabbable-line">
        <div class="caption">
            <i class="icon-pin font-yellow-crusta"></i>
            <h4 class="c-left c-font-18 c-font-bold c-font-uppercase caption-subject bold font-yellow-crusta uppercase"> Fotos e Vídeo </h4>
            <div class="c-line-center c-theme-bg"></div>
            <span class="caption-helper"></span>
        </div>
        <ul class="nav nav-tabs">
            <?php if ( ( isset($imovel->video) && !empty($imovel->video) ) ) : ?>
            <li >
                <a href="#video" data-toggle="tab"> Vídeo </a>
            </li>
            <?php endif; ?>
            <li class="active">
                <a href="#images_tab" data-toggle="tab"> Fotos ( <span class="foto-atual">1</span> de <span class="foto-qtde"><?php echo count($images['lista']['lista']);?></span> ) </a>
            </li>
        </ul>   
    </div>
    <div class="portlet-body">
        <div class="tab-content">
            <div class="tab-pane active" id="images_tab">
                <div class="carousel-master galeria-mobile carousel slide item-<?php echo $imovel->_id;?>" id="images">
                    <div class="carousel-inner" aria-label="Carousel de imagens expandidas">
                    <?php 
                        $descricao_image = ( isset($images['lista']['principal']->titulo) && ! empty($images['lista']['principal']->titulo) ) ? $images['lista']['principal']->titulo : $imovel->imoveis_tipos_titulo;
                        $image_gr = $images['lista']['principal']->arquivo_local;
                        ?>
                        <div class="item im active" data-ordem="0">
                            <center><img itemprop="image" width="100%" height="auto" data-src="<?php echo $image_gr;?>" src="<?php echo base_url();?>imagens/naodisponivel.jpg" alt="<?php echo $descricao_image;?>" class="img-responsive primeira-image"></center>
                        </div>
                    </div>
                    <a class="left carousel-control" href="#carousel" role="button" data-slide="prev" data-item="<?php echo $imovel->_id;?>" data-atual="1"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span><span class="sr-only">Anterior</span></a>
                    <a class="right carousel-control" href="#carousel" role="button" data-slide="next" data-item="<?php echo $imovel->_id;?>" data-atual="1"><span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span> <span class="sr-only">Proximo</span></a>
                    
                </div>
            </div>
            <div class="tab-pane" id="video">
                <?php if ( ( isset($imovel->video) && !empty($imovel->video) ) ) : ?>
                    <center><iframe style="width:100%; height: auto; min-height: 550px;" class="video" src="<?php echo set_embed_video($imovel->video);?>" frameborder="0" allowfullscreen></iframe></center>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
