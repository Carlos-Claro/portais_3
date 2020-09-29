<?php
class Imoveis_historico_Model extends MY_Model {
	
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
    }
    
    public function adicionar( $data = array() )
    {
    	$this->db->insert('imoveis_historico', $data); 
        return $this->db->insert_id();
    }
    
    public function editar($data = array(),$filtro = array())
    {
        $this->db->update('imoveis_historico', $data, $filtro);  
        return $this->db->affected_rows();
    }
    
       
    public function get_item_por_id( $id = '', $destaque = FALSE )
    {
    	$data['coluna'] = '
                            imoveis_historico.id as id,
                            imoveis_historico.id as _id,
                            imoveis_historico.nome as nome,
                            imoveis_historico.id_tipo as id_tipo,
                            imoveis_historico.id_empresa as id_empresa,
                            imoveis_historico.negocio as imovel_para,
                            imoveis_historico.negocio as tipo_negocio,
                            if ( imoveis_historico.negocio ="venda", 1, 0) as venda,
                            if ( imoveis_historico.negocio ="locacao", 1, 0) as locacao,
                            if ( imoveis_historico.negocio ="locacao_dia", 1, 0) as locacao_dia,
                            imoveis_historico.id_cidade as id_cidade,
                            imoveis_historico.id_bairro as id_bairro,
                            bairros.link as bairros_link,
                            imoveis_tipos.link as imoveis_tipos_link,
                            cidades.link as cidade_link,
                            cidades.link as cidades_link,
                            "" as imobiliaria_nome_seo,
                            '; 
    	$data['tabela'] = array(
                                array('nome' => 'imoveis_historico'),
                                array('nome' => 'imoveis_tipos',    'where' => 'imoveis_historico.id_tipo = imoveis_tipos.id', 'tipo' => 'LEFT'),
                                array('nome' => 'bairros',    'where' => 'imoveis_historico.id_bairro = bairros.id', 'tipo' => 'LEFT'),
                                array('nome' => 'cidades',          'where' => 'cidades.id = imoveis_historico.id_cidade', 'tipo' => 'LEFT'),
                                );
    							
    	$data['filtro'][] = 'imoveis_historico.id = '.$id;
    	$item = $this->get_itens_($data);
    	return isset($item['itens'][0]) ? $item['itens'][0] : FALSE;
    }
	
	
}
