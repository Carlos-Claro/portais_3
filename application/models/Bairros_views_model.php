<?php
/**
 */
class Bairros_views_Model extends MY_Model {
	
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
    }

    public function adicionar( $data = array() )
    {
        $this->db->insert('bairros_views', $data); 
        return $this->db->insert_id();
    }
    
    public function editar($data = array(), $filtro = FALSE)
    {
        $this->db->update('bairros_views', $data, $filtro);  
        return $this->db->affected_rows();
    }
    
    public function get_item_por_id( $id_bairro, $data_ = FALSE, $limit = 4, $id_cidade = 1 )
    {
        $data['coluna'] = '*';
    	$data['tabela'] = array(
                                array('nome' => 'bairros_views'),
                                );
        $filtro[] = 'link_bairro = "'.$id_bairro.'"';
        $filtro[] = 'id_cidade = "'.$id_cidade.'"';
        if ( $data_ )
        {
            if (is_array($data_) )
            {
                $filtro[] = 'data BETWEEN "'.$data_[0].'" AND "'.$data_[1].'"';
            }
            else
            {
                $filtro[] = 'data = "'.( ($data_ === TRUE) ? date('Y-m-d') : $data_ ).'"';
            }
        }
    	$data['filtro'] = $filtro;
    	$data['col'] = 'views';
    	$data['ordem'] = 'DESC';
        $data['off_set'] = 0;
        $data['qtde_itens'] = $limit;
    	$retorno = $this->get_itens_($data);
    	return $retorno;
    }
    
    public function get_ranking( $id_cidade )
    {
        $data['coluna'] = 'bairros.link as id, bairros.nome as descricao';
    	$data['tabela'] = array(
                                array('nome' => 'bairros_views'),
                                array('nome' => 'bairros', 'where' => 'bairros.link = bairros_views.link_bairro', 'tipo' => 'INNER')
                                );
        $filtro[] = 'bairros.cidade = '.$id_cidade;
        $filtro[] = 'data BETWEEN "'.date('Y-m-d',strtotime("-1 week")).'" AND "'.date('Y-m-d').'"';
    	$data['filtro'] = $filtro;
    	$data['col'] = 'views';
    	$data['ordem'] = 'DESC';
        $data['off_set'] = 0;
        $data['qtde_itens'] = 6;
        $data['group'] = 'bairros.id';
    	$retorno = $this->get_itens_($data);
        
        /*
        if (  $retorno['qtde'] < 4 )
        
        {
            $qtde = $retorno['qtde'] > 0 ? 4 - $retorno['qtde']  : 4;
            $data['coluna'] = 'bairros.link as id, bairros.nome as descricao';
            $data['tabela'] = array(
                                    array('nome' => 'bairros'),
                                    );
            $filtro_[] = 'bairros.cidade = '.$id_cidade;
            $data['filtro'] = $filtro_;
            $data['col'] = 'RAND()';
            $data['ordem'] = 'DESC';
            $data['off_set'] = '0';
            $data['qtde_itens'] = $qtde;
            $reto = $this->get_itens_($data);
            $ret = array_merge($reto['itens'],$retorno['itens']);
        }
        else
        {
        }
         * */
        
        $ret = $retorno['itens'];
        return $ret;
    }
    
    public function set_log( $id_bairro = NULL, $cidade = FALSE )
    {
        /*
        //var_dump($id_bairro, $cidade);
        if ( isset($id_bairro) )
        {
            if (is_array($id_bairro) )
            {
                foreach( $id_bairro as $id )
                {
                    $item = $this->get_item_por_id($id, TRUE, 1, $cidade );
                    if ( isset( $item['qtde'] ) && $item['qtde'] > 0 )
                    {
                        $data = array( 'link_bairro' => $id, 'data' => date('Y-m-d'), 'views' => ( $item['itens'][0]->views + 1 ) );
                        $filtro = array('link_bairro' => $id, 'data' => date('Y-m-d'), 'id_cidade' => $cidade);
                        $this->editar($data, $filtro);
                    }
                    else 
                    {
                        $data = array( 'link_bairro' => $id, 'data' => date('Y-m-d'), 'views' => 1 , 'id_cidade' => $cidade );
                        $this->adicionar( $data );
                    }
                }
            }
            else
            {
                
                $item = $this->get_item_por_id($id_bairro, TRUE, 1, $cidade);
                if ( isset( $item['qtde'] ) && $item['qtde'] > 0 )
                {
                    $data = array( 'link_bairro' => $id_bairro, 'data' => date('Y-m-d'), 'views' => ( $item['itens'][0]->views + 1 ) );
                    $filtro = array('link_bairro' => $id_bairro, 'data' => date('Y-m-d'), 'id_cidade' => $cidade);
                    $this->editar($data, $filtro);
                }
                else 
                {
                    $data = array( 'link_bairro' => $id_bairro, 'data' => date('Y-m-d'), 'views' => 1, 'id_cidade' => $cidade );
                    $this->adicionar( $data );
                }
            }
        }
         * 
         */
        
    }
    
    
    
   
    
}