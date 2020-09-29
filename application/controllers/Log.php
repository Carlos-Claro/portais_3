<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Log do sistema...
 * @access public
 * @version 1.0
 * @author Carlos Claro
 * @package Index
 * 
 */
class Log extends MY_Controller {

    
     /**
     * construtor da função extendida de MyController
     * Identifica o portal, e set as informações necessárias para o View
     * 
     * @version 1.0
     */
    public function __construct()
    {
        parent::__construct(FALSE);
        $this->benchmark->mark('loadIniciallog_start');
        $this->load->library(array('Mongo_db','my_mongo'));
        $this->load->library(array('lista_normal','encryption'));
        $this->load->helper('cookie');
        $this->benchmark->mark('setCidade_start');
        $this->set_cidade();
        $this->benchmark->mark('setCidade_end');
        $this->print_time('setCidade');
        $this->benchmark->mark('loadIniciallog_end');
        $this->print_time('loadIniciallog');
    }

        /**
     * Estatisticas... log de portal e imovel
     */
    public function set_estatistica()
    {
        $this->benchmark->mark('estatistica_start');
        $post = $this->input->post(NULL, TRUE);
        if ( isset($post['imovel']) )
        {
            $log = $this->set_local($post['origem']);
            $this->set_log_mongo($post['imovel'],$log,$post['tipo'], (isset($post['complemento']) ? $post['complemento'] : [] ));
        }
        else
        {
            $log = $this->set_local($post['origem']);
            $this->set_log_mongo($post['imovel'],$log,$post['tipo'], (isset($post['complemento']) ? $post['complemento'] : [] ));
        }
        $this->benchmark->mark('estatistica_end');
        $this->print_time('estatistica');
        echo $this->soma_time();
    }
    
    public function empresa()
    {
        $this->benchmark->mark('estatistica_start');
        $post = $this->input->post(NULL, TRUE);
        $log = $post['origem'];
        $this->set_log_empresa_mongo($post['id_empresa'],$log,$post['tipo'], (isset($post['complemento']) ? $post['complemento'] : [] ));
        $this->benchmark->mark('estatistica_end');
        $this->print_time('estatistica');
        echo $this->soma_time();
        
    }
    
    public function manifest()
    {
        $tamanhos = [48,96,128,144,152,192,512];
        
        $json = [
            "name"=> 'Encontre seus imoveis em '.$this->cidade->nome.$this->titulo_padrao,
            "short_name"=>$this->cidade->nome.$this->titulo_padrao,
            "icons" => [],
            "start_url"=> ".",
            "theme_color"=> "#285384",
            "background_color"=>"#FFFFFF",
            "orientation"=>"portrait",
            "display"=>"standalone",
            "lang"=> "pt-BR"
        ];
        $nome = str_replace(['tp_','.gif'], '', $this->logo_principal);
        foreach( $tamanhos as $chave => $tamanho )
        {
            $a = 'imagens/'.$nome.'-'.$tamanho.'x'.$tamanho.'.png';
            $json['icons'][$chave] = [
                        "src"=>$a,
                        "type"=>"image/gif",
                        "sizes"=>$tamanho."x".$tamanho
            ];
        }
        header('Content-Type: application/json');
        echo json_encode($json);
    }
    
}    