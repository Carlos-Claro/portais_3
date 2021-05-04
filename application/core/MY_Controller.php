<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class MY_Controller extends CI_Controller 
{	
    public $sessao = NULL;
	
    public function __construct( $verifica_login = FALSE, $log = FALSE ) 	
    {	
        
        if ( isset($_SERVER['HTTP_ACCEPT_ENCODING']) && substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
        {
            ob_start("ob_gzhandler");
        }
        else
        {
            ob_start();
        }
        parent::__construct();	
        $this->benchmark->mark('MyController_start');
        $this->load->library('listagem');
        $this->load->library('pagination');
        $this->load->library('email');
        $this->load->helper('text');
        if ( $verifica_login )		
        {			
            $this->_valida_login();		
        }
        $this->load->library('user_agent');
//        var_dump($this->agent->is_mobile(),$this->agent->mobile(),$this->agent->platform());die();
        $this->is_mobile = $this->agent->is_mobile();
        
        $this->benchmark->mark('MyController_end');
        $this->print_time('MyController');
        if ( strstr($_SERVER['HTTP_USER_AGENT'],'seekport') )
        {
            die('Portais imobiliário.com.br');
            $retorno = TRUE;
        }
        
    }	
    
    
    private $time = array();
    public $tipo_negocio = ['venda','locacao','locacao_dia'];
    public $tipo_negocio_data = ['venda' => 'a Venda','locacao' => 'para Alugar','locacao_dia' => 'para Alugar Temporada'];
    public $campos = [
                            'id' => [
                                    'url' => FALSE, 
                                    'tipoJs' => 'val',       
                                    'filtro' => [
                                        'titulo' => 'Código',
                                        'campo' => 'id',              
                                        'where' => 'where',     
                                        'form' => 'input',       
                                        'tipo' => 'int',        
                                        'itens' => FALSE
                                        ]
                            ],
            'id_empresa'    => ['url' => TRUE,  'tipoJs' => 'val',       'filtro' => ['titulo' => 'Imobiliária',  'campo' => 'id_empresa',       'where' => 'where_in',  'form' => 'input',       'class' => '', 'tipo' => 'string',     'itens' => TRUE]],
            'tipo_negocio'  => ['url' => TRUE,  'tipoJs' => 'attr',      'filtro' => ['titulo' => '',             'campo' => 'tipo_negocio',     'where' => 'where',     'form' => 'tab',         'class' => '', 'tipo' => 'string',     'itens' => TRUE]],
            'tipo'          => ['url' => TRUE,  'tipoJs' => 'attr',      'filtro' => ['titulo' => 'Tipo',         'campo' => 'imoveis_tipos_link','where' => 'where_in',  'form' => 'select2',    'class' => '', 'tipo' => 'string',     'itens' => FALSE],],
            'cidade'        => ['url' => TRUE,  'tipoJs' => 'attr_local','filtro' => ['titulo' => 'Localidade',   'campo' => 'cidade_link',      'where' => 'where',     'form' => 'select2',     'class' => '', 'tipo' => 'string',     'itens' => FALSE],],
            'bairro'        => ['url' => TRUE,  'tipoJs' => 'attr_local','filtro' => ['titulo' => '',             'campo' => 'bairros_link',      'where' => 'where_in',  'form' => 'select2',    'class' => '', 'tipo' => 'string',     'itens' => FALSE],],
            'valor_min'     => ['url' => FALSE, 'tipoJs' => 'val',       'filtro' => ['titulo' => 'Valor',        'campo' => 'preco',            'where' => 'where_gte', 'form' => 'input_group', 'class' => '', 'tipo' => 'double',     'itens' => FALSE],],
            'valor_max'     => ['url' => FALSE, 'tipoJs' => 'val',       'filtro' => ['titulo' => '',             'campo' => 'preco',            'where' => 'where_lte', 'form' => 'input_group', 'class' => '', 'tipo' => 'double',     'itens' => FALSE],],
            'area_min'      => ['url' => FALSE, 'tipoJs' => 'val',       'filtro' => ['titulo' => 'Área',         'campo' => 'area',             'where' => 'where_gte', 'form' => 'input_group', 'class' => '', 'tipo' => 'double',     'itens' => FALSE],],
            'area_max'      => ['url' => FALSE, 'tipoJs' => 'val',       'filtro' => ['titulo' => '',             'campo' => 'area',             'where' => 'where_lte', 'form' => 'input_group', 'class' => '', 'tipo' => 'double',     'itens' => FALSE],],
            'residencial'   => ['url' => FALSE, 'tipoJs' => 'check',     'filtro' => ['titulo' => 'Residencial',  'campo' => 'residencial',      'where' => 'where',     'form' => 'checkbox',    'class' => '', 'tipo' => 'int',        'itens' => [1,2,3,4]]],
            'comercial'     => ['url' => FALSE, 'tipoJs' => 'check',     'filtro' => ['titulo' => 'Comercial',    'campo' => 'comercial',        'where' => 'where',     'form' => 'checkbox',    'class' => '', 'tipo' => 'int',        'itens' => [1,2,3,4]]],
            'condominio'    => ['url' => FALSE, 'tipoJs' => 'check',     'filtro' => ['titulo' => 'Condominio',   'campo' => 'condominio',       'where' => 'where',     'form' => 'checkbox',    'class' => '', 'tipo' => 'string',     'itens' => FALSE],],
            'mobiliado'     => ['url' => FALSE, 'tipoJs' => 'check',     'filtro' => ['titulo' => 'Mobiliado',    'campo' => 'mobiliado',        'where' => 'where',     'form' => 'checkbox',    'class' => '', 'tipo' => 'string',     'itens' => FALSE],],
            'quartos'       => ['url' => TRUE,  'tipoJs' => 'range',     'filtro' => ['titulo' => 'Quartos',      'campo' => 'quartos',          'where' => 'where_in',  'form' => 'checkbox',    'class' => '', 'tipo' => 'int',        'itens' => [1,2,3,4]]],
            'banheiros'     => ['url' => FALSE, 'tipoJs' => 'range',     'filtro' => ['titulo' => 'Banheiros',    'campo' => 'banheiros',        'where' => 'where_in',  'form' => 'checkbox',    'class' => '', 'tipo' => 'int',        'itens' => [1,2,3,4]]],
            'vagas'         => ['url' => FALSE, 'tipoJs' => 'range',     'filtro' => ['titulo' => 'Vagas',        'campo' => 'garagens',         'where' => 'where_in',  'form' => 'checkbox',    'class' => '', 'tipo' => 'int',        'itens' => [1,2,3,4]]],
            'negativos'     => ['url' => FALSE, 'tipoJs' => 'val',       'filtro' => ['titulo' => 'Negativos',    'campo' => '_id',              'where' => 'where_not_in','form' => 'hidden',    'class' => '', 'tipo' => 'int',        'itens' => FALSE]],
        ];
    
    
    public function print_time($tag)
    {
        $t = $this->benchmark->elapsed_time($tag.'_start', $tag.'_end');
        $this->time[$tag] = $t;
        if ( isset($_GET['tempo_']) )
        {
            var_dump($tag . ': '.$t.'s');
        }
        return $t;
    }
    
    public function soma_time($parcial = FALSE)
    {
        $sum = array_sum($this->time);
        if ( $parcial )
        {
            var_dump($this->time);
            var_dump('Tempo total em s: '.array_sum($this->time));
        }
        return $sum;
    }
    
    public $cidade;
    
    /**
     * 
     * @return $this
     */
    public function set_cidade()
    {
        $this->load->model(array('cidades_mongo_model'));
        $url_base = LOCALHOST ? 'https://icuritiba.com/' : base_url();
        
        $this->benchmark->mark('setPortal_start');
        $this->get_item_por_portal($url_base);
        $this->benchmark->mark('setPortal_end');
        $this->print_time('setPortal');
        $url_portal = strtolower(HTTPS ? str_replace('http://www.', 'https://', $this->cidade->portal).'/' : $this->cidade->portal.'/');
        if ( ( $url_base != strtolower($url_portal) ) || ( HTTPS && (strstr($_SERVER['HTTP_HOST'],'www.')) ) ) 
        { 
            if ( $url_base != strtolower($url_portal) )
            {
                redirect($this->cidade->portal, 'location', 301);
                exit();
                
            }
//            var_dump($url_base, $url_portal, HTTPS);die();
            if ( HTTPS )
            {
                redirect(str_replace('http://www.', 'https://', base_url().( ! LOCALHOST ? $_SERVER['REQUEST_URI'] : '')), 'location', 301);
            }
            else
            {
                redirect($this->cidade->portal, 'location', 301);
                exit();
                
            }
        }
//        var_dump($url_portal);die();
        $this->id_cidade = $this->cidade->id;
        $this->facebook = $this->cidade->rs_face;
        $this->plus = $this->cidade->rs_googlemais;
        $this->logo_principal = $this->cidade->topo;
        $this->adwords = $this->cidade->tag_adwords;
        $this->titulo_padrao = ' - '.substr($this->cidade->portal, 11);
        $this->benchmark->mark('setMenu_start');
        $this->menu = $this->get_menu();
        $this->benchmark->mark('setMenu_end');
        $this->print_time('setMenu');
        return $this;
    }
    
    private function get_menu()
    {
         if ( file_exists(getcwd().'/application/views/menus/'.$this->cidade->link) ) 
         {
             $retorno = str_replace('localhost','192.168.1.20',file_get_contents(getcwd().'/application/views/menus/'.$this->cidade->link));
             return $retorno;
         }
         else
         {
//             curl_executavel(base_url().'funcoes/gera_menu_por_cidade?own=1');
         }
    }
    
    private function get_item_por_portal( $url_base )
    {
        $this->cidade = $this->cidades_mongo_model->get_item_por_portal( $url_base );
        if ( ! $this->cidade )
        {
            $this->cidade = $this->cidades_mongo_model->get_item_por_portal( $url_base, TRUE );
            if ( ! $this->cidade ){
                die('Nenhuma cidade encontrada '.$url_base);
            }
        }
        return $this;
    }
    
    
    public function set_local($id)
    {
        switch ( $id )
            {
                case 'formulario':
                    $log = FALSE;
                    break;
                case 'g':
                    $log = 79;
                    break;
                case 5:
                    $log = 37;
                    break;
                case 7 :
                    $log = 70;
                    break;
                case 3:
                    $log = 27;
                    break;
                case 2:
                    $log = 96;
                    break;
                case 8:
                    $log = 103;
                    break;
                case 4:
                default:
                    $log = 36;
            }
        
        return $log;
    }
    
    public function set_log_ligacao($id_imovel, $id_local)
    {
        $i = $this->set_local($id_local);
        $this->set_log_mongo($id_imovel,$i,'ligacao');
    }
    
    public function set_log_ligacao_celular($id_imovel, $id_local)
    {
        $i = $this->set_local($id_local);
        $this->set_log_mongo($id_imovel,$i,'ligacao_celular');
    }
    
    public function set_log_ligacao_whatsapp($id_imovel, $id_local)
    {
        $i = $this->set_local($id_local);
        $this->set_log_mongo($id_imovel,$i,'ligacao_whatsapp');
    }
    
    public function set_log_mongo($id_tabela, $id_local = 22, $tipo = 'views', $complemento = [])
    {
        $this->load->model(array('log_portal_mongo_model','imoveis_mongo_model','locais_mongo_model'));
        $item = $this->imoveis_mongo_model->get_item($id_tabela);
        $local = $this->locais_mongo_model->get_item_por_id($id_local);
        switch ( $tipo )
        {
            case 'views':
                $data['tipo'] = 'lista';
                break;
            case 'clicks':
                $data['tipo'] = 'ficha';
                break;
            default :
                $data['tipo'] = $tipo;
                break;
        }
        $data['id_empresa'] = (int)$item->id_empresa;
        $data['nome_empresa'] = $item->nome_empresa;
        $dat = new DateTime();
        $data['data'] = (new MongoDB\BSON\UTCDateTime($dat));
        $data['id_imovel'] = (int)$id_tabela;
        $data['imoveis_tipos_link'] = $item->imoveis_tipos_link;
        $data['tipo_negocio'] = $item->tipo_negocio;
        $data['preco'] = $item->preco;
        $data['bairros_link'] = $item->bairros_link;
        $data['cidades_link'] = $item->cidades_link;
        $data['ordem'] = (int)$item->ordem;
        $data['id_local'] = $id_local;
        $data['local_nome'] = $local->descricao;
        $data['ip'] = $_SERVER['REMOTE_ADDR'];
        $data['host'] = $_SERVER['HTTP_HOST'];
        $data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        $data['imovel_para'] = $item->imovel_para;
        $data['complemento'] = $complemento;
        $this->load->model(array('log_portal_mongo_model'));
        if ( ! $this->_is_robot() )
        {
            $this->log_portal_mongo_model->adicionar($data);
        }
        
    }
    
    public function set_log_empresa_mongo($id_tabela, $id_local = 22, $tipo = 'views', $complemento = [])
    {
        $this->load->model(array('log_portal_mongo_model','empresas_mongo_model','locais_mongo_model'));
        $item = $this->empresas_mongo_model->get_item($id_tabela);
        $local = $this->locais_mongo_model->get_item_por_id($id_local);
        $data['tipo'] = $tipo;
        $data['id_empresa'] = (int)$item->id;
        $data['nome_empresa'] = $item->descricao;
        $dat = new DateTime();
        $data['data'] = (new MongoDB\BSON\UTCDateTime($dat));
        $data['id_local'] = $id_local;
        $data['local_nome'] = $local->descricao;
        $data['ip'] = $_SERVER['REMOTE_ADDR'];
        $data['host'] = $_SERVER['HTTP_HOST'];
        $data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        $data['complemento'] = $complemento;
        $this->load->model(array('log_portal_mongo_model'));
        if ( ! $this->_is_robot() )
        {
            $this->log_portal_mongo_model->adicionar($data);
        }
    }
    
    public function _is_robot()
    {
        $retorno = FALSE;
        if ( strstr($_SERVER['HTTP_USER_AGENT'],'Googlebot') )
        {
            $retorno = TRUE;
        }
        if ( strstr(strtolower($_SERVER['HTTP_USER_AGENT']),'bot') )
        {
            $retorno = TRUE;
        }
        if ( strstr($_SERVER['HTTP_USER_AGENT'],'baidu') )
        {
            $retorno = TRUE;
        }
        if ( strstr(strtolower($_SERVER['HTTP_USER_AGENT']),'crawler') )
        {
            $retorno = TRUE;
        }
        if ( strstr($_SERVER['HTTP_USER_AGENT'],'seekport') )
        {
            sleep(5);
            $retorno = TRUE;
        }
        return $retorno;
    }
    
    
    
    private function _grava_log()	
    {	
        $this->load->model('registro_ip_model');
        $log = array(
            'ip' 			=> $_SERVER['REMOTE_ADDR'],					
            'url' 			=> ( isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : $_SERVER['REQUEST_URI'] ),					
            'data'			=> date('Y-m-d H:i:s'),					
            'user_agent'                => $_SERVER['HTTP_USER_AGENT'],		
            );		
        $this->registro_ip_model->adicionar($log);	
    }	
    
    private function _get_post( $tipo = NULL )	
    {		
        $retorno = '';
        if ( isset($tipo) && ! empty($tipo) )
        {
            foreach ( $tipo as $c => $v )		
            {			
                if ( is_array($v) )			
                {				
                    foreach ( $v as $key => $value )				
                    {					
                        $retorno .= ', ['.$c.'] ['.$key.'] => '.$value;				
                    }			
                }			
                else			
                {				
                    $retorno .= ', ['.$c.'] => '.$v;			
                }		 
            }
        }
        return $retorno;	
    }	
    private function _valida_login()	
    {		
        $this->sessao = ( $this->session->userdata('login') ) ? $this->session->all_userdata() : NULL;	
        if( ! isset($this->sessao['login'])  )		
        {			
            redirect(base_url().'login/logout','refresh');			
        }	
    }
    public function init_paginacao($total_itens = 0, $url = '', $per_page = NULL) 
    {
        $config = array(			
            'page_query_string'         => TRUE,			
            'base_url' 			=> $url,			
            'total_rows' 		=> $total_itens,			
            'per_page' 			=> isset($per_page) ? $per_page : N_ITENS,	
            'full_tag_open'             => '<ul class="pagination">',
            'full_tag_close'            => '</ul>',
            'first_link'                => '&laquo;',
            'first_tag_open'            => '<li>',
            'first_tag_close'           => '</li>',
            'last_link'                 => '&raquo;',
            'last_tag_open'             => '<li>',
            'last_tag_close'            => '</li>',
            'next_link'                 => '&gt;',
            'next_tag_open'             => '<li>',
            'next_tag_close'            => '</li>',
            'prev_link'                 => '&lt;',
            'prev_tag_open'             => '<li>',
            'prev_tag_close'            => '</li>',
            'cur_tag_open'              => '<li class="active"><span>',
            'cur_tag_close'             => '<span class="sr-only">(current)</span></span></li>',
            'num_tag_open'              => '<li>',
            'num_tag_close'             => '</li>',
            'use_page_numbers'          => FALSE,			
            );
        $this->pagination->initialize($config);
        return $this->pagination->create_links();
    }
    
    public function requisita_bd_sessao() 
    {
        //$this->load->library('database');
        $this->load->database();
    }
    
    public function array_padrao( $data )	
    {		
        $retorno = array(1 => 'a', 2 => 'b', 3 => 'c', 4 => 'd', 5 => 'e', 6 => 'f', 7 => 'g',	8 => 'h', 9 => 'i', 10 => 'j', 11 => 'k', 12 => 'l', 13 => 'm', 14 => 'n', 15 => 'o', 16 => 'p', 17 => 'q', 18 => 'r', 19 => 's', 20 => 't', 21 => 'u', 22 => 'v', 23 => 'x', 24 => 'z', 25 => 'q',	26 => 'r', 27 => 's', 28 => 't', 29 => 'u', 30 => 'v', 31 => 'x');		
        return $retorno[$data];			
        
    }		
    		
    public function set_usuario()		
    {				
        return $this->sessao['usuario'];		
        
    }		
    
    
    public function envio( $data )	
    {
        $keys = json_decode(KEYS);
        if ( ! isset($data['iagente']) )
        {
            $config = (array)$keys->email->pow;
        }
        else
        {
            $config = (array)$keys->email->iagente;
        }
        $config['mailtype'] = 'html';
        $config['useragent'] = 'RededePortaisImobiliarios';
        $config['protocol'] = 'smtp';
        $this->email->initialize($config);
        $date = date('d/m/Y H:i');		
        $to = $data['to'];
        $send['status'] = $this->email
                ->from('envio@powinternet.com.br')
                ->reply_to( (isset($data['reply'][0]) ? $data['reply'][0] : ''), (isset($data['reply'][1]) ? $data['reply'][1] : (isset($data['from']) ? $data['from'] : 'envio@powinternet.com.br') ))
                ->to( $to )
                ->subject( isset($data['assunto']) ? $data['assunto'] : 'Contato do Portal '.( isset($data['portal']) ? $data['portal'] : '' .' | POW Internet') )
                ->message($data['mensagem'])
                ->send();				
        if ( isset($data['retorno']) && $data['retorno'] )
        {
            $send['debugger'] = $this->email->print_debugger();
            $retorno = $send;
        }
        else
        {
            $retorno = $send['status'];
        }	
        return $retorno;
    }
    
    public function upload( $file, $dir = 'images/album/' )	
    {				
        if( $file["name"] )		
        {			
            preg_match("/\.(jpg|png|JPG|pdf|doc){1}$/i",$file["name"],$ext);			
            $nome = explode('.', $file['name']);			
            $name = tira_acento($nome[0]).date('Mhis').'.'.$ext[1];			
            $imagem_dir = getcwd().'/'.$dir.$name;			
            move_uploaded_file( $file["tmp_name"], $imagem_dir) ;			
            $data = $name; 		
            
        }		
        else		
        {			
            $data = NULL;		
            
        }		
        return $data;	
        
    }	
    
       /**
     * Função que trata a variavel antes de entrar no banco de dados
     * @param string $item - o item descrito, pode ser string, int ou decimal
     * @param string $tipo - define qual o tratamento
     * @return string - valor retornado tratado
     */
    public function _trata( $item, $tipo = 'texto' )
    {
        //echo $tipo.' - '.mb_detect_encoding($item).'<br>';
        $item = trim($item);
        switch ( $tipo ) 
        {
            case 'texto':
                $retorno = ( isset($item) ) ? ucfirst( iconv( "UTF-8", "ISO-8859-1//IGNORE", trim($item) ) ) : '' ;
                break;
            case 'numero':
                $retorno = ( isset($item) ) ? $item : 0;
                break;
            case 'decimal':
                $retorno = ( isset($item) && ! empty($item) ) ? number_format($item, 2, '.', '') : 0;
                break;
            case 'texto_sem_acento':
                $retorno = ( isset($item) && ! empty($item) ) ? tira_acento(trim($item)) : '';
                break;
            case 'texto_iso':
                //echo 'Item: '.$item.' - ';
                //var_dump( mb_detect_encoding($item) );
                $retorno = ( isset($item) ) ? ucfirst( iconv(mb_detect_encoding($item), "ISO-8859-1//TRANSLIT", $item ) ) : '' ;
                $retorno = mb_convert_encoding($retorno, 'ISO-8859-1', 'UTF-8');
                //$retorno = html_entity_decode($retorno);
                //echo ' Depois '.$retorno.'<br>';
                break;
             case 'texto_iso_sem_acento':
                $retorno = ( isset($item) ) ? ucfirst( iconv(mb_detect_encoding($item), "ISO-8859-1//IGNORE", trim($item) ) ) : '' ;
                $retorno = tira_acento($retorno);
                break;
        }
        return $retorno;
    }

    
    public function set_menu_por_cidade( $tipo = NULL, $cidade = NULL, $chave = TRUE )
    {
        $this->load->model('tipo_model');
        $itens['url'] = strtolower($cidade->portal).'/[tipo]-[oq]-'.$cidade->link;
//        $url_ = HTTPS ? str_replace('http://www.', 'https://', $itens['url']) : $itens['url'];
        $filtro_venda = $this->filtros_padrao('venda', $cidade);
        $venda['titulo'] = 'Comprar';
        $venda['link'] = 'venda';
        $venda['itens'] = $this->tipo_model->get_item_por_modalidade($filtro_venda, $tipo);
        $itens['venda'] = $venda;
        
        
        $filtro_locacao = $this->filtros_padrao('locacao', $cidade);
        //var_dump($filtro_locacao);
        $locacao['titulo'] = 'Alugar';
        $locacao['link'] = 'locacao';
        $locacao['itens'] = $this->tipo_model->get_item_por_modalidade($filtro_locacao, $tipo);
        $itens['locacao'] = $locacao;
        
        
        
        $filtro_locacao_dia = $this->filtros_padrao('locacao_dia', $cidade);
        $locacao_dia['titulo'] = 'Alugar Dia';
        $locacao_dia['link'] = 'locacao_dia';
        $locacao_dia['itens'] = $this->tipo_model->get_item_por_modalidade($filtro_locacao_dia, $tipo);
        $itens['locacao_dia'] = $locacao_dia;

        return $chave ? $itens : [$venda,$locacao,$locacao_dia];
    }
    
    public function curl_executavel( $endereco, $post = FALSE )
    {
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_HTTPHEADER , array( 'Accept: application/json' ) );
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 100);
        curl_setopt($ch, CURLOPT_URL, $endereco);
        if ( $post )
        {
            curl_setopt($ch,CURLOPT_POST,true);
            curl_setopt($ch,CURLOPT_POSTFIELDS,$post); 
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_TIMEOUT, 240);
        $retorno = curl_exec($ch);
        /*
         * 
         */
        $debug['erro'] = curl_errno( $ch );
        $debug['erromsg']  = curl_error( $ch );
        $debug['info']  = curl_getinfo( $ch );
        if ( isset($_GET['debug']) )
        {
            var_dump($debug);
        }
        curl_close($ch);
	return $retorno;
    }
    
    
    
}
//campolargo.com/index/get_json/tipo/?pow[...ow[valormax]=1850000&pow[quartos]=0&pow[vagas]=0