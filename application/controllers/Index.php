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
class Index extends MY_Controller {
	
    /**
     * Guarda informações do portal com base na http_host
     * @var array cidade 
     */
    public $cidade = array();
    
    /**
     * guarda as informações da cidade setada quando o usuario mudar a cidade default
     * @var array cidade_set 
     */
    public $cidade_set = array();
    
    /**
     * Id cidade padrão para usar nas functions
     * @var string id_cidade
     */
    private $id_cidade = array();
    
    /**
     * guarda a logo, setada em _inicia_geral
     * @var string logo_principal 
     */
    private $logo_principal = '';
    
    /**
     * SEO - armazena o titulo padrao no inicia_geral
     * @var string titulo_padrao 
     */
    private $titulo_padrao = '';
    
    /**
     * SEO - armazena a descricao da pagina 
     * @var string description 
     */
    private $description = '';
    
    /**
     * SEO - armazena o titulo e é alimentado no decorrer do código
     * @var type 
     */
    private $titulo = '';
    
    /**
     * Setada no inicia_geral e armazena o q busca no inicio do código 
     * @var srray oq_set 
     */
    private $oq_set = '';
    
    /**
     * Armazena o tipo de imovel buscado para uso em functions
     * @var array tipo 
     */
    private $tipo = '';
    
    private $plus = '';
    private $facebook = '';
    private $link_bairro_visitados = array();
    
    /**
     * __construct
     * inicia banco de dados e library, models padrão
     */
    public function __construct()
    {

        parent::__construct(FALSE);
        $this->benchmark->mark('code_start');
        $this->load->library(array('Mongo_db','my_mongo'));
        $this->load->library(array('lista_normal'));
        $this->load->helper('cookie');
        $this->benchmark->mark('code_end');
        if ( isset($_GET['tempo_']) )
        {
            var_dump('Loading inicial: '.$this->benchmark->elapsed_time('code_start', 'code_end').'s');
            
        }
    }
    
    
    
    public function set_menu()
    {
        $dominio = $this->cidade->link;
        $menu = str_replace('localhost','192.168.1.20',file_get_contents(getcwd().'/application/views/menus/'.$dominio));
        return $menu;
    }
    
    /**
     * seta função principal
     * 
     * Ex de uri:
     *      imoveis-curitiba_pr
     *      imoveis-venda-curitiba_pr
     *      imoveis-venda-curitiba_pr-campo_comprido
     *      imoveis-venda-curitiba_pr-campo_comprido
     *      imoveis_locacao_curitiba+pr
     *      apartamentos_curitiba_pr_campo+comprido-santa+candida
     *      apartamentos-lote+terreno_curitiba+pr_campo+comprido-santa+candida
     *      apartamentos_venda_curitiba+pr
     *      imoveis_corteze_curitiba+pr
     *      imoveis_corteze_venda_curitiba+pr
     *      apartamentos_corteze_venda_curitiba+pr
     * Ex de complemento:
     *      [itens]={'tipo':'apartamento,casa', 'tipo_negocio':'venda', etc...};
     * 
     */
    public function index ( $uri = NULL, $A = FALSE, $B = FALSE )
    {
        $data = array();
        $this->set_redirect();
        if ( isset($uri) )
        {
            $valores = $this->get_valores_url($uri);
            $data['itens'] = $this->pesquisa($valores,'return');
        }
        else
        {
            $this->_inicia_geral();
        }
        if( $this->cidade->nome !== $this->cidade_set->nome )
        {
            redirect( base_url());
            $layout->set_robot(FALSE);
        }
        $titulo = isset($data['itens']['h1']) ? $data['itens']['h1'] : 'Imóveis em '.$this->cidade->nome.': Encontre aqui seu imóvel - '. strtolower(substr($this->cidade->portal, 11));
        $description = isset($data['itens']['description']) ? $data['itens']['description'] : 'No '. strtolower(substr($this->cidade->portal, 11)).' você encontra diversas opções de imóveis para compra, venda e locação: Apartamentos, Casas, terrenos e mais. Confira!';
        $menu = $this->set_menu();
        $cidade = $this->cidade;
        $data['tipo_negocio_array'] = array('venda' => 'Comprar','locacao' => 'Alugar','locacao_dia' => 'Alugar Dia');
        $data['cidade_set'] = $this->cidade_set;
        $data['bairro'] = $this->_set_bairro($data);
        $layout = $this->layout
                ->set_menu($menu)
                ->set_logo( $this->logo_principal )
                ->set_include('js/3_3/jquery.mobile.touch.min.js', TRUE)
                ->set_titulo($titulo)
                ->set_description($description)
                ->set_header_extra($this->cidade->header_tag)
                ->set_sobre($cidade->sobre, $cidade->nome, $cidade->portal, $cidade->gentilico, $cidade->link, $cidade->uf, $cidade->link_prefeitura,1 )
                ->set_analytics($this->cidade->instrucoes_head)
                ->set_google_tag($this->cidade->google_tag);
        
        if ( LOCALHOST )
        {
            $layout
                    ->set_include('css/3_3/lista.css', TRUE)
                    ->set_include('js/3_3/filtro.js', TRUE);
        }
        else
        {
            $layout
                    ->set_include('css/3_3/lista.min.css', TRUE)
                    ->set_include('js/3_3/filtro.min.js', TRUE);
        }
        $layout
                ->view('filtro', $data, 'layout/layout'); 
    }
    
    
    private function _set_bairro($data)
    {
        $retorno['cidade_marcado'] = ( isset($data['itens']['valores']['cidade']) && ! empty($data['itens']['valores']['cidade']) ) ? $data['itens']['valores']['cidade'] : $data['cidade_set']->link;
        $bairros_json = file_get_contents(getcwd().'/application/views/json/bairros/'.$retorno['cidade_marcado']);
        $bairros = json_decode($bairros_json);
        $retorno['item'] = '';
        if ( isset($data['itens']['valores']['bairro']) && ! empty($data['itens']['valores']['bairro']) && count($data['itens']['valores']['bairro']) > 0 )
        {
            $b_marcado = $data['itens']['valores']['bairro'];
        }
        else
        {
            $b_marcado = array();
        }
        foreach( $bairros as $bairro )
        {
            $marcado = FALSE;
            if ( ( in_array($bairro->id, $b_marcado) ) )
            {
                $marcado = TRUE;
                $retorno['item'] .= '<option value="'.$bairro->id.'" '.( $marcado ? 'selected="selected"' : '').'>'.$bairro->descricao.'</option>'; 
            }
        }
        $retorno['id'] = count($b_marcado) > 0 ? implode(',', $b_marcado) : '';
        return $retorno;
    }
    
    private function _set_breadcrumb ( $get, $id_empresa = NULL )
    {
        $retorno = '<li><a href="'.base_url().'"><img alt="Voltar para a Página Inicial do Portal Imóveis Curitiba" src="'.base_url().'images/icones/imovel.png"></a></li>';
        if ( isset($id_empresa) )
        {
            $this->requisita_bd_sessao();
            $this->load->model('empresas_model');
            $empresa = $this->empresas_model->get_item_por_id( $id_empresa );
            $link_empresa = 'imobiliaria/'.urlencode( tira_especiais($empresa->nome_fantasia) ).'-'.$empresa->id.'/';
            $retorno .= '<li>'
                    . '<a href="'.base_url().$link_empresa.'">'
                    . ''.$empresa->nome_fantasia.''
                    . '</a>'
                    . '</li>';
        }
        if ( isset($get['valores']['cidade']) )
        {
            
            $retorno .= '<li>'
                    . '<a href="'.base_url().( isset($empresa) ? $link_empresa : '' ).'imoveis-'.$get['valores']['cidade'].'">'
                    . ''.$this->cidades_mongo_model->get_nome_por_link($get['valores']['cidade']).''
                    . '</a>'
                    . '</li>';
            $cidade = TRUE;
        }
        if ( isset($get['valores']['tipo_negocio']) )
        {
            
            $retorno .= '<li>'
                    . '<a href="'.base_url().( isset($empresa) ? $link_empresa : '' ).'imoveis-'
                    . $get['valores']['tipo_negocio'].'-'
                    . ''.( isset($cidade) ? $get['valores']['cidade'].'' : '')
                    . '">'
                    . ucfirst($get['valores']['tipo_negocio'])
                    . '</a>'
                    . '</li>';
            $tipo_negocio = TRUE;
            
        }
        
        if ( isset($get['valores']['tipo'][0]) && ! empty($get['valores']['tipo'][0]) )
        {
            $arquivo_tipo = getcwd().'/application/views/json/tipo';
            $tipos_json = json_decode(file_get_contents($arquivo_tipo));
            if ( is_array($get['valores']['tipo']) ) 
            {
                foreach ( $get['valores']['tipo'] as $tipo )
                {
                    $tipos = $tipos_json->{$tipo};
                    $retorno .= '<li>'
                            . '<a href="'.base_url().( isset($empresa) ? $link_empresa : '' ).''.$tipos->id.'-'
                            .''.(isset($tipo_negocio) ? $get['valores']['tipo_negocio'].'-' : '')
                            . ''.( isset($cidade) ? $get['valores']['cidade'] : '')
                            .'">'
                            . $tipos->descricao
                            . '</a>'
                            . '</li>';
                }
                
            }
            else
            {
                    $tipos = $tipos_json->{$get['valores']['tipo']};
                    $retorno .= '<li>'
                            . '<a href="'.base_url().( isset($empresa) ? $link_empresa : '' ).''.$tipos->id.'-'
                            .''.(isset($tipo_negocio) ? $get['valores']['tipo_negocio'].'-' : '')
                            . ''.( isset($cidade) ? $get['valores']['cidade'] : '')
                            .'">'
                            . $tipos->descricao
                            . '</a>'
                            . '</li>';
                
            }
            
            $tipo = TRUE;
            
        }
        if ( isset($get['valores']['bairro']) && ! empty($get['valores']['bairro']) )
        {
            $arquivo_bairro = getcwd().'/application/views/json/bairros/'.$get['valores']['cidade'];
            $bairros_json = json_decode(file_get_contents($arquivo_bairro));
            foreach ( $get['valores']['bairro'] as $bairro )
            {
                $bairros = $bairros_json->{$bairro};
                $retorno .= '<li>'
                        . '<a href="'.base_url().( isset($empresa) ? $link_empresa : '' ).( isset($tipo) && $tipo ? implode('+',$get['valores']['tipo']).'-' : 'imoveis-')
                        .''.(isset($tipo_negocio) ? $get['valores']['tipo_negocio'].'-' : '')
                        . ''.$get['valores']['cidade'].'-'
                        . $bairros->id
                        .'">'
                        . $bairros->descricao
                        . '</a>'
                        . '</li>';
                
            }
            
            
        }
        return $retorno;
    }
    
    public function pesquisa($valores = NULL, $tipo_retorno = 'echo')
    {
        $this->load->model(array('imoveis_mongo_model','log_pesquisa_mongo_model'));
        $get = $this->_get($valores);
        $valores = $get['valores'];
//        $log_pesquisa = $valores;
//        $log_pesquisa['date'] = $this->log_pesquisa_mongo_model->set_date();
//        $log_pesquisa['ip'] = $_SERVER['REMOTE_ADDR'];
//        $this->log_pesquisa_mongo_model->adicionar($log_pesquisa);
        $this->_inicia_geral(isset($valores['cidade']) ? $valores['cidade'] : NULL);
        $retorno['url'] = $get['url'];
        $filtro = $this->_inicia_filtros($get['url'],$valores, $get['coluna'], $get['ordem']);
//        var_dump($filtro->get_filtro());die();
        $imoveis = $this->imoveis_mongo_model->get_itens($filtro->get_filtro(),$get['coluna'],$get['ordem'],$get['per_page'],20);
//        var_dump($imoveis);die();
        $total = $this->imoveis_mongo_model->get_total_itens($filtro->get_filtro());
        $retorno['itens'] = '';
        if ( $total > 0 )
        {
            $contador_publicidade = 0;
            $sequencia_publicidade = 0;
            foreach( $imoveis['itens'] as $imovel )
            {
                
                if ( ! $contador_publicidade || ! ( $contador_publicidade % 4)  )
                {
                    $sequencia_publicidade++;
                    $retorno['itens'] .= '<li class="hide media list-group-item publicidade publicidade-'.$sequencia_publicidade.'">espaço publicidade</li>';
                }
                if ( ! $contador_publicidade )
                {
                    $retorno['itens'] .= '<li class="hide media list-group-item destaques">espaço destaques</li>';
                    
                }
                $retorno['itens'] .= $this->lista_normal->_set_item_vertical($imovel);
                $contador_publicidade++;
            }
        }
        else
        {   
            $retorno['itens'] = '<li class="list-group-item">Não localizamos imóveis com a busca que realizou! <br> Refaça sua pesquisa utilizando outros filtros ou <a href="'.base_url().'encontre">deixe que encontramos para você clicando aqui.</a></li>';
        }
        $ordem = $this->_set_ordenacao_qtdes($get['url'], $this->oq, $get['coluna'], $get['ordem'], $get['per_page']);
        $retorno['ordem'] = $this->lista_normal->get_ordenacao($ordem);
        $retorno['link_proximo']['url'] = $ordem['link_proximo'];
        $retorno['link_proximo']['existe'] = ( $total > ( $get['per_page'] + 20 ) );
        $retorno['link_proximo']['atual'] = $total < $get['per_page'] ? $total : ( $get['per_page'] + 20 );
        $retorno['total'] = $total;
        $retorno['h1'] = $filtro->get_titulo();
        $retorno['description'] = $filtro->get_description();
        $retorno['valores'] = $valores;
        $retorno['breadcrumb'] = $this->_set_breadcrumb($get, (isset($valores['id_empresa']) ? $valores['id_empresa'] : NULL) );
        $retorno['google_tags'] = $this->_set_tags();
        // $tipo_retorno == 'echo'
        if ( $this->input->is_ajax_request() )
        {
//            echo json_encode($retorno, JSON_PARTIAL_OUTPUT_ON_ERROR);
            echo json_encode($retorno, JSON_HEX_TAG |JSON_HEX_AMP|JSON_HEX_QUOT|JSON_UNESCAPED_UNICODE|JSON_HEX_APOS);
            exit();
        }
        else
        {
            return $retorno;
        }
    }
    
    private function _set_tags()
    {
        $retorno = isset($this->cidade->google_tag) ? $this->cidade->google_tag : '';
        $retorno .= isset($this->cidade->instrucoes_head) ? $this->cidade->instrucoes_head : '';
        return $retorno;
    }
    
    /**
     * Monta array para montagem do campo ordenação, baseado nos defaults de inicia geral + paramentros
     * @param string $url - get gerado pelo filtro para inserir no fim do link
     * @param array $oq - array com o oq selecionado, base para ordem valor, tipo e campos
     * @param string $cidade - cidade setada
     * @param string $coluna - coluna setada
     * @param string $ordem - ordem setada
     * @param string $qtde - qtde de itens atual
     * @param string $function - function que envia a solicitação de página
     * @return array - [ordenacao] , [qtdes]
     */
    private function _set_ordenacao_qtdes($url, $oq =  NULL, $coluna = NULL, $ordem = NULL, $per_page = 0)
    {
        $retorno['ordenacao'] = array(
                                    array('titulo' => 'Relevância',     'tipo' => '&coluna=ordem&ordem=DESC',                                                                                                       'url' => (strstr($url,'?') ? '' : '?' ).$url.'&coluna=ordem&ordem=DESC', 'active' => ( isset($coluna) && $coluna == 'ordem' ) ? 1 : 0  ),
                                    array('titulo' => 'Menor Valor',    'tipo' => '&coluna='.( ( isset($oq['selecionado']) && $oq['selecionado']) ? $oq['selecionado']->valor_campo : 'preco' ).'&ordem=ASC',     'url' => (strstr($url,'?') ? '' : '?' ).$url.( ( isset($oq['selecionado']) && $oq['selecionado']) ? '&coluna='.$oq['selecionado']->valor_campo : '&coluna=preco').'&ordem=ASC' , 'active' => ( isset($coluna) && isset($oq['selecionado']) && $coluna == $oq['selecionado']->valor_campo && $ordem == 'ASC' ) ? 1 : 0 ),
                                    array('titulo' => 'Maior Valor',    'tipo' => '&coluna='.( ( isset($oq['selecionado']) && $oq['selecionado']) ? $oq['selecionado']->valor_campo : 'preco' ).'&ordem=DESC',    'url' => (strstr($url,'?') ? '' : '?' ).$url.( ( isset($oq['selecionado']) && $oq['selecionado']) ? '&coluna='.$oq['selecionado']->valor_campo : '&coluna=preco').'&ordem=DESC' , 'active' => ( isset($coluna) && isset($oq['selecionado']) && $coluna == $oq['selecionado']->valor_campo && $ordem == 'DESC' ) ? 1 : 0),
                                    array('titulo' => 'Bairro A-Z',     'tipo' => '&coluna=bairros_link&ordem=ASC',                                                                                                   'url' => (strstr($url,'?') ? '' : '?' ).$url.'&coluna=bairros_link&ordem=ASC', 'active' => ( isset($coluna) && $coluna == 'bairros_link' && $ordem == 'ASC' ) ? 1 : 0),
                                    array('titulo' => 'Bairro Z-A',     'tipo' => '&coluna=bairros_link&ordem=DESC',                                                                                                  'url' => (strstr($url,'?') ? '' : '?' ).$url.'&coluna=bairros_link&ordem=DESC', 'active' => ( isset($coluna) && $coluna == 'bairros_link' && $ordem == 'DESC' ) ? 1 : 0),
                                    array('titulo' => 'Menor Área Útil','tipo' => '&coluna=area_util&ordem=ASC',                                                                                                      'url' => (strstr($url,'?') ? '' : '?' ).$url.'&coluna=area_util&ordem=ASC', 'active' => ( isset($coluna) && $coluna == 'area_util' && $ordem == 'ASC' ) ? 1 : 0),
                                    array('titulo' => 'Maior Área Útil','tipo' => '&coluna=area_util&ordem=DESC',                                                                                                     'url' => (strstr($url,'?') ? '' : '?' ).$url.'&coluna=area_util&ordem=DESC', 'active' => ( isset($coluna) && $coluna == 'area_util' && $ordem == 'DESC' ) ? 1 : 0),
                                    );
        $retorno['link_proximo'] = $url.'&coluna='.$coluna.'&ordem='.$ordem.'&per_page='.($per_page + 20);
        /*
        $retorno['qtdes'] = array(
                                array('titulo' => '20',     'tipo' => '20',     'url' => base_url().$function.'/'.(isset($cidade) ? $cidade : 0).'/'.( isset($this->oq_set) && ! empty($this->oq_set) ? $this->oq_set : 0 ).'/'.((isset($this->tipo) && !empty($this->tipo)) ? implode('+', $this->tipo) : 0).'/'.((isset($bairro) && !empty($bairro)) ? implode('+',$bairro) : 0).'/'.((isset($caracteristicas) && !empty($caracteristicas)) ? $caracteristicas : 0).(strstr($url,'?') ? '' : '?' ).$url.'&coluna='.$coluna.'&ordem='.$ordem.'&qtde=10', 'active' => ( $qtde == 20 ) ? 1 : 0  ),
                                array('titulo' => '50',     'tipo' => '50',     'url' => base_url().$function.'/'.(isset($cidade) ? $cidade : 0).'/'.( isset($this->oq_set) && ! empty($this->oq_set) ? $this->oq_set : 0 ).'/'.((isset($this->tipo) && !empty($this->tipo)) ? implode('+', $this->tipo) : 0).'/'.((isset($bairro) && !empty($bairro)) ? implode('+',$bairro) : 0).'/'.((isset($caracteristicas) && !empty($caracteristicas)) ? $caracteristicas : 0).(strstr($url,'?') ? '' : '?' ).$url.'&coluna='.$coluna.'&ordem='.$ordem.'&qtde=50', 'active' => ( $qtde == 50 ) ? 1 : 0  ),
                                array('titulo' => '100',    'tipo' => '100',    'url' => base_url().$function.'/'.(isset($cidade) ? $cidade : 0).'/'.( isset($this->oq_set) && ! empty($this->oq_set) ? $this->oq_set : 0 ).'/'.((isset($this->tipo) && !empty($this->tipo)) ? implode('+', $this->tipo) : 0).'/'.((isset($bairro) && !empty($bairro)) ? implode('+',$bairro) : 0).'/'.((isset($caracteristicas) && !empty($caracteristicas)) ? $caracteristicas : 0).(strstr($url,'?') ? '' : '?' ).$url.'&coluna='.$coluna.'&ordem='.$ordem.'&qtde=100', 'active' => ( $qtde == 100 ) ? 1 : 0  ),
                                );
         * 
         */
        $retorno['qtdes'] = array();
        return $retorno;
    }
    

