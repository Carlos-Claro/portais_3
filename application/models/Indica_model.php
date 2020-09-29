<?php
class Indica_Model extends MY_Model {
	
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
    public function adicionar( $data = array() )
    {
    	$this->db->insert('imoveis_indica', $data); 
		return $this->db->insert_id();
    }
    
    public function editar($data = array(),$filtro = array())
	{
		$this->db->update('imoveis_indica', $data, $filtro);  
		return $this->db->affected_rows();
	}

	public function excluir($filtro)
	{
		$this->db->delete('imoveis_indica',$filtro);
		return $this->db->affected_rows();
	}
    
}