<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Reformulação de sistema de portais, Limpeza de arquivo e reset.
 * @access public
 * @version 3.0
 * @author Carlos Claro
 * @package Imoveis
 * @since 14/08/2018
 *
 * @copyright (c) 2018 POW Internet, Carlos Claro
 */
class Imoveis extends MY_Controller {



    /**
     * construtor da função extendida de MyController
     * Identifica o portal, e set as informações necessárias para o View
     *
     * @version 1.0
     */
    public function __construct()
    {
        parent::__construct(FALSE);
        $this->benchmark->mark('loadInicial_start');
        $this->load->library(['Mongo_db','my_mongo']);
        $this->load->library(['lista_normal','encryption']);
        $this->load->model(['empresas_mongo_model']);
        $this->load->helper('cookie');
        $this->benchmark->mark('loadInicial_end');
        $this->print_time('loadInicial');
        $this->benchmark->mark('setCidade_start');
        $this->set_cidade();
        $this->set_cidades();
        $this->set_tipos();
        $this->benchmark->mark('setCidade_end');
        $this->print_time('setCidade');
        $this->benchmark->mark('setUri_start');
        $this->set_uri();
        $this->benchmark->mark('setUri_end');
        $this->print_time('setUri');
        $this->benchmark->mark('setRequest_start');
        $this->set_request();
        $this->set_titulo();
        $this->benchmark->mark('setRequest_end');
        $this->print_time('setRequest');
        $this->benchmark->mark('setlogPesquisa_start');
        $this->set_log_pesquisa_mongo();
        $this->benchmark->mark('setlogPesquisa_end');
        $this->print_time('setlogPesquisa');
    }



    /**
     * Set inicial, verifica o location para direcionar montagem.
     * @access public
     * @version 1.0
     * @return redirecionamento
     */
    public function index($variavel = FALSE)
    {
        $this->view = 'imoveis';
        $this->_set_page();
    }

    public function imovel()
    {
        $uri = $this->uri->segment_array();
        var_dump($uri);
    }


    public function imobiliaria($empresa = NULL)
    {
        if ( isset($empresa) )
        {
            $this->view = 'imoveis';
            $this->_set_page();
        }
        else
        {
            $this->index();
        }
    }

    public function set_empresa($id_empresa)
    {
        $this->empresa = $this->empresas_mongo_model->get_item($id_empresa);
        return $this;
    }

    public function get_empresa($campo = NULL)
    {
        return isset($this->empresa) ? ( $campo ? $this->empresa->{$campo} : $this->empresa ) : NULL;
    }

    /**
     *
     */
    private function _set_page()
    {
        $data = [];
        $data['uri'] = isset($this->uri[1]) ? $this->uri[1] : '';
        $data['valores'] = $this->get_request();
        $data['form'] = $this->set_form();
        $data['is_mobile'] = $this->is_mobile;
        var_dump($data);die();
        $layout = $this->layout
                ->set_includes_defaults()
                ->set_include('js/filtro.js', TRUE)
                ->set_time($this->soma_time())
                ->set_mobile($this->is_mobile)
                ->set_robot($this->cidade->link === $this->get_request('cidade'))
                ->set_menu($this->menu)
                ->set_logo( $this->logo_principal )
                ->set_titulo($this->get_titulo())
                ->set_description($this->get_descricao())
                ->set_header_extra($this->cidade->header_tag)
                ->set_sobre($this->cidade->sobre, $this->cidade->nome, $this->cidade->portal, $this->cidade->gentilico, $this->cidade->link, $this->cidade->uf, $this->cidade->link_prefeitura,1 )
                ->set_analytics($this->cidade->instrucoes_head);
//                ->set_google_tag($this->cidade->google_tag);

        $layout
                ->view($this->view, $data, 'layout/layout_3');


    }


    private $post = NULL;



    public function pesquisa( $return = FALSE )
    {
        $this->benchmark->mark('getPesquisa_start');
        $this->load->model('imoveis_mongo_model');
        $this->set_filtro();
        $total = $this->imoveis_mongo_model->get_total_itens($this->get_filtro());
        $data['titulo'] = $this->get_titulo();
        $data['descricao'] = $this->get_descricao();
        $data['total'] = $total;
        $data['url'] = $this->get_url(TRUE);
        $data['lista_tipo'] = $this->get_lista_tipo();
        $this->benchmark->mark('getPesquisa_end');
        $this->print_time('getPesquisa');
        $data['tempo'] = $this->soma_time();
        if ( $return )
        {
            return $data;
        }
        echo json_encode($data);
    }

