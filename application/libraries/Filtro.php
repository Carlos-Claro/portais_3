<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 
 * 
 * formato padrao de chamada a classe
 * $config['valores'] = $_GET['b']
 * $config['itens'] = array( 'name' => 'nome', 'tipo' => 'text', 'classe' => 'classes', 'valor' => '', 'where' => array( 'tipo' => 'where', 'campo' => 'login', 'valor' => 'teste' ) )
 * $config['colunas'] = 1
 * $config['extras'] = onsubmit="aprova"
 * $config['url'] = http://www.site.com.br/classe/funcao
 * $config['botoes'] = botao
 */

class Filtro
{
	private $CI;
	/**
	 * 
	 * Itens do campo de busca
	 * @var $itens
	 */
	private $itens;
	/**
	 * 
	 * Valores do campo de busca
	 * @var campos
	 */
	private $valores;
	
	private $extras;
	
	private $botoes;
	
	private $url;
        private $width;
	/**
	 * 
	 * Qtde colunas da montagem do menu
	 * @var colunas
	 */
	private $colunas = 1;
        private $cidade = NULL;
	private $rotulo = FALSE;
	private $url_mascara = FALSE;
	private $oq = FALSE;
	private $selecao = FALSE;
	/**
	 * 
	 * Contrutor da Classe
	 */
	public function __construct($config = FALSE) 
	{
            $this->CI =& get_instance();
		if ( $config )
		{
			$this->inicia($config);
		}
	}
	
      
        public function get_titulo()
        {
            return $this->extras['titulo'];
        }
        
        public function get_description()
        {
            return $this->extras['description'];
        }
	
        public function nao_vai_na_url( $chave )
        {
            $array = array('1','reservaimovel','locado','vendido','invisivel','vencimento','bloqueio','servico_ini','servico_fim','tipo','oq','cidade','bairro','proximidade');
            $retorno = array_search($chave, $array);
            return $retorno;
        }
        
	public function get_url()
	{
		$retorno = '';
		if ( $this->valores )
		{
			$a = 0;
			foreach ( $this->valores as $chave => $valor )
			{
                            $valor = is_array($valor) ? implode('-', $valor) : $valor;
                            if ( isset($valor) )
                            {
                                
				if ( $a == 0 )
				{
                                    if ( ! $this->nao_vai_na_url($chave) )
                                   {
					$retorno .= '?pow['.$chave.']='.urlencode($valor);
                                   }
				}
				else 
				{
                                    if ( ! $this->nao_vai_na_url($chave) )
                                   {
					$retorno .= ( empty($retorno) ? '?' : '').'&pow['.$chave.']='.urlencode($valor);
                                   }
				}
                            }
				$a++;
			}
		}
		return $retorno;
	}
        
