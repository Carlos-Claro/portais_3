<?php
class Unidades_Model extends MY_Model {
	
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
    }
	
    public function adicionar( $data = array() )
    {
    	$this->db->insert('rest_unidade', $data); 
		return $this->db->insert_id();
    }
    
    public function editar($data = array(),$filtro = array())
	{
		$this->db->update('rest_unidade', $data, $filtro);  
		return $this->db->affected_rows();
	}

	public function excluir($filtro)
	{
		$this->db->delete('rest_unidade',$filtro);
		return $this->db->affected_rows();
	}
    
    public function get_item( $id = '' )
    {
    	$data['coluna'] = '*';
    	$data['tabela'] = array(
                                array('nome' => 'rest_unidade'),
                                );
    							
    	$data['filtro'] = 'rest_unidade.id_unidade = '.$id;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0];
    }
	
	
	public function get_select( $filtro = array() )
    {
    	$data['coluna'] = 'rest_unidade.id_unidade as id, rest_unidade.titulo as descricao ';
    	$data['tabela'] = array(
                                array('nome' => 'rest_unidade'),
                                );
    							
    	$data['filtro'] = $filtro;
    	$data['col'] = 'id';
    	$data['ordem'] = 'ASC';
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    public function get_itens( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '	
                            rest_unidade.id_unidade as id,
                            rest_unidade.titulo as titulo,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'rest_unidade'),
                                );
    	$data['filtro'] = $filtro;
    	if ( isset($off_set) )
    	{
    		$data['off_set'] = $off_set;
    	}
    	$data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno;
    }

    
}