    public function get_itens( $return = FALSE )
    {
        $this->benchmark->mark('getImoveis_start');
        $this->load->model('imoveis_mongo_model');
        $this->load->library('lista_normal');
        $this->set_filtro();
        $imoveis_destaque = $this->get_destaques();
        $retorno = $this->get_imoveis($imoveis_destaque['imoveis']['tipo'], 'tipo');
        $retorno .= $this->get_imoveis($imoveis_destaque['imoveis']['bairro'], 'bairro');
        $filtro = $this->get_filtro();
        if ( count($imoveis_destaque['negativos']) > 0 )
        {
            $filtro[] = ['tipo' => 'where_not_in','campo' => '_id','valor' => $imoveis_destaque['negativos']];
        }
        $imoveis = $this->imoveis_mongo_model->get_itens($filtro, $this->get_ordem('coluna'), $this->get_ordem('ordem'), $this->offset, $this->qtde_itens);
        $retorno .= $this->get_imoveis($imoveis['itens']);
        $this->benchmark->mark('getImoveis_end');
        $this->print_time('getImoveis');
        $tempo = $this->soma_time();
        if ( $return )
        {
            return $retorno;
        }
//        header("Content-Type: text/html; charset=UTF-8",true);
        echo json_encode(['status' => TRUE, 'data' => $retorno]);
    }

    public function get_destaques()
    {
        $tipo_negocio = isset($this->request['tipo_negocio']) ? $this->request['tipo_negocio'] : 'venda';
        $cidade = isset($this->request['cidade']) ? $this->request['cidade'] : $this->cidade->link;
        $tipos = isset($this->request['tipo']) ? $this->request['tipo'] : FALSE;
        $empresas = [];
        $imoveis_data = ['tipo' => [],'bairro' => []];
        $negativos = [];
        if ( $tipos )
        {
            $t = [];
            $this->set_tipos_destaques();
            foreach ( $tipos as $tipo )
            {
                $data = ['tipo' => $tipo, 'cidade' => $cidade, 'tipo_negocio' => $tipo_negocio];
                $empresas_tipo = $this->get_tipos_destaques($data);
                foreach ( $empresas_tipo as $empresa_tipo )
                {
                    $empresas[$empresa_tipo] = isset($empresas[$empresa_tipo]) ? count($empresas[$empresa_tipo]) + 1 : 1;
                    $filtro_tipo = $this->get_filtro();
                    $filtro_tipo['id_empresa'] = ['tipo' => 'where','campo' => 'id_empresa','valor' => $empresa_tipo];
                    if ( count($negativos) > 0 )
                    {
                        $filtro_tipo['negativos'] = ['tipo' => 'where_not_in','campo' => '_id','valor' => $negativos];
                    }
                    if( isset($_GET['debug_tipo']) ){
                      var_dump($filtro_tipo);
                    }
                    $i = $this->imoveis_mongo_model->get_item_destaque_por_filtro($filtro_tipo, 'destaque_tipo', -1, ($empresas[$empresa_tipo] + ( ($this->offset)/12 )));
                    if( isset($_GET['debug_tipo']) ){
                      var_dump($i);
                    }

                    if ( $i )
                    {
                        $imoveis_data['tipo'][] = $i;
                        $negativos[] = $i->_id;
                    }
                }
            }
        }
        $bairros = isset($this->request['bairro']) ? $this->request['bairro'] : FALSE;
        if ( $bairros )
        {
            $b = [];
            $this->set_bairros_destaques();
            foreach( $bairros as $bairro )
            {
                if ( $tipos )
                {
                    foreach ( $tipos as $tipo )
                    {
                        $data = ['tipo' => $tipo, 'cidade' => $cidade, 'tipo_negocio' => $tipo_negocio, 'bairro' => $bairro];
                        $empresas_bairros = $this->get_bairros_destaques($data);
                        foreach ( $empresas_bairros as $empresa_bairro )
                        {
                            $empresas[$empresa_bairro] = isset($empresas[$empresa_bairro]) ? $empresas[$empresa_bairro] + 1 : 1;
                            $filtro_bairro = $this->get_filtro();
                            $filtro_bairro['id_empresa'] = ['tipo' => 'where','campo' => 'id_empresa','valor' => $empresa_bairro];
                            if ( count($negativos) > 0 )
                            {
                                $filtro_bairro['negativos'] = ['tipo' => 'where_not_in','campo' => '_id','valor' => $negativos];
                            }
                            $i = $this->imoveis_mongo_model->get_item_destaque_por_filtro($filtro_bairro, 'destaque_bairro', -1, ($empresas[$empresa_bairro] + ( ($this->offset)/12 )));
                            if ( $i )
                            {
                                $imoveis_data['bairro'][] = $i;
                                $negativos[] = $i->_id;
                            }
                        }
                    }
                }
            }
        }
        $retorno = ['imoveis' => $imoveis_data,'negativos' => $negativos];
        return $retorno;
    }

