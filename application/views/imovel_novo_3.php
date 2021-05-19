<div class="c-content-box c-content-box c-overflow-hide c-bg-white corpo imovel" data-imovel="<?php echo $item->_id;?>" data-log="<?php echo $log;?>">
        <div class="c-shop-product-detail-4">
            <div class="c-product-header c-content-title-4">
                <h1 class="c-font-uppercase c-font-bold c-left margin-titulo"><?php echo $h1;?></h1>
            </div>
        </div>
        <div class="c-product-desc ">
            
                <div class="row" id="images">
                    <div class="col-md-7">
                    <?php
                    echo $images['galeria'];
                    ?>
                    </div>
                    <div class="col-md-5">
                        <h4 class="c-left c-font-18 c-font-bold c-font-uppercase titulo-ficha">Descrição</h4>
                        <div class="c-line-center c-theme-bg"></div>
                        <div class="c-content-title-1 c-font-14 imovel-descricao">
                            <p><?php echo nl2br($item->descricao);?></p>
                        </div>
                    </div>
                </div>
                <div class="row primeira-linha">
                    <div class="col-lg-7 col-sm-7 col-md-7 col-xs-12">
                        <h4 class="c-left c-font-18 c-font-bold c-font-uppercase titulo-ficha">Características</h4>
                        <div class="c-line-center c-theme-bg"></div>
                        <?php echo $lista;?>
                    </div>
                    <div class="col-lg-5 col-sm-5 col-md-5 col-xs-12">
                        <h4 class="text-left azul-pow c-left c-font-16 c-font-bold c-font-uppercase">Agente imobiliário responsável pelo Imóvel:</h4>
                        <div class="c-line-center c-theme-bg"></div>
                        <div class="empresa imobiliaria" id="empresa" data-empresa="<?php echo $item->id_empresa;?>">
                            <div  class="media">
                                <a href="<?php echo base_url().'imobiliaria/'.urlencode( tira_especiais($item->imobiliaria_nome) ).'-'.$item->id_empresa;?>/">
                                <h4 class="media-heading text-left">
                                        <img itemprop="logo" width="80" height="60" src="https://www.pow.com.br/powsites/<?php echo $item->id_empresa.'/'.$item->logo;?>" alt="logo <?php echo $item->imobiliaria_nome;?>" class="media-object pull-left">
                                    <span itemprop="name"><strong><?php echo $item->imobiliaria_nome;?></strong><br>CRECI: <?php echo $item->creci;?></span>
                                </h4>
                                </a>
                                <div class="media-body">
                                    <p>End: <?php echo $item->imobiliaria_logradouro.', '.$item->imobiliaria_numero.' <br> '.$item->imobiliaria_bairro.' - '.$item->imobiliaria_cidade;?></p>
                                    <ul class="list-unstyled list-inline">

                                        <li>
                                        <button class="btn btn-topo btn-vermelho-pow ver-telefone mt-ladda-btn ladda-button" data-style="zoom-in" data-item="<?php echo $item->id_empresa;?>" type="button">Ver telefone</button>
                                        </li>
                                        <?php
                                        $col= 6;
                                        $link_whats = '';
                                        if ( isset($item->imobiliaria_whatsapp) && ! empty($item->imobiliaria_whatsapp) ):
                                            $col = 4;
                                            ?>
                                            <li class="dropup">
                                                <div class="btn-group btn-vertical">
                                                    <button type="button"  class="btn btn-whats hidden-xs whats-desktop  btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fa fa-whatsapp"></i> WhatsApp
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a target="_blank" class="btn ver-telefone-whats-lista"  href="https://web.whatsapp.com/send?phone=+55<?php echo $item->imobiliaria_whatsapp.'&text='. urlencode('Gostaria de saber mais sobre o imóvel: '.$url);?>" data-item="<?php echo $item->id;?>" data-log="<?php echo $origem;?>">Conversar com a imobiliária</a>
                                                        </li>
                                                        <li>
                                                            <a target="_blank" class="btn compartilha-telefone-whats-lista" href="https://web.whatsapp.com/send?<?php echo '&text='. urlencode('Conheça esse imóvel: '.$url);?>" data-item="<?php echo $item->id;?>" data-log="<?php echo $origem;?>">Enviar para um amigo</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="btn-group btn-vertical">
                                                    <button type="button" class="btn btn-whats whats-mobile btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
                                            <a type="button" data-item="<?php echo $item->id_empresa;?>" class="btn btn-azul-pow" target="_blank" href="<?php echo base_url().'imobiliaria/'.urlencode( tira_especiais($item->imobiliaria_nome) ).'-'.$item->id_empresa;?>/" > + Imóveis <span class="hidden-xs">do anunciante</span></a>
                                        </li>
                                    </ul>
                                    <div class="espaco-telefone hide">
                                        <div class="portlet light">
                                            <div class="portlet-body"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-5 col-sm-12 col-xs-12 formulario-original">
                        <div class="formulario form-cel" id="form-cel">
                            <div class="portlet box portlet-azul-pow">
                                <div class="portlet-title">
                                    <ul class="nav nav-tabs nav-justified">
                                        <li class="tipo-contato active" data-item="tab-pergunte">
                                            <a href="#pergunte_imovel" data-toggle="tab"> Dúvidas </a>
                                        </li>
                                        <li class="tipo-contato" data-item="tab-ligacao">
                                            <a href="#solicite_ligacao" data-toggle="tab"> Solicite uma ligação </a>
                                        </li>
                                        <?php if ( $item->venda ) :?>
                                        <li class="tipo-contato" data-item="tab-simule">
                                            <a href="#simule_financiamento" data-toggle="tab"> Simule um financiamento </a>
                                        </li>
                                        <?php endif;?>
                                    </ul>
                                </div>
                                <div class="portlet-body">
                                    <span class="form-0"></span>
                                    <form role="form" method="post" data-tipo="" id="form-cel" >
                                        <input type="hidden" class="id_empresa" name="id_empresa"   value="<?php echo $item->id_empresa;?>">
                                        <input type="hidden" class="id_imovel"  name="id_imovel"    value="<?php echo $item->id;?>" >
                                        <input type="hidden" class="local"      name="local"        value="i<?php echo ($local);?>" >
                                        <input type="text" name="validador" class="validador" value="<?php echo set_value('validador','');?>">
                                        <input type="hidden" class="assunto" name="assunto" value="<?php echo set_value('assunto', 'imóvel referência: '.( ( ! empty($item->referencia) ) ? $item->referencia : $item->id ).' / '.$item->id );?> ">
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="pergunte_imovel">
                                                        <h4>Pergunte sobre este Imóvel</h4>

                                            </div>
                                            <div class="tab-pane" id="simule_financiamento">
                                                        <h4>Simule um Financiamento</h4>
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-left">
                                                        <label>Quantidade de parcelas</label>
                                                        <div class="btn-group" data-toggle="buttons">
                                                            <label class="btn btn-horario">
                                                                <input type="checkbox" value="60" class="toggle parcelas" > 
                                                                60 x 
                                                            </label>
                                                            <label class="btn btn-horario">
                                                                <input type="checkbox" value="120" class="toggle parcelas" > 
                                                                120 x 
                                                            </label>
                                                            <label class="btn btn-horario">
                                                                <input type="checkbox" value="180" class="toggle parcelas" > 
                                                                180 x 
                                                            </label>
                                                            <label class="btn btn-horario">
                                                                <input type="checkbox" value="240" class="toggle parcelas" > 
                                                                240 x 
                                                            </label>
                                                            <label class="btn btn-horario">
                                                                <input type="checkbox" value="320" class="toggle parcelas" > 
                                                                320 x 
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 coluna">
                                                        <div class="form-group valor_entrada margin-top-10">
                                                            <input type="text" name="valor_entrada" class="form-control valor_entrada" placeholder="Valor da entrada" required value="<?php echo set_value('valor_entrada');?>">
                                                            <p class="help-block"><?php echo form_error('valor_entrada');?></p>
                                                            <input type="text" name="valor_entrada" class="form-control valor_entrada" placeholder="Renda mensal" required value="<?php echo set_value('renda_mensal');?>">
                                                            <p class="help-block"><?php echo form_error('renda_mensal');?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="solicite_ligacao">
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <h4>Solicite uma ligação desta imobiliária</h4>
                                                    </div>
                                                </div>
                                                <div class="row c-bottom-15">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-left">
                                                        <h5>Clique no melhor período para receber ligações</h5>
                                                        <div class="btn-group" data-toggle="buttons">
                                                            <label class="btn btn-horario">
                                                                <input type="checkbox" value="qualquer" class="toggle horario"> 
                                                                Qualquer horário 
                                                            </label>
                                                            <label class="btn btn-horario">
                                                                <input type="checkbox" value="manha" class="toggle horario" > 
                                                                Manhã
                                                            </label>
                                                            <label class="btn btn-horario">
                                                                <input type="checkbox" value="tarde" class="toggle horario" > 
                                                                Tarde
                                                            </label>
                                                            <label class="btn btn-horario">
                                                                <input type="checkbox" value="noite" class="toggle horario" > 
                                                                Noite 
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 coluna">
                                                <div class="form-group mensagem_master">
                                                    <!--<label for="mensagem" >Mensagem</label>-->
                                                    <textarea name="mensagem" class="form-control mensagem" placeholder="Escreva sua mensagem"><?php echo set_value('mensagem','Imóvel referência: '.( ( ! empty($item->referencia) ) ? $item->referencia : $item->id ).', PI: '.$item->id).' '.$titulo;?></textarea>
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
                                                    <input type="text" name="fone" class="form-control fone" placeholder="Telefone 41 1111 1111 " value="<?php echo set_value('fone');?>">
                                                    <p class="help-block"><?php echo form_error('fone');?></p>
                                                </div>
                                                <div class="form-group"><label>Desejo receber contato por:</label>
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                                            <label><input type="checkbox" name="telefone" value="1" class="check_telefone pull-left" checked>&nbsp;Telefone
                                                            </label>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                                            <label><input type="checkbox" name="whatsapp" value="1" class="check_whatsapp pull-left" checked="checked">&nbsp;WhatsApp
                                                            </label>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                                            <label><input type="checkbox" name="comunica-email" value="1" class="check_email pull-left" checked="checked">&nbsp;Email
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group"><button class="btn btn-success envia ladda-button g-recaptcha" data-sitekey="<?php echo $sitekey;?>" type="submit" data-callback="onSubmit" data-action="submit"> Contatar Anunciante </button></div>
                                                <div class="form-group"><label>&nbsp;Ao enviar, você concorda com os <a data-toggle="modal" data-target="#modal" class="termos" href="#">Termos de Uso</a>, <a data-toggle="modal" data-target="#modal" class="politica" href="#">Política de Privacidade</a> e recebimento de sugestões de imóveis.</label></div>
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
                                            </div><!-- fecha coluna -->
                                        </div><!-- fecha row -->
                                    </form>
                                </div>
                            </div>
                        </div><!-- .formulario -->
                        <span class="form-1"></span>
                    </div>
                </div>
                <span class="ultima-linha"></span>
            </div>
        </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 c-margin-t-20 hidden-xs" >
                <ul class="list-unstyled list-inline ">
                    <li>
                        <button class="btn btn-vermelho-pow c-btn-uppercase c-btn-bold c-btn-square favoritar favorito-<?php echo $item->_id;?>" type="button" data-item="<?php echo $item->_id;?>">
                            <span class="glyphicon glyphicon-star<?php echo ( array_key_exists($item->_id, $itens_favoritos) ? '' : '-empty' );?>"></span> 
                            <span class="hidden-md hidden-sm hidden-xs texto"><?php echo ( array_key_exists($item->_id, $itens_favoritos) ? 'Retirar de' : 'Adicionar a' );?></span> favoritos
                        </button>
                    </li>
                    <li >
                        <a href="<?php echo $link_print;?>" class="btn btn-azul-pow">Imprimir</a>
                    </li>