    /**
     * Classe que inicia os filtros da pagina principal
     * @param string $url
     * @param array $valores
     * @return object
     */
    private function _inicia_filtros($url = '', $valores = array(), $coluna = NULL, $ordem = NULL  )
    {
        $config['separador'] = '/';
        $config['url_mascara'] = $url;
        $valores['id_empresa'] = (isset($valores['id_empresa']) && ! empty($valores['id_empresa']) ) ? $valores['id_empresa'] : NULL;
        if( isset($valores['id_empresa'])  ) 
        {
            $empresa = TRUE;
            if ( ! isset($valores['cidade']) ) 
            { 
                $valores['cidade'] = NULL; 
            }
        } 
        else  
        { 
            $valores['cidade'] = isset($valores['cidade']) ? $valores['cidade'] : NULL;  
        } 
        $log['cidade'] = $valores['cidade'];
        if ( isset($valores['tipo_negocio']) && ! empty($valores['tipo_negocio']) && $valores['tipo_negocio'] != '0' )
        {
            $oq = $this->_set_oq($valores['tipo_negocio'], ( isset($valores['id_empresa']) ? $valores['id_empresa'] : NULL ) );
            $valores['oq'] = array($oq['selecionado']->id);
            $log['negocio'] = $oq['selecionado']->id;
            $filtro_links[] = $oq['selecionado']->id.' = 1';
            $config['oq'] = $oq['selecionado']->id;
            $filtro_prox['oq'] = $oq['selecionado']->id;
            $campo_preco = 'preco_'.$oq['selecionado']->id;
        }
        else
        {
            $campo_preco = 'preco';
            $oq = $this->_set_oq();
        }
        $this->oq = $oq;
        if ( isset( $valores['bairro'] ) && ! empty($valores['bairro']) && $valores['bairro'] != '0' )
        {
//            $this->load->model('bairros_proximidade_mongo_model');
//            $proximidade = $this->bairros_proximidade_mongo_model->get_item_por_link($valores['bairro'][0]);
//            if ( isset($proximidade) )
//            {
//                unset($valores['bairro'][0]);
//                $links = explode('+',$proximidade->link_bairros);
//                array_push($valores['bairro'],$links);
//            }
            $log['bairros'] = $valores['bairro'];
            $filtro_links[] = 'bairros.link != ""';
            $filtro_prox['bairro'] = $valores['bairro'];
            if ( ! empty($valores['bairro'][0])  )
            {
                $config['itens'][] = array( 'name' => 'bairro',  'where' => array( 'tipo' => 'where_in', 	'campo' => 'bairros_link',  'valor' => '' ) );
            }
        }
        $log['valor_min'] = isset($valores['valor_min']) ? $valores['valor_min'] : NULL;
        $log['valor_max'] = isset($valores['valor_max']) ? $valores['valor_max'] : NULL;
        if ( isset($valores['valor_max']) && ( $valores['valor_max'] == 10000000 || $valores['valor_max'] == 20000 ) )
        {
            unset($valores['valor_max']);
        }
        //$valores = array_merge($valores,$padrao['valores']);
        //var_dump($valores['caracteristicas']);
        $caracteristicas_selecionadas = $this->_set_selecionado('caracteristicas', isset($valores['caracteristicas']) ? $valores['caracteristicas'] : NULL);
        //var_dump($caracteristicas_selecionadas);
        if ( isset($caracteristicas_selecionadas) && count($caracteristicas_selecionadas) > 0  )
        {
            $complemento_titulo_caracteristicas = '';
            foreach( $caracteristicas_selecionadas as $conta => $c_s )
            {
                $complemento_titulo_caracteristicas .= ( $conta ? ', ' : ' ' ).$c_s->descricao;
            }
            $complemento_titulo_caracteristicas .= ', ';
        }
        $config['itens'][] = array( 'name' => 'id_empresa','where' => array( 'tipo' => 'where', 'campo' => 'id_empresa',   'valor' => '' ) );
        if ( isset($oq['selecionado']) )
        {
            $config['itens'][] = array( 'name' => 'oq',     'where' => array( 'tipo' => 'where_in', 	'campo' => 'imovel_para',   'valor' => '' ) );
        }
        $config['itens'][] = array( 'name' => 'tipo',   'where' => array( 'tipo' => 'where_in', 	'campo' => 'imoveis_tipos_link',       'valor' => '' ) );
        //$config['itens'][] = array( 'name' => 'uf', 'where' => array( 'tipo' => 'where',                'campo' => 'cidades.uf',     'valor' => '' ) );
        $config['itens'][] = array( 'name' => 'cidade', 'where' => array( 'tipo' => 'where',            'campo' => 'cidades_link',     'valor' => '' ) );
        if ( isset($valores['valor_min']) && ! empty($valores['valor_min']) && $valores['valor_min'] > 0 ) 
        {
            $config['itens'][] = array( 'name' => 'valor_min','where' => array( 'tipo' => 'where_gte', 	'campo' => $campo_preco, 'valor' => '' ));
        }
        else
        {
            if ( $coluna == 'preco' )
            {
                $valores['valor_min'] = 1;
                $config['itens'][] = array( 'name' => 'valor_min','where' => array( 'tipo' => 'where_gte', 	'campo' => $campo_preco, 'valor' => '' ));
            }
        }
        if ( isset($valores['valor_max']) && ($valores['valor_max'] !== '10000000' && $valores['valor_max'] !== '20000' ) ) 
        {
            $config['itens'][] = array( 'name' => 'valor_max','where' => array( 'tipo' => 'where_lte', 	'campo' => $campo_preco, 'valor' => '' ));
        }
        else
        {
            if ( $coluna == 'preco' )
            {
                $valores['valor_max'] = 1;
                $config['itens'][] = array( 'name' => 'valor_max','where' => array( 'tipo' => 'where_gte', 	'campo' => $campo_preco, 'valor' => '' ));
            }
        }
        if ( isset($valores['area_min']) && ! empty($valores['area_min']) && $valores['area_min'] > 0 ) 
        {
            $config['itens'][] = array( 'name' => 'area_min','where' => array( 'tipo' => 'where_gte', 	'campo' => 'area_util', 'valor' => '' ));
        }
        else
        {
            if ( $coluna == 'area_util' )
            {
                $valores['area_min'] = 1;
                $config['itens'][] = array( 'name' => 'area_min','where' => array( 'tipo' => 'where_gte', 	'campo' => 'area_util', 'valor' => '' ));
            }
        }
        if ( isset($valores['area_max']) ) 
        {
            $config['itens'][] = array( 'name' => 'area_max','where' => array( 'tipo' => 'where_lte', 	'campo' => 'area_util', 'valor' => '' ));
        }
        else
        {
            if ( $coluna == 'area_util' )
            {
                $valores['area_max'] = 1;
                $config['itens'][] = array( 'name' => 'area_max','where' => array( 'tipo' => 'where_gte', 	'campo' => 'area_util', 'valor' => '' ));
            }
        }
        $config['itens'][] = array( 'name' => 'mobiliado','where' => array( 'tipo' => 'where', 	'campo' => 'mobiliado',     'valor' => '' ));
        $config['itens'][] = array( 'name' => 'comercial','where' => array( 'tipo' => 'where', 	'campo' => 'comercial',     'valor' => '' ));
        $config['itens'][] = array( 'name' => 'residencial','where' => array( 'tipo' => 'where','campo' => 'residencial',   'valor' => '' ));
        $config['itens'][] = array( 'name' => 'aceito_troca','where' => array( 'tipo' => 'where','campo' => 'troca',        'valor' => '' ));
        $config['itens'][] = array( 'name' => 'condominio','where' => array( 'tipo' => 'where','campo' => 'condominio',        'valor' => '' ));
        $config['itens'][] = array( 'name' => 'mobiliado','where' => array( 'tipo' => 'where','campo' => 'mobiliado',        'valor' => '' ));
        $config['itens'][] = array( 'name' => 'quartos','where' => array( 'tipo' => 'where_in','campo' => 'quartos',        'valor' => '' ));
        $config['itens'][] = array( 'name' => 'banheiros','where' => array( 'tipo' => 'where_in','campo' => 'banheiros',        'valor' => '' ));
        $config['itens'][] = array( 'name' => 'vagas','where' => array( 'tipo' => 'where_in','campo' => 'garagens',        'valor' => '' ));
        //$config['itens'][] = array( 'name' => 'id',     'where' => array( 'tipo' => 'where', 	'campo' => 'imoveis.id',            'valor' => '' ));
        $config['itens'][] = array( 'name' => 'id_negativo','where' => array( 'tipo' => 'where_not_in','campo' => '_id',     'valor' => '' ));
        //$config['itens'] = array_merge($config['itens'], $padrao['itens']);
        $config['colunas'] = 2;
        $config['extras']['titulo'] = '';
        $config['extras']['description'] = '';
        if ( isset($oq['selecionado']->descricao_titulo) )
        {
            
            $config['extras']['titulo'] .= 'Imóveis '.(isset($empresa) ? '[empresa] ' : '').( isset($complemento_titulo_caracteristicas) ? $complemento_titulo_caracteristicas : '' ).$oq['selecionado']->descricao_description.( isset($valores['cidade']) ? ' em '.$this->cidade_set->nome.' - '.$this->cidade_set->uf : '' );
            $oq_description = ' '.$oq['selecionado']->descricao_description;
        }
        else
        {
            $config['extras']['titulo'] .= 'Encontre o seu imóvel '.(isset($empresa) ? '[empresa] ' : '').( isset($valores) ? ( isset($valores['cidade']) ? ' em '.$this->cidade_set->nome.' - '.$this->cidade_set->uf : '' ) : ' no '.substr($this->cidade->portal, 11));
            $oq_description = '';
        }
        $ti = '';
        $arquivo_tipo = getcwd().'/application/views/json/tipo';
        $tipos_json = json_decode(file_get_contents($arquivo_tipo));
        if( isset($valores['tipo']) && ! empty($valores['tipo']) )
        {
            $ti .= '';
            $t = 0;
            $de = '';
            $qtde_tipo = count($valores['tipo']);
            $valores['tipo'] = is_array($valores['tipo']) ? $valores['tipo'] : array($valores['tipo']);
            foreach( $valores['tipo'] as $tipo)
            {
                if ( $t == 0 )
                {
                    $separador = '';
                }
                elseif( ( $t > 0 ) && ( $t == ($qtde_tipo - 1) ) )
                {
                    $separador = ' ou ';
                }
                else
                {
                    $separador = ', ';
                }
                foreach ( $tipos_json as $tipo_json )
                {
                    if ( $tipo == $tipo_json->id )
                    {
                        $tipo_description_titulo = $tipo_json->plural;
                        $tipo_description_description = $tipo_json->descricao;
                        $ti .= $separador.ucfirst($tipo_description_titulo);
                        $de .= $separador.ucfirst($tipo_description_description);
                        $t++;
                        
                    }
                }
            }
            $tem_tipo = TRUE;
            $config['extras']['description'] = 'Procurando '.  strtolower($de).( isset($complemento_titulo_caracteristicas) ? $complemento_titulo_caracteristicas : '' ).( ! empty($oq_description) ? $oq_description : ' para comprar ou alugar' ).( isset($valores['cidade']) ? ' em '.$this->cidade_set->nome.' - '.$this->cidade_set->uf : '' ).'? Confira as opções disponíveis perfeitas para você: área útil, dormitórios, garagens e muito mais.';
            $config['extras']['titulo'] = $ti.( isset($complemento_titulo_caracteristicas) ? $complemento_titulo_caracteristicas : '' ).(isset($empresa) ? ' [empresa]' : '').' '.( isset($oq['selecionado']->descricao_description) ? $oq['selecionado']->descricao_description : '').( isset($valores['cidade']) ? ' em '.$this->cidade_set->nome.' - '.$this->cidade_set->uf : '' );
            
        }
        else
        {
            $config['extras']['description'] = 'Acesse e encontre as melhores opções de imóveis'.(isset($empresa) ? '[empresa] ' : '').( isset($de) ? $de : '').$oq_description.( isset($complemento_titulo_caracteristicas) ? $complemento_titulo_caracteristicas : '' ).( isset($valores['cidade']) ? ' em '.$this->cidade_set->nome.'. O imóvel em '.$this->cidade_set->nome.' - '.$this->cidade_set->uf : ' no Brasil. O imóvel' ).' que você procura, está aqui!';
        }
        if ( ( isset($valores['bairro']) && !empty($valores['bairro']) && count($valores['bairro']) < 2 ) || isset($valores['proximidade']) )
        {
            if ( isset($proximidade) )
            {
//                $this->requisita_bd_sessao();
                $this->load->model('bairros_proximidade_mongo_model');
                $prox = $this->bairros_proximidade_mongo_model->get_nome_por_link($valores['proximidade']);
                $l_bairro = ' Proximo '.$prox;
                $tem_no = FALSE;
            }
            else
            {
                $tem_no = TRUE;
                $ba = 0;
                $bairros_valores = is_array($valores['bairro']) ? $valores['bairro'] : array($valores['bairro']);
                $qtde_bairro = count($bairros_valores);
                $l_bairro = '';
                $this->load->model('bairros_mongo_model');
                foreach( $bairros_valores as $b )
                {
                    if ( $ba == 0 )
                    {
                        $separador = '';
                    }
                    elseif( ( $ba > 0 ) && ( $ba == ($qtde_bairro - 1) ) )
                    {
                        $separador = ' ou ';
                    }
                    else
                    {
                        $separador = ', ';
                    }
                    $l_bairro .= $separador.$this->bairros_mongo_model->get_nome_por_link($b);
                    $ba++;
                }
                
            }
            if ( ! isset($oq['selecionado']) )
            {
                $config['extras']['titulo'] = ( isset($ti) && ! empty($ti) ? $ti.( $tem_no ? ' no ' : ' ' ) : 'Imóveis '.( $tem_no ? 'no ' : '') ).$l_bairro.( isset($complemento_titulo_caracteristicas) ? $complemento_titulo_caracteristicas : '' ).(isset($empresa) ? ' [empresa]' : '').( isset($valores['cidade']) ? ' em '.$this->cidade_set->nome.' - '.$this->cidade_set->uf : '' );
            }
            else
            {
                if ( isset($ti) && ! empty($ti) )
                {
                    if ( $oq['selecionado']->id == 'locacao' )
                    {
                        $config['extras']['titulo'] = 'Aluguel de '.$ti.( isset($complemento_titulo_caracteristicas) ? $complemento_titulo_caracteristicas : '' ).( $tem_no ? ' no ' : ' ' ).$l_bairro.(isset($empresa) ? ' [empresa]' : '').( isset($valores['cidade']) ? ' em '.$this->cidade_set->nome.' - '.$this->cidade_set->uf : '' );
                    }
                    else
                    {
                        $config['extras']['titulo'] = $ti.( isset($complemento_titulo_caracteristicas) ? $complemento_titulo_caracteristicas : '' ).' '.$oq['selecionado']->descricao_description.( $tem_no ? ' no ' : ' ' ).$l_bairro.(isset($empresa) ? ' [empresa]' : '').( isset($valores['cidade']) ? ' em '.$this->cidade_set->nome.' - '.$this->cidade_set->uf : '' );
                    }
                }
                else
                {
                    if ( $oq['selecionado']->id == 'locacao' )
                    {
                        $config['extras']['titulo'] = 'Aluguel de imóveis '.( $tem_no ? 'no ' : '' ).$l_bairro.( isset($complemento_titulo_caracteristicas) ? $complemento_titulo_caracteristicas : '' ).(isset($empresa) ? ' [empresa]' : '').( isset($valores['cidade']) ? ' em '.$this->cidade_set->nome.' - '.$this->cidade_set->uf : '' );
                    }
                    else
                    {
                        $config['extras']['titulo'] = 'Imóveis '.$oq['selecionado']->descricao_description.( $tem_no ? ' no ' : ' ' ).$l_bairro.( isset($complemento_titulo_caracteristicas) ? $complemento_titulo_caracteristicas : '' ).(isset($empresa) ? ' [empresa]' : '').( isset($valores['cidade']) ? ' em '.$this->cidade_set->nome.' - '.$this->cidade_set->uf : '' );
                    }
                    
                }
            }
                
            
            $config['extras']['description'] = 'Querendo '.( ! empty($oq_description) ? str_replace('para ', '', $oq_description) : 'comprar ou alugar').' um '.(isset($de) ? $de : '').( $tem_no ? ' no ' : ' ').$l_bairro.'? Confira os imóveis disponíveis neste bairro'.(isset($empresa) ? ' [empresa]' : '').( isset($valores['cidade']) ? ' de '.$this->cidade_set->nome.' - '.$this->cidade_set->uf : '' ).' com diversas opções que cabem no seu bolso.';
        }
        
        if ( isset($valores['empresa']) )
        {
            $config['extras']['titulo'] .= ' - '.$valores['empresa']->nome_fantasia.' ';
            $config['extras']['description'] .= ' - '.$valores['empresa']->nome_fantasia.' ';;
        }
        
        $config['cidade'] = $this->cidade_set->nome.'/'.$this->cidade_set->uf;
        $config['url'] = $url; 
        $config['valores'] = $valores;
        //$robot = $this->is_robot();
        
        $filtro_prox['cidade'] = $this->cidade_set->id;
        $filtro_prox['cidade_link'] = $this->cidade_set->link;
        //$links_relacionados = $this->imoveis_model->get_itens_por_cidade_filtro( $this->cidade_set->link, '', $filtro_links, 10, ( isset($filtro_prox) ? $filtro_prox : FALSE ) );
        //var_dump($config);
        $filtro = $this->filtro->inicia($config);
        //$filtro->links_relacionados = $links_relacionados;
        return $filtro;
        
    }
    
    private function _get( $valores_default = NULL )
    {
        $get = $this->input->get(NULL, TRUE);
        if ( isset($get['itens']) )
        {
            $valores = json_decode(urldecode($get['itens']));
        }
        $empresa = isset($valores_default->empresa) ? TRUE : FALSE;
        if ( ! isset($valores_default) || $empresa )
        {
            if ( $empresa )
            {
                //$valores->uf = substr($valores->cidade, -2);
                if ( ! isset($valores) )
                {
                    $valores = (object)array();
                }
                $valores->empresa = $valores_default->empresa;
                $valores->id_empresa = $valores_default->id_empresa;
            }
            $valores->cidade = isset($valores->cidade) ? $valores->cidade : NULL;
            $valores->tipo = ( isset($valores->tipo) && ! empty($valores->tipo) ? ( is_array($valores->tipo) ? $valores->tipo : explode(',', $valores->tipo) ) : NULL);
            
            if ( isset($valores->bairro) && ! empty($valores->bairro) )
            {
                $valores->bairro = is_array($valores->bairro) ? $valores->bairro : explode(',', $valores->bairro);
            }
        }
        else
        {
            if ( ! isset($valores) )
            {
                $valores = $valores_default;
            }
        }
        if ( isset($valores->valor_min) && ! empty($valores->valor_min) )
        {
            $valores->valor_min = str_replace(array(',','.'), '', $valores->valor_min);
        }
        if ( isset($valores->valor_max) && ! empty($valores->valor_max) )
        {
            $valores->valor_max = str_replace(array(',','.'), '', $valores->valor_max);
        }
        if ( isset($valores->area_min) && ! empty($valores->area_min) )
        {
            $valores->area_min = substr($valores->area_min, 0, -2);
        }
        if ( isset($valores->area_max) && ! empty($valores->area_max) )
        {
            $valores->area_max = substr($valores->area_max, 0, -2);
        }
        if ( isset($valores->residencial) && $valores->residencial )
        {
            $valores->residencial = (string)$valores->residencial;
        }
        if ( isset($valores->comercial) && $valores->comercial )
        {
            $valores->comercial = (string)$valores->comercial;
        }
        if ( isset($valores->aceita_troca) && $valores->aceita_troca )
        {
            $valores->aceita_troca = (string)$valores->aceita_troca;
        }
        if ( isset($valores->condominio) && $valores->condominio )
        {
            $valores->condominio = (string)$valores->condominio;
        }
        if ( isset($valores->mobiliado) && $valores->mobiliado )
        {
            $valores->mobiliado = (string)$valores->mobiliado;
        }
        if ( isset($valores->quartos) && count($valores->quartos) > 0 )
        {
            $quartos = array();
            foreach ( $valores->quartos as $quarto )
            {
                if ( $quarto == 4 )
                {
                    for ( $a = 4; $a < 50; $a++ )
                    {
                        $quartos[] = (int)$a;
                    }
                }
                else
                {
                    $quartos[] = (int)$quarto;
                }
            }
            $valores->quartos = $quartos;
        }
        if ( isset($valores->banheiros) && count($valores->banheiros) > 0 )
        {
            $banheiros = array();
            foreach ( $valores->banheiros as $banheiro )
            {
                if ( $banheiro == 4 )
                {
                    for ( $a = 4; $a < 50; $a++ )
                    {
                        $banheiros[] = (int)$a;
                    }
                }
                else
                {
                    $banheiros[] = (int)$banheiro;
                }
            }
            $valores->banheiros = $banheiros;
        }
        if ( isset($valores->vagas) && count($valores->vagas) > 0 )
        {
            $vagas = array();
            foreach ( $valores->vagas as $vaga )
            {
                if ( $vaga == 4 )
                {
                    for ( $a = 4; $a < 50; $a++ )
                    {
                        $vagas[] = (int)$a;
                    }
                }
                else
                {
                    $vagas[] = (int)$vaga;
                }
            }
            $valores->vagas = $vagas;
        }
        $retorno['url'] = $this->set_url($valores);
        $retorno['valores'] = (array)$valores;
        $retorno['coluna'] = isset($get['coluna']) ? $get['coluna'] : 'ordem';
        $retorno['ordem'] = isset($get['ordem']) ? $get['ordem'] : 'DESC';
        $retorno['per_page'] = isset($get['per_page']) ? $get['per_page'] : 0;
        return $retorno;
    }
    
    /**
     * * Ex de uri:
     *      imoveis_curitiba+pr
     *      imoveis_venda_curitiba+pr
     *      imoveis_venda_curitiba+pr_campo+comprido
     *      imoveis_venda_curitiba+pr_campo+comprido
     *      imoveis_locacao_curitiba+pr
     *      apartamentos_curitiba_pr_campo+comprido-santa+candida
     *      apartamentos-lote+terreno_curitiba+pr_campo+comprido-santa+candida
     *      apartamentos_venda_curitiba+pr
     *      imoveis_corteze_curitiba+pr
     *      imoveis_corteze_venda_curitiba+pr
     *      apartamentos_corteze_venda_curitiba+pr
     * @param type $valores
     */
    public function set_url( $valores, $imovel = FALSE, $empresa = FALSE )
    {
        $tipo = $imovel ? $valores->imoveis_tipos_link : ( ( isset($valores->tipo) && count($valores->tipo) > 0 ) ? $valores->tipo : FALSE );
        $id_empresa = $imovel ? FALSE : ( (isset($valores->id_empresa) && ! empty($valores->id_empresa)) ? $valores->id_empresa : FALSE );
        $tipo_negocio = $imovel ? $valores->tipo_negocio : ( (isset($valores->tipo_negocio) && ! empty($valores->tipo_negocio)) ? $valores->tipo_negocio : FALSE );
        $cidade = $imovel ? $valores->cidade_link : ( ( isset($valores->cidade) && ! empty($valores->cidade) ) ? $valores->cidade : FALSE );
        $bairro = $imovel ? FALSE : ( (isset($valores->bairro) && ! empty($valores->bairro) && count($valores->bairro) > 0) ? $valores->bairro : FALSE );
        if ( $tipo )
        {
            $elemento[] = is_array($tipo) ? implode('+',$tipo) : $tipo;
        }
        else
        {
            $elemento[] = 'imoveis';
        }    
        if ( $tipo_negocio )
        {
            $elemento[] = $tipo_negocio;
        }
        if ( $cidade )
        {
            $elemento[] = $cidade;
        }
        if ( $bairro )
        {
            $elemento[] = is_array($bairro) ? implode('+',$bairro) : $bairro;
        }
        if ( isset($valores->empresa) )
        {
            unset($valores->empresa);
        }
        $retorno = isset($elemento) ? implode('-', $elemento).( $imovel ? '' : '?itens='.urlencode(json_encode($valores, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) )) : '';    
        if ( $empresa )
        {
            $retorno = 'imobiliaria/'.urlencode( tira_especiais($empresa->nome_fantasia) ).'-'.$empresa->id.'/'.$retorno;
        }
        return $retorno;
    }
    
    public $tipo_negocio = array('venda','locacao','locacao_dia');
    
    
    public function get_valores_url( $valor )
    {
        $retorno = (object)array();
        if ( isset($valor) )
        {
            $cidades_json = getcwd().'/application/views/json/cidades';
            $cidades = json_decode(file_get_contents($cidades_json));
            $itens = explode('-', $valor);
            if ( ! empty($itens[0]) )
            {
                if ( $itens[0] != 'imoveis' )
                {
                    $retorno->tipo = explode('+',$itens[0]);
                }
            }
            unset($itens[0]);
            if ( isset($itens) )
            {
                foreach( $itens as $item )
                {
                    if ( ! empty($item) )
                    {
                        if ( in_array($item, $this->tipo_negocio) )
                        {
                            $retorno->tipo_negocio = $item;
                        }
                        if ( isset($retorno->cidade) )
                        {
                            if ( isset($item) && ! empty($item) )
                            {
                                $retorno->bairro = explode('+',$item);
                            }
                            //$bairros_json = getcwd().'/application/views/json/bairros/'.$retorno->cidade;
                            //$bairros = json_decode(file_get_contents($bairros_json));
                        }
                        if ( in_array($item, $cidades) )
                        {
                            $retorno->cidade = $item;
                            $retorno->uf = substr($retorno->cidade, -2);
                        }
                    }
                }
            }
        }
        return $retorno;
    }
    
    private function set_redirect()
    {
        $uri = $this->uri->segment_array();
        if ( isset($uri[1]) && $uri[1] === 'imoveis' )
        {
            $array_elementos = array( 2 => 'cidade', 3 => 'tipo_negocio', 4 => 'tipo', 5 => 'bairro', 6 => 'caracteristicas');
            unset ( $uri[1] );
            if ( isset($uri) && count($uri) > 0 )
            {
                //var_dump($uri, $array_elementos);
                $retorno_uri = (object)array();
                foreach ( $uri as $chave => $valor )
                {
                    if ( $array_elementos[$chave] == 'tipo' || $array_elementos[$chave] == 'bairro' )
                    {
                        if ( isset($valor) && $valor )
                        {
                            $retorno_uri->{$array_elementos[$chave]} = array($valor);
                        }
                    }
                    else
                    {
                        $retorno_uri->{$array_elementos[$chave]} = $valor;
                    }
                }
                $redirect = $this->set_url($retorno_uri);
                redirect( base_url().$redirect , 'location', 301);
                exit();
            }
        }
        elseif ( isset($uri[1]) && $uri[1] === 'imobiliaria' )
        {
            if ( isset($uri[3]) && ! strstr($uri[3], 'imoveis') )
            {
                $array_elementos = array( 3 => 'cidade', 4 => 'tipo_negocio', 5 => 'tipo', 6 => 'bairro', 7 => 'caracteristicas');
                $e = explode('-', $uri[2]);
                unset ( $uri[1], $uri[2] );
                $retorno_uri = (object)array();
                foreach ( $uri as $chave => $valor )
                {
                    if ( $array_elementos[$chave] == 'tipo' || $array_elementos[$chave] == 'bairro' )
                    {
                        if ( isset($valor) && $valor )
                        {
                            $retorno_uri->{$array_elementos[$chave]} = array($valor);
                        }
                    }
                    else
                    {
                        $retorno_uri->{$array_elementos[$chave]} = $valor;
                    }
                }
                $this->requisita_bd_sessao();
                $this->load->model('empresas_model');
                $empresa = $this->empresas_model->get_item_por_id( $e[1] );
                $redirect = $this->set_url($retorno_uri, FALSE,$empresa);
                redirect( base_url().$redirect , 'location', 301);
                exit();
            }
            
        }
        
    }
    