    public function get_imoveis($imoveis, $destaque = FALSE)
    {
        $retorno = '<li class="publicidade publicidade_0"></li>';
        $contador = 1;
        $publicidade = 1;
        foreach ( $imoveis as $imovel )
        {
            if ( $this->get_lista_tipo() == 'lista' && ! $this->is_mobile )
            {
                $retorno .= $this->lista_normal->_set_item_vertical($imovel, $destaque);
            }
            else
            {
                $retorno .= $this->lista_normal->_set_item_grid($imovel, $destaque);

            }
            $contador++;
            if ( ! ($contador % 4) )
            {
                $retorno .= '<li class="publicidade publicidade_'.$publicidade.'"></li>';
                $publicidade++;

            }
        }
        if ( ! $this->input->is_ajax_request() && ! $destaque )
        {
            $retorno .= '<li class="ultimo col-lg-12 col-md-12 col-sm-12 col-xs-12"></li>';
        }
        return $retorno;
    }

    private function get_lista_tipo()
    {
        return $this->lista_tipo;
    }

    private $mascara_titulo = ['tipo', 'tipo_negocio', 'localidade', ];

    public function get_titulo()
    {
        return $this->titulo;
    }

    public $titulo;
    public $descricao;

    public function set_titulo()
    {
        $this->titulo = '';
        $this->descricao = '';
        $select = $this->get_select();
        $data_valores = $select['data_valores'];
        if ( isset($select['valores']['id']) && !empty($select['valores']['id']) ){
            $this->titulo .= 'Imoveis por codigo: '.$select['valores']['id'];
            $this->descricao .= 'Busca de Imoveis por codigo: '.$select['valores']['id'];
        }else{

            if ( count($data_valores['tipo_selecionado']) > 0 && !isset($this->sem_tipo) ){
                $a = 0; $tv = '';
                foreach( $data_valores['tipo_selecionado'] as $t ){
                    $tv .= ($a ? ', ' : '').$t->plural;
                    $a++;
                }
                $this->titulo .= $tv;
                $this->descricao .= $tv;
            }else{
                $this->titulo .= 'Imóveis ';
                $this->descricao .= 'Imóveis ';

            }
            if ( count($data_valores['tipo_negocio_selecionado']) > 0 ){
                $this->titulo .= ' '.$data_valores['tipo_negocio_selecionado'][$this->request['tipo_negocio']].' ';
                $this->descricao .= ' '.$data_valores['tipo_negocio_selecionado'][$this->request['tipo_negocio']].' ';
            }else{
                $this->titulo .= $this->tipo_negocio_data['venda'].' ';
                $this->descricao .= $this->tipo_negocio_data['venda'].' ';

            }
            if ( count($data_valores['localidade_selecionado']['bairro']) > 0 ){
                $a = 0; $tv = ' no';
                foreach( $data_valores['localidade_selecionado']['bairro'] as $cb => $b ){
                    $tv .= ($a ? ', ' : ' ');
                    if ( isset($b->descricao) ){
                        $tv .= $b->descricao;
                    }else{
                        var_dump($cb);
                        $bairro_b = $this->get_bairros($cb);
                        var_dump($bairro_b);
                        $tv .= $bairro_b->descricao;

                    }
                    $a++;
                }
                $this->titulo .= $tv;
                $this->descricao .= $tv;
            }
            if ( count($data_valores['localidade_selecionado']['cidade']) > 0 ){
                $this->titulo .= ' em '.$data_valores['localidade_selecionado']['cidade'][$this->request['cidade']]->descricao.' ';
                $this->descricao .= ' em '.$data_valores['localidade_selecionado']['cidade'][$this->request['cidade']]->descricao.' ';
            }
            $empresa = $this->get_empresa();
            if ( isset($empresa) ){
                $this->titulo .= 'de '.$empresa->descricao.' ';
                $this->descricao .= 'de '.$empresa->descricao.' ';

            }
            $titulo_valor = $this->get_titulo_valor($select['valores']);
            $this->titulo .= $titulo_valor;
            $this->descricao .= $titulo_valor;

            $titulo_area = $this->get_titulo_area($select['valores']);
            $this->titulo .= $titulo_area;
            $this->descricao .= $titulo_area;

            $titulo_itens = $this->get_titulo_itens($select['valores']);
            $this->titulo .= $titulo_itens;
            $this->descricao .= $titulo_itens;
        }

        return $this;
    }