<!--                    <li >
                        <a class="btn btn-azul-pow">Indicar</a>
                    </li>-->
                    <?php
                       $col= 6;
                       $link_whats = '';
                       if ( isset($item->imobiliaria_whatsapp) && ! empty($item->imobiliaria_whatsapp) ):
                            $col = 4;
                             ?>
                           <li class="dropup">
                                <div class="btn-group btn-vertical">
                                    <button type="button" class="btn btn-whats hidden-xs whats-desktop btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
                                    <button type="button" class="btn btn-whats whats-mobile btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-whatsapp">WhatsApp</i> 
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a target="_blank" class="btn ver-telefone-whats-lista"  href="https://api.whatsapp.com/send?phone=+55<?php echo $item->imobiliaria_whatsapp.'&text='. urlencode('Gostaria de saber mais sobre o imóvel: '.$url);?>" data-item="<?php echo $item->id;?>" data-log="<?php echo $origem;?>">Conversar com a imobiliária</a></li>
                                        <li><a target="_blank" class="btn compartilha-telefone-whats-lista"  href="https://api.whatsapp.com/send?<?php echo '&text='. urlencode('Conheça esse imóvel: '.$url);?>" data-item="<?php echo $item->id;?>" data-log="<?php echo $origem;?>">Enviar para um amigo</a></li>
                                    </ul>
                                </div>
                            </li>
                        <?php
                        endif;
                        ?> 
                </ul>
                
            </div>
        </div>
        
            <?php
            if ( $item->mostramapa && ! empty($item->logradouro) ) :
            ?>
            <div class="row " id="mapa">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <h4 class="titulo-ficha c-font-bold c-font-uppercase">Localização do Imóvel</h4>
                    <div class="c-line-center c-theme-bg"></div>
                    <p><?php echo $item->logradouro. ' - '. $item->bairro. ' - '. $item->cidade?></p>
                    <p><?php echo $item->estado. ' - '. $item->cep?></p>
                    <br>
            <?php
            if ( $item->mostramapa && isset($item->mapa) && ! empty($item->mapa) ) :
            ?>
                    <div class="mapa" aria-hidden="true" data-ativo="0">
                        <?php // echo $mapa;?>;
                    </div> 
                        <!---->
            <?php
            endif;
            ?>
                </div>
            </div>
            <?php
            endif;
            ?>
        
        <div class="c-content-box c-size-md c-overflow-hide c-bs-grid-small-space espaco-imoveis-relacionados"></div>
        
          <div class="c-content-box c-size-md c-overflow-hide c-bs-grid-small-space espaco-imoveis-relacionados-imobiliaria"></div>
        <?php if ( ! $mobile ): ?>
                    <div class="nao-encontrei">
                        <a target="blank" href="<?php echo base_url();?>nao_encontrei">
                            <img class="c-margin-t-20 img-nao-encontrei" src="<?php echo base_url();?>images/banner/nao-encontrei.jpg" width="100%" height="">
                        </a>
                    </div>        
        <?php              endif;?>
      
    </div>
