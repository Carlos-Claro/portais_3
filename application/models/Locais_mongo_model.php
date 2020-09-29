<?php
class Locais_mongo_Model extends My_mongo {
	
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
    }

    public function get_item_por_id( $id )
    {
    	$data['tabela'] = 'locais';
    	$data['filtro'] = array(array('tipo'=> 'where', 'campo' => 'id', 'valor' => strval($id) ) );
    	$retorno = $this->get_item_($data);
    	return $retorno;
    }

    
}