    /**
     * Inicia padroes de página e redireciona para o dominio certo.
     * Set cidade
     * set id-cidade
     * set logo
     * set titulo-padrao
     * set oq
     * set tipo
     * @param string $link_cidade - cidade setada apartir da URL, HTTP_HOST quando NULL
     * @param string $oq - oq setado na url
     * @param string $tipo - tipo setado na url
     * @return $this - devolve default
     */
    private function _inicia_geral($link_cidade = NULL, $oq = NULL, $tipo = NULL)
    { 
        $this->load->model(array('cidades_mongo_model'));
        $url_base = LOCALHOST ? 'https://icuritiba.com/' : base_url();
        $this->cidade = $this->cidades_mongo_model->get_item_por_portal( $url_base );
        /**
         * redireciona cidade se estiver no dominio relativo
         */
        $url_portal = HTTPS ? str_replace('http://www.', 'https://', $this->cidade->portal).'/' : $this->cidade->portal.'/';
//        var_dump($this->cidade, $url_portal);
//        var_dump(( $url_base != strtolower($url_portal) ) );
//        var_dump( $url_base , strtolower($url_portal)  );
//        die();
        if ( ( $url_base != strtolower($url_portal) ) || ( HTTPS && (strstr($_SERVER['HTTP_HOST'],'www.')) ) ) 
        { 
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
        $ok = $this->input->get( 'ok', TRUE );
        if ( $ok )
        {
            if ( $ok == 1 )  
            {
                echo $this->cidade->tag_adwords;
                echo '<script type="text/javascript">alert("sua mensagem foi enviada com sucesso.")</script>';
            }
            else 
            {
                echo '<script type="text/javascript">alert("Problemas no envio da mensagem, tente novamente.")</script>';
            }
        }
        $this->cidade_set = ( isset($link_cidade) ) ? $this->cidades_mongo_model->get_item_por_link( $link_cidade ) : $this->cidade;
        //var_dump($this->cidade_set);
        if ( ( isset($link_cidade) && $link_cidade ) && ! isset($this->cidade_set) )
        {
            $this->set_redirect();
            die();

        }
        $this->id_cidade = $this->cidade->id;
        $this->facebook = $this->cidade->rs_face;
        $this->plus = $this->cidade->rs_googlemais;
        $this->logo_principal = $this->cidade->topo;
        //var_dump($this->cidade);
        $this->titulo_padrao = ' - '.substr($this->cidade->portal, 11);
        $this->oq_set = str_replace('#', '', $oq);
        $this->tipo = $tipo ? $tipo : NULL;
        $this->link_bairro_visitados = NULL;//array('itens' => $this->bairros_views_model->get_ranking($this->id_cidade), 'link_cidade' => $this->cidade->link);
        
        
        return $this;
    }
    
    /**
     * Com base na cidade-set define as publicidades de banner da página, usa $this->cidade
     * - lateral_nacional
     * - lateral
     * - meio
     * - vertical
     * - topo
     * @return array $data - com 5 tipos de banners
     */
    public function set_publicidade()
    {
        $this->requisita_bd_sessao();
        $this->load->model(array('publicidade_model'));
        $this->_inicia_geral();
        $data['publicidade']['lateral_nacional'] = $this->publicidade_model->get_item_por_id(1044);
        //$data['publicidade']['lateral'] = ( ! empty($this->cidade->banner_lateral) && $this->cidade->banner_lateral > 0) ? $this->publicidade_model->get_item_por_id($this->cidade->banner_lateral) : NULL;
        $data['publicidade']['atopo'] = ( ! empty($this->cidade->banner_topo) && $this->cidade->banner_topo > 0) ? $this->publicidade_model->get_item_por_id($this->cidade->banner_topo) : NULL;
        $data['publicidade']['meio'] = ( ! empty($this->cidade->banner_meio) && $this->cidade->banner_meio > 0) ? $this->publicidade_model->get_item_por_id($this->cidade->banner_meio) : NULL;
        $data['publicidade']['home'] = ( ! empty($this->cidade->banner_home) && $this->cidade->banner_home > 0) ? $this->publicidade_model->get_item_por_id($this->cidade->banner_home) : NULL;
        //var_dump($data['publicidade']['home']);        
        //$data['publicidade']['meio_2'] = ( ! empty($this->cidade->banner_meio_2) && $this->cidade->banner_meio_2 > 0) ? $this->publicidade_model->get_item_por_id($this->cidade->banner_meio_2) : NULL;
        //$data['publicidade']['vertical'] = ( ! empty($this->cidade->banner_vertical) && $this->cidade->banner_vertical > 0) ? $this->publicidade_model->get_item_por_id($this->cidade->banner_vertical) : NULL;
        //$data['publicidade']['rodape'] = ( ! empty($this->cidade->banner_rodape) && $this->cidade->banner_rodape > 0) ? $this->publicidade_model->get_item_por_id($this->cidade->banner_rodape) : NULL;
        $retorno = $this->lista_normal->set_publicidade($data);
        
        
        echo json_encode($retorno);
    }
    
    /**
     * carrega os destaques de da função resultado, com base no filtro e paginacao
     * @param array $filtro - array de filtro com campos pertinentes para a pesquisa
     * @param string $off_set - pagina onde o usuario esta, para não aparecer propaganda na segunda página
     * @return array $retorno - [publicidade] - [destaques]
     */
    public function set_destaques ($off_set = 0)
    {
        $this->requisita_bd_sessao();
        $this->load->model(array('imoveis_model'));
        $get = $this->_get();
        $valores = $get['valores'];
        $empresas_tipo = $this->imoveis_model->get_itens_destaque_tipo( $valores );
        $empresas_bairro = $this->imoveis_model->get_itens_destaque_bairro( $valores );
        $this->_inicia_geral($valores['cidade']);
        $imoveis = '';
        $negativo = array();
        if ( isset($valores['tipo']) && ! empty($valores['tipo']) )
        {
            $imoveis_tipo = $this->_set_destaques_por($empresas_tipo, $get, $valores, FALSE, 'destaque_tipo');
            if ( isset($imoveis_tipo) && count($imoveis_tipo) > 0 && $imoveis_tipo[0] )
            {
                foreach ( $imoveis_tipo as $tipo )
                {
                    if ( $tipo )
                    {
                        $imoveis .= $this->lista_normal->_set_item_vertical($tipo,'tipo');
                        $negativo[] = $tipo->_id;
                    }
                }
            }
        }
        if ( isset($valores['bairro']) && ! empty($valores['bairro']) )
        {
            $imoveis_bairro = $this->_set_destaques_por($empresas_bairro, $get, $valores, $negativo, 'destaque_bairro');
            if ( isset($imoveis_bairro) && count($imoveis_bairro) > 0 && $imoveis_bairro[0])
            {
                foreach ( $imoveis_bairro as $bairro )
                {
                    if ( $bairro ) 
                    {
                        $imoveis .= $this->lista_normal->_set_item_vertical($bairro,'bairro');
                        
                    }
                }
            }
        }
            
        
        
        echo json_encode( array('item'=>$imoveis) );
    }
    
    private function _set_destaques_por( $empresas, $get, $valores, $negativo = array(), $por = 'destaque_tipo')
    {
        $this->load->model('imoveis_mongo_model');
        $retorno = FALSE;
        if ( isset($empresas) && count($empresas) > 0 )
        {
            $imoveis = array();
            foreach( $empresas as $empresa )
            {
                if ( isset($negativo) && count($negativo) > 0 )
                {
                    $valores['id_negativo'] = $negativo;
                }
                $valores['id_empresa'] = $empresa->id_empresa;
                $filtro = $this->_inicia_filtros($get['url'],$valores);
                $filtros = $filtro->get_filtro();
                $imovel = $this->imoveis_mongo_model->get_item_destaque_por_filtro($filtros, $por, 1);
                $imoveis[] = isset($imovel) ? $imovel : FALSE;
            }
            $retorno = $imoveis;
        }
        return $retorno;
    }
    
    
    /**
     * carrega os destaques da pagina principal
     * @param array $filtro - filtros da primeira pagina -  oq, cidade
     * @param type $qtde - qtde de destaques
     * @return array $retorno - [publicidade] - [destaques]
     */
    private function _set_destaques ( $filtro, $qtde = 8 )
    {
        $retorno = $this->_set_publicidade();
        switch ( $this->cidade_set->ativa )
        {
            case 1:
                //destaques pago
                $retorno['destaques'] = $this->imoveis_model->get_itens_destaque_pago($filtro->get_filtro(), $this->id_cidade, 12);
                break;
            case 4:
                //video
                $retorno['destaques']['video'][0] = '<h1 class="text-center">Procurando Imóveis em '.$this->cidade->nome.'?</h1> <h2 class="text-center">Imóveis '.$this->cidade->nome.' é aqui. '.substr($this->cidade->portal,11).'</h2>';
                $retorno['destaques']['video'][1] = '<h4 class="text-center">Aqui no '.substr($this->cidade->portal,11).' | Portal de Imóveis '.$this->cidade->nome.', você '.$this->cidade->gentilico.' encontra as Imobiliárias e os Imóveis de '.$this->cidade->nome.' reunidos em um único local.</h4>';
                
                break;
            case 5:
                //destaque automatico
                $i = $this->imoveis_model->get_itens( $filtro->get_filtro(), 'ordem_rad', 'random', 0, $qtde, 27 );
                //var_dump($i);
                $retorno['destaques'] = $i['itens'];
                if ( count($i['itens']) < 4 )
                {
                    $retorno['destaques']['video'][0] = '<h1 class="text-center">Procurando Imóveis em '.$this->cidade->nome.'?</h1> <h2 class="text-center">Imóveis '.$this->cidade->nome.' é aqui. '.substr($this->cidade->portal,11).'</h2>';
                    $retorno['destaques']['video'][1] = '<h4 class="text-center">Aqui no '.substr($this->cidade->portal,11).' | Portal de Imóveis '.$this->cidade->nome.', você '.$this->cidade->gentilico.' encontra as Imobiliárias e os Imóveis de '.$this->cidade->nome.' reunidos em um único local.</h4>';
                }
                break;
            
        }
        
        return $retorno;
    }
    
    /**
     * Função para página principal - parametros de url
     * @param string $cidade - cidade setada na url
     * @param string $oq - oq URL
     * @param string $tipo - tipo URL
     * 
     */
    public function principal()
    {
        
        $data = array();
        $this->layout
                ->view('filtro', $data, 'layout/layout'); 
        die();
        
        
        $this->_inicia_geral();
        $menu_itens = $this->set_menu_por_cidade();
        $filtro = $this->_inicia_filtros( ( isset($url) ? $url : NULL ));
        $data['filtro'] = $filtro->get_html_horizontal();
        $config['cidade'] = $this->cidade_set->nome;
        $lista = $this->lista_normal->inicia( $config );
        $data['listagem'] = $lista->get_html_horizontal();
        $f_tipo = $this->_filtro_json();
        $f_tipo[] = 'cidades.id = '.$this->cidade_set->id;
        $ti = '';
        $ti .= 'Apartamentos, Casas, terrenos';
        $this->titulo = 'Imóveis em '.$this->cidade->nome.': Encontre aqui o seu imóvel ';
        $this->description = 'No '.substr($this->cidade->portal, 11).' você encontra diversas opções de imóveis para compra, venda e locação: '.$ti.' e mais. Confira!';
        if ( $manda_url )
        {
            $layout->set_canonical($url);
        }
        $ok = $this->input->get( 'ok', TRUE );
        if( $ok )
        {
            $layout->set_tag_adwords($this->cidade->tag_adwords);
        }
        $layout
                ->set_facebook($this->facebook)
                ->set_plus($this->plus)
                ->set_link_bairro($this->link_bairro_visitados)
                ->set_includes_3_0()
                ->set_include('js/3_0/default.min.js', TRUE)
                ->set_include('js/3_0/publicidade.js', TRUE)
                ->set_titulo($this->titulo.$this->titulo_padrao)
                ->set_keywords($this->description)
                ->set_description($this->description)
                ->set_analytics($this->cidades_model->get_item_analytics_por_portal($this->id_cidade))
                ->set_google_tag($this->cidades_model->get_item_google_tag_por_portal($this->id_cidade))
                ->set_sobre($this->cidade->sobre, $this->cidade->nome, $this->cidade->portal, $this->cidade->gentilico, $this->cidade->link, $this->cidade->uf, $this->cidade->link_prefeitura,1 )
                ->set_menu($menu_itens)
                ->set_logo($this->logo_principal)
                ->set_favoritos($this->get_favoritos(FALSE))
                ->view('principal', $data, 'layout/layout'); 
    }
    
    
    
    /**
     * Página resultado, com parametros de url
     * @param string $cidade - cidade na URL
     * @param string $oq - oq na URL
     * @param string $tipo - tipo na URL
     * @param string $coluna - coluna na URL - vai definir a coluna de ordem da lista
     * @param string $ordem - ordem na URL - vai definir ordem na lista
     * @param string $qtde - qyde na URL - vai definir a qtde de itens apresentadas
     */
    public function landing_page( $layout = 'queremos_voce_de_novo' )
    {
        $this->requisita_bd_sessao();
            $this->_inicia_geral(  );
	    $this->load->model(array('empresas_model','cidades_model'));
            $menu_itens = $this->set_menu();
            $url = base_url().'imoveis/';
            $valores = array();
            /*
            $filtro_c['serv_ini'] = array( 'tipo' => 'where',  'campo' => 'empresas.servicos_pagina_inicio <',    'valor' => time() );
            $filtro_c['serv_fim'] = array( 'tipo' => 'where',  'campo' => 'empresas.servicos_pagina_termino >',       'valor' => time() );
            $filtro['serv_ini'] = array( 'tipo' => 'where',  'campo' => 'empresas.servicos_pagina_inicio <',    'valor' => time() );
            $filtro['serv_fim'] = array( 'tipo' => 'where',  'campo' => 'empresas.servicos_pagina_termino >',       'valor' => time() );
            $filtro_c['bloqueado'] = 'empresas.bloqueado = 0 ';
            $filtro_c['visivel'] = 'empresas.pagina_visivel = 1 ';
            $filtro_c[] = '( logradouros.id_cidade = 2  )';
            $data['imobiliarias'] = $this->empresas_model->get_itens_imobiliarias( $filtro_c );
            $filtro['bloqueado'] = 'empresas.bloqueado = 0 ';
            $filtro['visivel'] = 'empresas.pagina_visivel = 1 ';
            $filtro[] = '( imoveis.id_cidade = '.$this->id_cidade.' )';
            $filtro[] = '( logradouros.id_cidade != 2 )';
            $url = base_url().'imobiliarias/?';
            $url_canonica = base_url().'imobiliarias/';
            $data['imobiliarias_b'] = $this->empresas_model->get_itens_imobiliarias( $filtro);
            $filtro = $this->_inicia_filtros_vertical( $url, $valores, FALSE );
             * 
             */
            $this->load->model(array('noticias_model','imoveis_naoencontrei_model'));
            $this->titulo = 'Seguindo as tendências do mercado, resolvemos mudar!';
            $this->description = 'Em poucos dias voce conhecerá nossa nova proposta com uma nova identidade visual.';
            $itens['itens'] = NULL;
            $itens['total'] = 0;
            $config['itens'] = $itens;
            $config['titulo'] = $this->titulo;
            $data['cidade'] = $this->cidade;
            $data['noticias'] = $this->noticias_model->get_itens(array(),'data','DESC',0,3);
            $parametros = $this->imoveis_naoencontrei_model->get_parametros();
            $filtro_pedidos = array();
            if(isset($parametros[0]))
            {
                $parametros = $parametros[0];
                $dias = mktime(date('H'),date('i'),date('s'),date('m'),date('d')-$parametros->dias,date('Y'));
                $filtro_pedidos = array(
                    array('tipo' => 'where', 'campo' => 'imoveis_naoencontrei.data >', 'valor' => $dias),
                    array('tipo' => 'where', 'campo' => 'imoveis_naoencontrei.respostas <', 'valor' => $parametros->respostas),
                    array('tipo' => 'where', 'campo' => 'imoveis_naoencontrei.id_cidade', 'valor' => $this->cidade->id),
                );
            }
            $this->load->library('rss');
            $this->rss->set('http://www.portaisimobiliarios.com.br/blog/feed/');
            $data['noticias'] = $this->rss->get();
            $data['naoencontrei'] = $this->imoveis_naoencontrei_model->get_itens($filtro_pedidos);
            $this->layout
                    ->set_menu($menu_itens)
                    ->set_facebook($this->facebook)
                    ->set_plus($this->plus)
                    ->set_link_bairro($this->link_bairro_visitados)
//                    ->set_includes_3_0()
  //                  ->set_include('js/3_0/resultado.min.js', TRUE)
                    ->set_include('css/landing_page/'.$layout.'.css', TRUE)
                    ->set_include('css/landing_page/bootstrap_3_3_5_media.min.css', TRUE)
                    ->set_include('jango/css/components.css', TRUE)
                    ->set_include('jango/css/default.css', TRUE)
//                    ->set_include('jango/css/plugins.css', TRUE)
                    ->set_include('jango/plugins/reveal-animate/wow.js', TRUE)
                    ->set_include('jango/plugins/animate/animate.min.css', TRUE)
                    ->set_include('jango/plugins/simple-line-icons/simple-line-icons.min.css', TRUE)
//                    ->set_include('js/3_0/publicidade.js', TRUE)
                    ->set_include('js/'.$layout.'.js', TRUE)
                    ->set_header_extra($this->cidade->header_tag)
                    ->set_logo($this->logo_principal)
                    ->set_titulo($this->titulo.' '.$this->titulo_padrao)
                    ->set_keywords($this->description.$this->titulo_padrao)
                    ->set_description($this->description.' '.$this->titulo_padrao)
                    ->set_analytics($this->cidades_model->get_item_analytics_por_portal($this->id_cidade))
                    ->set_google_tag($this->cidades_model->get_item_google_tag_por_portal($this->id_cidade))
                    ->set_sobre($this->cidade->sobre, $this->cidade->nome, $this->cidade->portal, $this->cidade->gentilico, $this->cidade->link, $this->cidade->uf, $this->cidade->link_prefeitura)
                    ->set_favoritos($this->get_favoritos(FALSE))
                    ->view('nova_cara', $data, 'layout/layout'); 
 


    }
    
    /**
     * Página resultado, com parametros de url
     * @param string $cidade - cidade na URL
     * @param string $oq - oq na URL
     * @param string $tipo - tipo na URL
     * @param string $coluna - coluna na URL - vai definir a coluna de ordem da lista
     * @param string $ordem - ordem na URL - vai definir ordem na lista
     * @param string $qtde - qyde na URL - vai definir a qtde de itens apresentadas
     */
    public function e404()
    {
        $this->requisita_bd_sessao();
        header("HTTP/1.0 410 Not Found");
        header("Status: 410 Not Found"); 
            $this->_inicia_geral(  );
            $menu_itens = $this->set_menu();
            $url = base_url().'imoveis/';
            $valores = array();
            $filtro = $this->_inicia_filtros_vertical( $url, $valores );
            $this->titulo = 'Página Indisponivel - Use os filtros para continuar navegando';
            $this->description = 'Página Indisponivel  - Use os filtros para continuar navegando';
            //$itens = $this->_set_destaques_resultado( $filtro->get_filtro(), 0 );
            $itens['itens'] = NULL;
            $itens['total'] = 0;
            //$config = $this->_set_ordenacao_qtdes($filtro->get_url(), $valores['oq'], $cidade, $coluna, $ordem, $qtde, strtolower(__FUNCTION__));
            $config['pagination'] = $this->init_paginacao($itens['total'], $url.$filtro->get_url(), 0);
            $config['itens'] = $itens;
            $config['titulo'] = $this->titulo;
            //$this->lista_normal->inicia( $config );
            $data['listagem'] = '<h2>Desculpe mas o conteudo buscado não esta mais disponível, Use a busca ao lado para continuar sua navegação.</h2>';
            $this->layout
                    ->set_menu($menu_itens)
                    ->set_facebook($this->facebook)
                    ->set_plus($this->plus)
                    ->set_link_bairro($this->link_bairro_visitados)
                    //->set_include('js/3_0/resultado.min.js', TRUE)
                    //->set_include('js/3_0/publicidade.js', TRUE)
                    ->set_logo($this->logo_principal)
                    ->set_header_extra($this->cidade->header_tag)
                    ->set_titulo($this->titulo. ( ( $off_set > 0 ) ? ', a partir de '.$off_set.' imóveis' : '' ).$complemento_titulo.' '.$this->titulo_padrao)
                    ->set_keywords($this->description.$this->titulo_padrao)
                    ->set_description($this->description.( ( $off_set > 0 ) ? ', a partir de '.$off_set.' imóveis' : '' ).$complemento_titulo.' '.$this->titulo_padrao)
                    ->set_analytics($this->cidades_model->get_item_analytics_por_portal($this->id_cidade))
                    ->set_google_tag($this->cidades_model->get_item_google_tag_por_portal($this->id_cidade))
                    ->set_sobre($this->cidade->sobre, $this->cidade->nome, $this->cidade->portal, $this->cidade->gentilico, $this->cidade->link, $this->cidade->uf, $this->cidade->link_prefeitura)
                    ->set_favoritos($this->get_favoritos(FALSE))
                    ->view('resultado_lista', $data, 'layout/layout'); 
    }
    
    public function set_item_relacionado( $item, $qtde )
    {
        $this->requisita_bd_sessao();
        $this->load->model(array('bairro_model','tipo_model'));
        $retorno = '';
        $link = base_url().'imoveis/';
        if ( isset($item->cidade) && ! empty($item->cidade) )
        {
            $link .= $item->cidade.'/';
            $titulo_cidade = $this->cidade->nome;
            $ex = explode('-', $titulo_cidade);
            $titulo_cidade = isset($ex[0]) ? $ex[0] : $titulo_cidade;
        }
        else
        {
            
            if ( ( isset($item->bairro) && ! empty($item->bairro) ) || ( isset($item->tipo) && ! empty($item->tipo) ) || ( isset($item->negocio) && ! empty($item->negocio) ) )
            {
                $link .= '0/';
            }
        }
        if ( isset($item->negocio) && ! empty($item->negocio) )
        {
            $link .= $item->negocio.'/';
            $titulo_negocio = ( $item->negocio == 'venda' ? ' à venda' : ' para alugar' );
        }
        else
        {
            if ( ( isset($item->bairro) && ! empty($item->bairro) ) || ( isset($item->tipo) && ! empty($item->tipo) ) )
            {
                $link .= '0/';
            }
        }
        if ( isset($item->tipo) && ! empty($item->tipo) )
        {
            $link .= $item->tipo.'/';
            $titulo_tipo = $this->tipo_model->get_item_plural_por_link($item->tipo);
        }
        else
        {
            if ( isset($item->bairro) && ! empty($item->bairro) )
            {
                $link .= '0/';
            }
        }
        if ( isset($item->bairro) && ! empty($item->bairro) )
        {
            if ( isset( $item->proximidade ) )
            {
                $link .= $item->bairro.'/';
                $titulo_bairro = $item->proximidade;
            }
            else
            {
                $link .= $item->bairro.'/';
                $titulo_bairro = $this->bairro_model->get_item_nome_por_link($item->bairro);
            }
        }
        /*
        if ( isset($item->bairro) && ! empty($item->bairro) )
        {
            $link .= $item->bairro.'/';
            $titulo_bairro = $this->bairro_model->get_item_nome_por_link($item->bairro);
        }
         * 
         */
        if ( ( isset($item->valor_min) && ! empty($item->valor_min) && $item->valor_min > 1 ) || ( isset($item->valor_max) && ! empty($item->valor_max) && ( $item->valor_max != 10000000 ) && ( $item->valor_max != 20000 ) && ( $item->valor_max > 0 ) ) )
        {
            $link .= '?&pow[valormin]=';
            $link .= (( ( isset($item->valor_min) && ! empty($item->valor_min) && $item->valor_min > 1 ) ) ? $item->valor_min : 0);
            $link .= '&pow[valormax]=';
            $link .= ( ( isset($item->valor_max) && ! empty($item->valor_max) && ( $item->valor_max != 10000000 ) && ( $item->valor_max != 20000 ) && ( $item->valor_max > 1 ) ) ? $item->valor_max : 0 );
            $titulo_valor_min = ( ( isset($item->valor_min) && ! empty($item->valor_min) && $item->valor_min > 1 ) ) ? ' apartir de R$ '.set_valor_descrito($item->valor_min) : NULL;
            $titulo_valor_max = ( isset($item->valor_max) && ! empty($item->valor_max) && ( $item->valor_max != 10000000 ) && ( $item->valor_max != 20000 ) && ( $item->valor_max > 1 ) ) ? ' até R$ '.set_valor_descrito($item->valor_max) : NULL;
        }
        /*
        if ( isset($item->quartos) && ! empty($item->quartos) )
        {
            $link .= '&pow[quartos]='.$item->quartos;
            $titulo_quartos = ' até '.$item->quartos.' quartos';
        }
         * 
         */
        if ( isset($item->vagas) && ! empty($item->vagas) )
        {
            $link .= '&pow[vagas]='.$item->vagas;
            $titulo_vagas = ' até '.$item->vagas.' garagens';
        }
        $titulo = ( isset($titulo_tipo) ? $titulo_tipo : 'Imóveis ' ).( isset($titulo_negocio) ? $titulo_negocio : '' ).( isset($titulo_bairro) ? ( isset($item->proximidade) ? ' ' : ' no ' ).$titulo_bairro : '' ).( isset($titulo_valor_min) ? $titulo_valor_min : '' ).( isset($titulo_valor_max) ? $titulo_valor_max : '' ).( isset($titulo_quartos) ? $titulo_quartos : '' ).( isset($titulo_vagas) ? $titulo_vagas : '' );
        $titulo = $titulo.' em '.$titulo_cidade;
        if ( LOCALHOST )
        {
            $_SERVER['SCRIPT_URI'] = '';
        }
        if ( $_SERVER['SCRIPT_URI'] == $link )
        {
            $retorno = '';
        }
        else 
        {
            $numero = 12/$qtde;
            $retorno = '<li class="list-group-item col-lg-'.$numero.' col-md-'.$numero.' col-sm-'.$numero.' col-xs-12"><a href="'.$link.'" title="'.$titulo.'" class="alert-link">'.$titulo.'</a></li>';
        }  
        return $retorno;
    }
    
    public function set_links_relacionados ( $itens, $qtde_por_linha = 2 )
    {
        $retorno = NULL;
        if ( isset($itens['itens']) && $itens['qtde'] > 0 )
        {
            foreach( $itens['itens'] as $item )
            {
                $retorno[] = $this->set_item_relacionado($item, $qtde_por_linha );
            }
        }
        else
        {
            $retorno = NULL;
        }
        return $retorno;
    }
    
    /**
     * Página resultado, com parametros de url
     * @param string $cidade - cidade na URL
     * @param string $oq - oq na URL
     * @param string $tipo - tipo na URL
     * @param string $bairro - titulo dos bairros selecionados
     * @param string $caracteristicas - titulo dos caracteristicas selecionados
     */
    public function imoveis($cidade = NULL, $oq = NULL, $tipo = NULL, $bairro = NULL, $caracteristicas = NULL)
    {
        $this->set_redirect();
        die();
        /**
         * inicia verificação de 301 e modificações
         */
        $bairro_get = $this->input->get('pow',TRUE);
        if ( isset($bairro) )
        {
            $array_bairro_erro = array('bairro', 'ordem_rad','preco_venda','preco_locacao','bairro_link','area_util');
            if ( array_search($bairro, $array_bairro_erro) )
            {
                redirect(base_url().'imoveis/'.(isset($cidade) ? $cidade : '0').'/'.(isset($oq) ? $oq : '0').'/'.(isset($tipo) ? $tipo : '0').'/'.(isset($_GET['pow']['bairro']) ? $_GET['pow']['bairro'] : '0'), 'location', 301);
                die();
            }
        }
        
        elseif( isset($bairro_get['bairro']) && ! empty( $bairro_get['bairro'] ) )
        {
            $explode_bairro = explode('_', $bairro_get['bairro']);
            array_pop($explode_bairro);
            $bairro = implode('_', $explode_bairro);
                redirect(base_url().'imoveis/'.(isset($cidade) ? $cidade : '0').'/'.(isset($oq) ? $oq : '0').'/'.(isset($tipo) ? $tipo : '0').'/'.(isset($bairro) ? $bairro : '0'), 'location', 301);
                die();
        }
        
        /**
         * verifica se o parametro passado é valido em oq
         */
        if ( ( isset($oq) && $oq != '0' ) && ( $oq !== 'venda' && $oq !== 'locacao' && $oq !== 'locacao_dia' )   )
        {
            redirect(base_url().'imoveis/'.(isset($cidade) ? $cidade : '0').'/0/'.(isset($tipo) ? $tipo : '0').'/'.(isset($bairro) ? $bairro : '0'), 'location', 301);
            die();
        }
        /**
         * fim verificação de 301 e modificações
         */
        
        /**
         * retirado para evitar conteudo de titulo incorreto
         * @deprecated 11/08/2014 
            if ( $this->uri->segment(5) ) { $complemento_titulo .= ', ordem '.$coluna; }
            if ( $this->uri->segment(6) ) { $complemento_titulo .= ' e '.$ordem; }
            if ( $this->uri->segment(7) ) { $complemento_titulo .= ', itens por página '.$qtde; }
         * 
         */
        
        if ( ! isset($_GET) ) { $this->set_pageviews(strtolower(__FUNCTION__) ); } else { $this->set_pageviews(strtolower(__FUNCTION__).'_consulta' ); }
        /**
         * implementações de melhoria de performance
         * @since 11/08/2014
         */
        $qtde = $this->input->get('qtde') ? $this->input->get('qtde') : 20;
        $coluna = $this->input->get('coluna',TRUE) ? $this->input->get('coluna',TRUE) : 'ordem_rad';
        $ordem = $this->input->get('ordem',TRUE) ? $this->input->get('ordem',TRUE) : 'DESC';
        $off_set = $this->input->get('per_page',TRUE) ? $this->input->get('per_page',TRUE) : 0;
        $this->_inicia_geral( ( empty($cidade) ? NULL : $cidade), ( (empty($oq) ) ? NULL : $oq), ( empty($tipo) ? NULL : $tipo) );
        $menu_itens = $this->set_menu_por_cidade();
        $complemento_titulo = '';
        /**
         * melhoria de implementacao de funcionalidade reutilizavel em por_empresa
         */
        $complemento_url = $this->_set_complemento_url( $oq, $tipo, $bairro, $caracteristicas );
        $url = base_url().'imoveis/'.( (isset($cidade) && $cidade  ) ? $cidade : $this->cidade_set->link ).'/'.$complemento_url['url'];
        $url_canonical = base_url().'imoveis/'.( (isset($cidade) && $cidade  ) ? $cidade : $this->cidade_set->link ).'/'.$complemento_url['canonical'];
        $valores = $this->input->get('pow',TRUE);
        $valores['cidade'] = isset($cidade) ? $cidade : $this->cidade_set->link;
        $valores['oq'] = $this->oq_set;
        $valores['tipo'] = $this->tipo;
        $valores['caracteristicas'] = $caracteristicas;
            
        /**
         * Implementação de proximidade em bairros
         * @since 2015-10-25
         */
        if ( isset($bairro) && $bairro && strstr($bairro,'proximo_') )
        {
            $proximidade = str_replace('proximo_', '', $bairro);
            $valores['proximidade'] = $proximidade;
            $this->load->model('bairros_proximidade_model');
            $bairro = $this->bairros_proximidade_model->get_bairros_por_link($proximidade);
        }
        /**
             * implementacao de bairro no url
             * @since 2014-08-11
             */
            $valores['bairro'] = $bairro;
            if ( isset($bairro) && $bairro && ! strstr($bairro,'-') )
            {
                $verifica_bairro = $this->bairro_model->get_item_nome_por_link($bairro,$this->cidade_set->id);
                if ( ! isset($verifica_bairro) )
                {
                    $explode_bairro = explode('_', $bairro);
                    array_pop($explode_bairro);
                    $bairro = implode('_', $explode_bairro);
                    redirect(base_url().'imoveis/'.(isset($cidade) ? $cidade : '0').'/'.(isset($oq) ? $oq : '0').'/'.(isset($tipo) ? $tipo : '0').'/'.(isset($bairro) ? $bairro : '0'), 'location', 301);
                    die();
                }
            }
        $filtro = $this->_inicia_filtros_vertical( $url, $valores, TRUE );
        $this->titulo = $filtro->get_titulo();
        $this->description = $filtro->get_description();
        $itens = $this->_set_destaques_resultado( $filtro->get_filtro(), $off_set );
        $itens['itens'] = $this->imoveis_model->get_itens( $filtro->get_filtro(), $coluna, $ordem, $off_set, $qtde );
        $itens['total'] = $this->imoveis_model->get_total_itens( $filtro->get_filtro() );
        $data['filtro'] = $filtro->get_html_vertical( $itens['total'] );
        $config = $this->_set_ordenacao_qtdes($filtro->get_url(), $valores['oq'], $cidade, $bairro, $coluna, $ordem, $qtde, strtolower(__FUNCTION__), $caracteristicas);
        $url_modificada = $url.(strstr($filtro->get_url(),'?') ? '' : '?'  ).$filtro->get_url().'&ordem='.$ordem.'&coluna='.$coluna.'&qtde='.$qtde;
        $config['pagination'] = $this->init_paginacao($itens['total'], $url_modificada, $qtde);
        $config['itens'] = $itens;
        $config['titulo'] = $this->titulo;
        $config['links_relacionados'] = isset($filtro->links_relacionados) ? $this->set_links_relacionados($filtro->links_relacionados) : NULL;
        $this->lista_normal->inicia( $config );
        $data['listagem'] = $this->lista_normal->get_html_vertical();
        
        $layout = $this->layout;
        /**
         * retirado para ver se melhora os erros de html no webmastertools, 2014-11-13 -----> || $_SERVER['SCRIPT_URI'] == substr($url,0,-1) || $_SERVER['SCRIPT_URI'] == $url.'/' 
         */
        if (isset($_SERVER['SCRIPT_URI']))
        {
            if ( $url_canonical === $_SERVER['SCRIPT_URI']  )
            {
                if ( isset($_GET['pow']) )
                {
                    $layout->set_canonical($url_canonical);
                }
            }
            else
            {
                if ( ! $complemento_url['robot'] )
                {
                    //$layout->set_canonical($url_canonical);
                    
                }

            }
        }
        $erro = $this->input->get( 'erro', TRUE );
        if ( isset($erro) && $erro == 2 )
        {
            echo '<script>alert("não foi possivel enviar seu email, tente novamente.");</script>';
        }
        $ok = $this->input->get( 'ok', TRUE );
        if( $ok )
        {
            $layout->set_tag_adwords($this->cidade->tag_adwords);
        }
        if ( $this->cidade->id !== $this->cidade_set->id )
        {
            $layout->set_robot(FALSE);
        }
        elseif ( ! $complemento_url['robot'] )
        {
            $layout->set_robot(FALSE);
        }
        if ( strstr($_SERVER['HTTP_HOST'], 'localhost') )
        {
            $data['teste'] = NULL;
        }
        
        
        /* . ( ( $off_set > 0 ) ? ', a partir de '.$off_set.' imóveis' : '' ).$complemento_titulo.' ' */
        $layout 
                ->set_menu($menu_itens)
                ->set_facebook($this->facebook)
                ->set_plus($this->plus)
                ->set_link_bairro($this->link_bairro_visitados)
                ->set_includes_3_0()
                ->set_include('js/3_0/typeahead.bundle.js', TRUE)
                ->set_include('js/3_0/resultado.min.js', TRUE)
                ->set_include('js/3_0/publicidade.js', TRUE)
                ->set_header_extra($this->cidade->header_tag)
                ->set_logo($this->logo_principal)
                ->set_titulo($this->titulo.$this->titulo_padrao)
                ->set_description($this->description)
                ->set_analytics($this->cidades_model->get_item_analytics_por_portal($this->id_cidade))
                ->set_google_tag($this->cidades_model->get_item_google_tag_por_portal($this->id_cidade))
                ->set_sobre($this->cidade->sobre, $this->cidade->nome, $this->cidade->portal, $this->cidade->gentilico, $this->cidade->link, $this->cidade->uf, $this->cidade->link_prefeitura)
                ->set_favoritos($this->get_favoritos(FALSE))
                ->view('resultado_lista', $data, 'layout/layout'); 
    }
    
    private function _set_complemento_url( $oq, $tipo, $bairro, $caracteristicas )
    {
        $complemento['url'] = '';
        $complemento['canonical'] = '';
        $complemento['robot'] = TRUE;
        
        
        if ( ! ( isset($tipo) && $tipo ) || ! ( isset($bairro) && $bairro ) || ! ( isset($oq) && $oq ) || ! ( isset($caracteristicas) && $caracteristicas ) )  
        { 
            if ( ! ( isset($caracteristicas) && $caracteristicas ) )
            {
                if ( ! ( isset($bairro) && $bairro ) ) 
                {
                    if ( ! ( isset($tipo) && $tipo ) && ! ( isset($oq) && $oq ) ) 
                    { 
                        $complemento['url'] = ''; 
                        $complemento['canonical'] = '';
                    } 
                    else 
                    {
                        if ( ! ( isset($oq) && $oq ) ) 
                        { 
                            $complemento['url'] = '0/'.$tipo.'/'; 
                            $complemento['canonical'] = '0/'.$this->_get_item_por_traco($tipo).'/'; 
                        } 
                        else 
                        {
                            if ( ! ( isset($tipo) && $tipo ) ) 
                            { 
                                $complemento['url'] = $oq.'/';
                                $complemento['canonical'] = $oq.'/';
                            } 
                            else 
                            { 
                                $complemento['url'] = $oq.'/'.$tipo.'/'; 
                                $complemento['canonical'] = $oq.'/'.$this->_get_item_por_traco($tipo).'/'; 
                                if ( strstr($tipo, '-') )
                                {
                                    $complemento['robot'] = FALSE;
                                }
                            }
                        }
                    }
                } 
                else 
                { 
                    $complemento['url'] = $oq.'/'.$tipo.'/'.$bairro; 
                    $complemento['canonical'] = $oq.'/'.$this->_get_item_por_traco($tipo).'/'.$this->_get_item_por_traco($bairro).'/'; 
                    if ( strstr($tipo, '-') || ( strstr($bairro, '-') ) )
                    {
                        $complemento['robot'] = FALSE;
                    }
                }
            }
            else
            {
                $complemento['url'] = $oq.'/'.$tipo.'/'.$bairro.'/'.$caracteristicas; 
                $complemento['canonical'] = $oq.'/'.$this->_get_item_por_traco($tipo).'/'.$this->_get_item_por_traco($bairro).'/'.$this->_get_item_por_traco($caracteristicas).'/'; 
                if ( strstr($tipo, '-') || ( strstr($bairro, '-') ) )
                {
                    $complemento['robot'] = FALSE;
                }
            }
        } 
        else 
        { 
            $complemento['url'] = $oq.'/'.$tipo.'/'.$bairro.'/'.$caracteristicas; 
            $complemento['canonical'] = $oq.'/'.$this->_get_item_por_traco($tipo).'/'.$this->_get_item_por_traco($bairro).'/'.$this->_get_item_por_traco($caracteristicas).'/'; 
            if ( strstr($tipo, '-') || ( strstr($bairro, '-') ) )
            {
                $complemento['robot'] = FALSE;
            }
        }
        return $complemento;
    }
     
    private function _get_item_por_traco( $item )
    {
        if ( strstr($item,'-') )
        {
            $item_ = explode('-',$item);
        }
        else
        {
            $item_[] = $item;
        }
        return $item_[0];
    }
            
    
    /**
     * Página resultado, com parametros de url
     * @deprecated 11/08/2014 version 0.1
     * @param string $cidade - cidade na URL
     * @param string $oq - oq na URL
     * @param string $tipo - tipo na URL
     * @param string $coluna - coluna na URL - vai definir a coluna de ordem da lista
     * @param string $ordem - ordem na URL - vai definir ordem na lista
     * @param string $qtde - qyde na URL - vai definir a qtde de itens apresentadas
     */
    public function resultado($cidade = NULL, $oq = NULL, $tipo = NULL, $coluna = 'ordem_rad', $ordem = 'ASC', $qtde = 10)
    {
        
            if ( $this->uri->segment(1) === 'resultado' )
            {
                $u = substr(str_replace( 'resultado', 'imoveis', $_SERVER['REQUEST_URI'] ),1,1000);
                redirect(base_url().$u, 'location', 301);
                
                die();
            }
        
        
            if ( ( isset($oq) && $oq != '0' ) && ( $oq !== 'venda' && $oq !== 'locacao' && $oq !== 'locacao_dia' )   )
            {
                //var_dump($oq);
                redirect(base_url().'imoveis/'.(isset($cidade) ? $cidade : '0').'/0/'.(isset($tipo) ? $tipo : '0').'/'.$coluna.'/'.$ordem.'/'.$qtde, 'location', 301);
                die();
            }
        
        
        $complemento_titulo = '';
        if ( $this->uri->segment(5) )
        {
            $complemento_titulo .= ', ordem '.$coluna;
        }
        if ( $this->uri->segment(6) )
        {
            $complemento_titulo .= ' e '.$ordem;
        }
        if ( $this->uri->segment(7) )
        {
            $complemento_titulo .= ', itens por página '.$qtde;
        }
        
        if ( ! isset($_GET) )
        {
            $this->set_pageviews(strtolower(__FUNCTION__) );
        }
        else
        {
            $this->set_pageviews(strtolower(__FUNCTION__).'_consulta' );
        }
            $off_set = isset($_GET['per_page']) ? $_GET['per_page'] : 0;
            $this->_inicia_geral( ( empty($cidade) ? NULL : $cidade), ( (empty($oq) ) ? NULL : $oq), ( empty($tipo) ? NULL : $tipo) );
            $menu_itens = $this->set_menu_por_cidade();
            $url = base_url().'imoveis/'.((isset($cidade)) ? $cidade : '0' ).'/'.(isset($oq) ? $oq : '0' ).'/'.(isset($tipo) ? $tipo : '0').'/'.$coluna.'/'.$ordem.'/'.$qtde.'/';
            $valores = ( isset($_GET['pow']) ? $_GET['pow'] : array() );
            $valores['cidade'] = isset($cidade) ? $cidade : $this->cidade_set->link;
            $valores['oq'] = $this->oq_set;
            $valores['tipo'] = $this->tipo;
            $filtro = $this->_inicia_filtros_vertical( $url, $valores );
            $this->titulo = $filtro->get_titulo();
            $this->description = $filtro->get_description();
            $itens = $this->_set_destaques_resultado( $filtro->get_filtro(), $off_set );
            $itens['itens'] = $this->imoveis_model->get_itens( $filtro->get_filtro(), $coluna, $ordem, $off_set, $qtde );
            $itens['total'] = $this->imoveis_model->get_total_itens( $filtro->get_filtro() );
            $data['filtro'] = $filtro->get_html_vertical( $itens['total'] );
            $config = $this->_set_ordenacao_qtdes($filtro->get_url(), $valores['oq'], $cidade, $coluna, $ordem, $qtde, strtolower(__FUNCTION__));
            $config['pagination'] = $this->init_paginacao($itens['total'], $url.$filtro->get_url(), $qtde);
            $config['itens'] = $itens;
            $config['titulo'] = $this->titulo;
            $this->lista_normal->inicia( $config );
            $data['listagem'] = $this->lista_normal->get_html_vertical();
            $this->layout
                    ->set_canonical($url)
                    ->set_menu($menu_itens)
                    ->set_facebook($this->facebook)
                    ->set_plus($this->plus)
                    ->set_link_bairro($this->link_bairro_visitados)
                    ->set_includes_3_0()
                    ->set_include('js/3_0/resultado.min.js', TRUE)
                    ->set_include('js/3_0/publicidade.js', TRUE)
                    ->set_logo($this->logo_principal)
                    ->set_titulo($this->titulo. ( ( $off_set > 0 ) ? ', a partir de '.$off_set.' imóveis' : '' ).$complemento_titulo.' '.$this->titulo_padrao)
                    ->set_keywords($this->description.$this->titulo_padrao)
                    ->set_description($this->description.( ( $off_set > 0 ) ? ', a partir de '.$off_set.' imóveis' : '' ).$complemento_titulo.' '.$this->titulo_padrao)
                    ->set_analytics($this->cidades_model->get_item_analytics_por_portal($this->id_cidade))
                    ->set_google_tag($this->cidades_model->get_item_google_tag_por_portal($this->id_cidade))
                    ->set_sobre($this->cidade->sobre, $this->cidade->nome, $this->cidade->portal, $this->cidade->gentilico, $this->cidade->link, $this->cidade->uf, $this->cidade->link_prefeitura)
                    ->set_favoritos($this->get_favoritos(FALSE))
                    ->view('resultado_lista', $data, 'layout/layout'); 
    }
    
    public function por($quero = 'comprar', $tipo = 'bairro')
    {
        $this->requisita_bd_sessao();
        $this->load->model(array('bairro_model','cidades_model'));
        $this->_inicia_geral();
        $this->titulo = 'Encontre aqui os principais ';
        $this->description = 'Acesse o portal '.substr($this->cidade->portal,11).' e encontre o maior numero de ';
        switch( $quero )
        {
            case 'comprar':
            default:
                $oq = 'venda';
                $this->titulo .= 'Imóveis à venda, por ';
                $this->description .= 'Imóveis à venda, por ';
                $data['acao'] = array('titulo' => 'à venda', 'link' => 'venda');
                break;
            case 'alugar':
                $oq = 'locacao';
                $this->titulo .= 'Imóveis para Alugar, por ';
                $this->description .= 'Imóveis para Alugar, por ';
                $data['acao'] = array('titulo' => 'para Alugar', 'link' => 'locacao');
                break;
        }
        $filtro = $this->filtros_padrao($oq, TRUE);
        switch( $tipo )
        {
            case 'bairro':
            default:
                $this->titulo .= 'Bairro em ';
                $this->description .= 'Bairro em ';
                $data['lista'] = $this->bairro_model->get_select_json($filtro);
                $data['tipo'] = 'Bairro';
                break;
            case 'tipo':
                $this->titulo .= 'Tipo em ';
                $this->description .= 'Tipo em ';
                $data['lista'] = $this->tipo_model->get_select_json($filtro);
                $data['tipo'] = 'Tipo';
                break;
        }
        $this->titulo .= ' '.$this->cidade->nome.' e Região.';
        $this->description .= ' '.$this->cidade->nome.' e Região.';
        $data['cidade'] = array('titulo' => $this->cidade->nome, 'link' => $this->cidade->link);
        $menu_itens = $this->set_menu();
        //$this->description = $this->titulo;
        $layout = $this->layout;
        $ok = $this->input->get( 'ok', TRUE );
        
        if( $ok )
        {
            $layout->set_tag_adwords($this->cidade->tag_adwords);
        }
        $layout
                    ->set_menu($menu_itens)
                    ->set_facebook($this->facebook)
                    ->set_plus($this->plus)
                    //->set_link_bairro($this->link_bairro_visitados)
                    ->set_logo($this->logo_principal)
                    ->set_titulo($this->titulo.$this->titulo_padrao)
                    ->set_keywords($this->description)
                    ->set_description($this->description)
                ->set_header_extra($this->cidade->header_tag)
                    ->set_analytics($this->cidades_model->get_item_analytics_por_portal($this->id_cidade))
                    ->set_google_tag($this->cidades_model->get_item_google_tag_por_portal($this->id_cidade))
                    ->set_sobre($this->cidade->sobre, $this->cidade->nome, $this->cidade->portal, $this->cidade->gentilico, $this->cidade->link, $this->cidade->uf, $this->cidade->link_prefeitura)
                    ->set_favoritos($this->get_favoritos(FALSE))
                    ->view('por', $data, 'layout/layout'); 
        
    }
    
    public function quem_somos ( )
    {
        $this->_inicia_geral();
        $this->titulo .= 'Saiba mais informações: Quem somos e Portais imobiliários na cidade de '.$this->cidade->nome;
        $this->description .= 'Quem somos e mais informações sobre Portais imobiliários na cidade de '.$this->cidade->nome.', Alugue, Compre e Venda seu imóvel com segurança e rapidez.';
        $data['cidade'] = array('titulo' => $this->cidade->nome, 'link' => $this->cidade->link, 'portal' => $this->cidade->portal);
        $menu_itens = $this->set_menu();
        $data['itens'] = $menu_itens;
        $layout = $this->layout;
        $ok = $this->input->get( 'ok', TRUE );
        if( $ok )
        {
            $layout->set_tag_adwords($this->cidade->tag_adwords);
        }
        $layout
                    ->set_menu($menu_itens)
                    ->set_facebook($this->facebook)
                    ->set_plus($this->plus)
                    ->set_link_bairro($this->link_bairro_visitados)
                    //->set_includes_3_0()
                    //->set_include('js/3_0/resultado.min.js', TRUE)
                    //->set_include('js/3_0/publicidade.js', TRUE)
                    ->set_logo($this->logo_principal)
                    ->set_titulo($this->titulo.$this->titulo_padrao)
                    ->set_keywords($this->description.$this->titulo_padrao)
                    ->set_description($this->description)
                ->set_header_extra($this->cidade->header_tag)
                    ->set_analytics($this->cidade->instrucoes_head)
                    ->set_google_tag($this->cidade->tag_adwords)
                    ->set_sobre($this->cidade->sobre, $this->cidade->nome, $this->cidade->portal, $this->cidade->gentilico, $this->cidade->link, $this->cidade->uf, $this->cidade->link_prefeitura)
                    ->set_favoritos($this->get_favoritos(FALSE))
                    ->view('quem_somos', $data, 'layout/layout'); 
    }
    
    public function mapa_do_site(  )
    {
        $this->requisita_bd_sessao();
        $this->load->model(array('cidades_model'));
        //$this->set_pageviews(strtolower(__FUNCTION__) );
        $this->_inicia_geral();
        $this->titulo .= 'Mapa do Site '.$this->cidade->nome.', Encontre facilmente o imóvel que procura.';
        $data['cidade'] = array('titulo' => $this->cidade->nome, 'link' => $this->cidade->link, 'portal' => $this->cidade->portal);
        $menu_itens = $this->set_menu();
        $data['itens'] = $this->set_menu_por_cidade(NULL, $this->cidade);
        $this->description = 'Pagina de Mapa do Site portal de imóveis '.$this->cidade->nome.', Encontre os melhores Apartamento, casas, sobrados e muito mais. Facil e a um clique.';
        $layout = $this->layout;
        $ok = $this->input->get( 'ok', TRUE );
        if( $ok )
        {
            $layout->set_tag_adwords($this->cidade->tag_adwords);
        }
        $layout
                    ->set_menu($menu_itens)
                    ->set_facebook($this->facebook)
                    ->set_plus($this->plus)
                    ->set_link_bairro($this->link_bairro_visitados)
                    ->set_logo($this->logo_principal)
                    ->set_titulo($this->titulo.$this->titulo_padrao)
                    ->set_keywords($this->description.$this->titulo_padrao)
                    ->set_description($this->description)
                ->set_header_extra($this->cidade->header_tag)
                    ->set_analytics($this->cidades_model->get_item_analytics_por_portal($this->id_cidade))
                ->set_google_tag($this->cidades_model->get_item_google_tag_por_portal($this->id_cidade))    
                ->set_sobre($this->cidade->sobre, $this->cidade->nome, $this->cidade->portal, $this->cidade->gentilico, $this->cidade->link, $this->cidade->uf, $this->cidade->link_prefeitura)
                    ->set_favoritos($this->get_favoritos(FALSE))
                    ->view('mapa_do_site', $data, 'layout/layout'); 
    }
    
    public function rede(  )
    {
        $this->requisita_bd_sessao();
        $this->load->model('cidades_model');
        //$this->set_pageviews(strtolower(__FUNCTION__) );
        $this->_inicia_geral();
        $this->titulo .= 'Rede de Portais '.$this->cidade->estado_tratamento.' '.$this->cidade->estado;
        $data['titulo'] = $this->titulo;
        $data['cidade'] = array('titulo' => $this->cidade->nome, 'link' => $this->cidade->link, 'portal' => $this->cidade->portal, 'uf' => $this->cidade->uf, $this->cidade_set->link_prefeitura);
        $menu_itens = $this->set_menu();
        //$filtro = $this->filtros_padrao(NULL,FALSE);
        $filtro_c[] = 'cidades.uf = "'.$this->cidade->uf.'"';
        $filtro_c[] = 'cidades.capital = 1';
        $data['capital'] = $this->cidades_model->get_item_cidade_por_modalidade($filtro_c);
        $filtro[] = 'cidades.portal != ""';
        $filtro[] = 'cidades.uf = "'.$this->cidade->uf.'"';
        $filtro[] = array('tipo' => 'where_not_in', 'campo' => 'cidades.id ', 'valor' => $data['capital'][0]->id);
        $data['itens'] = $this->cidades_model->get_item_cidade_por_modalidade($filtro);
        $this->description = 'Conheça todos os portais no seu estado, e no Brasil. Assim pode comprar, vender ou alugar seu imóvel com segurança e comodidade.';
        $layout = $this->layout;
        $ok = $this->input->get( 'ok', TRUE );
        if( $ok )
        {
            $layout->set_tag_adwords($this->cidade->tag_adwords);
        }
        $layout
                    ->set_menu($menu_itens)
                    ->set_facebook($this->facebook)
                    ->set_plus($this->plus)
                    ->set_link_bairro($this->link_bairro_visitados)
                    //->set_includes_3_0()
                    //->set_include('js/3_0/resultado.min.js', TRUE)
                    //->set_include('js/3_0/publicidade.js', TRUE)
                    ->set_logo($this->logo_principal)
                    ->set_titulo($this->titulo.$this->titulo_padrao)
                    ->set_keywords($this->description.$this->titulo_padrao)
                    ->set_description($this->description)
                ->set_header_extra($this->cidade->header_tag)
                    ->set_analytics($this->cidades_model->get_item_analytics_por_portal($this->id_cidade))
                ->set_google_tag($this->cidades_model->get_item_google_tag_por_portal($this->id_cidade))
                    ->set_sobre($this->cidade->sobre, $this->cidade->nome, $this->cidade->portal, $this->cidade->gentilico, $this->cidade->link, $this->cidade->uf, $this->cidade->link_prefeitura)
                    ->set_favoritos($this->get_favoritos(FALSE))
                    ->view('rede', $data, 'layout/layout'); 
    }
    
    
    /**
     * Pagina por empresa com parametros por url
        
     */
    public function por_empresa($imobiliaria = NULL, $uri = NULL, $a = FALSE, $b = FALSE, $c = FALSE, $d = FALSE, $e = FALSE)
    {
        $this->requisita_bd_sessao();
        $this->load->model(array('empresas_model'));
        $this->set_redirect();
        $explode_imobiliaria = explode('-',$imobiliaria);
        $data['empresa'] = $this->empresas_model->get_item_por_id( $explode_imobiliaria[1] );
        $valores = (object)array();
        $valores = $this->get_valores_url( $uri );
        $valores->id_empresa = $explode_imobiliaria[1];
        $valores->empresa = $data['empresa'];
        $data['itens'] = $this->pesquisa($valores,'return');
        $layout = $this->layout;
        if( isset($ok) )
        {
            $layout->set_tag_adwords($this->cidade->tag_adwords);
        }
        $titulo = isset($data['itens']['h1']) ? str_replace('[empresa]','com '.$data['empresa']->nome_fantasia.', ',$data['itens']['h1']) : 'Imóveis de '.$empresa->nome.' em '.$this->cidade->nome.': Encontre aqui seu imóvel - '.substr($this->cidade->portal, 11);
        $data['itens']['h1'] = isset($data['itens']['h1']) ? str_replace('[empresa]','com '.$data['empresa']->nome_fantasia.', ',$data['itens']['h1']) : 'Imóveis de '.$empresa->nome.' em '.$this->cidade->nome.': Encontre aqui seu imóvel - '.substr($this->cidade->portal, 11);
        $description = isset($data['itens']['description']) ? str_replace('[empresa]','com '.$data['empresa']->nome_fantasia.', ',$data['itens']['description']) : 'No '.substr($this->cidade->portal, 11).' você encontra diversas opções de imóveis para compra, venda e locação: Apartamentos, Casas, terrenos e mais. Confira!';
        $menu = $this->set_menu();
        $cidade = $this->cidade;
        $data['tipo_negocio_array'] = array('venda' => 'Comprar','locacao' => 'Alugar','locacao_dia' => 'Alugar Dia');
        $data['cidade_set'] = $this->cidade_set;
        $data['bairro'] = $this->_set_bairro($data);
        $layout
                ->set_menu($menu)
                ->set_logo( $this->logo_principal )
                ->set_include('css/3_3/lista.css', TRUE)
                ->set_include('js/3_3/jquery.mobile.touch.min.js', TRUE)
                ->set_include('js/3_3/filtro.js', TRUE)
                ->set_titulo($titulo)
                ->set_header_extra($this->cidade->header_tag)
                ->set_description($description)
                ->set_sobre($cidade->sobre, $cidade->nome, $cidade->portal, $cidade->gentilico, $cidade->link, $cidade->uf, $cidade->link_prefeitura,1 )
                ->view('filtro', $data, 'layout/layout'); 
    }
    
    /**
     * requisições da busca de imóvel por id, se existir envia para o imóvel, senao envia para pesquisa
     * @param string $id_imovel - id do imovel vem pela URL
     * @return string URL
     */
    public function verifica_imovel ( $id_imovel )
    {
        $item = $this->imoveis_model->get_item_por_id( $id_imovel );
        if ( isset($item) )
        {
            $retorno = '/imovel/'.$item->id;
        }
        else
        {
            $retorno = 'erro';
        }
        echo $retorno;
    }
    
    /**
     * Gera qrcode para impressão
     * @param string $id_imovel
     * @return qrcode
     */
    public function qrcode ( $id_imovel )
    {
        include_once APPPATH.'libraries/QR/qrlib.php';
        echo QRcode::png(base_url().'imovel/0/'.$id_imovel.'/qrcode');
    }
    
    private $valida_email = array(
                                    array( 'field'   => 'id_empresa',           'label'   => '',                    'rules'   => 'trim'),
                                    array( 'field'   => 'id_imovel',            'label'   => '',                    'rules'   => 'trim'),
                                    array( 'field'   => 'local',                'label'   => '',                    'rules'   => 'trim'),
                                    array( 'field'   => 'validador',            'label'   => '',                    'rules'   => 'trim'),
                                    array( 'field'   => 'redirect',             'label'   => '',                    'rules'   => 'trim'),
                                    array( 'field'   => 'assunto',              'label'   => 'Assunto',             'rules'   => 'trim'),
                                    array( 'field'   => 'email',                'label'   => 'E-mail',              'rules'   => 'required|valid_email'),
                                    array( 'field'   => 'nome',                 'label'   => 'Nome',                'rules'   => 'required'),
                                    array( 'field'   => 'fone',                 'label'   => 'Telefone',            'rules'   => 'trim'),
                                );
    
    private function _post_email()
    {
        $dados = $this->input->post(NULL,TRUE);
        $dados['remetente']['email'] = $dados['email'];
        $dados['remetente']['fone'] = $dados['fone'];
        $dados['remetente']['nome'] = $dados['nome'];
        return $dados;
    }
    
    /**
     * pagina imóvel, com parametros URL
     * @param string $id_imovel - id do imovel
     * @param string $complemento - informações sobre o imóvel - apenas para SEO
     * @param string $local - local de onde veio este imóvel - para log
     * @param string $layout - default novo, e print para usar o print da pagina.
     * 
     */
    public function imovel ( $complemento = NULL, $id_imovel = 0, $local = 0, $layout = 'novo', $ajax = FALSE )
    {

        $this->load->model(array('imoveis_mongo_model'));
        $this->form_validation->set_rules( $this->valida_email );
        if ( $this->form_validation->run() )
        {
            error_reporting(E_ALL);ini_set('display_errors', 1);
            die('Ambiente promiscuo... volte e tente novamente...');
            $dados = $this->_post_email();
            $this->email($dados);
        }
        else 
        {           
            switch ( $local )
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
                case 8 :
                    $log = 103;
                    break;
                case 3:
                    $log = 27;
                    break;
                case 2:
                    $log = 96;
                    break;
                case 4:
                default:
                    $log = 36;
                    break; 
            }
            $this->load->library('lista_normal');
            $data['item'] = $this->imoveis_mongo_model->get_item( $id_imovel);
            $data['log'] = $local;
//            $this->set_log($id_imovel, $log, 'clicks');
            if ( $local == 3 )
            {
                if ( isset($data['item']) )
                {
                    //$this->imoveis_model->get_destaques_views($data['item']->id, $data['item']->id_empresa, 'clicks');
                }
            }

            $this->_inicia_geral();
            if ( ! $data['item'] )
            {
//                header("HTTP/1.1 410 Gone");
                $robot = FALSE;
                $layout = 'erro';
                $this->description = 'Desculpe, mas este imóvel não se encontra mais em nossa base.'.$id_imovel;
                $this->titulo = 'Desculpe, mas este imóvel não se encontra mais em nossa base.'.$id_imovel.$this->titulo_padrao;
                $this->load->model('imoveis_historico_model');
                $data['item'] = $this->imoveis_historico_model->get_item_por_id($id_imovel);
                $data['item']->bairro = $data['item']->bairros_link;
                $redirect = $this->set_url($data['item'],TRUE);
                //var_dump($redirect);die();
                redirect( base_url().$redirect , 'location', 301);
                exit();
                $historico = TRUE;
            }
            else
            {
                $historico = FALSE;
                $data['cidade'] = $this->cidade;
                $data['sobre'] = array('nome' => $this->cidade->nome, 'portal' => $this->cidade_set->portal);
                $data['complemento'] = ($data['item']->venda == 1) ? 'Venda' : (($data['item']->locacao == 1) ? 'Locação' : 'Locação dia');
                $bairro = ( ! empty($data['item']->bairro) ? $data['item']->bairro : '' ).( ! empty($data['item']->vila) ? $data['item']->vila : '' );
                $titulo = ( ($data['item']->venda == 1) )? 'Comprar ' : '';
                $description = $data['item']->imoveis_tipos_titulo.' ';
                $description .= ( ($data['item']->venda == 1) )? 'para venda ' : '';
                $titulo .= ( ( ($data['item']->venda == 1) && ($data['item']->locacao == 1) ) || ( ($data['item']->venda == 1) && ($data['item']->locacao_dia == 1) ) ) ? ' ou ' : '';
                $description .= ( ( ($data['item']->venda == 1) && ($data['item']->locacao == 1) ) || ( ($data['item']->venda == 1) && ($data['item']->locacao_dia == 1) ) ) ? ' ou ' : '';
                $titulo .= ( ($data['item']->locacao == 1) ) ? 'Alugar ' : '';
                $description .= ( ($data['item']->locacao == 1) ) ? 'para locação ' : '';
                $titulo .= ( ($data['item']->locacao == 1) && ($data['item']->locacao_dia == 1) ) ? ' ou ' : '';
                $description .= ( ($data['item']->locacao == 1) && ($data['item']->locacao_dia == 1) ) ? ' ou ' : '';
                $titulo .= ( ($data['item']->locacao_dia == 1) ) ? 'Locação temporária de ' : '';
                $description .= ( ($data['item']->locacao_dia == 1) ) ? 'para Locação temporária ' : '';
                $titulo .= $data['item']->imoveis_tipos_titulo;
                $titulo .= ( ! empty($data['item']->bairro)  ) ? ', '.$data['item']->bairro : '';
                $description .= ( ! empty($data['item']->bairro)  ) ? 'no bairro '.$data['item']->bairro : '';
                $titulo .= ', '.$data['item']->cidade;
                $description .= ', '.$data['item']->cidade;
                $titulo .= ( ( $data['item']->area_util > 0.00 ) ? ', '.$data['item']->area_util.'m&sup2;' : ''); 
                $description .= ( ( $data['item']->area_util > 0.00 ) ? ', '.$data['item']->area_util.'m&sup2;' : ''); 
                $titulo .= ( ( $data['item']->preco_venda > 0 ) ? ', R$'.  number_format($data['item']->preco_venda, 2, ',', '.') : '');
                $description .= ( ( $data['item']->preco_venda > 0 ) ? ', R$'.  number_format($data['item']->preco_venda, 2, ',', '.') : '');
                $titulo .= ( ( $data['item']->preco_locacao > 0 ) ? ', R$'.  number_format($data['item']->preco_locacao, 2, ',', '.') : '' );
                $description .= ( ( $data['item']->preco_locacao > 0 ) ? ', R$'.  number_format($data['item']->preco_locacao, 2, ',', '.') : '' );
                $titulo .= ( ( $data['item']->preco_locacao_dia > 0 ) ? ' R$'.  number_format($data['item']->preco_locacao_dia, 2, ',', '.') : '');
                $description .= ( ( $data['item']->preco_locacao_dia > 0 ) ? ', R$'.  number_format($data['item']->preco_locacao_dia, 2, ',', '.') : '');
                $titulo .= '| PI: '.$data['item']->id.'  ';
                $description .= '| PI: '.$data['item']->id.'  ';
                $this->titulo .= $titulo.' no '.  substr($this->cidade->portal, 11);
                $this->description .= $description.' no '.  substr($this->cidade->portal, 11);
                $r = $this->layout;
                $data['mapa'] = $r->set_include('js/imovel.js', TRUE)->set_function('mapa')->set_mapa($data['item']->mapa)->view('mapa', array(), 'layout/branco', TRUE);
                $data['local'] = $local;
                if ( isset($data['item']->images[0]->arquivo) && ! empty($data['item']->images[0]->arquivo) )
                {
                    if ( isset($data['item']->images[0]->original) )
                    {
                        $data['item']->images[0]->original = str_replace(array('http://www.powempresas.com/','http://admin.powempresas.com/'),base_url(),$data['item']->images[0]->original);
                        $image_gr = set_arquivo_image($data['item']->id, $data['item']->images[0]->original, $data['item']->id_empresa, 1, '', $data['item']->images[0]->id, '650F');
                    }
                    else
                    {
                        $image_gr = str_replace('F_','650F_F_',str_replace(array('http://www.powempresas.com/','http://admin.powempresas.com/'),base_url(),$data['item']->images[0]->arquivo));
                    }
//                    $image_destaque = ( strstr($data['item']->image1, 'http') ? $data['item']->image1 : set_arquivo_image($data['item']->id, $data['item']->image1, $data['item']->id_empresa, $data['item']->mudo, $data['item']->fs1, 1, '650F_F') );
                    $data['image_destaque'] = $image_gr;
                }
            }
            
        $separador = '-';
        if ( $data['item']) 
        {
            $url = $this->lista_normal->_set_link($data['item']);
            $data['url'] = $url;
            $data['breadscrumb'] = $this->set_url($data['item'], 'imovel');
        }
                //->set_header_extra('<meta name="robots" content="noindex">')
        $l = $this->layout;
        $ok = $this->input->get( 'ok', TRUE );
        if( $ok )
        {
            $l->set_tag_adwords($this->cidade->tag_adwords);
        }
            
        $data['relacionados'] = $this->set_relacionados($data['item'], $historico);
        $data['relacionados_imobiliaria'] = $this->set_relacionados_imobiliaria($data['item']);
        $data['link_print'] = base_url().'imovel/'.(isset($complemento) ? $complemento : 0).'/'.$id_imovel.'/'.$local.'/print';
        $robot = TRUE;
        $keys = json_decode($this->cidade->google_keys);
        $data['sitekey'] = $keys->site;
        $data['tag_adwords'] = $this->cidade->tag_adwords;
        if ( strstr($this->cidade->tag_adwords, '_googWcmGet') )
        {
            $load = "_googWcmGet(liga_imobiliaria,'55-".$data['item']->ddd."-".$data['item']->imobiliaria_telefone."')";
//            $load = "_googWcmGet(liga_imobiliaria,'55-41-33821581')";
            $l->set_on_load($load);
        }
        if ( isset($_GET['debug_item']) ){
            echo '<pre>';
            print_r($data['item']);
            print_r($this->cidade);
            echo '</pre>';
            die();
        }
        $menu = $this->set_menu();
        $l
            ->set_canonical($url)
            ->set_menu($menu)
            ->set_facebook($this->facebook)
            ->set_plus($this->plus)
            ->set_image_destaque(isset($data['image_destaque']) ? $data['image_destaque'] : NULL)
            ->set_include('css/3_3/imovel.css', TRUE)
            ->set_include('js/3_0/lightbox/css/lightbox.css', TRUE)
            ->set_include('js/3_0/lightbox/js/lightbox-2.6.min.js', TRUE)
            ->set_include('js/3_3/jquery.mobile.touch.min.js', TRUE)
            ->set_include('js/metronic/ladda/ladda-themeless.min.css', TRUE)
            ->set_include('js/metronic/ladda/ladda.min.js', TRUE)
            ->set_include('js/metronic/ladda/spin.min.js', TRUE)
            ->set_include('js/metronic/bootstrap-sweetalert/sweetalert.css', TRUE)
            ->set_include('js/metronic/bootstrap-sweetalert/sweetalert.min.js', TRUE)
            ->set_include('https://www.google.com/recaptcha/api.js', FALSE)
            ->set_include('js/3_3/imovel.js', TRUE)
            ->set_logo($this->logo_principal)
            ->set_titulo($this->titulo)
            ->set_keywords($this->description)
            ->set_description($this->description)
            ->set_robot($robot)
                ->set_header_extra($this->cidade->header_tag)
            ->set_analytics($this->cidade->instrucoes_head)
            ->set_google_tag($this->cidade->google_tag)
            ->set_sobre($this->cidade->sobre, $this->cidade->nome, $this->cidade->portal, $this->cidade->gentilico, $this->cidade->link, $this->cidade->uf, $this->cidade->link_prefeitura)
            ;
            if ( $layout == 'print' )
            {
                $l->set_include('js/3_0/print.js',TRUE);
                $data['logo'] = $this->logo_principal;
            }
            $la = 'layout/'.( ($layout == 'print') ? 'limpo' : 'layout');
            if ( $ajax )
            {
                $data['ajax'] = TRUE;
                $retorno = $l->view('imovel_'.$layout, $data, 'layout/limpo', TRUE);
                echo $retorno;
            }
            else
            {
                $l->view('imovel_'.$layout, $data, $la);
            }
        }
        
        
    }
    
