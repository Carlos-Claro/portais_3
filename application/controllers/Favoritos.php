<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 
 * @access public
 * @version 3.0
 * @author Carlos Claro
 * @package Favoritos
 * @since 14/08/2018
 * 
 * @copyright (c) 2018 POW Internet, Carlos Claro
 */
class Favoritos extends MY_Controller {
    
    
    
    /**
     * construtor da função extendida de MyController
     * Identifica o portal, e set as informações necessárias para o View
     * 
     * @version 1.0
     */
    public function __construct()
    {
        parent::__construct(FALSE);
        $this->benchmark->mark('loadInicialFavorito_start');
        $this->load->library(['Mongo_db','my_mongo']);
        $this->load->library(['lista_normal','encryption']);
        $this->load->helper('cookie');
        $this->set_cidade();
        $this->benchmark->mark('loadInicialFavorito_end');
        $this->print_time('loadInicialFavorito');
    }

    public function index()
    {
        $favoritos = get_cookie('imoveis_favoritos');
        echo json_encode($favoritos);
    }
    
    public function lista_normal()
    {
        $favoritos = get_cookie('imoveis_favoritos');
        $retorno = '';
        if ( isset($favoritos) && count($favoritos) > 0 )
        {
            $this->load->model(['imoveis_mongo_model']);
            $filtro[] = ['tipo' => 'where_in','campo' => '_id','valor' => array_keys($favoritos)];
            $imoveis = $this->imoveis_mongo_model->get_itens($filtro, 'ordem', -1, 0, 100);
            foreach ( $imoveis['itens'] as $imovel ) 
            {
                $retorno .= $this->lista_normal->_set_item_grid($imovel, FALSE, TRUE);
            }
            
        }
        echo $retorno;
    }
    
    public $compara_campos = [
        '_id' => ['titulo' => 'Id', 'medida' => ''],
	'tipo' => ['titulo' => 'Imóvel para ', 'medida' => ''],
        'imoveis_tipos_titulo' => ['titulo' => 'Tipo Imóvel', 'medida' => ''],
        'nome'=> ['titulo' => 'Titulo', 'medida' => ''],
        'preco'=> ['titulo' => 'Valor', 'medida' => ''],
        'logradouro'=> ['titulo' => 'Endereço', 'medida' => ''],
        'bairro'=> ['titulo' => 'Bairro', 'medida' => ''],
	'cidade_nome'=> ['titulo' => 'Cidade', 'medida' => ''],
        'quartos'=> ['titulo' => 'Quartos', 'medida' => ''],
        'garagens'=> ['titulo' => 'Vagas', 'medida' => ''],
        'banheiros'=> ['titulo' => 'Banheiros', 'medida' => ''],
	'suites'=> ['titulo' => 'Suites', 'medida' => ''],
        'area'=> ['titulo' => 'Área Total', 'medida' => 'm'],
        'area_terreno'=> ['titulo' => 'Área Terreno', 'medida' => 'm'],
        'area_util'=> ['titulo' => 'Área Útil', 'medida' => 'm'],
	'descricao'=> ['titulo' => 'Descricao', 'medida' => '', 'tratamento' => 'str_replace( array("<br>","<br />","<br/>", "</br>"), "-", tira_html(<#descricao#>))'],
	'uso'=> ['titulo' => 'Uso', 'medida' => ''],
	'mobiliado'=> ['titulo' => 'Mobiliado', 'medida' => '', 'tratamento' => 'get_x(<#mobiliado#>)'],
	'cobertura'=> ['titulo' => 'Cobertura', 'medida' => '', 'tratamento' => 'get_x(<#cobertura#>)'],
	'condominio'=> ['titulo' => 'Condominio', 'medida' => '', 'tratamento' => 'get_x(<#condominio#>)'],
	'condominio_valor'=> ['titulo' => 'Valor Condominio', 'medida' => '', 'tratamento' => 'get_valor(<#condominio_valor#>)'],
	'nome_empresa'=> ['titulo' => 'Imobiliária', 'medida' => '', 'tratamento' => 'set_link_empresa()'],
];
    
