<?php
class Empresas_email_mkt_Model extends MY_Model {
	
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
    }
	
    public function adicionar( $data = array() )
    {
    	$this->db->insert('empresas_email_mkt', $data); 
		return $this->db->insert_id();
    }
    
    public function editar($data = array(),$filtro = array())
    {
            $this->db->update('empresas_email_mkt', $data, $filtro);  
            return $this->db->affected_rows();
    }

    public function excluir($filtro)
    {
            $this->db->delete('empresas_email_mkt',$filtro);
            return $this->db->affected_rows();
    }
    
    public function get_item( $id = '' )
    {
    	$data['coluna'] = '*';
    	$data['tabela'] = array(
                                array('nome' => 'empresas_email_mkt'),
                                );
    							
    	$data['filtro'] = 'empresas_email_mkt.id = '.$id;
    	$data['off_set'] = 0;
        $data['qtde_itens'] = 1;
        $itens = $this->get_itens_($data);
        
        if ( isset($itens['itens'][0]) )
        {
            $retorno = $itens['itens'][0];
            
        }
        else
        {
            $retorno = NULL;
        }
    	return $retorno;
    }
    
    public function get_imoveis_por_campanha( $id_email )
    {
    	$data['coluna'] = '*';
    	$data['tabela'] = array(
                                array('nome' => 'empresas_email_mkt_imoveis'),
                                );
    							
    	$data['filtro'] = 'empresas_email_mkt_imoveis.id_email_mkt = '.$id_email;
        $itens = $this->get_itens_($data);
        if ( isset($itens['itens']) && $itens['qtde'] > 0 )
        {
            $this->load->model('imoveis_model');
            foreach( $itens['itens'] as $imovel )
            {
                $imoveis[] = $imovel->id_imovel;
            }
            $filtro = 'imoveis.id IN ('.implode(',',$imoveis).')';
            $retorno = $this->imoveis_model->get_itens($filtro,'ordem_rad', 'ASC', 0, count($imoveis));
        }
        else
        {
            $retorno = NULL;
        }
        
        return $retorno;
    }
	
    public function get_select( $filtro = array() )
    {
    	$data['coluna'] = 'empresas_email_mkt.id as id, empresas_email_mkt.titulo as descricao ';
    	$data['tabela'] = array(
                                array('nome' => 'empresas_email_mkt'),
                                );
    							
    	$data['filtro'] = $filtro;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    public function get_total_itens ( $filtro = array() )
    {
        $data['coluna'] = '	
                            empresas.id as id,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'empresas_email_mkt'),
                                array('nome' => 'imoveis', 'where' => 'empresas.id_subcategoria = subcategorias.id', 'tipo' => 'INNER'),
                                array('nome' => 'categorias', 'where' => 'subcategorias.id_categoria = categorias.id', 'tipo' => 'INNER'),
                                array('nome' => 'logradouros', 'where' => 'empresas.id_logradouro = logradouros.id', 'tipo' => 'LEFT'),
                                );
    	$data['filtro'] = $filtro;
        $data['group'] = 'id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['qtde'];
    }
    
    public function get_item_por_id( $id )
    {
        
    	$data['coluna'] = '	
                            id, id_empresa, data_disparo, titulo, tipo_destaque, qtde_destaques
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'empresas'),
                                array('nome' => 'logradouros',      'where' => 'empresas.id_logradouro = logradouros.id', 'tipo' => 'LEFT'),
                                array('nome' => 'cidades',          'where' => 'cidades.id = logradouros.id_cidade', 'tipo' => 'LEFT'),
                                array('nome' => 'imoveis',          'where' => 'imoveis.id_empresa = empresas.id', 'tipo' => 'INNER'),
                                );
        $data['filtro'] = 'empresas.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	$this->load->model('imoveis_model');
        if ( isset($retorno['itens'][0]->id) )
        {
            $item = $retorno['itens'][0];
        }
        else
        {
            $item = NULL;
        }
        
        if ( isset($item) )
        {
            $this->imoveis_model->get_log($item->id, 11, 'clicks');
        }
    	return $item;
    }
    
    
    public function get_itens_imobiliarias( $filtro = array(), $coluna = 'empresas.ordenacao', $ordem = 'DESC', $log = TRUE )
    {
    	$data['coluna'] = '	
                            empresas.id as id,
                            empresas.contrato as contrato,
                            CONCAT( "<span itemprop=\'streetAddress\'>",
                                    IF ( empresas.id_logradouro = 0, empresas.empresa_endereco, logradouros.logradouro), ", ",
                                    empresas.empresa_numero, " ", empresas.empresa_complemento, " </span> - <span itemprop=\'addressLocality\'>",
                                    IF (empresas.id_logradouro = 0, empresas.empresa_bairro, logradouros.bairro), " - ",
                                    logradouros.cidade, "</span> - <span itemprop=\'addressRegion\'>", cidades.uf, "</span>"
                                    ) as endereco,
                            empresas.empresa_numero as endereco_numero,
                            empresas.empresa_complemento as endereco_complemento,
                            empresas.pagina_logo_pequeno as logo,
                            CONCAT(cidades.ddd, " - ", empresas.empresa_telefone) as telefone, 
                            empresas.empresa_email as email, 
                            empresas.empresa_dominio as dominio,
                            empresas.empresa_nome_fantasia as nome_fantasia, 
                            empresas.pagina_creci as creci,
                            IF( empresas.latitude <> "", CONCAT( empresas.latitude, ",", empresas.longitude ), "" ) as mapa,
                            empresas.empresa_cnpj as cnpj,
                            empresas.empresa_descricao as descricao,
                            empresas.nome_seo as nome_seo,
                            COUNT(imoveis.id) as imoveis_qtde
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'empresas'),
                                array('nome' => 'logradouros',              'where' => 'empresas.id_logradouro = logradouros.id', 'tipo' => 'LEFT'),
                                array('nome' => 'cidades',                  'where' => 'cidades.id = logradouros.id_cidade', 'tipo' => 'LEFT'),
                                array('nome' => 'imoveis',                  'where' => 'imoveis.id_empresa = empresas.id', 'tipo' => 'LEFT'),
                                array('nome' => 'cidades cidades_imovel',   'where' => 'cidades_imovel.id = imoveis.id_cidade', 'tipo' => 'LEFT'),
                                );
    	$data['filtro'] = $filtro;
        $data['filtro'][] = 'empresas.id_subcategoria = 138';
        $data['filtro'][] = 'empresas.pagina_tipo = "imoveis"';
    	if ( isset($off_set) )
    	{
    		$data['off_set'] = $off_set;
    	}
    	$data['col'] = $coluna;
    	$data['ordem'] = $ordem;
        $data['group'] = 'empresas.id';
    	$retorno = $this->get_itens_($data);
    	$this->load->model('imoveis_model');
        if ( $log )
        {
            if ( isset($retorno['itens']) && count($retorno['itens']) > 0 )
            {
                foreach ( $retorno['itens'] as $item )
                {
                    $this->imoveis_model->get_log($item->id, 11, 'views');
                    $this->imoveis_model->get_log($item->id, 3, 'views');
                    $this->imoveis_model->get_log($item->id, 1, 'views');
                }
            }
        }
    	return $retorno;
    }
    
    public function get_itens( $filtro = array(), $coluna = 'id', $ordem = 'DESC', $off_set = NULL )
    {
    	$data['coluna'] = '	
                            empresas.id as id,
                            empresas.contrato as contrato,
                            CONCAT( 
                                    IF (empresas.id_logradouro = 0, empresas.empresa_cep, logradouros.cep), " - ",
                                    IF (empresas.id_logradouro = 0, empresas.empresa_endereco, logradouros.logradouro), ", ",
                                    empresas.empresa_numero, " ", empresas.empresa_complemento, " - ",
                                    IF (empresas.id_logradouro = 0, empresas.empresa_bairro, logradouros.bairro), " - ",
                                    logradouros.cidade 
                                    ) as endereco,
                            empresas.empresa_numero as endereco_numero,
                            empresas.empresa_complemento as endereco_complemento,
                            empresas.pagina_logo_pequeno as logo,
                            CONCAT(empresas.contato_ddd, " - ", empresas.contato_telefone) as telefone, 
                            empresas.empresa_email as email, 
                            empresas.empresa_dominio as dominio,
                            empresas.empresa_nome_fantasia as nome_fantasia, 
                            empresas.pagina_creci as creci,
                            IF( empresas.latitude <> "", CONCAT( empresas.latitude, ",", empresas.longitude ), "" ) as mapa,
                            empresas.empresa_cnpj as cnpj,
                            empresas.empresa_descricao as descricao,
                            empresas.nome_seo as nome_seo,
                            COUNT(imoveis.id) as imoveis_qtde
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'empresas'),
                                array('nome' => 'logradouros',      'where' => 'empresas.id_logradouro = logradouros.id', 'tipo' => 'LEFT'),
                                array('nome' => 'cidades',          'where' => 'cidades.id = logradouros.id_cidade', 'tipo' => 'LEFT'),
                                array('nome' => 'imoveis',          'where' => 'imoveis.id_empresa = empresas.id', 'tipo' => 'INNER'),
                                );
    	$data['filtro'] = $filtro;
        $data['filtro'][] = 'empresas.id_subcategoria = 138';
    	if ( isset($off_set) )
    	{
    		$data['off_set'] = $off_set;
    	}
    	$data['col'] = $coluna;
    	$data['ordem'] = $ordem;
        $data['group'] = 'empresas.id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno;
    }
    
    public function get_itens_qtde_imoveis( $filtro = array(), $coluna = 'id', $ordem = 'ASC')
    {
    	$data['coluna'] = '	
                            empresas.id as id,
                            count(imoveis.id) as qtde_imoveis,
                            empresas.empresa_nome_fantasia as nome_fantasia, 
                            empresas.empresa_cnpj as cnpj
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'empresas'),
                                array('nome' => 'imoveis', 'where' => 'empresas.id = imoveis.id_empresa', 'tipo' => 'INNER'),
                                );
    	$data['filtro'] = $filtro;
    	$data['col'] = $coluna;
    	$data['ordem'] = $ordem;
        $data['off_set'] = 0;
        $data['qtde_itens'] = 1000000;
        $data['group'] = 'empresas.id';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['itens'];
    }
    
    public function get_item_cadastro( $filtro = NULL )
    {
        $data['coluna'] = '
                            empresas.id as id,
                            empresas.empresa_razao_social as empresa_razao_social,
                            empresas.empresa_nome_fantasia as empresa_nome_fantasia,
                            empresas.empresa_cnpj as empresa_cnpj,
                            empresas.empresa_telefone as empresa_telefone,
                            empresas.id_logradouro as id_logradouro,
                            empresas.empresa_numero as empresa_numero,
                            empresas.empresa_complemento as empresa_complemento,
                            IF (empresas.id_logradouro = 0, empresas.empresa_cep, logradouros.cep) as cep,
                            IF (empresas.id_logradouro = 0, empresas.empresa_endereco, logradouros.logradouro) as endereco,
                            IF (empresas.id_logradouro = 0, empresas.empresa_bairro, logradouros.bairro) as bairro,
                            logradouros.id_cidade as id_cidade,
                            logradouros.cidade as cidade,
                            empresas.empresa_descricao as empresa_descricao,
                            empresas.empresa_email as empresa_email,
                            empresas.empresa_dominio as empresa_dominio,
                            empresas.contato_nome as contato_nome,
                            empresas.contato_email as contato_email,
                            empresas.contato_ddd as contato_ddd,
                            empresas.contato_telefone as contato_telefone,
                            empresas.id_subcategoria as id_subcategoria,
                            empresas.conhece_guia as conhece_guia,
                            empresas.status_atualizada as status_atualizada
                            ';
        $data['tabela'] = array(
                                array('nome' => 'empresas'),
                                array('nome' => 'logradouros', 'where' => 'empresas.id_logradouro = logradouros.id', 'tipo' => 'LEFT'),
                                );
        $data['filtro'] = isset($filtro) ? $filtro : 'logradouros.id_cidade = 1 AND empresas.status_atualizada = 0';
        $data['col'] = 'empresas.id';
        $data['ordem'] = 'random';
        
        $retorno = $this->get_itens_($data);
        return isset($retorno['itens'][0]) ? $retorno['itens'][0] : FALSE;
        
        
    }
    
    
}