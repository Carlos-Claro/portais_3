<?php
$file_background = getcwd().'/images/capa/'.$cidade_set->link.'.jpg';
if ( ! file_exists($file_background) )
{
//var_dump($file_background);die();
    $file_background = base_url().'images/capa/curitiba_pr.jpg?t='.time();
}
else
{
    $file_background = base_url().'images/capa/'.$cidade_set->link.'.jpg?t='.time();
}
?>
<style type="text/css">
    .inicial {
        background-image: url("<?php echo $file_background;?>");
        background-repeat: no-repeat;
        background-position: top center;
        background-size: 100% auto;
    }
    .publicidade-inicial h2 {
        font-size: 14px;
    }
</style>
<div class="container-fluid <?php echo isset($itens) ? 'interna' : 'inicial';?>">
        <div class="container">
            <button type="button" class="btn btn-topo btn-lg busca-xs <?php echo isset($itens) ? 'show' : ' hide';?>"><span class="glyphicon glyphicon-filter"></span> Filtrar Buscas</button>
            
            <?php 
            //var_dump($itens['valores']);
            ?>
            <div class="filtro">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="inside-filtro">
                            <?php
                            if ( isset($empresa) && $empresa ) :
                                ?>
                            <input type="hidden" class="id_empresa" id="id_empresa" name="id_empresa" value="<?php echo $empresa->id;?>" data-item="<?php echo 'imobiliaria/'.urlencode( tira_especiais($empresa->nome_fantasia) ).'-'.$empresa->id;?>"> 
                                <?php
                            endif;
                                
                            ?>
                            <div class="row busca-inteira">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="row">
                                        <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                                            <div class="dropdown tipo-negocio text-center col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <?php
                                                $valor_negocio = ( isset($itens['valores']['tipo_negocio']) && ! empty($itens['valores']['tipo_negocio']) ) ? $itens['valores']['tipo_negocio'] : '';
                                                ?>
                                                <button class="btn btn-filtro dropdown-toggle" type="button" id="tipo_negocio" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" data-valor="<?php echo $valor_negocio;?>">
                                                    <span class="valor"><?php echo ! empty( $valor_negocio) ? $tipo_negocio_array[$valor_negocio] : 'Selecione o negócio';?></span>
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                                    <li class="text-left tipo-negocio-item" data-item="venda">
                                                        Comprar
                                                    </li>
                                                    <li class="text-left tipo-negocio-item" data-item="locacao">
                                                        Alugar
                                                    </li>
                                                    <li class="text-left tipo-negocio-item" data-item="locacao_dia">
                                                        Alugar por dia
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                                            <?php 
                                            $marcado_default = ( isset($itens['valores']['tipo']) && ! empty($itens['valores']['tipo']) ) ? ( is_array($itens['valores']['tipo']) ? $itens['valores']['tipo'] : array($itens['valores']['tipo'])) : array();
                                            $tipos_json = file_get_contents(getcwd().'/application/views/json/tipo');
                                            $tipos = json_decode($tipos_json);
                                            $menu_tipo = '<ul class="dropdown-menu " aria-labelledby="dropdownMenu1">';
                                            foreach ( $tipos as $tipo )
                                            {
                                                $menu_tipo .= '<li class="tipo-item '.( (in_array($tipo->id, $marcado_default)) ? 'marcado' : '' ).' elemento-'.$tipo->id.'" data-item="'.$tipo->id.'" data-titulo="'.$tipo->descricao.'">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <span class="titulo-tipo">'.$tipo->descricao.'</span>
                                                                </label>
                                                            </div>
                                                        </li>';
                                                if ( (in_array($tipo->id, $marcado_default) ) )
                                                {
                                                    if ( ! isset($tipo_titulo) )
                                                    {
                                                        $tipo_titulo = $tipo->descricao;
                                                    }
                                                    $tipo_id_array[] = $tipo->id;
                                                }
                                            }
                                            if ( isset($tipo_id_array) ) :
                                                $tipo_id = count($tipo_id_array) > 0 ? implode(',', $tipo_id_array) : $tipo_id_array;
                                                $tipo_titulo = $tipo_titulo.' '.(count($tipo_id_array) > 1 ? '+ '.(count($tipo_id_array) -1 ) : '');
                                            else:
                                                $tipo_id = '';
                                                $tipo_titulo = 'Selecione o Tipo';
                                                
                                            endif;
                                            $menu_tipo .= '</ul>';
                                            ?>
                                            <div class="dropdown tipo text-center col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <button class="btn btn-filtro dropdown-toggle" type="button" id="tipo" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" data-valor="<?php echo $tipo_id;?>">
                                                    <span class="valor"><?php echo $tipo_titulo;?></span>
                                                    <span class="caret"></span>
                                                </button>
                                                <?php echo $menu_tipo;?>

                                            </div>
                                        </div>
                                        <div class="col-lg-8 col-md-8 col-sm-8 hidden-sm col-xs-12 localizacao" data-cidade="<?php echo $bairro['cidade_marcado'];?>" data-bairro="<?php echo $bairro['id'];?>">
                                            <div class="form-group">
                                                <div class="input-group select2-bootstrap-prepend ">
                                                    <span class="input-group-btn">
                                                        <button class="btn blue-hoki btn-outline sbold open-select2" type="button" title="Busque a localidade, bairro, nome de rua que busca seu imóvel." data-select2-open="localizacao_campo_id">
                                                            <span class="hidden-xs">Selecione cidade e bairro.</span>
                                                            <span class="glyphicon glyphicon-map-marker visible-xs"></span>
                                                        </button>
                                                    </span>
                                                    <select id="localizacao_campo_id" class="localizacao_campo select2 select2-hidden-accessible" style="width:100%" multiple="multiple">
                                                        <option value="<?php echo $cidade_set->link;?>" selected="selected"><?php echo $cidade_set->nome.' - '.$cidade_set->uf;?></option>
                                                        <?php echo $bairro['item'];?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
