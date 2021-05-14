<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 
 * @access public
 * @version 3.0
 * @author Carlos Claro
 * @package Imovel
 * @since 14/08/2018
 * 
 * @copyright (c) 2018 POW Internet, Carlos Claro
 */
class Imovel extends MY_Controller {
    
    
    
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
        $this->load->library(['Mongo_db','my_mongo']);
        $this->load->model(array('imoveis_mongo_model'));
        $this->load->library(['lista_normal','encryption']);
        $this->load->helper('cookie');
        $this->benchmark->mark('setCidade_start');
        $this->set_cidade();
        $this->benchmark->mark('setCidade_end');
        $this->print_time('setCidade');
        $this->benchmark->mark('loadInicialFavorito_end');
        $this->print_time('loadInicialFavorito');
    }

    public function set_404($id_imovel)
    {
        $robot = FALSE;
        $layout = 'erro';
        $this->description = 'Desculpe, mas este imóvel não se encontra mais em nossa base.'.$id_imovel;
        $this->titulo = 'Desculpe, mas este imóvel não se encontra mais em nossa base.'.$id_imovel.$this->titulo_padrao;
        $this->load->model('imoveis_historico_model');
        $data['item'] = $this->imoveis_historico_model->get_item_por_id($id_imovel);
        if ( isset($data['item']->id) ){
            $data['item']->bairro = $data['item']->bairros_link;
            $redirect = $this->set_url($data['item'],TRUE);
            redirect( base_url().$redirect , 'location', 301);
            exit();
        }else{
            redirect( base_url() , 'location', 301);
        }
    }
    
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
    
    /**
     * 
        $this->load->library('lista_normal');
     */
    public function index ( $complemento = NULL, $id_imovel = 0, $local = 0, $layout = 'novo_3', $ajax = FALSE )
    {
        $this->benchmark->mark('Imovel_start');
        
            $this->benchmark->mark('SetLocal_start');
        $log = $this->set_local($local);
            $this->benchmark->mark('SetLocal_end');
            $this->print_time('SetLocal');
            $this->benchmark->mark('SetImovel_start');
        $this->set_imovel($id_imovel);
            $this->benchmark->mark('SetImovel_end');
            $this->print_time('SetImovel');
        $data['item'] = $this->imovel;
        $data['log'] = $local;
        if ( ! $this->imovel )
        {
            $this->set_404($id_imovel);
        }
        else
        {
            $this->benchmark->mark('Valores_start');
            $this->set_valores_imovel();
            $this->set_titulo_imovel();
            $this->benchmark->mark('Valores_end');
            $this->print_time('Valores');
            if ( isset($this->imovel->location[0]) && ! empty($this->imovel->location[0]) )
            {
            $this->benchmark->mark('Mapa_start');
                $data['mapa'] = $this->layout->set_function('mapa')->set_mapa($this->imovel->location)->view('mapa', [], 'layout/branco', TRUE);
            $this->benchmark->mark('Mapa_end');
            $this->print_time('Valores');
            }
            $data['local'] = $local;
            $this->benchmark->mark('Images_start');
            $data['images'] = $this->lista_normal->get_images($this->imovel,FALSE,TRUE);
            $this->benchmark->mark('Images_end');
            $this->print_time('Images');
            $data['image_destaque'] = $data['images']['lista']['principal']->original;
            $this->benchmark->mark('Link_start');
            $data['url'] = $this->lista_normal->_set_link($this->imovel);
            $this->benchmark->mark('Link_end');
            $this->print_time('Link');
//            $data['breadscrumb'] = $this->set_url($data['item'], 'imovel');
            $l = $this->layout;
            $l->set_tag_adwords($this->cidade->tag_adwords);
            $data['link_print'] = $data['url'].'/'.$local.'/print';
            $keys = json_decode($this->cidade->google_keys);
            $data['sitekey'] = $keys->site;
            $data['tag_adwords'] = $this->cidade->tag_adwords;
            $data['h1'] = $this->get_titulo_imovel('h1');
            $this->benchmark->mark('Lista_start');
            $data['lista'] = $this->get_lista();
            $this->benchmark->mark('Lista_end');
            $this->print_time('Lista');
            $this->benchmark->mark('Valores_start');
            $data['valores'] = $this->get_valores_imovel();
            $this->benchmark->mark('Valores_end');
            $this->print_time('Valores');
            $this->benchmark->mark('relacionados_start');
            $data['relacionados'] = $this->get_relacionados();
            $data['relacionados_empresa'] = $this->get_relacionados(TRUE);
            $this->benchmark->mark('relacionados_end');
            $this->print_time('relacionados');
            
        $this->benchmark->mark('Imovel_end');
        $this->print_time('Imovel');
        $this->benchmark->mark('View_start');
        $l
            ->set_image_destaque(isset($data['image_destaque']) ? $data['image_destaque'] : NULL)
            ->set_includes_defaults()
            ->set_include('css/imovel.css', TRUE)
            ->set_include('css/style.css', TRUE)
            ->set_include('js/imovel.js', TRUE)
            ->set_include('https://www.google.com/recaptcha/api.js', FALSE)
//                ->set_time($this->soma_time())
//                ->set_menu($this->menu)
                ->set_logo( $this->logo_principal )
                ->set_mobile($this->is_mobile)
                ->set_titulo($this->get_titulo_imovel('titulo'))
                ->set_description($this->get_titulo_imovel('descricao'))
                ->set_header_extra($this->cidade->header_tag)
                ->set_sobre($this->cidade->sobre, $this->cidade->nome, $this->cidade->portal, $this->cidade->gentilico, $this->cidade->link, $this->cidade->uf, $this->cidade->link_prefeitura,1 )
                ->set_analytics($this->cidade->instrucoes_head)
                ->set_google_tag($this->cidade->google_tag);
        $this->benchmark->mark('View_end');
        $this->print_time('View');
            $layout_principal = 'layout_3';
            if ( $layout == 'print' )
            {
                $l->set_include('js/print.js',TRUE);
                $data['logo'] = $this->logo_principal;
                $layout_principal = 'limpo';
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
                $l->view('imovel_'.$layout, $data, 'layout/'.$layout_principal);
            }
        }
    }
    
    public $negativos = [];
    
    public function set_negativos($id)
    {
        $this->negativos[] = $id;
        return $this;
    }
    
    public function get_negativos()
    {
        return $this->negativos;
    }
    
    public function get_relacionados($empresa = FALSE)
    {
        $valores = [];
        $this->set_negativos($this->imovel->_id);
        if ( ! $empresa )
        {
            $valores['tipo_negocio'] = $this->imovel->tipo;
            $tipo_negocio = 'preco_'.$this->imovel->tipo;
            $valores['valor_min'] = ($this->imovel->{$tipo_negocio} - ( $this->imovel->{$tipo_negocio} / 20 ));
            $valores['valor_max'] = ($this->imovel->{$tipo_negocio} + ( $this->imovel->{$tipo_negocio} / 20 ));
            $valores['tipo'] = [$this->imovel->imoveis_tipos_link];
            if ( $this->imovel->quartos ){
                $valores['quartos'] = [$this->imovel->quartos-1,$this->imovel->quartos,$this->imovel->quartos+1];
            }
            if ($this->imovel->garagens ){
                $valores['vagas'] = [$this->imovel->garagens-1,$this->imovel->garagens,$this->imovel->garagens+1];
            }
            $valores['cidade'] = $this->imovel->cidade_link ;
        }
        else
        {
            $valores['tipo_negocio'] = $this->imovel->tipo;
            $valores['id_empresa'] = [$this->imovel->id_empresa];
        }
        $valores['negativos'] = $this->get_negativos();
        $filtro = $this->get_filtro($valores);
//            var_dump($valores);die();
        $imoveis = $this->imoveis_mongo_model->get_itens($filtro, 'ordem', 'DESC', 0, 3);
//        var_dump($imoveis);die();
//        if ( ! $empresa ){
//            var_dump($imoveis);
//            die();
//        }
        $retorno = '';
        if ( $imoveis['qtde']  ){
                $retorno .= '<ul class="list-unstyled">';
            foreach($imoveis['itens'] as $imovel)
            {
                $retorno .= $this->lista_normal->_set_item_grid($imovel, 'relacionado');
                $this->set_negativos($imovel->_id);
            }
            $retorno .= '</ul>';
        }
        return $retorno;
    }
    
    
    public function get_filtro($request)
    {
        $filtro = array();
        foreach( $request as $c => $v )
        {
            $campo = isset($this->campos[$c]) ? $this->campos[$c] : NULL;
            if ( isset($campo) && ! empty($v) )
            {
                $filtro[$c]['tipo'] = $campo['filtro']['where'];
                $filtro[$c]['campo'] = $campo['filtro']['campo'];
                if ( (is_array($v) && count($v) > 0) ){
                    
                    if ( $campo['filtro']['tipo'] == 'int' )
                    {
                        if ( $c == 'quartos' || $c == 'banheiros' || $c == 'vagas' )
                        {
                            if ( count($v) == 1 )
                            {
                                $filtro[$c]['tipo'] = 'where_gte';
                                $filtro[$c]['valor'] = (int)$v[0];
                            }
                            else
                            {
                                $va = [];
                                foreach( $v as $val )
                                {
                                    $va[] = (int)$val;
                                }
                                $filtro[$c]['valor'] = $va;
                            }
                        }
                        else
                        {
                            $filtro[$c]['valor'] = ( is_array($v) ? $v : $v );
                        }
                    }
                    else
                    {
                        $filtro[$c]['valor'] = $v;
                    }
                }else{
                    $filtro[$c]['valor'] = $v;
                    
                }
            }
        }
        if ( isset($this->negativos) )
        {
            $filtro[$c] = array('tipo' => 'where_not_in','campo' => '_id','valor' => $this->negativos );
        }
        return $filtro;
    }
    
    
    public function set_imovel($id_imovel)
    {
        $this->imovel = $this->imoveis_mongo_model->get_item($id_imovel);
        return $this;
    }
    
    public function get_titulo_imovel($campo = 'h1')
    {
        return $this->{$campo};
    }
    
    public function set_titulo_imovel()
    {
        $titulo = $this->imovel->imoveis_tipos_titulo.'  ';
        $titulo .= ($this->imovel->condominio) ? ' em condomínio ' : '';
        $descricao = 'Encontre o '.$this->imovel->imoveis_tipos_titulo.'  ';
        $c_negocio = 0;
        foreach ( $this->tipo_negocio as $negocio )
        {
            if ( $this->imovel->{$negocio} )
            {
                $tipo_negocio = ( ! $c_negocio ? '' : 'ou ' ).$this->tipo_negocio_data[$negocio].' ';
                $titulo .= $tipo_negocio;
                $descricao .= $tipo_negocio;
                $c_negocio++;
            }
        }
        $titulo .= ( ! empty($this->imovel->bairro)  ) ? ', '.$this->imovel->bairro : '';
        $descricao .= ( ! empty($this->imovel->bairro)  ) ? 'no bairro '.$this->imovel->bairro : '';
        $titulo .= ', '.$this->imovel->cidade.' ';
        $descricao .= ', '.$this->imovel->cidade.' ';
        $descricao .= $this->imovel->quartos > 0 ? $this->imovel->quartos.' quartos, ' : '';
        $descricao .= $this->imovel->banheiros > 0 ? $this->imovel->banheiros.' banheiros, ' : '';
        $descricao .= $this->imovel->garagens > 0 ? $this->imovel->garagens.' vagas, ' : '';
        $area = ( ( $this->imovel->area_util > 0.00 ) ? ', '.$this->imovel->area_util.'m&sup2; área util ' : '');
        $titulo .= $area; 
        $descricao .= $area; 
        $titulo .= $this->get_valores_imovel(); 
        $descricao .= $this->get_valores_imovel(); 
        $titulo .= '| PI: '.$this->imovel->id.'  ';
        $descricao .= '| PI: '.$this->imovel->id.'  ';
        $this->h1 = $titulo;
        $titulo .= 'no '.  substr($this->cidade->portal, 11);
        $descricao .= 'no '.  substr($this->cidade->portal, 11);
        $this->titulo = $titulo;
        $this->descricao = $descricao;
        return $this;
    }
    
    public function get_valores_imovel()
    {
        if ( isset($this->valores) )
        {
            return $this->valores;
        }
        else
        {
            $this->set_valores_imovel();
            return $this->valores;
        }
    }
    
    public function set_valores_imovel()
    {
        $this->valores = '';
        $c = 0;
        foreach ( $this->tipo_negocio as $negocio )
        {
            $variavel = 'preco_'.$negocio;
            if ( $this->imovel->{$variavel} > 0 )
            {
                $this->valores .= (! $c ? ', ' : '' ).'R$ '.number_format($this->imovel->{$variavel}, 0, ',', '.').' ';
                $c++;
            }
        }
        return $this;
    }
    
    public function lista_campos_complemento( $tipo = 'apartamento')
    {
        switch ( $tipo )
        {
            case 'andar':
            case 'area':
            case 'predio':
            case 'barracao_galpao':
            case 'fazenda':
            case 'haras':
            case 'garagem':
            case 'lote_terreno':
            case 'salao':
            case 'sitio_chacara':
                $retorno = [
                    'area_terreno'=> ['titulo' => 'Área do Terreno', 'medida' => 'm'],
                    'area'=> ['titulo' => 'Área Construída', 'medida' => 'm'],
                    'area_util'=> ['titulo' => 'Área Útil', 'medida' => 'm'],
                    'mobiliado'=> ['titulo' => 'Mobiliado', 'medida' => '', 'tratamento' => 'get_x(<#mobiliado#>)'],
                ];
                break;
            case 'hotel':
            case 'apartamento':
            case 'casa':
            case 'flat':
            case 'kitinete':
            case 'loft':
            case 'outro':
            case 'sobrado':
            case 'quarto':
                $retorno = [
                    'quartos'=> ['titulo' => 'Quartos', 'medida' => ''],
                    'garagens'=> ['titulo' => 'Vagas', 'medida' => ''],
                    'banheiros'=> ['titulo' => 'Banheiros', 'medida' => ''],
                    'suites'=> ['titulo' => 'Suites', 'medida' => ''],
                    'area'=> ['titulo' => 'Área Construída', 'medida' => 'm'],
                    'area_terreno'=> ['titulo' => 'Área do Terreno', 'medida' => 'm'],
                    'area_util'=> ['titulo' => 'Área Útil', 'medida' => 'm'],
                    'mobiliado'=> ['titulo' => 'Mobiliado', 'medida' => '', 'tratamento' => 'get_x(<#mobiliado#>)'],
                    'cobertura'=> ['titulo' => 'Cobertura', 'medida' => '', 'tratamento' => 'get_x(<#cobertura#>)'],
                    'condominio'=> ['titulo' => 'Condominio', 'medida' => '', 'tratamento' => 'get_x(<#condominio#>)'],
                ];
                break;
            case 'loja':
            case 'conjunto_comercial':
            case 'negocio_empresa':
            case 'ponto_comercial':
            case 'pousada':
                $retorno = [
                    'garagens'=> ['titulo' => 'Vagas', 'medida' => ''],
                    'banheiros'=> ['titulo' => 'Banheiros', 'medida' => ''],
                    'area'=> ['titulo' => 'Área Construída', 'medida' => 'm'],
                    'area_terreno'=> ['titulo' => 'Área Terreno', 'medida' => 'm'],
                    'area_util'=> ['titulo' => 'Área Útil', 'medida' => 'm'],
                    'mobiliado'=> ['titulo' => 'Mobiliado', 'medida' => '', 'tratamento' => 'get_x(<#mobiliado#>)'],
                    'condominio'=> ['titulo' => 'Condominio', 'medida' => '', 'tratamento' => 'get_x(<#condominio#>)'],
                ];
                break;
        }
        return $retorno;
    }
    
    public $lista_campos_principal = [
        '_id' => ['titulo' => 'Id', 'medida' => ''],
	'tipo' => ['titulo' => 'Imóvel para ', 'medida' => ''],
        'imoveis_tipos_titulo' => ['titulo' => 'Tipo Imóvel', 'medida' => ''],
        'nome'=> ['titulo' => 'Titulo', 'medida' => ''],
        'preco'=> ['titulo' => 'Valor', 'medida' => ''],
        'logradouro'=> ['titulo' => 'Endereço', 'medida' => ''],
        'bairro'=> ['titulo' => 'Bairro', 'medida' => ''],
	'cidade_nome'=> ['titulo' => 'Cidade', 'medida' => ''],
	'uso'=> ['titulo' => 'Uso', 'medida' => ''],
    ];
    

    public function get_lista()
    {
        $retorno = '';
        foreach( $this->lista_campos_principal as $chave => $campo )
        {
            $this->set_linha($chave, $campo);
        }
        $complemento = $this->lista_campos_complemento($this->imovel->imoveis_tipos_link);
        foreach( $complemento as $chave_c => $campo_c )
        {
            $this->set_linha($chave_c, $campo_c);
        }
        $retorno .= '<div class="portlet light bordered">';
        $retorno .= '<div class="caption font-red"><i class="icon-settings font-red"></i><span class="caption-subject bold uppercase"></span></div><div class="tools"> </div>';
        $retorno .= '<div class="portlet-body table-both-scroll"><table class="table table-striped table-bordered table-hover " summary="Sobre o imóvel" id="sample_1">';
        $retorno .= $this->get_linhas();
        $retorno .= '</table></div></div>';
        return $retorno;
    }
    
    public $linha = '';
    
    public function set_linha($campo, $valor)
    {
        $linha = '';
        $linha .= '<tr><td class="c-compare-info">'.$valor['titulo'].'</td>';
        $linha .= '<td class="c-compare-item">'.$this->imovel->{$campo}.'</td>';
        $linha .= '</tr>';
        $this->linha .= $linha;
        return $this;
    }
    
    public function get_linhas()
    {
        return $this->linha;
    }
    
    public function get_fotos($id_imovel = NULL){
        $retorno = [];
        if ( isset($id_imovel)){
            $this->load->library('lista_normal');
            $this->set_imovel($id_imovel);
            $retorno = $this->lista_normal->get_images($this->imovel);
        }
        echo json_encode($retorno['lista']['lista']);
    }
    
    public function get_politica(){
        $retorno['titulo'] = 'Política de privacidade PORTAISIMOBILIARIOS.COM ';
        $retorno['texto'] = '<p>
Esse Portal é integrante da rede PORTAISIMOBILIARIOS.COM.
                </p>
                <p>
O presente instrumento estabelece a política de privacidade e segurança de informações e de direitos e deveres assumidos pelos seus anunciantes, usuários e pelo PORTAISIMOBILIARIOS.COM quanto ao presente Portal de serviços de publicidade.
                </p>
                <p>
Através deste documento, a rede PORTAISIMOBILIARIOS.COM esclarece aos usuários como essas informações são coletadas e a que uso se destina.
O PORTAISIMOBILIARIOS.COM mostra o respeito por sua privacidade nos termos a seguir:
                </p>
                <p>
A rede PORTAISIMOBILIARIOS.COM não fornecerá informações pessoais do usuário sem sua devida autorização, exceto se tais informações forem necessárias para prestar o serviço solicitado pelo próprio usuário, ou, ainda, na hipótese das informações pessoais do usuário ser requeridas por motivos legais. A rede tomará todas as medidas possíveis para manter a confidencialidade e a segurança das informações do usuário, porém não responderá por prejuízo que possa advir da divulgação de tais informações pelo motivo excepcionado acima, ou ainda por violação ou quebra das barreiras de segurança de internet por terceiros como “hackers” ou “crackers”.
                </p>
                <p>
A rede PORTAISIMOBILIARIOS.COM utiliza ainda a tecnologia “Cookies HTTP”, que são mecanismos reconhecidamente confiáveis que analisam as preferências de navegação do usuário e o direcionam com precisão para aqueles assuntos de seu maior interesse e a parceiros comerciais. Em geral, é possível desativar estes “Cookies HTTP”, para tanto basta desabilitá-los através da caixa de ferramentas do seu navegador. É importante dizer que os “cookies” não executam programas nem infectam computadores com vírus, e só podem ser lidos por nós.
                </p>
                <p>
Ou seja, o objetivo da instalação dos “cookies” é sempre beneficiar o USUÁRIO que os recebe.
                </p>
                <p>
Para ter acesso às algumas seções dos portais da rede, o usuário deve preencher um formulário, no qual ele deverá fornecer algumas informações, como por exemplo, forma de contato, entre outras.
                </p>
                <p>
Este registro é armazenado em um banco de dados protegido e sigiloso.
                </p>
                <p>
Quanto mais informações você fornecer, melhor será a personalização da sua experiência.
                </p>
                <p>
Os newsletters e mensagens publicitárias enviadas por e-mail sempre trarão opção de cancelamento do envio daquele tipo de mensagem por parte da rede PORTAISIMOBILIARIOS.COM
                </p>
                <p>
Não nos responsabilizamos por eventuais prejuízos advindos de eventual divulgação de dados, seja por erro no preenchimento ou mau uso do usuário, seja de ordem legal, judicial, ou quebra de sistema por terceiros.
                </p>
                <p>
Ciente destas condições torna-se inegavelmente uma decisão singular do usuário a utilização deste Portal.
                </p>';
        echo json_encode($retorno);
    }
    
    public function get_termos(){
        $retorno['titulo'] = 'Termos de uso PORTAIS IMOBILIÁRIOS ';
        $retorno['texto'] = '<p>

Portais Imobiliários:
                </p>
                <p>
Com o objetivo de integrar várias cidades do Brasil o PORTAISIMOBILIARIOS.COM atualmente conta com mais de 270 cidades integrantes da rede. Cada cidade conta com seu próprio site, estes são de nomes intuitivos, com fácil memorização e busca.
Esse portal é integrante da rede PORTAISIMOBILIARIOS.COM.
                </p>
                <p>


É imprescindível que seus anunciantes e usuários leiam com atenção o presente TERMO DE USO antes de acessar e utilizar o Portal PORTAISIMOBILIARIOS.COM eis que seu acesso repercute na concordância de todos os seus estritos termos de uso.
                </p>
                <p>

O PORTAISIMOBILIARIOS.COM atua como mero veiculador de anúncios, serviços e informações correlatas e específicas de seus anunciantes relativas a imóveis. Portanto, não presta serviços de consultoria ou intermediações de negócios entre seus anunciantes e usuários.
                </p>
                <p>

Dessa forma, o SITE não assume responsabilidade por nenhuma consequência que possa advir de qualquer relação entre o USUÁRIO e o(s) ANUNCIANTE(S), seja ela direta ou indireta.
                </p>
                <p>

Os anúncios, produtos e informações veiculadas em qualquer um dos sites que fazem parte da rede PORTAISIMOBILIARIOS.COM são de inteira, total e exclusiva responsabilidade dos seus anunciantes e usuários, de forma que qualquer ação comissiva ou omissiva dos seus anunciantes e usuários tais como, mas não se limitando, a erros, fraudes, inexatidão ou divergência de dados, fotos, vídeos ou outros materiais relacionados a anúncios ou oferecidas pelos usuários, são de única e exclusiva responsabilidade de seus anunciantes e usuários.
                </p>
                <p>

A rede PORTAISIMOBILIARIOS.COM também não se responsabiliza pelo cumprimento devido por Você, por anunciantes ou quaisquer terceiros, das respectivas obrigações tributárias que venham a incidir, nos termos da lei vigente, sobre quaisquer atividades, operações ou negócios que tenham sua origem nos anúncios veiculados em um dos portais da rede. Portanto, a rede PORTAISIMOBILIARIOS.COM recomenda expressamente que Você fique atento a estes pontos e, ao adquirir algum bem, exija todos os documentos fiscais e legais pertinentes.
                </p>
                <p>

As marcas, logotipos layouts, nomeações de serviços e todo material deste portal são de propriedade da rede PORTAISIMOBILIARIOS.COM e seus associados, ficando estritamente proibido o seu uso por terceiros sem prévia autorização da direção da rede PORTAISIMOBILIARIOS.COM.
                </p>
                <p>

A gestão dos anúncios e informações (vídeos, fotos e características dos bens oferecidos) será realizada exclusivamente pelos anunciantes, mediante cadastro prévio, com inserção e exclusão de informações e dados através de login e senha de caráter confidencial e pessoal dos anunciantes e sob a responsabilidade destes.
                </p>
                <p>


Ao Transmitir Conteúdo à rede PORTAISIMOBILIARIOS.COM, Você afirma e garante que tem o direito de ceder e transferir a rede PORTAISIMOBILIARIOS.COM, como de fato cede e transfere uma licença irrevogável, perpétua, não exclusiva, gratuita e mundial para que a rede PORTAISIMOBILIARIOS.COM possa usar, copiar, exibir e distribuir o Conteúdo, bem como preparar trabalhos derivados dele, ou incorporar em outros trabalhos ao Conteúdo, bem como deles livremente dispor.
                </p>
                <p>


Para fins de obtenção de informações sobre um anúncio, o usuário da rede PORTAISIMOBILIARIOS.COM não necessitará desembolsar qualquer valor em favor do anunciante. Qualquer infração nesse sentido por parte do anunciante deve ser notificada imediatamente a rede PORTAISIMOBILIARIOS.COM.
                </p>
                <p>

Ao adquirir qualquer bem, o usuário deverá exigir sempre nota fiscal do anunciante, a menos que este esteja dispensado legalmente de fornecê-la, uma vez que o Portal também não se responsabiliza pelas exações tributárias que recaiam sobre a relação comercial estabelecida entre anunciante e usuário.
                </p>';
        echo json_encode($retorno);
    }
}