        private function _url_negativa( $tipo, $valor )
        {
            //var_dump($tipo, $valor);
            $valores = $this->valores;
            //var_dump($valores);
            $url = $this->url_mascara;
            if ( isset($valores['cidade']) && ! empty($valores['cidade']) )
            {
                $url = str_replace('[cidade]', $valores['cidade'], $url);
            }
            else
            {
                $url = str_replace('[cidade]', 0, $url);
            }
            if ( isset($valores['oq']) && $valores['oq'] )
            {
                $url = str_replace('[negocio]', ( $tipo == 'oq' ? 0 : $this->oq ), $url);
            }
            else
            {
                $url = str_replace('[negocio]', 0, $url);
            }
            if ( isset($valores['tipo']) && count( $valores['tipo'] ) > 0 && ! empty( $valores['tipo'] )  )
            {
                foreach( $valores['tipo'] as $chave_tipo => $valor_tipo )
                {
                    if ( $tipo == 'tipo'  )
                    {
                        if ( $valor_tipo != $valor )
                        {
                            $link_tipo[] = $valor_tipo;
                        }
                    }
                    else
                    {
                        $link_tipo[] = $valor_tipo;
                    }
                }
            }
            if ( isset($link_tipo) )
            {
                $url = str_replace('[tipo]', implode('-',$link_tipo), $url);
            }
            else
            {
                $url = str_replace('[tipo]', 0, $url);
            }
            if ( isset($valores['bairro']) && count( $valores['bairro'] ) > 0 && ! empty( $valores['bairro'] ) )
            {
                foreach( $valores['bairro'] as $chave_bairro => $valor_bairro )
                {
                    if ( $tipo == 'bairro' )
                    {
                        if ( $valor_bairro != $valor )
                        {
                            $link_bairro[] = $valor_bairro;
                        }
                    }
                    else
                    {
                        $link_bairro[] = $valor_bairro;
                    }
                }
            }
            if ( isset($link_bairro) )
            {
                $url = str_replace('[bairro]', implode('-',$link_bairro), $url);
            }
            else
            {
                $url = str_replace('[bairro]', 0, $url);
            }
            if ( isset($valores['caracteristicas']) && ! empty( $valores['caracteristicas'] ) )
            {
                //var_dump($valores['caracteristicas']);
                $caracteristicas = explode('-',$valores['caracteristicas']);
                foreach( $caracteristicas as $chave_caracteristicas => $valor_caracteristicas )
                {
                    if ( $tipo == 'caracteristicas' )
                    {
                        if ( $valor_caracteristicas != $valor )
                        {
                            $link_caracteristicas[] = $valor_caracteristicas;
                        }
                    }
                    else
                    {
                        $link_caracteristicas[] = $valor_caracteristicas;
                    }
                }
            }
            if ( isset($link_caracteristicas) )
            {
                $url = str_replace('[caracteristicas]', implode('-',$link_caracteristicas), $url);
            }
            else
            {
                $url = str_replace('[caracteristicas]/', '', $url);
            }
            
            $retorno = $url.$this->get_url();
            return $retorno;
        }
        
        private function _url_positiva( $tipo, $valor )
        {
            $valores = $this->valores;
            $url = $this->url_mascara;
            if ( isset($valores['cidade']) && ! empty($valores['cidade']) )
            {
                $url = str_replace('[cidade]', $valores['cidade'], $url);
            }
            else
            {
                $url = str_replace('[cidade]', 0, $url);
            }
            if ( isset($valores['oq']) && $valores['oq'] )
            {
                $url = str_replace('[negocio]', ( $tipo == 'oq' ? $valor : $this->oq ), $url);
            }
            else
            {
                $url = str_replace('[negocio]', ( $tipo == 'oq' ? $valor : 0 ), $url);
            }
            if ( isset($valores['tipo']) && count( $valores['tipo'] ) > 0 && ! empty( $valores['tipo'] ) )
            {
                foreach( $valores['tipo'] as $chave_tipo => $valor_tipo )
                {
                    $link_tipo[] = $valor_tipo;
                }
            }
            if ( $tipo == 'tipo' )
            {
                $link_tipo[] = $valor;
            }
            //var_dump($link_tipo);
            if ( isset($link_tipo) )
            {
                $url = str_replace('[tipo]', implode('-',$link_tipo), $url);
            }
            else
            {
                $url = str_replace('[tipo]', 0, $url);
            }
            //var_dump($valores['bairro']);
            if ( isset($valores['bairro']) && count( $valores['bairro'] ) > 0 && ! empty( $valores['bairro'] ) )
            {
                foreach( $valores['bairro'] as $chave_bairro => $valor_bairro )
                {
                    $link_bairro[] = $valor_bairro;
                }
            }
            if ( $tipo == 'bairro' )
            {
                $link_bairro[] = $valor;
            }
            if ( isset($link_bairro) )
            {
                $url = str_replace('[bairro]', implode('-',$link_bairro), $url);
            }
            else
            {
                $url = str_replace('[bairro]', 0, $url);
            }
            if ( isset($valores['caracteristicas']) && ! empty( $valores['caracteristicas'] ) )
            {
                $caracteristicas = explode('-',$valores['caracteristicas']);
                foreach( $caracteristicas as $chave_caracteristicas => $valor_caracteristicas )
                {
                    $link_caracteristicas[] = $valor_caracteristicas;
                }
            }
            if ( $tipo == 'caracteristicas' )
            {
                $link_caracteristicas[] = $valor;
            }
            if ( isset($link_caracteristicas) )
            {
                $url = str_replace('[caracteristicas]', implode('-',$link_caracteristicas), $url);
            }
            else
            {
                $url = str_replace('[caracteristicas]/', '', $url);
            }
            
            $retorno = $url.$this->get_url();
            return $retorno;
        }
        
	
	public function get_filtro()
	{
            $retorno = array();
            if ( $this->itens )
            {
                foreach ( $this->itens as $item )
                {
                    if ( isset( $this->valores[ $item['name'] ] ) && ! empty( $this->valores[ $item['name'] ] ) )
                    {
                        if ( is_array( $item['where'] ) )
                        {
                            $retorno[] = array( 'tipo' => $item['where']['tipo'], 'campo' =>  $item['where']['campo'] , 'valor' => $this->valores[ $item['name'] ] );
                        }
                        else 
                        {

                            $retorno[] = $item['where'];
                        }
                    }
                }
            }
            return $retorno;
	}
        
