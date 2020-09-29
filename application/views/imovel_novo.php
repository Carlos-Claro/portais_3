<?php 
$conjunto = array();
$conjunto[] = $item->imobiliaria_telefone;
$conjunto[] = substr($item->imobiliaria_telefone, -4);
$contato_imobiliaria = '<div class="imobiliaria" itemprop="brand" itemscope itemtype="http://schema.org/Brand">';
$contato_imobiliaria .= '<div  class="media">';
$contato_imobiliaria .= '<h4 class="text-center"><small>Agente imobiliário responsável pelo Imóvel:</small></h4>';
$contato_imobiliaria .= '<h4 class="media-heading text-left">';
$contato_imobiliaria .= '<img itemprop="logo" src="https://www.pow.com.br/powsites/'.$item->id_empresa.'/'.$item->logo.'" alt="logo '.$item->imobiliaria_nome.'" class="media-object pull-left">';
$contato_imobiliaria .= '<span itemprop="name">';
$contato_imobiliaria .= '<strong>'.$item->imobiliaria_nome.'</strong><br>CRECI:'.$item->creci;
$contato_imobiliaria .= '</span>';
$contato_imobiliaria .= '</h4>';
$contato_imobiliaria .= '<div class="media-body">';
$contato_imobiliaria .= '<p>End: '. $item->imobiliaria_logradouro.', '.$item->imobiliaria_numero.' <br> '.$item->imobiliaria_bairro.' - '.$item->imobiliaria_cidade.'</p>';
$contato_imobiliaria .= '<center><button class="btn btn-topo contato" data-toggle="modal" data-target="#modal-ver-telefone" data-item="'.$item->id_empresa.'" type="button"><span class="glyphicon glyphicon-phone-alt"></span> Ver telefone</button>';
$contato_imobiliaria .= '<button class="btn btn-topo ver-telefone" data-toggle="modal" data-target="#modal-ver-telefone" data-item="'.$item->id_empresa.'" type="button"><span class="glyphicon glyphicon-envelope"></span> Contatar o anunciante</button>';
if ( ! isset($ajax) ) :
    $contato_imobiliaria .= '<a type="button" data-item="'.$item->id_empresa.'" class="btn" target="_blank" href="'.base_url().'imobiliaria/'.urlencode( tira_especiais($item->imobiliaria_nome) ).'-'.$item->id_empresa.'/" > + Imóveis desse anunciante</a></center>';
endif;
$contato_imobiliaria .= '</div>';
$contato_imobiliaria .= '</div>';
$contato_imobiliaria .= '</div>'; 
$video = ( isset($item->video) && ! empty($item->video) && $item->video != 'NULL' ) ? TRUE : FALSE;
?>

<div class="container-fluid fundo-breadcrumb">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?php if ( ! isset($ajax) ) :
                    ?>
                <ol class="breadcrumb" itemtype="http://data-vocabulary.org/Breadcrumb"> 
                    <li>
                        <a href="<?php echo base_url();?>" title="Voltar para a Página Inicial do Portal Imóveis <?php  echo $sobre['nome']; ?>" itemprop="url" >
                            <img src="<?php echo base_url();?>images/icones/imovel.png" alt="Voltar para a Página Inicial do Portal Imóveis <?php  echo $sobre['nome']; ?>">
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo base_url().$breadscrumb;?>" title="Voltar para <?php echo $complemento.' '.$item->imoveis_tipos_titulo;?> do Portal Imóveis <?php  echo $sobre['nome']; ?>" itemprop="url" >
                            <?php echo $complemento.' de '.$item->imoveis_tipos_titulo; ?>
                        </a>
                    </li>
                    <li class="active" itemprop="title">
                        <?php
                        $d = ( ! empty($item->bairro)  ) ? $item->bairro : '';
                        $d .= ' '.( ( $item->area_util > 0.00 ) ? $item->area_util.'m&sup2;' : ''); 
                        $d .= ' '.( ( $item->preco_venda > 0 ) ? 'R$'.  number_format($item->preco_venda, 2, ',', '.') : '');
                        $d .= ' '.( ( $item->preco_locacao > 0 ) ? 'R$'.  number_format($item->preco_locacao, 2, ',', '.') : '' );
                        $d .= ' '.( ( $item->preco_locacao_dia > 0 ) ? 'R$'.  number_format($item->preco_locacao_dia, 2, ',', '.') : '');
                        $palavra_quebrada = wordwrap($d, 30,'%n%',false);
                        $termo = explode('%n%',$palavra_quebrada);
                        echo $termo[0].' <span title="Código do imóvel na rede Portais Imobiliários">PI: '.$item->id.'</span>';
                        ?>
                    </li>
                </ol>
                    <?php
                endif;
                ?>
            </div>
        </div>
    </div>
