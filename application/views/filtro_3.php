<style>
    .infos {
    padding: 0 20px!important;
}
@media (max-width: 768px){
.infos {
    padding: 0 0!important;
}
}
</style>
<div class="container-fluid main" data-uri="<?php echo $url;?>">
    <!--<div class="c-layout-sidebar-menu c-theme ">-->
    <div class="c-layout-sidebar-menu c-theme ">
        <div class="data-valores hide"><?php echo json_encode($data_valores);?></div>
        <div class="c-content-tab-1 c-theme ">
            <div class="visible-phone c-margin-t-10"></div>
            <ul class="nav nav-tabs c-font-uppercase c-font-bold">
                <li class=" col-lg-4 col-md-4 col-sm-4 col-xs-4 tipo-busca tipo-negocio <?php echo ((isset($valores['tipo_negocio']) && $valores['tipo_negocio'] == 'venda') || ! isset($valores['tipo_negocio']) ? 'active"' : '');?>" data-tipo="venda"><a href="#" data-toggle="tab">Comprar</a></li>
                <li class=" col-lg-4 col-md-4 col-sm-4 col-xs-4 tipo-busca tipo-negocio <?php echo (isset($valores['tipo_negocio']) && $valores['tipo_negocio'] == 'locacao' ? 'active"' : '');?>" data-tipo="locacao"><a href="#"  data-toggle="tab">Alugar</a></li>
                <li class=" col-lg-4 col-md-4 col-sm-4 col-xs-4 tipo-busca tipo-negocio <?php echo (isset($valores['tipo_negocio']) && $valores['tipo_negocio'] == 'locacao_dia' ? 'active"' : '');?>" data-tipo="locacao_dia"><a href="#"  data-toggle="tab">Diária</a></li>
            </ul>
        </div>
        <div class="clearfix"></div>
        <ul class="c-shop-filter-search-1 list-unstyled c-margin-t-15">
            <?php 
            if ( isset($empresa) ) :
                ?>
            <li class="empresa">
                <label class="control-label c-font-uppercase c-font-bold">Empresa</label>
                <ul class="c-card-menu-items itens list-unstyled" >
                    <li class="id_empresa c-margin-t-20 <?php echo $empresa->id;?>" data-item="<?php echo $empresa->id;?>">
                        <div class="btn c-btn-blue-2 c-btn-uppercase c-btn-bold c-btn-border-2x btn-block">
                            <span class="c-theme-link pull-left"> x </span>
                                <?php echo $empresa->descricao;?>
                            <center>
                                <img src="<?php echo $empresa->logo;?>" class="logo-imobi-imoveis">
                            </center>
                        </div>
                    </li>
                </ul>
            </li>
                <?php
            endif;
            ?>
            <li class="localidades">
                <label class="control-label c-font-uppercase c-font-bold">Localidade</label>
                <select class="form-control c-square c-theme localidade-select" name="localidade">
                    <option value="">Busque a localidade</option>';
                    <?php 
                        foreach ( $bairros as $bairro ) :
                            echo '<option value="'.$bairro->id.'">'.$bairro->descricao.'</option>';
                        endforeach;
                    ?>
                </select>
                <ul class="c-card-menu-items itens list-unstyled" >
                </ul>
            </li>
            <li class="tipos">
                <label class="c-margin-t-20 control-label c-font-uppercase c-font-bold">Tipo do imóvel</label>
                <select class="form-control c-square c-theme c-tipo">
                    <option value="">Selecione o tipo</option>
                    <?php foreach( $tipos as $tipo ) :
                        echo '<option value="'.$tipo->id.'">'.$tipo->descricao.'</option>';
                    endforeach;
                    ?>
                </select>
                <ul class="c-card-menu-items itens list-unstyled" >
                </ul>
            </li>
                <div class="c-checkbox" style="margin-top: 10px;">
                    <input type="checkbox" id="condominio" class="c-check condominio" <?php echo (isset($valores['condominio']) ? 'checked="checked"' : '');?> value="1"> <label for="condominio">
                        <span class="inc"></span> <span class="check"></span> <span class="box"></span>     
                        <label class=" control-label c-font-uppercase c-font-bold chek-perso">Em condomínio</label>
                    </label>
                </div>
