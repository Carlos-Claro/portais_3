
<div data-role="content" >
    <div class="container-fluid">
        <?php 
        if ( isset($item->image1) && ! empty($item->image1)) :
        ?>    
            <div id="myCarousel" class="carousel slide ">
                <div class="carousel-inner ">
                    <?php 
                    $ol = '';
                    for( $a = 1; $a < 9; $a++ ) :
                        $e = 'image'.$a;
                        if ( isset($item->$e) && ! empty( $item->$e ) ) :
                            echo '<div class="item '.( $a == 1 ? 'active' : '' ).'">';
                            echo '<a href="'.( strstr($item->$e, 'http') ? $item->$e : str_replace('codEmpresa', $item->id_empresa, $item->mudou).$item->$e ).'" ><img src="'.( strstr($item->$e, 'http' ) ? $item->$e : str_replace('codEmpresa', $item->id_empresa, $item->mudou).$item->$e ).'"></a>';
                            echo '</div>'; 
                            $ol .= '<li data-target="#myCarousel" data-slide-to="'.($a-1).'" class="'.( $a == 1 ? 'active' : '' ).'"></li>';
                        endif;
                    endfor; 
                    ?>
                </div>
                <a class="carousel-control left" href="#myCarousel" data-slide="prev">&lsaquo;</a>
                <a class="carousel-control right" href="#myCarousel" data-slide="next">&rsaquo;</a> 

                <ol class="carousel-indicators">
                    <?php echo $ol;?>
                </ol>

            </div>
        <?php 
        endif;
        ?>
        </div>
        
        <div class="container-fluid">
            <h4 class="imobiliaria_telefone "><a onclick="_gaq.push(['_trackEvent', 'Telefone_Imobiliaria_mobile', 'Ver_Telefone_mobile']);" href="tel:<?php echo '0'.$item->ddd.(trim(substr($item->ddd.$item->imobiliaria_telefone, 0, 9)  ) );?>" class="btn btn-primary tel_log" target="_blank" data-empresa="<?php echo $item->id_empresa;?>" style="color:#FFF;"><strong>Ligue para o anunciante</strong> </a></h4>
            <?php 
            if ( isset($item->video) && ! empty($item->video) ) :
                echo '<a class="btn pull-left video" href="'.$item->video.'" target="_blank"><i class="icon-facetime-video"></i> Video </a>';
            endif;
            if ( isset($item->mapa) && ! empty($item->mapa) ) :
                echo '<a class="btn pull-right mapa" href="'.base_url().'index.php/imoveis/mapa/'.$item->id.'" target="_blank"> <i class="icon-map-marker"></i> Mapa </a>';
            endif;
            ?>
        </div>
        <div class="container-fluid imovel">
            <div class="control-group">
                <h1>Características do Imóvel</h1>
                <?php 
                echo '<p><strong>Preço para '.$item->imovel_para.': R$ ';
                if ( $item->preco_venda > 0 && $item->imovel_para == 'venda' ) :
                    echo number_format($item->preco_venda, 2, ',', '.');
                elseif ( $item->preco_locacao > 0 && $item->imovel_para == 'locação') :
                    echo number_format($item->preco_locacao, 2, ',', '.');    
                else :
                    echo number_format($item->preco_locacao_dia, 2, ',', '.');
                endif;
                echo '</strong></p><br>';
                echo '<div class="caracteristicas">';
                echo '<ul>';
                echo ( isset($item->id) ) ? '<li><strong>Código: '.$item->id.'</strong></li>' : '';
                echo ( isset($item->referencia) ) ? '<li><strong>Referência: '.$item->referencia.'</strong></li>' : '';
                echo ( isset($item->imovel_para) ) ? '<li><strong>Imóvel para: '.ucfirst($item->imovel_para).'</strong></li>' : '';
                echo ( isset($item->uso) ) ? '<li><strong>Uso: '.$item->uso.'</strong></li>' : '';
                echo ( isset($item->condominio_valor) && $item->condominio_valor > 0 ) ? '<li><strong>Valor Condominio: R$ '.$item->condominio_valor.'</strong></li>' : '';
                echo ( isset($item->quartos) && $item->quartos > 0 ) ? '<li><strong>Quartos: '.$item->quartos.'</strong></li>' : '';
                echo ( isset($item->mobiliado) && $item->mobiliado > 0 ) ? '<li><strong>Mobiliado: Sim</strong></li>' : '';
                echo ( isset($item->area) && $item->area > 0 ) ? '<li><strong>Área Construída: '.$item->area.'</strong></li>' : '';
                echo ( isset($item->area_terreno) && $item->area_terreno > 0 ) ? '<li><strong>Área Terreno: '.$item->area_terreno.'</strong></li>' : '';
                echo ( isset($item->area_util) && $item->area_util > 0 ) ? '<li><strong>Área útil: '.$item->area_util.'</strong></li>' : '';
                echo '</ul>';
                echo '</div>';
                ?>
                <h1>Detalhes do imóvel</h1>    
                <?php
                echo ( isset($item->nome) && ! empty($item->nome) ) ? '<p><strong>Titulo: </strong>'.$item->nome.'</p>' : '' ;
                echo ( isset( $item->bairro ) && ! empty( $item->bairro ) ) ? '<p><strong>Local: </strong>'.$item->bairro.'</p>' : '';
                echo ( isset( $item->logradouro ) && ! empty( $item->logradouro ) ) ? '<p><strong>Endereço: </strong>'.$item->logradouro.' '.$item->bairro.' - '.$item->cidade.' - '.$item->uf.'</p>' : '';
                echo ( isset( $item->descricao ) && ! empty( $item->descricao ) ) ? '<p>'.$item->descricao.'</p>' : '';
                ?>
            </div>
            <div class="control-group imobiliaria container-fluid">
                <?php 
                if ( isset($item->logo) && ! empty($item->logo) ) :
                    echo '<img src="http://www.guiasjp.com/paginas/'.$item->logo.'" class="pull-left">';
                endif;
                ?> 
                <h3 class="imobiliaria_nome "><?php echo $item->imobiliaria_nome;?></h3>
                <h4 class="imobiliaria_telefone "><a onclick="_gaq.push(['_trackEvent', 'Telefone_Imobiliaria_mobile', 'Ver_Telefone_mobile']);" href="tel:<?php echo '0'.$item->ddd.(trim(substr($item->ddd.$item->imobiliaria_telefone, 0, 9)  ) );?>" class="btn btn-primary tel_log" target="_blank" data-empresa="<?php echo $item->id_empresa;?>"><strong>Ligue para o anunciante</strong> </a></h4>
                <p> Informe que viu este anuncio no <strong>Portal <?php echo substr($cidade->portal, 11);?></strong></p>
            </div>
            <?php 
            if ( ! empty($item->empresa_email) || ( ! empty($item->locacao_email) && $item->imovel_para == 'locação') || ! empty($item->email_corretor)  ) :
                
            ?>
            
            <div class="control-group imobiliaria container-fluid mail">
                <fieldset>
                    <legend>Entre em contato com a Imobiliária: </legend>
                    <form action="<?php echo base_url().'index.php/imoveis/email';?>" method="post" id="form_contato" >
                        <div class="row-fluid">
                            <div class="span12">
                                <?php if ( isset($erro) ) : echo '<div class="'.$erro['class'].'">'.$erro['texto'].'</div>'; endif; ?>
                            </div>
                        </div>
                        <input name="id_empresa" value="<?php echo $item->id_empresa;?>" type="hidden">
                        <input name="id_imovel" value="<?php echo $item->id;?>" type="hidden">
                        <div class="row-fluid">
                            <div class="span12 text-left">
                                <label>
                                    Assunto: Informações sobre o Imóvel: <?php echo $item->id;?> 
                                    <input name="assunto" value="Informações sobre o Imóvel: <?php echo $item->id;?> " type="hidden">
                                </label>
                            </div>
                        </div>
                        
                        <div class="row-fluid">
                            <div class="span3 text_right">
                                <label for="nome">Nome</label>
                            </div>
                            <div class="span9">
                                <input required name="nome" type="text" value="" class="<?php echo ( (form_error('nome')) ? 'alert' : '' );?> nome"  placeholder="<?php echo ( (form_error('nome')) ? str_replace(array('<p>', '</p>'), '', form_error('nome')) : 'Nome' );?>" required="required">
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="span3 text_right">
                                <label for="fone">Telefone</label>
                            </div>
                            <div class="span9">
                                <input required name="fone" type="text" value="" class="<?php echo ( (form_error('fone')) ? 'alert' : '' );?> fone"  placeholder="<?php echo ( (form_error('fone')) ? str_replace(array('<p>', '</p>'), '', form_error('fone')) : 'Telefone com DDD' );?>" required="required">
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="span3 text_right">
                                <label for="email">E-mail</label>
                            </div>
                            <div class="span9">
                                <input required name="email" type="email" value="" class="<?php echo ( (form_error('email')) ? 'alert' : '' );?> email"  placeholder="<?php echo ( (form_error('email')) ? str_replace(array('<p>', '</p>'), '', form_error('email')) : 'E-mail' );?>" required="required">
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="span3 text_right">
                                <label for="cidade">Cidade / UF</label>
                            </div>
                            <div class="span9">
                                <input required name="cidade" type="text" value="" class="<?php echo ( (form_error('cidade')) ? 'alert' : '' );?> cidade"  placeholder="<?php echo ( (form_error('cidade')) ? str_replace(array('<p>', '</p>'), '', form_error('cidade')) : 'Cidade' );?>" required="required">
                                <input required name="uf" type="text" value="" class="<?php echo ( (form_error('uf')) ? 'alert' : '' );?> uf"  placeholder="<?php echo ( (form_error('uf')) ? str_replace(array('<p>', '</p>'), '', form_error('uf')) : 'UF' );?>" required="required">
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="span3 text_right">
                                <label for="mensagem" class="text_right">Mensagem</label>
                            </div>
                            <div class="span9">
                                <textarea name="mensagem" class="mensagem"  placeholder="Mensagem" rows="5" ></textarea>
                            </div>
                        </div> 
                        <div class="row-fluid">
                                
                            <div class="span9 offset3">
                                <input type="checkbox" value="1" name="aceito" checked="checked" class="clearfix"> <p style="margin-left: 45px; margin-top: 10px;">Quero receber outras ofertas de imóveis do Portal <?php echo substr($cidade->portal, 11);?> e seus parceiros.</p>
                            </div>
                        </div> 
                        <div class="row-fluid">
                            <div class="span3 offset9">
                                
                                <center><input type="submit" value="Enviar" class="btn btn-success pull-right"/></center>
                                <div style="display: none; border: none; box-shadow: none;">
                                    <input type="text" name="verificador" value="" style="display: none;">
                                </div>
                            </div>
                        </div>
                    </form>
                </fieldset>
            </div>
            <?php
            endif;
            ?>
    </div>  
</div>