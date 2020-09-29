<?php
class sms_Model extends MY_Model {
	
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
    }
	
    public function adicionar( $data = array() )
    {
    	$this->db->insert('SMS', $data); 
        return $this->db->insert_id();
    }
    
    public function adicionar_consumo( $data = array() )
    {
    	$this->db->insert('sms_consumo', $data); 
        return $this->db->insert_id();
    }
    
    public function editar($data = array(),$filtro = array())
    {
        $this->db->update('SMS', $data, $filtro);  
        return $this->db->affected_rows();
    }

    public function editar_consumo($data = array(),$filtro = array())
    {
        $this->db->update('sms_consumo', $data, $filtro);  
        return $this->db->affected_rows();
    }
    
    public function excluir($filtro)
    {
        $this->db->delete('SMS',$filtro);
        return $this->db->affected_rows();
    }
    
    public function get_item( $filtro )
    {
    	$data['coluna'] = '*';
    	$data['tabela'] = array(
                                array('nome' => 'SMS'),
                                );
    							
    	$data['filtro'] = $filtro;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0];
    }
    
    public function get_item_por_id( $id )
    {
    	$data['coluna'] = '*';
    	$data['tabela'] = array(
                                array('nome' => 'SMS'),
                                );
    							
    	$data['filtro'] = 'SMS.SMSID = '.$id;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0]->id;
    }
	
    public function get_item_consumo_por_id_empresa( $id_empresa )
    {
    	$data['coluna'] = 'sms_consumo.id as id,'
                . 'sms_consumo.id_empresa as id_empresa,'
                . 'sms_consumo.ano as ano,'
                . 'sms_consumo.mes as mes,'
                . 'sms_consumo.liberado as liberado,'
                . 'sms_consumo.consumo as consumo';
    	$data['tabela'] = array(
                                array('nome' => 'sms_consumo'),
                                );
    							
    	$data['filtro'][] = 'sms_consumo.id_empresa = '.$id_empresa;
        $data['filtro'][] = 'sms_consumo.ano = '.date('Y');
        $data['filtro'][] = 'sms_consumo.mes = '.date('n');
    	$retorno = $this->get_itens_($data);
    	if ( isset($retorno['itens'][0]) )
        {
            $ret = $retorno['itens'][0];
        }
        else
        {
            $ret = FALSE;
        }
        return $ret;
    }
	
}