    public function set_relacionados( $item, $historico = FALSE )
    {
        if ( $item )
        {
            
            $url = '';
            $valores['cidade'] = $item->cidade_link;
            $valores['tipo'] = $item->imoveis_tipos_link;
            $retorno = NULL;
            if ( ! $historico )
            {
                if ( $item->venda )
                {
                    $ordem = 'preco_venda';
                    $valores['oq'] = 'venda';
                    //$qtde_numero = strlen($item->preco_venda);
                    //$variacao = str_pad('1', ($qtde_numero - 4), '0', STR_PAD_RIGHT).'.00';
                    //$valores['valormin'] = ($item->preco_venda - $variacao);
                    $valores['valor_max'] = $item->preco_venda;
                }
                elseif ( $item->locacao )
                {
                    $ordem = 'preco_locacao';
                    $valores['oq'] = 'locacao';
                    //$qtde_numero = strlen($item->preco_locacao);
                    //$variacao = str_pad('8', ($qtde_numero - 5), '0', STR_PAD_RIGHT).'.00';
                    //$valores['valormin'] = ($item->preco_locacao - $variacao);
                    $valores['valor_max'] = $item->preco_locacao;
                }
                else
                {
                    $ordem = 'preco_locacao_dia';
                    $valores['oq'] = 'locacao_dia';
                    //$qtde_numero = strlen($item->preco_locacao_dia);
                    //$variacao = str_pad('8', ($qtde_numero - 5), '0', STR_PAD_RIGHT).'.00';
                    //$valores['valormin'] = ($item->preco_locacao_dia - $variacao);
                    $valores['valor_max'] = $item->preco_locacao_dia;
                }
                if( isset($item->quartos))
                {
                    $valores['quartos'] = array($item->quartos);
                }

                $valores['id_negativo'] = array($item->id);
                $qtde = 12;
                $a = -1;
            }
            else
            {
                $a = -1;
                $ordem = 'ordem';
                $valores['oq'] = $item->tipo_negocio;
                $qtde = 10;
            }
            $filtro = $this->_inicia_filtros( $url, $valores );
            $itens = $this->imoveis_mongo_model->get_itens( $filtro->get_filtro(), $ordem, $a, 0, $qtde );
            if ( isset($itens['itens']) )
            {
                $this->load->library('lista_normal');
                if ( $historico )
                {
                    foreach ( $itens['itens'] as $item_r )
                    {
                        $retorno[] = $this->lista_normal->set_item_relacionado($item_r, 0,0,1);
                    }
                }
                else
                {
                    $retorno = $this->lista_normal->set_itens_relacionado_ficha($itens);
                    
                }
            }
            else
            {
                $retorno = NULL;
            }
        }
        else
        {
            $retorno = NULL;
        }
        
        return $retorno;
    }
    
