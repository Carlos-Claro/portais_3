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
class Estatisticas extends MY_Controller {

    
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
     * Pagina imobiliarias , com base na URL
     * @param string $coluna - coluna de ordem
     * @param string $ordem - ordem
     */
    public function index ( $coluna = 'empresas.ordenacao DESC,empresas.data_portal ASC', $ordem = '' )
    {
        $this->requisita_bd_sessao();
        $this->load->model(array('imoveis_model','cidades_model'));
        $data['itens'] = $this->imoveis_model->get_estatistica($this->cidade->id);
        $data['cidade'] = array('link' => $this->cidade->link, 'titulo' => $this->cidade->nome);
        $this->titulo = 'Estatisticas de valor por bairros na cidade de '.$this->cidade->nome;
        $this->description = $this->titulo;
        $this->layout
                ->set_includes_defaults()
                ->set_time($this->soma_time())
                ->set_menu($this->menu)
                ->set_logo( $this->logo_principal )
                ->set_titulo($this->titulo)
                ->set_description($this->description)
                ->set_header_extra($this->cidade->header_tag)
                ->set_sobre($this->cidade->sobre, $this->cidade->nome, $this->cidade->portal, $this->cidade->gentilico, $this->cidade->link, $this->cidade->uf, $this->cidade->link_prefeitura,1 )
                ->set_analytics($this->cidade->instrucoes_head)
                ->view('estatistica', $data, 'layout/layout_3'); 

    }
    
}    