<?php
class Cidades_Model extends MY_Model {
	
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
    }

    public function editar($data = array(),$filtro = array())
    {
        $this->db->update('cidades', $data, $filtro);  
        return $this->db->affected_rows();
    }
    
    public function get_select( $filtro = array() )
    {
    	$data['coluna'] = 'cidades.link as id, CONCAT(cidades.nome, " / ", cidades.uf) as descricao,  CONCAT(cidades.uf, "_", cidades.nome) as link_novo, cidades.id as _id';
    	$data['tabela'] = array(
                                array('nome' => 'cidades')
                                );
    							
    	$data['filtro'] = $filtro;
    	$data['col'] = 'id';
    	$data['ordem'] = 'ASC';
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }

    public function get_select_json( $filtro = array() )
    {
    	$data['coluna'] = 'cidades.link as id, cidades.nome as descricao';
    	$data['tabela'] = array(
                                array('nome' => 'cidades')
                                );
    							
    	$data['filtro'] = $filtro;
    	$data['col'] = 'id';
    	$data['ordem'] = 'ASC';
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }

    public function get_cidades_com_menu()
    {
        $filtro = 'cidades.ativa IN (1,5)';
        $retorno = $this->get_itens($filtro);
        return $retorno;
        
    }
    
    public function get_cidade_estado ( $filtro = array() )
    {
    	$data['coluna'] = 'cidades.id as id, cidades.nome as nome, cidades.uf as uf, estados.pais as pais';
    	$data['tabela'] = array(
                                array('nome' => 'estados'),
                                array('nome' => 'cidades',      'where' => 'cidades.uf = estados.uf'),
                                );
    							
    	$data['filtro'] = $filtro;
    	$data['col'] = 'id';
    	$data['ordem'] = 'ASC';
    	$retorno = $this->get_itens_($data);
    	if ( isset( $retorno['itens'][0] ) )
        {
            $ret = $retorno['itens'][0];
        }
        else
        {
            $ret = NULL;
        }
        return $ret;
    }

    /**
     * alteração dia 13/01 inclusão de dominios para direcionamento correto do portal
     * @param type $portal
     * @return type
     */
    public function get_item_por_portal( $portal = 'icuritiba.com' )
    {
        $data['coluna'] = '
                            cidades.id as id, 
                            cidades.nome as nome, 
                            cidades.link as link, 
                            cidades.desconto as desconto, 
                            cidades.portal as portal, 
                            cidades.uf as uf, 
                            cidades.ativa as ativa, 
                            cidades.banner_topo as banner_topo,
                            cidades.banner_lateral as banner_lateral,
                            cidades.banner_vertical as banner_vertical,
                            cidades.banner_meio as banner_meio,
                            cidades.banner_meio_2 as banner_meio_2,
                            cidades.banner_rodape as banner_rodape,
                            cidades.sobre_cidade as sobre,
                            cidades_perfil.gentilico as gentilico,
                            cidades.rs_face_simples as rs_face,
                            cidades.rs_googlemais as rs_googlemais,
                            cidades.link_prefeitura as link_prefeitura,
                            cidades.tag_adwords as tag_adwords,
                            cidades.google_tag as google_tag,
                            estados.nome as estado,
                            estados.tratamento as estado_tratamento,
                            cidades.topo as topo,
                            cidades.adstxt as adstxt,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'dominios'),
                                array('nome' => 'cidades',          'where' => 'cidades.id = dominios.cd_cidade', 'tipo' => 'INNER'),
                                array('nome' => 'estados',          'where' => 'cidades.uf = estados.uf', 'tipo' => 'INNER'),
                                array('nome' => 'cidades_perfil',      'where' => 'cidades.id = cidades_perfil.id', 'tipo' => 'LEFT'),
                                );
    	//tratamento de url
        if (strstr($portal, 'http://www.') )
        {
            $portal = str_replace('http://www.', '', $portal);
        }
        elseif (strstr($portal, 'http://') )
        {
            $portal = str_replace('http://', '', $portal);
        }
        elseif (strstr($portal, 'www.') )
        {
            $portal = str_replace('www.', '', $portal);
        }
        elseif (strstr($portal, 'https://') )
        {
            $portal = str_replace('https://', '', $portal);
        }
        if(LOCALHOST)
        {
            $portal = 'icuritiba';
        }
        $p = explode('.', $portal);	
    	$data['filtro'] = 'dominios.url LIKE "'.$p[0].'"';
    	$data['col'] = 'id';
    	$data['ordem'] = 'ASC';
    	$retorno = $this->get_itens_($data);
    	return isset($retorno['itens'][0]) ? $retorno['itens'][0] : FALSE;
    }
    
    
    /**
     */
    public function get_itens_mongo(  )
    {
        $data['coluna'] = '
                            cidades.id as _id, 
                            cidades.id as id, 
                            GROUP_CONCAT(dominios.url SEPARATOR ", ") as dominio, 
                            cidades.nome as nome, 
                            cidades.link as link, 
                            cidades.desconto as desconto, 
                            cidades.portal as portal, 
                            cidades.uf as uf, 
                            cidades.ativa as ativa, 
                            cidades.banner_topo as banner_topo,
                            cidades.banner_lateral as banner_lateral,
                            cidades.banner_vertical as banner_vertical,
                            cidades.banner_meio as banner_meio,
                            cidades.banner_meio_2 as banner_meio_2,
                            cidades.banner_rodape as banner_rodape,
                            cidades.banner_home as banner_home,
                            cidades.sobre_cidade as sobre,
                            cidades_perfil.gentilico as gentilico,
                            cidades.rs_face_simples as rs_face,
                            cidades.rs_googlemais as rs_googlemais,
                            cidades.link_prefeitura as link_prefeitura,
                            cidades.tag_adwords as tag_adwords,
                            cidades.google_tag as google_tag,
                            estados.nome as estado,
                            estados.tratamento as estado_tratamento,
                            cidades.topo as topo,
                            cidades.cobrar as cobrar,
                            cidades.instrucoes_head as instrucoes_head,
                            cidades.header_tag as header_tag,
                            cidades.google_keys as google_keys,
                            cidades.adstxt as adstxt,
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'cidades',          ),
                                array('nome' => 'dominios',  'where' => 'cidades.id = dominios.cd_cidade', 'tipo' => 'LEFT'),
                                array('nome' => 'estados',          'where' => 'cidades.uf = estados.uf', 'tipo' => 'INNER'),
                                array('nome' => 'cidades_perfil',      'where' => 'cidades.id = cidades_perfil.id', 'tipo' => 'LEFT'),
                                );
    	
    	$data['col'] = 'cidades.id';
    	$data['group'] = 'cidades.id';
    	$data['ordem'] = 'ASC';
        $data['off_set'] = 0;
        $data['qtde_itens'] = 10000000;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    public function get_item_por_link( $link = 'curitiba_pr' )
    {
    	$data['coluna'] = '
                            cidades.id as id, 
                            cidades.nome as nome, 
                            cidades.link as link, 
                            cidades.portal as portal, 
                            cidades.uf as uf,
                            cidades.ativa as ativa, 
                            cidades.banner_topo as banner_topo,
                            cidades.banner_lateral as banner_lateral,
                            cidades.banner_vertical as banner_vertical,
                            cidades.banner_meio as banner_meio,
                            cidades.sobre_cidade as sobre,
                            cidades.link_prefeitura as link_prefeitura,
                            cidades.topo as topo,
                            cidades_perfil.gentilico as gentilico
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'cidades'),
                                array('nome' => 'cidades_perfil',      'where' => 'cidades.id = cidades_perfil.id', 'tipo' => 'LEFT'),
                                );
    							
    	$data['filtro'] = 'cidades.link LIKE "'.$link.'"';
    	$data['col'] = 'id';
    	$data['ordem'] = 'ASC';
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0];
    }

    public function get_nome_por_id( $id )
    {
    	$data['coluna'] = 'CONCAT(cidades.nome, " - ", cidades.uf, "  ") as nome';
    	$data['tabela'] = array(
                                array('nome' => 'cidades')
                                );
    							
    	$data['filtro'] = 'cidades.id = '.$id;
    	$data['col'] = 'id';
    	$data['ordem'] = 'ASC';
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0]->nome;
    }

    public function get_nome_por_link( $id )
    {
    	$data['coluna'] = 'CONCAT(cidades.nome, " - ", cidades.uf, "  ") as nome';
    	$data['tabela'] = array(
                                array('nome' => 'cidades')
                                );
    							
    	$data['filtro'] = 'cidades.link like "'.$id.'"';
    	$data['col'] = 'id';
    	$data['ordem'] = 'ASC';
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0]->nome;
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
                            cidades.id as id, CONCAT(cidades.nome, " / ", cidades.uf) as descricao, CONCAT(cidades.nome, " / ", cidades.uf) as text, count(imoveis.id) as qtde, cidades.link as link, cidades.uf as uf, cidades.uf as uf, cidades.portal as portal, cidades.libera as libera
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
    
    public function get_select2 ( $filtro )
    {
        $data['coluna'] = '	
                            cidades.link as id, CONCAT(cidades.nome, " / ", cidades.uf) as text
                        ';
    	$data['tabela'] = array(
                                array('nome' => 'cidades'),
                                array('nome' => 'imoveis',      'where' => 'imoveis.id_cidade = cidades.id', 'tipo' => 'INNER'),
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
        $data['coluna'] = '	
                            *
                        ';
    	$data['tabela'] = array(
                                array('nome' => 'cidades'),
                                );
    	$data['filtro'] = $filtro;
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['itens'];
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
    
    
    public function get_item( $id = '' )
    {
    	$data['coluna'] = '*';
    	$data['tabela'] = array(
                                array('nome' => 'cidades'),
                                );
    							
    	$data['filtro'] = 'cidades.id = '.$id;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'][0];
    }
    
    
}