    public function get_titulo_valor($valores)
    {
        $retorno = '';
        if ( isset($valores['valor_min']) && isset($valores['valor_max']) )
        {
            $retorno .= ', valores entre R$ '.number_format($valores['valor_min'],0,',','.').' e R$ '.number_format($valores['valor_max'],0,',','.');
        }
        elseif(isset($valores['valor_min']) || isset($valores['valor_max']))
        {
            if ( isset($valores['valor_min']) )
            {
                $retorno .= ', mínimo R$ '.number_format($valores['valor_min'],0,',','.');

            }
            else
            {
                $retorno .= ', até R$ '.number_format($valores['valor_max'],0,',','.');

            }
        }
        return $retorno;
    }

    public function get_titulo_area($valores)
    {
        $retorno = '';
        if ( isset($valores['area_min']) && isset($valores['area_max']) )
        {
            $retorno .= ', medidas entre '.number_format($valores['area_min'],0,',','.').' e '.number_format($valores['area_max'],0,',','.');
        }
        elseif(isset($valores['area_min']) || isset($valores['area_max']))
        {
            if ( isset($valores['area_min']) )
            {
                $retorno .= ', área mínima '.number_format($valores['area_min'],0,',','.');

            }
            else
            {
                $retorno .= ', área máxima '.number_format($valores['area_max'],0,',','.');

            }
        }
        return $retorno;

    }

    public function get_titulo_itens($valores)
    {
        $retorno = '';
        if ( isset($valores['quartos']))
        {
            $retorno .= ', '.implode(',',$valores['quartos']).' quartos';
        }
        if ( isset($valores['banheiros']))
        {
            $retorno .= ', '.implode(',',$valores['banheiros']).' banheiros';
        }
        if ( isset($valores['vagas']))
        {
            $retorno .= ', '.implode(',',$valores['vagas']).' vagas de garagem';
        }
        return $retorno;
    }


    public function get_descricao()
    {
        return $this->descricao;
    }

    private $forms = [
        'imoveis'       => ['tipo_negocio','tipo',['cidade','bairro'],['valor_min','valor_max'],['area_min','area_max'],'residencial','comercial','quartos','banheiros','vagas'],
        'imobiliarias'  => ['id_empresa','tipo_negocio','tipo',['cidade','bairro'],['valor_min','valor_max'],['area_min','area_max'],'residencial','comercial','quartos','banheiros','vagas'],
        'id'            => ['id'],
    ];

    public function get_forms($tipo = NULL)
    {
        if ( isset($tipo) )
        {
            $retorno = $this->forms[$tipo];
        }
        else
        {
            $retorno = $this->forms;
        }
        return $retorno;
    }

    public function set_form( $tipo = 'imoveis' )
    {
        $this->benchmark->mark('getForm_start');
        $data = $this->get_select();
        $data['imoveis'] = $this->get_itens(TRUE);
        $retorno = $layout = $this->layout
                        ->set_time($this->soma_time())
                        ->view('filtro_3', $data, 'layout/sem_head.php', TRUE);
        $this->benchmark->mark('getForm_end');
        $this->print_time('getForm');
        return $retorno;
    }


