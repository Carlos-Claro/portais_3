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
class Contato extends MY_Controller {
    
    
    
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
        $this->requisita_bd_sessao();
        $this->load->library(['Mongo_db','my_mongo']);
        $this->load->library(['lista_normal','encryption']);
        $this->load->model(['spam_model', 'imoveis_model','imoveis_mongo_model', 'empresas_model', 'cadastro_model', 'cidades_model','erros_mongo_model']);
        $this->load->helper('cookie');
        $this->benchmark->mark('setCidade_start');
        $this->set_cidade();
        $this->benchmark->mark('setCidade_end');
        $this->print_time('setCidade');
        $this->benchmark->mark('loadInicialFavorito_end');
        $this->print_time('loadInicialFavorito');
        $this->benchmark->mark('requisitaDb_start');
        $this->requisita_bd_sessao();
        $this->benchmark->mark('requisitaDb_end');
        $this->print_time('requisitaDb');
    }
    
    public function consulta_cadastro()
    {
        $email = $this->input->post('email',TRUE);
        $email_ret = $this->cadastro_model->get_item_por_email($email);
        if ( isset($email_ret) )
        {
            echo json_encode($email_ret);
        }
        else
        {
            echo FALSE;
        }
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
    
    public $dados = [];
    
    public function teste_mensagem()
    {
        $dados = [];
        $dados['assunto'] = "imóvel referência: AP0686 / 1744734 ";
        $dados['check_email'] = "1";
        $dados['check_telefone'] = "1";
        $dados['check_whatsapp'] = "1";
        $dados['email'] = "testes@pow.com.br";
        $dados['fone'] = "4196311662";
        $dados['id_empresa'] = "83036";
        $dados['id_imovel'] = "1374352";
        $dados['local'] = "i4";
        $dados['log'] = "4";
        $dados['mensagem'] = "Gostaria de receber mais informações sobre o imóvel referência: AP0686, PI: 1744734 ";
        $dados['nome'] = "Carlos Claro";
        $dados['tipo'] = "tab-pergunte";
        $dados['token'] = "03AL4dnxplfawp4TA0unofqTNCksE4yzCviNANAV9JNkY9Iv1Mqepo1P-56nPZDO7Y9HcwwIeZTQmwpHCh8XvVcyUmQt56R_qzvH-7670w54P7-n6zwuR4qsV5Yk7l5ScsaqXc_tR0xdXEuccwmmYdcgFXK_zFs5ggCTxfOBvd8u0w4N3nPEOrfQwefbLPAJuLD7ikEgi9I4crxk5Z_lJf-WnXc8EmOZ33rxENjbGPKGANO9XwqqX1KBaBtnOWaTJV1V38O6pXsAEC";
        $dados['valor_entrada'] = "50000";
        $dados['parcelas'] = [60,120,180];
        $dados['horario'] = ["manha","tarde"];
        $dados['remetente']['fone'] = $dados['fone'];
        $dados['remetente']['nome'] = $dados['nome'];
        $dados['remetente']['email'] = $dados['email'];
        $this->dados = $dados;
        $retorno = $this->email();
        echo json_encode($retorno);
        
    }
    
    public function index()
    {
        $this->set_dados();
        $valida = $this->set_recaptcha();
        if ( $valida )
//        if ( TRUE )
        {
            $retorno = $this->email();
        }
        else
        {
            $retorno = array('status' => FALSE, 'mensagem' => 'Me parece um robo... tente de novo...');
        }
        echo json_encode($retorno);
    }
    
    public function set_recaptcha()
    {
        $keys = json_decode($this->cidade->google_keys);
        $post = array('secret' => $keys->secret, 'response' => $this->dados['token'] );
        $endereco = 'https://www.google.com/recaptcha/api/siteverify';
        $j_curl = $this->curl_executavel($endereco, $post);
        $curl = json_decode($j_curl);
        return $curl->success;
    }
    
    
    private function set_dados()
    {
        $dados = $this->input->post(NULL,TRUE);
        $dados['remetente']['email'] = $dados['email'];
        $dados['remetente']['fone'] = $dados['fone'];
        $dados['remetente']['nome'] = $dados['nome'];
        $this->dados = $dados;
        return $this;
    }
    
    public function set_spam()
    {
        $f_spam = 'email like "'.$this->dados['remetente']['email'].'"';
        $retorno = $this->spam_model->get_item($f_spam);
        if ( isset($retorno) )
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
        
    }
    
    public function set_assunto()
    {
        $retorno = ['empresa'=>'','usuario'=>''];
        switch ($this->dados['tipo'])
        {
            case 'tab-ligacao':
                $this->dados['tipo_salva'] = 'solicita_ligacao';
                $retorno['empresa'] = 'Solicitação de ligação enviado pelo portal: '.substr($this->cidade->portal, 11).' - '.$this->dados['assunto'];
                $retorno['usuario'] = 'Obrigado por solicitar a ligação no portal '.substr($this->cidade->portal, 11).' - '.$this->dados['assunto'];
                break;
            case 'tab-simule':
                $this->dados['tipo_salva'] = 'simulacao_financimento';
                $retorno['empresa'] = 'Simulação de financiamento enviado pelo portal: '.substr($this->cidade->portal, 11).' - '.$this->dados['assunto'];
                $retorno['usuario'] = 'Obrigado por Simulação de financiamento no portal '.substr($this->cidade->portal, 11).' - '.$this->dados['assunto'];
                break;
            case 'tab-pergunte':
                $this->dados['tipo_salva'] = 'pergunta_imovel';
                $retorno['empresa'] = 'Contato enviado pelo portal: '.substr($this->cidade->portal, 11).' - '.$this->dados['assunto'];
                $retorno['usuario'] = 'Obrigado por pesquisar no portal '.substr($this->cidade->portal, 11).' - '.$this->dados['assunto'];
                break;
        }
        $this->assunto = $retorno;
        return $this;
    }
    
    public function set_data_mensagem()
    {
        $retorno = [];
        $retorno['cidade'] = $this->cidade;
        $retorno['imovel'] = $this->imovel;
        $retorno['empresa'] = $this->empresa;
        $retorno['dados'] = $this->dados;
        $retorno['assunto'] = $this->assunto;
        $retorno['logo'] = base_url().'imagens/'.$this->logo_principal;
        $retorno['link'] = $this->lista_normal->_set_link($this->imovel,10);
        $images = $this->lista_normal->get_images($this->imovel);
        $retorno['image'] = $images['arquivo'];
        return $retorno;
    }
    
    public function set_mensagem()
    {
        $retorno = ['empresa'=>'','usuario'=>''];
        $data = $this->set_data_mensagem();
        switch ($this->dados['tipo'])
        {
            case 'tab-ligacao':
                $retorno['empresa'] = $this->layout->view('email/solicite_ligacao_empresa', $data, 'layout/sem_head', TRUE);
                $retorno['usuario'] = $this->layout->view('email/solicite_ligacao_usuario', $data, 'layout/sem_head', TRUE);
                break;
            case 'tab-simule':
                $retorno['empresa'] = $this->layout->view('email/simule_financiamento_empresa', $data, 'layout/sem_head', TRUE);
                $retorno['usuario'] = $this->layout->view('email/simule_financiamento_usuario', $data, 'layout/sem_head', TRUE);
                break;
            case 'tab-pergunte':
                $retorno['empresa'] = $this->layout->view('email/pergunte_empresa', $data, 'layout/sem_head', TRUE);
                $retorno['usuario'] = $this->layout->view('email/pergunte_usuario', $data, 'layout/sem_head', TRUE);
                break;
        }
        $this->mensagem = $retorno;
        return $this;
    }
    
    
    public function set_imovel()
    {
        $this->imovel = $this->imoveis_mongo_model->get_item( $this->dados['id_imovel'], FALSE );
        return $this;
    }
    
    public function set_empresa()
    {
        $this->empresa = $this->empresas_model->get_item($this->dados['id_empresa']);
        return $this;
    }
    
    public function get_array_contato()
    {
        $array_cad['data'] = time();
        $array_cad['id_canal'] = '29';
        $array_cad['nome'] = $this->dados['remetente']['nome'];
        $array_cad['email'] = $this->dados['remetente']['email'];
        $array_cad['fone'] = ( isset($this->dados['remetente']['fone']) ? $this->dados['remetente']['fone'] : 0);
        $array_cad['senha'] = '17het3';
        $array_cad['news'] = isset($this->dados['aceito']) && ! empty($this->dados['aceito']) ? $this->dados['aceito'] : 1;
        $array_cad['status'] = '0';
        $array_cad['id_origem'] = '2';
        $array_cad['datatu'] = date('Y-m-d H:i');
        if ( isset($this->dados['remetente']['cidade']) && ! empty($this->dados['remetente']['cidade']) ){
            $array_cad['cidade'] = $this->dados['remetente']['cidade'];
            $array_cad['estado'] = isset($this->dados['remetente']['estado']) ? $this->dados['remetente']['estado'] : '';
            $this->load->model('cidades_model');
            $f_cidade = 'cidades.nome LIKE "'.$dados['remetente']['cidade'].'"';
            $city = $this->cidades_model->get_cidade_estado($f_cidade);
            if ( isset($city) ){
                $array_cad['id_pais']   = $city->pais;
                $array_cad['id_cidade'] = $city->id;
                $array_cad['estado']    = $city->uf;
            }
        }
        return $array_cad;
    }
    
    /**
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
     */
    public function set_cadastro()
    {
        $cad_email = $this->cadastro_model->get_item_email($this->dados['remetente']['email']);
        if ( $cad_email == 0 )
        {
            $array_cad = $this->get_array_contato();
            $id_cadastro = $this->cadastro_model->adicionar($array_cad);
            if ( ! $id_cadastro )
            {
                $erro = $array_cad;
                $erro['tipo_erro'] = 'add_cadastro';
                $this->erros_mongo_model->adicionar($erro);
            }
        }
        else
        {
            $id_cadastro = $this->cadastro_model->get_item_id_por_email($this->dados['remetente']['email']);
            $d = array( 'datatu' => date('Y-m-d H:i') );
            $f = array( 'id' => $id_cadastro );
            $this->cadastro_model->editar( $d, $f );
        }

        $data_ip = array(
                        'id_cadastro' => (isset($id_cadastro) ? $id_cadastro : NULL),
                        'id_contato' => (isset($this->id_contato) ? $this->id_contato : 0),
                        'ip' => $_SERVER['REMOTE_ADDR'],
                        'data' => date('Y-m-d H:i'),
                        );
        $this->cadastro_model->adicionar_ip($data_ip);
        $this->id_cadastro = isset($id_cadastro) ? $id_cadastro : NULL;
        return $this;
    }
    
    public $id_contato;
    
    public function set_contato()
    {
        $array_contato = [
                                'data'          => time() , 
                                'id_empresa'    => $this->dados['id_empresa'] , 
                                'nome'          => $this->dados['remetente']['nome'] , 
                                'email'         => $this->dados['remetente']['email'] , 
                                'assunto'       => $this->assunto['usuario'].' - IP: '.$_SERVER['REMOTE_ADDR'] , 
                                'mensagem'      => $this->dados['mensagem'] , 
                                'fone'          => ( isset($this->dados['remetente']['fone']) ? $this->dados['remetente']['fone'] : 0) , 
                                'origem'        => $this->dados['local'],
                                'id_item'       => isset($this->dados['id_imovel']) ? $this->dados['id_imovel'] : '',
                                'id_cidade'     => isset($this->imovel->id_cidade) ? $this->imovel->id_cidade : $this->id_cidade, 
                                'sms_enviado'   => isset($this->sms) ? $this->sms : 0, 
                                'portal'        => substr($this->cidade->portal, 11),
                                'id_tipo_item'  => isset($this->imovel->id_tipo) ? $this->imovel->id_tipo : 0,
                                'tipo_negocio_item' => $this->imovel->tipo
                                ];
        $this->load->model('cadastro_model');
        if ( $this->dados['remetente']['email'] == 'programacao@pow.com.br' ){
            $id_contato = true;
        }else{
            $id_contato = $this->cadastro_model->adicionar_contato($array_contato);
        }
        if ( ! $id_contato )
        {
            $erro = $array_contato;
            $erro['tipo_erro'] = 'add_contato';
            $this->erros_mongo_model->adicionar($erro);
        }
        $this->id_contato = $id_contato;
        return $this;
    }
    
    public function set_sms()
    {
        if ( ! LOCALHOST )
        {
            if ( $this->imovel->sms_limite > 0 )
            {
                switch($this->imovel->sms_quem)
                {
                    case 0:
                        $sms_parametro['destinatario'] = array();
                        break;
                    case 1:
                        $sms_parametro['destinatario'][] = array('telefone' => $this->imovel->empresa_telefone_sms,);
                        break;
                    case 2:
                        $sms_parametro['destinatario'][] = array('telefone' => $this->imovel->empresa_telefone_sms,);
                        if ( $this->imovel->sms_corretor )
                        {
                            $sms_parametro['destinatario'][] = array('telefone' => $this->imovel->celular_corretor,);
                            
                        }
                        break;
                    case 3:
                        if ( $this->imovel->sms_corretor )
                        {
                            $sms_parametro['destinatario'][] = array('telefone' => $this->imovel->celular_corretor,);
                        }
                        break;
                }
                
            }
            $sms_parametro['empresa'] = array('id' => $this->dados['id_empresa'], 'limite' => $this->imovel->sms_limite );
            $sms_parametro['mensagem'] = array('tipo' => $this->imovel->tipo, 'id_imovel' => $this->dados['id_imovel'], 'referencia' => $this->imovel->referencia, 'nome' => $this->dados['remetente']['nome'], 'fone' => $this->dados['remetente']['fone'], 'portal' => substr($this->cidade->portal,11));
            $this->load->library('sms');
            $this->sms = $this->sms->dispara_sms($sms_parametro);
        }
        else
        {
            $sms_parametro['destinatario'][] = array('telefone' => '41996311662',);
            $sms_parametro['destinatario'][] = array('telefone' => '41988649329',);
            $sms_parametro['empresa'] = array('id' => $this->dados['id_empresa'], 'limite' => 1000 );
            $sms_parametro['mensagem'] = array('tipo' => $this->imovel->tipo, 'id_imovel' => $this->dados['id_imovel'], 'referencia' => $this->imovel->referencia, 'nome' => $this->dados['remetente']['nome'], 'fone' => $this->dados['remetente']['fone'], 'portal' => substr($this->cidade->portal,11));
            $this->load->library('sms');
            $this->sms = $this->sms->dispara_sms($sms_parametro);
        }
        return $this;
    }
    
    public function set_envia_empresa()
    {
        $data_e['assunto'] = $this->assunto['empresa'];
        $data_e['to'][] = $this->imovel->empresa_email;
        if ( LOCALHOST || $this->dados['remetente']['email'] == 'programacao@pow.com.br')
        {
            $data_e['to'] = ['programacao@pow.com.br','carlosclaro79@gmail.com'];
            
        }
        else
        {
            if( isset($this->imovel->email_corretor) && ! empty($this->imovel->email_corretor) )
            {
                $data_e['to'][] = $this->imovel->email_corretor;
            }
            if ( $this->imovel->tipo_negocio == 'locacao' && ! empty($this->imovel->locacao_email))
            {
                $data_e['to'][] = $this->imovel->locacao_email;
                
            }
        }
        $data_e['reply'] = [$this->dados['remetente']['email'],$this->dados['remetente']['nome']];
        $data_e['mensagem'] = $this->mensagem['empresa'];
        $data_e['retorno'] = TRUE;
        $data_e['iagente'] = TRUE;
        $data_e['portal'] = substr($this->cidade->portal, 11);
        $enviado = $this->envio($data_e);
        if ( ! $enviado['status'] )
        {
            $erro_add = $enviado;
            $erro_add['tipo_erro'] = 'email_empresa';
            $this->erros_mongo_model->adicionar($erro_add);
        }
        $this->envio['empresa'] = $enviado;
        return $this;
    }
    
    public $envio = [];
    
    public function set_envia_usuario()
    {
        $data_e['assunto'] = $this->assunto['usuario'];
        $data_e['to'] = $this->dados['remetente']['email'];
        if ( LOCALHOST || $this->dados['remetente']['email'] == 'programacao@pow.com.br' )
        {
            $data_e['to'] = ['programacao@pow.com.br','carlosclaro79@gmail.com'];
        }
        $data_e['reply'] = [$this->imovel->empresa_email,$this->empresa->contato_nome];
        $data_e['mensagem'] = $this->mensagem['usuario'];
        $data_e['retorno'] = TRUE;
        $data_e['portal'] = substr($this->cidade->portal, 11);
        $enviado = $this->envio($data_e);
        if ( ! $enviado['status'] )
        {
            $erro_add = $enviado;
            $erro_add['tipo_erro'] = 'email_usuario';
            $this->erros_mongo_model->adicionar($erro_add);
        }
        $this->envio['usuario'] = $enviado;
        return $this;
    }
    
    public function get_envio($tipo = 'usuario')
    {
        return isset($this->envio[$tipo]) ? $this->envio[$tipo] : NULL;
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
    public function email()
    {
        if ( ! isset($this->dados['remetente']['email']) && empty($this->dados['remetente']['email']) )
        {
            return ['status' => FALSE, 'mensagem' => 'Acho que seu e-mail se perdeu no caminho, tente novamente ou nos avise, em programacao@pow.com.br...'];
        }
        $spam = $this->set_spam();
        if ( $spam )
        {
            return ['status' => FALSE, 'mensagem' => 'Seu e-mail esta em nossa lista negra. Caso deseje retirar, entre em contato com comercial@pow.com.br.'];
        }
        $this->set_assunto();
        $this->set_imovel();
        $this->set_empresa();
        $this->set_mensagem();
        $this->set_contato();
        $this->set_cadastro();
        $this->set_envia_empresa();
        $this->set_envia_usuario();
//        $this->set_sms();
        $get_envio = $this->get_envio('empresa');
        if ( $get_envio['status'] )
        {
            $array = ['id_contato' => $this->id_contato];
            $this->set_log_mongo($this->dados['id_imovel'],25,$this->dados['tipo_salva'],$array);
            return ['status' => TRUE, 'mensagem' => 'Mensagem enviada com sucesso.'];
        }
        else
        {
            $array = ['id_contato' => $this->id_contato, 'tipo_erro' => 'envio','dados' => $this->dados];
            $this->set_log_mongo($this->dados['id_imovel'],25,$this->dados['tipo_salva'],$array);
            return ['status' => FALSE, 'mensagem' => 'Problemas no envio de mensagem, tente novamente.'];
        }
    }
    
    
    public function set_envia_usuario_nao_encontrei($mensagem)
    {
        $data_e['assunto'] = 'Obrigado, Sua mensagem foi enviada a nossos associados.';
        $data_e['to'] = $this->dados['remetente']['email'];
        if ( LOCALHOST || $this->dados['remetente']['email'] == 'programacao@pow.com.br' )
        {
            $data_e['to'] = ['programacao@pow.com.br','carlosclaro79@gmail.com'];
        }
        $data_e['reply'] = ['ll@pow.com.br','Comercial'];
        $data_e['mensagem'] = $mensagem['usuario'];
        $data_e['retorno'] = TRUE;
        $data_e['portal'] = substr($this->cidade->portal, 11);
        $enviado = $this->envio($data_e);
        if ( ! $enviado['status'] )
        {
            $erro_add = $enviado;
            $erro_add['tipo_erro'] = 'nao_encontrei_usuario';
            $this->erros_mongo_model->adicionar($erro_add);
        }
        $this->envio['usuario'] = $enviado;
        return $this;
    }
    
    
    private function set_dados_nao_encontrei($dados_teste = FALSE)
    {
        $dados = $dados_teste ? $dados_teste : $this->input->post(NULL,TRUE);
        $dados['remetente']['email'] = $dados['email'];
        $dados['remetente']['fone'] = $dados['telefone'];
        $dados['remetente']['nome'] = $dados['nome'];
        $dados['remetente']['cidade'] = $dados['cidade'];
        $dados['aceito'] = isset($dados['aceito']) ? 1 : 0;
        $pedido = ( isset($dados['observacao']) ? $dados['observacao'] : '' ).'<br>'.PHP_EOL.'Campos selecionados:<br>'.PHP_EOL;
        if ( isset($dados['oq']) && ! empty($dados['oq']) )
        {
            $oq = explode('-',$dados['oq']);
            $oq = array_unique($oq);
            $oq = implode(',',$oq);
            $pedido .= ' o que busca: '.$oq.'<br>'.PHP_EOL;
        }
        if ( isset($dados['complemento']['tipo']) && ! empty($dados['complemento']['tipo']) )
        {
            $pedido .= ' Tipo: '. implode('-', $dados['complemento']['tipo']).'<br>'.PHP_EOL;
        }
        if( isset($dados['complemento']['cidade']) )
        {
            $pedido .= ' Cidade: '.implode(' - ',$dados['complemento']['cidade']).'<br>'.PHP_EOL;
            
        }
        elseif ( isset($dados['cidade_']) && ! empty($dados['cidade_']) )
        {
            //var_dump()
            $ci = explode('-',$dados['cidade_']);
            $ci = array_unique($ci);
            $ci = implode(',',$ci);
            $pedido .= ' Cidade: '.$ci.'<br>'.PHP_EOL;
        }
        if ( isset($dados['complemento']['bairro']) && ! empty($dados['complemento']['bairro']) )
        {
            $pedido .= ' Bairros: '.implode(',',$dados['complemento']['bairro']).'<br>'.PHP_EOL;
        }
        if ( isset($dados['complemento']['valormin']) )
        {
            $pedido .= ' valor minimo: '.$dados['complemento']['valormin'].'<br>'.PHP_EOL;
        }
        if ( isset($dados['complemento']['valormax']) )
        {
            $pedido .= ' valor maximo: '.$dados['complemento']['valormax'].'<br>'.PHP_EOL;
        }
        $dados['pedido'] = $pedido;
        $this->dados = $dados;
        return $this;
    }
    
    public function set_data_mensagem_nao_encontrei()
    {
        $retorno = [];
        $retorno['cidade'] = $this->cidade;
        $retorno['dados'] = $this->dados;
        $retorno['logo'] = base_url().'imagens/'.$this->logo_principal;
        $retorno['titulo'] = 'Solicitação de não encontrei';
        return $retorno;
    }
    public function set_mensagem_nao_encontrei()
    {
        $data = $this->set_data_mensagem_nao_encontrei();
        $retorno['usuario'] = $this->layout->view('email/nao_encontrei_usuario', $data, 'layout/sem_head', TRUE);
        return $retorno;
        
    }
    
    public function nao_encontrei()
    {
        $this->load->model(['imoveis_naoencontrei_model']);
        $this->set_dados_nao_encontrei();
        $valida = $this->set_recaptcha();
        if ( $valida )
        {
            $this->set_cadastro();
            $mensagem = $this->set_mensagem_nao_encontrei();
            $this->set_envia_usuario_nao_encontrei($mensagem);
            $data_nao = array(
                            'id_cadastro' => $this->id_cadastro,
                            'data' => time(),
                            'pedido' => $this->dados['pedido'],
                            'finalidade' => isset($this->dados['oq']) ? $this->dados['oq'] : '',
                            'cidade_interesse' => isset($this->dados['complemento']['cidade'][0]) ? $this->dados['complemento']['cidade'][0] : $this->dados['cidade_'],
                            );
            $insert = $this->imoveis_naoencontrei_model->adicionar($data_nao);
            if( isset($insert) && $insert )
            {
                $retorno = array('status' => TRUE, 'mensagem' => 'Seu registro foi salvo e logo será contatado...');
            }
        }
        else
        {
            $retorno = array('status' => FALSE, 'mensagem' => 'Me parece um robo... tente de novo...');
        }
        echo json_encode($retorno);
    }
    
    public function nao_encontrei_ads()
    {
        $this->load->model(['imoveis_naoencontrei_model']);
        $dados['remetente']['email'] = 'programacao@pow.com.br';
        $dados['remetente']['fone'] = '11111111';
        $dados['remetente']['nome'] = 'prog teste';
        $dados['remetente']['cidade'] = 'prog cidade';
        $post = json_decode($this->input->raw_input_stream);
        
        $pedido = '';
        foreach($post as $chave => $valor){
            $pedido .= $chave .': '.$valor.PHP_EOL;
        }
        
//            $this->set_cadastro();
//            $mensagem = $this->set_mensagem_nao_encontrei();
//            $this->set_envia_usuario_nao_encontrei($mensagem);
            $data_nao = array(
                            'id_cadastro' => 1,
                            'data' => time(),
                            'pedido' => $pedido,
//                            'finalidade' => isset($this->dados['oq']) ? $this->dados['oq'] : '',
//                            'cidade_interesse' => isset($this->dados['complemento']['cidade'][0]) ? $this->dados['complemento']['cidade'][0] : $this->dados['cidade_'],
                            );
            $insert = $this->imoveis_naoencontrei_model->adicionar($data_nao);
            if( isset($insert) && $insert )
            {
                $retorno = array('status' => TRUE, 'mensagem' => 'Seu registro foi salvo e logo será contatado...');
            }
        
        echo json_encode($retorno);
    }
    
    public function teste_nao_encontrei()
    {
        $data['aceito'] = 1;
        $data['cidade']	= 'curitiba';
        $data['cidade_'] = 'curitiba';
        $data['complemento']['bairro'][] = 'uberaba';
        $data['complemento']['cidade'][] = 'curitiba_pr';
        $data['complemento']['tipo'][] =	'apartamento';
        $data['complemento']['valor_max'] = 100000;
        $data['complemento']['valor_min']	= 50000;
        $data['email'] = 'programacao@pow.com.br';
        $data['estado'] = 'pr';
        $data['nome'] =	'Carlos';
        $data['observacao'] = 'teste';
        $data['oq'] = 'Locar';
        $data['telefone'] = 99999999;
        $this->set_dados_nao_encontrei($data);
        $mensagem = $this->set_mensagem_nao_encontrei();
        $this->set_envia_usuario_nao_encontrei($mensagem);
        
    }
    
    /**
     * refatorar...
     */
    
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
    
    /**
     *
     * Conferencia.
     */
    
    
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
    
    
    
}