<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Model extends CI_Model
{	
    public function __construct()	
    {		
        parent::__construct();	
        $this->load->database();
    }	
    public function get_itens_( $data, $debug = FALSE ) 	
    {		
        $query = $this->db->select($data['coluna'], FALSE);		
        $query->from( $data['tabela'][0]['nome'] );		
        unset( $data['tabela'][0] );		
        if ( isset($data['tabela']) && is_array($data['tabela']) )		
        {			
            foreach ( $data['tabela'] as $join )			
            {				
                $query->join($join['nome'], $join['where'], ( isset( $join['tipo'] ) ? $join['tipo'] : 'left' ) );			
            }		
            
        }		
        if ( isset($data['filtro']) )		
        {	
            
            if ( is_array($data['filtro']) )			
            {	
                
                foreach ( $data['filtro'] as $f )				
                {
                    
                    if ( is_array( $f ) )
                    {
                            $query->{$f['tipo']} ($f['campo'],$f['valor']);
                    }
                    else
                    {
                        $query->where( $f );
                    }
                    
                    
                }			
                
            }			
            else 			
            {				
                $query->where($data['filtro']);			
                
            }		
            
        }		
        if ( isset( $data['group']) )		
        {			
            $query->group_by($data['group']);		
            
        }		
        if ( isset($data['ordem']) )		
        {			
            $query->order_by($data['col'], $data['ordem']);		
            
        }		
        if ( isset($data['off_set']) )		
        {			
            $query->limit( isset($data['qtde_itens']) ? $data['qtde_itens'] : N_ITENS, $data['off_set'] );		
            
        }
        elseif ( isset($data['qtde_itens']) )
        {
            $query->limit( $data['qtde_itens'] );		
            
        }
        unset($data);		 
        $retorno['itens'] = $query->get()->result();		
        $retorno['qtde'] = count($retorno['itens']);
        if ( $debug )
        {
            echo $this->db->last_query();
        }
        return $retorno;	
        
    }
	

}