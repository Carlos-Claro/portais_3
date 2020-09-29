<?php
class Parametro_finalidade_Model extends MY_Model {
	
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
    }

    
    
    public function get_select( $filtro = array() )
    {
    	$data['coluna'] = 'parametro_finalidade.finalidade as id, parametro_finalidade.finalidade as descricao ';
    	$data['tabela'] = array(
                                array('nome' => 'parametro_finalidade')
                                );
    							
    	$data['filtro'] = $filtro;
    	$data['col'] = 'id';
    	$data['ordem'] = 'ASC';
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }

}