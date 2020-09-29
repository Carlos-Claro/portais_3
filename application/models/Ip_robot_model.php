<?php
class Ip_robot_Model extends MY_Model {
	
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
    }
	
    public function adicionar( $data = array() )
    {
    	$this->db->insert('ip_robot', $data); 
		return $this->db->insert_id();
    }
    
    
    public function editar($data = array(),$filtro = array())
	{
		$this->db->update('ip_robot', $data, $filtro);  
		return $this->db->affected_rows();
	}

	public function excluir($filtro)
	{
		$this->db->delete('ip_robot',$filtro);
		return $this->db->affected_rows();
	}
    
       
    public function get_item( $filtro = array() )
    {
    	$data['coluna'] = '*';
    	$data['tabela'] = array(
                                array('nome' => 'ip_robot'),
                                );
    							
    	$data['filtro'] = $filtro;
    	$retorno = $this->get_itens_($data);
    	return isset($retorno['itens'][0]) ? $retorno['itens'][0] : NULL;
    }
	
	
}