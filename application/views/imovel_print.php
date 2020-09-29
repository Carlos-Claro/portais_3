<div class="container">
    <div class="row">
        
        <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 " style="margin-top: 0px;">
            <div class="media" style="border:1px solid #000; text-align: left; padding: 10px; margin-top: 10px; height: 140px;">
                <h4 class="media-heading text-left">
                    <small>Agente imobiliário responsável pelo Imóvel:</small><br><br>
                </h4>
                <img src="<?php echo str_replace('codEmpresa/imo',$item->id_empresa,URL_IMAGE_MUDOU).'/'.$item->logo;?>" alt="logo <?php echo $item->imobiliaria_nome;?>" class="media-object pull-left">
                <div class="media-body" style="margin-top: 0px;">
                    <p><strong><?php echo $item->imobiliaria_nome;?></strong> - CRECI: <?php echo $item->creci;?></p>
                    <p>
                        End: <?php echo $item->imobiliaria_logradouro;?>, <?php echo $item->imobiliaria_numero;?> - <?php echo $item->imobiliaria_bairro?> - <?php echo $item->imobiliaria_cidade;?>.
                    </p>
                    <p>
                        Tel: <?php echo $item->ddd.' - '.$item->imobiliaria_telefone;?>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-sm-2 col-md-2 col-xs-2">
            <?php 
            echo '<img src="'.base_url().'index/qrcode/'.$item->id.'" class="img-responsive">';
            ?>
        </div>
        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 text-right">
            <p>Use o QrCode para acessar este imóvel, ou digite no seu navegador:
                <br><a href="#">
                    <?php echo base_url().'imovel/0/'.$item->id;?>
                </a>
            </p>
        </div>
    </div>
</div>
<!-- inicio das informações titulos, galerias, contato-->
<?php 
    if ( isset($item->images) && ! empty($item->images)) :
        ?>
        <?php
        $image_principal = '';
        $images = '';
        $chave = 0;
        foreach( $item->images as $image ) :
            if ( $chave  < 5 ) :
                $image_ = '';
                if ( isset($image->arquivo) && ! empty( $image->arquivo ) ) :
                    $descricao_image = ( isset($image->titulo) && ! empty($image->titulo) ) ? $image->titulo : $item->imoveis_tipos_titulo;
                    $image_ .= '<div class="col-lg-6 col-sm-6 col-md-6 col-xs-6">';
                    $image_ .= '<a class="thumbnail" >';
                    $fs = '';
                    $image_ .= '<img src="'.$image->arquivo;
                    $image_ .= '" alt="'.$descricao_image.'" class="img-responsive">';
                    $image_ .= '</a>';
                    $image_ .= '</div>';
                endif;
                if ( ! $chave ) : 
                    $image_principal = $image_;
                else:
                    $images .= $image_;
                endif;
            endif;
                $chave++;
        endforeach;
            ?>
