<?php
class Bairros_proximidade_Model extends MY_Model {
	
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
    }
    
    public function get_bairros_por_link( $link )
    {
    	$data['coluna'] = 'bairros_proximidade.link_bairros as id';
    	$data['tabela'] = array(
                                array('nome' => 'bairros_proximidade'),
                                );
    							
    	$data['filtro'] = 'bairros_proximidade.link LIKE "'.$link.'"';
    	$data['col'] = 'id';
    	$data['ordem'] = 'ASC';
    	$retorno = $this->get_itens_($data);
    	return isset( $retorno['itens'][0]->id ) ? $retorno['itens'][0]->id : 0;
    }
    
    
    public function get_item_nome_por_link ( $link )
    {
        $data['coluna'] = '	
                            bairros_proximidade.titulo as descricao
                        ';
    	$data['tabela'] = array(
                                array('nome' => 'bairros_proximidade'),
                                );
    	$data['filtro'][] = 'bairros_proximidade.link like "'.$link.'" ';
        $data['off_set'] = 0;
        $data['qtde_itens'] = 1;
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['itens'][0]->descricao;
    }
    
    public function get_itens( $filtro, $limit )
    {
        
        if ( isset($filtro['tipo']) && ! empty($filtro['tipo']) )
        {
            $data['coluna'] = '"'.(isset($filtro['tipo']) ? $filtro['tipo'][0] : 0 ).'" as tipo,';
        }
        else
        {
            $data['coluna'] = '"" as tipo, ';
        }
        $data['coluna'] .= '	
                            CONCAT("Proximo ", bairros_proximidade.titulo) as proximidade,
                            CONCAT("proximo_", bairros_proximidade.link) as bairro,
                            0 as qtde, 
                            "'.$filtro['cidade_link'].'" as cidade, ';
        if ( isset($filtro['oq']) && ! empty($filtro['oq']) )
        {
            $data['coluna'] .= 'IF ( "'.$filtro['oq'].'" = "venda", "venda", "locação" ) as negocio, ';
        }
        else
        {
            $data['coluna'] .= '"" as negocio, ';
        }
    	$data['tabela'] = array(
                                array('nome' => 'bairros_proximidade'),
                                );
    	$data['filtro'][] = 'bairros_proximidade.id_cidade like "'.$filtro['cidade'].'" ';
        if ( isset($filtro['bairro']) && ! empty($filtro['bairro']) )
        {
            $data['filtro'][] = 'bairros_proximidade.link_bairros like "%'.$filtro['bairro'][0].'%" ';
        }
        $data['off_set'] = 0;
        $data['qtde_itens'] = $limit;
    	$retorno = $this->get_itens_($data);
        return isset($retorno['itens']) ? $retorno['itens'] : FALSE;
    }
    
    public function get_itens_mongo( )
    {
        
        $data['coluna'] = '*';
    	$data['tabela'] = array(
                                array('nome' => 'bairros_proximidade'),
                                );
        $data['off_set'] = 0;
        $data['qtde_itens'] = 10000;
    	$retorno = $this->get_itens_($data);
        return isset($retorno['itens']) ? $retorno['itens'] : FALSE;
    }
    
}