    public function get_select()
    {
        $this->benchmark->mark('getSelect_start');
        $retorno = $this->pesquisa(TRUE);
        $retorno['valores'] = $this->get_request();
        $retorno['empresa'] = $this->get_empresa();
        $retorno['tipos'] = $this->get_tipos();
        $retorno['tipo_selecionado'] = $this->get_selecionados('tipo');
        $retorno['tipo_negocio_selecionado'] = $this->get_selecionados('tipo_negocio');
        $retorno['cidades'] = $this->get_cidades();
        $this->set_bairros(isset($retorno['valores']['cidade']) ? $retorno['valores']['cidade'] : $this->cidade->link);
        $retorno['bairros'] = $this->get_bairros();
        $retorno['localidade_selecionado'] = array('cidade' => $this->get_selecionados('cidade'), 'bairro' => $this->get_selecionados('bairro') );
        $retorno['forms'] = $this->get_forms(!empty($tipo) ? $tipo : 'imoveis');
        $retorno['url'] = $this->get_url(TRUE);
        $retorno['data_valores'] = array_merge($retorno['valores'], array('tipo_selecionado' => $retorno['tipo_selecionado']), array('localidade_selecionado' => $retorno['localidade_selecionado']), array('tipo_negocio_selecionado' => $retorno['tipo_negocio_selecionado']));
//        var_dump($retorno['data_valores']);
        $this->benchmark->mark('getSelect_end');
        $this->print_time('getSelect');
        return $retorno;

    }

    public function get_selecionados( $campo )
    {
        $valor = $this->get_request($campo);
        $retorno = [];
        if ( isset($valor) )
        {
            if ( ! is_array($valor) )
            {
                $valor = array($valor);
            }
            foreach ( $valor as $c => $v )
            {
                switch($campo)
                {
                    case 'tipo':
                        $retorno[$v] = $this->get_tipos($v);
                        break;
                    case 'bairro':
                        $retorno[$v] = $this->get_bairros($v);
                        break;
                    case 'cidade':
                        $retorno[$v] = $this->get_cidades($v);
                        break;
                    case 'tipo_negocio':
                        $retorno[$v] = $this->tipo_negocio_data[$v];
                        break;
                }
            }
        }
        return $retorno;
    }

    public function set_bairros_destaques()
    {
        $tipos_json = getcwd().'/application/views/json/destaques_bairros.json';
        $this->bairros_destaques = json_decode(file_get_contents($tipos_json));
        return $this;
    }

    /**
     * utiliza o $this->filtro para que seja retornada as empresas com destaques para o filtro
     * [cidade_uf]
     * [tipo_negocio]
     * [tipo]
     * [bairro]
     * @return array empresas com destaques, caso não existe retorna FALSE
     */
    public function get_bairros_destaques($data)
    {
        $retorno = [];
        if ( isset($this->bairros_destaques->{$data['cidade']}->{$data['tipo_negocio']}->{$data['tipo']}->{$data['bairro']}) )
        {
            $retorno = $this->bairros_destaques->{$data['cidade']}->{$data['tipo_negocio']}->{$data['tipo']}->{$data['bairro']};
        }
        return $retorno;

    }


    public function set_tipos_destaques()
    {
        $tipos_json = getcwd().'/application/views/json/destaques_tipos.json';
        if (file_exists($tipos_json) )
        {
            $this->tipos_destaques = json_decode(file_get_contents($tipos_json));
            return $this;
        }
        else
        {
            curl_executavel(str_replace('portais_3/','admin2_0/',base_url()).'funcoes/set_destaques_tipos_portais?own=1');
            curl_executavel(str_replace('portais_3/','admin2_0/',base_url()).'funcoes/set_destaques_bairros_portais?own=1');
            $this->set_tipos_destaques();
        }
        return $this;
    }

    /**
     * utiliza o $this->filtro para que seja retornada as empresas com destaques para o filtro
     * [cidade]
     * [tipo_negocio]
     * [tipo]
     * @return array empresas com destaques, caso não existe retorna FALSE
     */
    public function get_tipos_destaques($data)
    {
        $retorno = [];
        if ( isset($this->tipos_destaques->{$data['cidade']}->{$data['tipo_negocio']}->{$data['tipo']}) )
        {
            $retorno = $this->tipos_destaques->{$data['cidade']}->{$data['tipo_negocio']}->{$data['tipo']};
        }
        return $retorno;
    }

    public function set_tipos()
    {
        $tipos_json = getcwd().'/application/views/json/tipo';
        if (file_exists($tipos_json) )
        {
            $this->tipos = json_decode(file_get_contents($tipos_json));
            return $this;
        }
        else
        {
            curl_executavel(base_url().'funcoes/get_tipos?own=1');
            $this->set_tipos();
        }
    }

    private function get_tipos ($id = FALSE)
    {
        if ($id)
        {
            return isset($this->tipos->{$id}) ? $this->tipos->{$id} : NULL;
        }
        return $this->tipos;
    }

