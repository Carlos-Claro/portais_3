<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * XML 
 * @access public
 * @version 0.1
 * @author Carlos Claro
 * @package XML
 * @since 18/09/2013
 * 
 * @copyright (c) 2013, Carlos Claro
 */
class XML extends MY_Controller {
    
    private $array = array();
    public $cidade = array();
    public $link_cidade = NULL;
    public $id_cidade = 2;
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
        $this->load->database();
        
        $data_padrao = '2017-03-02';
        $endereco = (HTTPS ? 'https://'.(str_replace('www.', '', $_SERVER['HTTP_HOST'])).'/' : 'http://'.$_SERVER['HTTP_HOST'].'/');
        $endereco_raiz = (HTTPS ? 'https://'.(str_replace('www.', '', $_SERVER['HTTP_HOST'])).'' : 'http://'.$_SERVER['HTTP_HOST'].'');
        $this->array[] = (object)array( 'loc' => $endereco_raiz, 'lastmod' => date('Y-m-d'), 'changefreq' => 'daily', 'priority' => '1.0' );
        //$this->array[] = (object)array( 'loc' => $endereco.'imoveis/', 'lastmod' => date('Y-m-d'), 'changefreq' => 'daily', 'priority' => '0.8' );
        //$this->array[] = (object)array( 'loc' => $endereco.'imobiliarias/', 'lastmod' => date('Y-m-d'), 'changefreq' => 'daily', 'priority' => '0.8' );
        $this->array[] = (object)array( 'loc' => $endereco.'encontre/', 'lastmod' => $data_padrao, 'changefreq' => 'yearly', 'priority' => '0.2' );
        //$this->array[] = (object)array( 'loc' => $endereco.'blog/', 'lastmod' => date('Y-m-d'), 'changefreq' => 'hourly', 'priority' => '0.8' );
        $this->array[] = (object)array( 'loc' => $endereco.'anuncie/', 'lastmod' => $data_padrao, 'changefreq' => 'monthly', 'priority' => '0.6' );
        //$this->array[] = (object)array( 'loc' => $endereco.'por/comprar/tipo/', 'lastmod' => date('Y-m-d'), 'changefreq' => 'daily', 'priority' => '0.9' );
        //$this->array[] = (object)array( 'loc' => $endereco.'por/alugar/tipo/', 'lastmod' => date('Y-m-d'), 'changefreq' => 'daily', 'priority' => '0.9' );
        //$this->array[] = (object)array( 'loc' => $endereco.'por/comprar/bairro/', 'lastmod' => date('Y-m-d'), 'changefreq' => 'daily', 'priority' => '0.9' );
        //$this->array[] = (object)array( 'loc' => $endereco.'por/alugar/bairro/', 'lastmod' => date('Y-m-d'), 'changefreq' => 'daily', 'priority' => '0.9' );
        $this->array[] = (object)array( 'loc' => $endereco.'quem_somos/', 'lastmod' => $data_padrao, 'changefreq' => 'monthly', 'priority' => '0.5' );
        $this->array[] = (object)array( 'loc' => $endereco.'estatistica/', 'lastmod' => date('Y-m-d'), 'changefreq' => 'daily', 'priority' => '0.6' );
        //$this->array[] = (object)array( 'loc' => $endereco.'mapa_do_site/', 'lastmod' => date('Y-m-d'), 'changefreq' => 'daily', 'priority' => '0.6' );
        
    }

    /**
     * redirecionamento para a função pesquisa
     * @access public
     * @version 1.0
     * @return redirecionamento
     */
    public function index(  )
    {
        
        $retorno = '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL;
        $retorno .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.PHP_EOL;
        foreach ( $this->array as $a )
        {
            $retorno .= '<url>'.PHP_EOL;
            $retorno .= '<loc>'.$a->loc.'</loc>'.PHP_EOL;
            $retorno .= '<lastmod>'.$a->lastmod.'</lastmod>'.PHP_EOL;
            $retorno .= '<changefreq>'.$a->changefreq.'</changefreq>'.PHP_EOL;
            $retorno .= '<priority>'.$a->priority.'</priority>'.PHP_EOL;
            $retorno .= '</url>'.PHP_EOL;
        }
        $retorno .= '</urlset>'.PHP_EOL;
        print $retorno;
        
    }
    
    
    
    public function completo ()
    {
        if ( !isset($_GET['usuario']) )
        {
            header('Content-type: text/xml');
        }
        if ( LOCALHOST )
        {
        	$portal = 'https://icuritiba.com';
        }
        else
        {
        	$portal = 'http://'.$_SERVER['HTTP_HOST'] ;
        }
        if ( HTTPS )
        {
            $portal = str_replace('https://', 'http://www.', $portal);
        }
        
        $this->load->model(array('tipo_model','bairro_model', 'imoveis_model', 'cidades_model', 'empresas_model', 'bairros_proximidade_model'));
        $this->cidade = $this->cidades_model->get_item_por_portal( $portal );
        $this->id_cidade = $this->cidade->id;
        $this->empresas = $this->cidades_model->get_item_por_portal( $portal );
        $this->link_cidade =  $this->cidade->link;
            $menu = $this->set_menu_por_cidade(NULL, $this->cidade);
            $url_ = HTTPS ? str_replace('http://www.', 'https://', $menu['url']) : $menu['url'];
//            var_dump($url_, $menu);die();
            $url_bairro = $url_.'-[bairro]';
            unset($menu['url']);
            $array = array('[tipo]-[oq]');
            $array_b = array('imoveis');
            $li = str_replace($array, $array_b, $url_);
            $this->array[] = (object)array( 'loc' => $li, 'lastmod' => date('Y-m-d'), 'changefreq' => 'daily', 'priority' => '0.9' );
            foreach ( $menu as $chave => $valor )
            {
                if ( isset($_GET['usuario']) )
                {
                    var_dump($chave);
                }
                    
                $array = array('[tipo]-[oq]');
                $array_b = array('imoveis-'.$chave);
                $li = str_replace($array, $array_b, $url_);
                $this->array[] = (object)array( 'loc' => $li, 'lastmod' => date('Y-m-d'), 'changefreq' => 'daily', 'priority' => '0.9' );
                if ( count($valor['itens']) > 0 )
                {
                    foreach($valor['itens'] as $v)
                    {
                        $array = array('[oq]','[tipo]');
                        $array_b = array($chave,$v->link);
                        $li = str_replace($array, $array_b, $url_);
                        $this->array[] = (object)array( 'loc' => $li, 'lastmod' => date('Y-m-d'), 'changefreq' => 'daily', 'priority' => '0.9' );
                        $f_bairro = array('cidades.link LIKE "'.$this->cidade->link.'"', 'imoveis_tipos.link LIKE "'.$v->link.'"', 'imoveis.'.$chave.' = 1');
                        $bairros_tipo = $this->bairro_model->get_select_json($f_bairro);
                        foreach($bairros_tipo as $bairro)
                        {
                            $array_a = array('[oq]','[tipo]','[bairro]');
                            $array_a_b = array($chave,$v->link,$bairro->id);
                            $li_b = str_replace($array_a, $array_a_b, $url_bairro);
                            $this->array[] = (object)array( 'loc' => $li_b, 'lastmod' => date('Y-m-d'), 'changefreq' => 'daily', 'priority' => '0.8' );
                            
                        }
                    }
                }
            }
           
            $imobiliarias = $this->get_imobiliarias();
           	foreach( $imobiliarias['itens'] as $imobiliaria )
           	{
           		$url = 
				$this->array[] = (object)array( 'loc' => base_url().'imobiliaria/'.urlencode( tira_especiais($imobiliaria->nome_fantasia) ).'-'.$imobiliaria->id.'/', 'lastmod' => date('Y-m-d'), 'changefreq' => 'daily', 'priority' => '0.9' );
           	}
            
        $this->load->library(array('lista_normal','Mongo_db','my_mongo'));
        $filtro_imoveis[] = array( 'tipo' => 'where',            'campo' => 'cidades_link',     'valor' => $this->cidade->link);
        $this->load->model('imoveis_mongo_model');
        $imoveis = $this->imoveis_mongo_model->get_itens($filtro_imoveis, 'ordem',-1, 0, 15000);
        if ( isset($imoveis['itens']) && count($imoveis['itens']) > 0 )
        {
            foreach( $imoveis['itens'] as $imovel )
            {
                            $url = 
                                    $this->array[] = (object)array( 'loc' => $this->lista_normal->_set_link($imovel,4), 'lastmod' => date('Y-m-d'), 'changefreq' => 'daily', 'priority' => '0.6' );

               //$this->lista_normal->_set_link($imovel,4);
            }
        }
//        var_dump($imoveis);die();
            
        /**
         * imoveis por cidade
         * @deprecated 12/08/2014 version 1.1
         */
        //foreach ( $itens_por_cidade as $iten )
        //{
            //$this->array[] = (object)array( 'loc' => $this->_set_link_imovel_base($iten), 'lastmod' => date('Y-m-d', $iten->atualizacao), 'changefreq' => 'daily', 'priority' => '0.5' );
        //}
        
        $retorno = '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL;
        $retorno .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.PHP_EOL;
        foreach ( $this->array as $a )
        {
            $retorno .= '<url>'.PHP_EOL;
            $retorno .= '<loc>'.$a->loc.'</loc>'.PHP_EOL;
            $retorno .= '<lastmod>'.$a->lastmod.'</lastmod>'.PHP_EOL;
            $retorno .= '<changefreq>'.$a->changefreq.'</changefreq>'.PHP_EOL;
            $retorno .= '<priority>'.$a->priority.'</priority>'.PHP_EOL;
            $retorno .= '</url>'.PHP_EOL;
        }
        $retorno .= '</urlset>'.PHP_EOL;
        print $retorno;
        
    }
    
    public function filtros_padrao ( $oq = NULL, $cidade = TRUE, $cidade_set = FALSE )
    {
        if ( isset($oq) && ! empty($oq) )
        {
            $filtro['oq'] = 'imoveis.'.( isset($oq) ? $oq : 'venda' ).' = 1 ';
        }
        if ( $cidade )
        {
            $filtro['cidade'] = array( 'tipo' => 'where', 	'campo' => 'cidades.link',     'valor' => ($cidade_set) ? $this->cidade_set->link : $this->cidade->link );
        }
        $filtro['vencimento'] = '(imoveis.vencimento = "0000-00-00" OR (imoveis.vencimento <> "0000-00-00" AND imoveis.vencimento >= "'.date('Y-m-d').'") )';
        $filtro['serv_ini'] = array( 'tipo' => 'where',  'campo' => 'empresas.servicos_pagina_inicio <',    'valor' => time() );
        $filtro['serv_fim'] = array( 'tipo' => 'where',  'campo' => 'empresas.servicos_pagina_termino >',       'valor' => time() );
        $filtro['reserva'] = 'imoveis.reservaimovel = 0 ';
        $filtro['vendido'] = 'imoveis.vendido = 0 ';
        $filtro['locado'] = 'imoveis.locado = 0 ';
        $filtro['bloqueado'] = 'empresas.bloqueado = 0 ';
        $filtro['invisivel'] = 'imoveis.invisivel = 0 ';
        return $filtro;
    }
    
    public function get_imobiliarias()
    {
    	$coluna = 'empresas.ordenacao';
    	$ordem = 'DESC';
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
    	$itens = $this->empresas_model->get_itens_imobiliarias( $filtro, $coluna, $ordem );
    	$retorno['itens'] = array_merge($itens_cidade['itens'], $itens['itens'] );
    	return $retorno;
    }
    
    /**
     * @deprecated 03/03/2014 version 0.9
     */
    public function mobile ()
    {
        //instancia.. e monta cidade
        $this->load->model(array('tipo_model','bairro_model', 'imoveis_model', 'cidades_model'));
        $this->cidade = $this->cidades_model->get_item_por_portal( 'http://'.$_SERVER['HTTP_HOST'] );
        $this->link_cidade =  $this->cidade->link;
        
        $prioridade = 0.9;
        $array = array(
                        (object)array( 'loc' => base_url(), 'lastmod' => date('Y-m-d'), 'changefreq' => 'daily', 'priority' => '1.0' ),
                        (object)array( 'loc' => base_url().'index.php/imoveis/', 'lastmod' => date('Y-m-d'), 'changefreq' => 'daily', 'priority' => '1.0' ),
                        (object)array( 'loc' => base_url().'index.php/imoveis/pesquisa/', 'lastmod' => date('Y-m-d'), 'changefreq' => 'daily', 'priority' => '1.0' ),
                        (object)array( 'loc' => base_url().'index.php/cidades/', 'lastmod' => date('Y-m-d'), 'changefreq' => 'daily', 'priority' => '1.0' ),
                        (object)array( 'loc' => base_url().'index.php/imoveis/', 'lastmod' => date('Y-m-d'), 'changefreq' => 'daily', 'priority' => '1.0' ),
                        );
        $retorno = '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL;
        $retorno .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:mobile="http://www.google.com/schemas/sitemap-mobile/1.0">'.PHP_EOL;
        
        
        
        
        $valores = $this->_set_valores(0);
        $campo = 'negocio';
        $tipo = $this->_set_negocio($campo, $valores);
        
                        
        foreach ( $tipo as $tk => $tv )
        {
            if ( $tv['valor'] > 0 )
            {
                $array[] = (object)array( 'loc' => $this->_set_link($tk), 'lastmod' => date('Y-m-d'), 'changefreq' => 'daily', 'priority' => '0.9' );
                $valores_t = $this->_set_valores(0, $tk);
                $filtro_negocio = $this->_get_filtros('negocio', $valores_t, $tk);
                $itens = $this->tipo_model->get_item_por_modalidade( $filtro_negocio->get_filtro() );
                foreach ( $itens as $i )
                {
                    $array[] = (object)array( 'loc' => $this->_set_link($tk, $i->link), 'lastmod' => date('Y-m-d'), 'changefreq' => 'daily', 'priority' => '0.8' );
                    $valores_b = $this->_set_valores(0, $tk, $i->link);
                    $filtro_bairro = $this->_get_filtros('tipo', $valores_b, $tk);
                    $itens_b = $this->bairro_model->get_item_por_modalidade( $filtro_bairro->get_filtro() );
                    foreach ( $itens_b as $ib )
                    {
                        $array[] = (object)array( 'loc' => $this->_set_link($tk, $i->link, $ib->link).'0/10000000', 'lastmod' => date('Y-m-d'), 'changefreq' => 'daily', 'priority' => '0.7' );
                    }
                }
                
            }
            
        }
        $itens_por_cidade = $this->imoveis_model->get_imoveis_por_cidade($this->link_cidade);
        foreach ( $itens_por_cidade as $iten )
        {
            $array[] = (object)array( 'loc' => $this->_set_link_imovel($iten), 'lastmod' => date('Y-m-d'), 'changefreq' => 'daily', 'priority' => '0.9' );
        }
        
        foreach ( $array as $a )
        {
            $retorno .= '<url>'.PHP_EOL;
            $retorno .= '<loc>'.$a->loc.'</loc>'.PHP_EOL;
            $retorno .= '<lastmod>'.$a->lastmod.'</lastmod>'.PHP_EOL;
            $retorno .= '<changefreq>'.$a->changefreq.'</changefreq>'.PHP_EOL;
            $retorno .= '<priority>'.$a->priority.'</priority>'.PHP_EOL;
            $retorno .= '</url>'.PHP_EOL;
        }
        $retorno .= '</urlset>'.PHP_EOL;
        print $retorno;
        
    }
    
    public function noticias ()
    {
        'xmlns:news="http://www.google.com/schemas/sitemap-news/0.9"';
    }
    
    

    /**
     * Monta a pesquisa para negócios
     * @param string $campo tipo de negocio
     * @param array $valores traz os valores que estao sendo pesquisados para retornar as quantidades
     * @version 1.0
     * @access private
     * @return array 
     * 
     */
    private function _set_negocio( $campo = 'negocio', $valores = array() )
    {
        $array_tipo = array(
                        array('tipo' => 'venda'),
                        array('tipo' => 'locacao'),
                        array('tipo' => 'locacao_dia'),
                        );
        foreach( $array_tipo as $a )
        {
            $filtro_tipo = $this->_get_filtros($campo, $valores, $a['tipo']);
            $retorno[ $a['tipo'] ] = array( 
                                        'valor'     => $this->imoveis_model->get_item_por_modalidade( $filtro_tipo->get_filtro() ),
                                        );
            
            
        }
        return $retorno;
    }
    
    /**
     * Monta link padrão para pesquisa e retorno a função anterior
     * @param string $tipo tipo de negocio
     * @param string $link_tipo link do tipo de empreendimento
     * @param int $id_bairro id do bairro de referencia
     * @version 1.0
     * @access private
     * @return string
     */
    private function _set_link ( $tipo = NULL, $link_tipo = NULL, $id_bairro = NULL )
    {
        
        $retorno = base_url().'index.php/imoveis/pesquisa/'.( ($this->link_cidade) ? $this->link_cidade : 0 ).'/';
        if ( isset($tipo) )
        {
            $retorno .= $tipo.'/';
            if ( isset($link_tipo) && $link_tipo )
            {
                $retorno .= $link_tipo.'/';
                if ( isset($id_bairro) && $id_bairro )
                {
                    $retorno .= $id_bairro.'/';
                }
            }
        }
        return $retorno;
    }

        
    private function _set_link_imovel ( $item )
    {
        $retorno = base_url().'index.php/imoveis/imovel/'.$item->id_imovel.'/'.$item->tipo.'/'.$item->tipo_imovel.'/'.tira_acento($item->bairro).'/';
        return $retorno;
    }
    
    
    private function _set_link_imovel_base ( $item )
    {
        //http://www.icuritiba.com/popdetalhes.php?id_imovel=139482&origem=i9&info=
        $endereco = 'http://'.$_SERVER['HTTP_HOST'];
        $retorno = $endereco.'/imovel/'.$item->id_imovel.'/'.$item->tipo.'_'.$item->tipo_imovel.'_'.tira_acento($item->cidade).'_'.tira_acento($item->bairro).'/9';
        return $retorno;
    }

    
    /**
     * Set padrao de itens globais e url
     * @param string $link_cidade
     * @param string $tipo
     * @param string $link_tipo
     * @param int $id_bairro
     * @access private
     * @version 1.0
     * @return array
     */
    private function _set_valores ( $link_cidade = 0, $tipo = 'venda', $link_tipo = '', $id_bairro = 0 )
    {
        
        $this->link_cidade  = ( $link_cidade ) ? $link_cidade : $this->cidade->link;
        $this->tipo         = $tipo;
        $this->link_tipo    = $link_tipo;
        $this->id_bairro    = $id_bairro;
        
        $retorno = array(
                'link_cidade' => $this->link_cidade,
                'tipo' => 1,
                'link_tipo' => $link_tipo,
                'bairro_combo' => $id_bairro,
            );
        return $retorno;
    }
    
    /**
     * Set campos necessarios para pesquisa
     * @param string $tipo
     * @return array
     */
    private function _set_var_tipo ( $tipo )
    {
        $retorno['campo_valor'] = 'preco_'.$tipo;
        $retorno['campo_tipo'] = $tipo;
        return $retorno;
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
    private function _get_filtros ( $campo = 'negocio', $valores = array(), $tipo = NULL )
    {
        $t = isset($tipo) ? $this->_set_var_tipo($tipo) : array();
        $valores['vendido'] = '0';
        $valores['reservaimovel'] = '0';
        $valores['locado'] = '0';
        $valores['invisivel'] = 1;
        $valores['vencimento'] = 1;
        $valores['servico_ini'] = time();
        $valores['servico_fim'] = time();
        if ( $campo != 'estado' && $campo != 'estados' )
        {
            $valores['link_cidade'] = isset($valores['link_cidade'])? $valores['link_cidade'] :$this->link_cidade;
        }
        $config['itens'] = array(
                                    array( 'name' => 'vencimento',      'where' => '(imoveis.vencimento = "0000-00-00" OR (imoveis.vencimento <> "0000-00-00" AND imoveis.vencimento >= "'.date('Y-m-d').'") )' ),
                                    array( 'name' => 'servico_ini',     'where' => array( 'tipo' => 'where',  'campo' => 'empresas.servicos_pagina_inicio <',    'valor' => '' ) ), 
                                    array( 'name' => 'servico_fim',     'where' => array( 'tipo' => 'where',  'campo' => 'empresas.servicos_pagina_termino >',       'valor' => '' ) ),
                                    array( 'name' => 'link_cidade',     'where' => array( 'tipo' => 'where',  'campo' => 'cidades.link',                   'valor' => '' ) ),
                                    array( 'name' => 'reservaimovel',   'where' => 'imoveis.reservaimovel = 1 '),
                                    array( 'name' => 'vendido',         'where' => 'imoveis.vendido = 1 '),
                                    array( 'name' => 'locado',          'where' => 'imoveis.locado = 1 '),   
                                    array( 'name' => 'invisivel',       'where' => 'imoveis.invisivel = 0 '),    
                                    //array( 'name' => 'area_min',        'where' => array( 'tipo' => 'where',  'campo' => 'imoveis.area > ',                     'valor' => '' ) ),
                                    //array( 'name' => 'area_max',        'where' => array( 'tipo' => 'where',  'campo' => 'imoveis.area < ',                     'valor' => '' ) ),
                                    );
        if ( $campo == 'estados' )
        {
            $config['itens'][] = array( 'name' => 'uf',       'where' => array( 'tipo' => 'where',  'campo' => 'cidades.uf',                   'valor' => '' ) );
        }
        
        switch ( $campo )
        {
            case 'estado':
                break;
            case 'cidade':
                break;
            case 'negocio':
                
                $config['itens'][] = array( 'name' => 'tipo',            'where' => array( 'tipo' => 'where',  'campo' => 'imoveis.'.$t['campo_tipo'],           'valor' => '' ) );
                break;
            case 'tipo':
                $config['itens'][] = array( 'name' => 'tipo',            'where' => array( 'tipo' => 'where',  'campo' => 'imoveis.'.$t['campo_tipo'],           'valor' => '' ) );
                $config['itens'][] = array( 'name' => 'link_tipo',       'where' => array( 'tipo' => 'where',  'campo' => 'imoveis_tipos.link',                  'valor' => '' ) );
                break;
            case 'bairro':
                $config['itens'][] = array( 'name' => 'tipo',            'where' => array( 'tipo' => 'where',  'campo' => 'imoveis.'.$t['campo_tipo'],           'valor' => '' ) );
                $config['itens'][] = array( 'name' => 'link_tipo',       'where' => array( 'tipo' => 'where',  'campo' => 'imoveis_tipos.link',                  'valor' => '' ) );
                $config['itens'][] = array( 'name' => 'bairro_combo',    'where' => array( 'tipo' => 'where',  'campo' => 'imoveis.bairro_combo',                'valor' => '' ) );
                break;
            case 'valor':
                $config['itens'][] = array( 'name' => 'tipo',            'where' => array( 'tipo' => 'where',  'campo' => 'imoveis.'.$t['campo_tipo'],           'valor' => '' ) );
                $config['itens'][] = array( 'name' => 'link_tipo',       'where' => array( 'tipo' => 'where',  'campo' => 'imoveis_tipos.link',                  'valor' => '' ) );
                $config['itens'][] = array( 'name' => 'bairro_combo',    'where' => array( 'tipo' => 'where',  'campo' => 'imoveis.bairro_combo',                'valor' => '' ) );
                $config['itens'][] = array( 'name' => 'valor_min',       'where' => array( 'tipo' => 'where',  'campo' => 'imoveis.'.$t['campo_valor'].' >= ',    'valor' => '' ) );
                $config['itens'][] = array( 'name' => 'valor_max',       'where' => array( 'tipo' => 'where',  'campo' => 'imoveis.'.$t['campo_valor'].' <= ',    'valor' => '' ) );
                break;
        }
        $config['valores'] = $valores;
        $filtro = $this->filtro->inicia($config);
        return $filtro;

    }
    
    
    
}

            