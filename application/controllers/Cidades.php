<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Exibição e pesquisa de imóveis para varios portais, gerencia todas as fases da pesquisa.
 * @access public
 * @version 1.2
 * @author Carlos Claro
 * @package Imoveis
 * @since 20/06/2013
 * 
 * @ultimaAlteracao 30/07/2013  - documentação - inserir analytics
 * @copyright (c) 2013, Carlos Claro
 */
class Cidades extends MY_Controller {
    
    
    /**
     * construtor da função extendida de MyController
     * carrega os model´s necessários para montagem das pesquisas
     * pesquisa qual a cidade com referencia na url
     * set a variavel id_cidade
     * set logo do portal
     * set titulo padrao para a página
     * @version 1.0
     */
    public function __construct()
    {
        parent::__construct(FALSE);
        $this->requisita_bd_sessao();
        $this->load->model(array('tipo_model','bairro_model', 'imoveis_model', 'cidades_model'));
    }


    public function cidade_json ( $estado = NULL, $empresa = NULL )
    {
        if ( ! isset($estado) || ! $estado )
        {
            if ( isset($empresa) )
            {
                $filtro = array( 'empresa' => $empresa );
            }
            else
            {
                $filtro = array();
            }
            $filtro_itens = $this->_get_filtros('estado',$filtro);
            $itens = $this->cidades_model->get_item_estado( $filtro_itens->get_filtro() );
        }
        else
        {
            if ( isset($empresa) )
            {
                $filtro = array( 'empresa' => $empresa, 'uf' => $estado );
            }
            else
            {
                $filtro = array( 'uf' => $estado );
            }
            $filtro_itens = $this->_get_filtros('estados', $filtro);
            $itens['itens'] = $this->cidades_model->get_item_por_modalidade( $filtro_itens->get_filtro() );
            $filtro_capital = $this->_get_filtros('capital', $filtro);
            $itens['capital'] = $this->cidades_model->get_item_por_uf_capital( $filtro_capital->get_filtro() );
        }
        
        echo json_encode($itens);
    }
    
    public function cidade_json_encontre ( $estado = NULL )
    {
        if ( ! isset($estado) )
        {
            $filtro_itens = $this->_get_filtros('estado', array(), NULL, TRUE);
            $itens = $this->cidades_model->get_item_estado(array());
            //var_dump($filtro_itens->get_filtro());
        }
        else
        {
            
            $filtro_itens = 'cidades.uf = "'.$estado.'"';
            $itens['itens'] = $this->cidades_model->get_item_cidade_todas( $filtro_itens );
            $filtro_capital = $this->_get_filtros('capital', array('uf' => $estado),NULL, TRUE);
            $itens['capital'] = $this->cidades_model->get_item_por_uf_capital( $filtro_capital->get_filtro() );
        }
        
        echo json_encode($itens);
    }
    
    public function busca_cidade_json ( $busca = NULL )
    {
        $busca = urldecode($busca);
        $filtro_itens = $this->_get_filtros('cidade', array('cidade' => $busca));
        $itens = $this->cidades_model->get_select2( $filtro_itens->get_filtro() );
        if ( ! empty($busca) )
        {
            $bairros = array();
            $this->load->model('logradouros_model');
            $filtro_bairro = 'logradouros.bairro like "%'.$busca.'%"';
            $busca_bairro = $this->logradouros_model->get_itens_group_bairro($filtro_bairro);
            if ( isset($busca_bairro['itens']) && $busca_bairro['qtde'] > 0  )
            {
                foreach( $busca_bairro['itens'] as $i )
                {
                    $itens[] = (object)array('id' => $i->cidade_link.';'.str_replace(', ', ',', $i->bairro_link), 'text' => '*'.$i->cidade.' *'.str_replace(', ',',',$i->bairro));
                }
            }
            $filtro = 'logradouros.logradouro like "%'.$busca.'%"';
            $busca_logradouro = $this->logradouros_model->get_itens_group($filtro);
            if ( isset($busca_logradouro['itens']) && $busca_logradouro['qtde'] > 0  )
            {
                foreach( $busca_logradouro['itens'] as $i )
                {
                    $itens[] = (object)array('id' => $i->cidade_link.';'.str_replace(', ', ',', $i->bairro_link), 'text' => 'região da '.$i->logradouro.' em *'.$i->cidade.' *'.str_replace(', ',',',$i->bairro));
                    $bairros[$i->cidade_link] = $i->cidade;
//                    $a = explode(',', $i->bairro_link);
//                    $b = explode(',', $i->bairro);
//                    foreach( $a as $ac => $ab)
//                    {
//                        $bairros[$ab] = $b[$ac];
//                    }
                }
            }
            if ( isset($bairros) && count($bairros) > 0 )
            {
                foreach($bairros as $bk => $bv)
                {
                    $itens[] = (object)array('id' => $bk,'text' => trim($bv) );
                }
            }
            else
            {
                $itens = array((object)array('id' => -1, 'text' => 'Nenhuma cidade buscada'));
            }
            //var_dump($busca_logradouro);
        }
        echo json_encode($itens);
    }
        