    private function get_set_cidade()
    {
        return $this->set_cidade;
    }

    public function set_bairros($cidade)
    {
        $bairros_json = getcwd().'/application/views/json/bairros/'.$cidade;
        if (file_exists($bairros_json) )
        {
            $this->bairros = json_decode(file_get_contents($bairros_json));
            return $this;
        }
        else
        {
            $this->bairros = [];
            return $this;
//            curl_executavel(base_url().'funcoes/get_bairros_por_cidade?own=1');
            $this->set_bairros($cidade);
        }
    }

    public function get_bairros($id = FALSE)
    {
        if ($id)
        {
            return isset($this->bairros->{$id}) ? $this->bairros->{$id} : NULL;
        }
        return $this->bairros;
    }

    public function get_filtro()
    {
        return $this->filtro;
    }


    public function set_filtro()
    {
        $filtro = array();
        $request = $this->get_request();
        foreach( $request as $c => $v )
        {
            $campo = isset($this->campos[$c]) ? $this->campos[$c] : NULL;
            if ( isset($campo) && ( ( ! empty($v) || is_array($v) && count($v) > 0 ) ) )
            {
                $filtro[$c]['tipo'] = $campo['filtro']['where'];
                $filtro[$c]['campo'] = $campo['filtro']['campo'];
                if ( $campo['filtro']['tipo'] == 'int' )
                {
                    if ( $c == 'quartos' || $c == 'banheiros' || $c == 'vagas' )
                    {
                        if ( count($v) == 1 )
                        {
                            $filtro[$c]['tipo'] = 'where_gte';
                            $filtro[$c]['valor'] = (int)$v[0];
                        }
                        else
                        {
                            $va = [];
                            foreach( $v as $val )
                            {
                                $va[] = (int)$val;
                            }
                            $filtro[$c]['valor'] = $va;
                        }
                    }
                    else
                    {
                        $filtro[$c]['valor'] = ( is_array($v) ? $v : $v );
                    }
                }
                else
                {
                    $filtro[$c]['valor'] = $v;
                }
            }
        }
        if ( isset($this->negativos) )
        {
            $filtro[$c] = array('tipo' => 'where_not_in','campo' => '_id','valor' => $this->negativos );
        }
        $this->filtro = $filtro;
        return $this;
    }

    private function get_cidades($id = FALSE)
    {
        if ( $id )
        {
            return isset($this->cidades->{$id}) ? $this->cidades->{$id} : NULL;
        }
        return $this->cidades;
    }

    private function set_cidades()
    {
        $cidades_json = getcwd().'/application/views/json/cidades';
        if (file_exists($cidades_json) )
        {
            $this->cidades = json_decode(file_get_contents($cidades_json));
            return $this;

        }
        else
        {
            curl_executavel(base_url().'funcoes/get_cidades_json?own=1');
            $this->set_cidades();
        }
    }

    private function get_post()
    {
        $get = $this->input->get(NULL,TRUE);
        $post = $this->input->post(NULL,TRUE);
        if ( isset($get) && count($get) > 0 )
        {
            foreach($get as $c => $v)
            {
                $post[$c] = $v;
            }
        }
        return $post;
    }

