<?php
class Bairro_Model extends MY_Model {
	
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
    }
    
    public function editar($data = array(),$filtro = array())
    {
        $this->db->update('bairros', $data, $filtro);  
        return $this->db->affected_rows();
    }
    
    public function get_select_json( $filtro = array() )
    {
    	$data['coluna'] = 'bairros.link as id, bairros.nome as descricao, COUNT(imoveis.id) as qtde ';
    	$data['tabela'] = array(
                                 array('nome' => 'bairros'),
                                array('nome' => 'imoveis',      'where' => 'imoveis.bairro_combo = bairros.id', 'tipo' => 'LEFT'),
                                array('nome' => 'empresas',	'where' => 'imoveis.id_empresa = empresas.id AND empresas.pagina_visivel = 1', 'tipo' => 'INNER'),
                                array('nome' => 'imoveis_tipos','where' => 'imoveis.id_tipo = imoveis_tipos.id', 'tipo' => 'INNER'),
                                array('nome' => 'logradouros',	'where' => 'imoveis.id_logradouro = logradouros.id', 'tipo' => 'LEFT'),
                                array('nome' => 'cidades',      'where' => 'cidades.id = imoveis.id_cidade')
                                );
    							
    	$data['filtro'] = $filtro;
    	$data['col'] = 'id';
    	$data['ordem'] = 'ASC';
        $data['group'] = 'bairros.id';
    	$retorno = $this->get_itens_($data, ( isset($_GET['usuario']) ? 1 : 0 ) );
    	return $retorno['itens'];
    }
    
    
    public function get_select( $filtro = array() )
    {
    	$data['coluna'] = 'bairros.link as id, bairros.nome as descricao ';
    	$data['tabela'] = array(
                                array('nome' => 'bairros'),
                                array('nome' => 'cidades', 'where' => 'cidades.id = bairros.cidade')
                                );
    							
    	$data['filtro'] = $filtro;
    	$data['col'] = 'id';
    	$data['ordem'] = 'ASC';
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    public function get_itens_mongo( $filtro = array() )
    {
    	$data['coluna'] = 'bairros.link as id, bairros.id as _id, bairros.link as link, bairros.nome as nome, cidades.id as id_cidade, cidades.nome as cidade, cidades.uf as uf, cidades.link as cidade_link ';
    	$data['tabela'] = array(
                                 array('nome' => 'bairros'),
                                array('nome' => 'cidades',      'where' => 'cidades.id = bairros.cidade')
                                );
    							
    	$data['filtro'] = $filtro;
    	$data['col'] = 'id';
    	$data['off_set'] = 0;
        $data['qtde_itens'] = 50000;
    	$data['ordem'] = 'ASC';
        $data['group'] = 'bairros.id';
    	$retorno = $this->get_itens_($data, ( isset($_GET['usuario']) ? 1 : 0 ) );
    	return $retorno['itens'];
    }
    
    public function get_por_nome_cidade( $bairro, $id_cidade )
    {
    	$data['coluna'] = 'bairros.id as id';
    	$data['tabela'] = array(
                                array('nome' => 'bairros'),
                                array('nome' => 'cidades', 'where' => 'cidades.id = bairros.cidade')
                                );
    							
    	$data['filtro'] = 'bairros.nome LIKE "'.$bairro.'" AND cidades.id = '.$id_cidade;
    	$data['col'] = 'id';
    	$data['ordem'] = 'ASC';
    	$retorno = $this->get_itens_($data);
    	return isset( $retorno['itens'][0]->id ) ? $retorno['itens'][0]->id : 0;
    }
    
    public function get_por_nome_cidade_equivalentes( $bairro, $id_cidade )
    {
    	$data['coluna'] = 'bairros_equivalentes.id_bairro as id';
    	$data['tabela'] = array(
                                array('nome' => 'bairros_equivalentes'),
                                array('nome' => 'cidades', 'where' => 'cidades.id = bairros_equivalentes.id_cidade')
                                );
    							
    	$data['filtro'] = 'bairros_equivalentes.nome_equivalente LIKE "'.$bairro.'" AND cidades.id = '.$id_cidade;
    	$data['col'] = 'id';
    	$data['ordem'] = 'ASC';
    	$retorno = $this->get_itens_($data);
    	return isset( $retorno['itens'][0]->id ) ? $retorno['itens'][0]->id : 0;
    }
    
    public function get_item_por_modalidade ( $filtro )
    {
        $data['coluna'] = '	
                            bairros.id as id, bairros.nome as descricao, count(imoveis.id) as qtde, bairros.link as link
                        ';
    	$data['tabela'] = array(
                                array('nome' => 'bairros'),
                                array('nome' => 'imoveis',      'where' => 'imoveis.bairro_combo = bairros.id', 'tipo' => 'LEFT'),
                                array('nome' => 'empresas',	'where' => 'imoveis.id_empresa = empresas.id AND empresas.pagina_visivel = 1', 'tipo' => 'INNER'),
                                array('nome' => 'imoveis_tipos','where' => 'imoveis.id_tipo = imoveis_tipos.id', 'tipo' => 'INNER'),
                                array('nome' => 'logradouros',	'where' => 'imoveis.id_logradouro = logradouros.id', 'tipo' => 'LEFT'),
                                array('nome' => 'cidades',      'where' => 'cidades.id = imoveis.id_cidade')
                                );
    	$data['filtro'] = $filtro;
    	$data['group'] = 'bairros.id';
        $data['col'] = 'bairros.nome';
        $data['ordem'] = 'ASC';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['itens'];
    }

    public function get_item_por_modalidade_qtde ( $filtro )
    {
        $data['coluna'] = '	
                            count(imoveis.id) as qtde
                        ';
    	$data['tabela'] = array(
                                array('nome' => 'bairros'),
                                array('nome' => 'imoveis',      'where' => 'imoveis.bairro_combo = bairros.id', 'tipo' => 'INNER'),
                                array('nome' => 'empresas',	'where' => 'imoveis.id_empresa = empresas.id AND empresas.pagina_visivel = 1', 'tipo' => 'INNER'),
                                array('nome' => 'imoveis_tipos','where' => 'imoveis.id_tipo = imoveis_tipos.id', 'tipo' => 'INNER'),
                                array('nome' => 'cidades',      'where' => 'cidades.id = imoveis.id_cidade')
                                );
    	$data['filtro'] = $filtro;
    	$data['group'] = 'bairros.id';
        $data['col'] = 'bairros.nome';
        $data['ordem'] = 'ASC';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['itens'][0]->qtde;
    }

    
    public function get_item_nome ( $filtro )
    {
        $data['coluna'] = '	
                            bairros.nome as descricao
                        ';
    	$data['tabela'] = array(
                                array('nome' => 'bairros'),
                                );
    	$data['filtro'] = $filtro;
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['itens'][0]->descricao;
    }
    
    public function get_item_nome_por_link ( $link, $cidade = FALSE )
    {
        $data['coluna'] = '	
                            bairros.nome as descricao
                        ';
    	$data['tabela'] = array(
                                array('nome' => 'bairros'),
                                );
    	$data['filtro'][] = 'bairros.link like "'.$link.'" ';
        if ( $cidade )
        {
            $data['filtro'][] = 'bairros.cidade = '.$cidade;
        }
        $data['off_set'] = 0;
        $data['qtde_itens'] = 1;
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['itens'][0]->descricao;
    }
}