	public function get_html_encontre( $total = NULL )
        {
            $retorno = array();
            foreach( $this->rotulo as $item )
            {
                if ( count($item['itens']) > 0 )
                {
                    $retorno[ $item['grupo'] ]['titulo'] = $item['titulo'];
                    foreach( $item['itens'] as $i )
                    {
                       
                        $retorno[ $item['grupo'] ]['itens'][] = array('item' => $this->_get_campo($i, NULL, FALSE,$i['titulo']), 'titulo' => $i['titulo'], 'tipo' => $i['tipo'] );
                    }
                }
            }
            return $retorno;
        }
        
        private function _get_campo( $item = array(), $conta = NULL, $qtde = TRUE, $vertical = FALSE )
	{
            //var_dump($item);
            
		$retorno = '';
		switch ( $item['tipo'] )
		{
			case 'hidden':
                            $retorno .= '<input class="'.( isset($item['classe']) ? $item['classe'] : '' ).'" name="pow['.$item['name'].']" type="'.$item['tipo'].'" value="'.$item['valor'].'" >'.PHP_EOL;
                            break;
			case 'text':
                            if ( isset($item['label']) && $item['label'] )
                            {
                                $retorno .= '<label >'.$item['titulo'].'</label>';
                            }
                            $retorno .= '<input class="'.( isset($item['classe']) ? $item['classe'] : '' ).' '.$item['name'].'" name="pow['.$item['name'].']" type="'.$item['tipo'].'" value="'.( array_key_exists($item['name'], $this->valores) ?  $this->valores[$item['name'] ] : '' ).'" '.( (isset($item['label']) && $item['label']) ? 'required="required"' : '' ).' placeholder="'.( isset($item['default']) ? $item['default'] : '').'"/>'.PHP_EOL;
                            $retorno .= '<p class="help-block elemento-'.$item['name'].'"></p>';
                            break;
			case 'select':
                            $retorno .= '<select name="pow['.$item['name'].']" class="'.$item['name'].' '.( isset($item['classe']) ? $item['classe'] : '' ).'" required="required">'.PHP_EOL;
                            $retorno .= isset($item['seleciona']) ? '' :  '<option value=""> '.(isset($item['default']) ? $item['default'] : ' - ').'</option>'.PHP_EOL;
                            foreach ( $item['valor'] as $valor )
                            {
                                    $retorno .= '<option value="'.$valor->id.'" '.( ( isset($this->valores[$item['name']]) && $this->valores[$item['name']] == $valor->id ) ? 'selected="selected"' : '' ).'>'.$valor->descricao.'</option>'.PHP_EOL;
                            }
                            $retorno .= '</select>'.PHP_EOL;
                            break;
			case 'radio':
			case 'checkbox':
                            $retorno .= '<label >';
                            $retorno .= '<input class="'.( isset($item['classe']) ? $item['classe'] : '' ).' '.$item['name'].'" name="pow['.$item['name'].']" type="'.$item['tipo'].'" value="1" '.( ( isset($this->valores[$item['name']]) && $this->valores[$item['name']] ) ? 'checked="checked"' : '' ).'/>'.PHP_EOL;
                            $retorno .= $item['titulo'].'</label>';
                            $retorno .= '<p class="help-block elemento-'.$item['name'].'"></p>';
                            break;
                        case 'range':
                            $step = 50;
                            $retorno .= '<div class="item-'.$conta.'  form-group col-lg-12 col-md-12" data-toggle="tooltip" data-title="Clique no valor para digita-lo ou arraste o ponteiro abaixo" data-placement="top" data-trigger="hover" >';
                            $retorno .= '<label class="control-label range-font-'.$item['name'].'" for="'.$item['titulo'].'">'.$item['titulo'].' <span class="valor-'.$item['name'].'"></span> </label>'.PHP_EOL;
                            $retorno .= '<div class="range"><input type="range" name="pow['.$item['name'].']" step="'.$step.'" min="'.$item['valor']['min'].'" max="'.$item['valor']['max'].'" value="'.$item['valor']['value'].'" class="range '.$item['name'].' col-lg-12 col-sm-12 col-md-12 col-xs-12"></div>';
                            $retorno .= '</div>';
                            break;
                        case 'number':
                            $retorno .= '<input 
                                                type="number" 
                                                class="form-control col-lg-8 col-md-8 col-sm-8" 
                                                name="quantity" 
                                                min="1" 
                                                max="5">
                                        <div class=" col-lg-2 col-md-2 col-sm-2 btn btn-default mais glyphicon glyphicon-arrow-up"></div>
                                        <div class="col-lg-2 col-md-2 col-sm-2 btn btn-default menos glyphicon glyphicon-arrow-down"></div>';
                            break;
                        case 'modal':
                            $retorno .= '<button class="btn btn-default" data-toggle="modal-'.$item['name'].'" data-target="#modal-'.$item['name'].'"><span class="pull-left">'.(isset($titulo->descricao) ? $titulo->descricao : 'Selecione').'</span><span class="caret pull-right"></span></button>';
                            $retorno .= set_modal_checklist($item);
                            break;
                        case 'clicavel':
                            $retorno .= '<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12 ">';
                            $retorno .= '<h2>'.$item['titulo'].'</h2>';
                            $retorno .= $this->_get_modal($item['name'], isset($item['selecionado']) ? $item['selecionado'] : $item['titulo'], $item['valor']);
                            $retorno .= '</div>';
                            //<button class="btn btn-busca" data-toggle="modal" data-target="#modal-quero"><span class="pull-left">Selecione</span> <span class="caret pull-right"></span></button>
                            //$retorno .= $this->_get_modal($item['name'], isset($item['selecionado']) ? $item['selecionado'] : $item['titulo'], $item['valor']);
                            break;
                        case 'selecionavel':
                            $retorno .= $this->_get_modal($item['name'], isset($item['selecionado']) ? $item['selecionado']->descricao : $item['titulo'], $item['valor'], FALSE, $qtde, $vertical);
                            $retorno .= '<ul class="resultado-'.$item['name'].'">'.( isset($item['selecionado']) ? '<li  data-item="'.$item['selecionado']->id.'">'.$item['selecionado']->descricao.'</li>' : '').'</ul>';
                            $retorno .= '<input type="hidden" class="hidden-'.$item['name'].'" name="'.$item['name'].'" value="">';
                            break;
                        case 'select_hidden_multiplo':
                            $retorno .= $this->_get_select_hidden_multiplo($item);
                            break;
                        case 'select_hidden_unico':
                            $retorno .= $this->_get_select_hidden_unico($item);
                            break;
                        case 'select_hidden':
                            $retorno .= $this->_get_select_hidden($item);
                            break;
                        case 'select_check':
                            $retorno .= $this->_get_select_check($item);
                            break;
                        case 'modal_vazio':
                            $retorno .= '<button class="btn btn-pow '.$item['name'].' " type="button">';
                            $retorno .= '<center>'.$item['titulo'].'</center>';
                            $retorno .= '<span class="caret pull-right"></span>';
                            $retorno .= '</button>';
                            $retorno .= '<div class="modal-'.$item['name'].'"></div>';
                            $valor_item = '';
                            if ( isset($item['selecionado']) && is_array($item['selecionado']) )
                            {
                                $retorno .= '<ul class="resultado-'.$item['name'].' '.$item['name'].'">';
                                $contador = 0;
                                
                                foreach ( $item['selecionado'] as $sel )
                                {
                                    $retorno .= '<li data-item="'.$sel->id.'">';
                                    $retorno .= $sel->descricao;
                                    //$retorno .= '<span class="close deleta" data-item-pai="'.$item['name'].'" data-deleta="'.$sel->id.'">x</span>';
                                    $retorno .= '</li>';
                                    $valor_item .= $contador == 0 ? $sel->id : '-'.$sel->id;
                                    $contador++;
                                }
                                $retorno .= '</ul>';
                            }
                            else
                            {
                                $retorno .= '<ul class="resultado-'.$item['name'].'">';
                                $retorno .= '</ul>';
                            }
                            $retorno .= '<input type="hidden" class="hidden-'.$item['name'].'" name="'.$item['name'].'" value="'.$valor_item.'">';
                            break;
                            
                        case 'modal_vazio_unico':
                            if ( isset($item['selecionado']) && is_array($item['selecionado']) )
                            {
                                $selecionado_ = $item['selecionado'][0];
                            }
                            else
                            {
                                $selecionado_ = FALSE;
                            }
                            $retorno .= '<button class="btn btn-pow '.$item['name'].' resultado-'.$item['name'].'" type="button" data-item="'.( isset($selecionado_) && ! empty($selecionado_) ? $selecionado_->id : '').'">';
                            $retorno .= '<center>'. ( ( isset($selecionado_) && $selecionado_ ) ? $selecionado_->descricao : $item['titulo'] ) .'</center>';
                            $retorno .= '<span class="caret pull-right"></span>';
                            $retorno .= '</button>';
                            $retorno .= '<div class="modal-'.$item['name'].'"></div>';
                            break;
                            
                        case 'so_empresa':
                            $retorno .= '<button class="btn btn-pow '.$item['name'].' " type="button">';
                            $retorno .= '<center>'.$item['titulo'].'</center>';
                            $retorno .= '</button>';
                            $valor_item = '';
                            if ( isset($item['selecionado']) )
                            {
                                $retorno .= '<ul class="resultado-'.$item['name'].' '.$item['name'].'">';
                                $retorno .= '<li >';
                                $retorno .= $item['selecionado'];
                                $retorno .= '</li>';
                                $retorno .= '</ul>';
                            }
                            else
                            {
                                $retorno .= '<ul class="resultado-'.$item['name'].'">';
                                $retorno .= '</ul>';
                            }
                            //$retorno .= '<input type="hidden" class="hidden-'.$item['name'].'" name="'.$item['name'].'" value="'.$valor_item.'">';
                            break;
                        case 'inativo' :
                            $retorno .= '';
                            break;
		}           
		return $retorno;
	}
	
	public function inicia($config)
	{
                $this->rotulo       = ( isset($config['itens']['rotulo']) ? $config['itens']['rotulo'] : FALSE );
                unset($config['itens']['rotulo']);
		$this->itens        = ( isset($config['itens']) ? $config['itens'] : FALSE );
		$this->valores      = ( isset($config['valores']) ? $config['valores'] : array() );
		$this->colunas      = ( isset($config['colunas']) ? $config['colunas'] : 1 );
		$this->url          = ( isset($config['url']) ? $config['url'] : '#' );
		$this->extras       = ( isset($config['extras']) ? $config['extras'] : FALSE );
                $this->cidade       = ( isset($config['cidade']) ? $config['cidade'] : FALSE );
		$this->botoes       = ( isset($config['botoes']) ? $config['botoes'] : FALSE );
		$this->url_mascara  = ( isset($config['url_mascara']) ? $config['url_mascara'] : FALSE );
                $this->oq           = ( isset($config['oq']) ? $config['oq'] : FALSE);
                //var_dump($config['valores']);
                
		return $this;
	}
	
}	