    /**
     * @todo 'quando retornar 0 itens, forçar sugestões em valores diferentes e possiveis outros itens'
     */
    private function set_request()
    {
        $this->benchmark->mark('get_post_start');
        $request = $this->get_post();
        $this->benchmark->mark('get_post_end');
        $this->print_time('get_post');
        $this->benchmark->mark('get_uri_start');
        $valores = $this->get_uri_valores();
        $this->benchmark->mark('get_uri_end');
        $this->print_time('get_uri');
        $this->benchmark->mark('setValores_start');
        if ( isset($valores) && count($valores) > 0 ){
            foreach( $valores as $c => $v ){
                if ( ! ( $c == 'cidade' && isset($valores['set_cidade']) && isset($request['cidade']) ) ){
                    $request[$c] = $v;
                }
            }
        }
        if ( isset($request['id_empresa']) && is_array($request['id_empresa'])){
            $this->set_empresa($request['id_empresa'][0]);
        }
        if ( isset($request['cidade']) && is_array($request['cidade'])){
            $request['cidade'] = $request['cidade'][0];
        }
        if ( isset($request['bairro']) && ! is_array($request['bairro'])){
            $request['bairro'] = explode('+', $request['bairro']);
        }
        if ( ! isset($request['tipo_negocio']) ){
            if ( ! $this->input->is_ajax_request() ){
                $request['tipo_negocio'] = 'venda';
            }
        }
        if ( isset($request['tipo']) ){
            $request['tipo'] = ( (! is_array($request['tipo'])) ? explode('+', $request['tipo']) : $request['tipo']);
        }else{
            if ( ! $this->input->is_ajax_request() ){
                $this->sem_tipo = TRUE;
                if ( ! isset($request['id_empresa']) ){
//                    $request['tipo'] = ['apartamento'];
                }
            }
        }
        if ( isset($request['quartos']) && ! is_array($request['quartos'])){
            $request['quartos'] = explode('+', $request['quartos']);
        }
        if ( isset($request['banheiros']) && ! is_array($request['banheiros'])){
            $request['banheiros'] = explode('+', $request['banheiros']);
        }
        if ( isset($request['vagas']) && ! is_array($request['vagas'])){
            $request['vagas'] = explode('+', $request['vagas']);
        }
        $this->ordem = $this->set_ordem();
        if ( isset($request['ordem']) ){
            $this->ordem = $this->set_ordem($request['ordem']);
            unset($request['ordem']);
        }
        $this->lista_tipo = 'lista';
        if ( isset($request['lista_tipo']) ){
            $this->lista_tipo = $request['lista_tipo'];
            unset($request['lista_tipo']);
        }
        $this->set_cidade = FALSE;
        if (isset($request['set_cidade']) ){
            $this->set_cidade = $request['set_cidade'];
            unset($request['set_cidade']);
        }
        if (isset($request['cidade']) ){
            $this->set_cidades($request['cidade']);
            $this->set_bairros($request['cidade']);
        }
        $this->negativos = NULL;
        if (isset($request['negativos']) ){
            $this->negativos = $request['negativos'];
            unset($request['negativos']);
        }
        $this->offset = NULL;
        if (isset($request['offset']) ){
            $this->offset = $request['offset'];
            unset($request['offset']);
        }
        $this->qtde_itens = 5;
        if (isset($request['qtde_itens']) ){
            $this->qtde_itens = $request['qtde_itens'];
            unset($request['qtde_itens']);
        }
        if ( isset($request['id']) && !empty($request['id']) ){
            $id = $request['id'];
            unset($request);
            $request['id'] = $id;
        }
        $this->request = $request;
        $this->benchmark->mark('setValores_end');
        $this->print_time('setValores');
        return $this;
    }

    private function set_ordem ( $ordem = 'padrao')
    {
        switch ( $ordem )
        {
            default:
            case 'padrao':
                $t_ordem = ['coluna' => 'ordem', 'ordem' => -1 ];
                break;
            case 'preco_menor':
                $t_ordem = ['coluna' => 'preco', 'ordem' => 1 ];
                break;
            case 'preco_maior':
                $t_ordem = ['coluna' => 'preco', 'ordem' => -1 ];
                break;
        }
        return $t_ordem;

    }

    private function get_ordem ( $casa = 'coluna')
    {
        return $this->ordem[$casa];
    }

    private function get_request($campo = FALSE)
    {
        if ( $campo )
        {
            return isset($this->request[$campo]) ? $this->request[$campo] : NULL;
        }
        return $this->request;
    }

    private function get_uri_valores()
    {
        $valores = array();
        $uri_a = $this->get_uri(NULL);
        if ( isset($uri_a['id_empresa']) )
        {
            $valores['id_empresa'] = $uri_a['id_empresa'];
            unset($uri_a['id_empresa']);
        }
        if ( count($uri_a) > 0 && ! empty($uri_a[0]) )
        {
            if ( $uri_a[0] != 'imoveis' )
            {
                $valores['tipo'] = explode('+',$uri_a[0]);
            }
            unset($uri_a[0]);
            if ( isset($uri_a) )
            {
                foreach( $uri_a as $uri )
                {
                    if ( ! empty($uri) )
                    {
                        if ( in_array($uri, $this->tipo_negocio) )
                        {
                            $valores['tipo_negocio'] = $uri;
                        }
                        if ( isset($valores['cidade']) )
                        {
                            $valores['bairro'] = explode('+',$uri);
                        }
                        $cidade = $this->get_cidades($uri);
                        if ( isset($cidade) )
                        {
                            $valores['cidade'] = $uri;
                        }
                    }
                }
            }
        }
        if ( ! ( isset($valores['cidade']) && ! empty($valores['cidade']) ) && ! $this->input->is_ajax_request() )
        {
            $valores['cidade'] = $this->cidade->link;
            $valores['set_cidade'] = TRUE;
        }
        return $valores;
    }