</div>
<nav class="quick-nav">
    <a class="quick-nav-trigger" href="#0">
        <span aria-hidden="true"></span>
    </a>
    <ul>
        <li class="hidden-xs">
            <a href="#form-cel" class="contato-link">
                <span>Entre em contato</span>
                <i class="icon-basket"></i>
            </a>
        </li>
        <li class="hidden-lg">
            <a href="#" class="contato-link" data-toggle="modal" data-target="#modal-formulario-xs">
                <span>Entre em contato</span>
                <i class="icon-basket"></i>
            </a>
        </li>
         <?php
        if ( $item->mostramapa && isset($item->mapa) && ! empty($item->mapa) ) :
        ?>
        <li>
            <a href="#mapa" >
                <span>Mapa</span>
                <i class="icon-users"></i>
            </a>
        </li>
        <?php 
        endif;
        ?>
        <li>
            <a href="<?php echo $link_print;?>"  target="_blank">
                <span>Imprimir</span>
                <i class="icon-print"></i>
            </a>
        </li>
        <li>
            <a href="#images" >
                <span>Imagens</span>
                <i class="icon-user"></i>
            </a>
        </li>
        <li>
            <a href="#relacionados">
                <span>Mais imóveis</span>
                <i class="icon-graph"></i>
            </a>
        </li>
    </ul>
    <span aria-hidden="true" class="quick-nav-bg"></span>
