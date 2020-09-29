<?php
class Bairros_proximidade_mongo_Model extends My_mongo {
	
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
    }

    public function adicionar($data = array())
    {
        return $this->adicionar_multi_('bairros_proximidade', $data);  
    }
    
    public function excluir($filtro)
    {
        return $this->excluir_('bairros_proximidade', $filtro);
    }
    
    public function excluir_all()
    {
        return $this->delete_all('bairros_proximidade');
    }
    
    public function get_item_por_link_cidade( $link = 'curitiba_pr' )
    {
    	
    	$data['tabela'] = 'bairros_proximidade';
    							
    	$data['filtro'] = array(array('tipo'=> 'where', 'campo' => 'link', 'valor' => $link ) );
    	
    	$retorno = $this->get_itens_($data);
        foreach ($retorno['itens'] as $item)
        {
            $retorno_item = $item;
        }
        //var_dump($retorno['itens']);
    	return $retorno_item;
    }
    
    public function get_item_por_link( $link = NULL )
    {
    	
    	$data['tabela'] = 'bairros_proximidade';
    							
    	$data['filtro'] = array(array('tipo'=> 'where', 'campo' => 'link', 'valor' => $link ) );
    	
    	$retorno = $this->get_item_($data);
    	return $retorno;
    }
    
    
    public function get_busca_por_cidade( $cidade, $bairro = '', $link = FALSE )
    {
    	$data['tabela'] = 'bairros_proximidade';
    	$data['filtro'][] = array('tipo'=> 'where', 'campo' => 'cidade', 'valor' => $cidade );
        $data['filtro'][] = array('tipo'=> 'like', 'campo' => 'titulo_', 'valor' => $bairro );
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }

    public function get_nome_por_link( $link )
    {
        $data['coluna'] = array('titulo');
    	$data['tabela'] = 'bairros_proximidade';
    	$data['filtro'] = array(array('tipo'=> 'where', 'campo' => 'link', 'valor' => $link ) );
    	$retorno = $this->get_item_($data);
    	return $retorno->nome;
    }

    
}