    public function lista_compara()
    {
        $retorno = 'Nenhuma comparação selecionada no momento, tente favoritar seus imoveis preferidos.';
        $favoritos = get_cookie('imoveis_favoritos');
        if ( isset($favoritos) && count($favoritos) > 0 )
        {
            $retorno = '';
            $this->set_imoveis($favoritos);
            if ( isset($this->imoveis) && $this->qtde > 0 )
            {
                $this->set_images_compara();
                foreach( $this->compara_campos as $chave => $campo )
                {
                    $this->set_linha($chave);
                }
                $retorno .= '<div class="portlet light bordered"><div class="portlet-title">';
                $retorno .= '<div class="caption font-red"><i class="icon-settings font-red"></i><span class="caption-subject bold uppercase"></span></div><div class="tools"> </div>';
                $retorno .= '</div><div class="portlet-body table-both-scroll"><table class="table table-striped table-bordered table-hover " summary="Comparação de imóveis" id="sample_1">';
                $retorno .= $this->get_images();
                $retorno .= $this->get_linhas();
                $retorno .= '</table></div></div>';
                
            }
        }
        
        $data['tabela'] = $retorno;
        $layout = $this->layout
                ->set_includes_defaults()
                ->set_include('js/filtro.js', TRUE)	
                ->set_time($this->soma_time())
                ->set_mobile($this->is_mobile)
                ->set_menu($this->menu)
                ->set_logo( $this->logo_principal )
                ->set_titulo('Veja seus favoritos. '.substr($this->cidade->portal, 11))
                ->set_description('Confira sua lista de imóveis favoritos. '.substr($this->cidade->portal, 11))
                ->set_header_extra($this->cidade->header_tag)
                ->set_sobre($this->cidade->sobre, $this->cidade->nome, $this->cidade->portal, $this->cidade->gentilico, $this->cidade->link, $this->cidade->uf, $this->cidade->link_prefeitura,1 )
                ->set_analytics($this->cidade->instrucoes_head)
                ->set_include('plugins/datatables/datatables.min.css', TRUE)
                ->set_include('plugins/datatables/plugins/bootstrap/datatables.bootstrap.css', TRUE)
                ->set_include('js/datatable.min.js', TRUE)
                ->set_include('plugins/datatables/datatables.min.js', TRUE)
                ->set_include('plugins/datatables/plugins/bootstrap/datatables.bootstrap.js', TRUE)
                ->set_include('js/table-datatables-scroller.min.js', TRUE)
                ->view('favoritos_compara', $data, 'layout/layout_3'); 
        
        
        echo $layout;
    }
    
    public function get_images()
    {
        return $this->images;
    }
    public function get_linhas()
    {
        return $this->linha;
    }
    
    public function set_images_compara()
    {
        $images = '';
        $images .= '<tr>';
        $images .= '<th class="c-compare-info c-bg-white"><p>Comparando '.$this->qtde.' Imóveis nesta lista.</p></th>';
        for ( $a = 0; $a < $this->qtde; $a++  )
        {
            $i = $this->lista_normal->get_images($this->imoveis[$a]);
            $images .= '<th class="c-compare-item">
                                '.$i['galeria'].'
                                
                              </th>';
            
        }
        $images .= '</tr>';
        $this->images = $images;
        return $this;
    }
    
    public $linha;
    public function set_linha($campo)
    {
        $linha = '';
//        $linha .= '<tr><th colspan="3"><h2>Product Details</h2></th></tr>';
        $linha .= '<tr><td class="c-compare-info">'.$this->compara_campos[$campo]['titulo'].'</td>';
        for ( $a = 0; $a < $this->qtde; $a++  )
        {
            $linha .= '<td class="c-compare-item">'.$this->imoveis[$a]->{$campo}.'</td>';
        }
        $linha .= '</tr>';
        $this->linha .= $linha;
        return $this;
    }
    
    public function set_imoveis($ids = NULL)
    {
        $this->load->model(['imoveis_mongo_model']);
        $filtro[] = ['tipo' => 'where_in','campo' => '_id','valor' => array_keys($ids)];
        $imoveis = $this->imoveis_mongo_model->get_itens($filtro);
        $this->qtde = $imoveis['qtde'];
        $this->imoveis = [];
        foreach( $imoveis['itens'] as $imovel)
        {
            $this->imoveis[] = $imovel;
        }
        return $this;
    }
    
    public function favoritar ( $id_imovel = NULL, $log = FALSE )
    {
        $retorno = array('status' => FALSE, 'mensagem' => 'Não foi possivel inserir este imóvel nos seus favoritos, habilite cookies para este site, em seu navegador.', 'acao' => 'inserir' );
        if ( isset($id_imovel) )
        {
            $this->set_log_mongo($id_imovel, $this->set_local($log), 'favorito');
            if ( ! ( isset($_COOKIE['imoveis_favoritos']) && array_key_exists($id_imovel, $_COOKIE['imoveis_favoritos']) ) )
            {
                $array = array(
                           'name'   => 'imoveis_favoritos['.$id_imovel.']',
                           'value'  => $id_imovel,
                           'expire' => 432000,
                           'path'   => '/'
                            );
                set_cookie($array);
                $retorno['status'] = TRUE;
                $retorno['acao'] = 'inserir';
                $retorno['mensagem'] = 'Inserido com sucesso, verifique aa lista... ';
            }
            else
            {
                $this->deleta_favorito($id_imovel);
                $retorno['status'] = TRUE;
                $retorno['acao'] = 'deletar';
                $retorno['mensagem'] = 'Deletado dos seus favoritos com sucesso';
            }
        }
        echo json_encode($retorno);
    }
    
    
}