    /**
     * Inicia Filtros padrão e variaveis
     * @param string $campo nivel da pesquisa
     * @param array $valores com valores fornecidos na url
     * @param string $tipo default null ou venda, locação
     * @access private
     * @version 1.0
     * @return void
     */
    private function _get_filtros ( $campo = 'negocio', $valores = array(), $tipo = NULL, $completo = TRUE )
    {
        
        if ( $completo )
        {
            $valores['vendido'] = 1;
            $valores['reservaimovel'] = 1;
            $valores['locado'] = 1;
            $valores['invisivel'] = 1;
            $valores['vencimento'] = 1;
            $valores['bloqueio'] = 1;
            $valores['servico_ini'] = time();
            $valores['servico_fim'] = time();
            //$valores['link_cidade'] = isset($valores['link_cidade'])? $valores['link_cidade'] :$this->link_cidade;

            $config['itens'] = array(
                                        array( 'name' => 'vencimento',      'where' => '(imoveis.vencimento = "0000-00-00" OR (imoveis.vencimento <> "0000-00-00" AND imoveis.vencimento >= "'.date('Y-m-d').'") )' ),
                                        array( 'name' => 'servico_ini',     'where' => array( 'tipo' => 'where',  'campo' => 'empresas.servicos_pagina_inicio <',    'valor' => '' ) ), 
                                        array( 'name' => 'servico_fim',     'where' => array( 'tipo' => 'where',  'campo' => 'empresas.servicos_pagina_termino >',       'valor' => '' ) ),
                                        array( 'name' => 'link_cidade',     'where' => array( 'tipo' => 'where',  'campo' => 'cidades.link',                   'valor' => '' ) ),
                                        array( 'name' => 'reservaimovel',   'where' => 'imoveis.reservaimovel = 0 '),
                                        array( 'name' => 'vendido',         'where' => 'imoveis.vendido = 0 '),
                                        array( 'name' => 'locado',          'where' => 'imoveis.locado = 0 '),   
                                        array( 'name' => 'bloqueio',          'where' => 'empresas.bloqueado = 0 '),   
                                        array( 'name' => 'invisivel',       'where' => 'imoveis.invisivel = 0 '),    
                                        );
        }
        if ( isset($valores['empresa']) )
        {
            $config['itens'][] = array( 'name' => 'empresa', 'where' => array( 'tipo' => 'where',  'campo' => 'empresas.id',    'valor' => '' ) );
        }
        if ( $campo == 'estados' )
        {
            $config['itens'][] = array( 'name' => 'uf',       'where' => array( 'tipo' => 'where',  'campo' => 'cidades.uf',                   'valor' => '' ) );
        }
        
        switch ( $campo )
        {
            case 'estado':
                break;
            case 'capital':
                $valores['capital'] = 1;
                $config['itens'][] = array( 'name' => 'capital',          'where' => 'cidades.capital = 1 '); 
                $config['itens'][] = array( 'name' => 'uf',       'where' => array( 'tipo' => 'where',  'campo' => 'cidades.uf',                   'valor' => '' ) );
                break;
            case 'cidade':
                $config['itens'][] = array( 'name' => 'cidade',       'where' => array( 'tipo' => 'like',  'campo' => 'cidades.nome',                   'valor' => '' ) );
                break;
        }
        $config['valores'] = $valores;
        $filtro = $this->filtro->inicia($config);
        return $filtro;

    }
    
    

}

            