<!--                <div class="c-checkbox" style="margin-top: 10px;">
                    <input type="checkbox" id="mobiliado" class="c-check mobiliado" <?php echo (isset($valores['mobiliado']) ? 'checked="checked"' : '');?> value="2"> <label for="mobiliado">
                        <span class="inc"></span> <span class="check"></span> <span class="box"></span>     
                        <label class=" control-label c-font-uppercase c-font-bold chek-perso">Mobiliado</label>
                    </label>
                </div>
                <div class="c-checkbox" style="margin-top: 10px;">
                    <input type="checkbox" id="residencial" class="c-check residencial" <?php echo (isset($valores['residencial']) ? 'checked="checked"' : '');?> value="3"> <label for="residencial">
                        <span class="inc"></span> <span class="check"></span> <span class="box"></span>     
                        <label class=" control-label c-font-uppercase c-font-bold chek-perso">Residencial</label>
                    </label>
                </div>
                <div class="c-checkbox" style="margin-top: 10px;">
                    <input type="checkbox" id="comercial" class="c-check comercial" <?php echo (isset($valores['comercial']) ? 'checked="checked"' : '');?> value="4"> <label for="comercial">
                        <span class="inc"></span> <span class="check"></span> <span class="box"></span>     
                        <label class=" control-label c-font-uppercase c-font-bold chek-perso">Comercial</label>
                    </label>
                </div>-->
            <li>
		<label class="c-margin-t-20 control-label c-font-uppercase c-font-bold">Faixa de preço</label>
		<div class="c-price-range-box input-group">
			<div class="c-price input-group col-md-6 pull-left">
				<span class="input-group-addon c-square c-theme">R$</span>
				<input class="form-control c-square c-theme valor valor-min" placeholder="Mínimo" type="text" value="<?php echo (isset($valores['valor_min']) ? $valores['valor_min'] : '');?>">
			</div>
			<div class="c-price input-group col-md-6 pull-left">
				<span class="input-group-addon c-square c-theme">R$</span>
				<input class="form-control c-square c-theme valor valor-max" placeholder="Máximo" type="text" value="<?php echo (isset($valores['valor_max']) ? $valores['valor_max'] : '');?>">
			</div>
		</div>
            </li>
            <li>
		<label class="c-margin-t-20 control-label c-font-uppercase c-font-bold">Área util</label>
		<div class="c-price-range-box input-group">
			<div class="c-price input-group col-md-6 pull-left">
				<span class="input-group-addon c-square c-theme">m²</span>
				<input class="form-control c-square c-theme area area-min" placeholder="Minimo" type="text" value="<?php echo (isset($valores['area_min']) ? $valores['area_min'] : '');?>">
			</div>
			<div class="c-price input-group col-md-6 pull-left">
				<span class="input-group-addon c-square c-theme">m²</span>
				<input class="form-control c-square c-theme area area-max" placeholder="Maximo" type="text"  value="<?php echo (isset($valores['area_max']) ? $valores['area_max'] : '');?>">
			</div>
		</div>
                <div class="form-group" role="group">
                </div>
            </li>
            <li class="c-margin-b-40">
                <label class="c-margin-t-10 control-label c-font-uppercase c-font-bold">Quartos</label>
                <div class="c-checkbox">
                    <input type="checkbox" id="quartos-1" class="c-check quartos" <?php echo (isset($valores['quartos']) && in_array(1, $valores['quartos']) ? 'checked="checked"' : '');?> value="1"> <label for="quartos-1">
                        <span class="inc"></span> <span class="check"></span> <span class="box"></span>
                            1
                    </label>
                </div>
                <div class="c-checkbox">
                    <input type="checkbox" id="quartos-2" class="c-check quartos" <?php echo (isset($valores['quartos']) && in_array(2, $valores['quartos']) ? 'checked="checked"' : '');?> value="2"> <label for="quartos-2">
                        <span class="inc"></span> <span class="check"></span> <span class="box"></span>
                            2
                    </label>
                </div>
                <div class="c-checkbox">
                    <input type="checkbox" id="quartos-3" class="c-check quartos" <?php echo (isset($valores['quartos']) && in_array(3, $valores['quartos']) ? 'checked="checked"' : '');?> value="3"> <label for="quartos-3">
                        <span class="inc"></span> <span class="check"></span> <span class="box"></span>
                            3
                    </label>
                </div>
                <div class="c-checkbox">
                    <input type="checkbox" id="quartos-4" class="c-check quartos" <?php echo (isset($valores['quartos']) && in_array(4, $valores['quartos']) ? 'checked="checked"' : '');?> value="4"> <label for="quartos-4">
                        <span class="inc"></span> <span class="check"></span> <span class="box"></span>
                            4+
                    </label>
                </div>
                
            </li>
            <li class="c-margin-b-40">
                <label class="c-margin-t-10 control-label c-font-uppercase c-font-bold">Banheiros</label>
                <div class="c-checkbox">
                    <input type="checkbox" id="banheiros-1" class="c-check banheiros" <?php echo (isset($valores['banheiros']) && in_array(1, $valores['banheiros']) ? 'checked="checked"' : '');?> value="1"> <label for="banheiros-1">
                        <span class="inc"></span> <span class="check"></span> <span class="box"></span>
                            1
                    </label>
                </div>
                <div class="c-checkbox">
                    <input type="checkbox" id="banheiros-2" class="c-check banheiros" <?php echo (isset($valores['banheiros']) && in_array(2, $valores['banheiros']) ? 'checked="checked"' : '');?> value="2"> <label for="banheiros-2">
                        <span class="inc"></span> <span class="check"></span> <span class="box"></span>
                            2
                    </label>
                </div>
                <div class="c-checkbox">
                    <input type="checkbox" id="banheiros-3" class="c-check banheiros" <?php echo (isset($valores['banheiros']) && in_array(3, $valores['banheiros']) ? 'checked="checked"' : '');?> value="3"> <label for="banheiros-3">
                        <span class="inc"></span> <span class="check"></span> <span class="box"></span>
                            3
                    </label>
                </div>
                <div class="c-checkbox">
                    <input type="checkbox" id="banheiros-4" class="c-check banheiros" <?php echo (isset($valores['banheiros']) && in_array(4, $valores['banheiros']) ? 'checked="checked"' : '');?> value="4"> <label for="banheiros-4">
                        <span class="inc"></span> <span class="check"></span> <span class="box"></span>
                            4+
                    </label>
                </div>
                
            </li>
            <li class="c-margin-b-40">
                <label class="c-margin-t-10 control-label c-font-uppercase c-font-bold">Vagas</label>
                <div class="c-checkbox">
                    <input type="checkbox" id="vagas-1" class="c-check vagas" <?php echo (isset($valores['vagas']) && in_array(1, $valores['vagas']) ? 'checked="checked"' : '');?> value="1"> <label for="vagas-1">
                        <span class="inc"></span> <span class="check"></span> <span class="box"></span>
                            1
                    </label>
                </div>
                <div class="c-checkbox">
                    <input type="checkbox" id="vagas-2" class="c-check vagas" <?php echo (isset($valores['vagas']) && in_array(2, $valores['vagas']) ? 'checked="checked"' : '');?> value="2"> <label for="vagas-2">
                        <span class="inc"></span> <span class="check"></span> <span class="box"></span>
                            2
                    </label>
                </div>
                <div class="c-checkbox">
                    <input type="checkbox" id="vagas-3" class="c-check vagas" <?php echo (isset($valores['vagas']) && in_array(3, $valores['vagas']) ? 'checked="checked"' : '');?> value="3"> <label for="vagas-3">
                        <span class="inc"></span> <span class="check"></span> <span class="box"></span>
                            3
                    </label>
                </div>
                <div class="c-checkbox">
                    <input type="checkbox" id="vagas-4" class="c-check vagas" <?php echo (isset($valores['vagas']) && in_array(4, $valores['vagas']) ? 'checked="checked"' : '');?> value="4"> <label for="vagas-4">
                        <span class="inc"></span> <span class="check"></span> <span class="box"></span>
                        4+
                    </label>
                </div>
                <label class="c-font-bold c-font-uppercase c-margin-t-10 control-label">Busca por código</label>
                <input type="text" name="id" placeholder="Digite o código do imóvel e aperte enter." value="" class="form-control busca-id" autocomplete="off">

            </li>