</nav>
<div class="quick-nav-overlay"></div>
<div class="hidden-desktop atalho-xs text-center">
    <ul class="list-unstyled list-inline">
        <li class="dropup">
            <a type="button" class="btn btn-whats whats-desktop btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-whatsapp"></i> WhatsApp
            </a>
            <div class="dropdown-menu">
                <a target="_blank" class="btn ver-telefone-whats-lista" href="https://web.whatsapp.com/send?phone=+55<?php echo $item->imobiliaria_whatsapp.'&text='. urlencode('Gostaria de saber mais sobre o imóvel: '.$url);?>" data-item="<?php echo $item->id;?>" data-log="<?php echo $origem;?>">Conversar com a imobiliária</a><br>
                <a target="_blank" class="btn compartilha-telefone-whats-lista"  href="https://web.whatsapp.com/send?<?php echo '&text='. urlencode('Conheça esse imóvel: '.$url);?>" data-item="<?php echo $item->id;?>" data-log="<?php echo $origem;?>">Enviar para um amigo</a>
            </div>
            <a type="button" class="btn btn-whats whats-mobile btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-whatsapp"></i> WhatsApp
            </a>
            <div class="dropdown-menu">
                <a target="_blank" class="btn ver-telefone-whats-lista" href="https://api.whatsapp.com/send?phone=+55<?php echo $item->imobiliaria_whatsapp.'&text='. urlencode('Gostaria de saber mais sobre o imóvel: '.$url);?>" data-item="<?php echo $item->id;?>" data-log="<?php echo $origem;?>">Conversar com a imobiliária</a><br>
                <a target="_blank" class="btn compartilha-telefone-whats-lista"  href="https://api.whatsapp.com/send?<?php echo '&text='. urlencode('Conheça esse imóvel: '.$url);?>" data-item="<?php echo $item->id;?>" data-log="<?php echo $origem;?>">Enviar para um amigo</a>
            </div>
        </li>
        <li>
            <button type="button" class="btn btn-detalhes btn-azul-pow-reverso " title="Entre em contato" data-toggle="modal" data-target=".reserva-modal"> 
                Contato
            </button>
        </li>
    </ul>
</div>
