<?php
class Publicidade_Model extends MY_Model {
	
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
    }
	
    public function adicionar_stats( $data = array() )
    {
    	$this->db->insert('publicidade_stats', $data); 
        return $this->db->insert_id();
    }
    
    public function editar_stats($data = array(),$filtro = array())
    {
        $this->db->update('publicidade_stats', $data, $filtro);  
        return $this->db->affected_rows();
    }
    
    public function get_select( $filtro = array() )
    {
    	$data['coluna'] = 'publicidade_areas.id as id, publicidade_areas.area as descricao ';
    	$data['tabela'] = array(
                                array('nome' => 'publicidade_areas')
                                );
    							
    	$data['filtro'] = $filtro;
    	$data['col'] = 'id';
    	$data['ordem'] = 'ASC';
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }

    public function get_item_por_id ( $id )
    {
        $data['coluna'] = '
                            publicidade_areas.id as id_servico, 
                            publicidade_areas.posicao as posicao, 
                            publicidade_areas.quantia as qtde, 
                            publicidade_areas.largura as width, 
                            publicidade_areas.altura as height ';
    	$data['tabela'] = array(
                                array('nome' => 'publicidade_areas')
                                );
    							
    	$data['filtro'] = 'publicidade_areas.id = '.$id ;
    	$data['col'] = 'id';
    	$data['ordem'] = 'ASC';
    	$retorno = $this->get_itens_($data);
        if ( isset($retorno['itens'][0]) )
        {
            $ret = $this->get_item_por_servico( $retorno['itens'][0] );
                $completa = 0;
                if ( isset($ret) && isset($item->qtde) && count($ret) < $item->qtde )
                {
                    $completa = $item->qtde - count($ret);
                }
                for( $a = 0; $a < $completa; $a++ )
                {
                    $codigo = $this->get_item_coringa( $item->posicao );
                    if ( isset($codigo) )
                    {
                        $ret[] = $codigo;
                    }
                }
            
            if ( isset($_GET['carlos_publicidade']) )
            {
            
                var_dump($retorno['itens'],$ret);
            
                
            }
            
        }
    	return $ret;
    }
    
    public function get_item_por_servico ( $data_v )
    {
        $data['coluna'] = '
                            empresas.id as id_empresa,
                            empresas.empresa_email as email, 
                            empresas.pagina_nome_inicial as nome,
                            publicidade_campanhas.id as id_campanha,
                            publicidade_campanhas.banner as banner,
                            publicidade_campanhas.banner_alternativo as banner_alternativo,
                            publicidade_campanhas.diferente_interno as diferente_interno,
                            publicidade_campanhas.url as url,
                            publicidade_campanhas.ext as ext, 
                            '.(isset($data_v->width) ? $data_v->width : 0).' as width,
                            '.(isset($data_v->height) ? $data_v->height : 0).' as height
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'publicidade_campanhas'),
                                array('nome' => 'empresas', 'where' => 'publicidade_campanhas.id_empresa = empresas.id', 'tipo' => 'INNER')
                                );
        if ( isset($data_v->id_servico) )
        {
            $filtro[] = 'empresas.bloqueado = 0';
            //$filtro[] = array( 'tipo' => 'where',  'campo' => 'empresas.servicos_pagina_inicio <',    'valor' => time() );
            //$filtro[] = array( 'tipo' => 'where',  'campo' => 'empresas.servicos_pagina_termino >',       'valor' => time() );
            $filtro[] = array( 'tipo' => 'where',  'campo' => 'publicidade_campanhas.inicio <',    'valor' => time() );
            $filtro[] = array( 'tipo' => 'where',  'campo' => 'publicidade_campanhas.termino >',       'valor' => time() );
            $filtro[] = array( 'tipo' => 'where',  'campo' => 'publicidade_campanhas.id_servico',       'valor' => $data_v->id_servico );
        }
        else
        {
            $filtro[] = array( 'tipo' => 'where',  'campo' => 'publicidade_campanhas.id',       'valor' => $data_v->id );
        }
        
    	$data['filtro'] = $filtro;
    	$data['col'] = 'publicidade_campanhas.id';
    	$data['ordem'] = 'RAND()';
        $data['off_set'] = 0;
        $data['qtde_itens'] = isset($data_v->qtde) ? $data_v->qtde : 1;
    	$retorno = $this->get_itens_($data);
        if ( $retorno['qtde'] > 0 )
        {
            $r = $retorno['itens'];
            //$this->_set_log($retorno['itens']);
        }
        else
        {
            $r = NULL;
        }
        return $r;
    }
    
    public function get_item_coringa( $local )
    {
        $codigos = array(
                        'Topo' => (object)array( 'id_empresa' => '6288', 'email' => 'comercial@pow.com.br', 'nome' => 'POW INTERNET', 'id_campanha' => '1', 
                                        'banner' => '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- Topo_Portais -->
<ins class="adsbygoogle"
     style="display:inline-block;width:728px;height:90px"
     data-ad-client="ca-pub-3231235740420919"
     data-ad-slot="8249254383"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>', 'url' => '#', 'ext' => 'googl', 'width' => '728', 'height' => '90' ),
                        'Lateral' => (object)array( 'id_empresa' => '6288', 'email' => 'comercial@pow.com.br', 'nome' => 'POW INTERNET', 'id_campanha' => '2', 
                                        'banner' => '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- Lateral_1 -->
<ins class="adsbygoogle"
     style="display:inline-block;width:200px;height:200px"
     data-ad-client="ca-pub-3231235740420919"
     data-ad-slot="3155931181"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>', 'url' => '#', 'ext' => 'googl', 'width' => '140', 'height' => '140' ),
                        'Meio da página 1' => (object)array( 'id_empresa' => '6288', 'email' => 'comercial@pow.com.br', 'nome' => 'POW INTERNET', 'id_campanha' => '3', 
                                        'banner' => '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- Meio Pagina 1 Portais -->
<ins class="adsbygoogle"
     style="display:inline-block;width:728px;height:90px"
     data-ad-client="ca-pub-3231235740420919"
     data-ad-slot="9725987588"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>', 'url' => '#', 'ext' => 'googl', 'width' => '728', 'height' => '90' ),
                        'Meio da página 2' => (object)array( 'id_empresa' => '6288', 'email' => 'comercial@pow.com.br', 'nome' => 'POW INTERNET', 'id_campanha' => '3', 
                                        'banner' => '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- Meio de pagina 2 Portais -->
<ins class="adsbygoogle"
     style="display:inline-block;width:728px;height:90px"
     data-ad-client="ca-pub-3231235740420919"
     data-ad-slot="6632920389"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>', 'url' => '#', 'ext' => 'googl', 'width' => '728', 'height' => '90' ),
                        'Rodapé' => (object)array( 'id_empresa' => '6288', 'email' => 'comercial@pow.com.br', 'nome' => 'POW INTERNET', 'id_campanha' => '4', 
                                        'banner' => '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- Rodape_portais -->
<ins class="adsbygoogle"
     style="display:inline-block;width:728px;height:90px"
     data-ad-client="ca-pub-3231235740420919"
     data-ad-slot="2063119984"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>', 'url' => '#', 'ext' => 'googl', 'width' => '728', 'height' => '90' ),
                        );
        return $codigos[$local];
    }
    
    public function _set_log ( $itens = NULL )
    {
        if ( isset($itens) )
        {
            foreach($itens as $item)
            {
                $this->get_view($item->id_campanha);
            }
        }
    }
    
    public function get_view ( $item, $robot = FALSE )
    {
        $data['coluna'] = '
                            publicidade_stats.id as id,
                            publicidade_stats.views as views,
                            publicidade_stats.robot as robot
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'publicidade_stats'),
                                );
        $data['filtro'][] = 'publicidade_stats.id_campanha = '.$item;
        $data['filtro'][] = 'publicidade_stats.ano = '.date('Y');
        $data['filtro'][] = 'publicidade_stats.mes = '.date('n');
        $retorno = $this->get_itens_($data);
        if ( isset($retorno['qtde']) && $retorno['qtde'] > 0 )
        {
            //update
            $filtro = array( 'id' => $retorno['itens'][0]->id );
            if ( $robot )
            {
                $data = array( 'robot' => ( $retorno['itens'][0]->robot + 1 ) );
            }
            else
            {
                $data = array( 'views' => ( $retorno['itens'][0]->views + 1 ) );
            }
            $r = $this->editar_stats($data, $filtro);
            
        }
        else
        {
            //insert
            if ( $robot )
            {
                $data = array('id_campanha' => $item, 'ano' => date('Y'), 'mes' => date('n'), 'robot' => 1);
            }
            else
            {
                $data = array('id_campanha' => $item, 'ano' => date('Y'), 'mes' => date('n'), 'views' => 1);
            }
            $r = $this->adicionar_stats($data);
        }
        return $r;
    }
    
    public function get_click ( $item )
    {
        $data['coluna'] = '
                            publicidade_stats.id as id,
                            publicidade_stats.clicks as clicks
                            ';
    	$data['tabela'] = array(
                                array('nome' => 'publicidade_stats'),
                                );
        $data['filtro'][] = 'publicidade_stats.id_campanha = '.$item;
        $data['filtro'][] = 'publicidade_stats.ano = '.date('Y');
        $data['filtro'][] = 'publicidade_stats.mes = '.date('n');
        $retorno = $this->get_itens_($data);
        if ( isset($retorno['qtde']) && $retorno['qtde'] > 0 )
        {
            //update
            $filtro = array( 'id' => $retorno['itens'][0]->id );
            $data = array( 'clicks' => ( $retorno['itens'][0]->clicks + 1 ) );
            $r = $this->editar_stats($data, $filtro);
            
        }
        else
        {
            //insert
            $data = array('id_campanha' => $item, 'ano' => date('Y'), 'mes' => date('n'), 'clicks' => 1);
            $r = $this->adicionar_stats($data);
        }
        return $r;
    }
    
    
    
}