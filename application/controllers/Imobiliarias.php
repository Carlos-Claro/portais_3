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
class Imobiliarias extends MY_Controller {

    
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
     * usado em solicitações jquery da pesquisa de imobiliarias
     * @param string $item - texto com titulo ou descrição que são buscados
     * @param string $echo - tipo de resposta echo para solicitação de jquery TRUE, return para FALSE quando é solicitado por function
     * @return string $retorno - pode ser echo ou return
     */
    public function imobiliarias_lista($item = NULL,$echo = TRUE)
    {
        $this->requisita_bd_sessao();
        $item_ = urldecode($item);
        $item_a = explode(' ',$item_);
        $item_a = implode('%', $item_a);
        $this->load->model('empresas_model');
        //$filtro = $this->filtros_padrao(NULL,FALSE);
        $filtro['serv_ini'] = array( 'tipo' => 'where',  'campo' => 'empresas.servicos_pagina_inicio <',    'valor' => time() );
        $filtro['serv_fim'] = array( 'tipo' => 'where',  'campo' => 'empresas.servicos_pagina_termino >',       'valor' => time() );
        $filtro['bloqueado'] = 'empresas.bloqueado = 0 ';
        $filtro['visivel'] = 'empresas.pagina_visivel = 1 ';
        
        //$filtro[] = 'imoveis.id_cidade = '.$this->id_cidade;
        if ( isset($item) )
        {
            $filtro[] = 'empresas.empresa_nome_fantasia like "%'.$item_a.'%"';
        }
        $itens = $this->empresas_model->get_itens_imobiliarias( $filtro, 'empresas.ordenacao', 'DESC', FALSE );
        if ( $itens['qtde'] > 0 )
        {
            $retorno = '';
            foreach( $itens['itens'] as $item )
            {
                $retorno .= $this->lista_normal->set_item_imobiliaria($item, $this->cidade->link);
            }
        }
        else
        {
            
            $retorno = 'Nenhuma Imobiliária encontrada para este resultado.';
        }
        if ( $echo )
        {
            echo $retorno;
        }
        else
        {
            return $retorno;
        }
    }
    
    /**
     * Pagina imobiliarias , com base na URL
     * @param string $coluna - coluna de ordem
     * @param string $ordem - ordem
     */
    public function index ( $coluna = 'empresas.ordenacao DESC,empresas.data_portal ASC', $ordem = '' )
    {
        $this->requisita_bd_sessao();
        $this->load->model('empresas_model');
        $filtro_c['serv_ini'] = array( 'tipo' => 'where',  'campo' => 'empresas.servicos_pagina_inicio <',    'valor' => time() );
        $filtro_c['serv_fim'] = array( 'tipo' => 'where',  'campo' => 'empresas.servicos_pagina_termino >',       'valor' => time() );
        $filtro['serv_ini'] = array( 'tipo' => 'where',  'campo' => 'empresas.servicos_pagina_inicio <',    'valor' => time() );
        $filtro['serv_fim'] = array( 'tipo' => 'where',  'campo' => 'empresas.servicos_pagina_termino >',       'valor' => time() );
        $filtro_c['bloqueado'] = 'empresas.bloqueado = 0 ';
        $filtro_c['visivel'] = 'empresas.pagina_visivel = 1 ';
        $filtro_c[] = '( logradouros.id_cidade = '.$this->id_cidade.' )';
        $itens_cidade = $this->empresas_model->get_itens_imobiliarias( $filtro_c, $coluna, $ordem );
        $filtro['bloqueado'] = 'empresas.bloqueado = 0 ';
        $filtro['visivel'] = 'empresas.pagina_visivel = 1 ';
        $filtro[] = '( imoveis.id_cidade = '.$this->id_cidade.' )';
        $filtro[] = '( logradouros.id_cidade != '.$this->id_cidade.' )';
        $url = base_url().'imobiliarias/'.$coluna.'/'.$ordem.'/?';
        $url_canonica = base_url().'imobiliarias/';
        $itens = $this->empresas_model->get_itens_imobiliarias( $filtro, $coluna, $ordem );
        //$publicidade = $this->_set_publicidade();
        //$config['itens']['publicidade'] = $publicidade['publicidade'];
        $config['itens']['itens'] = array_merge($itens_cidade['itens'], $itens['itens'] );
        $this->titulo = 'Imobiliárias em '.$this->cidade->nome.', Consulte nossa relação de imobiliarias anunciantes em '.$this->cidade->nome.' e encontre a mais perto de você';
        $this->description = 'As imobiliárias de '.$this->cidade->nome.' reunidas em um só lugar. Consulte nossa relação de imobiliarias anunciantes em '.$this->cidade->nome.' e encontre a mais perto de você.';
        $config['titulo'] = $this->titulo;
        $config['h1'] = 'Imobiliárias em '.$this->cidade->nome;
        $config['h2'] = 'Consulte nossa relação de imobiliarias anunciantes em '.$this->cidade->nome.' e encontre a mais perto de você';
        $this->lista_normal->inicia( $config );
        $data['listagem'] = $this->lista_normal->get_html_imobiliarias($this->cidade->link);
        if ( isset ($_SERVER['SCRIPT_URI']) && ( $url_canonica == $_SERVER['SCRIPT_URI'] || $_SERVER['SCRIPT_URI'] == substr($url_canonica,0,-1) ) )
        {
        }
        else
        {
            $this->layout->set_canonical($url_canonica);
        }
        $ok = $this->input->get( 'ok', TRUE );
        if( $ok )
        {
            $this->layout->set_tag_adwords($this->cidade->tag_adwords);
        }    
        $this->layout
                ->set_includes_defaults()
                ->set_include('js/imobiliarias.js', TRUE)	
                ->set_time($this->soma_time())
                ->set_menu($this->menu)
                ->set_logo( $this->logo_principal )
                ->set_titulo($this->titulo)
                ->set_description($this->description)
                ->set_header_extra($this->cidade->header_tag)
                ->set_sobre($this->cidade->sobre, $this->cidade->nome, $this->cidade->portal, $this->cidade->gentilico, $this->cidade->link, $this->cidade->uf, $this->cidade->link_prefeitura,1 )
                ->set_analytics($this->cidade->instrucoes_head)
                ->set_google_tag($this->cidade->google_tag)

                    ->view('resultado_lista', $data, 'layout/layout_3'); 
    }
      
}    