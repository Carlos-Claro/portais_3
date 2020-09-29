<?php
class Imoveis_Model extends MY_Model {
	
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
    }
    
    public function adicionar( $data = array() )
    {
    	$this->db->insert('imoveis', $data); 
        return $this->db->insert_id();
    }
    
    public function editar($data = array(),$filtro = array())
    {
        $this->db->update('imoveis', $data, $filtro);  
        return $this->db->affected_rows();
    }
    
    public function adiciona_logs( $data = array() )
    {
    	$this->db->insert('logs_insert', $data); 
	return $this->db->insert_id();
    }
    
    public function editar_logs($data = array(),$filtro = array())
    {
        $this->db->update('logs', $data, $filtro);  
        return $this->db->affected_rows();
    }
    
    public function adiciona_destaques_views( $data = array() )
    {
    	$this->db->insert('imoveis_destaques_views', $data); 
	return $this->db->insert_id();
    }
    
    public function editar_destaques_views($data = array(),$filtro = array())
    {
        $this->db->update('imoveis_destaques_views', $data, $filtro);  
        return $this->db->affected_rows();
    }
    

    public function excluir($filtro)
    {
        $this->db->delete('imoveis',$filtro);
        return $this->db->affected_rows();
    }
    
    public function get_itens_estatistica( $cidade, $negocio )
    {
        $data['coluna'] = ' imoveis.id_tipo, 
                            imoveis_tipos.nome as nome_tipo,
                            imoveis_tipos.link as link_tipo,
                            imoveis.bairro_combo, 
                            bairros.nome as nome_bairro,
                            bairros.link as link_bairro,
                            imoveis.id_cidade, 
                            AVG(imoveis.preco_'.$negocio.') as '.$negocio.', 
                            count(imoveis.id) as nume_i_'.$negocio;
        $data['tabela'] = array(
                                array('nome' => 'imoveis'),
                                array('nome' => 'empresas',         'where' => 'imoveis.id_empresa = empresas.id AND empresas.pagina_visivel = 1', 'tipo' => 'INNER'),
                                array('nome' => 'bairros', 'where' => 'imoveis.bairro_combo = bairros.id', 'tipo' => 'INNER'),
                                array('nome' => 'imoveis_tipos',    'where' => 'imoveis.id_tipo = imoveis_tipos.id', 'tipo' => 'LEFT'),
                                );
        $data['filtro'][] = 'imoveis.id_tipo > 0';
        $data['filtro'][] = 'imoveis.'.$negocio.' = 1';
        $data['filtro'][] = 'imoveis.vendido = 0';
        $data['filtro'][] = 'imoveis.locado = 0';
        $data['filtro'][] = 'imoveis.invisivel = 0';
        $data['filtro'][] = 'imoveis.reservaimovel = 0';
        $data['filtro'][] = 'imoveis.id_cidade = '.$cidade;
        $data['filtro'][] = 'imoveis.preco_venda > 0 ';
        $data['filtro'][] = '(imoveis.vencimento = "0000-00-00" OR (imoveis.vencimento <> "0000-00-00" AND imoveis.vencimento >= "'.date('Y-m-d').'") )';
        $data['filtro'][] = array( 'tipo' => 'where',  'campo' => 'empresas.servicos_pagina_inicio <',    'valor' => time() );
        $data['filtro'][] = array( 'tipo' => 'where',  'campo' => 'empresas.servicos_pagina_termino >',       'valor' => time() );
        $data['filtro'][] = 'empresas.bloqueado = 0 ';

        $data['group'] = 'imoveis.id_tipo, imoveis.id_cidade, imoveis.bairro_combo';
        $data['col'] = 'imoveis_tipos.nome, imoveis.bairro_combo';
    	$data['ordem'] = 'ASC'; 
        $itens = $this->get_itens_($data);
        $retorno = [];
        if ( $itens['qtde'] > 0 )
        {
            foreach($itens['itens'] as $item)
            {
                $retorno[$item->nome_tipo][$item->nome_bairro] = $item;
                $retorno[$item->nome_tipo][$item->nome_bairro]->{$negocio} = round($item->{$negocio},($negocio == 'venda' ? -4 : -2));
            }
        }
        return $retorno;
        
    }
    
    public function get_estatistica ($cidade)
    {
        $itens_venda = $this->get_itens_estatistica($cidade, 'venda');
        $itens_locacao = $this->get_itens_estatistica($cidade, 'locacao');
        $retorno = ['Venda' => $itens_venda, 'Alugar' => $itens_locacao];
//        foreach( $itens_venda as $c_b => $t_v )
//        {
//            foreach($t_v as $c_t_v => $valor_v)
//            {
//                $retorno[$c_b][$c_t_v] = $valor_v;
//                $retorno[$c_b][$c_t_v]->venda = round($valor_v->venda,-4);
//                if ( isset($itens_locacao[$c_b][$c_t_v]) )
//                {
//                    $retorno[$c_b][$c_t_v]->locacao = round($itens_locacao[$c_b][$c_t_v]->locacao,-2);
//                    unset($itens_locacao[$c_b][$c_t_v]);
//                }
//                else
//                {
//                    $retorno[$c_b][$c_t_v]->locacao = 0;
//                }
//            }
//        }
//        if( isset($itens_locacao))
//        {
//            foreach( $itens_locacao as $c_l => $v_l)
//            {
//                foreach( $v_l as $c_t_l => $valor_l)
//                {
//                    $retorno[$c_l][$c_t_l] = $valor_l;
//                    $retorno[$c_l][$c_t_l]->locacao = round($valor_l->locacao,-2);
//                    $retorno[$c_l][$c_t_l]->venda = 0;
//                    
//                }
//            }
//            
//        }
        return $retorno;
        
    }
    
    public function get_item_por_id( $id = '', $destaque = FALSE )
    {
        $this->load->model('images_model');
    	$data['coluna'] = '
                            imoveis.id as id,
                            imoveis.id as _id,
                            imoveis.clics as clics,
                            imoveis.referencia as referencia,
                            imoveis.nome as nome,
                            imoveis.data_atualizacao as data_atualizacao,
                            imoveis.descricao as descricao,
                            imoveis.id_tipo as id_tipo,
                            imoveis_tipos.nome as tipo,
                            IF ( imoveis.comercial = 1, 
                                    "Comercial",  
                                    IF ( imoveis.residencial = 1, "Residencial", "Lazer")
                                    ) as uso,


                            imoveis.id_empresa as id_empresa,
                            empresas.empresa_nome_fantasia as imobiliaria_nome,
                            empresas.nome_seo as imobiliaria_nome_seo,
                            empresas.empresa_telefone as imobiliaria_telefone,
                            empresas.pagina_logo_pequeno as logo,
                            end_empresa.logradouro as imobiliaria_logradouro,
                            end_empresa.bairro as imobiliaria_bairro,
                            end_empresa.cidade as imobiliaria_cidade,
                            empresas.empresa_numero as imobiliaria_numero,
                            empresas.empresa_email as empresa_email,
                            empresas.empresa_emaillocacao as locacao_email,
                            empresas.pagina_creci as creci,
                            IF ( empresas.mudou = 1, "http://www.pow.com.br/powsites/codEmpresa/imo/", "http://www.guiasjp.com/imoveis_imagens/" ) as mudou,
                            empresas.mudou as mudo,
                            IF( imoveis.latitude <> "", CONCAT( imoveis.latitude, ",", imoveis.longitude ), "" ) as mapa,
                            if ( imoveis.venda = 1, 
                                    "venda",  
                                    IF ( imoveis.locacao = 1, "locação", 
                                        IF ( imoveis.locacao_dia = 1, "locação temporada", NULL )
                                    )
                                ) as imovel_para, 
                            if ( imoveis.venda = 1, 
                                    "venda",  
                                    IF ( imoveis.locacao = 1, "locacao", 
                                        IF ( imoveis.locacao_dia = 1, "locacao_dia", NULL )
                                    )
                                ) as tipo_negocio, 
                            imoveis.venda as venda,
                            imoveis.locacao as locacao,
                            imoveis.locacao_dia as locacao_dia, 
                            imoveis.quartos as quartos,
                            imoveis.suites as suites,
                            imoveis.banheiros as banheiros,
                            imoveis.garagens as garagens,
                            imoveis.mobiliado as mobiliado,
                            imoveis.cobertura as cobertura,
                            imoveis.condominio as condominio,
                            imoveis.condominio_valor as condominio_valor,
                            imoveis.area_terreno as area_terreno,
                            imoveis.area as area, 
                            imoveis.area_util as area_util,
                            imoveis.preco_venda as preco_venda,
                            imoveis.preco_locacao as preco_locacao,
                            imoveis.preco_locacao_dia as preco_locacao_dia,
                            imoveis.id_cidade as id_cidade,
                            bairros.nome as bairro,
                            bairros.link as bairros_link,
                            imoveis.bairro as vila,
                            imoveis.cep as cep,
                            IF ( imoveis.id_logradouro > 0, logradouros.logradouro, imoveis.logradouro  ) as logradouro,
                            imoveis.numero as numero,
                            imoveis.comercial as comercial,
                            imoveis.residencial as residencial,
                            imoveis.lazer as lazer,
                            imoveis.video as video,
                            imoveis.banheiros as banheiros,
                            imoveis.mostramapa as mostramapa,
                            cidades.link as cidades_link, 
                            empresa_cidade.ddd as ddd, 
                            cidades.nome as cidade,
                            cidades.uf as uf,
                            estados.nome as estado,
                            cidades.link as cidade_link,
                            IF ( imoveis_corretor.recebe_email = 1, imoveis_corretor.email, "") as email_corretor,
                            imoveis_corretor.nome as nome_corretor,
                            imoveis_corretor.sms as sms_corretor,
                            imoveis_corretor.celular as celular_corretor,
                            hotsite_parametros.sms_quem as sms_quem,
                            empresas.servicos_sms_limite as sms_limite,
                            empresas.empresa_fone_sms as empresa_telefone_sms,
                            empresas.pagina_limite_ofertas as pagina_limite_ofertas,
                            imoveis_tipos.nome as imoveis_tipos_nome,
                            imoveis_tipos.link as imoveis_tipos_link
                            
                            '; 
    	$data['tabela'] = array(
                                array('nome' => 'imoveis'),
                                array('nome' => 'empresas',         'where' => 'imoveis.id_empresa = empresas.id ', 'tipo' => 'INNER'),
                                array('nome' => 'logradouros empresa_logradouro','where' => 'empresas.id_logradouro = empresa_logradouro.id', 'tipo' => 'LEFT'),
                                array('nome' => 'cidades empresa_cidade','where' => 'empresa_logradouro.id_cidade = empresa_cidade.id', 'tipo' => 'LEFT'),
                                array('nome' => 'imoveis_tipos',    'where' => 'imoveis.id_tipo = imoveis_tipos.id', 'tipo' => 'LEFT'),
                                array('nome' => 'bairros',          'where' => 'imoveis.bairro_combo = bairros.id', 'tipo' => 'LEFT'),
                                array('nome' => 'cidades',          'where' => 'cidades.id = imoveis.id_cidade', 'tipo' => 'LEFT'),
                                array('nome' => 'estados',          'where' => 'cidades.uf = estados.uf', 'tipo' => 'LEFT'),
                                array('nome' => 'logradouros',      'where' => 'imoveis.id_logradouro = logradouros.id', 'tipo' => 'LEFT'),
                                array('nome' => 'imoveis_corretor', 'where' => 'imoveis.id_corretor = imoveis_corretor.id', 'tipo' => 'LEFT'),
                                array('nome' => 'logradouros end_empresa','where' => 'empresas.id_logradouro = end_empresa.id', 'tipo' => 'LEFT'),
                                array('nome' => 'hotsite_parametros','where' => 'imoveis.id_empresa = hotsite_parametros.id_empresa', 'tipo' => 'LEFT'),
                                );
    							
    	$data['filtro'][] = 'imoveis.id = '.$id;
    	$data['filtro'][] = 'imoveis.invisivel = 0';
    	$item = $this->get_itens_($data);
        if ( $destaque )
        {
            if ( isset($item['itens'][0]->clics) )
            {
                /*
                 *  retirar edição por soma
                    $d = array( 'imoveis.clics' => ( $item['itens'][0]->clics + 1 ) );
                    $f = array( 'imoveis.id' => $item['itens'][0]->id );
                    $this->editar($d, $f);
                 * 
                 */
                $this->get_log($item['itens'][0]->id, ( ($destaque) ? $destaque : 36 ), 'clicks');
                //$this->get_log($item['itens'][0]->id_empresa, 22, 'views');
                $this->get_log($item['itens'][0]->id_empresa, 25, 'views');
                $this->get_log($item['itens'][0]->id_empresa, 64, 'views');
            }
        }
        if ( isset($item['itens'][0]) )
        {
            $retorno = $item['itens'][0];
            $images = $this->images_model->get_itens('imoveis_images.id_imovel = '.$retorno->id, 'imoveis_images.ordem', 'ASC');
            $retorno->images = ( isset($images) && count($images) > 0 ) ? $images : FALSE;
        }
        else
        {
            $retorno = NULL;
        }
    	return $retorno;
    }
	
	
    public function get_item_por_id_empresa( $id_empresa = NULL )
    {
    	$data['coluna'] = ' * ';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis'),
                            );
    							
    	$data['filtro'] = isset($id_empresa) ? 'imoveis.id_empresa = '.$id_empresa : 'imoveis.id_empresa = 1';
    	$retorno = $this->get_itens_($data);
    	return isset($retorno['itens']) ? $retorno['itens'] : NULL;
    }
    
    public function get_select( $filtro = array() )
    {
    	$data['coluna'] = 'imoveis.id as id, imoveis.nome as descricao ';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis'),
                            );
    							
    	$data['filtro'] = $filtro;
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }
    
    public function get_total_itens( $filtro = array() )
    {
    	$data['coluna'] = '	
                            count(imoveis.id) as qtde
                        ';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis'),
                                array('nome' => 'empresas',	'where' => 'imoveis.id_empresa = empresas.id AND empresas.pagina_visivel = 1', 'tipo' => 'INNER'),
                                array('nome' => 'imoveis_tipos','where' => 'imoveis.id_tipo = imoveis_tipos.id', 'tipo' => 'LEFT'),
                                array('nome' => 'bairros',	'where' => 'imoveis.bairro_combo = bairros.id', 'tipo' => 'LEFT'),
                                array('nome' => 'logradouros',	'where' => 'imoveis.id_logradouro = logradouros.id', 'tipo' => 'LEFT'),
                                array('nome' => 'cidades',      'where' => 'cidades.id = imoveis.id_cidade')
                                );
    	$data['filtro'] = $filtro;
    	
    	$retorno = $this->get_itens_($data);
    	
    	return isset($retorno['itens'][0]->qtde) ? $retorno['itens'][0]->qtde : 0;
    }
    
    public function get_item_por_modalidade ( $filtro )
    {
        $data['coluna'] = '	
                            count(imoveis.id) as qtde
                        ';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis'),
                                array('nome' => 'empresas',	'where' => 'imoveis.id_empresa = empresas.id AND empresas.pagina_visivel = 1', 'tipo' => 'INNER'),
                                array('nome' => 'imoveis_tipos','where' => 'imoveis.id_tipo = imoveis_tipos.id', 'tipo' => 'LEFT'),
                                array('nome' => 'bairros',	'where' => 'imoveis.bairro_combo = bairros.id', 'tipo' => 'LEFT'),
                                array('nome' => 'cidades',      'where' => 'cidades.id = imoveis.id_cidade') 
            
                                );
    	$data['filtro'] = $filtro;
    	
    	$retorno = $this->get_itens_($data);
    	
    	return isset($retorno['itens'][0]->qtde) ? $retorno['itens'][0]->qtde : 0 ;
    }
    
    public function get_imoveis_por_cidade( $link = 'curitiba-pr' )
    {
    	$data['coluna'] = '	
                            imoveis.id as id_imovel, 
                            imoveis.id as _id, 
                            imoveis.nome as nome,
                            IF ( imoveis.venda = 1, "venda" , IF ( imoveis.locacao = 1 , "locacao", "locacao_dia" )   ) as tipo,
                            bairros.link as bairro,
                            imoveis_tipos.link as tipo_imovel,
                            cidades.link as cidade,
                            imoveis.data_atualizacao as atualizacao
                           ';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis'),
                                array('nome' => 'empresas',	'where' => 'imoveis.id_empresa = empresas.id AND empresas.pagina_visivel = 1', 'tipo' => 'INNER'),
                                array('nome' => 'imoveis_tipos','where' => 'imoveis.id_tipo = imoveis_tipos.id', 'tipo' => 'LEFT'),
                                array('nome' => 'bairros',	'where' => 'imoveis.bairro_combo = bairros.id', 'tipo' => 'LEFT'),
                                array('nome' => 'cidades',      'where' => 'cidades.id = imoveis.id_cidade')
                                );
    	$data['filtro'] = 'cidades.link = "'.$link.'"';
    	 
    	$retorno = $this->get_itens_($data);    
        
    	return $retorno['itens'];
    }
    
    public function get_itens( $filtro = array(), $coluna = 'ordem_rad', $ordem = 'DESC', $off_set = 0, $quantidade = N_ITENS, $destaque = FALSE )
    {
        /**
         * 
         */
    	$data['coluna'] = '	
                            imoveis.id as id_imovel, 
                            imoveis.id as _id, 
                            imoveis.nome as nome,
                            IF ( imoveis.preco_venda > 0, imoveis.preco_venda , IF ( imoveis.preco_locacao > 0 , imoveis.preco_locacao, imoveis.preco_locacao_dia )   ) as preco,
                            imoveis.preco_venda as preco_venda,
                            imoveis.preco_locacao as preco_locacao,
                            imoveis.preco_locacao_dia as preco_locacao_dia,
                            imoveis.logradouro as logradouro,
                            IF( imoveis.video, 1, 0 ) as video,
                            imoveis.condominio as terreno,
                            imoveis.quartos as quartos,
                            imoveis.garagens as garagens,
                            imoveis.banheiros as banheiros,
                            imoveis.area as area, 
                            imoveis.area_terreno as area_terreno,
                            imoveis.area_util as area_util,
                            empresas.mudou as mudou,
                            IF ( imoveis.bairro <> bairros.nome, imoveis.bairro, "")  as vila,
                            bairros.nome as bairro,
                            bairros.link as bairros_link,
                            imoveis_tipos.nome as imoveis_tipos_titulo,
                            imoveis_tipos.english as imoveis_tipos_english,
                            imoveis_tipos.link as imoveis_tipos_link,
                            cidades.link as cidades_link,
                            IF ( imoveis.venda = 1, "venda" , IF ( imoveis.locacao = 1 , "locacao", "locacao_dia" )   ) as tipo,
                            imoveis.venda as tipo_venda,
                            imoveis.locacao as tipo_locacao,
                            imoveis.locacao_dia as tipo_locacao_dia,
                            empresas.id as id_empresa,
                            imoveis.views as views,
                            imoveis.id_cidade as imovel_id_cidade,
                            bairros.cidade as bairro_cidade,
                            cidades.id as cidades_id,
                            cidades.nome as cidade_nome,
                            cidades.uf as uf,
                            imoveis.bairro_combo as bairro_combo,
                            empresas.empresa_nome_fantasia as nome_empresa,
                            empresas.nome_seo as imobiliaria_nome_seo,
                            imoveis.longitude as longitude,
                            imoveis.latitude as latitude,
                            logradouros.logradouro as logradouro_,
                            imoveis.descricao as descricao,
                            imoveis.referencia as referencia, 
                           ';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis'),
                                array('nome' => 'empresas',	'where' => 'imoveis.id_empresa = empresas.id AND empresas.pagina_visivel = 1', 'tipo' => 'INNER'),
                                array('nome' => 'imoveis_tipos','where' => 'imoveis.id_tipo = imoveis_tipos.id', 'tipo' => 'LEFT'),
                                array('nome' => 'bairros',	'where' => 'imoveis.bairro_combo = bairros.id', 'tipo' => 'LEFT'),
                                array('nome' => 'logradouros',	'where' => 'imoveis.id_logradouro = logradouros.id', 'tipo' => 'LEFT'),
                                array('nome' => 'cidades',      'where' => 'cidades.id = imoveis.id_cidade'),
                                );
        $this->load->model('images_model');
    	$data['filtro'] = $filtro;
        if ( $coluna == 'preco_venda' )
        {
            $data['filtro'][] = '( imoveis.preco_venda > 0 )';
        }
        if ( $coluna == 'preco_locacao' )
        {
            $data['filtro'][] = '( imoveis.preco_locacao > 0 )';
        }
        if ( $coluna == 'area_util' )
        {
            $data['filtro'][] = '( imoveis.area_util > 1 )';
        }
        if ( $coluna == 'bairros_link' )
        {
            $data['filtro'][] = '( imoveis.bairro_combo > 0 )';
        }
        $data['off_set'] = isset($off_set) ? $off_set : 0;
        $data['qtde_itens'] = $quantidade;
    	$data['col'] = $coluna;
    	$data['ordem'] = $ordem; 
    	$data['group'] = 'imoveis.id'; 
    	$retorno_db = $this->get_itens_($data);
        $retorno = array('itens' => array(), 'qtde' => 0 );
        if ( isset($retorno_db['itens']) && $retorno_db['qtde'] > 0 )
        {
            foreach ( $retorno_db['itens'] as $chave => $item )
            {
                $this->get_log($item->id_imovel, ( ($destaque) ? $destaque : 36 ), 'views');
                $retorno['itens'][$chave] = $item;
                $retorno['itens'][$chave]->images = $this->images_model->get_itens('imoveis_images.id_imovel = '.$item->id_imovel, 'imoveis_images.ordem', 'ASC', 0, 5);
                //var_dump($retorno['itens'][$chave]->images);
            }
        }
    	return $retorno;
    }
    
    	public function get_itens_por_cidade_filtro( $cidade_link = 'curitiba_pr', $group = 'bairro', $filtro = array(), $limit = 16, $filtro_proximidade = FALSE )
    	{
            $this->load->model('bairros_proximidade_model');
            if ( $filtro_proximidade )
            {
                $proximidade = $this->bairros_proximidade_model->get_itens($filtro_proximidade , $limit);
            }
    		$data['coluna'] = ' ';
    		$data['coluna'] .= ' COUNT( imoveis.id ) as qtde, ';
    		$data['coluna'] .= ' cidades.link as cidade, ';
    		$data['coluna'] .= ' IF ( imoveis.venda = 1, "venda", "locação" ) as negocio, ';
    		$data['coluna'] .= ' imoveis_tipos.link as tipo, ';
    		$data['coluna'] .= ' bairros.link as bairro, ';
    		switch ( $group )
    		{
    			case 'bairro':
    			case 'tipo':
		    		$data['coluna'] .= ' 0 as valor_min, ';
		    		$data['coluna'] .= ' 0  as valor_max ';
		    		$data['group'] = $group;
    				break;
    			case 'valor_max':
		    		$data['coluna'] .= ' 0 as valor_min, ';
		    		$data['coluna'] .= ' MAX( IF( imoveis.venda = 1, imoveis.preco_venda, imoveis.preco_locacao ) )  as valor_max, ';
		    		$data['coluna'] .= ' MAX( IF( imoveis.quartos > 0, imoveis.quartos, NULL ) )  as quartos,';
		    		$data['coluna'] .= ' MAX( IF( imoveis.garagens > 0, imoveis.garagens, NULL ) )  as vagas';
		    		$data['group'] = 'bairro';
    				break;
    			default:
		    		$data['group'] = $group;
		    		$data['coluna'] .= ' MIN( IF( imoveis.venda = 1, imoveis.preco_venda, imoveis.preco_locacao ) ) as valor_min, ';
		    		$data['coluna'] .= ' MAX( IF( imoveis.venda = 1, imoveis.preco_venda, imoveis.preco_locacao ) )  as valor_max ';
    				break;
    			
    		}
    		$data['tabela'] = array(
                                array('nome' => 'imoveis'),
                                array('nome' => 'empresas',	'where' => 'imoveis.id_empresa = empresas.id AND empresas.pagina_visivel = 1', 'tipo' => 'INNER'),
                                array('nome' => 'imoveis_tipos','where' => 'imoveis.id_tipo = imoveis_tipos.id', 'tipo' => 'LEFT'),
                                array('nome' => 'bairros',	'where' => 'imoveis.bairro_combo = bairros.id', 'tipo' => 'LEFT'),
                                array('nome' => 'logradouros',	'where' => 'imoveis.id_logradouro = logradouros.id', 'tipo' => 'LEFT'),
                                array('nome' => 'cidades',      'where' => 'cidades.id = imoveis.id_cidade')
                                );
    		if ( is_array($filtro) )
    		{
    			$data['filtro'] = $filtro;
    		}
    		else
    		{
    			$data['filtro'][] = $filtro;
    		}
    		$data['filtro'][] = 'cidades.link like "'.$cidade_link.'"';
    		$data['col'] = 'qtde';
    		$data['ordem'] = 'DESC';
    		$data['off_set'] = 0;
    		$data['qtde_itens'] = ( isset($proximidade) && count($proximidade) > 0 ) ? ( $limit - count($proximidade) ) : $limit;
    		$lista = $this->get_itens_($data);
                if ( isset($proximidade) && count($proximidade) > 0 )
                {
                    $retorno['itens'] = array_merge($proximidade, $lista['itens'] );
                    $retorno['qtde'] = $lista['qtde'] + count($proximidade) ;
                }
                else
                {
                    $retorno = $lista;
                }
                
    		return $retorno;
    	}
    	
    
    public function get_itens_bairro( $filtro = array() )
    {
    	$data['coluna'] = '
                            imoveis.id as id_imovel, 
                            imoveis.id as _id, 
                            imoveis.logradouro as logradouro,
                            imoveis.numero as numero,
                            imoveis.bairro as bairro,
                            imoveis.cep as cep,
                            imoveis.cidade as cidade_nome,
                            imoveis.id_cidade as id_cidade,
                            cidades.nome as cidade,
                            cidades.uf as estado,
                            imoveis.id_empresa as id_empresa,
                            imoveis.id_tipo as id_tipo,
                            imoveis_tipos.nome as tipo,
                            imoveis.invisivel as invisivel,
                            imoveis.data as data
                           ';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis'),
                                array('nome' => 'empresas',	'where' => 'imoveis.id_empresa = empresas.id AND empresas.pagina_visivel = 1', 'tipo' => 'LEFT'),
                                array('nome' => 'imoveis_tipos','where' => 'imoveis.id_tipo = imoveis_tipos.id', 'tipo' => 'LEFT'),
                                array('nome' => 'bairros',	'where' => 'imoveis.bairro_combo = bairros.id', 'tipo' => 'LEFT'),
                                array('nome' => 'cidades',      'where' => 'cidades.id = imoveis.id_cidade')
                                );
    	$data['filtro'] = $filtro;
    	
    	$retorno = $this->get_itens_($data);    
    	 
    	return $retorno;
    }

    
    public function get_log( $id_tabela, $id_local = 41, $tipo = 'views' )
    {
        $ld = array('id_tabela' => $id_tabela, 'id_local' => $id_local, $tipo => 1);
//        $resposta = $this->adiciona_logs($ld);
//        return ( $resposta > 0 ) ? TRUE : FALSE;
        
        /*
        $sel_log = array(
                        'coluna'    => 'logs.id as id, logs.'.$tipo.' as item',
                        'tabela'    => array( array('nome' => 'logs'),),
                        'filtro'     => 'logs.id_tabela = '.$id_tabela.' AND logs.mes = '.date('m').' AND logs.ano = '.date('Y').' AND logs.id_local = '.$id_local
                        );
        $log = $this->get_itens_($sel_log);
        if ( $log['qtde'] > 0 )
        {
            $la = array( 'logs.'.$tipo => ( $log['itens'][0]->item + 1 ) );
            $lf = array( 'logs.id' => $log['itens'][0]->id );
            $resposta = $this->editar_logs($la, $lf);
        }
        else
        {
            $ld = array('id_tabela' => $id_tabela, 'id_local' => $id_local, 'mes' => date('m'), 'ano' => date('Y'), $tipo => 1);
            $resposta = $this->adiciona_logs($ld);
        }
        return ( $resposta > 0 ) ? TRUE : FALSE;
         * 
         */
    }
    
    public function get_destaques_views( $id_imovel, $id_empresa, $tipo = 'views' )
    {
        $sel_log = array(
                        'coluna'    => 'imoveis_destaques_views.id as id, imoveis_destaques_views.'.$tipo.' as item',
                        'tabela'    => array( array('nome' => 'imoveis_destaques_views'),),
                        'filtro'     => 'imoveis_destaques_views.mes = '.date('m').' '
                                        . 'AND imoveis_destaques_views.ano = '.date('Y').' '
                                        . 'AND imoveis_destaques_views.site = "portal" '
                                        . 'AND imoveis_destaques_views.id_imovel = '.$id_imovel
                        );
        $log = $this->get_itens_($sel_log);
        if ( $log['qtde'] > 0 )
        {
            $la = array( 'imoveis_destaques_views.'.$tipo => ( $log['itens'][0]->item + 1 ) );
            $lf = array( 'imoveis_destaques_views.id' => $log['itens'][0]->id );
            $resposta = $this->editar_destaques_views($la, $lf);
        }
        else
        {
            $ld = array('id_empresa' => $id_empresa, 'id_imovel' => $id_imovel, 'mes' => date('m'), 'ano' => date('Y'), $tipo => 1, 'local' => 'venda', 'site' => 'portal' );
            $resposta = $this->adiciona_destaques_views($ld);
        }
        return ( $resposta > 0 ) ? TRUE : FALSE;
    }
    
    public function get_min_max_valor ( $filtro, $campo )
    {
        $data['coluna'] = '	
                            MIN(imoveis.'.$campo.') as min,
                            MAX(imoveis.'.$campo.') as max
                        ';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis'),
                                array('nome' => 'empresas',	'where' => 'imoveis.id_empresa = empresas.id AND empresas.pagina_visivel = 1', 'tipo' => 'INNER'),
                                array('nome' => 'imoveis_tipos','where' => 'imoveis.id_tipo = imoveis_tipos.id', 'tipo' => 'LEFT'),
                                array('nome' => 'bairros',	'where' => 'imoveis.bairro_combo = bairros.id', 'tipo' => 'LEFT'),
                                array('nome' => 'cidades',      'where' => 'cidades.id = imoveis.id_cidade')
            
                                );
    	$data['filtro'] = $filtro;
    	//$data['group'] = 'imoveis.id';
        
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['itens'][0];
    }
    
    public function get_valor ( $filtro, $campo )
    {
        $data['coluna'] = '	
                            MIN(imoveis.'.$campo.') as min,
                            MAX(imoveis.'.$campo.') as max
                        ';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis'),
                                array('nome' => 'empresas',	'where' => 'imoveis.id_empresa = empresas.id AND empresas.pagina_visivel = 1', 'tipo' => 'INNER'),
                                array('nome' => 'imoveis_tipos','where' => 'imoveis.id_tipo = imoveis_tipos.id', 'tipo' => 'LEFT'),
                                array('nome' => 'bairros',	'where' => 'imoveis.bairro_combo = bairros.id', 'tipo' => 'LEFT'),
                                array('nome' => 'cidades',      'where' => 'cidades.id = imoveis.id_cidade')
            
                                );
    	$data['filtro'] = $filtro;
    	//$data['group'] = 'imoveis.id';
        
    	$retorno = $this->get_itens_($data);
    	
    	return $retorno['itens'][0];
    }
    
    public function  get_itens_destaque_pago ( $filtro, $id_cidade, $qtde_total )
    {
        $data['coluna'] = '	
                            imoveis_destaques.id_empresa as id_empresa,
                            count(imoveis_destaques.id_empresa) as qtde
                           ';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis_destaques'),
                                );
    	$data['filtro'] = 'imoveis_destaques.cidade = '.$id_cidade;
    	$data['group'] = 'imoveis_destaques.id_empresa';
        $retorno = $this->get_itens_($data);
        //var_dump($retorno);die();
        $itens = array();
        if ( $retorno['qtde'] > 0 )
        {
            $a = 0;
            foreach( $retorno['itens'] as $item )
            {
                //var_dump($item);
                $item_ = $this->get_destaques_por_empresa($item->id_empresa, $item->qtde, $filtro);
                //echo $item->id_empresa.' - '.count($item_).' - '.$item->qtde.'<br>';
                $b = count($item_);
//var_dump($b);
                if ( $b == $item->qtde )
                {
                    //echo 'tem - '.$b.'<br>';
                    foreach ( $item_ as $i )
                    {
                        
                        $this->get_destaques_views($i->id_imovel, $i->id_empresa, 'views');
                        
                        $itens[] = $i;
                        
                    }
                }
                elseif( $b < $item->qtde )
                {
                    $c = $item->qtde - $b;
                    //echo 'nao tem - '.$c.'<br>';
                    $filtro_c = $filtro;
                    $filtro_c[] = 'imoveis.id_empresa = '.$item->id_empresa;
                    if ( count($item_) > 0 )
                    {
                        foreach ( $item_ as $i )
                        {
                            $this->get_destaques_views($i->id_imovel, $i->id_empresa, 'views');
                            $itens[] = $i;
                        }
                    }
                    $item_b = $this->get_itens($filtro_c, 'ordem_rad', 'random', 0, $c, 27);
                    if ( isset($item_b['qtde']) && $item_b['qtde'] > 0 )
                    {
                        foreach ( $item_b['itens'] as $i_ )
                        {
                            $this->get_destaques_views($i_->id_imovel, $i_->id_empresa, 'views');
                            $itens[] = $i_;
                        }
                    }
                    else
                    {
                        //echo 'nenhum item enconbtrado para o resultado.<br>';
                        //var_dump($item_b);
                    }
                }
                
                
                $a++;
            }
        }
        else
        {
            $itens = array();
        }
        //var_dump($itens);die();
        return $itens;
    }
    
    public function get_destaques_por_empresa ( $id_empresa, $qtde, $filtro )
    {
        $data['coluna'] = ' DISTINCT	
                            imoveis.id as id_imovel, 
                            imoveis.id as _id, 
                            IF (imoveis_destaques_lv.titulo = "", imoveis_images.titulo, imoveis_destaques_lv.titulo) as nome,
                            IF (imoveis_destaques_lv.foto = "", imoveis_images.arquivo, imoveis_destaques_lv.foto) as image,
                            IF ( imoveis.preco_venda > 0, imoveis.preco_venda , IF ( imoveis.preco_locacao > 0 , imoveis.preco_locacao, imoveis.preco_locacao_dia )   ) as preco,
                            imoveis_destaques_lv.descricao as descricao,
                            imoveis.preco_venda as preco_venda,
                            imoveis.preco_locacao as preco_locacao,
                            imoveis.preco_locacao_dia as preco_locacao_dia,
                            imoveis.logradouro as logradouro,
                            imoveis.video as video,
                            imoveis.quartos as quartos,
                            imoveis.garagens as garagens,
                            imoveis.banheiros as banheiros,
                            imoveis.condominio as terreno,
                            imoveis.area as area, 
                            imoveis.area_terreno as area_terreno,
                            imoveis.area_util as area_util,
                            empresas.mudou as mudou,
                            IF ( imoveis.bairro <> bairros.nome, imoveis.bairro, "")  as vila,
                            bairros.nome as bairro,
                            bairros.link as bairros_link,
                            imoveis_tipos.link as imoveis_tipos_link,
                            imoveis_tipos.nome as imoveis_tipos_titulo,
                            cidades.link as cidades_link,
                            IF ( imoveis.venda = 1, "venda" , IF ( imoveis.locacao = 1 , "locacao", "locacao_dia" )   ) as tipo,
                            imoveis.venda as tipo_venda,
                            imoveis.locacao as tipo_locacao,
                            imoveis.locacao_dia as tipo_locacao_dia,
                            empresas.id as id_empresa,
                            imoveis.views as views,
                            imoveis.id_cidade as imovel_id_cidade,
                            bairros.cidade as bairro_cidade,
                            cidades.id as cidades_id,
                            cidades.nome as cidade_nome,
                            cidades.uf as uf,
                            imoveis.bairro_combo as bairro_combo,
                            empresas.empresa_nome_fantasia as nome_empresa,
                            empresas.nome_seo as imobiliaria_nome_seo,
                            imoveis.longitude as longitude,
                            imoveis.latitude as latitude,
                            logradouros.logradouro as logradouro_,
                            imoveis.referencia as referencia 
                            
                           ';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis_destaques_lv'),
                                array('nome' => 'imoveis',      'where' => 'imoveis_destaques_lv.id_imovel = imoveis.id', 'tipo' => 'INNER'),
                                array('nome' => 'empresas',	'where' => 'imoveis.id_empresa = empresas.id AND empresas.pagina_visivel = 1', 'tipo' => 'INNER'),
                                array('nome' => 'imoveis_tipos','where' => 'imoveis.id_tipo = imoveis_tipos.id', 'tipo' => 'LEFT'),
                                array('nome' => 'bairros',	'where' => 'imoveis.bairro_combo = bairros.id', 'tipo' => 'LEFT'),
                                array('nome' => 'logradouros',	'where' => 'imoveis.id_logradouro = logradouros.id', 'tipo' => 'LEFT'),
                                array('nome' => 'imoveis_images','where' => 'imoveis.id = imoveis_images.id_imovel', 'tipo' => 'LEFT'),
                                array('nome' => 'cidades',      'where' => 'cidades.id = imoveis.id_cidade')
                                );
    	
        unset($filtro[0]);
        
        $data['filtro'] = $filtro;
        $data['filtro'][] = 'imoveis.id_empresa = '.$id_empresa;
        $data['filtro'][] = 'imoveis_destaques_lv.home = 1';
        $data['off_set'] = 0;
        $data['qtde_itens'] = $qtde;
    	$data['col'] = 'imoveis_destaques_lv.id';
    	$data['ordem'] = 'random'; 
    	$retorno = $this->get_itens_($data);
    	 
        foreach ( $retorno['itens'] as $item )
        {
            /*
            if ( isset($item->views) )
            {
                $d = array( 'imoveis.views' => ( $item->views + 1 ) );
                $f = array( 'imoveis.id' => $item->id_imovel );
                $this->editar($d, $f);
            }
             * 
             */
            $this->get_log($item->id_imovel, 27, 'views');
            
            
        }
        
    	return $retorno['itens'];
    }
    
    public function  get_itens_destaque_tipo ( $filtro = NULL )
    {
        switch ( $filtro['tipo_negocio'] )
        {
            case 'venda': 
                $negocio = 1;
                break;
            case 'locacao':
                $negocio = 2;
                break;
            case 'locacao_dia':
                $negocio = 3;
            default:
                $negocio = FALSE;
                
        }
        if ( isset( $filtro['tipo'] )  )
        {
            if (is_array($filtro['tipo']) )
            {
                $resp_t = '"'.implode('","', $filtro['tipo']).'"';
            }
            else 
            {
                $resp_t = $filtro['tipo'];
            }
            $data['filtro'][] = 'imoveis_tipos.link IN ('.$resp_t.')';
        }
        
        $data['coluna'] = '	
                            imoveis_dest_listagem.id_empresa as id_empresa
                           ';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis_dest_listagem'),
                                array('nome' => 'imoveis_tipos',            'where' => 'imoveis_tipos.id = imoveis_dest_listagem.id_tipo', 'tipo' => 'INNER'),
                                array('nome' => 'empresas',            'where' => 'empresas.id = imoveis_dest_listagem.id_empresa', 'tipo' => 'INNER'),
                                array('nome' => 'cidades',            'where' => 'imoveis_dest_listagem.id_cidade = cidades.id', 'tipo' => 'INNER'),
                                );
    	$data['filtro'][] = 'cidades.link like "'.$filtro['cidade'].'"';
        $data['filtro'][] = 'imoveis_dest_listagem.data_ini < '.time();
        $data['filtro'][] = 'imoveis_dest_listagem.data_fim > '.time();
        $data['filtro'][] = 'empresas.bloqueado = 0 ';
        if ( $negocio  )
        {
            $data['filtro'][] = 'imoveis_dest_listagem.negocio = '.$negocio;
        }
        $data['col'] =  'imoveis_dest_listagem.data_ini';
        $data['ordem'] = 'ASC';
        $data['group'] = 'empresas.id';
        $data_resposta = $this->get_itens_($data);    
        
        return $data_resposta['itens'];
    }
    
    public function  get_itens_destaque_bairro ( $filtro = NULL )
    {
        switch ( $filtro['tipo_negocio'] )
        {
            case 'venda': 
                $negocio = 1;
                break;
            case 'locacao':
                $negocio = 2;
                break;
            case 'locacao_dia':
                $negocio = 3;
            default:
                $negocio = FALSE;
                
        }
        if ( isset( $filtro['tipo'] ) && ! empty($filtro['tipo'])  )
        {
            if (is_array($filtro['tipo']) )
            {
                $resp_t = '"'.implode('","', $filtro['tipo']).'"';
            }
            else 
            {
                $resp_t = $filtro['tipo'];
            }
            $data['filtro'][] = 'imoveis_tipos.link IN ('.$resp_t.')';
        }
        
        if ( isset( $filtro['bairro'] ) && ! empty($filtro['bairro'])  )
        {
            if (is_array($filtro['bairro']) )
            {
                $resp_t = '"'.implode('","', $filtro['bairro']).'"';
            }
            else 
            {
                $resp_t = $filtro['bairro'];
            }
            $data['filtro'][] = 'bairros.link IN ('.$resp_t.')';
        }
        
        $data['coluna'] = '	
                            imoveis_destaque_bairro.id_empresa as id_empresa
                           ';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis_destaque_bairro'),
                                array('nome' => 'bairros',            'where' => 'bairros.id = imoveis_destaque_bairro.id_bairro', 'tipo' => 'INNER'),
                                array('nome' => 'imoveis_tipos',            'where' => 'imoveis_tipos.id = imoveis_destaque_bairro.id_tipo', 'tipo' => 'INNER'),
                                array('nome' => 'empresas',            'where' => 'empresas.id = imoveis_destaque_bairro.id_empresa', 'tipo' => 'INNER'),
                                array('nome' => 'cidades',            'where' => 'imoveis_destaque_bairro.id_cidade = cidades.id', 'tipo' => 'INNER'),
                                );
    	$data['filtro'][] = 'cidades.link like "'.$filtro['cidade'].'"';
        $data['filtro'][] = 'imoveis_destaque_bairro.data_inicio <= "'.date('Y-m-d H:i').'"';
        $data['filtro'][] = 'imoveis_destaque_bairro.data_fim >= "'.date('Y-m-d H:i').'"';
        $data['filtro'][] = 'empresas.bloqueado = 0 ';
        if ( $negocio  )
        {
            $data['filtro'][] = 'imoveis_destaque_bairro.negocio = '.$negocio;
        }
        $data['col'] =  'imoveis_destaque_bairro.data_inicio';
        $data['ordem'] = 'ASC';
        $data['group'] = 'empresas.id';
        $data_resposta = $this->get_itens_($data);    
        
        return $data_resposta['itens'];
    }
    
    public function get_images_por_filtro( $filtro, $off_set = 0, $qtde = 5000 )
    {
        $data['coluna'] = '	
                            imoveis.id as id,
                            imoveis.id_empresa as id_empresa,
                            imoveis.foto1 as foto1,
                            imoveis.foto2 as foto2,
                            imoveis.foto3 as foto3,
                            imoveis.foto4 as foto4,
                            imoveis.foto5 as foto5,
                            imoveis.foto6 as foto6,
                            imoveis.foto7 as foto7,
                            imoveis.foto8 as foto8,
                            imoveis.foto9 as foto9,
                            imoveis.foto10 as foto10,
                            imoveis.foto11 as foto11,
                            imoveis.foto12 as foto12,
                            imoveis.fs1 as fs1,
                            imoveis.fs2 as fs2,
                            imoveis.fs3 as fs3,
                            imoveis.fs4 as fs4,
                            imoveis.fs5 as fs5,
                            imoveis.fs6 as fs6,
                            imoveis.fs7 as fs7,
                            imoveis.fs8 as fs8,
                            empresas.mudou as mudou,
                           ';
    	$data['tabela'] = array(
                                array('nome' => 'imoveis'),
                                array('nome' => 'empresas', 'where' => 'imoveis.id_empresa = empresas.id',  'tipo' => 'INNER'),
                                );
    	$data['filtro'] = $filtro;
        $data['col'] = 'imoveis.id';
        $data['ordem'] = 'ASC';
        $data['off_set'] = $off_set;
        $data['qtde_itens'] = $qtde;
        $retorno = $this->get_itens_($data);
        return $retorno;
    }
}
