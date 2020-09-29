<?php
class Imoveis_naoencontrei_Model extends MY_Model {
	
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
    }

    public function adicionar( $data = array() )
    {
        $this->db->insert('imoveis_naoencontrei', $data); 
        return $this->db->insert_id();
    }
    
    public function get_item()
    {
    	$data['coluna'] = ' * ';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis_parametros'),
                            );
    							
    	$data['filtro'] = 'id = 1';
    	$retorno = $this->get_itens_($data);
    	return isset($retorno['itens'][0]) ? $retorno['itens'][0] : NULL;
    }
    
    public function get_itens( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '	
                            imoveis_naoencontrei.id as id,
                            FROM_UNIXTIME(imoveis_naoencontrei.data,"%d/%m/%Y %h:%i:%s") as data,
                            imoveis_naoencontrei.id_cadastro as id_cadastro,
                            imoveis_naoencontrei.pedido as pedido, 
                            imoveis_naoencontrei.respostas as respostas,
                            imoveis_naoencontrei.cidade_interesse as cidade_interesse, 
                            imoveis_naoencontrei.enviado as enviado,
                            imoveis_naoencontrei.finalidade as finalidade,
                            IF(imoveis_naoencontrei.mostra_p_i = 1,\'Sim\',\'Não\') as invisivel,
                            cadastros.nome as nome,
                            cadastros.email as email,
                            cadastros.fone as telefone,
                            cadastros.cidade as cidade,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis_naoencontrei'),
                                array('nome' => 'cadastros', 'where' => 'cadastros.id = imoveis_naoencontrei.id_cadastro', 'tipo' => 'INNER'),
                                //array('nome' => 'categorias', 'where' => 'subcategorias.id_categoria = categorias.id', 'tipo' => 'INNER'),
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
    public function get_parametros( $filtro = array(),$coluna = 'respostas_nao_encontrei', $ordem = 'ASC' )
    {
    	$data['coluna'] = 'imoveis_parametros.dias_nao_encontrei as dias, imoveis_parametros.respostas_nao_encontrei as respostas ';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis_parametros'),
                                );
    							
    	$data['filtro'] = $filtro;
        $data['col'] = $coluna;
    	$data['ordem'] = $ordem;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
}