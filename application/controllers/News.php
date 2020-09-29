<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @access public
 * @version 0.1
 * @author Breno Henrique
 * @package Funções
 * @since 03/09/2014
 * 
 * @copyright (c) 2014, Breno Henrique
 */
class News extends MY_Controller {
    
    public $cidade = array();
    
    public $logo_principal = '';
    
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
        $this->load->model(array('cidades_model'));
        $this->_inicia_geral();
    }
    
    /**
     * Inicia padroes de página e redireciona para o dominio certo.
     * @param string $link_cidade - cidade setada apartir da URL, HTTP_HOST quando NULL
     * @param string $oq - oq setado na url
     * @param string $tipo - tipo setado na url
     * @return $this - devolve default
     */
    public function _inicia_geral()
    {
        $this->cidade = $this->cidades_model->get_item_por_portal( $_SERVER['HTTP_HOST']);
        $this->logo_principal = $this->cidades_model->get_item_logo_por_portal( $this->cidade->id );
        return $this;
    }
    
    public function mail( $oq = NULL )
    {
        if($this->cidade->desconto == 0.45)
        {
            $this->_inicia_geral();
            $data['cidade'] = array('titulo' => $this->cidade->nome, 'link' => $this->cidade->link, 'portal' => $this->cidade->portal, 'logo' => $this->logo_principal);
            $this->layout
                    ->set_includes_3_0()
                    ->set_include('js/3_0/default.js', TRUE)
                    ->set_include('js/3_0/publicidade.js', TRUE)
                    ->set_logo($this->logo_principal)
                    ->set_sobre($this->cidade->sobre, $this->cidade->nome, $this->cidade->portal, $this->cidade->gentilico, $this->cidade->link, $this->cidade->uf, $this->cidade->link_prefeitura)
                    ->view('email/'.$oq, $data,'layout/email'); 
                    //->view('email/corretor_feliz', $data, 'layout/email'); 
        }
    }
    
}

