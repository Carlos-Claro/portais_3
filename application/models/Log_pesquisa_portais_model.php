<?php
/**
 * 
 */
class Log_pesquisa_portais_Model extends MY_Model {
	
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
    }

    public function adicionar( $data = array() )
    {
        $this->db->insert('log_pesquisa_portais', $data); 
        return $this->db->insert_id();
    }
    
    public function get_item_por_id( $id_bairro, $data_ = FALSE, $limit = 4 )
    {
        $data['coluna'] = '*';
    	$data['tabela'] = array(
                                array('nome' => 'log_pesquisa_portais'),
                                );
    	$data['filtro'] = '';
    	$data['col'] = 'views';
    	$data['ordem'] = 'DESC';
        $data['off_set'] = 0;
        $data['qtde_itens'] = $limit;
    	$retorno = $this->get_itens_($data);
    	return $retorno;
    }
    
    public function get_ranking( $id_cidade )
    {
        $data['coluna'] = 'bairros.link as id, bairros.nome as descricao';
    	$data['tabela'] = array(
                                array('nome' => 'bairros_views'),
                                array('nome' => 'bairros', 'where' => 'bairros.link = bairros_views.link_bairro', 'tipo' => 'INNER')
                                );
        $filtro[] = 'bairros.cidade = '.$id_cidade;
        $filtro[] = 'data BETWEEN "'.date('Y-m-d',strtotime("-1 week")).'" AND "'.date('Y-m-d').'"';
    	$data['filtro'] = $filtro;
    	$data['col'] = 'views';
    	$data['ordem'] = 'DESC';
        $data['off_set'] = 0;
        $data['qtde_itens'] = 6;
        $data['group'] = 'bairros.id';
    	$retorno = $this->get_itens_($data);
        
        
        
        $ret = $retorno['itens'];
        return $ret;
    }
    
    public function get_itens_por_cidade_filtro( $cidade_link = 'curitiba_pr', $group = 'bairro', $filtro = array(), $limit = 50 )
    {
        
        $data['coluna'] = ' ';
        $data['coluna'] .= ' COUNT( log_pesquisa_portais.id ) as qtde, ';
        $data['coluna'] .= ' log_pesquisa_portais.cidade as cidade, ';
        $data['coluna'] .= ' log_pesquisa_portais.negocio as negocio, ';
        $data['coluna'] .= ' log_pesquisa_portais.tipo as tipo, ';
        $data['coluna'] .= ' log_pesquisa_portais.bairro as bairro, ';
        $data['coluna'] .= ' log_pesquisa_portais.valor_min as valor_min, ';
        $data['coluna'] .= ' log_pesquisa_portais.valor_max as valor_max ';
    	$data['tabela'] = array(
                                array('nome' => 'log_pesquisa_portais'),
                                );
        
        if ( is_array($filtro) )
        {
            $data['filtro'] = $filtro;
        }
        else
        {
            $data['filtro'][] = $filtro;
        }
        $data['filtro'][] = 'log_pesquisa_portais.data BETWEEN "'.date('Y-m-d H:i', mktime (0, 0, 0, date("m"), date("d")-5,  date("Y")) ).'" AND "'.date('Y-m-d').' 23:59:59"';
        $data['filtro'][] = 'log_pesquisa_portais.cidade like "'.$cidade_link.'"';
        $data['col'] = 'qtde';
    	$data['ordem'] = 'DESC';
        $data['group'] = $group;
        $data['off_set'] = 0;
        $data['qtde_itens'] = $limit;
    	$retorno = $this->get_itens_($data);
    	return $retorno;
    }
    
    
    public function set_log( $valores )
    {
        $negocio = isset($valores['negocio']) ? $valores['negocio'] : NULL;
        $add = array();
        if ( isset($valores['tipos']) && count($valores['tipos']) > 0 )
        {
            if ( isset($valores['bairros']) && count($valores['bairros']) > 0 )
            {
                foreach( $valores['bairros'] as $bairro )
                {
                    foreach( $valores['tipos'] as $tipo )
                    {
                        $add[] = array(
                            'cidade' => $valores['cidade'],
                            'bairro' => $bairro,
                            'negocio' => $negocio,
                            'tipo' => $tipo,
                            'valor_min' => $valores['valor_min'],
                            'valor_max' => $valores['valor_max'],
                            'ip' => $_SERVER['REMOTE_ADDR']
                        );
                    }
                }
            }
            else
            {
                foreach( $valores['tipos'] as $tipo )
                {
                    $add[] = array(
                        'cidade' => $valores['cidade'],
                        'negocio' => $negocio,
                        'tipo' => $tipo,
                        'valor_min' => $valores['valor_min'],
                        'valor_max' => $valores['valor_max'],
                        'ip' => $_SERVER['REMOTE_ADDR']
                    );
                }
                
            }
        }
        else
        {
            if ( isset($valores['bairros']) && count($valores['bairros']) > 0 )
            {
                foreach( $valores['bairros'] as $bairro )
                {
                    $add[] = array(
                        'cidade' => $valores['cidade'],
                        'bairro' => $bairro,
                        'negocio' => $negocio,
                        'valor_min' => $valores['valor_min'],
                        'valor_max' => $valores['valor_max'],
                        'ip' => $_SERVER['REMOTE_ADDR']
                    );
                }
            }
            else
            {
                $add[] = array(
                    'cidade' => $valores['cidade'],
                    'negocio' => $negocio,
                    'valor_min' => $valores['valor_min'],
                    'valor_max' => $valores['valor_max'],
                    'ip' => $_SERVER['REMOTE_ADDR']
                );
            }
        }
        foreach ( $add as $adicionar )
        {
            $this->adicionar($adicionar);
        }
    }
    
    
    
   
    
}