<!--            <li class="c-margin-b-40">
                <label class="control-label c-font-uppercase c-font-bold"></label>
                <div class="c-checkbox">
                    <input type="checkbox" id="residencial" class="c-check residencial" <?php //echo (isset($valores['residencial'])  ? 'checked="checked"' : '');?>> <label for="residencial">
                        <span class="inc"></span> <span class="check"></span> <span class="box"></span>
                        Residencial
                    </label>
                </div>
                <div class="c-checkbox">
                    <input type="checkbox" id="comercial" class="c-check comercial" <?php //echo (isset($valores['comercial'])  ? 'checked="checked"' : '');?>> <label for="comercial">
                        <span class="inc"></span> <span class="check"></span> <span class="box"></span>
                        Comercial
                    </label>
                </div>
            </li>-->
            <li class="c-margin-b-40">
                <div class="form-group" role="group">
                    <!--<button type="button" class="buscar btn btn-lg c-theme-btn c-btn-square c-btn-uppercase c-btn-bold"><i class="fa fa-search"></i>Buscar</button>-->
                    <button type="button" class="limpar btn btn-lg btn-default c-btn-square c-btn-uppercase c-btn-bold col-lg-12 col-md-12 col-sm-12 ccol-xs-12">Limpar</button>
                </div>
            </li>
        </ul>
    </div>
    <div class="c-layout-sidebar-content">
        <?php 
            if ( isset($empresa) ) :
                ?>
        <div class="banner-imobiliarias margin-imobiliaria" style="background: url('<?php echo base_url();?>images/banner/banner_imobiliarias.jpeg');">
                        <div class="row topo-imob">
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <div class="fundo-logo-imob">
                                    <img src="<?php echo $empresa->logo;?>" class="logo-imobi-imoveis">
                                </div>
                            </div>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <h1 class="titulo-imobiliaria"><?php echo $empresa->descricao;?></h1>
                                <?php
                                if ( isset($empresa->whatsapp) && ! empty($empresa->whatsapp) ):
                                            ?>
                                            <li class="dropup">
                                                <div class="btn-group btn-vertical">
                                                    <button type="button" class="btn btn-vermelho-imobi dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fa fa-whatsapp">WhatsApp</i> 
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li><a target="_blank" class="btn ver-telefone-whats-lista" href="https://api.whatsapp.com/send?phone=+55<?php echo $empresa->whatsapp.'&text='. urlencode('Gostaria de saber mais sobre o imóvel: '.$url);?>" data-item="<?php echo $empresa->id;?>" data-log="imob">Conversar com a imobiliária</a></li>
                                                    </ul>
                                                </div>
                                            </li>
                                            <?php
                                        endif;
                                        ?>
