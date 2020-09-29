<?php

class SMS {
    
    public $CI;
    
    public function __construct() 
    {
        $this->CI =& get_instance();
    }
    
    public function dispara_sms ( $data )
    {
        $this->CI->load->model('sms_model');
        $consumo = $this->CI->sms_model->get_item_consumo_por_id_empresa($data['empresa']['id']);
        if ( $consumo )
        {
            if ( $consumo->consumo < $consumo->liberado )
            {
                $ret = 0;
                $mensagem = $this->_set_mensagem($data['mensagem']);
                foreach( $data['destinatario'] as $d )
                {
                    $disparo = $this->disparador($mensagem, $d['telefone']);
                    if ( $disparo == 100 )
                    {
                        $salva_sms = array('Data' => date('Y-m-d H:i:s',mktime(date('H'), date('i'), date('s') + $ret, date('m'), date('d'), date('Y') )), 'Celular' => $d['telefone'], 'Mensagem' => $mensagem );
                        $filtro_sms_consumo = 'id = '.$consumo->id;
                        $data_sms_consumo = array('consumo' => ( $consumo->consumo + 1 ));
                        $this->CI->sms_model->adicionar($salva_sms);
                        $this->CI->sms_model->editar_consumo($data_sms_consumo,$filtro_sms_consumo);
                        $ret++;
                    }
                }
            }
            else
            {
                $ret = 0;
            }
        }
        else
        {
            $ret = 0;
            $mensagem = $this->_set_mensagem($data['mensagem']);
            foreach( $data['destinatario'] as $d )
            {
                $disparo = $this->disparador($mensagem, $d['telefone']);
                if ( $disparo == 100 )
                {
                    $salva_sms = array('Data' => date('Y-m-d H:i:s',mktime(date('H'), date('i'), date('s') + $ret, date('m'), date('d'), date('Y') ) ), 'Celular' => $d['telefone'], 'Mensagem' => $mensagem );
                    if ( $ret == 0 )
                    {
                        $data_sms_consumo = array(
                                                'id_empresa' => $data['empresa']['id'], 
                                                'ano' => date('Y'), 
                                                'mes' => date('n'), 
                                                'liberado' => $data['empresa']['limite'],
                                                'consumo' => 1
                                                );
                        $add_sms_consumo = $this->CI->sms_model->adicionar_consumo($data_sms_consumo);
                    }
                    else
                    {
                        $filtro_sms_consumo = 'id = '.$add_sms_consumo;
                        $data_sms_consumo = array(
                                                'consumo' => ($ret + 1), 

                                                );
                        $this->CI->sms_model->editar_consumo($data_sms_consumo,$filtro_sms_consumo);
                    }
                    $this->CI->sms_model->adicionar($salva_sms);
                    $ret++;
                }
            }
        }
        return $ret;
    }
    
    public function disparador( $mensagem, $celular )
    {
        $params['usuario'] = "pow";
        $params['senha']   = "pow2013";
        $params['dest']    = "55".$celular;
        $params['texto']   = $mensagem;
        $url = "http://www.iagentesms.com.br/gateway/sms.php";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_REFERER, $_SERVER['REMOTE_ADDR']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        $retorno = curl_exec($ch);
        curl_close($ch);
        
        return $retorno;
    }
    
    private function _set_mensagem ( $data )
    {
        $retorno = 'POW SMS Informa: '.$data['nome'].',';
        $retorno .= 'Fone:, '.$data['fone'].' ';
        $retorno .= 'quer saber sobre '.$data['tipo'].' ';
        $retorno .= ' imovel:'.$data['id_imovel'].', ref.: '.$data['referencia'].' '.$data['portal'];
        return $retorno;
    }
}