</div>
<div class="fundo-azul barra-fixa">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 pull-right text-right">
                <button class="btn btn-azul favorito btn-lg" type="button" data-item="<?php echo $item->id;?>"><span class="glyphicon glyphicon-star-empty pull-left"></span><span class="hidden-xs hidden-sm pull-right">&nbsp;<span class="hidden-md">Adicionar aos </span>Favorito</span></button>
                <button class="btn btn-azul erro btn-lg" data-toggle="modal" data-target="#modal-relatar-erro" type="button" data-item="<?php echo $item->id; ?>"><img src="<?php echo base_url();?>images/icones/relatar_erro.png" class="img-icone"> <span class="hidden-xs hidden-sm pull-right">&nbsp;Relatar erro</span></button>
                <button class="btn btn-azul indique btn-lg" data-toggle="modal" data-target="#modal-indicar" type="button" data-item="<?php echo $item->id; ?>"><img src="<?php echo base_url();?>images/icones/contato_branco.png" class="img-icone"> <span class="hidden-xs hidden-sm pull-right">&nbsp;Indique</span></button>
                <a href="<?php echo $link_print;?>" target="_blank" class="btn btn-azul imprimir btn-lg" type="button" data-item="<?php echo $item->id; ?>"><img src="<?php echo base_url();?>images/icones/imprimir.png" class="img-icone"> <span class="hidden-xs hidden-sm pull-right">&nbsp;Imprimir</span></a>
            </div>
        </div>
    </div>
