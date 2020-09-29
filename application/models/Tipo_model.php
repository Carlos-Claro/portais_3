<?php
class Tipo_Model extends MY_Model {
	
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
    }
	
    public function get_select( $filtro = array() )
    {
    	$data['coluna'] = 'imoveis_tipos.link as id, imoveis_tipos.nome as descricao';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis_tipos'),
                                );
    							
    	$data['filtro'] = $filtro;
    	$data['col'] = 'id';
    	$data['ordem'] = 'ASC';
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    public function get_select_json( $filtro = array() )
    {
    	$data['coluna'] = 'imoveis_tipos.link as id, imoveis_tipos.nome as descricao, imoveis_tipos.plural, imoveis_tipos.english as english';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis_tipos'),
                                array('nome' => 'imoveis',      'where' => 'imoveis.id_tipo = imoveis_tipos.id', 'tipo' => 'INNER'),
                                array('nome' => 'empresas',	'where' => 'imoveis.id_empresa = empresas.id AND empresas.pagina_visivel = 1', 'tipo' => 'INNER'),
                                array('nome' => 'bairros',	'where' => 'imoveis.bairro_combo = bairros.id', 'tipo' => 'LEFT'),
                                array('nome' => 'logradouros',	'where' => 'imoveis.id_logradouro = logradouros.id', 'tipo' => 'LEFT'),
                                array('nome' => 'cidades',      'where' => 'cidades.id = imoveis.id_cidade')
                                );
    							
    	$data['filtro'] = $filtro;
    	$data['col'] = 'id';
    	$data['ordem'] = 'ASC';
        $data['group'] = 'imoveis_tipos.id';
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    public function get_select_por_qtde( $filtro = array(), $qtde_itens = 3 )
    {
    	$data['coluna'] = 'imoveis_tipos.link as id, imoveis_tipos.plural as plural, imoveis_tipos.nome as descricao, COUNT(imoveis.id) as qtde ';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis_tipos'),
                                array('nome' => 'imoveis',      'where' => 'imoveis.id_tipo = imoveis_tipos.id', 'tipo' => 'INNER'),
                                array('nome' => 'empresas',	'where' => 'imoveis.id_empresa = empresas.id AND empresas.pagina_visivel = 1', 'tipo' => 'INNER'),
                                array('nome' => 'bairros',	'where' => 'imoveis.bairro_combo = bairros.id', 'tipo' => 'LEFT'),
                                array('nome' => 'logradouros',	'where' => 'imoveis.id_logradouro = logradouros.id', 'tipo' => 'LEFT'),
                                array('nome' => 'cidades',      'where' => 'cidades.id = imoveis.id_cidade')
                                );
    							
    	$data['filtro'] = $filtro;
        $data['off_set'] = 0;
        $data['qtde_itens'] = $qtde_itens;
    	$data['col'] = 'qtde';
    	$data['ordem'] = 'DESC';
        $data['group'] = 'imoveis_tipos.id';
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    public function get_item_por_modalidade ( $filtro, $coluna = NULL )
    {
        $data['coluna'] = '	
                            imoveis_tipos.id as id, imoveis_tipos.nome as descricao, count(imoveis.id) as qtde, imoveis_tipos.link as link
                        ';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis_tipos'),
                                array('nome' => 'imoveis',      'where' => 'imoveis.id_tipo = imoveis_tipos.id', 'tipo' => 'INNER'),
                                array('nome' => 'empresas',	'where' => 'imoveis.id_empresa = empresas.id AND empresas.pagina_visivel = 1', 'tipo' => 'INNER'),
                                array('nome' => 'bairros',	'where' => 'imoveis.bairro_combo = bairros.id', 'tipo' => 'LEFT'),
                                array('nome' => 'logradouros',	'where' => 'imoveis.id_logradouro = logradouros.id', 'tipo' => 'LEFT'),
                                array('nome' => 'cidades',      'where' => 'cidades.id = imoveis.id_cidade')
            
                                );
    	$data['filtro'] = $filtro;
    	$data['group'] = 'imoveis_tipos.id';
        $data['col'] = isset($coluna) ? 'qtde' : 'imoveis_tipos.nome';
        $data['ordem'] = isset($coluna) ? 'DESC' : 'ASC';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['itens'];
    }
    
    public function get_item_por_modalidade_qtde ( $filtro )
    {
        $data['coluna'] = '	
                            count(imoveis.id) as qtde
                        ';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis_tipos'),
                                array('nome' => 'imoveis',      'where' => 'imoveis.id_tipo = imoveis_tipos.id', 'tipo' => 'INNER'),
                                array('nome' => 'empresas',	'where' => 'imoveis.id_empresa = empresas.id AND empresas.pagina_visivel = 1', 'tipo' => 'INNER'),
                                array('nome' => 'bairros',	'where' => 'imoveis.bairro_combo = bairros.id', 'tipo' => 'INNER'),
                                array('nome' => 'cidades',      'where' => 'cidades.id = imoveis.id_cidade')
            
                                );
    	$data['filtro'] = $filtro;
    	$data['group'] = 'imoveis_tipos.id';
        $data['col'] = 'imoveis_tipos.nome';
        $data['ordem'] = 'ASC';
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0]->qtde;
    }
    
    public function get_item_nome ( $filtro )
    {
        $data['coluna'] = '	
                            imoveis_tipos.nome as descricao
                        ';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis_tipos'),
                                );
    	$data['filtro'] = $filtro;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0]->descricao;
    }
    
    public function get_item_nome_por_link ( $link )
    {
        $data['coluna'] = '	
                            imoveis_tipos.nome as descricao 
                        ';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis_tipos'),
                                );
    	$data['filtro'] = 'imoveis_tipos.link like "'.$link.'"';
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0]->descricao;
    }
    
    public function get_item_plural_por_link ( $link )
    {
        $data['coluna'] = '	
                            imoveis_tipos.plural as descricao 
                        ';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis_tipos'),
                                );
    	$data['filtro'] = 'imoveis_tipos.link like "'.$link.'"';
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0]->descricao;
    }
    
    
}