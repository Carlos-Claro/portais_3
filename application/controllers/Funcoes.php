<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * XML 
 * @access public
 * @version 0.1
 * @author Carlos Claro
 * @package Funções
 * @since 18/09/2013
 * 
 * @copyright (c) 2013, Carlos Claro
 */
class Funcoes extends MY_Controller {
    
    private $array = array();
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
        
        error_reporting(-1);
        ini_set('display_errors', 1);
        parent::__construct(FALSE);
        $this->load->database();
    }

    
    
    
    public function index(  )
    {
        error_reporting(E_ALL);
        if ( isset($_GET['usuario']) && $_GET['usuario'] == '41be7336a7f841675f5ac0ae4317ae86'  ) 
        {
            $this->load->model( array('bairro_model') );

            $array = $this->bairro_model->get_select('bairros.link = ""');
            foreach ( $array as $a )
            {
                $data = array( 'link' => tira_acento(trim($a->descricao)).'_'.$a->id, 'nome' => trim($a->descricao)  );
                $filtro = array('id' => $a->id);
                $editar = $this->bairro_model->editar($data,$filtro);
                echo 'ID: '.$a->id.' -> '.tira_acento(trim($a->descricao)).'_'.$a->id.' -> Afetados: '.$editar.'<br>'.PHP_EOL;
            }
        }
        else
        {
            echo 'inacessivel atualmente';
        }
    }
    
    public function get_empresa($empresa = NULL)
    {
        if ( isset($empresa))
        {
            $this->load->library(array('Mongo_db','my_mongo'));
            $this->load->model('empresas_mongo_model');
            $item = $this->empresas_mongo_model->get_item($empresa);
            if ( $item ){
                $item->contato_telefone_link = '<a href="tel:'.$item->contato_telefone.'" target="_blank">';
                if (strpos($item->contato_telefone, '-') === false ){
                    if (strlen($item->contato_telefone) == 8 ){
                        $item->contato_telefone = substr($item->contato_telefone, 0, 4).'-'.substr($item->contato_telefone, 4, 4);
                    }
                    elseif (strlen($item->contato_telefone) == 7){
                        $item->contato_telefone = substr($item->contato_telefone, 0, 3).'-'.substr($item->contato_telefone, 3, 4);
                    }
                    elseif (strlen($item->contato_telefone) == 9){
                        $item->contato_telefone = '('.substr($item->contato_telefone, 0, 2).') '.substr($item->contato_telefone, 2, 3).'-'.substr($item->contato_telefone, 5, 4);
                    }
                    elseif (strlen($item->contato_telefone) == 10){
                        $item->contato_telefone = '('.substr($item->contato_telefone, 0, 2).') '.substr($item->contato_telefone, 2, 4).'-'.substr($item->contato_telefone, 6, 4);
                    }
                }
                $item->contato_telefone_link .= $item->contato_telefone;
                $item->contato_telefone_link .= '</a>';
                echo json_encode($item);
            }
            else
            {
                curl_executavel((str_replace('portais_3','admin2_0',base_url())).'funcoes/?own=1');
            }
        }
        else
        {
            echo json_encode([]);
        }
    }
    
    
    public function set_cidades_mongo()
    {
//        die();
        $this->load->library(array('mongo_db','my_mongo'));
        $this->load->model(array('cidades_model','cidades_mongo_model'));
        $this->cidades_mongo_model->excluir_all();
        $cidades = $this->cidades_model->get_itens_mongo();
        foreach($cidades as $chave => $cidade)
        {
            $cidades_ = $cidade;
            $cidades_->_id = (int)$cidade->_id;
            $cidades_->id = (int)$cidade->id;
            $cidades_->menu = $this->set_menu_por_cidade(NULL, $cidade, FALSE);
            $this->cidades_mongo_model->adicionar_one((array)$cidades_);
            unset($cidades_);
        }
    }
    
    public function set_bairros_mongo()
    {
        $this->load->library(array('mongo_db','my_mongo'));
        $this->load->model(array('bairro_model','bairros_mongo_model'));
        $this->bairros_mongo_model->excluir_all();
        $bairros = $this->bairro_model->get_itens_mongo();
        foreach( $bairros as $chave => $bairro )
        {
            $insert_bairros[$chave] = $bairro;
            $insert_bairros[$chave]->_id = (int)$bairro->_id;
            $insert_bairros[$chave]->id = (int)$bairro->id;
            $insert_bairros[$chave]->id_cidade = (int)$bairro->id_cidade;
            $insert_bairros[$chave]->nome_ = str_replace('_',' ',tira_acento($bairro->nome));
        }
        //var_dump($insert_bairros);die();
        $this->bairros_mongo_model->adicionar($insert_bairros);
    }
    
    public function set_bairros_proximidades_mongo()
    {
        $this->load->library(array('mongo_db','my_mongo'));
        $this->load->model(array('bairros_proximidade_model','bairros_proximidade_mongo_model', 'cidades_mongo_model'));
        $this->bairros_proximidade_mongo_model->excluir_all();
        $bairros = $this->bairros_proximidade_model->get_itens_mongo();
        foreach( $bairros as $chave => $bairro )
        {
            $insert_bairros[$chave] = $bairro;
            $insert_bairros[$chave]->_id = (int)$bairro->id;
            $insert_bairros[$chave]->id = (int)$bairro->id;
            $insert_bairros[$chave]->id_cidade = (int)$bairro->id_cidade;
            $insert_bairros[$chave]->titulo_ = str_replace(array('do ','da ','na ', 'no '), '', str_replace('_',' ',tira_acento($bairro->titulo)));
            $insert_bairros[$chave]->apelido = str_replace(array('do ','da ','na ', 'no '), '', $bairro->titulo);
            $insert_bairros[$chave]->cidade = $this->cidades_mongo_model->get_link_por_id($bairro->id_cidade);
        }
        //var_dump($insert_bairros);die();
        $this->bairros_proximidade_mongo_model->adicionar($insert_bairros);
    }
    
    public function retorna_bairro($bairro)
    {
        $this->load->library(array('mongo_db','my_mongo'));
        $this->load->model(array('bairros_mongo_model'));
        $retorno = $this->bairros_mongo_model->get_nome_por_link($bairro);
        var_dump($retorno);
    }
    
    public function gera_menu_por_cidade() 
    {
        $this->load->model('cidades_model');
        $cidades = $this->cidades_model->get_cidades_com_menu();
        $arquivo = getcwd().'/application/views/menus/';
        foreach ( $cidades as $cidade )
        {
            $data['sobre'] = $cidade;
            $data['menu'] = $this->set_menu_por_cidade(NULL, $cidade);
            $l_m = $this->layout
                        ->view('menu', $data, 'layout/sem_head', TRUE); 
            $arquivo_ = $arquivo.$cidade->link;
            unlink($arquivo.$cidade->link);
            $a = fopen($arquivo_, 'x');
            fwrite($a, $l_m);
            fclose($a);
        }
        //var_dump($cidades);
    }
    
    public function get_tipos()
    {
        $this->load->model('tipo_model');
        $tipos = $this->tipo_model->get_select_json();
        $tipo_novo = array();
        foreach( $tipos as $tipo )
        {
            $tipo_novo[$tipo->id] = $tipo;
        }
        $arquivo = getcwd().'/application/views/json/tipo';
        unlink($arquivo);
        $a = fopen($arquivo, 'x');
        fwrite($a, json_encode($tipo_novo) );
        fclose($a);
        
    }
    
    public function get_cidades_json()
    {
        $this->load->model('cidades_model');
        $cidades = $this->cidades_model->get_select_json();
        $arquivo = getcwd().'/application/views/json/cidades';
        foreach( $cidades as $cidade )
        {
            if ( ! empty($cidade->id) )
            {
                $b[$cidade->id] = $cidade;
            }
        }
        unlink($arquivo);
        $a = fopen($arquivo, 'x');
        fwrite($a, json_encode($b) );
        fclose($a);
        
    }
    
    public function set_bairros_por_cidade( $cidade, $bairro = '' )
    {
        $arquivo = getcwd().'/application/views/json/bairros/'.$cidade;
        if ( file_exists($arquivo) )
        {
            $bairros = json_decode(file_get_contents($arquivo));
            if ( isset($bairro) && ! empty($bairro) )
            {
                
                $retorno = FALSE;
                foreach ( $bairros as $bairro_ )
                {
                    if (strstr(strtolower($bairro_->descricao), strtolower($bairro) ) )
                    {
                        $retorno[$bairro_->id] = $bairro_;
                    }
                }
            }
            else
            {
                $retorno = $bairros;
            }
        }
        else
        {
            $retorno = FALSE;
        }
        echo json_encode($retorno);
        
    }
    
    public function set_bairros_por_cidade_select2( $cidade, $bairro_historico = 0 )
    {
        $retorno = FALSE;
        $bairro = $this->input->get('term',TRUE);
        if ( isset($bairro) && ! empty($bairro) )
        {
            $this->load->library(array('mongo_db','my_mongo'));
            $this->load->model(array('bairros_mongo_model'));
            $bairros = $this->bairros_mongo_model->get_busca_por_cidade($cidade, $bairro);
            if ( isset($bairros) && count(get_object_vars($bairros)) > 0  )
            {
                foreach($bairros as $b )
                {
                    $retorno[] = array('id' => $b->link, 'text' => $b->nome);
                }
            }
            else
            {
                $bairros_ = $this->bairros_mongo_model->get_busca_por_cidade($cidade, $bairro, TRUE);
                if ( isset($bairros_) && count(get_object_vars($bairros_)) > 0 )
                {
                    foreach($bairros_ as $b )
                    {
                        $retorno[] = array('id' => $b->link, 'text' => $b->nome);
                    }
                }
                
            }
            $this->load->model(array('bairros_proximidade_mongo_model'));
            $proximos = $this->bairros_proximidade_mongo_model->get_busca_por_cidade($cidade,$bairro);
            if ( isset($proximos) && count(get_object_vars($proximos)) > 0 )
            {
                foreach ( $proximos as $proximo )
                {
                    $retorno[] = array('id' => str_replace('+',',',$proximo->link_bairros), 'text' => $proximo->apelido);
                }
            }
            $this->load->model('logradouros_model');
            $filtro___ = 'logradouros.logradouro like "%'.$bairro.'%"';
            $filtro___ .= ' AND cidades.link like "'.$cidade.'"';
            $busca_logradouro = $this->logradouros_model->get_itens_group($filtro___);
            if ( isset($busca_logradouro['itens']) && $busca_logradouro['qtde'] > 0  )
            {
                foreach( $busca_logradouro['itens'] as $i )
                {
                    $retorno[] = (object)array('id' => str_replace(', ', ',', $i->bairro_link), 'text' => 'região da '.$i->logradouro.' *'.str_replace(', ',',',$i->bairro));
                }
            }
        }
        else 
        {
            $arquivo = getcwd().'/application/views/json/bairros/'.$cidade;
            if ( file_exists($arquivo) )
            {
                $arquivo_bairros = file_get_contents($arquivo);
                $arquivo_bairros = str_replace('descricao','text',$arquivo_bairros);
                $bairros = json_decode($arquivo_bairros);
                $retorno = array();
                foreach( $bairros as $b )
                {
                    $retorno[] = array('id' => $b->id, 'text' => $b->text );
                }   
            }
        }
        echo json_encode($retorno);
    }
    
    public function get_bairros_por_cidade()
    {
//        die('parado');
        $this->load->model('bairro_model');
        $this->load->model('cidades_model');
        $cidades = $this->cidades_model->get_cidades_com_menu();
        foreach ( $cidades as $cidade )
        {
            $arquivo = getcwd().'/application/views/json/bairros/'.$cidade->link;
            $filtro = $this->filtros_padrao(NULL, $cidade);
            $bairros = $this->bairro_model->get_select_json($filtro);
            $bairros_array = array();
            foreach( $bairros as $bairro )
            {
                $bairros_array[$bairro->id] = $bairro;
            }
            unlink($arquivo);
            $a = fopen($arquivo, 'x');
            fwrite($a, json_encode($bairros_array) );
            fclose($a);
        }
        
    }
    
    
    
    public function get_cidades()
    {
        $this->load->model('bairro_model');
        $this->load->model('cidades_model');
        $cidades = $this->cidades_model->get_cidades_com_menu();
        foreach ( $cidades as $cidade )
        {
            $arquivo = getcwd().'/application/views/json/bairros/'.$cidade->link;
            $filtro = $this->filtros_padrao(NULL, $cidade);
            $tipos = $this->bairro_model->get_select_json($filtro);
            unlink($arquivo);
            $a = fopen($arquivo, 'x');
            fwrite($a, json_encode($tipos) );
            fclose($a);
        }
        
    }
    
    public function filtros_padrao ( $oq = NULL, $cidade = NULL )
    {
        if ( isset($oq) && ! empty($oq) )
        {
            $filtro['oq'] = 'imoveis.'.( isset($oq) ? $oq : 'venda' ).' = 1 ';
        }
        if ( isset($cidade) )
        {
            $filtro['cidade'] = array( 'tipo' => 'where', 	'campo' => 'cidades.link',     'valor' => $cidade->link );
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
    
    private $arquivo_do_dia = FALSE;
    private $pasta_arquivos = FALSE;
    
    private function _set_arquivo_do_dia()
    {
        if ( ! $this->arquivo_do_dia )
        {
            $this->arquivo_do_dia = date('Y-m-d').'.txt';
        }
        $this->pasta_arquivos = getcwd().'/images/por_empresa/';
    }
    
    private function _manipula_arquivo_do_dia( $id = FALSE, $executando = FALSE )
    {
        if ( ! file_exists($this->pasta_arquivos.$this->arquivo_do_dia) )
        {
            $f = fopen($this->pasta_arquivos.$this->arquivo_do_dia, 'a+');
            fwrite($f, json_encode( $this->_set_lista() ));
            fclose($f);
        }
        else
        {        
            if ( $id )
            {
                unlink($this->pasta_arquivos.$this->arquivo_do_dia);
                $f = fopen($this->pasta_arquivos.$this->arquivo_do_dia, 'a+');
                fwrite($f, json_encode( $this->_set_lista($id, $executando) ));
                fclose($f);
            }
        }
        $arquivo = file_get_contents($this->pasta_arquivos.$this->arquivo_do_dia);
        if ( ! empty($arquivo ) )
        {
            $lista = json_decode($arquivo);
        }
        return $lista;
    }
    
    private function _set_lista( $id_executa = FALSE, $executando = TRUE )
    {
        $lista = scandir($this->pasta_arquivos, SCANDIR_SORT_ASCENDING);
        $array = array();
        foreach( $lista as $pasta )
        {
            if ( ( $pasta != '.' && $pasta != '..' ) && is_dir( $this->pasta_arquivos.$pasta ) )
            {
                if ( $id_executa )
                {
                    if ( $pasta == $id_executa  )
                    {
                        $array[$pasta] = $executando;
                        
                    }
                    elseif( $pasta < $id_executa )
                    {
                        $array[$pasta] = TRUE;
                    }
                    else
                    {
                        $array[$pasta] = FALSE;
                    }
                }
                else
                {
                    $array[$pasta] = FALSE;
                    
                }
            }
        }
        return $array;
    }
    
    
    
    public function verifica_images_zeradas()
    {
        $this->_set_arquivo_do_dia();
        $lista = scandir($this->pasta_arquivos, SCANDIR_SORT_ASCENDING);
        $array = array();
        foreach( $lista as $pasta )
        {
            if ( ( $pasta != '.' && $pasta != '..' ) && is_dir( $this->pasta_arquivos.$pasta ) )
            {
                $lista_arquivos = scandir( $this->pasta_arquivos.$pasta, SCANDIR_SORT_ASCENDING);
                foreach ( $lista_arquivos as $arquivo )
                {
                    $caminho_arquivo = $this->pasta_arquivos.$pasta.'/'.$arquivo;
                    if ( ( $arquivo != '.' && $arquivo != '..' ) && is_file( $caminho_arquivo ) )
                    {
                        $tamanho_arquivo = filesize($caminho_arquivo);
                        if ( $tamanho_arquivo < 2000 )
                        {
                            echo $tamanho_arquivo.' - '.$caminho_arquivo.PHP_EOL.'<br>';
                            unlink($caminho_arquivo);
                        }
                    }
                }
            }
        }
        
    }
    
    
    public function verifica_images_ativas()
    {
        $this->load->model(array('empresas_model', 'imoveis_model', 'images_model'));
        $this->_set_arquivo_do_dia();
        $json = $this->_manipula_arquivo_do_dia();
        foreach( $json as $chave => $status )
        {
            if ( ! $status )
            {
                $empresa_ativa = $this->empresas_model->get_item($chave);
                //var_dump($empresa_ativa->id);
                if ( $empresa_ativa->bloqueado )
                {
                    deletar_pasta( $this->pasta_arquivos,$empresa_ativa->id );
                    $this->_manipula_arquivo_do_dia($empresa_ativa->id, TRUE);
                }
                else
                {
                    $lista_arquivos = scandir( $this->pasta_arquivos.$empresa_ativa->id, SCANDIR_SORT_ASCENDING);
                    foreach ( $lista_arquivos as $arquivo )
                    {
                        $caminho_arquivo = $this->pasta_arquivos.$empresa_ativa->id.'/'.$arquivo;
                        if ( ( $arquivo != '.' && $arquivo != '..' ) && is_file( $caminho_arquivo ) )
                        {
                            $tamanho_arquivo = filesize($caminho_arquivo);
                            if ( $tamanho_arquivo < 5 )
                            {
                                unlink($caminho_arquivo);
                            }
                            else
                            {
                                $extensao = explode('.',$arquivo);
                                $ids = explode('_',$extensao[0]);
                                $id_imovel = ($ids[0] == 'destaque') ? $ids[1] : $ids[0];
                                $filtro_imovel = 'imoveis.id = '.$id_imovel;
                                $imovel = $this->imoveis_model->get_total_itens($filtro_imovel);
                                if ( ! $imovel )
                                {
                                    $end_mascara = $this->pasta_arquivos.$empresa_ativa->id.'/*'.$id_imovel.'*.*';
                                    array_map('unlink', glob($end_mascara));
                                    $end_mascara_ = $this->pasta_arquivos.$empresa_ativa->id.'/'.$id_imovel.'*.*';
                                    array_map('unlink', glob($end_mascara_));
                                    $end_mascara_ = $this->pasta_arquivos.$empresa_ativa->id.'/'.$id_imovel.'*';
                                    array_map('unlink', glob($end_mascara_));
                                }
                                else
                                {
                                    
                                    $id_image = ($ids[0] == 'destaque') ? $ids[2] : $ids[1];
                                    $filtro = 'imoveis_images.id = '.$id_image;
                                    $image = $this->images_model->get_total_images($filtro);
                                    if ( ! $image )
                                    {
                                        $end_mascara = $this->pasta_arquivos.$empresa_ativa->id.'/*'.$id_image.'*.*';
                                        array_map('unlink', glob($end_mascara));
                                        $end_mascara_ = $this->pasta_arquivos.$empresa_ativa->id.'/*'.$id_image.'.*';
                                        array_map('unlink', glob($end_mascara_));
                                        unlink($caminho_arquivo); 
                                    }
                                    
                                }
                                
                                /*
                                 * 
                                 */
                            }
                        }
                    }
                    $this->_manipula_arquivo_do_dia($empresa_ativa->id, TRUE);
                }
            }
        }
    }
    
    
    public function url_redirect()
    {
        $this->load->model('bairro_model');
        $array_bairro_erro = array('bairro', 'ordem_rad','preco_venda','bairro_link','area_util');
        $bairros = '';
        foreach( $array_bairro_erro as $b )
        {
            
            $url_antiga = base_url().'imoveis/curitiba_pr/0/0/'.(isset($_GET['pow']['bairro']) ? $_GET['pow']['bairro'] : '0');
            $url_nova = base_url().'imoveis/'.(isset($cidade) ? $cidade : '0').'/'.(isset($oq) ? $oq : '0').'/'.(isset($tipo) ? $tipo : '0').'/'.(isset($_GET['pow']['bairro']) ? $_GET['pow']['bairro'] : '0');
            
        }
        
        
        
    }
    
    public function gerir_csv ()
    {
        $this->load->model('ip_robot_model');
        $arquivo = base_url().'repositorio/robot20140225.csv';
        $h = fopen($arquivo, 'r');
        while (($data = fgetcsv($h, 10000, ",")) !== FALSE) {
            $filtro = 'ip like "'.$data[0].'"';
            $item = $this->ip_robot_model->get_item($filtro);
            if ( ! isset($item) )
            {
                $d = array(
                            'ip' => $data[0],
                            'descricao' => $data[1],
                            'faq' => $data[2],
                            'data_inclusao' => date('Y-m-d H:i')
                            );
                $ip[] = $this->ip_robot_model->adicionar($d);
            }
        }
        fclose ($handle);
        var_dump($ip);
    }
    
    public function tira_espaco_nome_bairro(  )
    {
        error_reporting(E_ALL);
        if ( isset($_GET['usuario']) && $_GET['usuario'] == '41be7336a7f841675f5ac0ae4317ae86'  ) 
        {
            $this->load->model( array('bairro_model') );

            $array = $this->bairro_model->get_select();
            foreach ( $array as $a )
            {
                $data = array( 'nome' => trim($a->descricao)  );
                $filtro = array('id' => $a->id);
                $editar = $this->bairro_model->editar($data,$filtro);
                echo 'ID: '.$a->id.' -> '.tira_acento(trim($a->descricao)).'_'.$a->id.' -> Afetados: '.$editar.'<br>'.PHP_EOL;
            }
        }
        else
        {
            echo 'inacessivel atualmente';
        }
    }
    
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
    
    public function completo ()
    {
        $end = $_SERVER['HTTP_HOST'].'/';
        $this->load->model(array('tipo_model','bairro_model', 'imoveis_model', 'cidades_model'));
        $this->cidade = $this->cidades_model->get_item_por_portal( 'http://'.$_SERVER['HTTP_HOST'] );
        $this->link_cidade =  $this->cidade->link;
        $itens_por_cidade = $this->imoveis_model->get_imoveis_por_cidade($this->link_cidade);
        
        $this->array[] = (object)array( 'loc' => $end, 'lastmod' => date('Y-m-d'), 'changefreq' => 'daily', 'priority' => '1.0' );
        $this->array[] = (object)array( 'loc' => $end.'imobiliarias', 'lastmod' => date('Y-m-d'), 'changefreq' => 'daily', 'priority' => '0.8' );
        $this->array[] = (object)array( 'loc' => $end.'encontre', 'lastmod' => '2014-02-14', 'changefreq' => 'yearly', 'priority' => '0.2' );
        $this->array[] = (object)array( 'loc' => $end.'blog', 'lastmod' => date('Y-m-d'), 'changefreq' => 'hourly', 'priority' => '0.8' );
        $this->array[] = (object)array( 'loc' => $end.'anuncie', 'lastmod' => '2014-02-14', 'changefreq' => 'monthly', 'priority' => '0.6' );
        $this->array[] = (object)array( 'loc' => $end.'por/comprar/tipo', 'lastmod' => date('Y-m-d'), 'changefreq' => 'daily', 'priority' => '0.8' );
        $this->array[] = (object)array( 'loc' => $end.'por/alugar/tipo', 'lastmod' => date('Y-m-d'), 'changefreq' => 'daily', 'priority' => '0.8' );
        $this->array[] = (object)array( 'loc' => $end.'por/comprar/bairro', 'lastmod' => date('Y-m-d'), 'changefreq' => 'daily', 'priority' => '0.8' );
        $this->array[] = (object)array( 'loc' => $end.'por/alugar/bairro', 'lastmod' => date('Y-m-d'), 'changefreq' => 'daily', 'priority' => '0.8' );
        $this->array[] = (object)array( 'loc' => $end.'quem_somos', 'lastmod' => '2014-02-14', 'changefreq' => 'monthly', 'priority' => '0.5' );
        $this->array[] = (object)array( 'loc' => $end.'estatistica', 'lastmod' => date('Y-m-d'), 'changefreq' => 'daily', 'priority' => '0.6' );
        $this->array[] = (object)array( 'loc' => $end.'mapa_do_site', 'lastmod' => date('Y-m-d'), 'changefreq' => 'daily', 'priority' => '0.6' );
        foreach ( $itens_por_cidade as $iten )
        {
            $this->array[] = (object)array( 'loc' => $this->_set_link_imovel_base($iten), 'lastmod' => date('Y-m-d'), 'changefreq' => 'daily', 'priority' => '0.9' );
        }
          
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
        //http://www.icuritiba.com/imovel/139482
        $endereco = 'http://'.$_SERVER['HTTP_HOST'];
        $retorno = $endereco.'/imovel/'.$item->id_imovel.'/'.$item->tipo.'_'.$item->tipo_imovel.'_'.tira_acento($item->cidade).'_'.tira_acento($item->bairro).'/2/';
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
    
    public function arruma_imovel_bairro( )
    {
        $this->load->model( array('imoveis_model') );
        $retorno = '<br>inicio: ';
        $itens = $this->imoveis_model->get_itens(array(),'imoveis.id','ASC',1,50000);
        $retorno .= '<br> quantidade total de itens: '.$itens['qtde'];
        $contador = 0;
        foreach ( $itens['itens'] as $item )
        {
            if ( ($item->bairro_cidade != $item->imovel_id_cidade) && $item->bairro_combo != 0 && $item->imovel_id_cidade != 0 )
            {
                
                $retorno .= '<br> - id_imovel: '.$item->id_imovel.' - Cidade imovel: '.$item->imovel_id_cidade.' - Cidade bairro: '.$item->bairro_cidade;
                //$this->set_bairros($item->id_imovel);
                $contador++;
            }
        }
        $retorno .= '<br><br> quantidade total de itens incorretos: '.$contador;
        echo $retorno;
        $envio['mensagem'] = $retorno;
        $envio['to'] = array('carlos@carlosclaro.com.br', 'll@pow.com.br');
        $this->envio($envio);
            
            
    }
    
    public function set_bairros( $id_imovel = FALSE )
    {
        //echo 'teste';
        error_reporting(E_ALL);
        header('Content-Type: text/html; charset=UTF-8');
        $this->load->model(array('imoveis_model', 'bairro_model'));
        $filtro = ( ($id_imovel) ? 'imoveis.id = '.$id_imovel : 'imoveis.bairro_combo = 0 ');
        $itens = $this->imoveis_model->get_itens_bairro($filtro);
        if ( $id_imovel ) 
        {
            var_dump($itens);
        }
        $log['item'] = 'Qtde de itens sem bairro_combo: '.$itens['qtde'];
        foreach ( $itens['itens'] as $item )
        {
            $bairro_combo = $this->_get_bairro($item->id_cidade, $this->_trata($item->bairro, 'texto_sem_acento'));
            
            if ( $bairro_combo == 0 ) 
            {
                $bairro_combo = $this->_get_bairro_eq($item->id_cidade, $this->_trata($item->bairro, 'texto_sem_acento'));
                if ( $bairro_combo == 0 ) 
                {
                    $log['erro'][] = '<tr>
                                            <td>'.$item->id_imovel.'</td>
                                            <td>'.$item->bairro.'</td>
                                            <td>'.$item->cidade.'</td>
                                            <td>'.$item->estado.'</td>
                                            <td>'.$item->cidade_nome.'</td>
                                            <td>'.$item->id_cidade.'</td>
                                            <td>'.$item->logradouro.' n: '.$item->numero.' cep: '.$item->cep.'</td>
                                            <td>'.$item->id_empresa.'</td>
                                            <td>'.$item->tipo.'</td>
                                            <td>'.$item->invisivel.'</td>
                                            <td>'.date('d/m/Y',$item->data).'</td>
                                        </tr>';
                } 
                else
                {
                    $data = array('bairro_combo' => $bairro_combo);
                    $filtro = array('id' => $item->id_imovel);
                    var_dump($data,$filtro); echo '<br><br>';
                    $edit = $this->imoveis_model->editar($data,$filtro);
                    $log['acerto'][] = 'Imóvel alterado com sucesso: id: '.$item->id_imovel;
                }
            }
            else
            {
                $data = array('bairro_combo' => $bairro_combo);
                $filtro = array('id' => $item->id_imovel);
                var_dump($data,$filtro); echo '<br><br>';
                $edit = $this->imoveis_model->editar($data,$filtro);
                $log['acerto'][] = 'Imóvel alterado com sucesso: id: '.$item->id_imovel;
            }
            
        }
        $envio['mensagem'] = $this->_monta_log_bairro($log);
        $envio['to'] = array('carlos@carlosclaro.com.br', 'll@pow.com.br');
        $this->envio($envio);
        echo $this->_monta_log_bairro($log);
        
            
    }
    
    private function _monta_log_bairro($log)
    {
        $retorno = '<table>';
        $retorno .= '<tr><th colspan=3>'.$log['item'].'</th></tr>';
        $retorno .= '<tr><th colspan=3>Qtde de itens corrigidos: '.(isset($log['acerto']) ? count($log['acerto']) : 0).'</th>';
        $retorno .= '<tr><th colspan=3>Lista de erros: '.count($log['erro']).'</th>';
        $retorno .= '<tr><th>id_imovel</th><th>Bairro</th><th>Cidade</th><th>UF</th><th>Cidade Nome</th><th>ID Cidade</th><th>Logradouro</th><th>id_empresa</th><th>Tipo Imóvel</th><th>Invisivel ( 0 invisivel ) ( 1 visivel )</th><th>Data Cadastro</th></tr>';
        foreach ( $log['erro'] as $erro )
        {
            $retorno .= $erro;
        }
        $retorno .= '</table>';
        
        return $retorno;
    }
    
    /**
     * Função que retorna o bairro_combo  com base na cidade e no titulo do bairro "Afonso pena"
     * @param int $id_cidade
     * @param string $bairro
     * @return int id_bairro
     */
    private function _get_bairro( $id_cidade, $bairro )
    {
        
        $retorno = $this->bairro_model->get_por_nome_cidade( $bairro, $id_cidade );
        return $retorno;
    }

    /**
     * Função que retorna o bairro_combo  com base na cidade e no titulo do bairro "Afonso pena" caso nao tenha sido achado na lista principal
     * @param int $id_cidade
     * @param string $bairro
     * @return int id_bairro
     */
    private function _get_bairro_eq( $id_cidade, $bairro )
    {
        $retorno = $this->bairro_model->get_por_nome_cidade_equivalentes( $bairro, $id_cidade );
        return $retorno;
    }
    
    public function lixo()
    {
        header('Content-Type: text/html; charset=UTF-8');
        $array_a = array('Á','á','à','À','é','É','í','Í','ì','ó','Ó','ú','Ú','â','Â','ê','Ê','ô','Ô','à','ã','Ã','õ','Õ','ü','ç','Ç',' ','-','´', "'",'"','º','(',')','+');
        $array_b = array('%C3%81','%C3%A1','%C3%A0','%C3%80','%C3%A9','%C3%89','%C3%AD','%C3%8D','%C3%AC','%C3%B3','%C3%93','%C3%BA','%C3%9A','%C3%A2','%C3%82','%C3%AA','%C3%8A','%C3%B4','%C3%94',            '%C3%A0','%C3%A3','%C3%83','%C3%B5','%C3%95','%C3%BC','%C3%A7','%C3%87',' ','-','%C2%B4','%27','%22','%C2%BA','%28','%29','%2B');
    }
    
    public function get_xml($tipo = 1)
    {
        error_reporting('E_ALL');
        
        
        $endereco = ($tipo == 1) ? 'http://www.ribeiro.vistahosting.com.br/vista.imobi/portaisimobiliarios/ribeiroi.xml' : 'http://rubensaquino.vistahosting.com.br/okaimove/vista.imobi/portaisimobiliarios/okaimove.xml';
        
        
        
        echo '<br> endereco chamado: '.$endereco.'<br>';
        $xml = file_get_contents($endereco);
        echo 'file get content: ';var_dump($xml);
        $xml = simplexml_load_file( $endereco );
        echo '<br>resultado por simplexml: '; var_dump($xml);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endereco);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $retorno = curl_exec($ch);
        curl_close($ch);
        $xml = simplexml_load_file($retorno);
        echo '<br> resultado por curl: '; var_dump($xml);
        die();
        foreach( $xml->channel as $x )
        {
            foreach($x->item as $r)
            {
                $a = $r;
                /*
                 * ["title"]=>
  object(SimpleXMLElement)#26 (0) {
  }
  ["link"]=>
  object(SimpleXMLElement)#27 (0) {
  }
  ["description"]=>
  object(SimpleXMLElement)#28 (0) {
  }
  ["pubDate"]=>
  string(19) "05/11/2013 16:38:39"
  ["localDataHora"]=>
                 */
                var_dump($r);
            }
        }
    }
    
    private $conn = NULL;
    
    private function conecta_ftp()
    {
        
        $this->load->library('ftp');
        $config['hostname'] = 'ftp.powimoveis.com.br';
        $config['username'] = 'powimoveis05';
        $config['password'] = 'c2a0r1l2';
        $config['port']     = 21;
        $config['passive']  = FALSE;
        $config['debug']    = TRUE;
        $this->ftp->connect($config);
        /*
        $this->conn = ftp_connect('ftp.powimoveis.com.br');
        // login com o nome de usuário e senha
        $login_result = ftp_login($this->conn, 'powimoveis05', 'c2a0r1l2');
         * 
         */
        return $this;
    }
    
    private function close_ftp()
    {
        ftp_close($this->conn);
    }
    
    public function verifica_image_powimoveis( $id_empresa = NULL )
    {
        die('desabilitado para testes');
        error_reporting(E_ALL);
        //$this->close_ftp();
        header('Content-Type: text/html; charset=UTF-8');
        $this->load->model(array('empresas_model','imoveis_model'));
        $this->conecta_ftp();
        
        $pasta_thumb = ftp_pwd( $this->conn ).'thumb/';
        if ( isset($id_empresa) )
        {
            $pastas = array('/thumb/'.$id_empresa);
        }
        else
        {
            $pastas = ftp_nlist($this->conn, $pasta_thumb);
        }
        //var_dump($pastas);die();
        
        //var_dump( $pastas ); die();
        
        
        $conta = 0;
        foreach($pastas as $p)
        {
            $a = explode('/',$p);
            $empresa = $a[2];
            if ( ! strstr( '.', $empresa ) && $empresa !== '.' && $empresa !== '..' )
            {
            //var_dump($empresa);die();
                $filtro = 'empresas.id = '.$empresa.' AND pagina_visivel = 1 ';
                $i = $this->empresas_model->get_select($filtro);
                if ( count($i) == 0 )
                {
                    
                    $arquivos = ftp_nlist($this->conn, $p);
                    //var_dump($arquivos);die();
                    unset($arquivos[0]);
                    unset($arquivos[1]);
                    echo '<br>'.$p;
                    if ( count($arquivos) > 0 )
                    {
                        ftp_chdir($this->conn, $p);
                        //var_dump($arquivos);$die();
                        foreach ( $arquivos as $caminho_arquivo )
                        {
                            $arquivo_explodido = explode('/', $caminho_arquivo);
                            $arquivo = $arquivo_explodido[4];
                            if ( isset($arquivo) && ! empty($arquivo) && $arquivo !== '.' && $arquivo !== '..' )
                            {
                                ftp_delete($this->conn, $arquivo);
                                echo '<br>'.$p.' - '.$arquivo.' - Deletado.';
                                //chdir($local);
                                //unlink($arquivo);
                                
                                //die();
                            }
                        }
                    }
                    ftp_cdup($this->conn);
                    ftp_rmdir($this->conn, $empresa);
                }
                else
                {
                    //ftp_chdir($this->conn, $p);
                    $arquivos = ftp_nlist($this->conn, $p);
                    var_dump($arquivos);die();
                    echo $p;
                    if ( count($arquivos) > 0 )
                    {
                        unset($arquivos[0]);
                        unset($arquivos[1]);
                        ftp_chdir($this->conn, $p);
                        foreach ( $arquivos as $caminho_arquivo )
                        {
                            $arquivo_explodido = explode('/', $caminho_arquivo);
                            $arquivo = $arquivo_explodido[4];
                            //var_dump($arquivo);
                            if ( isset($arquivo) && ! empty($arquivo) && $arquivo !== '.' && $arquivo !== '..' )
                            {
                                //var_dump($arquivo);
                                $array_a = array('.jpg', '.png','.PNG','.gif','.GIF','.JPG', '.jpeg');
                                $array_b = array('','', '', '', '','','');
                                $modificado = str_replace($array_a, $array_b, $arquivo);
                                $arq = explode('_', $modificado);
                                
                                if ( $arq[1] !== $p )
                                {
                                    $filtro_arquivo = 'imoveis.id = "'.$arq[1].'"';
                                    $search = $this->imoveis_model->get_select($filtro_arquivo);
                                    if ( count($search) == 0 )
                                    {
                                        
                                        echo '<br>'.$caminho_arquivo;
                                        ftp_delete($this->conn, $arquivo);
                                        echo '<br>-------------------------------->registro inexistente: '.$arquivo.' - '.$arq[1];
                                        //die();
                                    }
                                    else
                                    {
                                        echo '<br>registro existe: '.$arquivo.' - '.$arq[1];
                                    }
                                    //echo '<br>arquivo: '.$arquivo.' - '.$arq[1];
                                }
                                else
                                {
                                    echo '<br>'.$caminho_arquivo;
                                    ftp_delete($this->conn, $arquivo);
                                    echo '<br>empresa: '.$arquivo.' - '.$arq[1];
                                }
                                
                            }
                        }
                        
                    }
                    else
                    {
                        
                    }
                     
                }
                
                $conta++;
                
                
            }
            
        }
        $this->close_ftp();
    }
    
    
    /**
     * verifica as pastas locais
     * modificada em 25/06/2014 -> 
     */
    public function verifica_image()
    {
        error_reporting(E_ALL);
        header('Content-Type: text/html; charset=UTF-8');
        $this->load->model(array('empresas_model','imoveis_model'));
        $this->conecta_ftp();
        //$conn = $this->conecta_ftp();
        $pastas = $this->ftp->list_files('/thumb/');
        //$endereco = '/home/powimoveis/www/thumb/';
        $conta = 0;
        foreach($pastas as $p)
        {
            $p = str_replace('/thumb/', '', $p);
            
                //var_dump( $p );
            if ( $p !== '.' && $p !== '..' )
            {
                var_dump( $p );
                
             
                $filtro = 'empresas.id = '.$p.' AND pagina_visivel = 1 ';
                $i = $this->empresas_model->get_select($filtro);
                var_dump($i);
                if ( count($i) == 0 )
                {
                    /*
                    $local = $endereco.$p;
                    $arquivos = scandir($local);
                    echo '<br>'.$local;
                    if ( count($arquivos) > 0 )
                    {
                        foreach ( $arquivos as $arquivo )
                        {
                            if (is_file($local.'/'.$arquivo) && $arquivo !== '.' && $arquivo !== '..' )
                            {
                                echo '<br>'.$local.'/'.$arquivo;
                                chdir($local);
                                unlink($arquivo);
                                
                                //die();
                            }
                        }
                    }
                    rmdir($local);
                     * 
                     */
                }
                
                else
                {
                    /*
                    $local = $endereco.$p;
                    $arquivos = scandir($local);
                    echo $local;
                    if ( count($arquivos) > 0 )
                    {
                        foreach ( $arquivos as $arquivo )
                        {
                            if (is_file($local.'/'.$arquivo) && $arquivo !== '.' && $arquivo !== '..' )
                            {
                                $array_a = array('.jpg', '.png','.PNG','.gif','.GIF','.JPG', '.jpeg');
                                $array_b = array('','', '', '', '','','');
                                $modificado = str_replace($array_a, $array_b, $arquivo);
                                $arq = explode('_', $modificado);
                                
                                if ( $arq[1] !== $p )
                                {
                                    $filtro_arquivo = 'imoveis.id = "'.$arq[1].'"';
                                    $search = $this->imoveis_model->get_select($filtro_arquivo);
                                    if ( count($search) == 0 )
                                    {
                                        echo $local;
                                        chdir($local);
                                        unlink($arquivo);
                                        echo '<br>-------------------------------->registro inexistente: '.$arquivo.' - '.$arq[1];
                                    }
                                    else
                                    {
                                        //echo '<br>registro existe: '.$arquivo.' - '.$arq[1];
                                    }
                                    
                                    //echo '<br>arquivo: '.$arquivo.' - '.$arq[1];
                                }
                                else
                                {
                                    echo '<br>'.$local;
                                    chdir($local);
                                    unlink($arquivo);
                                    echo '<br>empresa: '.$arquivo.' - '.$arq[1];
                                }
                                
                            }
                        }
                    }
                    else
                    {
                        
                    }
                     * 
                     */
                }
                
                $conta++;
                
            }
                
            
        }
        //$this->close_ftp();
    }
    
    
    public function limpa_imoveis()
    {
        error_reporting(E_ALL);
        header('Content-Type: text/html; charset=UTF-8');
        $this->load->model(array('empresas_model', 'imoveis_model'));
        $time = time(date('Y-m-d H:i'));
        $filtro = 'empresas.servicos_pagina_termino < "'.$time.'" AND empresas.servicos_pagina = 0 ';
        $empresas = $this->empresas_model->get_itens_qtde_imoveis($filtro);
        if ( count($empresas) > 0 )
        {
            $retorno = '<table>';
            $retorno .= '<tr><th>Sequencia</th><th>id</th><th>Nome Fantasia</th><th>CNPJ</th><th>Qtde de itens</th>';
            //$retorno .= '<th>Qtde deleta</th>';
            $retorno .= '</tr>';
            $sequencia = 1;
            foreach($empresas as $empresa)
            {
                $retorno .= '<tr>';
                $retorno .= '<td>'.$sequencia.'</td>';
                $retorno .= '<td>'.$empresa->id.'</td>';
                $retorno .= '<td>'.$empresa->nome_fantasia.'</td>';
                $retorno .= '<td>'.$empresa->cnpj.'</td>';
                $retorno .= '<td>'.$empresa->qtde_imoveis.'</td>';
                $sequencia++;
                $filtro = 'imoveis.id_empresa = '.$empresa->id;
                $deleta = $this->imoveis_model->excluir($filtro);
                $retorno .= '<td>'.$deleta.'</td>';
                $retorno .= '</tr>';
            }
            $retorno .= '</table>';
            echo $retorno;
        }
                
    }
    
    public function duplicar_contato ( $bloqueio = TRUE )
    {
        if ( ! $bloqueio )
        {
            error_reporting(E_ALL);
            header('Content-Type: text/html; charset=utf-8');
            $this->load->model('imoveis_model');
            $data = $this->imoveis_model->get_item_por_id_empresa(82119);
            if ( isset($data) )
            {
                foreach( $data as $item )
                {
                    $ins = $item;
                    $ins->id_empresa = 82773;
                    $ins->data = time(date('Y-m-d H:i'));
                    unset($ins->thumb1m);
                    unset($ins->fs1, $ins->fs2, $ins->fs3, $ins->fs4, $ins->fs5, $ins->fs6, $ins->fs7, $ins->fs8);
                    unset($ins->id);
                    //var_dump($ins);die();
                    $this->imoveis_model->adicionar($ins);


                    /**
                     * pega do guia
                     * copia pro pow.com.br/powsites/82773/imo/
                     */

                }
            }
        }
    }
            
        
    public function endereco_cidade()
    {
        $this->load->model('cidades_model');
        $itens = $this->cidades_model->get_itens();
        foreach( $itens as $item )
        {
            if ( tira_acento( strtolower($item->nome) ) === 'sao_jose_dos_pinhais' )
            {
                $link = 'http://www.sjp.pr.gov.br';
            }
            else
            {
                $link = 'http://www.'.str_replace(array('_','+'),'',tira_acento(strtolower($item->nome) ) ).'.'.strtolower($item->uf ).'.gov.br';
            }
            $d = file_get_contents($link);
            $data = array('link_prefeitura' => ($d) ? $link : 0 );
            $filtro = array('id' => $item->id );
            $alt = $this->cidades_model->editar($data,$filtro);           
            echo '<br> - '.$item->nome.' - '.$link.' - '.$alt;
            
        }
        
                    
        
    }
    
    public function muda_link_cidade()
    {
        $this->load->model('cidades_model');
        $cidades = $this->cidades_model->get_select();
        foreach ($cidades as $cidade )
        {
            $data = '';
            $filtro;
        }
        //var_sump($cidades);
    }
    
    
    public function testa_retorno($bairro = FALSE)
    {
        $inicio = microtime(TRUE);
        $cidades_json = getcwd().'/application/views/json/cidades';
        $cidades = json_decode(file_get_contents($cidades_json));
        $a = 0;
        foreach($cidades as $cidade)
        {
            $a++;
        }
        $fim = microtime(TRUE);
        $inicio_mongo = microtime(TRUE);
        $this->load->library(array('mongo_db','my_mongo'));
        $this->load->model(array('cidades_mongo_model'));
        $cidades = $this->cidades_mongo_model->get_itens(array());
        $fim_mongo = microtime(TRUE);
        var_dump($inicio,$fim, $a, ( $fim - $inicio));
        echo '<br>'.PHP_EOL;
        var_dump($inicio_mongo,$fim_mongo, $cidades['qtde'], ( $fim_mongo - $inicio_mongo));
//        var_dump($cidades);
        
    }
    
}