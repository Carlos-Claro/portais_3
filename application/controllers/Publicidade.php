<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Pagina que gerencia a publicidade
 * @access public
 * @version 1.0
 * @author Carlos Claro
 * @package Index
 * 
 */
class Publicidade extends MY_Controller {
	
    
    public function __construct()
    {
        parent::__construct(FALSE);
        $this->load->database();
        $this->load->model('publicidade_model');
    }
    
    public function index ()
    {
        redirect(base_url().'imoveis');
        exit;
    }

    public function salva_click($item, $tipo = NULL)
    { 
        $item = $this->security->xss_clean($item);
        $salvou = $this->publicidade_model->get_click($item);
        if ( $salvou > 0 )
        {
            $r = TRUE;
        }
        else
        {
            $r = FALSE;
        }
        echo json_encode($r);
    }
    
    public function salva_view($item, $tipo = NULL)
    { 
        $item = $this->security->xss_clean($item);
        $robo = $this->is_robot();
        if ( ! isset($robo) )
        {
            //echo 'nao robot';
            $salvou = $this->publicidade_model->get_view($item, FALSE);
        }
        else
        {
            //echo 'robo';
            $salvou = $this->publicidade_model->get_view($item, TRUE);
        }
        if ( $salvou > 0 )
        {
            $r = TRUE;
        }
        else
        {
            $r = FALSE;
        }
        //echo $_SERVER['REMOTE_ADDR'];
        echo json_encode($r);
    }
    
    public function set_publicidade()
    {
        $this->requisita_bd_sessao();
        $this->load->library(['Mongo_db','my_mongo']);
        $this->load->library(['lista_normal','encryption']);
        $this->load->model(array('publicidade_model'));
        $this->set_cidade();
        $data['publicidade']['lateral_nacional'] = $this->publicidade_model->get_item_por_id(1044);
        $data['publicidade']['atopo'] = ( ! empty($this->cidade->banner_topo) && $this->cidade->banner_topo > 0) ? $this->publicidade_model->get_item_por_id($this->cidade->banner_topo) : NULL;
        $data['publicidade']['meio'] = ( ! empty($this->cidade->banner_meio) && $this->cidade->banner_meio > 0) ? $this->publicidade_model->get_item_por_id($this->cidade->banner_meio) : NULL;
        $data['publicidade']['home'] = ( ! empty($this->cidade->banner_home) && $this->cidade->banner_home > 0) ? $this->publicidade_model->get_item_por_id($this->cidade->banner_home) : NULL;
        $retorno = $this->lista_normal->set_publicidade($data);
        
        
        echo json_encode($retorno);
    }
    
}