    public function set_relacionados_imobiliaria( $item )
    {
        if ( $item )
        {
            
            $url = '';
//            $valores['cidade'] = $item->cidade_link;
//            $valores['tipo'] = $item->imoveis_tipos_link;
            $valores['id_empresa'] = $item->id_empresa;
            $retorno = NULL;
            $valores['id_negativo'] = array($item->id);
            $qtde = 12;
            $a = -1;
            $filtro = $this->_inicia_filtros( $url, $valores );
            $itens = $this->imoveis_mongo_model->get_itens( $filtro->get_filtro(), 'ordem', $a, 0, $qtde );
            if ( isset($itens['itens']) )
            {
                $this->load->library('lista_normal');
                $retorno = $this->lista_normal->set_itens_relacionado_ficha($itens, $item);
            }
            else
            {
                $retorno = NULL;
            }
        }
        else
        {
            $retorno = NULL;
        }
        
        return $retorno;
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
        $this->_inicia_geral();
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
    public function imobiliarias ( $coluna = 'empresas.ordenacao DESC,empresas.data_portal ASC', $ordem = '' )
    {
        $this->requisita_bd_sessao();
        $this->load->model('empresas_model');
        $this->_inicia_geral();
        //$filtro = $this->filtros_padrao(NULL,FALSE);
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
        $this->titulo = 'Imobiliárias anunciantes em '.$this->cidade->nome.': Encontre a sua';
        $this->description = 'As imobiliárias de '.$this->cidade_set->nome.' reunidas em um só lugar. Faça sua busca por uma imobiliária específica ou por todos seus imóveis na cidade.';
        $config['titulo'] = $this->titulo;
        $this->lista_normal->inicia( $config );
        $data['listagem'] = $this->lista_normal->get_html_imobiliarias($this->cidade_set->link);
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
                    
                    ->set_menu($this->set_menu())
                    ->set_facebook($this->facebook)
                    ->set_plus($this->plus)
                    ->set_link_bairro($this->link_bairro_visitados)
                    ->set_include('css/3_3/imobiliarias.css', TRUE)
                    ->set_include('js/3_0/imobiliarias.js', TRUE)
                    ->set_logo($this->logo_principal)
                ->set_header_extra($this->cidade->header_tag)
                    ->set_titulo($this->titulo.$this->titulo_padrao)
                    ->set_keywords($this->description.$this->titulo_padrao)
                    ->set_description($this->description)
                    ->set_analytics($this->cidade->instrucoes_head)
                    ->set_google_tag($this->cidade->google_tag)
                    ->set_sobre($this->cidade->sobre, $this->cidade->nome, $this->cidade->portal, $this->cidade->gentilico, $this->cidade->link, $this->cidade->uf, $this->cidade->link_prefeitura)
                    ->view('resultado_lista', $data, 'layout/layout'); 
    }
    
    public function estatistica()
    {
        $this->requisita_bd_sessao();
        $this->load->model(array('imoveis_model','cidades_model'));
        $this->_inicia_geral();
        //$this->set_pageviews(strtolower(__FUNCTION__) );
        $data['itens'] = $this->imoveis_model->get_estatistica($this->cidade->id);
        $data['cidade'] = array('link' => $this->cidade->link, 'titulo' => $this->cidade->nome);
        $menu_itens = $this->set_menu();
        $this->titulo .= 'Estatisticas de valor por bairros na cidade de '.$this->cidade->nome;
        $this->description = $this->titulo;
        $this->layout
                    ->set_menu($menu_itens)
                    ->set_facebook($this->facebook)
                    ->set_plus($this->plus)
                    ->set_link_bairro($this->link_bairro_visitados)
                    ->set_include('js/3_0/encontre.js', TRUE)
                    //->set_include('js/3_0/publicidade.js', TRUE)
                    ->set_logo($this->logo_principal)
                ->set_header_extra($this->cidade->header_tag)
                    ->set_titulo($this->titulo.$this->titulo_padrao)
                    ->set_keywords($this->description.$this->titulo_padrao)
                    ->set_description($this->description)
                    ->set_analytics($this->cidades_model->get_item_analytics_por_portal($this->id_cidade))
                    ->set_google_tag($this->cidades_model->get_item_google_tag_por_portal($this->id_cidade))
                    ->set_sobre($this->cidade->sobre, $this->cidade->nome, $this->cidade->portal, $this->cidade->gentilico, $this->cidade->link, $this->cidade->uf, $this->cidade->link_prefeitura)
                    ->set_favoritos($this->get_favoritos(FALSE))
                    ->view('estatistica', $data, 'layout/layout'); 
    }
    
    /**
     * pagina anuncie
     */
    public function anuncie()
    {
        $this->requisita_bd_sessao();
        $this->load->model(array('imoveis_model','cidades_model'));
        $this->_inicia_geral();
        $dados = $this->input->post(NULL,TRUE);
        if ( isset($dados) && count($dados) > 1 && $dados['validador'] == '' )
        {
            $array_contato = array(
                                    'data' => time() , 
                                    'nome' => $dados['nome'] , 
                                    'email' => $dados['email'] , 
                                    'assunto' => 'Informações de contratação' , 
                                    'mensagem' => '' , 
                                    'fone' => $dados['telefone'] , 
                                    'cidade' => $dados['cidade'],
                                    'origem' => 'A1',
                                    'portal'        => substr($this->cidade->portal, 11),
                                    );
            $this->load->model('cadastro_model');
            $this->cadastro_model->adicionar_contato($array_contato);
            $cad_email = $this->cadastro_model->get_item_email($dados['email']);
            //var_dump( $cad_email );
            if ( $cad_email == 0 )
            {
                //$f_cidade = '( estados.uf LIKE "'.$conteudo['remetente']['uf'].'" OR estados.nome LIKE "'.$conteudo['uf'].'") AND cidades.nome LIKE "'.$conteudo['cidade'].'"';
                //$city = $this->cidades_model->get_cidade_estado($f_cidade);
                $array_cad = array(
                                        'data'      => time() , 
                                        'id_canal'  => '29' , 
                                        'nome'      => $dados['nome'] , 
                                        'email'     => $dados['email'],  
                                        'fone'      => $dados['telefone'],  
                                        'cidade'    => $dados['cidade'], 
                                        'senha'     => '17het3',  
                                        'news'      => isset($dados['aceito']) ? 1 : 0, 
                                        'status'    => '0', 
                                        'id_origem' => '28', 
                                        'datatu'    => date('Y-m-d H:i')
                    );
                if ( isset($city) )
                {
                    $array_cad['id_pais']   = $city->pais;
                    $array_cad['id_cidade'] = $city->id;
                    $array_cad['estado']    = $city->uf;
                }
                $this->cadastro_model->adicionar($array_cad);
            }
            else
            {
                $d = array( 'datatu' => date('Y-m-d H:i') );
                $f = array( 'email' => $dados['email'] );
                $this->cadastro_model->editar( $d, $f );
            }
            if ( isset($dados['aceito']) && $dados['aceito'] == 1 )
            {
                
            }
            //var_dump($dados);die();
            $inc['mensagem'] = '<img src="'.str_replace('/m','',base_url()).'imagens/'.$this->logo_principal.'"> <img src="http://www.portaisimobiliarios.com.br/imagens/logo.png">'.PHP_EOL;
            $inc['mensagem'] .= '<hr>'.PHP_EOL;
            $inc['mensagem'] .= '<br><br>';
            $inc['mensagem'] .= '<br>'.PHP_EOL;
            $inc['mensagem'] .= '<br>Contato feito por:'.PHP_EOL;
            $inc['mensagem'] .= '<br>Nome: '.$dados['nome'].PHP_EOL;
            $inc['mensagem'] .= '<br>E-mail: '.$dados['email'].PHP_EOL;
            $inc['mensagem'] .= isset($dados['telefone']) ? '<br>Telefone: '.$dados['telefone'] : ''.PHP_EOL;
            if ( isset($dados['cidade']) )
            {
                $inc['mensagem'] .= '<br>Cidade: '.$dados['cidade'].PHP_EOL;
            }
            $inc['mensagem'] .= '<br>Tipo: '.$dados['tipo_contato'].PHP_EOL;	
            $inc['mensagem'] .= '<br>Data: '.date('d/m/Y H:i').PHP_EOL;	
            $inc['mensagem'] .= '<br>Mensagem:'.PHP_EOL;
            $inc['mensagem'] .= '<br>'.$dados['mensagem'].PHP_EOL;
            $inc['mensagem'] .= '<br>'.PHP_EOL;
            $inc['reply'] = array($dados['email'],$dados['nome']);
            $inc['nome'] = $dados['nome'];
            
            $inc['assunto'] = 'Informações de Contratação - '.substr($this->cidade->portal, 11);
            $inc['to'][] = 'comercial@pow.com.br';
            $inc['to'][] = 'programacao@pow.com.br';
            $inc['portal'] = substr($this->cidade->portal, 11);
            $inc['from'] = $dados['email'];
            $this->envio($inc);
//            var_dump($inc);die();
            echo '<script type="text/javascript">alert("Sua mensagem foi enviada com sucesso.")</script>';
        }
        
        
        $dominio = 'curitiba_pr';
        $menu = str_replace('localhost','192.168.1.20',file_get_contents(getcwd().'/application/views/menus/'.$dominio));
        $valores = array();
        $this->titulo .= 'Anuncie seu imóvel';
        $this->description = 'Amplie as oportunidades de anunciar os seus imóveis com as nossas soluções de divulgação. Conheça nossos serviços e entre em contato conosco!';
        $data['url'] = base_url().strtolower(__FUNCTION__);
        $data['qtde'] = number_format($this->imoveis_model->get_total_itens(), 0, ',', '.');
        $data['qtde_portais'] = number_format($this->cidades_model->get_total_itens('cidades.portal <> ""'), 0, ',', '.');
        $data['desconto'] = ( $this->cidade->desconto * 100 );
        $data['cidade'] = $this->cidade->nome;
        $data['cobrar'] = $this->cidade->cobrar;
        $ok = $this->input->get( 'ok', TRUE );
        if( $ok )
        {
            $this->layout->set_tag_adwords($this->cidade->tag_adwords);
        } 
        //$data['bairros'] = $this->link_bairro_visitados;
//        var_dump(substr($this->cidade->portal, 11));
        $this->layout
                    ->set_menu($this->set_menu())
                    ->set_facebook($this->facebook)
                    ->set_plus($this->plus)
                    ->set_link_bairro($this->link_bairro_visitados)
                    ->set_include('js/3_3/anuncie.js', TRUE)
                    ->set_include('css/3_3/anuncie.css', TRUE)
                    ->set_logo($this->logo_principal)
                ->set_header_extra($this->cidade->header_tag)
                    ->set_titulo($this->titulo.$this->titulo_padrao)
                    ->set_keywords($this->titulo_padrao)
                    ->set_description($this->description)
                    ->set_analytics($this->cidades_model->get_item_analytics_por_portal($this->id_cidade))
                    ->set_google_tag($this->cidades_model->get_item_google_tag_por_portal($this->id_cidade))
                    ->set_sobre($this->cidade->sobre, $this->cidade->nome, $this->cidade->portal, $this->cidade->gentilico, $this->cidade->link, $this->cidade->uf, $this->cidade->link_prefeitura)
                    ->set_favoritos($this->get_favoritos(FALSE))
                    ->view('anuncie', $data, 'layout/layout'); 
    }
    
    public function consulta_cadastro( $email )
    {
        $this->requisita_bd_sessao();
        $this->load->model('cadastro_model');
        $email_ret = $this->cadastro_model->get_item_por_email($email);
        if ( isset($email_ret) )
        {
            echo json_encode($email_ret);
        }
        else
        {
            echo 0;
        }
    }
    
    /**
     * pagina encontre, pode ser resultado de post e apresentação
     */    
    public function encontre ( )
    {
        $this->requisita_bd_sessao();
        $this->load->model(array('imoveis_naoencontrei_model', 'cidades_model', 'cadastro_model','bairro_model','' ));
        $dados = $this->input->post(NULL, TRUE);
        if ( $dados && empty($dados['validador']) )
        {
            $this->load->model('cadastro_model');
            $cad_email = $this->cadastro_model->get_item_email($dados['remetente']['email']);
            if ( $cad_email == 0 )
            {
                $f_cidade = 'cidades.nome LIKE "'.$dados['remetente']['cidade'].'"';
                $city = $this->cidades_model->get_cidade_estado($f_cidade);
                $array_cad = array(
                                        'data'      => time() , 
                                        'id_canal'  => '29' , 
                                        'nome'      => $dados['remetente']['nome'] , 
                                        'email'     => $dados['remetente']['email'],  
                                        'fone'      => isset($dados['remetente']['telefone']) ? $dados['remetente']['telefone'] : 0,  
                                        'cidade'    => isset($dados['remetente']['cidade']) ? $dados['remetente']['cidade'] : 0, 
                                        'estado'    => isset($dados['remetente']['estado']) ? $dados['remetente']['estado'] : 0, 
                                        'senha'     => '17het3',  
                                        'news'      => isset($dados['aceito']) && ! empty($dados['aceito']) ? 1 : 0 , 
                                        'status'    => '0', 
                                        'id_origem' => '2', 
                                        'datatu'    => date('Y-m-d H:i')
                    );
                if ( isset($city) )
                {
                    $array_cad['id_pais']   = $city->pais;
                    $array_cad['id_cidade'] = $city->id;
                    $array_cad['estado']    = $city->uf;
                }
                $this->cadastro_model->adicionar($array_cad);
            }
            else
            {
                $d['datatu'] = date('Y-m-d H:i');
                if( isset($dados['remetente']['telefone']) )
                {
                    $d['fone'] = $dados['remetente']['telefone'];
                }
                if( isset($dados['remetente']['cidade']) )
                {
                    $d['cidade'] = $dados['remetente']['cidade'];
                }
                if( isset($dados['remetente']['estado']) )
                {
                    $d['estado'] = $dados['remetente']['estado'];
                }
                if( isset($dados['remetente']['nome']) )
                {
                    $d['nome'] = $dados['remetente']['nome'];
                }
                //var_dump($d);
                
                $f = array( 'email' => $dados['remetente']['email'] );
                $this->cadastro_model->editar( $d, $f );
            }
            $id_cadastro = $this->cadastro_model->get_item_id_por_email( $dados['remetente']['email'] );
            $pedido = ( isset($dados['observacao']) ? $dados['observacao'] : '' ).'<br>'.PHP_EOL.'Campos selecionados:<br>'.PHP_EOL;
            if ( isset($dados['pow']['oq']) && ! empty($dados['pow']['oq']) )
            {
                $oq = explode('-',$dados['pow']['oq']);
                $oq = array_unique($oq);
                $oq = implode(',',$oq);
                $pedido .= ' o que busca: '.$oq.'<br>'.PHP_EOL;
            }
            if ( isset($dados['tipo']) && ! empty($dados['tipo']) )
            {
                $pedido .= ' Tipo: '.str_replace('-', ', ', $dados['tipo']).'<br>'.PHP_EOL;
            }
            if ( isset($dados['pow']['cidade_']) && ! empty($dados['pow']['cidade_']) )
            {
                //var_dump()
                $ci = explode('-',$dados['pow']['cidade_']);
                $ci = array_unique($ci);
                $ci = implode(',',$ci);
                $pedido .= ' Cidade: '.$ci.'<br>'.PHP_EOL;
            }
            if ( isset($dados['bairro']) && ! empty($dados['bairro']) )
            {
                $ba = explode('-',$dados['bairro']);
               
                foreach( $ba as $b )
                {
                    $fi = 'bairros.link = "'.$b.'"';
                    $bairro[] = $this->bairro_model->get_item_nome($fi);
                }
                $pedido .= ' Bairros: '.implode(',',$bairro).'<br>'.PHP_EOL;
            }
            if ( isset($dados['pow']['valormin']) && $dados['pow']['valormin'] > 0 )
            {
                $pedido .= ' valor minimo: '.$dados['pow']['valormin'].'<br>'.PHP_EOL;
            }
            if ( isset($dados['pow']['valormax']) && $dados['pow']['valormax'] < 10000000 )
            {
                $pedido .= ' valor maximo: '.$dados['pow']['valormax'].'<br>'.PHP_EOL;
            }
            if ( isset($dados['pow']['quartos']) && ! empty($dados['pow']['quartos']) )
            {
                $pedido .= ' Quartos: '.$dados['pow']['quartos'].'<br>'.PHP_EOL;
            }
            if ( isset($dados['pow']['vagas']) && ! empty($dados['pow']['vagas']) )
            {
                $pedido .= ' Vagas: '.$dados['pow']['vagas'].'<br>'.PHP_EOL;
            }
            
            $data_nao = array(
                            'id_cadastro' => $id_cadastro,
                            'data' => time(),
                            'pedido' => $pedido,
                            'finalidade' => isset($oq) ? $oq : '',
                            'cidade_interesse' => isset($ci) ? $ci : '',
                            );
            //var_dump($data_nao);die();
            
            $insert = $this->imoveis_naoencontrei_model->adicionar($data_nao);
            if( isset($insert) && $insert )
            {
                echo "<script>alert('Solicitação enviada com sucesso!')</script>";
            }
        }
        $this->_inicia_geral();
        $menu_itens = $this->set_menu();
        $valores = array();
        $data['url'] = base_url().strtolower(__FUNCTION__);
        $data['insert'] = isset($insert) ? TRUE : FALSE;
        $filtro = $this->_inicia_filtros_encontre(  );
        $data['filtro'] = $filtro->get_html_encontre(  );
        $data['sobre'] = array('nome' => $this->cidade_set->nome, 'portal' => $this->cidade_set->portal);
        $data['parametros'] = $this->imoveis_naoencontrei_model->get_item();
        $this->titulo .= 'Não encontrou o imóvel desejado? Nós ajudamos';
        $this->description = 'Encontre o imóvel que você quer de acordo com suas necessidades. Descreva as caracteristicas que sejam importantes e nós buscamos para você.';
        $ok = $this->input->get( 'ok', TRUE );
        if( $ok )
        {
            $this->layout->set_tag_adwords($this->cidade->tag_adwords);
        }    
        $data['encaminhado'] = isset($_GET['encaminhado']) ? TRUE : FALSE;
        $this->layout
                    ->set_menu($menu_itens)
                    ->set_facebook($this->facebook)
                    ->set_plus($this->plus)
                    ->set_link_bairro($this->link_bairro_visitados)
                    ->set_include('js/3_0/encontre.js', TRUE)
                    ->set_include('js/3_3/mascara.min.js', TRUE)
                    ->set_include('css/3_3/encontre.css', TRUE)
                    ->set_logo($this->logo_principal)
                ->set_header_extra($this->cidade->header_tag)
                    ->set_titulo($this->titulo.$this->titulo_padrao)
                    ->set_keywords($this->titulo_padrao)
                    ->set_description($this->description)
                    ->set_analytics($this->cidades_model->get_item_analytics_por_portal($this->id_cidade))
                    ->set_google_tag($this->cidades_model->get_item_google_tag_por_portal($this->id_cidade))
                    ->set_sobre($this->cidade->sobre, $this->cidade->nome, $this->cidade->portal, $this->cidade->gentilico, $this->cidade->link, $this->cidade->uf, $this->cidade->link_prefeitura)
                    ->set_favoritos($this->get_favoritos(FALSE))
                    ->view('encontre_para_mim', $data, 'layout/layout'); 
    }
    
    /**
     * pagina favoritos com parametros de url
     * @param string $coluna -  coluna de ordem
     * @param string $ordem - ordem
     */
    public function favoritos($coluna = '_id', $ordem = 'DESC')
    {
        $this->_inicia_geral();
        $data['favoritos'] = $this->get_favoritos(TRUE, 'return');
        $url = base_url().'index/favoritos/'.$coluna.'/'.$ordem.'/?';
        $this->titulo .= 'Imóveis Favoritos ';
        $this->description = $this->titulo;
        $menu_itens = $this->set_menu();
        $ok = $this->input->get( 'ok', TRUE );
        if( $ok )
        {
            $this->layout->set_tag_adwords($this->cidade->tag_adwords);
        }
        $this->layout
                    ->set_menu($menu_itens)
                    ->set_facebook($this->facebook)
                    ->set_plus($this->plus)
                    ->set_logo($this->logo_principal)
                    ->set_titulo($this->titulo.$this->titulo_padrao)
                    ->set_keywords($this->description.$this->titulo_padrao)
                    ->set_description($this->description)
                ->set_header_extra($this->cidade->header_tag)
                    ->set_analytics($this->cidade->instrucoes_head)
                    ->set_google_tag($this->cidade->google_tag)
                    ->set_sobre($this->cidade->sobre, $this->cidade->nome, $this->cidade->portal, $this->cidade->gentilico, $this->cidade->link, $this->cidade->uf, $this->cidade->link_prefeitura)
                    ->set_include('js/3_3/filtro.js', TRUE)
                    ->set_include('js/3_3/favoritos.js', TRUE)
                    ->set_include('css/3_3/favoritos.css', TRUE)
                    ->set_include('js/3_3/jquery.mobile.touch.min.js', TRUE)
                    ->view('favorito_lista', $data, 'layout/layout'); 
    }
    
    
    public function favorito ( $id_imovel = NULL, $log = FALSE )
    {
        $retorno = array('status' => FALSE, 'mensagem' => 'Não foi possivel inserir este imóvel nos seus favoritos, habilite cookies para este site, ems eus navegador.', 'acao' => 'inserir' );
        if ( isset($id_imovel) )
        {
            $this->set_log_mongo($id_imovel, $this->set_local($log), 'favorito');
            if ( ! ( isset($_COOKIE['imoveis_favoritos']) && array_key_exists($id_imovel, $_COOKIE['imoveis_favoritos']) ) )
            {
                $array = array(
                           'name'   => 'imoveis_favoritos['.$id_imovel.']',
                           'value'  => $id_imovel,
                           'expire' => 432000,
                           'path'   => '/'
                            );
                set_cookie($array);
                $retorno['status'] = TRUE;
                $retorno['acao'] = 'inserir';
                $retorno['mensagem'] = 'inseriu';
            }
            else
            {
                $this->deleta_favorito($id_imovel);
                $retorno['status'] = TRUE;
                $retorno['acao'] = 'deletar';
                $retorno['mensagem'] = 'deletou';
            }
        }
        echo json_encode($retorno);
    }
    
    public function verifica_favoritos()
    {
        $status = $this->get_favoritos(FALSE, 'array');
        if ( ! isset($status) )
        {
            $retorno['status'] = FALSE;
            $retorno['tela'] = $this->get_favoritos(TRUE, 'return');
        }
        else
        {
            $retorno['status'] = TRUE;
        }
        echo json_encode($retorno);
    }
    
    public function get_favoritos ( $monta_retorno = TRUE, $retorno_tipo = 'echo' )
    {
        $retorno = $this->lista_normal->set_favoritos( $monta_retorno, $retorno_tipo );
        if ( $retorno_tipo == 'echo' )
        {
            echo $retorno;
        }
        else
        {
            return $retorno;
        }
    }
    
    public function deleta_favorito ( $id_imovel = NULL )
    {
        if ( isset($id_imovel) )
        {
            delete_cookie('imoveis_favoritos['.$id_imovel.']');
        }
        else
        {
            $favoritos = $this->get_favoritos(FALSE, 'array');
            if ( isset($favoritos) )
            {
                foreach($favoritos as $f)
                {
                    delete_cookie('imoveis_favoritos['.$f.']');
                }
            }
            
        }
        
    }
    
    public function filtros_padrao ( $oq = NULL, $cidade = TRUE, $cidade_set = TRUE, $id_empresa = FALSE )
    {
        if ( isset($oq) && ! empty($oq) )
        {
            $filtro['oq'] = 'imoveis.'.( isset($oq) ? $oq : 'venda' ).' = 1 ';
        }
        if ( $cidade )
        {
            $filtro['cidade'] = array( 'tipo' => 'where', 	'campo' => 'cidades.link',     'valor' => ($cidade_set) ? $this->cidade_set->link : $this->cidade->link );
        }
        if ( $id_empresa )
        {
            $filtro['empresa'] = array( 'tipo' => 'where', 	'campo' => 'empresas.id',     'valor' => $id_empresa );
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
    
    /**
     * Classe que inicia os filtros da pagina principal
     * @param string $url
     * @param array $valores
     * @return object
     */
    private function _inicia_filtros_vertical($url = '', $valores = array(), $ativa_log = FALSE, $classe = 'imoveis' )
    {
        $config['url_mascara'] = base_url().$classe.'/[cidade]/[negocio]/[tipo]/[bairro]/[caracteristicas]/';
        $padrao = array();//$this->_filtro_padrao();
        $filtro_links = array();//$this->_filtro_json();
        $de = '';
        $chaves = array(
                        'local' => 1,
                        'oq' => 2,
                        'tipo' => 3,
                        'bairro' => 4,
                        'caracteristicas' => 5,
                        'comodos' => 6,
                        'valor' => 7,
                        'id' => 8,
                        );
        if ( isset($valores['cidade']) )
        {
            $chaves['local'] = $chaves['local'] + 10;
        }
        $valores['id_empresa'] = (isset($valores['id_empresa']) && ! empty($valores['id_empresa']) ) ? $valores['id_empresa'] : NULL;
        if ( isset($valores['id']) && ! empty($valores['id']) ) 
        {
            $id = $valores['id']; 
            unset($valores); 
            $valores['id'] = $id; 
        }
        elseif( isset($valores['id_empresa'])  ) 
        {
            $config['selecionados']['itens']['id_empresa'] = $valores['id_empresa'];
            if ( ! $valores['cidade'] ) 
            { 
                $valores['cidade'] = 0; 
            }
            $this->load->model('empresas_model');
            $config['itens']['rotulo'][] = array( 'grupo' => 'local', 'itens' => array(
                                            array( 'name' => 'id_empresa_n',      'titulo' => 'Agente Imobiliário ',             'tipo' => 'so_empresa',     'valor' => '', 'selecionado' =>    $this->empresas_model->get_item_nome_empresa($valores['id_empresa']), 'classe' => 'empresa'),
                                        ) );
        } 
        else  
        { 
            $valores['cidade'] = $this->cidade_set->link;  
        } 
        $log['cidade'] = $valores['cidade'];
        if ( isset($valores['oq']) && ! empty($valores['oq']) && $valores['oq'] != '0' )
        {
            $chaves['oq'] = $chaves['oq'] + 90;
            $oq = $this->_set_oq($valores['oq'], ( isset($valores['id_empresa']) ? $valores['id_empresa'] : NULL ) );
            $valores['oq'] = 1;
            $log['negocio'] = $oq['selecionado']->id;
            $filtro_links[] = $oq['selecionado']->id.' = 1';
            $group_links = 'bairro';
            $config['oq'] = $oq['selecionado']->id;
            $filtro_prox['oq'] = $oq['selecionado']->id;
        }
        else
        {
            $group_links = 'tipo';
            $oq = $this->_set_oq();
        }
        if ( isset( $valores['tipo'] ) && $valores['tipo'] != '0')
        {
            
            $chaves['tipo'] = $chaves['tipo'] + 90;
            $valores['tipo'] = explode('-',$valores['tipo']);
            $log['tipos'] = $valores['tipo'];
            foreach( $valores['tipo'] as $t_f )
            {
                $filtro_links[] = 'imoveis_tipos.link LIKE "'.$t_f.'"';
            }
            $group_links = 'bairro';
            $filtro_prox['tipo'] = $valores['tipo'];
        }
        if ( isset( $valores['bairro'] ) && ! empty($valores['bairro']) && $valores['bairro'] != '0' )
        {
            $valores['bairro'] = explode('-',$valores['bairro']);
            $log['bairros'] = $valores['bairro'];
            foreach ( $valores['bairro'] as $bairros )
            {
                //$bairro[] = $this->bairro_model->get_item_nome_por_link($bairros, $this->cidade_set->id);
            }
            $filtro_links[] = 'bairros.link != ""';
            $group_links = 'bairro';
            $filtro_prox['bairro'] = $valores['bairro'];
            /**
             * @since 2015-08-05
             */
            //$this->load->model('bairros_views_model');
            //$this->bairros_views_model->set_log( $valores['bairro'], $this->cidade_set->id );
        }
        $log['valor_min'] = isset($valores['valormin']) ? $valores['valormin'] : NULL;
        $log['valor_max'] = isset($valores['valormax']) ? $valores['valormax'] : NULL;
        if ( isset($valores['valormax']) && ( $valores['valormax'] == 10000000 || $valores['valormax'] == 20000 ) )
        {
            $chaves['valor'] = $chaves['valor'] + 90;
            unset($valores['valormax']);
        }
        if ( isset($valores['caracteristicas']) && $valores['caracteristicas'] != '0' )
        {
            $chaves['caracteristicas'] = $chaves['caracteristicas'] + 90;
            $array_caracteristicas = explode('-', $valores['caracteristicas']);
            $conta_quartos = 0;
            foreach ( $array_caracteristicas as $chave_caracteristicas )
            {
                if ( strstr($chave_caracteristicas, '_x_') )
                {
                    $a = explode('_x_', $chave_caracteristicas);
                    if ( ! ( isset($a_anterior) && $a_anterior[1] > $a[1] ) )
                    {
                        $valores[$a[0]] = $a[1];
                        $marcador_maior = $a[1] == 4 ? '>=' : NULL;
                    }
                    $a_anterior = $a;
                    $conta_quartos++;
                }
                else
                {
                    $valores[$chave_caracteristicas] = 1;
                }
            }
        }
        //var_dump($valores);
        $valores = $valores;
        $caracteristicas_selecionadas = $this->_set_selecionado('caracteristicas', isset($valores['caracteristicas']) ? $valores['caracteristicas'] : NULL);
        //var_dump($caracteristicas_selecionadas);
        if ( isset($caracteristicas_selecionadas) && count($caracteristicas_selecionadas) > 0  )
        {
            $complemento_titulo_caracteristicas = '';
            foreach( $caracteristicas_selecionadas as $conta => $c_s )
            {
                $complemento_titulo_caracteristicas .= ( $conta ? ', ' : ' ' ).$c_s->descricao;
            }
            $complemento_titulo_caracteristicas .= ', ';
        }
        $config['itens']['rotulo'][$chaves['local']] = array( 'grupo' => 'local', 'itens' => array(
                                            array( 'name' => 'cidade',      'titulo' => 'Cidade ',             'tipo' => 'modal_vazio_unico',     'valor' => '', 'selecionado' => ( $valores['cidade'] ? $this->_set_selecionado('cidade', $this->cidade_set->link) : NULL ), 'classe' => 'cidade'),
                                        ) );
        $config['itens']['rotulo'][$chaves['oq']] = array( 'grupo' => 'oq', 'itens' => array(
                                            array( 'name' => 'oq',          'titulo' => 'Tipo de negócio ',           'tipo' => 'select_hidden_unico',   'valor' => $oq['itens'], 'selecionado' => isset($oq['selecionado']) ? $oq['selecionado'] : NULL,                'classe' => 'form-control oq'),
                                        ) );
        $config['itens']['rotulo'][$chaves['tipo']] = array( 'grupo' => 'tipo', 'itens' => array(
                                            array( 'name' => 'tipo',        'titulo' => 'Tipo de imóvel',               'tipo' => 'select_hidden_multiplo',    'valor' => $this->tipo_model->get_select(), 'selecionado' => $this->_set_selecionado('tipo', isset($valores['tipo']) ? $valores['tipo'] : NULL), 'classe' => 'form-control tipo'),
                                        ) );
        $config['itens']['rotulo'][$chaves['bairro']] = array( 'grupo' => 'bairro', 'itens' => array(
                                            array( 'name' => 'bairro',      'titulo' => 'Bairro ',             'tipo' => 'select_hidden_multiplo',      'valor' => $this->bairro_model->get_select('bairros.cidade = '.$this->cidade_set->id), 'selecionado' => $this->_set_selecionado('bairro', isset($valores['bairro']) ? $valores['bairro'] : NULL),  'classe' => 'form-control bairro' ),
                                        ) );
         $config['itens']['rotulo'][$chaves['caracteristicas']] = array( 'grupo' => 'caracteristicas', 'class' => '',  'itens' => array(
                                            array( 'name' => 'caracteristicas', 'titulo' => 'Caracteristicas: ','tipo' => 'select_hidden_multiplo', 'valor' => $this->_set_valor_caracteristicas(FALSE, TRUE),  'selecionado' => $caracteristicas_selecionadas,   'classe' => 'form-control caracteristicas' ),
                                        ) );
        $config['itens']['rotulo'][$chaves['comodos']] = array( 'grupo' => 'comodos', 'class' => '', 'itens' => array(
                                            array( 'name' => 'vagas',       'titulo' => 'Vagas ',              'tipo' => 'select',     'valor' => $this->_set_valor_numerico(),    'classe' => 'form-control', 'classe_master' => 'col-lg-12 col-sm-12 col-md-12 col-xs-12' ),
                                        ) );
        $config['itens']['rotulo'][$chaves['valor']] = array( 'grupo' => 'valor', 'itens' => array(
                                            array( 'name' => 'valormin',    'titulo' => 'Valor Minimo ',       'tipo' => 'text',      'valor' => array('min' => isset($oq['selecionado']) ? $oq['selecionado']->valor_min : 0, 'max' => isset($oq['selecionado']) ? $oq['selecionado']->valor_max : 10000000,'value' => (isset($valores['valormin']) ?  $valores['valormin'] : '0') ),                                   'classe' => 'form-control', 'classe_master' => 'col-lg-6 col-sm-6 col-md-6 col-xs-6' ),
                                            array( 'name' => 'valormax',    'titulo' => 'Valor Maximo ',       'tipo' => 'text',      'valor' => array('min' => isset($oq['selecionado']) ? $oq['selecionado']->valor_min : 0, 'max' => isset($oq['selecionado']) ? $oq['selecionado']->valor_max : 10000000,'value' => (isset($valores['valormax']) ?  $valores['valormax'] : '10000000') ),                            'classe' => 'form-control', 'classe_master' => 'col-lg-6 col-sm-6 col-md-6 col-xs-6' ),
                                        ) );
         $config['itens']['rotulo'][$chaves['id']] = array( 'grupo' => 'id', 'class' => 'busca-id',  'itens' => array(
                                            array( 'name' => 'id',              'titulo' => 'Código do Imóvel: ',   'tipo' => 'text',       'valor' => '',                                                                                      'classe' => 'form-control id' ),
                                            array( 'name' => 'id_empresa',     'titulo' => '',            'tipo' => 'hidden',     'valor' => isset($valores['id_empresa']) ? $valores['id_empresa'] : '',    'classe' => 'form-control id_empresa', 'classe_master' => '' ),
                                        ) );
        ksort($config['itens']['rotulo']);
        //var_dump($config['itens']['rotulo']);
        $config['itens'][] = array( 'name' => 'id_empresa','where' => array( 'tipo' => 'where', 'campo' => 'imoveis.id_empresa',   'valor' => '' ) );
        if ( isset($oq['selecionado']) )
        {
            $config['itens'][] = array( 'name' => 'oq',     'where' => array( 'tipo' => 'where', 	'campo' => 'tipo_'.( isset($oq['campo']) ? $oq['campo'] : 'venda' ).' ',   'valor' => '' ) );
        }
        $config['itens'][] = array( 'name' => 'tipo',   'where' => array( 'tipo' => 'where', 	'campo' => 'imoveis_tipos_link',       'valor' => '' ) );
        $config['itens'][] = array( 'name' => 'cidade', 'where' => array( 'tipo' => 'where',            'campo' => 'cidades_link',     'valor' => '' ) );
        $config['itens'][] = array( 'name' => 'bairro',  'where' => array( 'tipo' => 'where', 	'campo' => 'bairros_link',  'valor' => '' ) );
        $config['itens'][] = array( 'name' => 'valormin','where' => array( 'tipo' => 'where', 	'campo' => 'imoveis.'.( isset($oq['selecionado']) ? $oq['selecionado']->valor_campo : 'preco_venda').' >=', 'valor' => '' ));
        if ( isset($valores['valormax']) && ($valores['valormax'] !== '10000000' && $valores['valormax'] !== '20000' ) ) 
        {
            $config['itens'][] = array( 'name' => 'valormax','where' => array( 'tipo' => 'where', 	'campo' => 'imoveis.'.( isset($oq['selecionado']) ? $oq['selecionado']->valor_campo : 'preco_venda').' <=', 'valor' => '' ));
        }
        $config['itens'][] = array( 'name' => 'quartos','where' => array( 'tipo' => 'where', 	'campo' => 'quartos ', 'valor' => '' ));
        $config['itens'][] = array( 'name' => 'vagas',  'where' => array( 'tipo' => 'where', 	'campo' => 'imoveis.garagens  >= ', 'valor' => '' ));
        $config['itens'][] = array( 'name' => 'mobiliado','where' => array( 'tipo' => 'where', 	'campo' => 'imoveis.mobiliado', 'valor' => '' ));
        $config['itens'][] = array( 'name' => 'comercial','where' => array( 'tipo' => 'where', 	'campo' => 'imoveis.comercial', 'valor' => '' ));
        $config['itens'][] = array( 'name' => 'residencial','where' => array( 'tipo' => 'where', 	'campo' => 'imoveis.residencial', 'valor' => '' ));
        $config['itens'][] = array( 'name' => 'novo','where' => array( 'tipo' => 'where', 	'campo' => 'imoveis.novo >= ', 'valor' => '' ));
        $config['itens'][] = array( 'name' => 'id',     'where' => array( 'tipo' => 'where', 	'campo' => '_id',            'valor' => '' ));
        $config['itens'][] = array( 'name' => 'id_negativo',     'where' => array( 'tipo' => 'where_not_in', 	'campo' => '_id',            'valor' => '' ));
        $config['itens'] = $config['itens'];
        $config['colunas'] = 2;
        $config['extras']['titulo'] = '';
        $config['extras']['description'] = '';
        if ( isset($oq['selecionado']->descricao_titulo) )
        {
            $config['extras']['titulo'] .= 'Imóveis '.( isset($complemento_titulo_caracteristicas) ? $complemento_titulo_caracteristicas : '' ).$oq['selecionado']->descricao_description.' em '.$this->cidade_set->nome.' - '.$this->cidade_set->uf;
            $oq_description = ' '.$oq['selecionado']->descricao_description;
        }
        else
        {
            $config['extras']['titulo'] .= 'Encontre o seu imóvel em '.$this->cidade_set->nome.' - '.$this->cidade_set->uf;
            $oq_description = '';
        }
        $ti = '';
        $this->load->model('tipo_model');
        if( isset($valores['tipo']) )
        {
            $ti .= '';
            $t = 0;
            $de = '';
            //$config['extras']['titulo'] .= ':';
            $qtde_tipo = count($valores['tipo']);
            foreach( $valores['tipo'] as $tipo)
            {
                if ( $t == 0 )
                {
                    $separador = '';
                }
                elseif( ( $t > 0 ) && ( $t == ($qtde_tipo - 1) ) )
                {
                    $separador = ' ou ';
                }
                else
                {
                    $separador = ', ';
                }
                $tipo_description_titulo = '';//$this->tipo_model->get_item_plural_por_link($tipo);
                $tipo_description_description = '';//$this->tipo_model->get_item_nome_por_link($tipo);
                $ti .= $separador.ucfirst($tipo_description_titulo);
                $de .= $separador.ucfirst($tipo_description_description);
                $t++;
            }
            $tem_tipo = TRUE;
            $config['extras']['description'] = 'Procurando '.  strtolower($de).( isset($complemento_titulo_caracteristicas) ? $complemento_titulo_caracteristicas : '' ).( ! empty($oq_description) ? $oq_description : ' para comprar ou alugar' ).' em '.$this->cidade_set->nome.' - '.$this->cidade_set->uf.'? Confira as opções disponíveis perfeitas para você: área útil, dormitórios, garagens e muito mais.';
            $config['extras']['titulo'] = $ti.( isset($complemento_titulo_caracteristicas) ? $complemento_titulo_caracteristicas : '' ).' '.( isset($oq['selecionado']->descricao_description) ? $oq['selecionado']->descricao_description : '').' em '.$this->cidade_set->nome.' - '.$this->cidade_set->uf;
            
        }
        else
        {
            $config['extras']['description'] = 'Acesse e encontre as melhores opções de imóveis'.$de.$oq_description.( isset($complemento_titulo_caracteristicas) ? $complemento_titulo_caracteristicas : '' ).' em '.$this->cidade_set->nome.'. O imóvel em '.$this->cidade_set->nome.' - '.$this->cidade_set->uf.' que você procura, está aqui!';
        }
        if ( isset($bairro) && count($bairro) < 2 || isset($valores['proximidade']) )
        {
            if ( isset($valores['proximidade']) )
            {
                $this->load->model('bairros_proximidade_model');
                $prox = $this->bairros_proximidade_model->get_item_nome_por_link($valores['proximidade']);
                $l_bairro = ' Proximo '.$prox;
                $tem_no = FALSE;
            }
            else
            {
                $tem_no = TRUE;
                $ba = 0;
                $qtde_bairro = count($bairro);
                $l_bairro = '';
                foreach( $bairro as $b )
                {
                    if ( $ba == 0 )
                    {
                        $separador = '';
                    }
                    elseif( ( $ba > 0 ) && ( $ba == ($qtde_bairro - 1) ) )
                    {
                        $separador = ' ou ';
                    }
                    else
                    {
                        $separador = ', ';
                    }
                    $l_bairro .= $separador.$b;
                    $ba++;
                }
                
            }
            if ( ! isset($oq['selecionado']) )
            {
                $config['extras']['titulo'] = ( isset($ti) && ! empty($ti) ? $ti.( $tem_no ? ' no ' : ' ' ) : 'Imóveis '.( $tem_no ? 'no ' : '') ).$l_bairro.( isset($complemento_titulo_caracteristicas) ? $complemento_titulo_caracteristicas : '' ).' em '.$this->cidade_set->nome.' - '.$this->cidade_set->uf;
            }
            else
            {
                if ( isset($ti) && ! empty($ti) )
                {
                    if ( $oq['selecionado']->id == 'locacao' )
                    {
                        $config['extras']['titulo'] = 'Aluguel de '.$ti.( isset($complemento_titulo_caracteristicas) ? $complemento_titulo_caracteristicas : '' ).( $tem_no ? ' no ' : ' ' ).$l_bairro.' em '.$this->cidade_set->nome.' - '.$this->cidade_set->uf;
                    }
                    else
                    {
                        $config['extras']['titulo'] = $ti.( isset($complemento_titulo_caracteristicas) ? $complemento_titulo_caracteristicas : '' ).' '.$oq['selecionado']->descricao_description.( $tem_no ? ' no ' : ' ' ).$l_bairro.' em '.$this->cidade_set->nome.' - '.$this->cidade_set->uf;
                    }
                }
                else
                {
                    if ( $oq['selecionado']->id == 'locacao' )
                    {
                        $config['extras']['titulo'] = 'Aluguel de imóveis '.( $tem_no ? 'no ' : '' ).$l_bairro.( isset($complemento_titulo_caracteristicas) ? $complemento_titulo_caracteristicas : '' ).' em '.$this->cidade_set->nome.' - '.$this->cidade_set->uf;
                    }
                    else
                    {
                        $config['extras']['titulo'] = 'Imóveis '.$oq['selecionado']->descricao_description.( $tem_no ? ' no ' : ' ' ).$l_bairro.( isset($complemento_titulo_caracteristicas) ? $complemento_titulo_caracteristicas : '' ).' em '.$this->cidade_set->nome.' - '.$this->cidade_set->uf;
                    }
                    
                }
            }
                
            
            $config['extras']['description'] = 'Querendo '.( ! empty($oq_description) ? str_replace('para ', '', $oq_description) : 'comprar ou alugar').' um '.$de.( $tem_no ? ' no ' : ' ').$l_bairro.'? Confira os imóveis disponíveis neste bairro de '.$this->cidade_set->nome.' - '.$this->cidade_set->uf.' com diversas opções que cabem no seu bolso.';
        }
        $config['cidade'] = $this->cidade_set->nome.'/'.$this->cidade_set->uf;
        $config['url'] = $url; 
        $config['valores'] = $valores;
        //$robot = $this->is_robot();
        //$this->load->model('log_pesquisa_portais_model');
        if ( ! isset($robot) )
        {
            if ( $ativa_log )
            {
                //$this->log_pesquisa_portais_model->set_log( $log );
            }
        }
        $filtro_prox['cidade'] = $this->cidade_set->id;
        $filtro_prox['cidade_link'] = $this->cidade_set->link;
        //$links_relacionados = $this->imoveis_model->get_itens_por_cidade_filtro( $this->cidade_set->link, $group_links, $filtro_links, 10, ( isset($filtro_prox) ? $filtro_prox : FALSE ) );
        
        $filtro = $this->filtro->inicia($config);
        //$filtro->links_relacionados = $links_relacionados;
        return $filtro;

    }
    
    /**
     * Classe que inicia os filtros da pagina encontre
     * @param string $url
     * @param array $valores
     * @return object
     */
    private function _inicia_filtros_encontre(  )
    {
        $this->requisita_bd_sessao();
        $this->load->model('parametro_finalidade_model');
        $oq = $this->_set_oq();
        $config['itens']['rotulo'][] = array( 'grupo' => 'oq', 'titulo' => 'O que Busca', 'itens' => array(
                                            array( 'name' => 'oq',          'titulo' => 'Tipo Negócio(Locação, Venda): ',          'tipo' => 'select',   'valor' => $this->parametro_finalidade_model->get_select(), 'selecionado' => isset($oq['selecionado']) ? $oq['selecionado'] : NULL,                'classe' => 'form-control oq', 'default' => 'Selecione o que deseja'),
                                            array( 'name' => 'cidade_',      'titulo' => 'Informe a Cidade onde deseja o imóvel: ','tipo' => 'text',     'valor' => '', 'selecionado' =>    $this->_set_selecionado('cidade', $this->cidade_set->link), 'classe' => 'cidade form-control','default' => 'Cidade onde deseja o imóvel:'),
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
    
    private function _set_valor_caracteristicas( $valor = FALSE, $retorno_itens = TRUE )
    {
        
        $itens = array(
                        (object)array( 'id' => 'mobiliado',     'descricao' => 'Mobiliado' ),
                        (object)array( 'id' => 'comercial',     'descricao' => 'Comercial' ),
                        (object)array( 'id' => 'residencial',   'descricao' => 'Residencial' ),
                        //(object)array( 'id' => 'novo',   'descricao' => 'Imóvel Novo' ),
                        );
        for( $a = 1; 4 >= $a; $a++ )
        {
            $itens[] = (object)array( 'id' => 'quartos_x_'.$a, 'descricao' => $a.' Quartos '.($a == 4 ? '+' : '') );
        }
        if ( $retorno_itens )
        {
            $retorno = $itens;
        }
        else
        {
            $retorno = array();
            if ( isset($valor) && $valor != "0" )
            {
                $valor_busca = $itens;
                $array_valor = explode('-',$valor);
                foreach( $valor_busca as $valores )
                {
                    if ( in_array($valores->id, $array_valor ) )
                    {
                        $retorno[] = $valores;
                    }
                }
            }
            
        }
        
        //var_dump($retorno, $valor, $itens);
        return $retorno;
    }
    
    
    private function _set_valor_numerico( $qtde = 4 )
    {
        for( $a = 0; $qtde >= $a; $a++ )
        {
            $retorno[] = (object)array( 'id' => $a, 'descricao' => $a.'+' );
        }
        return $retorno;
    }
    
    private function _filtro_padrao()
    {
        $config['valores']['vendido'] = 1;
        $config['valores']['reservaimovel'] = 1;
        $config['valores']['locado'] = 1;
        $config['valores']['invisivel'] = 1;
        $config['valores']['vencimento'] = 1;
        $config['valores']['bloqueio'] = 1;
        $config['valores']['servico_ini'] = time();
        $config['valores']['servico_fim'] = time();
        //$config['valores']['random'] = 1;
        $config['itens'] = array(
                                array( 'name' => 'vencimento',   'tipo' => 'inativo',   'where' => '(imoveis.vencimento = "0000-00-00" OR (imoveis.vencimento <> "0000-00-00" AND imoveis.vencimento >= "'.date('Y-m-d').'") )' ),
                                array( 'name' => 'servico_ini',  'tipo' => 'inativo',   'where' => array( 'tipo' => 'where',  'campo' => 'empresas.servicos_pagina_inicio <',    'valor' => '' ) ), 
                                array( 'name' => 'servico_fim',  'tipo' => 'inativo',   'where' => array( 'tipo' => 'where',  'campo' => 'empresas.servicos_pagina_termino >',       'valor' => '' ) ),
                                array( 'name' => 'reservaimovel','tipo' => 'inativo',   'where' => 'imoveis.reservaimovel = 0 '),
                                array( 'name' => 'vendido',      'tipo' => 'inativo',   'where' => 'imoveis.vendido = 0 '),
                                array( 'name' => 'locado',       'tipo' => 'inativo',   'where' => 'imoveis.locado = 0 '),   
                                array( 'name' => 'bloqueio',     'tipo' => 'inativo',   'where' => 'empresas.bloqueado = 0 '),   
                                array( 'name' => 'invisivel',    'tipo' => 'inativo',   'where' => 'imoveis.invisivel = 0 '),    
                                //array( 'name' => 'random',       'tipo' => 'inativo',   'where' => 'imoveis.ordem_rad between 1 AND 2999999'),    
                                );
        return $config;
    }
    
    private function _filtro_json()
    {
        $config = array(
                                '(imoveis.vencimento = "0000-00-00" OR (imoveis.vencimento <> "0000-00-00" AND imoveis.vencimento >= "'.date('Y-m-d').'") )' ,
                                'empresas.servicos_pagina_inicio < '.time() , 
                                'empresas.servicos_pagina_termino > '.time() ,
                                'empresas.bloqueado = 0 ',   
                                'imoveis.reservaimovel = 0 ',
                                'imoveis.vendido = 0 ',
                                'imoveis.locado = 0 ',   
                                'imoveis.invisivel = 0 ',    
                                //'imoveis.ordem_rad between 1 AND 2999999',    
                                );
        return $config;
    }
    
    private function _set_selecionado( $tipo, $valor = NULL )
    {
        $retorno = NULL;
        if ( isset($valor) )
        {
            switch ( $tipo )
            {
                case 'tipo':
                    if ( is_array($valor) )
                    {
                        $valores = $valor;
                    }
                    else
                    {
                        $valores = explode('-', $valor);
                    }
                    $filtro = '';
                    $conta = 0;
                    foreach ( $valores as $v )
                    {
                        $filtro .= ($conta == 0) ? '"'.$v.'"' : ',"'.$v.'"';
                        $conta++;
                    }
                    $f = 'imoveis_tipos.link IN ('.$filtro.')';
                    $retorno = $this->tipo_model->get_select( $f );
                    break;
                case 'bairro':
                    if ( is_array($valor) )
                    {
                        $valores = $valor;
                    }
                    else
                    {
                        $valores = explode('-', $valor);
                    }
                    $filtro = '';
                    $conta = 0;
                    if ( count($valores) > 0 )
                    {
                        
                        foreach ( $valores as $v )
                        {
                            $filtro .= ($conta == 0) ? "'".$v."'" : ',"'.$v.'"';
                            $conta++;
                        }
                        if ( ! empty( $filtro ) )
                        {
                            $f = 'bairros.link IN ('.$filtro.') AND cidade = '.$this->cidade_set->id;
                            $retorno = $this->bairro_model->get_select( $f );
                        }
                        
                    }
                    break;   
                case 'cidade':
                    $this->requisita_bd_sessao();
                    $this->load->model('cidades_model');
                    $f = 'cidades.link IN ("'.$valor.'")';
                    $retorno = $this->cidades_model->get_select( $f );
                    break;
                case 'caracteristicas':
                    //var_dump($valor);
                    $retorno = $this->_set_valor_caracteristicas( $valor, FALSE );
                    break;
            }    
        }
        
        
        return $retorno;
    }
    
    private function _set_oq($valor = NULL, $id_empresa = NULL)
    {
        $array['itens'] = array(
                        (object)array('id' => 'venda',          'descricao' => 'Venda',         'descricao_titulo' => 'Imóveis a venda',                'descricao_description' => 'à Venda',                 'link' => '/imoveis/'.$this->cidade_set->link.'/venda/',         'qtde' => 0, 'valor_min' => '0', 'valor_max' => '10000000', 'valor_campo' => 'preco' ),
                        (object)array('id' => 'locacao',        'descricao' => 'Locação',       'descricao_titulo' => 'Alugue imóveis',                 'descricao_description' => 'para Alugar',                'link' => '/imoveis/'.$this->cidade_set->link.'/locacao/',       'qtde' => 0, 'valor_min' => '0', 'valor_max' => '20000', 'valor_campo' => 'preco'  ),
                        (object)array('id' => 'locacao_dia',    'descricao' => 'Locação Dia',   'descricao_titulo' => 'Alugue para temporada imóveis',  'descricao_description' => 'para Alugar temporada',      'link' => '/imoveis/'.$this->cidade_set->link.'/locacao_dia/',   'qtde' => 0, 'valor_min' => '0', 'valor_max' => '20000', 'valor_campo' => 'preco'  ),
                        );
        if ( isset($valor) )
        {
            for ( $a = 0; count($array['itens']) > $a; $a++ )
            {
                if ( $array['itens'][$a]->id == $valor )
                {
                    $array['campo']         = $array['itens'][$a]->id;
                    $array['selecionado']   = (object)array( 'id' => $array['itens'][$a]->id, 'descricao' => $array['itens'][$a]->descricao, 'descricao_titulo' => $array['itens'][$a]->descricao_titulo, 'descricao_description' => $array['itens'][$a]->descricao_description, 'valor_min' => $array['itens'][$a]->valor_min, 'valor_max' => $array['itens'][$a]->valor_max, 'valor_campo' => $array['itens'][$a]->valor_campo );
                }
            }
        }
       
        return $array;
    }
    
    
    
    public function get_json( $tipo )
    {
        $valores = $this->input->get('pow',TRUE);
        $filtro = $this->_filtro_json();
        //$valores['oq'] = isset($valores['oq']) && ! empty($valores['oq']) ? $valores['oq'] : 'venda';
        if ( isset($valores['oq']) && ! empty($valores['oq']) )
        {
            if ( strstr( $valores['oq'], '-' ) )
            {
                $oqs = explode('-', $valores['oq']);
                foreach ( $oqs as $oq )
                {
                    if ( ! empty($oq) )
                    {
                        $filtro[] = 'imoveis.'.$oq.' = 1';
                    }
                }
            }
            else
            {
                $filtro[] = 'imoveis.'.$valores['oq'].' = 1';
            }
            
        }
        
        if ( ( isset($valores['valormax']) && $valores['valormax'] && $valores['valormax'] < 10000000 && $valores['valormax'] != 20000 && isset($valores['oq']) && ! empty($valores['oq']) ) )
        {
            if ( strstr( $valores['oq'], '-' ) )
            {
                foreach ( $oqs as $oq )
                {
                    if ( ! empty($oq) )
                    {
                        $filtro[] =  'imoveis.preco_'.$oq.' < '.$valores['valormax'];
                    }
                }
            }
            else
            {
                $filtro[] =  'imoveis.preco_'.$valores['oq'].' < '.$valores['valormax'];
            }
        }
        if ( isset($valores['valormin']) && $valores['valormin'] && $valores['valormin'] > 0 && isset($valores['oq']) && ! empty($valores['oq']) )
        {
            if ( strstr( $valores['oq'], '-' ) )
            {
                foreach ( $oqs as $oq )
                {
                    if ( ! empty($oq) )
                    {
                        $filtro[] =  'imoveis.preco_'.$oq.' > '.$valores['valormin'];
                    }
                }
            }
            else
            {
                $filtro[] =  'imoveis.preco_'.$valores['oq'].' > '.$valores['valormin'];
            }
            
        }
        if ( isset($valores['quartos']) && $valores['quartos'] )
        {
            $filtro[] = 'imoveis.quartos >= '.$valores['quartos'];
        }
        if ( isset($valores['vagas']) && $valores['vagas'])
        {
            $filtro[] = 'imoveis.garagens >= '.$valores['vagas'];
        }
        
        switch ( $tipo )
        {
            case 'tipo':
                $f_cidade = array();
                if ( isset($valores['cidade']) && !empty($valores['cidade']) )
                {
                    $cidades = explode('-',$valores['cidade']);
                    foreach ( $cidades as $c )
                    {
                        $f_cidade[] =  '"'.$c.'"';
                    }
                    $filtro[] = 'cidades.link IN ('.implode(',',$f_cidade).')';
                }
                if ( isset($valores['id_empresa']) && !empty($valores['id_empresa']) )
                {
                    $filtro[] = 'empresas.id = '.$valores['id_empresa'];
                }
                $this->load->model('tipo_model');
                $itens = $this->tipo_model->get_select_json($filtro);
                break;
            case 'bairro': 
                $f_cidade = array();
                if ( isset($valores['cidade']) && !empty($valores['cidade']) )
                {
                    $cidades = explode('-',$valores['cidade']);
                    foreach ( $cidades as $c )
                    {
                        $f_cidade[] =  '"'.$c.'"';
                    }
                }
                if ( count($valores) == 1 )
                {
                    $filtro = 'cidades.link IN ('.implode(',',$f_cidade).')';
                }
                else
                {
                    $filtro[] = 'cidades.link IN ('.implode(',',$f_cidade).')';
                }
                $f_tipo = array();
                if ( isset($valores['tipo']) && ! empty($valores['tipo']) )
                {
                    $tipos = explode('-',$valores['tipo']);
                    foreach ( $tipos as $t )
                    {
                        $f_tipo[] =  '"'.$t.'"';
                    }
                    $filtro[] = 'imoveis_tipos.link IN ('.implode(',',$f_tipo).')';
                }
                if ( isset($valores['id_empresa']) && !empty($valores['id_empresa']) )
                {
                    $filtro[] = 'empresas.id = '.$valores['id_empresa'];
                }
                
                $this->load->model('bairro_model');
                $itens = $this->bairro_model->get_select_json($filtro);
                break;
        }
        //var_dump($itens);
        echo json_encode($itens);
    }
    
    /**
     * 
     * @param array $dados
     * @param string $assunto
     * @param string $local
     * lista de locais:
     * 1n - resultado de busca
     * 2n1 - ficha do imovel visivel
     * 2n2 - ficha do imóvel botao
     * 3n - lista de favoritos 
     * 4n - lista de imobiliarias
     * 5n1 - indica imoveis
     * 6n - solicitar ligação
     * 
     */
    public function email_padrao( $dados = NULL, $assunto = '', $local = '1n', $retorno = 'redirect', $salva = TRUE  )
    {
        if ( ! isset($dados['remetente']['email']) && empty($dados['remetente']['email']) )
        {
            die('Foi executada uma ação inválida, tente preencher os campos obrigatórios e tentar novamente.');
        }
        $this->requisita_bd_sessao();
        $this->load->model(array('spam_model', 'imoveis_model','imoveis_mongo_model', 'empresas_model', 'cadastro_model', 'cidades_model'));
        $f_spam = 'email like "'.$dados['remetente']['email'].'"';
        $item_spam = $this->spam_model->get_item($f_spam);
        if ( isset($dados) && empty($dados['validador']) && ! isset($item_spam) )
        {
            $this->_inicia_geral();
            $inc['assunto'] = 'Contato enviado pelo portal: '.substr($this->cidade->portal, 11).' - '.$assunto;
            $this->set_log($dados['empresa'],25);
            if ( isset($dados['imovel']))
            {
                $imovel = $this->imoveis_mongo_model->get_item( $dados['imovel'], FALSE );
                //$imovel = $this->imoveis_model->get_item_por_id( $dados['imovel'], FALSE );
            }
            else
            {
                //var_dump($dados['empresa']);die();
                $this->load->model('empresas_model');
                $empresa = $this->empresas_model->get_item_por_id( $dados['empresa'], FALSE );
            }
            
            $inc['mensagem'] = '<img src="'.str_replace('/m','',base_url()).'imagens/'.$this->logo_principal.'"> <img src="http://www.portaisimobiliarios.com.br/imagens/logo.png">'.PHP_EOL;
            $inc['mensagem'] .= '<hr>'.PHP_EOL;
            $inc['mensagem'] .= '<br><br>';
            $inc['mensagem'] .= 'Anunciante:<br>'.PHP_EOL;
            if ( $imovel )
            {
                $inc['mensagem'] .= $imovel->imobiliaria_nome.'<br>'.PHP_EOL;
                $inc['mensagem'] .= ( ( isset($imovel->nome_corretor) && ! empty($imovel->nome_corretor) ) ? $imovel->nome_corretor : '').'<br>'.PHP_EOL;
                $inc['mensagem'] .= 'Assunto:'.$assunto.'<br>'.PHP_EOL;
                $inc['mensagem'] .= 'Código do Imóvel : '.$imovel->id.PHP_EOL;
                $inc['mensagem'] .= '<br>Referência do Imóvel : '.$imovel->referencia.PHP_EOL;
                $inc['mensagem'] .= '<br>Endereço da ficha do Imóvel : '.strtolower($this->cidade->portal).'/imovel/0/'.$imovel->id.PHP_EOL;
            }
            else
            {
                $inc['mensagem'] .= $empresa->nome_fantasia.'<br>'.PHP_EOL;
                $inc['mensagem'] .= 'Assunto:'.$assunto.'<br>'.PHP_EOL;
            }
            $inc['mensagem'] .= '<br>'.PHP_EOL;
            $inc['mensagem'] .= '<br>'.PHP_EOL;
            $inc['mensagem'] .= '<br>Contato feito por:'.PHP_EOL;
            $inc['mensagem'] .= '<br>Nome: '.$dados['remetente']['nome'].PHP_EOL;
            $inc['mensagem'] .= '<br>E-mail: '.$dados['remetente']['email'].PHP_EOL;
            $inc['mensagem'] .= isset($dados['remetente']['fone']) ? '<br>Telefone: '.$dados['remetente']['fone'] : ''.PHP_EOL;
            if ( isset($dados['remetente']['cidade']) )
            {
                $inc['mensagem'] .= '<br>Cidade: '.$dados['remetente']['cidade'].'/'.(isset($dados['remetente']['uf']) ? $dados['remetente']['uf'] : '' ).PHP_EOL;
            }
            $inc['mensagem'] .= '<br>Data: '.(isset($dados['data']) ? $dados['data'] : date('d/m/Y H:i')).PHP_EOL;	
            $inc['mensagem'] .= '<br>Deseja receber mensagem via: ';
            $inc['mensagem'] .= '<br>'.(isset($dados['check_telefone']) ? 'Telefone' : '').PHP_EOL;	
            $inc['mensagem'] .= '<br>'.(isset($dados['check_whatsapp']) ? 'WhatsApp' : '').PHP_EOL;	
            $inc['mensagem'] .= '<br>'.(isset($dados['check_email']) ? 'E-mail' : '').PHP_EOL;	
            $inc['mensagem'] .= '<br>Mensagem:'.PHP_EOL;
            $inc['mensagem'] .= '<br>'.$dados['mensagem'].PHP_EOL;
            $inc['mensagem'] .= '<br>'.PHP_EOL;
            $inc['mensagem'] .= '<br>Esse email foi enviado a partir do portal '.substr($this->cidade->portal, 11).' em função de seu cadastro em portal integrante da rede PortaisImobiliarios.com.br'.PHP_EOL;
            $inc['mensagem'] .= '<br>Em caso de dúvidas entre em contato com o POW Internet através'.PHP_EOL;
            $inc['mensagem'] .= '<br>do fone (41) 3382-1581 ou pelo email comercial@pow.com.br'.PHP_EOL;
            $inc['reply'] = array($dados['remetente']['email'],$dados['remetente']['nome']);
            $inc['to_'] = $dados['remetente']['email'];
            $inc['nome'] = $dados['remetente']['nome'];
            
            if ( isset($imovel->empresa_email) && ! empty($imovel->empresa_email) )
            {
                $inc['to'][] = $imovel->empresa_email;
            }
            else
            {
                $inc['to'][] = $empresa->empresa_email;
            }
            if ( isset($imovel->locacao_email) && ! empty($imovel->locacao_email) && $imovel->imovel_para == 'locação') 
            {
                $inc['to'][] = $imovel->locacao_email;
            }
            else
            {
                if ( isset($empresa->locacao_email) )
                {
                    $inc['to'][] = $empresa->locacao_email;
                }
            }
            if ( isset($imovel->email_corretor) && ! empty($imovel->email_corretor)  ) 
            {
                $inc['to'][] = $imovel->email_corretor;
            }
            
            //$inc['to'][] = 'programacao@pow.com.br';
            $inc['portal'] = $this->cidade->portal;
            if(isset( $dados['retorno']) && $dados['retorno'] == 'status' )
            {
                $inc['retorno'] = TRUE;
            }
            $enviado = $this->envio($inc); 
            if ( isset($imovel) && $imovel->sms_limite > 0 )
            {
                switch($imovel->sms_quem)
                {
                    case 0:
                        $sms_parametro['destinatario'] = array();
                        break;
                    case 1:
                        $sms_parametro['destinatario'][] = array(
                                                        'telefone' => $imovel->empresa_telefone_sms,
                                                        );
                        break;
                    case 2:
                        $sms_parametro['destinatario'][] = array(
                                                        'telefone' => $imovel->empresa_telefone_sms,
                                                        );
                        if ( $imovel->sms_corretor )
                        {
                            $sms_parametro['destinatario'][] = array(
                                                            'telefone' => $imovel->celular_corretor,
                                                            );
                        }
                        break;
                    case 3:
                        if ( $imovel->sms_corretor )
                        {
                            $sms_parametro['destinatario'][] = array(
                                                            'telefone' => $imovel->celular_corretor,
                                                            );
                        }
                        break;
                }
                $sms_parametro['empresa'] = array('id' => $dados['empresa'], 'limite' => $imovel->sms_limite );
                $sms_parametro['mensagem'] = array('tipo' => $imovel->tipo, 'id_imovel' => $dados['imovel'], 'referencia' => $imovel->referencia, 'nome' => $dados['remetente']['nome'], 'fone' => $dados['remetente']['fone'], 'portal' => substr($this->cidade->portal,11));
                $this->load->library('sms');
                if ( $salva )
                {
                    $sms = $this->sms->dispara_sms($sms_parametro);
                }
                
            }
            if ( isset($imovel) )
            {
                if ( $imovel->locacao == 1 )
                {
                    $tipo_negocio_item = 'locacao';
                }
                if ( $imovel->locacao_dia == 1 )
                {
                    $tipo_negocio_item = 'locacao_dia';
                }
                if ( $imovel->venda == 1 )
                {
                    $tipo_negocio_item = 'venda';
                }
            }
            else
            {
                $tipo_negocio_item = 0;
            }
            $array_contato = array(
                                    'data'          => time() , 
                                    'id_empresa'    => $dados['empresa'] , 
                                    'nome'          => $dados['remetente']['nome'] , 
                                    'email'         => $dados['remetente']['email'] , 
                                    'assunto'       => $assunto.' - IP: '.$_SERVER['REMOTE_ADDR'] , 
                                    'mensagem'      => $dados['mensagem'] , 
                                    'fone'          => ( isset($dados['remetente']['fone']) ? $dados['remetente']['fone'] : 0) , 
                                    'cidade'        => ( isset($dados['remetente']['cidade']) ? $dados['remetente']['cidade'] : 0) , 
                                    'estado'        => ( isset($dados['remetente']['estado']) ? $dados['remetente']['estado'] : 0) , 
                                    'origem'        => $local ,
                                    'id_item'       => isset($dados['imovel']) ? $dados['imovel'] : '',
                                    'id_cidade'     => isset($imovel->id_cidade) ? $imovel->id_cidade : $this->id_cidade, 
                                    'sms_enviado'   => isset($sms) ? $sms : 0, 
                                    'portal'        => substr($this->cidade->portal, 11),
                                    'id_tipo_item'  => isset($imovel->id_tipo) ? $imovel->id_tipo : 0,
                                    'tipo_negocio_item' => $tipo_negocio_item
                                    //'estado' => $conteudo['uf']
                                    );
            $this->load->model('cadastro_model');
            if ( $salva )
            {
                $id_contato = $this->cadastro_model->adicionar_contato($array_contato);
            }
            $cad_email = $this->cadastro_model->get_item_email($dados['remetente']['email']);
            
            if ( $cad_email == 0 )
            {
                if ( isset( $dados['remetente']['cidade'] ) && ! empty($dados['remetente']['cidade']) && isset( $dados['remetente']['uf'] ) && ! empty($dados['remetente']['uf']) )
                {
                    $f_cidade = '( estados.uf LIKE "'.$dados['remetente']['uf'].'" OR estados.nome LIKE "'.$dados['remetente']['uf'].'") AND cidades.nome LIKE "'.$dados['rementente']['cidade'].'"';
                    $city = $this->cidades_model->get_cidade_estado($f_cidade);
                }
                $array_cad = array(
                                        'data'      => time() , 
                                        'id_canal'  => '29' , 
                                        'nome'      => $dados['remetente']['nome'] , 
                                        'email'     => $dados['remetente']['email'],  
                                        'fone'      => isset($dados['remetente']['fone']) ? $dados['remetente']['fone'] : 0,  
                                        'cidade'    => isset($dados['remetente']['cidade']) ? $dados['remetente']['cidade'] : 0, 
                                        'senha'     => '17het3',  
                                        'news'      => 1, 
                                        'status'    => '0', 
                                        'id_origem' => '2', 
                                        'datatu'    => date('Y-m-d H:i')
                    );
                if ( isset($city) && $city )
                {
                    $array_cad['id_pais']   = $city->pais;
                    $array_cad['id_cidade'] = $city->id;
                    $array_cad['estado']    = $city->uf;
                }
                $id_cadastro = $this->cadastro_model->adicionar($array_cad);
            }
            else
            {
                $id_cadastro = $this->cadastro_model->get_item_id_por_email($dados['remetente']['email']);
                $d = array( 'datatu' => date('Y-m-d H:i') );
                $f = array( 'email' => $dados['remetente']['email'] );
                if ( $salva )
                {
                    $this->cadastro_model->editar( $d, $f );
                }
            }
            
            $data_ip = array(
                            'id_cadastro' => (isset($id_cadastro) ? $id_cadastro : NULL),
                            'id_contato' => (isset($id_contato) ? $id_contato : NULL),
                            'ip' => $_SERVER['REMOTE_ADDR'],
                            'data' => date('Y-m-d H:i'),
                            );
            $this->cadastro_model->adicionar_ip($data_ip);
            if ( $retorno == 'redirect' )
            {
                if ( isset($dados['redirect']) && ! empty($dados['redirect']) )
                {
                    $interrogacao = strstr($dados['redirect'], '?') ? '&' : '/?' ;
                    redirect($dados['redirect'].$interrogacao.'ok=1', 'refresh');
                }
                elseif(isset( $dados['retorno']) && $dados['retorno'] == 'status' )
                {
//                    $enviado['tags'] = $this->cidade->tag_adwords;
                    return $enviado;
                }
                else
                {
                    echo '<script>alert("Obrigado, Seu Contato foi enviado com sucesso");</script>';
                    $this->imovel(0, $dados['imovel'], 'formulario');
                }
            }
            else
            {
                return 'TRUE';
            }
        }
        else
        {
            if ( isset($dados['redirect']) )
            {
                $interrogacao = strstr($dados['redirect'], '?') ? '&' : '?' ;
                redirect($dados['redirect'].$interrogacao.'ok=0', 'refresh');
            }
            else
            {
                redirect(base_url());
            }
        }
    }
    
    
    
    public function email_indica()
    {
        $dados = $this->input->post(NULL, TRUE);
        if ( isset($dados) && empty($dados['validador']) )
        {
            $this->_inicia_geral();
            $imoveis = isset($dados['imoveis']) ? explode('-',$dados['imoveis']) : NULL;
            $emails = isset($dados['emails']) ? explode(',',$dados['emails']) : NULL;
            if ( isset($emails) && count($emails) > 0 )
            {
                if ( isset($imoveis) && count($imoveis) > 0 )
                {
                    foreach( $imoveis as $i )
                    {
                        $this->set_log(trim($i), 98, 'clicks');
                        $imovel[] = $this->imoveis_model->get_item_por_id( trim($i) );
                    }
                }
                else
                {
                    $imovel = array();
                }
                
                $inc['assunto'] = $dados['remetente']['nome'].' - Indicou o Portal '.  substr($this->cidade->portal, 11);
                $inc['mensagem'] = '<img src="'.str_replace('/m','',base_url()).'imagens/'.$this->logo_principal.'"> <img src="http://www.portaisimobiliarios.com.br/imagens/logo.png">'.PHP_EOL;
                $inc['mensagem'] .= '<hr>'.PHP_EOL;
                $inc['mensagem'] .= '<br><br>';
                $inc['mensagem'] .= 'Olá, '.$dados['remetente']['nome'].' indicou os imóveis abaixo para você: <br><br>'.PHP_EOL;
                $contador = 1;
                foreach ( $imovel as $i )
                {
                    $this->set_log($i->id, 74);
                    $inc['mensagem'] .= '<br>';
                    $inc['mensagem'] .= $contador.' - Código do Imóvel : '.$i->id.PHP_EOL;
                    $inc['mensagem'] .= '<br>Referência do Imóvel : '.$i->referencia.PHP_EOL;
                    $inc['mensagem'] .= '<br>Tipo : '.$i->tipo.' - '.$i->uso.PHP_EOL;
                    $inc['mensagem'] .= '<br>Bairro : '.$i->bairro.PHP_EOL;
                    $inc['mensagem'] .= '<br>Endereço da ficha do Imóvel : '.strtolower($this->cidade->portal).'/imovel/0/'.$i->id.'/1'.PHP_EOL;
                    $inc['mensagem'] .= '<br>'.PHP_EOL;
                    
                    $contador++;
                }
                $inc['mensagem'] .= '<br>'.PHP_EOL;
                $inc['mensagem'] .= '<br>'.PHP_EOL;
                $inc['mensagem'] .= '<br>Contato feito por:'.PHP_EOL;
                $inc['mensagem'] .= '<br>Nome: '.$dados['remetente']['nome'].PHP_EOL;
                $inc['mensagem'] .= '<br>E-mail: '.$dados['remetente']['email'].PHP_EOL;
                $inc['mensagem'] .= '<br>Data: '.date('d/m/Y H:i').PHP_EOL;	
                $inc['mensagem'] .= '<br>Mensagem:'.PHP_EOL;
                $inc['mensagem'] .= '<br>'.$dados['mensagem'].PHP_EOL;
                $inc['mensagem'] .= '<br>'.PHP_EOL;
                $inc['mensagem'] .= '<br>Esse email foi enviado a partir do Portal '.substr($this->cidade->portal, 11).PHP_EOL;
                $inc['mensagem'] .= '<br>Em caso de dúvidas entre em contato com o POW Internet através'.PHP_EOL;
                $inc['mensagem'] .= '<br>do fone (41) 3382-1581 ou pelo email comercial@pow.com.br'.PHP_EOL;
                $inc['reply'] = array($dados['remetente']['email'],$dados['remetente']['nome']);
                //$inc['nome'] = $dados['remetente']['nome'];
                $inc['portal'] = substr($this->cidade->portal, 11);
                $this->load->model('indica_model');
                foreach( $emails as $email )
                {
                    foreach( $imovel as $i_ )
                    {
                    $array_indica = array(
                                            'anomes' => date('Ym'),
                                            'id_imovel' => $i_->id,
                                            'id_cidade' => 0,
                                            'emailenvia' => $dados['remetente']['email'],
                                            'emailrecebe' => $email,
                                            'nomeenvia' => $dados['remetente']['nome'],
                                            'nomerecebe' => '',
                                            'portal' => substr($this->cidade->portal,11),
                                            );
                    $this->indica_model->adicionar($array_indica);
                        
                    }
    
                    $inc['to'] = trim($email);
                    $this->envio($inc);
                }
                if ( isset($dados['redirect']) )
                {
                    redirect( base_url().'imovel/0/'.$dados['imoveis'].'?ok=1' );
                }
                else
                {
                    redirect( base_url().'favoritos/?ok=1' );
                }
            }
            else
            {
                if ( isset($dados['redirect']) )
                {
                    redirect( base_url().'imovel/'.$dados['imoveis'].'?ok=0' );
                }
                else
                {
                    redirect( base_url().'favoritos/?ok=0' );
                }
            }
        }
        else
        {
            if ( isset($dados['redirect']) )
                {
                    redirect( base_url().'imovel/'.$dados['imoveis'].'?ok=0' );
                }
                else
                {
                    redirect( base_url().'favoritos/?ok=0' );
                }
        }
        
    }
    
    private $valida_contato_lista = array();
    
    public function email_contato()
    {
        //vem imovel, empresa, redirect
        $dados = $this->input->post(NULL, TRUE);
        if ( ( isset($dados['remetente']['nome']) && ! empty($dados['remetente']['nome']) ) && ( isset($dados['remetente']['email']) && ! empty($dados['remetente']['email']) ) )
        {
            $dados['imovel'] = isset($dados['imovel']) ? $dados['imovel'] : $dados['id_imovel'];
            $dados['empresa'] = isset($dados['empresa']) ? $dados['empresa'] : $dados['id_empresa'];
            $local = isset($dados['local']) ? $dados['local'] : 'in';
            $assunto = 'Vitrine imóvel pelo site '.$dados['imovel'].(isset($dados['referencia']) ? ' - ref: '.$dados['referencia'] : '' );
            $this->set_log($dados['empresa'], 25);
            $this->email_padrao($dados, $assunto, $local);
        }
        else
        {
            $redirect = ( strstr( $dados['redirect'], '?') ?  $dados['redirect'] : $dados['redirect'].'?' ) . '&erro=2';
            redirect($redirect,'refresh');
        }
    }
    
    
    public function envio_formulario_imovel()
    {
        $dados = $this->_post_email_ficha();
        $this->_inicia_geral();
        $this->set_log_mongo($dados['imovel'], $this->set_local($dados['log']), 'email', $dados['remetente']);
//        var_dump($this->cidade);
        $keys = json_decode($this->cidade->google_keys);
        $post = array('secret' => $keys->secret, 'response' => $dados['token'] );
        $endereco = 'https://www.google.com/recaptcha/api/siteverify';
        $j_curl = $this->curl_executavel($endereco, $post);
        $curl = json_decode($j_curl);
        if ( $curl->success )
        {
            $retorno = $this->email($dados);
        }
        else
        {
            $retorno = array('status' => FALSE, 'mensagem' => 'Me parece um robo... tente de novo...');
        }
        echo json_encode($retorno);
    }
    
    private function _post_email_ficha()
    {
        $dados = $this->input->post(NULL,TRUE);
        $dados['remetente']['email'] = $dados['email'];
        $dados['remetente']['fone'] = $dados['telefone'];
        $dados['remetente']['nome'] = $dados['nome'];
        $dados['retorno'] = 'status';
        $dados['imovel'] = $dados['id_imovel'];
        $dados['log'] = isset($dados['log']) ? $dados['log'] : FALSE;
        return $dados;
    }
    
    
    public function email( $dados = FALSE )
    {
        $dados = ( ( $dados ) ? $dados : $this->input->post(NULL, TRUE) ); 
        $dados['imovel'] = isset($dados['imovel']) ? $dados['imovel'] : $dados['id_imovel'];
        $dados['empresa'] = isset($dados['empresa']) ? $dados['empresa'] : $dados['id_empresa'];
        $local = isset($dados['local']) ? $dados['local'] : 'in';
        $assunto = $dados['assunto'];
        $email = $this->email_padrao($dados, $assunto, $local );
        $this->set_log($dados['empresa'], 25);
        return $email;
    }
    
    public function email_empresa()
    {
        $dados = $this->input->post(NULL, TRUE);
        //var_dump($dados);
        $dados['imovel'] = isset($dados['imovel']) ? $dados['imovel'] : $dados['id_imovel'];
        $dados['empresa'] = isset($dados['empresa']) ? $dados['empresa'] : $dados['id_empresa'];
        $local = isset($dados['local']) ? $dados['local'] : '1n';
        $assunto = isset($dados['assunto']) ? $dados['assunto'] : '';
        $email = $this->email_padrao($dados, $assunto, $local);
        $this->set_log($dados['empresa'], 25, 'clicks');
        redirect($dados['redirect'].$interrogacao.'ok=0', 'refresh');
    }
    
    /**
     * função para disparo de email de interresse no imovel
     * @access public
     * @version 1.0
     */
    public function email_solicita_ligacao()
    {
        $this->_inicia_geral();
        $conteudo = $this->input->post(NULL,TRUE);
        if ( count($conteudo) > 2 && ! empty($conteudo['remetente']['fone']) && ! empty($conteudo['remetente']['nome']) )
        {
            //6n
            $this->set_log($conteudo['empresa'], 97, 'clicks'); 
            $inc['assunto'] = 'Solicitação de retorno de ligação através do Portal '.substr($this->cidade->portal, 11);
            $imovel = $this->imoveis_model->get_item_por_id( $conteudo['imovel'] );
            $inc['mensagem'] = '<img src="'.str_replace('/m','',base_url()).'imagens/'.$this->logo_principal.'"> <img src="http://www.portaisimobiliarios.com.br/imagens/logo.png">'.PHP_EOL;
            $inc['mensagem'] .= '<hr>Contato de solicitação de ligação, enviado pelo portal: '.substr($this->cidade->portal, 11).'  '.PHP_EOL;
            $inc['mensagem'] .= '<br><br>';
            $inc['mensagem'] .= 'Anunciante:<br>'.PHP_EOL;
            $inc['mensagem'] .= $imovel->imobiliaria_nome.'<br>'.PHP_EOL;
            $inc['mensagem'] .= ( ( isset($imovel->nome_corretor) && ! empty($imovel->nome_corretor) ) ? $imovel->nome_corretor : '').'<br>'.PHP_EOL;
            $inc['mensagem'] .= 'Assunto:'.$inc['assunto'].'<br>'.PHP_EOL;
            $inc['mensagem'] .= 'Código do Imóvel : '.$conteudo['imovel'].PHP_EOL;
            $inc['mensagem'] .= '<br>Referência do Imóvel : '.$imovel->referencia.PHP_EOL;
            $inc['mensagem'] .= '<br>Endereço da ficha do Imóvel : '.strtolower($this->cidade->portal).'/imovel/0/'.$conteudo['imovel'].PHP_EOL;
            $inc['mensagem'] .= '<br>'.PHP_EOL;
            $inc['mensagem'] .= '<br>'.PHP_EOL;
            $inc['mensagem'] .= '<br>Contato feito por:'.PHP_EOL;
            $inc['mensagem'] .= '<br>Nome: '.$conteudo['remetente']['nome'].PHP_EOL;
            $inc['mensagem'] .= '<br>E-mail: '.$conteudo['remetente']['email'].PHP_EOL;
            $inc['mensagem'] .= '<br>Telefone: '.isset($conteudo['remetente']['fone']) ? $conteudo['remetente']['fone'] : ''.PHP_EOL;
            if ( isset($conteudo['remetente']['cidade']) )
            {
                $inc['mensagem'] .= '<br>Cidade: '.$conteudo['remetente']['cidade'].'/'.$conteudo['remetente']['uf'].PHP_EOL;
            }
            $inc['mensagem'] .= '<br>Data: '.date('d/m/Y H:i').PHP_EOL;	
            $inc['mensagem'] .= '<br>Mensagem:'.PHP_EOL;
            $inc['mensagem'] .= '<br>'.$conteudo['mensagem'].PHP_EOL;
            $inc['mensagem'] .= '<br>'.PHP_EOL;
            $inc['mensagem'] .= '<br>Esse email foi enviado a partir do portal '.substr($this->cidade->portal, 11).' em função de seu cadastro em portal integrante da rede PortaisImobiliarios.com.br'.PHP_EOL;
            $inc['mensagem'] .= '<br>Em caso de dúvidas entre em contato com o POW Internet através'.PHP_EOL;
            $inc['mensagem'] .= '<br>do fone (41) 3382-1581 ou pelo email comercial@pow.com.br'.PHP_EOL;
            $inc['reply'] = array($conteudo['remetente']['email'],$conteudo['remetente']['nome']);
            $inc['nome'] = $conteudo['remetente']['nome'];
            
            if ( ! empty($imovel->empresa_email) )
            {
                $inc['to'][] = $imovel->empresa_email;
            }
            if ( ! empty($imovel->locacao_email) && $imovel->imovel_para == 'locação') 
            {
                $inc['to'][] = $imovel->locacao_email;
            }
            if ( ! empty($imovel->email_corretor)  ) 
            {
                $inc['to'][] = $imovel->email_corretor;
            }
            
            //$inc['to'][] = 'programacao@pow.com.br';
            $inc['portal'] = substr($this->cidade->portal, 11);
            $this->envio($inc);
            if ( isset($imovel) && $imovel->sms_limite > 0 )
            {
                $sms_parametro['destinatario'][] = array(
                                                'telefone' => $imovel->empresa_telefone_sms,
                                                );
                if ( $imovel->sms_quem == 2 && $imovel->sms_corretor )
                {
                    $sms_parametro['destinatario'][] = array(
                                                    'telefone' => $imovel->celular_corretor,
                                                    );
                }
                $sms_parametro['empresa'] = array('id' => $dados['empresa'], 'limite' => $imovel->sms_limite );
                $sms_parametro['mensagem'] = array('tipo' => $imovel->tipo, 'id_imovel' => $dados['imovel'], 'referencia' => $imovel->referencia, 'nome' => $dados['remetente']['nome'], 'fone' => $dados['remetente']['fone'], 'portal' => substr($this->cidade->portal,11));
                $this->load->library('sms');
                $sms = $this->sms->dispara_sms($sms_parametro);
                
            }
            if ( isset($imovel) )
            {
                if ( $imovel->locacao == 1 )
                {
                    $tipo_negocio_item = 'locacao';
                }
                if ( $imovel->locacao_dia == 1 )
                {
                    $tipo_negocio_item = 'locacao_dia';
                }
                if ( $imovel->venda == 1 )
                {
                    $tipo_negocio_item = 'venda';
                }
            }
            else
            {
                $tipo_negocio_item = 0;
            }
            $array_contato = array(
                                    'data' => time() , 
                                    'id_empresa' => $conteudo['empresa'] , 
                                    'nome' => $conteudo['remetente']['nome'] , 
                                    'email' => $conteudo['remetente']['email'] , 
                                    'assunto' => $inc['assunto'] , 
                                    'mensagem' => $conteudo['mensagem'] , 
                                    'fone' => $conteudo['remetente']['fone'] , 
                                    'origem' => '6n',
                                    'id_item' => $conteudo['imovel'],
                                    'id_cidade' => $imovel->id_cidade, 
                                    'sms_enviado' => 0, 
                                    'portal'        => substr($this->cidade->portal, 11),
                                    'id_tipo_item'  => isset($imovel->id_tipo) ? $imovel->id_tipo : 0,
                                    'tipo_negocio_item' => $tipo_negocio_item
                                    );
            $this->load->model('cadastro_model');
            $this->cadastro_model->adicionar_contato($array_contato);
            $cad_email = $this->cadastro_model->get_item_email($conteudo['remetente']['email']);
            //var_dump( $cad_email );
            if ( $cad_email == 0 )
            {
                //$f_cidade = '( estados.uf LIKE "'.$conteudo['remetente']['uf'].'" OR estados.nome LIKE "'.$conteudo['uf'].'") AND cidades.nome LIKE "'.$conteudo['cidade'].'"';
                //$city = $this->cidades_model->get_cidade_estado($f_cidade);
                $array_cad = array(
                                        'data'      => time() , 
                                        'id_canal'  => '29' , 
                                        'nome'      => $conteudo['remetente']['nome'] , 
                                        'email'     => $conteudo['remetente']['email'],  
                                        'fone'      => $conteudo['remetente']['fone'],  
                                        //'cidade'    => $conteudo['cidade'], 
                                        'senha'     => '17het3',  
                                        'news'      => $conteudo['aceito'], 
                                        'status'    => '0', 
                                        'id_origem' => '2', 
                                        'datatu'    => date('Y-m-d H:i')
                    );
                if ( isset($city) )
                {
                    $array_cad['id_pais']   = $city->pais;
                    $array_cad['id_cidade'] = $city->id;
                    $array_cad['estado']    = $city->uf;
                }
                $this->cadastro_model->adicionar($array_cad);
            }
            else
            {
                $d = array( 'datatu' => date('Y-m-d H:i') );
                $f = array( 'email' => $conteudo['remetente']['email'] );
                $this->cadastro_model->editar( $d, $f );
            }
            //origem = m4;
            echo '<script>alert("Obrigado, Seu Contato foi enviado com sucesso");</script>';
            $this->imovel($conteudo['imovel'], 0, 'formulario');
        }
        else
        {
            redirect( base_url() );
        }
        
    }
    
    /**
     * função para disparo de email de interresse no imovel
     * @access public
     * @version 1.0
     */
    public function email_relatar_erro()
    {
        $conteudo = $this->input->post(NULL,TRUE);
        $this->_inicia_geral();
        if ( count($conteudo) > 2 && empty($conteudo['validador']) )
        {
            $inc['assunto'] = 'Relato de erro, portal de imóveis';
            //$inc['mensagem'] = '<img src="'.str_replace('/m','',base_url()).'imagens/'.$this->logo_principal.'"> <img src="http://www.portaisimobiliarios.com.br/imagens/logo.png">'.PHP_EOL;
            $inc['mensagem'] = '<hr>Contato enviado pelo portal: '.substr($this->cidade->portal, 11).' - Relatar Erro no site '.PHP_EOL;
            $inc['mensagem'] .= '<br><br>';
            $inc['mensagem'] .= 'Código do Imóvel : '.$conteudo['imovel'].PHP_EOL;
            $inc['mensagem'] .= '<br>Endereço da ficha do Imóvel : <a href="'.strtolower($this->cidade_set->portal).'/imovel/0/'.$conteudo['imovel'].'">clique aqui</a>'.PHP_EOL;
            $inc['mensagem'] .= '<br>'.PHP_EOL;
            $inc['mensagem'] .= '<br>'.PHP_EOL;
            $inc['mensagem'] .= '<br>Contato feito por:'.PHP_EOL;
            $inc['mensagem'] .= '<br>Nome: '.$conteudo['remetente']['nome'].PHP_EOL;
            $inc['mensagem'] .= '<br>E-mail: '.$conteudo['remetente']['email'].PHP_EOL;
            $inc['mensagem'] .= '<br>Data: '.date('d/m/Y H:i').PHP_EOL;	
            $inc['mensagem'] .= '<br>Mensagem:'.PHP_EOL;
            $inc['mensagem'] .= '<br>'.$conteudo['mensagem'].PHP_EOL;
            $inc['mensagem'] .= '<br>'.PHP_EOL;
            $inc['reply'] = array($conteudo['email'],$conteudo['nome']);
            $inc['nome'] = $conteudo['nome'];
            
            $inc['to'][] = 'programacao@pow.com.br';
            $inc['to'][] = 'll@pow.com.br';
            $inc['portal'] = substr($this->cidade->portal, 11);
            $this->envio($inc);
            
            //origem = m4;
            echo '<script>alert("Obrigado, Seu Contato foi enviado com sucesso");</script>';
            $this->imovel($conteudo['imovel'], 0, 'formulario');
        }
        else
        {
            redirect( base_url() );
        }
        
    }
    
    public function set_estatistica()
    {
        $post = $this->input->post(NULL, TRUE);
        if ( isset($post['imovel']) )
        {
            switch ( $post['origem'] )
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
                    break; 
            }
            $this->set_log($post['imovel'],$log,$post['tipo']);
            $this->set_log_mongo($post['imovel'],$log,$post['tipo']);
        }
    }
    
    /**
     * função para uso de logs do sistema, pode ter origem Javascript ou interna serve para imovel ou imobiliaria
     * @param int $id_tabela id do imoveil ou imobiliaria
     * @param int $id_local pré definido no sistema o tipo de log
     * @param type $tipo
     * @access public
     * @version 1.0
     */
    public function set_log ( $id_tabela, $id_local = 22, $tipo = 'views' )
    {
        $this->requisita_bd_sessao();
        $this->load->model(array('imoveis_model','imoveis_mongo_model','locais_model'));
        $retorno_b = $this->imoveis_model->get_log( $id_tabela, $id_local, $tipo );
        
        
        //echo $retorno_b;
    }
    
    public function log_pesquisa()
    {
        $this->requisita_bd_sessao();
        $this->load->model(array('log_pesquisa_mongo_model'));
        $get = $this->_get();
        $valores = $get['valores'];
        $log_pesquisa = $valores;
        $log_pesquisa['date'] = $this->log_pesquisa_mongo_model->set_date();
        $log_pesquisa['ip'] = $_SERVER['REMOTE_ADDR'];
        $this->log_pesquisa_mongo_model->adicionar($log_pesquisa);
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
                    break; 
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
    
    public function set_log_mongo($id_tabela, $id_local = 22, $tipo = 'views', $complemento = array())
    {
        $this->requisita_bd_sessao();
        
        $this->load->model(array('imoveis_mongo_model','locais_model'));
        $item = $this->imoveis_mongo_model->get_item($id_tabela);
        $local = $this->locais_model->get_item($id_local);
        
        
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
        $data['local_nome'] = $local->nome;
        $data['local_resumo'] = $local->resumo;
        $data['ip'] = $_SERVER['REMOTE_ADDR'];
        $data['host'] = $_SERVER['HTTP_HOST'];
        $data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        $data['imovel_para'] = $item->imovel_para;
        $data['complemento'] = $complemento;
//        $this->set_log_mongo($data);
        
        $this->load->model(array('log_portal_mongo_model'));
        if ( ! strstr($_SERVER['HTTP_USER_AGENT'],'Googlebot') )
        {
//            echo 'não google';
            $this->log_portal_mongo_model->adicionar($data);
        }
        
    }
    
    /**
     * 'id_empresa',
        id_imovel
     *  'local'
        'validador'
        'redirect'
     * 'assunto
     * 'email
     * 'nome'
     * 'fone'
     *   
     */
    public function redisparo_emails ( $sequencia = 623 )
    {
        die('temporariamente indisponivel');
        echo $sequencia.'<br>';
        $this->load->model('cadastro_model');
        $filtro[] = 'contatos_site.data > '.mktime (0,0,0,4,20,2015);
        $filtro[] = 'contatos_site.data < '.mktime (11,0,0,4,28,2015);
        $filtro[] = '( contatos_site.origem like "i%" OR contatos_site.origem like "m%" OR  contatos_site.origem like "4n")';
        $filtro[] = 'contatos_site.tipo_negocio_item IS NOT NULL';
        $itens = $this->cadastro_model->get_itens_contatos($filtro, $sequencia);
        foreach ( $itens['itens'] as $chave => $item ) 
        {
            $dados['imovel'] = $item->id_imovel;
            $dados['validador'] = '';
            $dados['mensagem'] = $item->mensagem;
            $dados['data'] = date('d/m/Y H:i',$item->data);
            $dados['empresa'] = $item->id_empresa;
            $dados['remetente']['email'] = $item->email;
            $dados['remetente']['fone'] = $item->fone;
            $dados['remetente']['nome'] = $item->nome;
            $local = 're';
            $assunto = $item->assunto;
            //var_dump($dados);die();
            $email = $this->email_padrao($dados, $assunto, $local, FALSE, FALSE );
            var_dump($chave, $item->id, $item->data, $email);//die();
        }
    }
    
    public function mapa($id_imovel){
//        var_dump($id_imovel);die();
        $this->load->model(['imoveis_mongo_model']);
         $imovel = $this->imoveis_mongo_model->get_item(strval($id_imovel));
//         var_dump($imovel);
        if ( isset($imovel->location[0]) && ! empty($imovel->location[0]) )
        {
            $this->layout->set_function('mapa')->set_mapa($imovel->location)->view('mapa', [], 'layout/branco');

        }else{
            echo 'este imóvel não tem os dados necessários para exibir o mapa';
        }
    }
    
    public function mapa_empresa($id_empresa = NULL)
    {
        $this->load->model('empresas_model');
        if ( isset($id_empresa) )
        {
//            $this->set_log($id_empresa);
            $data['item'] = $this->empresas_model->get_item_por_id( $id_empresa );
            $mapa = array_reverse(explode(',',$data['item']->mapa));
            
            $this->titulo .= $data['item']->nome_fantasia;
            $this->description .= $data['item']->nome_fantasia;
            $this->layout
                    ->set_include('js/imovel.js', TRUE)
                    ->set_function('mapa')
                    ->set_mapa($mapa)
                    ->view('mapa', [], 'layout/branco');
        }
        else
        {
            redirect( base_url() );
        }
    }
}

