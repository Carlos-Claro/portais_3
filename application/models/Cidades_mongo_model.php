<?php
class Cidades_mongo_Model extends My_mongo {
	
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
    }

    public function adicionar($data = array())
    {
        return $this->adicionar_multi_('cidades', $data);  
    }
    
    public function adicionar_one($data = array())
    {
        return $this->adicionar_('cidades', $data);  
    }
    
    public function excluir($filtro)
    {
        return $this->excluir_('cidades', $filtro);
    }
    
    public function excluir_all()
    {
        return $this->delete_all('cidades');
    }
    
    /**
     * alteração dia 13/01 inclusão de dominios para direcionamento correto do portal
     * @param type $portal
     * @return type
     */
    public function get_item_por_portal( $portal = 'icuritiba.com', $dominio = FALSE )
    {
        
    	$data['tabela'] = 'cidades';
        if (strstr($portal, 'http://www.') )
        {
            $portal = str_replace('http://www.', '', $portal);
        }
        elseif (strstr($portal, 'http://') )
        {
            $portal = str_replace('http://', '', $portal);
        }
        elseif (strstr($portal, 'https://') )
        {
            $portal = str_replace('https://', '', $portal);
        }
        elseif (strstr($portal, 'www.') )
        {
            $portal = str_replace('www.', '', $portal);
        }
        $portal = 'http://www.'.$portal;
        $barra = substr($portal, -1);
        if ( $barra == '/' )
        {
            $portal = substr($portal, 0,-1);
        }
        if ( $dominio ){
            $data['filtro'][] = array('tipo'=> 'like', 'campo' => 'dominio', 'valor' => str_replace(['http//www.','http://', 'https://', 'https://www.','.com','.com.br','www.'],'',$portal));
        }else{
            $data['filtro'][] = array('tipo'=> 'like', 'campo' => 'portal', 'valor' => $portal);
        }
    	$retorno = $this->get_item_($data);
        
    	return $retorno;
    }
    
    public function get_item_por_link( $link = 'curitiba_pr' )
    {
    	
    	$data['tabela'] = 'cidades';
    							
    	$data['filtro'] = array(array('tipo'=> 'where', 'campo' => 'link', 'valor' => $link ) );
    	
    	$retorno = $this->get_itens_($data);
        foreach ($retorno['itens'] as $item)
        {
            $retorno_item = $item;
        }
        //var_dump($retorno['itens']);
    	return $retorno_item;
    }

    public function get_nome_por_link( $link )
    {
    	$data['tabela'] = 'cidades';
    	$data['filtro'] = array(array('tipo'=> 'where', 'campo' => 'link', 'valor' => $link ) );
    	$retorno = $this->get_item_($data);
    	return $retorno->nome.' - '.$retorno->uf;
    }
    
    public function get_link_por_id( $id )
    {
    	$data['tabela'] = 'cidades';
    	$data['filtro'] = array(array('tipo'=> 'where', 'campo' => '_id', 'valor' => $id ) );
    	$retorno = $this->get_item_($data);
    	return $retorno->link;
    }

    public function get_estado_por_uf( $uf )
    {
    	$data['coluna'] = 'CONCAT(estados.nome, " - ", estados.uf, " " ) as nome';
    	$data['tabela'] = array(
                                array('nome' => 'estados')
                                );
    							
    	$data['filtro'] = 'estados.uf = "'.$uf.'"';
    	$data['col'] = 'uf';
    	$data['ordem'] = 'ASC';
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0]->nome;
    }

    
    public function get_item_logo_por_portal( $id )
    {
    	$data['coluna'] = 'cidades.topo as topo';
    	$data['tabela'] = array(
                                array('nome' => 'cidades')
                                );
    							
    	$data['filtro'] = 'cidades.id = '.$id;
    	$data['col'] = 'id';
    	$data['ordem'] = 'ASC';
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0]->topo;
    }
    
    public function get_item_analytics_por_portal( $id )
    {
    	$data['coluna'] = 'cidades.instrucoes_head as topo';
    	$data['tabela'] = array(
                                array('nome' => 'cidades')
                                );
    							
    	$data['filtro'] = 'cidades.id = '.$id;
    	$data['col'] = 'id';
    	$data['ordem'] = 'ASC';
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0]->topo;
    }
    
    public function get_item_google_tag_por_portal( $id )
    {
    	$data['coluna'] = 'cidades.google_tag as tag';
    	$data['tabela'] = array(
                                array('nome' => 'cidades')
                                );
    							
    	$data['filtro'] = 'cidades.id = '.$id;
    	$data['col'] = 'id';
    	$data['ordem'] = 'ASC';
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0]->tag;
    }

    public function get_item_por_modalidade ( $filtro )
    {
        $data['coluna'] = '	
                            cidades.link as id, cidades.nome as descricao, count(imoveis.id) as qtde, cidades.link as link, cidades.uf as uf, cidades.portal as portal, cidades.libera as libera
                        ';
    	$data['tabela'] = array(
                                array('nome' => 'cidades'),
                                array('nome' => 'imoveis',      'where' => 'imoveis.id_cidade = cidades.id', 'tipo' => 'INNER'),
                                array('nome' => 'empresas',	'where' => 'imoveis.id_empresa = empresas.id AND empresas.pagina_visivel = 1', 'tipo' => 'LEFT'),
                                array('nome' => 'bairros',	'where' => 'imoveis.bairro_combo = bairros.id', 'tipo' => 'LEFT'),
                                array('nome' => 'logradouros',	'where' => 'imoveis.id_logradouro = logradouros.id', 'tipo' => 'LEFT'),
                                array('nome' => 'imoveis_tipos','where' => 'imoveis.id_tipo = imoveis_tipos.id', 'tipo' => 'LEFT'),
                                );
    	$data['filtro'] = $filtro;
    	$data['group'] = 'cidades.id';
        $data['col'] = 'cidades.nome';
        $data['ordem'] = 'ASC';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['itens'];
    }
    
    public function get_item_cidade_por_modalidade ( $filtro )
    {
        $data['coluna'] = '	
                            cidades.id as id, CONCAT(cidades.nome, " / ", cidades.uf) as descricao, count(imoveis.id) as qtde, cidades.link as link, cidades.uf as uf, cidades.uf as uf, cidades.portal as portal, cidades.libera as libera
                        ';
    	$data['tabela'] = array(
                                array('nome' => 'cidades'),
                                array('nome' => 'imoveis',      'where' => 'imoveis.id_cidade = cidades.id', 'tipo' => 'LEFT'),
                                array('nome' => 'empresas',	'where' => 'imoveis.id_empresa = empresas.id AND empresas.pagina_visivel = 1', 'tipo' => 'LEFT'),
                                array('nome' => 'imoveis_tipos','where' => 'imoveis.id_tipo = imoveis_tipos.id', 'tipo' => 'LEFT'),
                                );
    	$data['filtro'] = $filtro;
    	$data['group'] = 'cidades.id';
        $data['col'] = 'cidades.nome';
        $data['ordem'] = 'ASC';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['itens'];
    }
    
    public function get_item_cidade_todas ( $filtro )
    {
        $data['coluna'] = '	
                            cidades.id as id, CONCAT(cidades.nome, " / ", cidades.uf) as descricao, cidades.link as link, cidades.uf as uf
                        ';
    	$data['tabela'] = array(
                                array('nome' => 'cidades'),
                                );
    	$data['filtro'] = $filtro;
    	$data['group'] = 'cidades.id';
        $data['col'] = 'cidades.nome';
        $data['ordem'] = 'ASC';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['itens'];
    }
    
    public function get_item_estado ( $filtro )
    {
        $data['coluna'] = '	
                            estados.uf as id, estados.nome as descricao, count(imoveis.id) as qtde, estados.uf as link
                        ';
    	$data['tabela'] = array(
                                array('nome' => 'estados'),
                                array('nome' => 'cidades',      'where' => 'cidades.uf = estados.uf'),
                                array('nome' => 'imoveis',      'where' => 'imoveis.id_cidade = cidades.id', 'tipo' => 'LEFT'),
                                array('nome' => 'empresas',	'where' => 'imoveis.id_empresa = empresas.id AND empresas.pagina_visivel = 1', 'tipo' => 'LEFT'),
                                array('nome' => 'imoveis_tipos','where' => 'imoveis.id_tipo = imoveis_tipos.id', 'tipo' => 'LEFT'),
                                );
    	$data['filtro'] = $filtro;
    	$data['group'] = 'estados.uf';
        $data['col'] = 'estados.nome';
        $data['ordem'] = 'ASC';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['itens'];
    }
    
    public function get_item_por_uf_capital ( $filtro = NULL )
    {
        $data['coluna'] = '	
                            cidades.link as id, cidades.nome as descricao, count(imoveis.id) as qtde, cidades.link as link, cidades.uf as uf
                        ';
    	$data['tabela'] = array(
                                array('nome' => 'cidades'),
                                array('nome' => 'imoveis',      'where' => 'imoveis.id_cidade = cidades.id', 'tipo' => 'INNER'),
                                array('nome' => 'empresas',	'where' => 'imoveis.id_empresa = empresas.id AND empresas.pagina_visivel = 1', 'tipo' => 'INNER'),
                                array('nome' => 'imoveis_tipos','where' => 'imoveis.id_tipo = imoveis_tipos.id', 'tipo' => 'INNER'),
                                );
    	$data['filtro'] = $filtro;
    	$data['group'] = 'cidades.id';
        $data['col'] = 'cidades.nome';
        $data['ordem'] = 'ASC';
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['itens'];
    }
    
    public function get_total_itens ( $filtro = array() )
    {
        $data['coluna'] = '	
                            count(cidades.id) as qtde,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'cidades'),
                                );
    	$data['filtro'] = $filtro;
    	$retorno = $this->get_itens_($data);
    	return isset($retorno['itens'][0]->qtde) ? $retorno['itens'][0]->qtde : 0;
    }
    
    public function get_itens ( $filtro = NULL )
    {
    	$data['tabela'] = 'cidades';
        if ( isset($filtro) )
        {
            $data['filtro'] = $filtro;
        }
        $data['qtde_itens'] = 5000;
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno;
    }
    
    public function get_desconto_por_cidade( $id_cidade = 1 )
    {
        $data['coluna'] = '	
                            cidades.desconto as desconto
                        ';
    	$data['tabela'] = array(
                                array('nome' => 'cidades'),
                                );
    	$data['filtro'] = 'cidades.id = '.$id_cidade;
    	$retorno = $this->get_itens_($data);
    	
    	return isset($retorno['itens'][0]->desconto) ? $retorno['itens'][0]->desconto : 0;
    }
    
    
}