</div>
<div class="primeira-linha"></div>
<!-- inicio das informações titulos, galerias, contato-->
<div class="container-fluid corpo imovel" data-imovel="<?php echo $item->_id;?>" data-log="<?php echo $log;?>">
    <div itemscope itemtype="http://schema.org/Product" class="row ">
        <div class="col-lg-9 col-md-8 col-sm-7 col-xs-12">
            <div class="row">
                <div class="col-lg-7 col-md-8 col-sm-12 col-xs-12">
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
                <div class="col-lg-5 col-md-4 col-sm-12 col-xs-12">
                    <div itemprop="name" >
                        <h1>
                        <?php 
                        $titulo = $item->imoveis_tipos_titulo;
                        $titulo .= ( ($item->venda == 1) )? ' à venda' : '';
                        $titulo .= ( ( ($item->venda == 1) && ($item->locacao == 1) ) || ( ($item->venda == 1) && ($item->locacao_dia == 1) ) ) ? ', ' : '';
                        $titulo .= ( ($item->locacao == 1) ) ? ' para locação' : '';
                        $titulo .= ( ($item->locacao == 1) && ($item->locacao_dia == 1) ) ? ', ' : '';
                        $titulo .= ( ($item->locacao_dia == 1) ) ? ' para locação temporada' : '';
                        if ( ! empty($item->bairro) || ! empty($item->vila) ) :
                            $titulo .= ', no bairro ';
                            $titulo .= ( ! empty($item->bairro) ) ? $item->bairro : '';
                            if ( (! empty($item->bairro) && ! empty($item->vila) && tira_acento($item->vila) !== tira_acento($item->bairro) ) || ( empty($item->bairro) && ! empty($item->vila) ) ) :
                                $titulo .= ( ! empty($item->vila) ) ? ' - '. strtolower($item->vila) : '';
                            endif;
                        endif;
                        $titulo .= isset($item->cidade) ? '&nbsp;em '.$item->cidade.', '.$item->estado : ''; 
                        
                        echo $titulo;
                        ?>
                        </h1>
                    </div>
                    <span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                    <?php 
                    echo ( ($item->venda == 1) && $item->preco_venda > 0 ) ? '<h2 class="valor text-left"><img src="'.base_url().'images/icones/valor.png"> R$ <span itemprop="price">'.number_format($item->preco_venda, 2, ',', '.').'</span></h2>' : '';
                    echo ( ($item->locacao == 1) && $item->preco_locacao > 0 ) ? '<h2 class="text-left"><img src="'.base_url().'images/icones/valor.png">  R$ <span itemprop="price">'.number_format($item->preco_locacao, 2, ',', '.').'</span></h2>' : '';
                    echo ( ($item->locacao_dia == 1) && $item->preco_locacao_dia > 0 ) ? '<h2 class="text-left"><img src="'.base_url().'images/icones/valor.png">  R$ <span itemprop="price">'.number_format($item->preco_locacao_dia, 2, ',', '.').'</span></h2>' : '';
                    ?>
                    </span>
                     <p>
                        <small>Cidade: </small>
                        <?php 
                        echo $item->cidade.' - '.$item->uf;
                        ?>
                    </p>
                        <?php 
                        if ( ! empty($item->bairro) || ! empty($item->vila) ) :
                        ?>
                    <p>
                        <small>Bairro: </small>
                        <?php 
                        echo ( ! empty($item->bairro) ) ? $item->bairro : '';
                        if ( (! empty($item->bairro) && ! empty($item->vila) && tira_acento($item->vila) !== tira_acento($item->bairro) ) || ( empty($item->bairro) && ! empty($item->vila) ) ) :
                            echo ( ! empty($item->vila) ) ? ' - '.$item->vila : '';
                        endif;
                        ?>
                    </p>
                        <?php 
                        endif;
                    if ( ! empty($item->logradouro) ) :
                        ?>
                    <p>
                        <small>Logradouro: </small>
                        <?php 
                        echo strtolower($item->logradouro).', '.$item->numero;
                        ?>
                    </p>
                        <?php
                    endif;
                    if ( $item->quartos > 0 || $item->garagens > 0 || $item->banheiros > 0 )
                    {
                        echo '<p>';
                        if ( ! empty($item->quartos) && $item->quartos > 0 )
                        {
                            echo '<span class="bold"><img src="'.base_url().'images/icones/quartos.png">&nbsp&nbsp'.$item->quartos.'</span> quarto'.( ( $item->quartos > 1 ) ? 's ' : ' ');
                        }
                        echo ( ( (!empty($item->quartos) && $item->quartos > 0) && (!empty($item->garagens) && $item->garagens > 0) ) || ( (!empty($item->quartos) && $item->quartos > 0) && (!empty($item->banheiros) && $item->banheiros > 0) ) ) ? '<br>' : '';
                        if ( ! empty($item->garagens) && $item->garagens > 0 )
                        {
                            echo '<span class="bold"><img src="'.base_url().'images/icones/vagas.png">&nbsp'.$item->garagens.'</span> '. ( ( $item->garagens > 1 ) ? 'vagas ' : 'vaga ' );
                        }
                        echo ( (! empty($item->garagens) && $item->garagens > 0) && (! empty($item->banheiros) && $item->banheiros > 0) ) ? '<br>' : '';
                        if ( ! empty($item->banheiros) && $item->banheiros > 0 )
                        {
                            echo '<span class="bold"><img src="'.base_url().'images/icones/wc.png">&nbsp'.$item->banheiros.'</span> banheiro'. ( ( $item->banheiros > 1 ) ? 's ' : ' ' );
                        }
                        echo '</p>';
                    }
                    if ( ( ! empty($item->area) && $item->area > 0.00 ) || ( ! empty($item->area_terreno) && $item->area_terreno > 0.00 ) )
                    {
                        echo '<p><span class="bold"><img src="'.base_url().'images/icones/area_construida.png">';
                        echo ( ! empty($item->area) && $item->area > 0.00 ) ? $item->area : $item->area_terreno;
                        echo 'm&sup2;</span> de área </p>';
                    }
                    echo ( ! empty($item->area_util) && $item->area_util > 0.00 ) ? '<p class="hidden-xs"><span class="bold"><img src="'.base_url().'images/icones/area_util.png">'.$item->area_util.'m&sup2;</span> de área útil </p>' : '';
                    ?>
                    <p class="text-left"><img src="<?php echo base_url();?>images/icones/codigo.png"><span title="Código PI">PI: </span><?php echo $item->id;?></p>
                    <?php
                    if ( ! empty($item->referencia) ) :
                        ?>
                        <p class="text-left"><img src="<?php echo base_url();?>images/icones/referencia.png">Referência: <?php echo $item->referencia;?> </p>
                        <?php 
                    endif;
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="imobiliaria">
                        <?php // var_dump($item);?>
                        <a href="<?php echo base_url().'imobiliaria/'.urlencode( tira_especiais($item->imobiliaria_nome_seo) ).'-'.$item->id_empresa;?>" target="_blank" title="<?php echo $item->imobiliaria_nome;?>">
                            <img itemprop="logo" src="https://www.pow.com.br/powsites/<?php echo $item->id_empresa.'/'.$item->logo;?>" alt="logo <?php echo $item->imobiliaria_nome;?>" class="pull-left logo-imobiliaria">
                        </a>
                        <div class="identificacao-empresa pull-left">
                            <p>CRECI:<?php echo $item->creci;?></p>
                            <p>End: <?php echo  $item->imobiliaria_logradouro.', '.$item->imobiliaria_numero.' <br> '.$item->imobiliaria_bairro.' - '.$item->imobiliaria_cidade;?></p>
                            <button class="ver-telefone-hover btn  pull-left btn-sm" data-item="<?php echo $item->id_empresa;?>"><span class="glyphicon glyphicon-earphone"></span> Telefone<span class="telefone-hide hide">
                                <?php echo $item->ddd.' - '.$item->imobiliaria_telefone;?>
                                </span></button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h4>Descrição do Imóvel</h4>
                    <?php 
                    if ( ! empty($item->nome)) :
                        ?>
                        <h4 class="descricao">
                            <?php echo $item->nome;?>
                        </h4>
                        <?php 
                    endif;
                    ?>
                    <p class="descricao"><?php echo  (tira_html($item->descricao, $conjunto));?></p>
                </div>
                <div class="col-sm-12 col-xs-12 ">
                    <div class="form-cel hidden">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <h4>Pergunte sobre este Imóvel</h4>
                            </div>
                        </div>
                        <form role="form" method="post" action="<?php echo base_url().'imovel/0/'.$item->id.'/formulario';?>" >
                            <div class="row">
                                <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 coluna">
                                    <input type="hidden" class="id_empresa" name="id_empresa"   value="<?php echo $item->id_empresa;?>">
                                    <input type="hidden" class="id_imovel"  name="id_imovel"    value="<?php echo $item->id;?>" >
                                    <input type="hidden" class="local"      name="local"        value="i<?php echo ($local);?>" >
                                    <input type="text" name="validador" class="validador" value="<?php echo set_value('validador','');?>">
                                    <input type="hidden" name="redirect" class="redirect" value="<?php echo set_value('redirect', base_url().'imovel/0/'.$item->id.'/formulario');?>">
                                    <input type="hidden" class="assunto" name="assunto" value="<?php echo set_value('assunto', 'imóvel referência: '.( ( ! empty($item->referencia) ) ? $item->referencia : $item->id ).' / '.$item->id );?> ">
                                    <div class="form-group mensagem_master">
                                        <!--<label for="mensagem" >Mensagem</label>-->
                                        <textarea name="mensagem" class="form-control mensagem" placeholder="Escreva sua mensagem"><?php echo set_value('mensagem','Gostaria de receber mais informações sobre o imóvel referência: '.( ( ! empty($item->referencia) ) ? $item->referencia : $item->id ).', PI: '.$item->id).' '.$titulo;?></textarea>
                                        <p class="help-block"><?php echo form_error('mensagem');?></p>
                                    </div>
                                    <div class="form-group email_master">
                                        <!--<label for="email" >E-mail</label>-->
                                        <input type="email" name="email" class="form-control email" placeholder="E-mail de Contato" required value="<?php echo set_value('email');?>">
                                        <p class="help-block"><?php echo form_error('email');?></p>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 coluna">
                                    <div class="form-group nome_master">
                                        <!--<label for="nome" >Nome</label>-->
                                        <input type="text" name="nome" class="form-control nome" placeholder="Nome Completo" required value="<?php echo set_value('nome');?>">
                                        <p class="help-block"><?php echo form_error('nome');?></p>
                                    </div>
                                    <div class="form-group telefone_master">
                                        <!--<label for="fone" >Telefone</label>-->
                                        <input type="text" name="fone" class="form-control telefone" placeholder="Telefone 41 1111 1111 " value="<?php echo set_value('fone');?>">
                                        <p class="help-block"><?php echo form_error('fone');?></p>
                                    </div>
                                    <div class="form-group"><label>Desejo receber contato por:</label>
                                        <div class="row">
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                                <label><input type="checkbox" name="telefone" value="1" class="check_telefone pull-left" checked><img src="<?php echo base_url();?>images/icones/telefone.png" class="img-responsive pull-left"> 
                                                    <span>&nbsp;Telefone</span>
                                                </label>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                                <label><input type="checkbox" name="whatsapp" value="1" class="check_whatsapp pull-left" checked="checked"><img src="<?php echo base_url();?>images/icones/whats.png" class="img-responsive pull-left"> 
                                                    <span>&nbsp;WhatsApp</span>
                                                </label>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                                <label><input type="checkbox" name="comunica-email" value="1" class="check_email pull-left" checked="checked"><span class="glyphicon glyphicon-envelope" style="font-size:14px; margin-left:5px;"></span> <span>&nbsp;Email</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group"><button class="btn btn-success envia ladda-button g-recaptcha" data-sitekey="<?php echo $sitekey;?>" type="submit" onclick="_gaq.push([\'_trackEvent\', \'Pergunta_Imobiliaria\', \'Envie_sua_pergunta_form01\']);" data-size="invisible" data-callback="onSubmit"> Contatar Anunciante </button></div>
                                    <div class="form-group"><label>&nbsp;Ao enviar, você concorda com os <a data-toggle="modal" data-target="#modal-termo" href="#">Termos de Uso</a>, <a data-toggle="modal" data-target="#modal-politica" href="#">Política de Privacidade</a> e recebimento de sugestões de imóveis.</label></div>
                                </div><!-- fecha coluna -->
                            </div><!-- fecha row -->
                        </form>
                    </div><!-- .formulario -->
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h4 class="text-center"><small><a href="<?php echo base_url().'imobiliaria/'.urlencode( tira_especiais($item->imobiliaria_nome_seo) ).'-'.$item->id_empresa;?>" target="_blank">Aproveite e veja mais imóveis de <strong><?php echo $item->imobiliaria_nome;?></strong></a></small></h4>
                    <?php 
                    if ( isset($relacionados_imobiliaria) ) :
                        echo $relacionados_imobiliaria;
                    endif;
                    ?>
                </div>
            </div>
            <?php
            if ( $item->mostramapa && isset($item->mapa) && ! empty($item->mapa) ) :
            ?>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h4>Localização do Imóvel</h4>
                    <div class="mapa" >
                        <?php echo $mapa;?>
                    </div> 
                </div>
            </div>
            <?php
            endif;
            ?>
            
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <?php 
                    if ( isset($relacionados) && ! isset($ajax) ) :
                        ?>
                    <div class="relacionados">
                        <br>
                        <h4 class="text-center"><small> Talvez você goste desses imóveis: </small></h4>
                        <ul class="media-list">
                            <?php 
                            echo $relacionados;
                        ?>
                        </ul>
                    </div>
                        <?php 
                    endif;
                    if ( ! isset($ajax) ) :
                        if ( $item->venda == 1 )
                        {
                            $link = 'venda';
                        }
                        elseif ( $item->locacao == 1 )
                        {
                            $link = 'locacao';
                        }
                        else
                        {
                            $link = 'locacao_dia';
                            
                        }
                        //var_dump($item->imovel_para[0]);
                    ?>
                    <div class="relacionados">
                        <br>
                        <p class="titulo_horizontal"> Continue na lista de imóveis: </p>
                        <hr>
                        
                        <a href="<?php echo base_url().'imobiliaria/'.urlencode( tira_especiais($item->imobiliaria_nome_seo) ).'-'.$item->id_empresa;?>">Da imobiliária <?php echo $item->nome_fantasia;?></a>
                        <br><a href="<?php echo base_url().'imoveis-'.$item->cidade_link; ?>">Mais imóveis em <?php echo $item->cidade;?></a>
                        <br><a href="<?php echo base_url().'imoveis-'.$link.'-'.$item->cidade_link; ?>">Mais imóveis para <?php echo $item->imovel_para[0];?> em <?php echo $item->cidade;?></a>
                        <br><a href="<?php echo base_url().$item->imoveis_tipos_link.'-'.$link.'-'.$item->cidade_link; ?>">Mais <?php echo $item->imoveis_tipos_titulo;?> para <?php echo $item->imovel_para[0];?> em <?php echo $item->cidade;?></a>
                        <?php
                        if ( ! empty($item->bairro) ) :
                        ?>
                        <br><a href="<?php echo base_url().$item->imoveis_tipos_link.'-'.$link.'-'.$item->cidade_link.'-'.$item->bairros_link; ?>">Mais <?php echo $item->imoveis_tipos_titulo;?> para <?php echo $item->imovel_para[0];?> em <?php echo $item->bairro;?></a>
                        <?php
                        endif;
                        ?>
                    </div>
                    <?php
                    endif;
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                    <p class="aviso">ATENÇÃO: Este imóvel de código <?php echo $item->_id;?> aqui exibido mostra informações fornecidas e mantidas por <?php echo $item->imobiliaria_nome;?> e correspondem a um anúncio publicitário. Os dados, inclusive preços, deste imóvel poderão sofrer alterações sem aviso prévio. <?php echo ($item->data_atualizacao > 0) ? 'Imóvel atualizado em '.  $item->data_atualizacao.', por '.$item->imobiliaria_nome.' .' : '';?> O portal <?php echo substr($sobre['portal'],11);?> não garante a precisão ou veracidade do anúncio ou de qualquer informação associada a ele. O portal <?php echo substr($sobre['portal'],11);?> não possui controle sobre o conteúdo, que é de responsabilidade de <?php echo $item->imobiliaria_nome;?> . Para mais informações sobre o imóvel favor contactar diretamente com <?php echo $item->imobiliaria_nome;?>. </p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-5 col-xs-12">
            <div class="formulario">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <h4>Pergunte sobre este Imóvel</h4>
                    </div>
                </div>
                <form role="form" method="post" action="<?php echo base_url().'imovel/0/'.$item->id.'/formulario';?>" >
                    <div class="row">
                        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 coluna">
                            <input type="hidden" class="id_empresa" name="id_empresa"   value="<?php echo $item->id_empresa;?>">
                            <input type="hidden" class="id_imovel"  name="id_imovel"    value="<?php echo $item->id;?>" >
                            <input type="hidden" class="local"      name="local"        value="i<?php echo ($local);?>" >
                            <input type="text" name="validador" class="validador" value="<?php echo set_value('validador','');?>">
                            <input type="hidden" name="redirect" class="redirect" value="<?php echo set_value('redirect', base_url().'imovel/0/'.$item->id.'/formulario');?>">
                            <input type="hidden" class="assunto" name="assunto" value="<?php echo set_value('assunto', 'imóvel referência: '.( ( ! empty($item->referencia) ) ? $item->referencia : $item->id ).' / '.$item->id );?> ">
                            <div class="form-group mensagem_master">
                                <!--<label for="mensagem" >Mensagem</label>-->
                                <textarea name="mensagem" class="form-control mensagem" placeholder="Escreva sua mensagem"><?php echo set_value('mensagem','Gostaria de receber mais informações sobre o imóvel referência: '.( ( ! empty($item->referencia) ) ? $item->referencia : $item->id ).', PI: '.$item->id).' '.$titulo;?></textarea>
                                <p class="help-block"><?php echo form_error('mensagem');?></p>
                            </div>
                            <div class="form-group email_master">
                                <!--<label for="email" >E-mail</label>-->
                                <input type="email" name="email" class="form-control email" placeholder="E-mail de Contato" required value="<?php echo set_value('email');?>">
                                <p class="help-block"><?php echo form_error('email');?></p>
                            </div>
                        </div>
                        <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 coluna">
                            <div class="form-group nome_master">
                                <!--<label for="nome" >Nome</label>-->
                                <input type="text" name="nome" class="form-control nome" placeholder="Nome Completo" required value="<?php echo set_value('nome');?>">
                                <p class="help-block"><?php echo form_error('nome');?></p>
                            </div>
                            <div class="form-group telefone_master">
                                <!--<label for="fone" >Telefone</label>-->
                                <input type="text" name="fone" class="form-control telefone" placeholder="Telefone 41 1111 1111 " value="<?php echo set_value('fone');?>">
                                <p class="help-block"><?php echo form_error('fone');?></p>
                            </div>
                            <div class="form-group"><label>Desejo receber contato por:</label>
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                        <label><input type="checkbox" name="telefone" value="1" class="check_telefone pull-left" checked><img src="<?php echo base_url();?>images/icones/telefone.png" class="img-responsive pull-left"> 
                                            <span>&nbsp;Telefone</span>
                                        </label>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                        <label><input type="checkbox" name="whatsapp" value="1" class="check_whatsapp pull-left" checked="checked"><img src="<?php echo base_url();?>images/icones/whats.png" class="img-responsive pull-left"> 
                                            <span>&nbsp;WhatsApp</span>
                                        </label>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                        <label><input type="checkbox" name="comunica-email" value="1" class="check_email pull-left" checked="checked"><span class="glyphicon glyphicon-envelope" style="font-size:14px; margin-left:5px;"></span> <span>&nbsp;Email</span></label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group"><button class="btn btn-success envia ladda-button g-recaptcha" data-sitekey="<?php echo $sitekey;?>" type="submit" onclick="_gaq.push([\'_trackEvent\', \'Pergunta_Imobiliaria\', \'Envie_sua_pergunta_form01\']);" data-size="invisible" data-callback="onSubmit"> Contatar Anunciante </button></div>
                            <div class="form-group"><label>&nbsp;Ao enviar, você concorda com os <a data-toggle="modal" data-target="#modal-termo" href="#">Termos de Uso</a>, <a data-toggle="modal" data-target="#modal-politica" href="#">Política de Privacidade</a> e recebimento de sugestões de imóveis.</label></div>
                        </div><!-- fecha coluna -->
                    </div><!-- fecha row -->
                </form>
            </div><!-- .formulario -->
        </div>
    </div>
    
</div><!-- .corpo -->
<div class="ultima-linha"></div>
<div class="visible-xs botao-celular">
    <?php
    $col= 6;
    $link_whats = '';
    if ( isset($item->imobiliaria_whatsapp) && ! empty($item->imobiliaria_whatsapp) ):
        $col = 4;
        $link_whats = '<div class="col-xs-4"><a href="https://api.whatsapp.com/send?phone=+55'.$item->imobiliaria_whatsapp.'&text='. urlencode('Gostaria de saber mais sobre o imóvel: '.$url).'" class="btn btn-success btn-lg ver-telefone-whats" data-item="'.$item->id.'"><img src="'.base_url().'images/whatsapp.png" class="img-responsive"></a></div>';
    endif;
    //
    ?>
    <div class="row">
        <div class="col-xs-<?php echo $col;?> ">
            <a href="tel:<?php echo '+55'.$item->ddd.''.$item->imobiliaria_telefone;?>" class="btn btn-success btn-lg ver-telefone-celular" data-item="<?php echo $item->id;?>"><span class=" glyphicon glyphicon-earphone"></span></a>
        </div>
        <?php echo $link_whats;?>
        <div class="col-xs-<?php echo $col;?>">
            <button class="btn btn-success btn-lg botao-xs-form" data-toggle="modal" data-target="#modal-formulario-xs"><span class=" glyphicon glyphicon-envelope"></span></button>
        </div>
    </div>
    <div class="form-espera hide"></div>
</div>

<div class="modal fade" id="modal-formulario-xs" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog container">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="modal fade" id="modal-ver-telefone" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog container">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Telefone: <?php echo $item->imobiliaria_nome;?></h4>
      </div>
      <div class="modal-body">
          <div class="col-lg-4 col-md-4 col-sm-6  col-xs-12 imobiliaria pull-right">
              <div class="text-center telefone-ver-telefone">
                  <h3>Telefone:<span class="visible-xs"></span> <?php echo $item->ddd.' - '.$item->imobiliaria_telefone;?></h3>
              </div> <br/>
              <?php echo str_replace('itemprop="brand" itemscope itemtype="http://schema.org/Brand"', '', $contato_imobiliaria);?>
          </div>
          <div class="col-lg-8 col-md-8 col-sm-6  col-xs-12 pull-left">
              <?php echo $formulario;?>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="modal fade" id="modal-solicitar-ligacao" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog container">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Solicitar ligação para: <?php echo $item->imobiliaria_nome;?></h4>
      </div>
      <div class="modal-body">
        <div class="formulario_">
            <form method="post" action="<?php echo base_url()?>index/email_solicita_ligacao">
                <input type="hidden" name="empresa" value="<?php echo $item->id_empresa; ?>">
                <input type="hidden" name="imovel" value="<?php echo $item->id; ?>">
                <input type="text" name="validador" class="validador" value="">
                <input type="hidden" name="local" value="i<?php echo $local;?>">
                <input type="hidden" name="assunto" value="Contato via Portal Imóveis <?php echo $sobre['nome']; ?>, Solicitação de Ligação telefonica. ">
                <div class="form-group"><input type="text" name="remetente[nome]" class="form-control" placeholder="Nome Completo" required></div>
                <div class="form-group"><input type="email" name="remetente[email]" class="form-control" placeholder="E-mail de Contato" required></div>
                <div class="form-group"><input type="text" name="remetente[fone]" class="form-control telefone" placeholder="Telefone 41 1111 1111 " required></div>
                <div class="form-group"><textarea name="mensagem" class="form-control" placeholder="Escreva sua mensagem">Solicito retorno de ligação sobre o imóvel PI: <?php echo $item->id;?>.</textarea></div>
                <div class="form-group"><label><input type="checkbox" name="aceito" value="1" checked> Gostaria de receber noticias e novidades da Rede Portais Imobiliários</label></div>
                <div class="form-group"><button class="btn btn-busca-horizontal" type="submit"> Envie minha Solicitação</button></div>
            </form>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="modal fade" id="modal-relatar-erro" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog container">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Relatar Erro:</h4>
      </div>
      <div class="modal-body row">
        <div class="formulario_ col-lg-12 col-md-12 col-sm-12  col-xs-12">
            <form method="post" action="<?php echo base_url()?>index.php/index/email_relatar_erro">
                <input type="hidden" name="empresa" value="<?php echo $item->id_empresa; ?>">
                <input type="hidden" name="imovel" value="<?php echo $item->id; ?>">
                <input type="hidden" name="assunto" value="Relatar erro.">
                <input type="text" name="validador" class="validador" value="">
                <div class="form-group"><input type="text" name="remetente[nome]" class="form-control" placeholder="Nome Completo" required></div>
                <div class="form-group"><input type="email" name="remetente[email]" class="form-control" placeholder="E-mail de Contato" required></div>
                <div class="form-group"><textarea name="mensagem" class="form-control" placeholder="Descreva o erro encontrado.">Relatando um erro no imóvel: <?php echo $item->id;?></textarea></div>
                <div class="form-group"><button class="btn btn-busca-horizontal" type="submit"> Envie minha Observação</button></div>
            </form>
        </div>
          
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade container" id="modal-indicar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Indique estes imóveis </h4>
            </div>
            <div class="modal-body">
                <div class="formulario_">
                    <form method="post" action="<?php echo base_url();?>index/email_indica">
                        <input type="hidden" name="imoveis" class="imoveis" value="<?php echo $item->id;?>">
                        <input type="hidden" name="redirect" class="" value="imovel">
                        <input type="text" name="validador" class="validador" value="">
                        <input type="hidden" name="local" value="i<?php echo $local;?>">
                        <div class="form-group"><label>Seu nome: </label><input required type="text" class="form-control" name="remetente[nome]" value="" placeholder="Digite seu nome"></div>
                        <div class="form-group"><label>Seu E-mail: </label><input required type="email" class="form-control" name="remetente[email]" value="" placeholder="Digite seu e-mail"></div>
                        <div class="form-group"><label>Digite o email dos destinatários: (separe varios e-mails por ","(vírgula))</label><textarea required name="emails" class="form-control" placeholder=""></textarea></div>
                        <div class="form-group"><label>Mensagem que acompanha o envio.</label><textarea name="mensagem" class="form-control" placeholder="Mensagem que acompanha o envio."></textarea></div>
                        <div class="form-group"><button class="btn btn-primary" type="submit"> Enviar imóveis </button></div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="modal fade container" id="modal-politica" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Política de privacidade PORTAISIMOBILIARIOS.COM</h4>
            </div>
            <div class="modal-body">
                <p>
Esse Portal é integrante da rede PORTAISIMOBILIARIOS.COM.
                </p>
                <p>
O presente instrumento estabelece a política de privacidade e segurança de informações e de direitos e deveres assumidos pelos seus anunciantes, usuários e pelo PORTAISIMOBILIARIOS.COM quanto ao presente Portal de serviços de publicidade.
                </p>
                <p>
Através deste documento, a rede PORTAISIMOBILIARIOS.COM esclarece aos usuários como essas informações são coletadas e a que uso se destina.
O PORTAISIMOBILIARIOS.COM mostra o respeito por sua privacidade nos termos a seguir:
                </p>
                <p>
A rede PORTAISIMOBILIARIOS.COM não fornecerá informações pessoais do usuário sem sua devida autorização, exceto se tais informações forem necessárias para prestar o serviço solicitado pelo próprio usuário, ou, ainda, na hipótese das informações pessoais do usuário ser requeridas por motivos legais. A rede tomará todas as medidas possíveis para manter a confidencialidade e a segurança das informações do usuário, porém não responderá por prejuízo que possa advir da divulgação de tais informações pelo motivo excepcionado acima, ou ainda por violação ou quebra das barreiras de segurança de internet por terceiros como “hackers” ou “crackers”.
                </p>
                <p>
A rede PORTAISIMOBILIARIOS.COM utiliza ainda a tecnologia “Cookies HTTP”, que são mecanismos reconhecidamente confiáveis que analisam as preferências de navegação do usuário e o direcionam com precisão para aqueles assuntos de seu maior interesse e a parceiros comerciais. Em geral, é possível desativar estes “Cookies HTTP”, para tanto basta desabilitá-los através da caixa de ferramentas do seu navegador. É importante dizer que os “cookies” não executam programas nem infectam computadores com vírus, e só podem ser lidos por nós.
                </p>
                <p>
Ou seja, o objetivo da instalação dos “cookies” é sempre beneficiar o USUÁRIO que os recebe.
                </p>
                <p>
Para ter acesso às algumas seções dos portais da rede, o usuário deve preencher um formulário, no qual ele deverá fornecer algumas informações, como por exemplo, forma de contato, entre outras.
                </p>
                <p>
Este registro é armazenado em um banco de dados protegido e sigiloso.
                </p>
                <p>
Quanto mais informações você fornecer, melhor será a personalização da sua experiência.
                </p>
                <p>
Os newsletters e mensagens publicitárias enviadas por e-mail sempre trarão opção de cancelamento do envio daquele tipo de mensagem por parte da rede PORTAISIMOBILIARIOS.COM
                </p>
                <p>
Não nos responsabilizamos por eventuais prejuízos advindos de eventual divulgação de dados, seja por erro no preenchimento ou mau uso do usuário, seja de ordem legal, judicial, ou quebra de sistema por terceiros.
                </p>
                <p>
Ciente destas condições torna-se inegavelmente uma decisão singular do usuário a utilização deste Portal.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="modal fade container" id="modal-termo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Termos de uso PORTAIS IMOBILIÁRIOS</h4>
            </div>
            <div class="modal-body">
                <p>

Portais Imobiliários:
                </p>
                <p>
Com o objetivo de integrar várias cidades do Brasil o PORTAISIMOBILIARIOS.COM atualmente conta com mais de 270 cidades integrantes da rede. Cada cidade conta com seu próprio site, estes são de nomes intuitivos, com fácil memorização e busca.
Esse portal é integrante da rede PORTAISIMOBILIARIOS.COM.
                </p>
                <p>


É imprescindível que seus anunciantes e usuários leiam com atenção o presente TERMO DE USO antes de acessar e utilizar o Portal PORTAISIMOBILIARIOS.COM eis que seu acesso repercute na concordância de todos os seus estritos termos de uso.
                </p>
                <p>

O PORTAISIMOBILIARIOS.COM atua como mero veiculador de anúncios, serviços e informações correlatas e específicas de seus anunciantes relativas a imóveis. Portanto, não presta serviços de consultoria ou intermediações de negócios entre seus anunciantes e usuários.
                </p>
                <p>

Dessa forma, o SITE não assume responsabilidade por nenhuma consequência que possa advir de qualquer relação entre o USUÁRIO e o(s) ANUNCIANTE(S), seja ela direta ou indireta.
                </p>
                <p>

Os anúncios, produtos e informações veiculadas em qualquer um dos sites que fazem parte da rede PORTAISIMOBILIARIOS.COM são de inteira, total e exclusiva responsabilidade dos seus anunciantes e usuários, de forma que qualquer ação comissiva ou omissiva dos seus anunciantes e usuários tais como, mas não se limitando, a erros, fraudes, inexatidão ou divergência de dados, fotos, vídeos ou outros materiais relacionados a anúncios ou oferecidas pelos usuários, são de única e exclusiva responsabilidade de seus anunciantes e usuários.
                </p>
                <p>

A rede PORTAISIMOBILIARIOS.COM também não se responsabiliza pelo cumprimento devido por Você, por anunciantes ou quaisquer terceiros, das respectivas obrigações tributárias que venham a incidir, nos termos da lei vigente, sobre quaisquer atividades, operações ou negócios que tenham sua origem nos anúncios veiculados em um dos portais da rede. Portanto, a rede PORTAISIMOBILIARIOS.COM recomenda expressamente que Você fique atento a estes pontos e, ao adquirir algum bem, exija todos os documentos fiscais e legais pertinentes.
                </p>
                <p>

As marcas, logotipos layouts, nomeações de serviços e todo material deste portal são de propriedade da rede PORTAISIMOBILIARIOS.COM e seus associados, ficando estritamente proibido o seu uso por terceiros sem prévia autorização da direção da rede PORTAISIMOBILIARIOS.COM.
                </p>
                <p>

A gestão dos anúncios e informações (vídeos, fotos e características dos bens oferecidos) será realizada exclusivamente pelos anunciantes, mediante cadastro prévio, com inserção e exclusão de informações e dados através de login e senha de caráter confidencial e pessoal dos anunciantes e sob a responsabilidade destes.
                </p>
                <p>


Ao Transmitir Conteúdo à rede PORTAISIMOBILIARIOS.COM, Você afirma e garante que tem o direito de ceder e transferir a rede PORTAISIMOBILIARIOS.COM, como de fato cede e transfere uma licença irrevogável, perpétua, não exclusiva, gratuita e mundial para que a rede PORTAISIMOBILIARIOS.COM possa usar, copiar, exibir e distribuir o Conteúdo, bem como preparar trabalhos derivados dele, ou incorporar em outros trabalhos ao Conteúdo, bem como deles livremente dispor.
                </p>
                <p>


Para fins de obtenção de informações sobre um anúncio, o usuário da rede PORTAISIMOBILIARIOS.COM não necessitará desembolsar qualquer valor em favor do anunciante. Qualquer infração nesse sentido por parte do anunciante deve ser notificada imediatamente a rede PORTAISIMOBILIARIOS.COM.
                </p>
                <p>

Ao adquirir qualquer bem, o usuário deverá exigir sempre nota fiscal do anunciante, a menos que este esteja dispensado legalmente de fornecê-la, uma vez que o Portal também não se responsabiliza pelas exações tributárias que recaiam sobre a relação comercial estabelecida entre anunciante e usuário.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php
if ( isset($tag_adwords) && ! empty($tag_adwords) ) :
    echo '<div class="tem-adwords"></div>';
endif;
echo $tag_adwords;
/*
deletações:
 * 
 * 
 * <!--
                        <ol class="carousel-indicators">
                            <?php //echo $ol;?>
                        </ol>
-->
 * 
 * 
*/