<!--                                <p class="texto-imobiliaria">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco.</p>
                                <button type="button" class="btn btn-vermelho-imobi">Conheça mais sobre a imobiliária</button>
                                <button type="button" class="btn btn-vermelho-imobi ver-telefone button-left">Ver telefone</button>-->
                            </div>
                        </div>
                    </div>
                <?php
            endif;
        ?>
        <div class="row">
            <div class="col-md-7 col-sm-12 col-xs-12">
                <div class="c-page-title">
                    <h1 class="titulo c-font-20 c-font-bold"><?php echo isset($titulo) ? $titulo : ''; ?></h1>
                    <h2 class="qtde c-font-16 c-font-sbold"><?php echo isset($total) ? '<span class="total">' . $total . '</span> Imóveis encontrados' : ''; ?></h2>
                </div>
            </div>
            <div class="col-md-5 col-sm-12 col-xs-12 padding-menor">
                <div class="c-shop-result-filter-1 clearfix form-inline">
                    <div class="tipo-lista c-filter hidden-xs">
                        <label class="control-label c-font-10">Ordenação:</label>
                        <select class="form-control c-square c-theme c-input ordem">
                            <option value="padrao" selected="selected">Padrão</option>
                            <option value="preco_menor">Preço (Menor &gt; Maior)</option>
                            <option value="preco_maior">Preço (Maior &gt; Menor)</option>
                        </select>
                    </div>
                    <div class="tipo-lista listagem l-left c-filter hidden-xs">
                        <label class="control-label c-font-10">Listagem:</label>
                        <button type="button" class="btn btn-menor btn-azul-pow-reverso c-font-uppercase c-font-bold c-btn-border-1x lista-tipo <?php echo ( $lista_tipo == 'painel' ? 'active' : ''  );?>" data-tipo="painel">
                            <i class="fa fa-th-large"></i> Painel
                        </button>
                        <button type="button" class="btn btn-menor btn-azul-pow-reverso c-font-uppercase c-font-bold c-btn-border-1x lista-tipo <?php echo ( $lista_tipo == 'lista' ? 'active' : ''  );?>" data-tipo="lista">
                            <i class="fa fa-list"></i> Lista
                        </button>

                    </div>
                </div>
            </div>
        </div>
        <div class="row publicidade ">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="banner publicidade-atopo hide"></div>
            </div>
        </div>
        <div class="c-margin-t-20"></div>
        <ul class="imoveis list-unstyled " data-tipo-lista="<?php echo $lista_tipo;?>">
           <?php echo isset($imoveis) ? $imoveis : '';?>
        </ul>
    </div>
</div>