<?php
class Cadastro_Model extends MY_Model {
	
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
    }
	
    public function adicionar( $data = array() )
    {
    	$this->db->insert('cadastros', $data); 
		return $this->db->insert_id();
    }
    
    public function adicionar_contato( $data = array() )
    {
    	$this->db->insert('contatos_site', $data); 
        return $this->db->insert_id();
    }
    
    public function adicionar_ip( $data = array() )
    {
    	$this->db->insert('cadastros_ip', $data); 
        return $this->db->insert_id();
    }
    
    public function editar($data = array(),$filtro = array())
	{
		$this->db->update('cadastros', $data, $filtro);  
		return $this->db->affected_rows();
	}

	public function excluir($filtro)
	{
		$this->db->delete('cadastros',$filtro);
		return $this->db->affected_rows();
	}
    
    public function get_item_email( $email = '' )
    {
    	$data['coluna'] = 'count(id)as qtde';
    	$data['tabela'] = array(
                                array('nome' => 'cadastros'),
                                );
    							
    	$data['filtro'] = 'cadastros.email = "'.$email.'"';
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0]->qtde;
    }
    
    public function get_item_id_por_email( $email = '' )
    {
    	$data['coluna'] = 'cadastros.id as id';
    	$data['tabela'] = array(
                                array('nome' => 'cadastros'),
                                );
    							
    	$data['filtro'] = 'cadastros.email = "'.$email.'"';
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0]->id;
    }
	
    public function get_item_por_email( $email = '' )
    {
    	$data['coluna'] = 'cadastros.nome as nome'
                . ', cadastros.fone as telefone'
                . ', cidades.nome as cidade'
                . ', cidades.uf as uf';
    	$data['tabela'] = array(
                                array('nome' => 'cadastros'),
                                array('nome' => 'cidades', 'where' => 'cidades.id = cadastros.id_cidade', 'tipo' => 'LEFT'),
                                );
    							
    	$data['filtro'] = 'cadastros.email = "'.$email.'"';
    	$retorno = $this->get_itens_($data);
    	return isset($retorno['itens'][0]) ? $retorno['itens'][0] : NULL;
    }
    
    
    public function get_itens_contatos( $filtro , $off_set = 0, $qtde = 200 )
    {
    	$data['coluna'] = 'contatos_site.id_empresa as id_empresa'
                . ', contatos_site.id as id'
                . ', contatos_site.data as data'
                . ', contatos_site.nome as nome'
                . ', contatos_site.email as email'
                . ', contatos_site.assunto as assunto'
                . ', contatos_site.fone as fone'
                . ', contatos_site.id_item as id_imovel'
                . ', contatos_site.mensagem as mensagem';
    	$data['tabela'] = array(
                                array('nome' => 'contatos_site'),
                                );
    							
    	$data['filtro'] = $filtro;
        $data['ordem'] = 'ASC';
        $data['col'] = 'data';
        $data['off_set'] = $off_set;
        $data['qtde_itens'] = $qtde;
    	$retorno = $this->get_itens_($data);
        //var_dump($retorno);die();
    	return $retorno ;
    }
}