    private function get_url( $completo = FALSE )
    {
        $retorno = 'imoveis';
        $tipo = $this->get_request('tipo');
        if ( $tipo )
        {
            $retorno = implode('+', $tipo);
        }
        $tipo_negocio = $this->get_request('tipo_negocio');
        if ( $tipo_negocio )
        {
            $retorno .= '-'.$tipo_negocio;
        }
        $cidade = $this->get_request('cidade');
        if ( $cidade )
        {
            $retorno .= '-'.$cidade;
        }
        $bairro = $this->get_request('bairro');
        if ( $bairro )
        {
            $retorno .= '-'.implode('+', $bairro);
        }
        if ( $completo )
        {
            $retorno .= '?';
            $a = FALSE;
            foreach( $this->get_request() as $chave => $campo )
            {
                $valor = (is_array($campo) ? implode('+', $campo) : $campo);
                $retorno .= ($a ? '&' : '').$chave.'='.$valor;
                $a = TRUE;
            }
//            if ( isset($this->offset) )
//            {
//                $retorno .= '&qtde_itens='.$this->offset;
//            }
        }
        return $retorno;

    }

    private $uri_chaves = ['pesquisa','get_filtro','imovel','imobiliaria','set_form','get_itens',];
    private $uri_chaves_ordem = ['pesquisa' => 2,'get_filtro' => 2,'imovel' => 2,'imobiliaria' => 3,'set_form' => 2,'get_itens' => 2,];

    private function set_uri()
    {
        $r = $this->uri->segment_array();
        $i = '';
        if ( isset($r[1]) )
        {
            $i = ( ( in_array($r[1], $this->uri_chaves) ) ? (isset($r[$this->uri_chaves_ordem[$r[1]]]) ? $r[$this->uri_chaves_ordem[$r[1]]] : '') : $r[1] );
        }
        $this->uri = (isset($i) && ! empty($i) ) ? explode('-', ( in_array($r[1], $this->uri_chaves) ) ? $r[$this->uri_chaves_ordem[$r[1]]] : $r[1]) : array() ;
        if ( isset($r[1]) && $r[1] == 'imobiliaria' )
        {
            $a = explode('-', $r[2]);
            $id_empresa = $a[1];
            $this->uri['id_empresa'] = [$id_empresa];
        }
        return $this;
    }

    private function get_uri($i = NULL)
    {
        $retorno = isset($i) ? '' : $this->uri;
        if ( isset($this->uri[$i]) )
        {
            $retorno = $this->uri[$i];
        }
        return $retorno;
    }

    private function set_campos()
    {

    }

    private function get_campos($url = FALSE)
    {
        $retorno = array();
        foreach ( $this->campos as $c => $v )
        {
            if ( $url )
            {
                if ( $v['url'] )
                {
                    $retorno[$c] = $v;
                }
            }
            else
            {
               $retorno[$c] = $v;
            }
        }
        return $retorno;
    }

    public function imovel_modal($id)
    {
        $this->load->model('imoveis_mongo_model');
        $data = array();
        $data['item'] = $this->imoveis_mongo_model->get_item($id);
        $layout = $this->layout;
        $retorno = $layout
                ->view('imovel_modal', $data, 'layout/sem_head', TRUE);
        echo $retorno;
    }




    private function set_log_pesquisa_mongo()
    {
        $this->benchmark->mark('estatistica_start');
        $this->load->model(['log_pesquisa_mongo_model']);
        $data = $this->get_request();
        $data['ip'] = $_SERVER['REMOTE_ADDR'];
        $data['host'] = $_SERVER['HTTP_HOST'];
        $data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        $data['offset'] = $this->offset;
        $data['date'] = $this->log_pesquisa_mongo_model->get_time(new DateTime());
        if ( ! $this->_is_robot() && ! isset($data['imovel'])  )
        {
            $this->log_pesquisa_mongo_model->adicionar($data);
        }
        $this->benchmark->mark('estatistica_end');
        $this->print_time('estatistica');
//        echo $this->soma_time();
    }




}
