<?php
class Bairros_mongo_Model extends My_mongo {
	
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
    }

    public function adicionar($data = array())
    {
        return $this->adicionar_multi_('bairros', $data);  
    }
    
    public function excluir($filtro)
    {
        return $this->excluir_('bairros', $filtro);
    }
    
    public function excluir_all()
    {
        return $this->delete_all('bairros');
    }
    
    public function get_item_por_link( $link = 'curitiba_pr' )
    {
    	
    	$data['tabela'] = 'bairros';
    							
    	$data['filtro'] = array(array('tipo'=> 'where', 'campo' => 'link', 'valor' => $link ) );
    	
    	$retorno = $this->get_itens_($data);
        foreach ($retorno['itens'] as $item)
        {
            $retorno_item = $item;
        }
        //var_dump($retorno['itens']);
    	return $retorno_item;
    }
    
    
    public function get_busca_por_cidade( $cidade, $bairro = '', $link = FALSE )
    {
    	$data['tabela'] = 'bairros';
    	$data['filtro'][] = array('tipo'=> 'where', 'campo' => 'cidade_link', 'valor' => $cidade );
        $data['filtro'][] = array('tipo'=> 'like', 'campo' => 'nome_', 'valor' => $bairro );
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }

    public function get_nome_por_link( $link )
    {
        $data['coluna'] = array('nome');
    	$data['tabela'] = 'bairros';
    	$data['filtro'] = array(array('tipo'=> 'where', 'campo' => 'link', 'valor' => $link ) );
    	$retorno = $this->get_item_($data);
    	return $retorno->nome;
    }

    
}