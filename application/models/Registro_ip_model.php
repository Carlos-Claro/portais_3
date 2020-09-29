<?php
class Registro_ip_Model extends MY_Model {
	
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
    }
	
    public function adicionar( $data = array() )
    {
    	$this->db->insert('registro_ip', $data); 
		return $this->db->insert_id();
    }
    
    
    public function editar($data = array(),$filtro = array())
	{
		$this->db->update('registro_ip', $data, $filtro);  
		return $this->db->affected_rows();
	}

	public function excluir($filtro)
	{
		$this->db->delete('registro_ip',$filtro);
		return $this->db->affected_rows();
	}
    
       
    public function get_item( $filtro = array() )
    {
    	$data['coluna'] = '*';
    	$data['tabela'] = array(
                                array('nome' => 'registro_ip'),
                                );
    							
    	$data['filtro'] = $filtro;
    	$retorno = $this->get_itens_($data);
    	return isset($retorno['itens'][0]) ? $retorno['itens'][0] : NULL;
    }
	
	
}