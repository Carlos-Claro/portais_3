<?php
class Noticias_Model extends MY_Model {
	
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();		
    }

    public function editar($data = array(),$filtro = array())
    {
        $this->db->update('noticias', $data, $filtro);  
        return $this->db->affected_rows();
    }
    
    public function get_select( $filtro = array() )
    {
    	$data['coluna'] = 'noticias.id as id, noticias.titulo as descricao ';
    	$data['tabela'] = array(
                                array('nome' => 'noticias')
                                );
    							
    	$data['filtro'] = $filtro;
    	$data['col'] = 'id';
    	$data['ordem'] = 'ASC';
    	$retorno = $this->get_itens_($data);
    	return $retorno['itens'];
    }

    public function get_item_por_id ( $id )
    {
        $data['coluna'] = '*';
    	$data['tabela'] = array(
                                array('nome' => 'noticias')
                                );
    							
    	$data['filtro'] = 'noticias.id = '.$id ;
    	$data['col'] = 'id';
    	$data['ordem'] = 'ASC';
    	$retorno = $this->get_itens_($data);
        
            if ( isset($retorno['itens'][0]->views) )
            {
                $d = array( 'noticias.views' => ( $retorno['itens'][0]->views + 1 ) );
                $f = array( 'noticias.id' => $retorno['itens'][0]->id );
                $this->editar($d, $f);
            }
            
            
        
    	return isset($retorno['itens'][0]) ? $retorno['itens'][0] : NULL;
    }
    
    public function get_total_itens( $filtro = array() )
    {
    	$data['coluna'] = '	
                            count(noticias.id) as qtde
                        ';
    	$data['tabela'] = array(
                                array('nome' => 'noticias'),
                                );
    	$data['filtro'] = $filtro;
    	
    	$retorno = $this->get_itens_($data);
    	
    	return isset($retorno['itens'][0]->qtde) ? $retorno['itens'][0]->qtde : 0;
    }
    
    public function get_itens( $filtro = array(), $coluna = 'data', $ordem = 'DESC', $off_set = 0, $quantidade = N_ITENS )
    {
    	$data['coluna'] = '	
                            noticias.id as id,
                            noticias.data as data,
                            noticias.titulo as titulo,
                            noticias.texto as descricao,
                            noticias.icone as icone,
                            noticias.foto as foto,
                            noticias.views as views
                           ';
    	$data['tabela'] = array(
                                array('nome' => 'noticias'),
                                );
    	$data['filtro'] = $filtro;
    	if ( isset($off_set) )
    	{
    		$data['off_set'] = $off_set;
                $data['qtde_itens'] = $quantidade;
    	}
    	$data['col'] = $coluna;
    	$data['ordem'] = $ordem; 
    	$retorno = $this->get_itens_($data); 
    	 /*
        foreach ( $retorno['itens'] as $item )
        {
            if ( isset($item->views) )
            {
                $d = array( 'noticias.views' => ( $item->views + 1 ) );
                $f = array( 'noticias.id' => $item->id );
                $this->editar($d, $f);
            }
            
            
        }
        */
    	return $retorno;
    }
    
    private function _set_log ( $itens = NULL )
    {
        if ( isset($itens) )
        {
            foreach($itens as $item)
            {
                $this->get_view($item->id_campanha);
            }
        }
    }
    
    
    
    
    
}