<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Página de redirecionamento para pesquisa de imóveis.
 * @access public
 * @version 1.0
 * @author Carlos Claro
 * @package Index
 * 
 */
class Nao_encontrei extends MY_Controller {

    
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
        $this->load->library(array('Mongo_db','my_mongo'));
        $this->load->library(array('lista_normal','encryption'));
        $this->load->helper('cookie');
        $this->benchmark->mark('setCidade_start');
        $this->set_cidade();
        $this->benchmark->mark('setCidade_end');
        $this->print_time('setCidade');
        $this->benchmark->mark('loadInicialFavorito_end');
        $this->print_time('loadInicialFavorito');
    }

    /**
     * 
     */
    public function index (  )
    {
        $this->requisita_bd_sessao();
        $this->load->model(array('imoveis_naoencontrei_model', 'cidades_model', 'cadastro_model','bairro_model','parametro_finalidade_model' ));
        $valores = array();
        $data['url'] = base_url().strtolower(__FUNCTION__);
        $data['insert'] = isset($insert) ? TRUE : FALSE;
        $filtro = $this->_inicia_filtros_encontre(  );
        $data['filtro'] = $filtro->get_html_encontre(  );
        $data['sobre'] = array('nome' => $this->cidade->nome, 'portal' => $this->cidade->portal);
        $data['parametros'] = $this->imoveis_naoencontrei_model->get_item();
        $this->titulo = 'Não encontrou o imóvel desejado? Nós ajudamos';
        $this->description = 'Encontre o imóvel que você quer de acordo com suas necessidades. Descreva as caracteristicas que sejam importantes e nós buscamos para você.';
        $ok = $this->input->get( 'ok', TRUE );
        if( $ok )
        {
            $this->layout->set_tag_adwords($this->cidade->tag_adwords);
        }    
        $keys = json_decode($this->cidade->google_keys);
        $data['sitekey'] = $keys->site;
        $data['encaminhado'] = isset($_GET['encaminhado']) ? TRUE : FALSE;
        $this->layout
                    ->set_includes_defaults()
                    ->set_include('js/encontre.js', TRUE)
//                    ->set_include('js/3_3/mascara.min.js', TRUE)
                    ->set_include('css/encontre.css', TRUE)
                    ->set_include('plugins/css/components.min.css', TRUE)
                    ->set_include('css/simple-line-icons.min.css', TRUE)
//                    ->set_include('https://www.google.com/recaptcha/api.js', FALSE)
                    ->set_time($this->soma_time())
//                    ->set_menu($this->menu)
                    ->set_logo( $this->logo_principal )
                    ->set_titulo($this->titulo)
                    ->set_description($this->description)
                    ->set_header_extra($this->cidade->header_tag)
                    ->set_sobre($this->cidade->sobre, $this->cidade->nome, $this->cidade->portal, $this->cidade->gentilico, $this->cidade->link, $this->cidade->uf, $this->cidade->link_prefeitura,1 )
                    ->set_analytics($this->cidade->instrucoes_head)
                    ->view('encontre_para_mim', $data, 'layout/layout_3'); 

    }
    
    private function _inicia_filtros_encontre(  )
    {
        $config['itens']['rotulo'][] = array( 'grupo' => 'oq', 'titulo' => 'O que Busca', 'itens' => array(
                                            array( 'name' => 'oq',          'titulo' => 'Tipo Negócio(Locação, Venda): ',          'tipo' => 'select',   'valor' => $this->parametro_finalidade_model->get_select(), 'classe' => 'form-control oq', 'default' => 'Selecione o que deseja'),
                                            array( 'name' => 'cidade_',      'titulo' => 'Informe a Cidade onde deseja o imóvel: ','tipo' => 'text',     'valor' => '', 'classe' => 'cidade form-control','default' => 'Cidade onde deseja o imóvel:'),
        //                                    array( 'name' => 'tipo',        'titulo' => 'Tipo de imóvel: ',     'tipo' => 'modal_vazio',    'valor' => $this->tipo_model->get_select(), 'selecionado' => $this->_set_selecionado('tipo', isset($valores['tipo']) ? $valores['tipo'] : NULL), 'classe' => 'form-control tipo'),
                                        ) );
        
        //$config['itens']['rotulo'][] = array( 'grupo' => 'local', 'titulo' => 'Local que deseja', 'itens' => array(
        //                                    array( 'name' => 'bairro',      'titulo' => 'Bairro: ',             'tipo' => 'modal_vazio',      'valor' => $this->bairro_model->get_select('bairros.cidade = '.$this->id_cidade), 'selecionado' => $this->_set_selecionado('bairro', isset($valores['bairro']) ? $valores['bairro'] : NULL),  'classe' => 'form-control bairro' ),
        //                                ) );
        /*
        $config['itens']['rotulo'][] = array( 'grupo' => 'valor', 'titulo' => 'Faixa de Valor', 'itens' => array(
                                            array( 'name' => 'valormin',    'titulo' => 'Valor Minimo: ',       'tipo' => 'range',      'valor' => array('min' => isset($oq['selecionado']) ? $oq['selecionado']->valor_min : 0, 'max' => isset($oq['selecionado']) ? $oq['selecionado']->valor_max : 10000000,'value' => (isset($valores['valormin']) ?  $valores['valormin'] : '0') ),                                   'classe' => 'form-control' ),
                                            array( 'name' => 'valormax',    'titulo' => 'Valor Maximo: ',       'tipo' => 'range',      'valor' => array('min' => isset($oq['selecionado']) ? $oq['selecionado']->valor_min : 0, 'max' => isset($oq['selecionado']) ? $oq['selecionado']->valor_max : 10000000,'value' => (isset($valores['valormax']) ?  $valores['valormax'] : '10000000') ),                            'classe' => 'form-control' ),
                                       ) );
        */
        /*
        $config['itens']['rotulo'][] = array( 'grupo' => 'comodos', 'titulo' => 'Comodos', 'class' => '', 'itens' => array(
                                            array( 'name' => 'quartos',     'titulo' => 'Quartos: ',            'tipo' => 'select',     'valor' => $this->_set_valor_numerico(),    'classe' => 'form-control', 'classe_master' => 'col-lg-6 col-sm-6 col-md-6 col-xs-6' ),
                                            array( 'name' => 'vagas',       'titulo' => 'Vagas: ',              'tipo' => 'select',     'valor' => $this->_set_valor_numerico(),    'classe' => 'form-control', 'classe_master' => 'col-lg-6 col-sm-6 col-md-6 col-xs-6' ),
                                        ) );
        */
        
        $filtro = $this->filtro->inicia($config);
        return $filtro;

    }
    
}    