<?php 
endif;
?>
<div class="container corpo print">
    <div class="row ">
    <hr>
        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 ">
            <h1>
            <?php 
            echo $item->imoveis_tipos_titulo;
            echo ( ($item->venda == 1) )? ' à venda' : '';
            echo ( ( ($item->venda == 1) && ($item->locacao == 1) ) || ( ($item->venda == 1) && ($item->locacao_dia == 1) ) ) ? ', ' : '';
            echo ( ($item->locacao == 1) ) ? 'para locação' : '';
            echo ( ($item->locacao == 1) && ($item->locacao_dia == 1) ) ? ', ' : '';
            echo ( ($item->locacao_dia == 1) ) ? 'para locação temporada' : '';
            ?>
            </h1>
        </div>
    <hr>
        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 ">
            <div class="row ">
                <div class="col-lg-6 col-sm-6 col-md-6 col-xs-6">
                    <h2 class="cidade"><small>Cidade: </small><?php echo isset($item->cidade) ? $item->cidade.' / '.$item->uf : '';?></h2>
                    <?php 

                    if ( ! empty($item->bairro) || ! empty($item->vila) ) :
                        echo '<h2 class="bairro"><small>Bairro: </small> ';
                        echo ( ! empty($item->bairro) ) ? $item->bairro : '';
                        if ( (! empty($item->bairro) && ! empty($item->vila) && tira_acento($item->vila) !== tira_acento($item->bairro) ) || ( empty($item->bairro) && ! empty($item->vila) ) ) :
                            echo ( ! empty($item->vila) ) ? ' - '.$item->vila : '';
                        endif;
                        echo '</h2>';
                    endif;
                    ?>
                    
                    <?php 
                    echo ( ($item->venda == 1) && $item->preco_venda > 0 ) ? '<h2 class="valor text-left"><small>Valor venda:</small> R$ '.number_format($item->preco_venda, 2, ',', '.').'</h2>' : '';
                    echo ( ($item->locacao == 1) && $item->preco_locacao > 0 ) ? '<h2 class="text-left"><small>Valor locação:</small> R$ '.number_format($item->preco_locacao, 2, ',', '.').'</h2>' : '';
                    echo ( ($item->locacao_dia == 1) && $item->preco_locacao_dia > 0 ) ? '<h2 class="text-left"><small>Valor locação temporada</small> R$ '.number_format($item->preco_locacao_dia, 2, ',', '.').'</h2>' : '';
                    ?>
                    <?php 
                    if ( ! empty($item->referencia) ) :
                        ?>
                    <h3 class="text-left"><small>Código <?php echo $item->imobiliaria_nome;?>: </small><?php echo $item->referencia;?> </h3>
                        <?php 
                    endif;
                    ?>
                    <h3 class="text-left"><small>Código <?php echo substr($sobre['portal'], 11);?>: </small><?php echo $item->id;?></h3>
                    <?php
                            if ( ! empty($item->logradouro) ) :
                                ?>
                            <h3 class="text-left">
                                <small>Logradouro: </small>
                                <?php echo $item->logradouro.', '.$item->numero;?>
                            </h3>
                                <?php
                            endif;
                                $uso = '';
                                if( $item->comercial == 1 ) :
                                    $uso .= 'Comercial';
                                endif;
                                if ( ( ( $item->comercial == 1 ) && ( $item->residencial == 1 ) ) || ( ( $item->comercial == 1 ) && ( $item->lazer == 1 ) )  ) :
                                    $uso .= ', ';
                                endif;
                                if( $item->residencial == 1 ) :
                                    $uso .= 'Residencial';
                                endif;
                                if ( ( ( $item->residencial == 1 ) && ( $item->lazer == 1 ) )  ) :
                                    $uso .= ', ';
                                endif;
                                if( $item->lazer == 1 ) :
                                    $uso .= 'Lazer';
                                endif;
                                if ( ! empty($uso) ) :
                                    ?>
                            <h3 class="text-left">
                                <small>Uso: </small>
                                <?php 
                                echo $uso;
                                ?>
                            </h3>
                                    <?php
                                endif;
                            ?>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <div class="col-sm-12">
                    <?php
                    echo isset($image_principal) ? str_replace('-6','-12',$image_principal) : '';
                    ?>
                    </div>
                    <style type="text/css">
                        .thumbnail img {
                            max-width: 100%;
                            height: auto;
                            max-height: 500px;
                            margin: 0 auto;
                        }
                        .esp-thumb .thumbnail {
                            height: 50px;
                            max-width: 100%;
                        }
                    </style>
                    <div class="col-sm-12 esp-thumb">
                    <?php
                    echo isset($images) ? str_replace('-6','-3',$images) : '';
                    ?>
                    </div>
                </div>
                <div class="col-lg-6 col-sm-6 col-md-6 col-xs-6">
                </div>
            </div>
            <div class="row ">
                
            </div>
            <div class="row caracteristicas" id="caracteristicas">
                
                            
                            

                            <?php 
                            if ( ( $item->area > 0 ) || ( $item->area_terreno > 0 ) ) :
                                ?>
                            <p class="col-lg-4 col-sm-4 col-md-4 col-xs-4">
                                <small>Área: </small>
                                <?php echo ( $item->area > 0 ) ? $item->area : $item->area_terreno; ?>m&sup2;
                            </p>
                                <?php
                            endif;
                            ?>
                            <?php 
                            if ( ( $item->area_util > 0 ) ) :
                                ?>
                            <p class="col-lg-4 col-sm-4 col-md-4 col-xs-4">
                                <small>Área útil: </small>
                                <?php echo $item->area_util; ?>m&sup2;
                            </p>
                                <?php
                            endif;
                            ?>
                            <?php 
                            if ( $item->quartos > 0 ) :
                                ?>
                            <p class="col-lg-4 col-sm-4 col-md-4 col-xs-4">
                                <small>Quartos: </small>
                                <?php echo $item->quartos;?>
                            </p>
                                <?php
                            endif;
                            ?>
                            <?php 
                            if ( $item->suites > 0 ) :
                                ?>

                            <p class="col-lg-4 col-sm-4 col-md-4 col-xs-4">
                                <small>Suites: </small>
                                <?php echo $item->suites;?>
                            </p>
                                <?php
                            endif;
                            ?>
                            <?php 
                            if ( $item->banheiros > 0 ) :
                                ?>
                            <p class="col-lg-4 col-sm-4 col-md-4 col-xs-4">
                                <small>Banheiros: </small>
                                <?php echo $item->banheiros;?>
                            </p>
                                <?php
                            endif;
                            ?>
                            <?php 
                            if ( $item->garagens > 0 ) :
                                ?>
                            <p class="col-lg-4 col-sm-4 col-md-4 col-xs-4">
                                <small>Vagas: </small>
                                <?php echo $item->garagens;?>
                            </p>
                                <?php
                            endif;
                            ?>
                            <?php
                            if ( $item->mobiliado > 0 ) :
                                ?>
                            <p class="col-lg-4 col-sm-4 col-md-4 col-xs-4">
                                <small>Mobiliado: </small>
                                Sim
                            </p>
                                <?php
                            endif;
                            ?>
                            <?php
                            if ( $item->cobertura > 0 ) :
                                ?>
                            <p class="col-lg-4 col-sm-4 col-md-4 col-xs-4">
                                <small>Cobertura: </small>
                                Sim
                            </p>
                                <?php
                            endif;
                            ?>
                            <p class="col-lg-4 col-sm-4 col-md-4 col-xs-4">
                                <small>Condominio: </small>
                                <?php echo ( $item->condominio > 0 || $item->condominio_valor > 0 ) ? 'Sim' : 'Não';?>
                            </p>
                            <?php
                            if ( $item->condominio_valor > 0 ) :
                                ?>
                            <p class="col-lg-4 col-sm-4 col-md-4 col-xs-4">
                                <small>Valor Condominio: </small>
                                <?php echo 'R$ '.  number_format($item->condominio_valor, 2, ',', '.');?>
                            </p>
                                <?php
                            endif;
                            ?>

               
            </div>
                  
        </div>
    </div><!-- .cabecalho -->
    
    
    <div class="row caracteristicas" id="caracteristicas">
            <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 caracteristicas-miolo">
                <h3>Descrição do Imóvel</h3>
                <div class="row">
                    <?php
                    $conjunto[] = $item->imobiliaria_telefone;
                    $conjunto[] = substr($item->imobiliaria_telefone, -4);

                    ?>
                    <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 descricao"><?php echo (tira_html($item->descricao, $conjunto));?></div>
                </div>
            </div>
            
        </div>
        
    <div class="row">
        <div class="col-lg-4 col-sm-4 col-md-4 col-xs-4">
            <img style="margin-top: 20px;" src="<?php echo str_replace('/m','',base_url()).'imagens/'.$logo;?>" alt="Os imóveis e as Imobiliárias da cidade reunidos aqui!" class="img-responsive">
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <p class="aviso">ATENÇÃO: Este imóvel de código <?php echo isset($item->id) ? $item->id : '';?> aqui exibido mostra informações fornecidas e mantidas por <?php echo isset($item->imobiliaria_nome) ? $item->imobiliaria_nome : '';?> e correspondem a um anúncio publicitário. Os dados, inclusive preços, deste imóvel poderão sofrer alterações sem aviso prévio. <?php echo ($item->data_atualizacao > 0) ? 'Imóvel atualizado em '.  $item->data_atualizacao.', por '.$item->imobiliaria_nome.' .' : '';?> O portal <?php echo substr($sobre['portal'],11);?> não garante a precisão ou veracidade do anúncio ou de qualquer informação associada a ele. O portal <?php echo substr($sobre['portal'],11);?> não possui controle sobre o conteúdo, que é de responsabilidade de <?php echo $item->imobiliaria_nome;?> . Para mais informações sobre o imóvel favor contactar diretamente com <?php echo $item->imobiliaria_nome;?>. </p>
        </div>
    </div>
    </div><!-- .caracteristicas -->