<!--                                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12 pull-right espaco-btn-buscar">
                                            <a href="<?php // echo base_url();?>" class="limpar pull-left">Limpar Busca</a> 
                                            <button class="btn btn-buscar btn-success" type="button">BUSCAR</button>
                                        </div>-->
                                    </div>
                                    <div class="row ">
                                        <div class="avancado show">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <div class="form-group">
                                                    <label for="" class="text-center"> <img src="<?php echo base_url();?>images/icones/valor.png"> Valor</label>
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <input type="text" class="valor_min min-max valor form-control" id="valor_min" placeholder="mínimo" value="<?php echo isset($itens['valores']['valor_min']) ? $itens['valores']['valor_min'] : '';?>">
                                                            <input type="text" class="valor_max min-max valor form-control" id="valor_max" placeholder="máximo" value="<?php echo isset($itens['valores']['valor_max']) ? $itens['valores']['valor_max'] : '';?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <div class="form-group">
                                                    <label for=""> <img src="<?php echo base_url();?>images/icones/area_util.png">  Área</label>
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <input type="text" class="form-control area min-max" id="area_min" placeholder="mínimo" value="<?php echo isset($itens['valores']['area_min']) ? $itens['valores']['area_min'] : '';?>">
                                                            <input type="text" class="form-control area min-max" id="area_max" placeholder="máximo" value="<?php echo isset($itens['valores']['area_max']) ? $itens['valores']['area_max'] : '';?>">
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" class="residencial" <?php echo isset($itens['valores']['residencial']) && $itens['valores']['residencial'] === 1 ? 'checked="checked"' : '';?>> Residencial
                                                        </label>
                                                    </div>
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" class="comercial" <?php echo isset($itens['valores']['comercial']) && $itens['valores']['comercial'] === 1 ? 'checked="checked"' : '';?>> Comercial
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                    <div class="checkbox aceito">
                                                        <label>
                                                            <input type="checkbox" class="aceita_troca" <?php echo isset($itens['valores']['aceita_troca']) && $itens['valores']['aceita_troca'] === 1 ? 'checked="checked"' : '';?>> Aceita Troca
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" class="condominio" <?php echo isset($itens['valores']['condominio']) && $itens['valores']['condominio'] === 1 ? 'checked="checked"' : '';?>> Em Condomínio
                                                        </label>
                                                    </div>
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" class="mobiliado" <?php echo isset($itens['valores']['mobiliado']) && $itens['valores']['mobiliado'] === 1 ? 'checked="checked"' : '';?>> Mobiliado
                                                        </label>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="avancado show">
                                            <div class="col-lg-3 col-sm-3 col-md-3 col-xs-12" id="quartos">
                                                <label><img src="<?php echo base_url();?>images/icones/quartos.png"> Quartos</label>
                                                <div class="qtde-ladoalado">
                                                    
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" class="quartos" value="1" <?php echo isset($itens['valores']['quartos']) && in_array(1, $itens['valores']['quartos']) ? 'checked="checked"' : '';?>> 1
                                                        </label>
                                                    </div>
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" class="quartos" value="2" <?php echo isset($itens['valores']['quartos']) && in_array(2, $itens['valores']['quartos']) ? 'checked="checked"' : '';?>> 2
                                                        </label>
                                                    </div>
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox"  class="quartos" value="3" <?php echo isset($itens['valores']['quartos']) && in_array(3, $itens['valores']['quartos']) ? 'checked="checked"' : '';?>> 3
                                                        </label>
                                                    </div>
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox"  class="quartos" value="4" <?php echo isset($itens['valores']['quartos']) && in_array(4, $itens['valores']['quartos']) ? 'checked="checked"' : '';?>> 4 ou mais
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-sm-3 col-md-3 col-xs-12" id="banheiros">
                                                <label><img src="<?php echo base_url();?>images/icones/wc.png"> Banheiros</label>
                                                <div class="qtde-ladoalado">
                                                    
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox"  class="banheiros" value="1" <?php echo isset($itens['valores']['banheiros']) && in_array(1, $itens['valores']['banheiros']) ? 'checked="checked"' : '';?>> 1
                                                        </label>
                                                    </div>
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox"  class="banheiros" value="2" <?php echo isset($itens['valores']['banheiros']) && in_array(2, $itens['valores']['banheiros']) ? 'checked="checked"' : '';?>> 2
                                                        </label>
                                                    </div>
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" class="banheiros" value="3" <?php echo isset($itens['valores']['banheiros']) && in_array(3, $itens['valores']['banheiros']) ? 'checked="checked"' : '';?>> 3
                                                        </label>
                                                    </div>
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" class="banheiros" value="4" <?php echo isset($itens['valores']['banheiros']) && in_array(4, $itens['valores']['banheiros']) ? 'checked="checked"' : '';?>> 4 ou mais
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-sm-3 col-md-3 col-xs-12" id="vagas">
                                                <label><img src="<?php echo base_url();?>images/icones/vagas.png"> Vagas</label>
                                                <div class="qtde-ladoalado">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" class="vagas" value="1" <?php echo isset($itens['valores']['vagas']) && in_array(1, $itens['valores']['vagas']) ? 'checked="checked"' : '';?>> 1
                                                        </label>
                                                    </div>
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" class="vagas" value="2" <?php echo isset($itens['valores']['vagas']) && in_array(2, $itens['valores']['vagas']) ? 'checked="checked"' : '';?>> 2
                                                        </label>
                                                    </div>
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" class="vagas" value="3" <?php echo isset($itens['valores']['vagas']) && in_array(3, $itens['valores']['vagas']) ? 'checked="checked"' : '';?>> 3
                                                        </label>
                                                    </div>
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" class="vagas" value="4" <?php echo isset($itens['valores']['vagas']) && in_array(4, $itens['valores']['vagas']) ? 'checked="checked"' : '';?>> 4 ou mais
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-sm-3 col-md-3 col-xs-12 espaco-btn-buscar-avancado">
                                                <a href="<?php echo base_url();?>" class="btn btn-default limpar pull-left">Limpar Busca</a> 
                                                <button class="btn btn-buscar btn-success" type="button">BUSCAR</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row por-codigo hide">
                                <div class="col-gl-3 col-sm-3 col-md-3 col-xs-12">
                                    <label>Digite o código do Imóvel: </label>
                                </div>
                                <div class="col-gl-7 col-sm-7 col-md-7 col-xs-12">
                                    <input type="text" class="codigo form-control" name="codigo" placeholder="Digite o código do imóvel.">
                                </div>
                                 <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 pull-right espaco-btn-buscar">
                                    <center>
                                        <button class="btn btn-buscar btn-success buscar-por-codigo" type="button">BUSCAR</button>
                                    </center>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-8 col-md-6 col-sm-8 col-xs-12 text-left ">
                        <ol class="breadcrumb <?php echo isset($itens) ? 'show' : 'hide';?>">
                            <?php echo isset($itens['breadcrumb']) ? $itens['breadcrumb'] : '';?>
                            
                        </ol>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-5 col-xs-12 text-right links">
                        <button href="#" class="btn btn-default busca busca-codigo">Busca por código</button>
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <!--<button href="#" class="btn btn-default busca busca-avancada">Busca avançada</button>-->
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </div>
                </div>
            </div>
            <?php 
            if ( ! isset($itens) ) :
            ?>
            <div class="publicidade-inicial" data-item="200">
                <h2>Entre e encontre seu imóvel!</h2>
                <h3></h3>
            </div>
            <?php
            endif;
            ?>
        </div>
    </div>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 publicidade-home">
            <div class="publicidade-atopo">
            </div>
            
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 ">
            <span class="espaco-titulo-h1"><?php echo isset($itens['h1']) ? '<p>' . $itens['total'] . ' - </p><h1>' . $itens['h1'] . '</h1>' : '';?></span>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 ">
            <span class="ordenacao"><?php echo isset($itens['ordem']) ? $itens['ordem'] : '';?></span>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pull-right">
            <ul class="list-group resultado">
                <?php echo isset($itens['itens']) ? $itens['itens'] : ''; ?>
            </ul>
            
            <button type="button" class="btn btn-proximo <?php echo ( isset($itens['link_proximo']['url']) && $itens['total'] > 20 ) ? '' : 'hide';?> col-lg-12 col-md-12 col-sm-12 col-xs-12" data-url="<?php echo isset($itens['link_proximo']['url']) ? $itens['link_proximo']['url'] : '';?>"> <span class="glyphicon glyphicon-repeat"></span> &nbsp;&nbsp;Carregar mais <span class="qtde-atual"><?php echo isset($itens['link_proximo']['atual']) ? $itens['link_proximo']['atual'] : '';?></span> de <span class="qtde-total"><?php echo isset($itens['total']) ? $itens['total'] : '';?> imóveis.</button>
        </div>
        <?php /*<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 pull-left espaco-link-relacionados">
            <ul class="list-group filtros-selecao">
                <?php echo isset($itens['filtro_selecao']) ? $itens['filtro_selecao'] : ''; ?>
            </ul>
            <ul class="list-group links-proximos">
                <?php echo isset($itens['links']) ? $itens['links'] : ''; ?>
            </ul>
            */?>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 banner-nao-encontrei <?php echo ( isset($itens['link_proximo']['url']) ) ? '' : 'hide';?>">
            <center><a href="<?php echo base_url();?>encontre" class="link-banner-nao-encontrei"><img src="<?php echo base_url();?>images/banner/nao-encontrei.jpg" class="img-responsive"></a></center>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 google-tags">
        </div>
        
    </div>
</div>
        

            <button class="btn btn-success topo
            <?php 
            if ( ! isset($itens) ) :
            ?>
            hide
            <?php
            endif;
            ?>
                    " ><span class="glyphicon glyphicon-chevron-up"></span></button>