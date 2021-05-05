<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 *
 */

class Lista_normal
{
        private $CI;
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
        private $publicidade = NULL;
        private $pagination = NULL;
        private $empresa = NULL;
        private $url_publicidade = 'https://www.guiasjp.com/publicidade/';
        private $url_integracao = 'https://www.powimoveis.com.br/thumb/codEmpresa/';
        private $url_integracao_altera = '/images/portais/codEmpresa/';
        private $url_image_mudou = 'https://www.pow.com.br/powsites/codEmpresa/imo/';
        private $url_image_nao_mudou = 'https://www.guiasjp.com/imoveis_imagens/';
        private $ordenacao = NULL;
        private $qtdes = NULL;
        private $titulo = NULL;
        private $cidade = NULL;
        private $links_relacionados = NULL;
        //str_replace('codEmpresa',$item->id_empresa,


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
                $this->CI =& get_instance();
	}

        public function set_favoritos( $monta_retorno, $retorno_tipo )
        {
            $favoritos = get_cookie('imoveis_favoritos');
            if ( $retorno_tipo == 'array' )
            {
                return $favoritos;
            }
            else
            {
                if ( $favoritos && isset($favoritos) )
                {
                    if ( $monta_retorno )
                    {
                        $retorno = '';
                        if ( count($favoritos) > 0 )
                        {
                            $fav = implode(',',$favoritos);
                            $favoritos = explode(',', $fav);
                            $this->CI->load->model('imoveis_mongo_model');
                            $filtro_imoveis[] = array('tipo' => 'where_in', 'campo' => '_id', 'valor' => $favoritos);
                            $imoveis = $this->CI->imoveis_mongo_model->get_itens($filtro_imoveis, 'ordem', -1, 0, 500);
                            $lista = '';
                            foreach ( $imoveis['itens'] as $imovel )
                            {
                                $lista .= $this->_set_item_vertical($imovel, FALSE, TRUE);
                            }
                            $data['lista'] = $lista;
                            $data['favoritos'] = $favoritos;
                            $retorno = $this->CI->layout
                                    ->set_include('js/3_3/lista.js',TRUE)
                                    ->set_include('css/3_3/lista.css',TRUE)
                                    ->view('favorito_lista', $data, 'layout/sem_head', TRUE);
                        }
                        else
                        {
                            $retorno = $this->CI->layout->view('favorito_vazio', array(), 'layout/sem_head', TRUE);
                        }

                    }
                    else
                    {
                        $retorno = TRUE;
                    }
                }
                else
                {
                    if ( $monta_retorno )
                    {
                        $retorno = $this->CI->layout->view('favorito_vazio', array(), 'layout/sem_head', TRUE);
                    }
                    else
                    {
                        $retorno = FALSE;
                    }
                }
            }
            return $retorno;
        }

        public function get_html_vertical( $publicidade = TRUE, $favorito = FALSE )
        {
            $retorno = '';
            if ( $publicidade )
            {
                $this->_set_publicidade(FALSE, TRUE);
            }
            $data['favorito'] = $favorito;
            $data['publicidade'] = $publicidade ? TRUE : FALSE;
            $data['topo'] = isset($this->publicidade['topo']) ? $this->publicidade['topo'] : array();
            $data['lateral_nacional'] = isset($this->publicidade['lateral_nacional']) ? $this->publicidade['lateral_nacional'] : array();
            $data['vertical'] = isset($this->publicidade['vertical']) ? $this->publicidade['vertical'] : array();
            $data['lateral'] = isset($this->publicidade['lateral']) ? $this->publicidade['lateral'] : array();
            $data['largura'] = $publicidade ? 'col-lg-9 col-sm-9 col-md-9 col-xs-12' : 'col-lg-12 col-sm-12 col-md-12 col-xs-12';
            $data['titulo'] = ( isset( $this->titulo ) && ! empty( $this->titulo ) ) ? $this->titulo : '';
            $data['empresa'] = ( isset($this->empresa) ) ? $this->set_item_imobiliaria($this->empresa, NULL, FALSE,TRUE) : '';
            $data['qtde'] = isset($this->qtdes) ? $this->_set_qtde() : '';
            $data['ordenacao'] = isset($this->ordenacao) ? $this->_set_ordenacao() : '';
            $data['itens'] = array();
            if ( isset($this->itens['destaques']) )
            {
                $total_destaque = 0;
                foreach ( $this->itens['destaques'] as $destaque )
                {
                    //var_dump($destaque);
                    if ( $total_destaque < 4 )
                    {
                        $data['itens'][] = isset($destaque) ? $this->_set_item_vertical($destaque, 1) : '';
                    }
                    $total_destaque++;
                }
            }
            foreach ( $this->itens['itens']['itens'] as $item )
            {
                $data['itens'][] = isset($item) ? $this->_set_item_vertical($item, FALSE, $favorito) : '';
            }
            $data['paginacao'] = ( isset($this->pagination) && !empty($this->pagination) ) ? $this->pagination : '';
            $data['relacionados'] = '';
            if ( isset($this->links_relacionados) && count($this->links_relacionados) > 0 )
            {
            	$data['relacionados'] .= '<ul class="list-group links-relacionados">';
            	$data['relacionados'] .= '<li class="list-group-item list-group-item-success col-lg-12 col-md-12 col-sm-12 col-xs-12"><p class="titulo">Talvez você deseje alguma destas pesquisas:</p></li>';
                foreach( $this->links_relacionados as $link )
                {
                    $data['relacionados'] .= $link;
                }
                $data['relacionados'] .= '</ul>';
            }
            $retorno .= $this->CI->layout->view('html_vertical', $data, 'layout/sem_head', TRUE);
            return $retorno;
        }



        public function get_html_imobiliarias( $link_cidade = 'curitiba_pr' )
        {
            //echo $link_cidade;
            if ( isset($this->itens) && count($this->itens) > 0 )
            {
                //$this->_set_publicidade();
                //var_dump($this->publicidade); die();
                $retorno = '';

                $retorno .= '<div class="imobiliarias">';
                $retorno .= '<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                $retorno .= '<h1>'.( isset($this->h1) && ! empty($this->h1) ? $this->h1 : 'Imobiliárias com imóveis na Cidade' ).'.</h1>';
                $retorno .= '<h2>'.( isset($this->h2) && ! empty($this->h2) ? $this->h2 : 'Encontre a sua').'.</h2>';
                $retorno .= '</div></div>';
                $retorno .= '   <div class="row">';
                $retorno .= '       <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                //publicidade topo
                if ( isset($this->publicidade['topo']) && count($this->publicidade['topo']) > 0 )
                {
                    foreach( $this->publicidade['topo'] as $topo )
                    {
                        $retorno .= $topo;
                    }
                }
                $retorno .= '<div class="fundo-azul">'
                        . '<div class="form-group pesquisa-imobiliaria"><input type="text" name="nome" class="form-control busca-imobiliaria" placeholder="Digite o nome da imobiliária"></div>'
                        . '</div>';
                $retorno .= '<div class="itens-imobiliarias">';
                foreach( $this->itens['itens'] as $item )
                {
                    $retorno .= $this->set_item_imobiliaria($item, $link_cidade);
                }
                $retorno .= '</div>';//.itens-imobiliarias
                $retorno .= '       </div>';
                /*
                $retorno .= '       <div class="col-lg-2 col-md-2 col-sm-2 hidden-xs">';
                if ( isset($this->publicidade['lateral_nacional']) && count($this->publicidade['lateral_nacional']) > 0 )
                {
                    foreach( $this->publicidade['lateral_nacional'] as $lateral_nacional )
                    {
                        $retorno .= $lateral_nacional;
                    }
                }
                if ( isset($this->publicidade['vertical']) && count($this->publicidade['vertical']) > 0 )
                {
                    foreach( $this->publicidade['vertical'] as $vertical )
                    {
                        $retorno .= $vertical;
                    }
                }
                if ( isset($this->publicidade['lateral']) && count($this->publicidade['lateral']) > 0 )
                {
                    foreach( $this->publicidade['lateral'] as $lateral )
                    {
                        $retorno .= $lateral;
                    }
                }
                $retorno .= '       </div>';
                 *
                 */
                $retorno .= '   </div><!-- .row-->';
                $retorno .= '</div><!-- .container-->';
                $retorno .= set_modal_contato();
            }
            else
            {
                $retorno = 'Nenhuma imobiliária encontrada.';
            }
            return $retorno;
        }



        private function _set_qtde()
        {
            $retorno = '';
            if ( isset($this->qtdes) )
            {
               $retorno_ = '';
                $retorno_ .= '<div class="popover popover_qtdes">';
                $retorno_ .= '<div class="arrow"></div>';
                $retorno_ .= '<div class="popover-inner">';
                $container = '';
                foreach( $this->qtdes as $qtdes )
                {
                    if ( $qtdes['active'] == 1 )
                    {
                        $titulo = $qtdes['titulo'];
                        $tipo = $qtdes['tipo'];
                    }

                    $container .= '<a href="'.$qtdes['url'].'" title="'.$qtdes['titulo'].'">'.$qtdes['titulo'].'</a><br>';
                }
                $retorno_ .= $container;
                $retorno_ .= '</div>';
                $retorno_ .= '</div>';
                 $retorno .= '<div class="col-lg-4 col-sm-6 col-md-6 col-xs-12 pull-right">';
                $retorno .= '<a data-selecionado="'.$tipo.'" href="#" data-toggle="popover" title="Quantidade por pagina:" class="btn btn-default pull-right qtdes" style="margin-bottom: 10px;" >'.(isset($titulo) ? 'Quantidade por página: '.$titulo : 'Alterar quantidade').'&nbsp;&nbsp;&nbsp;&nbsp;<span class="caret"></span></a>'.$retorno_;
                $retorno .= '</div>';
            }
            return $retorno;
        }

        public function get_ordenacao($data)
        {
            $this->ordenacao = $data['ordenacao'];
            return $this->_set_ordenacao();
        }

        private function _set_ordenacao()
        {
            $retorno = '';
            if ( isset($this->ordenacao) )
            {
               $retorno_ = '';
                $retorno_ .= '<div class="popover popover_ordem">';
                $retorno_ .= '<div class="arrow"></div>';
                $retorno_ .= '<div class="popover-inner">';
                $container = '';
                foreach( $this->ordenacao as $ordem )
                {
                    if ( $ordem['active'] == 1 )
                    {
                        $titulo = $ordem['titulo'];
                        $tipo = $ordem['tipo'];
                    }

                    $container .= '<a href="'.$ordem['url'].'" title="'.$ordem['titulo'].'">'.$ordem['titulo'].'</a><br>';
                }
                $retorno_ .= $container;
                $retorno_ .= '</div>';
                $retorno_ .= '</div>';
                 $retorno .= '<div class="col-lg-4 col-sm-6 col-md-6 col-xs-12 pull-right">';
                $retorno .= '<a data-selecionado="'.(isset($tipo) ? $tipo : '&coluna=ordem_rad&ordem=ASC').'" href="#" data-toggle="popover" title="Ordenação por:" class="btn btn-default pull-right ordem" style="margin-bottom: 10px;"  >'.(isset($titulo) ? 'Ordem: '.$titulo : 'Alterar Ordem').'&nbsp;&nbsp;&nbsp;&nbsp;<span class="caret"></span></a>'.$retorno_;
                $retorno .= '</div>';
            }
            return $retorno;
        }

        public function set_publicidade ( $itens = FALSE, $interno = FALSE )
        {
            if ( isset($this->itens['publicidade']) || $itens )
            {
                $publicidade = ( $itens ) ? $itens['publicidade'] : $this->itens['publicidade'];
                //echo count($this->itens['publicidade']).'<br>';
                foreach ( $publicidade as $chave => $valor )
                {

                    if ( isset($valor) && count($valor) > 0 )
                    {
                        $this->publicidade[$chave] = array();
                        shuffle($valor);
                        foreach ( $valor as $v )
                        {
                            switch( $chave )
                            {
                                case 'home':
                                    $banner = $v;
                                    //var_dump($v);
                                    break;
                                case 'lateral_nacional':
                                case 'lateral':
                                case 'vertical':
                                    $banner = '<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 publicidade "><div class="row">';
                                    $banner .= '<div class="col-lg-1 col-md-2 col-sm-2 col-xs-12">';
                                    $banner .= $this->_set_publicidade_cabecalho($v);
                                    $banner .= '</div>';
                                    $banner .= '<div class="col-lg-11 col-md-10 col-sm-10 col-xs-12">';
                                    $banner .= $this->_set_publicidade_image($v, TRUE);
                                    $banner .= '</div>';
                                    $banner .= '</div></div></div>';
                                    break;
                                case 'atopo':
                                case 'meio':
                                case 'meio_2':
                                case 'rodape':
                                    $banner = '<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 publicidade "><div class="row">';
                                    $banner .= '<div class="col-lg-1 col-md-2 col-sm-2 col-xs-12">';
                                    $banner .= $this->_set_publicidade_cabecalho($v);
                                    $banner .= '</div>';
                                    $banner .= '<div class="col-lg-11 col-md-10 col-sm-10 col-xs-12">';
                                    $banner .= $this->_set_publicidade_image($v);
                                    $banner .= '</div>';
                                    $banner .= '</div></div></div>';
                                    break;
                            }
                            $this->publicidade[$chave][] = $banner;
                        }
                    }
                }

            }
            return $this->publicidade;
        }

        private function _set_publicidade_cabecalho ( $d )
        {
            $retorno = '<p class="titulo">Publicidade</p>';
            if ( $d->ext == 'googl' )
            {
                $retorno .= '';
            }
            else
            {
                $retorno .= '<p>'.$d->nome.'</p>';
            }
            //$retorno .= '<button class="btn btn-contato-email publicidade-contato" data-item="'.$d->id_campanha.'" data-email="'.$d->email.'">Contato por e-mail</button>';
            return $retorno;
        }

        private function _set_publicidade_image ( $p, $interno = FALSE )
        {
            if ( isset($_GET['usuario']) )
            {
                var_dump($p);
            }
            $retorno = '';
            switch( $p->ext )
            {
                case '.gif':
                case '.jpg':
                case '.png':
                    $retorno .= '<center><img src="'.$this->url_publicidade.( $interno ? ( $p->diferente_interno ? $p->banner_alternativo : $p->banner ) : $p->banner ).'" data-item="'.$p->id_campanha.'" data-url="'.( (isset($p->url) && ! empty($p->url)) ? $p->url : '' ).'" alt="'.$p->nome.'" class="img-publicidade img-responsive campanha-'.$p->id_campanha.'" style="max-width: 100%'./*$p->width*/''.'px; max-height: '.$p->height.'px;"></center>';
                    break;
                case '.swf':
                    $retorno .= '<div class="img-flash" data-item="'.$p->id_campanha.'" data-url="'.( (isset($p->url) && ! empty($p->url)) ? $p->url : '' ).'"  ><center>'
                        . '<object  '
                        . 'class="img-responsive " '
                        . 'style="width:'.$p->width.'px; height:'.$p->height.'px;" '
                        . 'type="application/x-shockwave-flash" '
                        . 'data="'.$this->url_publicidade.$p->banner.'"'
                        . '>'
                        . '<param name="movie" value="'.$this->url_publicidade.$p->banner.'" />'
                        . '<param name="wmode" value="transparent" />'
                        . '<param name="quality" value="high" />'
                        . '<a href="http://www.adobe.com/go/getflash">'
                        . '<img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Obter Adobe Flash Player" /></a>'
                        . '</object>'
                        . '</center></div>';
                    //$retorno .= '<center>'
                        //. '<embed src="'.$this->url_publicidade.$p->banner.'" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="false" class="img-responsive img-publicidade" data-url="'.( (isset($p->url) && ! empty($p->url)) ? $p->url : '' ).'" data-item="'.$p->id_campanha.'" style="width:'.$p->width.'px; height:'.$p->height.'px;" wmode="transparent"/>'
                        //. '</center>';
                    break;
                case 'googl':
                    $retorno .= $p->banner;
                    break;
            }
            return $retorno;
        }

        public function _set_item_email ( $item = NULL, $email = FALSE, $por_empresa = FALSE )
        {

            $retorno = '';
            $tn = 0;
            if ( $item->tipo_venda == 1 && $item->tipo_locacao == 1 )
            {
                $tipo = (object)array('classe' => 'venda_locacao', 'titulo' => 'Venda ou Locação');
            }
            elseif ( $item->tipo_locacao_dia == 1 && $item->tipo_locacao == 1 )
            {
                $tipo = (object)array('classe' => 'locacao_locacao_dia', 'titulo' => 'Locação');
            }
            elseif ( $item->tipo_venda == 1 )
            {
                $tn = 'venda';
                $tipo = (object)array('classe' => 'venda', 'titulo' => 'Imóvel para Venda');
            }
            elseif ( $item->tipo_locacao == 1 )
            {
                $tn = 'locacao';
                $tipo = (object)array('classe' => 'locacao', 'titulo' => 'Imóvel para Locação');
            }
            elseif ( $item->tipo_locacao_dia == 1 )
            {
                $tipo = (object)array('classe' => 'locacao_dia', 'titulo' => 'Imóvel para Locação temporada');
            }
            $titulo = (isset($item->nome) ? $item->nome.' de '.$item->nome_empresa : '' );
            $retorno .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >';
            $retorno .= '   <div class="thumbnail '.$tipo->classe.' " style="background-color:#EEEEEE; height:360px;">';
            $retorno .= '<h3>';
            $retorno .= '<a href="'. $this->_set_link($item, 'e').'" title="'.(isset($item->nome) ? $item->nome.' de '.$item->nome_empresa : '' ).'">';
            $retorno .= $tipo->titulo;
            $retorno .= '</a>';
            $retorno .= '</h3>';
            $retorno .= '<center>';
            $retorno .= '<a href="'. $this->_set_link($item, 'e').'" title="'.(isset($item->nome) ? $item->nome.' de '.$item->nome_empresa : '' ).'">';
            $retorno .= '<img class="img-responsive" src="';
            if ( isset($item->image) && !empty($item->image) )
            {
                $arquivo = set_arquivo_image($item->id_imovel, $item->image, $item->id_empresa, $item->mudou, $item->fs1,1,'T5');
               $retorno .= $arquivo;
               /*
                *

               if ( strstr($item->image, 'http' ) )
                {
                    $retorno .= $item->image;
                }
                else
                {
                    $retorno .= ( $item->mudou ) ? str_replace('codEmpresa',$item->id_empresa, 'http://www.pow.com.br/powsites/codEmpresa/imo/').$item->image : 'http://www.guiasjp.com/imoveis_imagens/'.$item->image;
                }
                */
            }
            else
            {
                $retorno .= 'https://www.icuritiba.com/imagens/naodisponivel.jpg';
            }
            $retorno .= '" alt="'.( ( ! empty($item->image_descricao) ) ? $item->image_descricao : $titulo ).'" style="width:300px; height:200px;">';
            $retorno .= '</a>';
            $retorno .= '</center>';
            $retorno .= '<div class="caption" style="height: 100px;" >';
            //lin ha abaixo modificada
            $retorno .= (isset($item->imoveis_tipos_titulo)) ? '<h4><strong>'.$item->imoveis_tipos_titulo.' </strong></h4>' : '';
            $retorno .= (isset($item->bairro) && ! empty($item->bairro) ) ? '<h5><strong>'.$item->bairro.' </strong></h5>' : '';

            //$retorno .= '<h3>'.(isset($item->nome) ? $item->nome : '' ).'</h3>';
            //$retorno .= '<hr>';
            //if ( isset($item->descricao) && ! empty($item->descricao) )
            //{
              //  $retorno .= '<p>'.$item->descricao.'</p>';
            //}
            //else
            //{
                $retorno .= '<p class="border-bottom">'.$item->cidade_nome.'/'.$item->uf.' </p>';
                /*
                if ( $item->area > 0 || $item->area_terreno > 0 )
                {
                    $retorno .= '<p>'.( ( $item->area > 0 ) ? '<span class="bold">'. $item->area.'</span>' : '<span class="bold">'.$item->area_terreno.'</span>' ).' m&sup2; de área útil </p> ';
                }
                $retorno .= '<p>'.( ($item->terreno) && ! empty($item->terreno) ? 'Terreno em Condominio' : ( ! empty($item->quartos) && $item->quartos > 0 ) ? '<span>'.$item->quartos.'</span> Dormitórios' : '' ).'</p>';
                //$retorno .= (isset($item->garagens) && $item->garagens > 0) ? '<p><strong>'.$item->garagens.'</strong> Vagas</p>' : '';
                 *
                 */
                /**
                 *

                if ( $item->preco_venda != '0.00' && $item->preco_venda > 0 )
                {
                    $retorno .= '<p class="valor_imovel">R$ '.( ! $email ? '<span itemprop="price">' : '').''.  number_format($item->preco_venda, 2, ',', '.').' '.( ! $email ? '</span>' : '').' </p>';
                }
                if ( $item->preco_locacao != '0.00' && $item->preco_locacao  > 0 )
                {
                    $retorno .= '<p class="valor_imovel">R$ '.( ! $email ? '<span itemprop="price">' : '').''.  number_format($item->preco_locacao, 2, ',', '.').' '.( ! $email ? '</span>' : '').' </p>';
                }
                if ( $item->preco_locacao_dia != '0.00' && $item->preco_locacao_dia > 0 )
                {
                    $retorno .= '<p class="valor_imovel">R$ '.( ! $email ? '<span itemprop="price">' : '').''.  number_format($item->preco_locacao_dia, 2, ',', '.').' '.( ! $email ? '</span>' : '').' </p>';
                }
                */
            //}

            $retorno .= '<a style="position:absolute; bottom:10px; right:'.( $email ? '40px' : '20px').'; padding:2px; " href="'.$this->_set_link($item, 'e').'" class="btn '.( $email ? 'btn-primary' : 'btn-link' ).' pull-right">Mais detalhes</a>';
            $retorno .= '</div>';
            $retorno .= '   </div>';
            $retorno .= '</div>';
            if ( ! $por_empresa )
            {
                $retorno .= '<div class="btn-group">';
                //var_dump($item);
                ///imoveis/curitiba_pr/venda/apartamento
                ///imoveis/curitiba_pr/venda/apartamento/?pow[bairro]=abranches_43
                if ( ! empty($item->bairro) )
                {
                    $retorno .= '<br><center><a href="'.base_url().'imoveis/'.$item->cidades_link.'/'.$tn.'/'.$item->imoveis_tipos_link.'/'.$item->bairros_link.'" class="btn btn-default">Veja mais: '.$item->imoveis_tipos_titulo.' / '.$item->bairro.' / '.str_replace('Imóvel para', '', $tipo->titulo).'</a></center>';
                    //$retorno .= '<a href="'.base_url().'imoveis/'.$item->cidades_link.'/'.$tn.'/0/'.$item->bairros_link.'" class="btn btn-link">+ '.$item->bairro.' para '.str_replace('Imóvel para', '', $tipo->titulo).'</a>';
                }
                else
                {
                    $retorno .= '<br><center><a href="'.base_url().'imoveis/'.$item->cidades_link.'/'.$tn.'/'.$item->imoveis_tipos_link.'" class="btn btn-default">Veja mais: '.$item->imoveis_tipos_titulo.' / '.str_replace('Imóvel para', '', $tipo->titulo).'</a></center>';

                }
                $retorno .= '</div>';
            }
            return $retorno;
        }

        public function _set_item_destaque ( $item = NULL, $email = FALSE )
        {
            $retorno = '';
            $data['item'] = $item;
            $data['tipo'] = $this->_set_tipo($item);
            $data['titulo'] = (isset($item->nome) ? $item->nome.' de '.$item->nome_empresa : '' );
            $data['coluna'] = ( $email ? 'col-lg-12 col-md-12 col-sm-12 col-xs-12' : 'col-lg-3 col-md-3 col-sm-6 col-xs-12' );
            $data['email'] = $email;
            $data['link'] = $this->_set_link($item, 3);
            $data['arquivo'] = ( ( isset($item->images[0]) && ! empty($item->images[0]->arquivo) ) || ( isset($item->image) && !empty($item->image)) ) ? set_arquivo_image($item->id_imovel, isset($item->image) ? $item->image : $item->images[0]->arquivo, $item->id_empresa, $item->mudou, ( isset($item->fs1) ? $item->fs1 : ''  ) ,1,'destaque_home') : base_url().'imagens/naodisponivel.jpg';
            $data['alt_image'] = ( ( ! empty($item->images[0]->titulo) ) ? $item->images[0]->titulo : $data['titulo'] );
            $data['valor'] = $this->_set_valor($item);
            $retorno .= $this->CI->layout->view('item_destaque', $data, 'layout/sem_head', TRUE);
            return $retorno;
        }

        private function _set_tipo( $item )
        {
            if ( $item->tipo_venda == 1 && $item->tipo_locacao == 1 )
            {
                $tipo = (object)array('classe' => 'venda_locacao', 'titulo' => '<span class="pull-left text-left">'.( isset($item->imoveis_tipos_titulo) ? $item->imoveis_tipos_titulo : 'Imóvel' ).' à Venda ou Aluguel</span>');
            }
            elseif ( $item->tipo_locacao_dia == 1 && $item->tipo_locacao == 1 )
            {
                $tipo = (object)array('classe' => 'locacao_locacao_dia', 'titulo' => '<span class="pull-left text-left">'.( isset($item->imoveis_tipos_titulo) ? $item->imoveis_tipos_titulo : 'Imóvel' ).' para Alugar</span>');
            }
            elseif ( $item->tipo_venda == 1 )
            {
                $tipo = (object)array('classe' => 'venda', 'titulo' => ( isset($item->imoveis_tipos_titulo) ? $item->imoveis_tipos_titulo : 'Imóvel' ).' à  Venda');
            }
            elseif ( $item->tipo_locacao == 1 )
            {
                $tipo = (object)array('classe' => 'locacao', 'titulo' => ( isset($item->imoveis_tipos_titulo) ? $item->imoveis_tipos_titulo : 'Imóvel' ).' para Alugar');
            }
            elseif ( $item->tipo_locacao_dia == 1 )
            {
                $tipo = (object)array('classe' => 'locacao_dia', 'titulo' => ( isset($item->imoveis_tipos_titulo) ? $item->imoveis_tipos_titulo : 'Imóvel' ).' para Alugar temporada');
            }
            return $tipo;
        }

        private function _set_valor( $item )
        {
            $retorno = '';
            if ( $item->preco_venda != '0.00' && $item->preco_venda > 0 )
            {
                $retorno = number_format($item->preco_venda, 2, ',', '.');
            }
            if ( $item->preco_locacao != '0.00' && $item->preco_locacao  > 0 )
            {
                $retorno = number_format($item->preco_locacao, 2, ',', '.');
            }
            if ( $item->preco_locacao_dia != '0.00' && $item->preco_locacao_dia > 0 )
            {
                $retorno = number_format($item->preco_locacao_dia, 2, ',', '.');
            }
            return $retorno;
        }

        public function _set_item_vertical ( $item, $destaque = FALSE, $favoritos = FALSE )
        {
            $origem = $this->get_origem($destaque, $favoritos);
//            $fav = $this->set_favoritos(FALSE, 'array');
            $data = $this->get_images($item);
            $data['itens_favoritos'] = isset($fav) ? $fav : array();
            $data['item'] = $item;
            $data['origem'] = $origem;
            $data['destaque'] = $destaque;
            $data['favoritos'] = $favoritos;
            $data['item'] = $item;
            $data['titulo'] = $this->set_titulo($item);
            $data['link'] = $this->_set_link($item, $origem );
            $data['area'] = $this->_set_area($item->area,$item->area_terreno,$item->area_util);
            $data['opcionais'] = $this->_set_opcionais($item->quartos, $item->garagens, $item->banheiros);
            $data['valores'] = $this->_set_valores($item->preco_venda, $item->preco_locacao, $item->preco_locacao_dia);
            $retorno = $this->CI->layout->view('item_vertical', $data, 'layout/sem_head', TRUE);
            return $retorno;
        }

        public function get_origem($destaque, $favoritos)
        {
            if ( $favoritos )
            {
                $origem = 5;
            }
            elseif($destaque)
            {
                switch($destaque)
                {
                    case 'tipo':
                        $origem = 7;
                        break;
                    case 'bairro':
                        $origem = 8;
                        break;
                    case 'relacionado':
                        $origem = 8;
                        break;
                }
            }
            else
            {
                $origem = 4;
            }
            return $origem;
        }

        public function _set_item_grid ( $item, $destaque = FALSE, $favoritos = FALSE )
        {
            //var_dump($item);
            $origem = $this->get_origem($destaque, $favoritos);
            $fav = $this->set_favoritos(FALSE, 'array');
            $data = $this->get_images($item);
            $data['itens_favoritos'] = isset($fav) ? $fav : array();
            $data['favoritos'] = $favoritos;
            $data['item'] = $item;
            $data['origem'] = $origem;
            $data['destaque'] = $destaque;
            $data['favoritos'] = $favoritos;
            $data['item'] = $item;
            $data['titulo'] = $this->set_titulo($item);
            $data['link'] = $this->_set_link($item, $origem );
            $data['area'] = $this->_set_area($item->area,$item->area_terreno,$item->area_util);
            $data['opcionais'] = $this->_set_opcionais($item->quartos, $item->garagens, $item->banheiros);
            $data['valores'] = $this->_set_valores($item->preco_venda, $item->preco_locacao, $item->preco_locacao_dia);
            $retorno = $this->CI->layout->view('item_grid', $data, 'layout/sem_head', TRUE);
            return $retorno;
        }

        public function get_images($item, $destaque = FALSE, $imovel = FALSE)
        {
            if ( $destaque )
            {
                if ( isset($item->image) )
                {
                    $data['arquivo'] = ( isset($item->image) && ! empty($item->image) ) ? $item->image : base_url().'imagens/naodisponivel.jpg';
                    $data['alt_image'] = ( ( ! empty($item->image_descricao) ) ? $item->image_descricao : $item->nome );

                }
                else
                {
                    $data['arquivo'] = ( isset($item->images[0]->arquivo) && ! empty($item->images[0]->arquivo) ) ? $item->images[0]->arquivo : base_url().'imagens/naodisponivel.jpg';
                    $data['alt_image'] = ( ( ! empty($item->images[0]->titulo) ) ? $item->images[0]->titulo : $item->nome );
                }
            }
            else
            {
                $data['arquivo'] = ( isset($item->images[0]->arquivo) && ! empty($item->images[0]->arquivo) ) ? $item->images[0]->arquivo : base_url().'imagens/naodisponivel.jpg';
                $data['alt_image'] = ( ( ! empty($item->images[0]->titulo) ) ? $item->images[0]->titulo : $item->nome );
            }
            $lista_images = $this->set_lista_images( $item->images, $item->id_empresa);
            $data['lista'] = $lista_images;
            if ( isset($lista_images) && count($lista_images) > 0 )
            {
                $data['arquivo'] = $lista_images['principal']->arquivo_local;
                $data['alt_image'] = $lista_images['principal']->titulo;
                unset($lista_images[0]);
                $data['images'] = urlencode(json_encode($lista_images['lista'], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT));
            }
            else
            {
                $data['arquivo'] = base_url().'imagens/naodisponivel.jpg';
                $data['alt_image'] = 'Não Disponível';
                $data['images'] = '';
            }
            $data['galeria'] = $this->set_galeria($data, $item, $imovel);
            return $data;
        }

        public function set_galeria($data,$item)
        {
            $galeria = '';
            $galeria .= '';
            $mais_images = ( isset($data['images']) && ! empty($data['images']) ) ? TRUE : FALSE;
            $galeria .= '<div class="espaco-image slide carousel item-'.$item->_id.'" data-item="'.$item->_id.'" data-images="'.($mais_images ? $data['images'] : '').'">';
            $galeria .= '<a class="amplia-images" data-item="'.$item->_id.'" href="#" title="'.$item->nome.'">';
            $galeria .= '<div class="carousel-inner" role="listbox">';
            $galeria .= '<div class="item active" data-atual="0">';
            $galeria .= '<div class=" link-img center-block" title="'.$item->nome.'" >';
            $galeria .= '<center><img '
                    . 'itemprop="image" '
                    . 'data-src="'.str_replace(['http://www.powempresas.com/','https://www.powempresas.com/', 'http://201.22.56.213/portais_3/','https://201.22.56.213/portais_3/'], base_url(), $data['arquivo']).'" '
                    . 'alt="'.$data['alt_image'].'" src="'.base_url().'imagens/naodisponivel.jpg" title="'.$data['alt_image'].'" class="img-responsive"></center>';
            $galeria .= '</div>';
            $galeria .= '</div>';
            $galeria .= '</div>';
            $galeria .= '</a>';
            if ( $mais_images )
            {
                $galeria .= '<a class="left carousel-control" href="#carousel" role="button" data-slide="prev" data-item="'.$item->_id.'" data-atual="0"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span><span class="sr-only">Anterior</span></a>';
                $galeria .= '<a class="right carousel-control" href="#carousel" role="button" data-slide="next" data-item="'.$item->_id.'" data-atual="0"><span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span> <span class="sr-only">Proximo</span></a>';
            }
            $galeria .= '</div>';
            return $galeria;
        }

        public function set_titulo($item)
        {
//            var_dump($item);die();
            $titulo = ( isset($item->imoveis_tipos_titulo) && ! empty($item->imoveis_tipos_titulo) ? $item->imoveis_tipos_titulo : 'Imóvel' );
            if ( isset($item->quartos) && $item->quartos > 0 )
            {
                $titulo .= ' '.$item->quartos.' quarto'.( ($item->quartos > 1) ? 's' : '' );
                $quartos = TRUE;
            }
            elseif ( ( ! empty($item->area) && $item->area > 0.00 ) )
            {
                $titulo .= ' '.($item->area).' m²';
                $area = TRUE;

            }
            elseif ( ( ! empty($item->area_terreno) && $item->area_terreno > 0.00 ) )
            {
                $titulo .= ' '.($item->area_terreno).' m²';
                $area = TRUE;
            }
            $titulo .= ( ($item->tipo_venda) ? ' à Venda ' : '' ).
                    ( ($item->tipo_locacao) ? ' para Alugar ' : '' ).
                    ( ($item->tipo_locacao_dia) ? ' para Alugar Temporada  ' : '' );
            if ( isset($bairro) || ! isset($area) )
            {
                $titulo .= ( ! empty($item->area) && $item->area > 0.00 ) ? ($item->area).' m²' : ( ( ! empty($item->area_terreno) && $item->area_terreno > 0.00 ) ? ($item->area_terreno).' m²' : '' );

            }
            $titulo .= ($item->condominio) ? ' em condomínio' : '';

            $retorno['h2'] = $titulo;
            $retorno['localizacao'] = '';
            if ( isset($item->logradouro) && ! empty($item->logradouro) && (! strstr($item->logradouro,'N?o informado')) )
            {
                $retorno['localizacao'] .= $item->logradouro;
                $logradouro = TRUE;
            }
            if ( isset($item->bairro) && ! empty($item->bairro) )
            {
                $retorno['localizacao'] .= (isset($logradouro) ? ' - ' : '').$item->bairro;
                $bairro = TRUE;
            }
            if ( isset($item->cidade) && ! empty($item->cidade) )
            {
                $retorno['localizacao'] .= (isset($logradouro) || isset($bairro) ? ', ' : '').$item->cidade. ' - '.$item->estado;
            }
            return $retorno;
        }

        public function set_lista_images($images, $id_empresa = NULL)
        {
            $retorno = array();
            if ( isset($images) )
            {
                $a = 0;
                foreach( $images as $chave => $image )
                {
                    if ( isset($image) && ! empty($image) )
                    {

                        if ( $a == 0 )
                        {
                            $retorno['principal'] = (object)array();
                            $retorno['principal']->arquivo_local = str_replace(['http://www.powempresas.com/images/por_empresa'],'https://images.powempresas.com/portais',$image->arquivo);
//                            $retorno['principal']->arquivo_local = strstr($image->original, 'http') ? $image->original : 'https://www.pow.com.br/powsites/'.$id_empresa.'/imo/'.$image->original;
                            $retorno['principal']->original = strstr($image->original, 'http') ? $image->original : 'https://www.pow.com.br/powsites/'.$id_empresa.'/imo/'.$image->original;
                            $retorno['principal']->titulo = $image->titulo;

                        }
                        $retorno['lista'][$chave] = (object)array();
                        $retorno['lista'][$chave]->arquivo_local = str_replace(['http://www.powempresas.com/images/por_empresa'],'https://images.powempresas.com/portais',$image->arquivo);
//                        $retorno['lista'][$chave]->arquivo_local = strstr($image->original, 'http') ? $image->original : 'https://www.pow.com.br/powsites/'.$id_empresa.'/imo/'.$image->original;
                        $retorno['lista'][$chave]->original = strstr($image->original, 'http') ? $image->original : 'https://www.pow.com.br/powsites/'.$id_empresa.'/imo/'.$image->original;
                        $retorno['lista'][$chave]->titulo = $image->titulo;
                    }
                    $a++;
                }
            }
            return $retorno;
        }

        public function set_item_relacionado ( $item, $destaque = FALSE, $favoritos = FALSE )
        {
            //var_dump($item);die();
            $origem = 2;
            $link = $this->_set_link($item, $origem );

            $retorno .= '<li itemscope itemtype="http://schema.org/Product" class="media '.( ($destaque) ? 'destaque' : '' ).'" data-item="'.$item->_id.'">';
            $retorno .= '<a class="pull-left link-img" href="'.$link.'" title="'.$item->nome.'"  >';
            $retorno .= '<img itemprop="image" class="media-object" src="';
            $conta_image = 0;
            foreach( $item->images as $image )
            {
                if ( $conta_image == 0 )
                {
                   $arquivo = str_replace(array('http://www.powempresas.com/images/por_empresa'),'https://images.powempresas.com/portais',$image->arquivo);
                   $retorno .= $arquivo;

                }
                $conta_image++;
            }
            $retorno .= '" alt="'.$item->nome.'" class="img-responsive">';
            $retorno .= '</a>';
            $retorno .= '<div class="media-body">';
            $retorno .= '<a href="'.$link.'" title="'.$item->nome.'" >';
            $retorno .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
            $retorno .= '<span itemprop="name">';
            $retorno .= '<h2 class="titulo">'.( ($item->tipo_venda) ? ' Venda | ' : '' ).( ($item->tipo_locacao) ? ' Locação | ' : '' ).( ($item->tipo_locacao_dia) ? ' Locação Temporada  ' : '' ).$item->imoveis_tipos_titulo.'</h2>';
            $retorno .= '</span><span class="descricao" itemprop="description">';
            //$retorno .= $this->_set_area($item->area,$item->area_terreno,$item->area_util);
            $retorno .= $this->_set_opcionais($item->quartos, $item->garagens, $item->banheiros, TRUE);
            $retorno .= '</span>';
            $retorno .= '</a>';
            $retorno .= $this->_set_valores($item->preco_venda, $item->preco_locacao, $item->preco_locacao_dia);
            $retorno .= '</div>';//.text-right
            $retorno .= '</div>';//.media-body
            $retorno .= '</li>';

            return $retorno;
        }

        public function _set_item_relacionado_ficha ( $item, $zero = FALSE, $local = 2 )
        {
            $link = $this->_set_link($item, $local );
            $retorno = '<div class="col-lg-3 col-sm-3 col-md-3 col-xs-6">';
            $retorno .= '<div class="thumbnail">';

            $retorno .= '<a class="pull-left link-img" href="'.$link.'" title="'.$item->nome.'"  >';
            $retorno .= '<img itemprop="image" src="';
            $conta_image = 0;
            foreach( $item->images as $image )
            {
                if ( $conta_image == 0 )
                {
                   $arquivo = LOCALHOST ? $image->arquivo : str_replace(array('http://www.powempresas.com/images/por_empresa', 'https://www.powempresas.com'),'https://images.powempresas.com/portais',$image->arquivo);
                   $retorno .= $arquivo;

                }
                $conta_image++;
            }
            $retorno .= '" alt="'.$item->nome.'" class="img-responsive">';
            $retorno .= '</a>';
            $retorno .= '<div class="caption">';
            $retorno .= '<a href="'.$link.'" title="'.$item->nome.'" >';
            $retorno .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
            $retorno .= '<span itemprop="name">';
            $retorno .= '<h2 class="titulo">'.( ($item->tipo_venda) ? ' Venda | ' : '' ).( ($item->tipo_locacao) ? ' Locação | ' : '' ).( ($item->tipo_locacao_dia) ? ' Locação Temporada  ' : '' ).$item->imoveis_tipos_titulo.'</h2>';
            $retorno .= '</span>';
//            $retorno .= '<span class="descricao" itemprop="description">';
//            //$retorno .= $this->_set_area($item->area,$item->area_terreno,$item->area_util);
//            $retorno .= $this->_set_opcionais($item->quartos, $item->garagens, $item->banheiros, TRUE);
//            $retorno .= '</span>';
            $retorno .= '</a>';
            $retorno .= $this->_set_valores($item->preco_venda, $item->preco_locacao, $item->preco_locacao_dia);
            $retorno .= '</div>';//.text-right
            $retorno .= '</div>';//.media-body

            $retorno .= '</div>';
            $retorno .= '</div>';
            //**/
            return $retorno;
        }


        public function set_itens_relacionado_ficha ( $itens, $empresa = FALSE )
        {
            $nome_ = 'carousel-relacionados'.($empresa ? '-empresa' : '');
            $lista = array();
            $marcadores = '';
            $paginas = '';
            $local = $empresa ? 'p' : 2;
            foreach ( $itens['itens'] as $chave => $item )
            {
                $lista[] = $this->_set_item_relacionado_ficha($item, $chave, $local );
            }
            $qtde = count($lista);
            $qtde_itens = ceil($qtde/4);
            $b = 0;
            for( $a = 0; $a < $qtde_itens; $a++ )
            {
                $paginas .= '<div class="item '.( ! $a ? 'active' : '').'">';
                for( $c = 0; $c < 4; $c++ )
                {
                    $paginas .= isset($lista[$b]) ? $lista[$b] : '';
                    $b++;
                }
                $paginas .= '</div>';
                $marcadores .= '<li data-target="#'.$nome_.'" data-slide-to="'.$a.'" class="'.( ! $a ? 'active' : '' ).'"></li>';
            }
            $retorno = '';
            if ( $empresa )
            {
                /*
                $retorno .= '<div class="imobiliaria" itemprop="brand" itemscope itemtype="http://schema.org/Brand">';
                $retorno .= '<div class="row">';
                $retorno .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                //$retorno .= '<h4 class="text-center"><small>Aproveite e veja mais imóveis de <strong>'.$empresa->imobiliaria_nome.'</strong></small></h4>';
//                $retorno .= '<br>';
                $retorno .= '<img itemprop="logo" src="https://www.pow.com.br/powsites/'.$empresa->id_empresa.'/'.$empresa->logo.'" alt="logo '.$empresa->imobiliaria_nome.'" class="pull-left logo-imobiliaria">';
                $retorno .= '<button class="ver-telefone-hover btn  pull-right btn-lg" data-item="'.$empresa->id_empresa.'"><span class="glyphicon glyphicon-earphone"></span><span class="telefone-hide hide">'.$item->ddd.' - '.$item->imobiliaria_telefone.'</span></button>';
//                $retorno .= '<p class="pull-right ver-mais-empresa btn"><small>Saiba mais sobre esta empresa</small></p>';
                $retorno .= '<div class="identificacao-empresa pull-left">';
                $retorno .= '<p>CRECI:'.$empresa->creci.'</p>';
                $retorno .= '<p>End: '. $empresa->imobiliaria_logradouro.', '.$empresa->imobiliaria_numero.' <br> '.$empresa->imobiliaria_bairro.' - '.$empresa->imobiliaria_cidade.'</p>';
//                $retorno .= '<button class="btn btn-topo ver-telefone" data-item="'.$empresa->id_empresa.'" type="button" data-telefone=""><span class="glyphicon glyphicon-envelope"></span> ver telefone</button>';
//                $retorno .= '<a type="button" data-item="'.$empresa->id_empresa.'" class="btn" target="_blank" href="'.base_url().'imobiliaria/'.urlencode( tira_especiais($empresa->imobiliaria_nome) ).'-'.$empresa->id_empresa.'/" > + Imóveis desse anunciante</a></center>';
                $retorno .= '</div>';
                $retorno .= '</div>';
                $retorno .= '</div>';
                $retorno .= '</div>';
                 *
                 */
            }
            $retorno .= '<div class="row">';
            $retorno .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
            $retorno .= '<div id="'.$nome_.'" class="carousel slide carousel-relacionado '.( $empresa ? 'carousel-empresa' : '' ).'" data-ride="carousel">';
            $retorno .= '<!-- Wrapper for slides --><div class="carousel-inner" role="listbox">';
            $retorno .= $paginas;
            $retorno .= '</div>';
            $retorno .= '<!-- Controls --><a class="left carousel-control" href="#'.$nome_.'" role="button" data-slide="prev"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span><span class="sr-only">Previous</span></a>';
            $retorno .= '<a class="right carousel-control" href="#'.$nome_.'" role="button" data-slide="next"><span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span><span class="sr-only">Next</span></a>';
            $retorno .= '<!-- Indicators --><ol class="carousel-indicators">'.$marcadores.'</ol>';
            $retorno .= '</div>';
            $retorno .= '</div>';
            $retorno .= '</div>';
            return $retorno;
        }

        private function _set_valores ( $venda, $locacao, $locacao_dia )
        {
            $retorno = '<div class="espaco-valores">';

            $qtde = 0;
            if ( $venda > 0.00 || $locacao > 0.00 || $locacao_dia > 0.00 )
            {
                if ( $venda > 0.00 )
                {
                    $retorno .= '<p class="valor_imovel valor_venda"><span class="glyphicon glyphicon-usd badget"></span><span itemprop="price"> '.  number_format($venda, 2, ',', '.').'</span></p>';
                }
                if ( $locacao > 0.00 )
                {
                    $retorno .= '<p class="valor_imovel valor_locacao"><span class="glyphicon glyphicon-usd badget"></span><span itemprop="price"> '.  number_format($locacao, 2, ',', '.').'</span></p>';
                }
                if ( $locacao_dia > 0.00 )
                {
                    $retorno .= '<p class="valor_imovel valor_locacao_dia"><span class="glyphicon glyphicon-usd badget"></span><span itemprop="price"> '.  number_format($locacao_dia, 2, ',', '.').'</span></p>';
                }
            }
            $retorno .= '</div><div class="clearfix"></div>';
            return $retorno;
        }

        /**
         * monta o item imobiliaria na lista
         * @version 20151021 modificado, retirada de mapa da empresa
         * @param type $item
         * @param type $link_cidade
         * @param type $links
         * @param type $h
         * @return type
         */
        public function set_item_imobiliaria($item, $link_cidade = NULL, $links = TRUE, $h = FALSE)
        {
            $mapa = $item->mapa;
            $item->mapa = NULL;
            $retorno = $links ? '' : '<br>';
            $retorno .= '<div itemscope itemtype="http://schema.org/LocalBusiness" class="media imobi" data-titulo="'.$item->nome_fantasia.'" data-id="'.$item->id.'" >';
            if ( isset($item->logo) && ! empty($item->logo) )
            {
                $retorno .= '<img itemprop="image" class="media-object pull-left" src="https://www.pow.com.br/powsites/'.$item->id.'/'.$item->logo.'" alt="'.$item->nome_fantasia.'" class="img-responsive">';
            }
            $retorno .= '<div class="media-body">';
            if ( isset($item->mapa) && ! empty($item->mapa) )
            {
                $retorno .= '<div class="col-lg-8 col-sm-8 col-md-8 col-xs-12">';
            }
            $retorno .= '<h'.( $h ? 1 : 2 ).' itemprop="name" class="media-heading">'.$item->nome_fantasia.' <small>'.$item->creci.'</small></h'.( $h ? 1 : 2 ).'>';

            $retorno .= '<span itemprop="review" itemscope itemtype="http://schema.org/Review"><p itemprop="reviewBody">'.character_limiter($item->descricao, 120).'</p></span>';
            $retorno .= '<p itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">'.$item->endereco.'</p>';

            if ( isset($item->mapa) && ! empty($item->mapa) )
            {
                $retorno .= '</div>';
                $retorno .= '<div class="col-lg-4 col-sm-4 col-md-4 col-xs-12 pull-right">';
                $retorno .= '<iframe src="'.base_url().'imoveis/mapa_empresa/'.$item->id.'" frameborder="0" height="140" width="100%" scrolling="no" class="pull-right"></iframe>';
                $retorno .= '<a href="'.base_url().'imoveis/mapa_empresa/'.$item->id.'" target="_blank" rel="nofollow">Visualizar mapa em tela inteira.</a>';
                $retorno .= '</div>';
            }
            if ( $links )
            {
                $retorno .= '<div class="btn-group btn-group-sm">';
                if ( $item->imoveis_qtde > 0 )
                {
                    $retorno .= '<a data-item="'.$item->id.'" class="btn btn-default carrinho" href="'.base_url().'imobiliaria/'.urlencode( tira_especiais($item->nome_fantasia) ).'-'.$item->id.'/imoveis-'.$link_cidade.'?itens='. urlencode(json_encode(array('cidade'=>$link_cidade))).'" style="width:auto;">Imóveis na cidade</a>';
                }
                $link = base_url().'imobiliaria/'.tira_especiais($item->nome_seo).'-'.$item->id.'/imoveis-'.$link_cidade;
//                $retorno .= '<button class="btn btn-default contato" type="button" data-item="'.$item->id.'" data-titulo="'.$item->nome_fantasia.'">Contato por e-mail</button>';
                $retorno .= '<button class="btn btn-default ver-telefone-empresa" type="button" data-item="'.$item->id.'" data-telefone="'.$item->telefone.'" >Ver Telefone</button>';
                if ( isset($item->whatsapp) && !empty($item->whatsapp) )
                {
                    $retorno .= '
                                    <div class="btn-group btn-vertical">
                                        <button type="button" class="btn btn-whats hidden-xs whats-desktop ver-telefone-whats-lista btn-secondary dropdown-toggle" data-item="'.$item->id.'" data-log="22" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fa fa-whatsapp"></i> WhatsApp
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="btn" href="https://web.whatsapp.com/send?phone=+55'.$item->whatsapp.'&text='. urlencode('Encontrei sua imobiliária no link: '.$link).'" data-item="'.$item->id.'" data-log="22" target="_blank">Conversar com a imobiliária</a></li>
                                            <li><a class="btn" href="https://web.whatsapp.com/send?text='. urlencode('Veja esta pagina: '.$link).'" data-item="'.$item->id.'" data-log="22" target="_blank">Enviar para um amigo</a></li>
                                        </ul>
                                      </div>
                                      <div class="btn-group btn-vertical">
                                        <button type="button" class="btn btn-whats whats-mobile ver-telefone-whats-empresa btn-secondary dropdown-toggle " data-item="'.$item->id.'" data-log="22" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fa fa-whatsapp"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="btn" href="https://api.whatsapp.com/send?phone=+55'.$item->whatsapp.'&text='. urlencode('encontrei sua imobiliaria atraves do link: '.$link).'" data-item="'.$item->id.'" data-log="22" target="_blank">Conversar com a imobiliária</a></li>
                                            <li><a class="btn" href="https://api.whatsapp.com/send?text='. urlencode('Veja estes imóveis: '.$link).'" data-item="'.$item->id.'" data-log="22" target="_blank">Enviar para um amigo</a></li>
                                        </ul>
                                      </div>
                                ';
                }
                if ( isset($mapa) && ! empty($mapa) )
                {
                    $retorno .= '<a class="btn btn-default" href="'.base_url().'imoveis/mapa_empresa/'.$item->id.'" target="_blank">Verificar localização</a>';
                }
                $retorno .= '</div>';//.btn-group
            }

            $retorno .= '</div>';//.media-body

            $retorno .= '</div>';//.media
            $retorno .= $links ? '' : '<br>';
            return $retorno;
        }

        public function set_item_noticia($item)
        {

            $retorno = '<div class="media noti" data-titulo="'.strip_tags($item->titulo).'" >';
            $retorno .= '<a itemscope itemtype="http://schema.org/Article"  href="'.base_url().'blog/'.urlencode( tira_especiais($item->titulo) ).'/'.$item->id.'">';

            if ( ! empty($item->icone) )
            {
                $retorno .= '<img itemprop="image" class="media-object pull-left" src="http://www.guiasjp.com/fotos_noticias/'.$item->foto.'" alt="'.$item->titulo.'" class="img-responsive">';
            }

            $retorno .= '<div class="media-body">';
            $retorno .= '<h2 itemprop="name" class="media-heading">'.strip_tags($item->titulo).'</h2>';
            $retorno .= '<p itemprop="articleBody">'.character_limiter(strip_tags($item->descricao), 120).'</p>';
            $retorno .= '</div>';//.media-body
            $retorno .= '</a>';
            $retorno .= '</div>';//.media
            return $retorno;
        }

        private function _set_opcionais ( $quartos, $garagens, $banheiros, $relacionados = FALSE )
        {
            $retorno = '';
            $qtde_itens = 0;
            if ( $quartos > 0 || $garagens > 0 || $banheiros > 0 )
            {
                if ( ! empty($quartos) && $quartos > 0 )
                {
                    $qtde_itens++;
                    $retorno .= ''.$quartos.' <span class="bold opcional">quarto'.( ( $quartos > 1 ) ? 's ' : ' ').'</span>';
                }
                if ( ! empty($garagens) && $garagens > 0 )
                {
                    $qtde_itens++;
                    $retorno .= '| '.$garagens.' <span class="bold opcional">'. ( ( $garagens > 1 ) ? 'vagas ' : 'vaga ' ).'</span>';
                }
                if ( ! $relacionados )
                {
                    $retorno .= ( (! empty($garagens) && $garagens > 0) && (! empty($banheiros) && $banheiros > 0) ) ? '|' : '';
                    if ( ! empty($banheiros) && $banheiros > 0  )
                    {
                            $qtde_itens++;
                            $retorno .= ''.$banheiros.' <span class="bold opcional">banheiro'. ( ( $banheiros > 1 ) ? 's ' : ' ' ).'</span>';
                    }
                }
            }
            return $retorno;
        }

        private function _set_area( $area = '', $area_terreno = '', $area_util = '' )
        {
//            var_dump($area,$area_terreno,$area_util);
            $retorno = '';
            if ( ( ! empty($area) && $area > 0 ) || ( ! empty($area_terreno) && $area_terreno > 0 ) )
            {
                $retorno .= '| '.( $area_terreno > 0 ? $area_terreno : $area);
                $retorno .= 'm&sup2; <span class="bold opcional">';
                $retorno .= ' de área </span>';
            }
            $retorno .= ( ! empty($area_util) && $area_util > 0.00 ) ? '| '.$area_util.'<span class="bold opcional">m&sup2; de área útil </span>' : '';
            return $retorno;
        }

        public function _set_link( $item, $origem = NULL )
        {
            $venda = isset($item->tipo_venda) ? $item->tipo_venda : $item->venda;
            $locacao = isset($item->tipo_locacao) ? $item->tipo_locacao : $item->locacao;
            $locacao_dia = isset($item->tipo_locacao_dia) ? $item->tipo_locacao_dia : $item->locacao_dia;


            //var_dump($item);
            $separador = '-';
            $retorno = base_url().'imovel/';
            $retorno .= $venda == 1 ? 'venda'.$separador : '';
            $retorno .= $locacao == 1 ? 'locacao'.$separador : '';
            $retorno .= $locacao_dia == 1 ? 'locacao_dia'.$separador : '';
            $retorno .= $item->imoveis_tipos_link.$separador;
            $retorno .= $item->cidades_link.$separador;
            $retorno .= $item->bairros_link.$separador;
            $retorno .= $item->imobiliaria_nome_seo;
            $retorno = trim($retorno);
            //$retorno .= 'teste';
            $retorno .= '/'.(isset($item->_id) ? $item->_id : $item->id);

            if ( isset($origem) && ! empty($origem) )
            {
                $retorno .= '/'.$origem;
            }

            return $retorno;
        }


        private function _set_item ( $item )
        {
            $retorno = '';
            if ( $item->tipo_venda == 1 && $item->tipo_locacao == 1 )
            {
                $tipo = (object)array('classe' => 'venda_locacao', 'titulo' => '<span class="pull-left text-left">Venda</span><span class="pull-right text-right">Locação</span>');
            }
            elseif ( $item->tipo_locacao_dia == 1 && $item->tipo_locacao == 1 )
            {
                $tipo = (object)array('classe' => 'locacao_locacao_dia', 'titulo' => '<span class="pull-left text-left">Locação</span><span class="pull-right text-right">Locação</span>');
            }
            elseif ( $item->tipo_venda == 1 )
            {
                $tipo = (object)array('classe' => 'venda', 'titulo' => 'Venda');
            }
            elseif ( $item->tipo_locacao == 1 )
            {
                $tipo = (object)array('classe' => 'locacao', 'titulo' => 'Locação');
            }
            elseif ( $item->tipo_locacao_dia == 1 )
            {
                $tipo = (object)array('classe' => 'locacao_dia', 'titulo' => 'Locação temporada');
            }
            $retorno .= '<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 ">';
            $retorno .= '   <div class="thumbnail '.$tipo->classe.' ">';
            $retorno .= '<a href="'.base_url().'imovel/'.$item->id_imovel.'" title="'.(isset($item->nome) ? $item->nome : '' ).'">';
            $retorno .= '<h4>'.$tipo->titulo.'</h4>';
            $retorno .= '<img class="img-responsive" src="';
            if ( isset($item->image) && !empty($item->image) )
            {
                if ( strstr($item->image, 'http' ) )
                {
                    $retorno .= $item->image;
                }
                else
                {
                    $retorno .= ( $item->mudou ) ? str_replace('codEmpresa',$item->id_empresa, 'https://www.pow.com.br/powsites/codEmpresa/imo/').$item->image : 'https://www.guiasjp.com/imoveis_imagens/'.$item->image;
                }

            }
            else
            {
                $retorno .= 'https://www.icuritiba.com/imagens/naodisponivel.jpg';
            }
            $retorno .= '" >';
            $retorno .= '<div class="caption">';
            $retorno .= (isset($item->bairro) && ! empty($item->bairro) ) ? '<h3>'.$item->bairro.'</h3>' : '';
            $retorno .= '<p>'.(isset($item->nome) ? $item->nome : '' ).'</p>';
            $retorno .= '<hr>';
            if ( $item->area > 0 || $item->area_terreno > 0 )
            {
                $retorno .= '<p>'.( ( $item->area > 0 ) ? '<strong>'. $item->area.'</strong>' : '<strong>'.$item->area_terreno.'</strong>' ).' m&sup2; de área útil </p> ';
            }
            $retorno .= '<p>'.( ($item->terreno) && ! empty($item->terreno) ? 'Terreno em Condominio' : ( ! empty($item->quartos) && $item->quartos > 0 ) ? $item->quartos.' Dormitórios' : '' ).'</p>';
            if ( $item->preco_venda != '0.00' && $item->preco_venda > 0 )
            {
                $retorno .= '<p class="valor_imovel">Venda: R$ '.  number_format($item->preco_venda, 2, ',', '.').' </p>';
            }
            if ( $item->preco_locacao != '0.00' && $item->preco_locacao  > 0 )
            {
                $retorno .= '<p class="valor_imovel">Locação:  R$ '.  number_format($item->preco_locacao, 2, ',', '.').' </p>';
            }
            if ( $item->preco_locacao_dia != '0.00' && $item->preco_locacao_dia > 0 )
            {
                $retorno .= '<p class="valor_imovel">Locação dia: R$ '.  number_format($item->preco_locacao_dia, 2, ',', '.').' </p>';
            }
            $retorno .= '<a href="#" class="btn btn-link">Mais detalhes</a>';
            $retorno .= '</div>';
            $retorno .= '</a>';
            $retorno .= '   </div>';
            $retorno .= '</div>';
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
            $this->pagination = ( isset($config['pagination']) ) ? $config['pagination'] : NULL;
            $this->empresa = ( isset($config['empresa']) ) ? $config['empresa'] : NULL;
            $this->ordenacao = ( isset($config['ordenacao']) ) ? $config['ordenacao'] : NULL;
            $this->qtdes = ( isset($config['qtdes']) ) ? $config['qtdes'] : NULL;
            $this->titulo = ( isset($config['titulo']) ) ? $config['titulo'] : NULL;
            $this->h1 = ( isset($config['h1']) ) ? $config['h1'] : NULL;
            $this->h2 = ( isset($config['h1']) ) ? $config['h2'] : NULL;
            $this->cidade = ( isset($config['cidade']) ) ? $config['cidade'] : NULL;
            $this->links_relacionados = ( isset($config['links_relacionados']) ) ? $config['links_relacionados'] : NULL;
            return $this;
	}

}
