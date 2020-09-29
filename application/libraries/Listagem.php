<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 
 * 
 */

class Listagem 
{
	private $itens;
        private $tipo = NULL;
        private $theme = NULL;
        private $link = NULL;
        private $extra = NULL;
        private $link_compl = FALSE;
        private $lista = FALSE;
        private $lista_itens = FALSE;
        private $classe = NULL;
        private $qtde_total = NULL;
        /**
	 * 
	 * Contrutor da Classe
	 */
	public function __construct($config = FALSE) 
	{
		if ( $config )
		{
			$this->inicia($config);
		}
	}
	
	public function get_html()
	{
		$retorno = '';
                if ( ! $this->lista_itens )
                {
                    $retorno .= '<ul '.( ! $this->lista ? 'data-role="listview"' : '' ).' data-tipo="'.$this->tipo.'" class="'.(isset($this->classe) ? $this->classe : '').'" >';
                }
		$retorno .= ( ! $this->lista ) ? $this->_set_itens() : $this->_set_itens_produtos();
                if ( ! $this->lista_itens )
                {
                    $retorno .= '</ul>';
                    if ( isset( $this->qtde_total ) && $this->qtde_total > N_ITENS )
                    {
                        $retorno .= ($this->lista) ? '<button class="btn btn-large btn-danger carrega_mais span12" id="5" >+ Itens ( '.( ( $this->itens['qtde'] > N_ITENS) ? N_ITENS : $this->itens['qtde'] ).' de '.$this->qtde_total.' )</button>' : '';
                    }
                    
                }
		
		return $retorno;
	}
	
        private function _set_itens_produtos ()
        {
            $retorno = '';
            if ( isset( $this->itens ) && count( $this->itens['qtde'] ) > 0 )
            {
                foreach ( $this->itens['itens']  as $item )
                {
                    if ( isset( $_GET['usuario'] ) && $_GET['usuario'] = 'master' )
                    {
                        var_dump($item);
                    }
                    $retorno .= '<li class="thumbnail thumbnail-borderless">';
                    $retorno .= '<a class="ui-link" href="'.base_url().'index.php/imoveis/imovel/'.$item->id_imovel.'/'.$item->tipo.'/'.$item->imoveis_tipos_link.'/'.$item->cidades_link.'/'.$item->bairros_link.'" target="_blank">';
                    $retorno .= '<img src="';
                    if ( isset($item->image) && !empty($item->image) )
                    {
                        if ( strstr($item->image, 'http' ) )
                        {
                            $retorno .= $item->image;
                        }
                        else
                        {
                            $retorno .= ( $item->mudou ) ? str_replace('codEmpresa',$item->id_empresa, 'http://www.pow.com.br/powsites/codEmpresa/imo/').$item->image : 'http://www.guiasjp.com/imoveis_imagens/'.$item->image;
                        }
                        
                    } 
                    else
                    {
                        $retorno .= 'http://www.icuritiba.com/imagens/naodisponivel.jpg';
                    }
                    $retorno .= '" >';
                    $retorno .= '<p>';
                    if ( $item->area > 0 || $item->area_terreno > 0 )  
                    {
                        $retorno .= ( ( $item->area > 0 ) ? $item->area : $item->area_terreno ).' m2   ';
                    }
                    $retorno .= ( ($item->terreno) && ! empty($item->terreno) ? 'condominio' : ( $item->quartos ) ? $item->quartos.' Quartos' : '' );
                    $retorno .= '</p>';
                     
                    $retorno .= '<p> '.$item->logradouro.' </p>';
                    $retorno .= '<p>Bairro '.$item->bairro.'</p>';
                    if ( $item->preco > 0 )
                    {
                        $retorno .= '<p class="valor_imovel"> R$ '.  number_format($item->preco, 2, ',', '.').' </p>';
                    }
                    $retorno .= '</a></li>';
                }
            }
            return $retorno;
        }
        
	private function _set_itens()
	{
		$retorno = '';
		if ( isset( $this->itens ) && count( $this->itens ) > 0 )
		{
                    foreach ( $this->itens  as $item )
                    {
                        $retorno .= '<li data-theme="'.$this->theme.'" data-role="'.( isset($this->tipo) ? $this->tipo : 'cidade' ).'-'.$item->id.'" class="'.$this->tipo.'-'.$item->id.'" '.( ( isset($item->extra) ) ? $item->extra : '' ).'>';
                        $retorno .= '<a data-item="'.( isset ($item->id) ? $item->id : '').'" data-qtde="'.( isset($item->qtde) ? $item->qtde : 'undefined' ).'" ';
                        $retorno .= 'href="';
                        $retorno .= $this->link.( ($this->link_compl) ? '' : $item->link ) ;
                        $retorno .= '" ';
                        $retorno .= ' class="'.( isset($item->class) ? $item->class : '' ).'">'; 
                        $retorno .= $item->descricao.( isset($item->qtde) ? ' <span class="ui-li-count"> '.number_format($item->qtde, 0, ',', '.').' </span>' :  '' );  
                        $retorno .= '</a>';
                        $retorno .= '</li>';
                    }
		}
		else
		{
			$retorno .= '<li>Nenhum Resultado</li>';
		}
		return $retorno;
	}
	
	public function inicia( $config )
	{
            $this->theme = ( isset($config['theme']) ) ? $config['theme'] : NULL;
            $this->link = ( isset($config['link']) ) ? $config['link'] : NULL;
            $this->itens = ( isset($config['itens']) ) ? $config['itens'] : NULL;
            $this->tipo = ( isset($config['tipo']) ) ? $config['tipo'] : NULL;
            $this->extra = ( isset($config['extra']) ) ? $config['extra'] : NULL;
            $this->link_compl = ( isset($config['link_compl']) ) ? $config['link_compl'] : FALSE;
            $this->lista = ( isset($config['lista']) ) ? $config['lista'] : FALSE;
            $this->lista_itens = ( isset($config['lista_itens']) ) ? $config['lista_itens'] : NULL;
            $this->classe = ( isset($config['classe']) ) ? $config['classe'] : NULL;
            $this->qtde_total = ( isset($config['qtde_total']) ) ? $config['qtde_total'] : NULL;
            return $this;
	}
	
}