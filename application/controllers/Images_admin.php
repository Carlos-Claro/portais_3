<?php

class Images_admin extends MY_Controller
{
    // 120 x 90 -> TM -> só faz da foto 1
    // 60 x 45 -> t3 -> faz todas
    // 300 -> T5_codimovel_numerodafoto -> faz todas
    // md5_file da original
    // thumb1m -> fs da TM 120 x 90
    // thumb/id_empresa/
    //powimoveis.com.br/thumb/$id_empresa/
    
    private $ftp_host = array( 
                                'powimoveis' => array( 'hostname' => 'ftp.powimoveis.com.br', 'username' => 'powimoveis06', 'password' => 'c2a0r1l2', 'debug' => TRUE ),
                                'guiasjp' => array( 'hostname' => 'ftp.guiasjp.com', 'username' => 'guiasjp06', 'password' => '9873214', 'debug' => TRUE, 'passive' => TRUE, 'port' => 21, 'timeout' => 60000000 ),
                                );
    private $connect = FALSE;
    private $conectado = FALSE;
    
    private $end_mudou = 'http://www.pow.com.br/powsites/%id_empresa%/imo/';
    private $end_nao_mudou = 'http://www.guiasjp.com/imoveis_imagens/';
    private $tamanhos = array(
                                array('tipo' => 'T3', 'width' => '120', 'height' => '90'),
                                array('tipo' => 'T5', 'width' => '650', 'height' => 'auto'),
                                array('tipo' => 'TM', 'width' => '240', 'height' => '180'),
                                array('tipo' => '650F_F', 'width' => 'auto', 'height' => 'auto'),
                                );
    private $itens = array();
    
    private $imoveis_negativos = array('security');
    private $imoveis_positivos = array();
    private $empresa_negativos = array();
    private $arquivos_deletados = array();
    
    
    public function __construct() 
    {
        /*
        if ( $_SERVER['HTTP_HOST'] != 'localhost' )
        {
            redirect(base_url(), 'location', 301);
            exit();
        } 
         * 
         */
        parent::__construct(FALSE);
        $this->load->library('ftp');
        $this->load->model(array('imoveis_model','empresas_model'));
    }
    
    public function index()
    {
        die('teste');
    }
    
       
    public function verifica_imoveis_pasta()
    {
        $this->ftp->connect($this->ftp_host['guiasjp']);
        $local_download = getcwd().'/images/images_deletadas/';
        $pasta = '';
        //var_dump($local_download);die();
        
        $lista = $this->ftp->list_files($pasta);
        var_dump($lista);
        $this->ftp->close();
    }
    
    public function deletar_imoveis_inativos (  )
    {
        error_reporting(0);
        $this->ftp->connect($this->ftp_host['guiasjp']);
        $local_download = getcwd().'/images/images_deletadas/';
        $pasta = '';
        $lista = $this->ftp->list_files($pasta);
        $array_negativa_str = array('sleep');
        //var_dump($lista);die();
        if ( isset($lista) )
        {
            foreach( $lista as $chave => $arquivo )
            {
                //var_dump($chave);
                //if ( $chave > 17500 )
                //{
                //$this->ftp->connect($this->ftp_host['guiasjp']);
                if ( ! ( $arquivo == '.' || $arquivo == '..' ) )
                {
                    $explode = explode('_', $arquivo);
                    if ( isset($explode[2]) )
                    {
                        $imovel = $explode[2];
                        $tipo = $explode[0];
                        if ( $tipo == 'T4' || $tipo == 'TC' || $tipo == 'FC' )
                        {
                            //echo '<img src="'.$this->end_nao_mudou.$arquivo.'">';
                            if ( ! ( strstr($arquivo,'sleep') || strstr($arquivo,'or') ) )
                            {
                                var_dump('T4 ou TC ou FC '.$arquivo);
                                //var_dump(getimagesize( $this->end_nao_mudou.$arquivo ));
                                $explode_ponto = explode('.', $arquivo);
                                if ( isset($explode_ponto[2]) )
                                {
                                    //var_dump($arquivo);
                                }
                                else
                                {
                                    if ( getimagesize( $this->end_nao_mudou.$arquivo )  )
                                    {
                                        $this->ftp->delete_file( $arquivo );
                                    }
                                }
                            }
                        }
                        
                        else
                        {
                            $imovel = intval($imovel);
                            if ( ! array_search($imovel, $this->imoveis_negativos) && ! array_search($imovel, $this->imoveis_positivos) )
                            {
                                if ( isset($imovel) && $imovel )
                                {
                                    $filtro_imoveis = 'imoveis.id = '.$imovel;
                                    $tem_imovel = $this->imoveis_model->get_total_itens($filtro_imoveis);
                                    if ( $tem_imovel > 0 )
                                    {
                                        $this->imoveis_positivos[] = $imovel;
                                    }
                                    else
                                    {
                                        $this->imoveis_negativos[] = $imovel;
                                        //var_dump('recem negativo - '.$arquivo);
                                        if ( strstr($arquivo,'sleep') || strstr($arquivo,'or') )
                                        {
                                            //var_dump('entrou'.$arquivo);
                                            $this->ftp->delete_file( str_replace('/\'', '', $arquivo) );
                                        }
                                        else
                                        {
                                            //var_dump('nao entrou'.$arquivo);
                                            $download = $this->ftp->download($pasta.$arquivo, $local_download.$arquivo, 'auto' );
                                            $this->ftp->delete_file( $arquivo );
                                        }
                                        
                                        $this->arquivos_deletados[] = $arquivo;

                                    }
                                }
                            }
                            elseif ( array_search($imovel, $this->imoveis_negativos) )
                            {
                                //var_dump('negativo - '.$arquivo);
                                $this->ftp->download($arquivo, $local_download.$arquivo, 'auto');
                                $this->ftp->delete_file( $arquivo );
                                $this->arquivos_deletados[] = $arquivo;
                            }
                        }
                    }
                    
                    else
                    {
                        //var_dump('fora normal- '.$arquivo);
                        $explode_ponto = explode('.', $arquivo);
                        //var_dump($explode_ponto);
                        if ( isset($explode_ponto[2]) || (  strstr($arquivo,'sleep') || strstr($arquivo,'or')  ) || ! isset($explode_ponto[1]) )
                        {
                            //var_dump($arquivo);
                        }
                        else
                        {
                            if ( getimagesize( $this->end_nao_mudou.$arquivo ) )
                            {
                                //$this->ftp->download($arquivo, $local_download.$arquivo, 'auto');
                                //var_dump('falta parametro - '.$arquivo);
                                $this->ftp->delete_file( $arquivo );
                            }
                        }
                    }
                    
                }
                //$this->ftp->close();
                //}
            }
            $this->ftp->close();
            var_dump($this->arquivos_deletados, $this->imoveis_negativos, $this->imoveis_positivos);
        }
        
    }
    
    public function gera_images_por_empresa ( $id_empresa = NULL )
    {
        
        //phpinfo();die();
        //$filtro[] = 'imoveis.data_atualiza_foto < "2015-04-14" ';
        $filtro[] = 'imoveis.invisivel = 0';
        $filtro[] = 'imoveis.vendido = 0';
        $filtro[] = 'imoveis.locado = 0';
        $filtro[] = 'empresas.pagina_visivel > 0';
        //$filtro[] = 'imoveis.fs1 = ""';
        $filtro[] = 'imoveis.foto1 != ""';
        $imoveis = $this->imoveis_model->get_images_por_filtro( $filtro, 0, 1 );
        //var_dump($imoveis);die();
        echo 'Inicio: '.date('Y-m-d H:i').'<br>'.PHP_EOL;
        if ( isset($imoveis['itens']) && $imoveis['qtde'] > 0 )
        {
            foreach( $imoveis['itens'] as $imovel )
            {
                $this->processa_images($imovel);
            }
        }
        
        
        //return $imoveis;
    }
    
    public function gera_images_sequencial()
    {
        //phpinfo();die();
        $filtro[] = 'imoveis.data_atualiza_foto < "2015-04-17 15:59" ';
        $filtro[] = 'imoveis.invisivel = 0';
        $filtro[] = 'imoveis.vendido = 0';
        $filtro[] = 'imoveis.locado = 0';
        $filtro[] = 'empresas.pagina_visivel > 0';
        //$filtro[] = 'imoveis.fs1 = ""';
        $filtro[] = 'imoveis.foto1 != ""';
        $imoveis = $this->imoveis_model->get_images_por_filtro( $filtro, 0, 5 );
        //var_dump($imoveis);die();
        echo 'Inicio: '.date('Y-m-d H:i').'<br>'.PHP_EOL;
        if ( isset($imoveis['itens']) && $imoveis['qtde'] > 0 )
        {
            foreach( $imoveis['itens'] as $imovel )
            {
                $this->processa_images($imovel);
            }
        }
        $this->gera_images_sequencial();
    }
    
    public function testa_url()
    {
        var_dump( getimagesize($this->end_mudou.$item->foto2) );
    }
    
    public function processa_images( $item = NULL )
    {
        if ( strstr($item->foto1, 'http') )
        { 
            $fs1 = ( isset($item->foto1) && ! empty($item->foto1) ) ? 
                    $this->_gera_image($item->fs1, $item->foto1, $item->id_empresa, $item->id, 1, TRUE): 
                    $item->fs1;
            $fs2 = ( isset($item->foto2) && ! empty($item->foto2) ) ? $this->_gera_image($item->fs2, $item->foto2, $item->id_empresa, $item->id, 2): $item->fs2;
            $fs3 = ( isset($item->foto3) && ! empty($item->foto3) ) ? $this->_gera_image($item->fs3, $item->foto3, $item->id_empresa, $item->id, 3): $item->fs3;
            $fs4 = ( isset($item->foto4) && ! empty($item->foto4) ) ? $this->_gera_image($item->fs4, $item->foto4, $item->id_empresa, $item->id, 4): $item->fs4;
            $fs5 = ( isset($item->foto5) && ! empty($item->foto5) ) ? $this->_gera_image($item->fs5, $item->foto5, $item->id_empresa, $item->id, 5): $item->fs5;
            $fs6 = ( isset($item->foto6) && ! empty($item->foto6) ) ? $this->_gera_image($item->fs6, $item->foto6, $item->id_empresa, $item->id, 6): $item->fs6;
            $fs7 = ( isset($item->foto7) && ! empty($item->foto7) ) ? $this->_gera_image($item->fs7, $item->foto7, $item->id_empresa, $item->id, 7): $item->fs7;
            $fs8 = ( isset($item->foto8) && ! empty($item->foto8) ) ? $this->_gera_image($item->fs8, $item->foto8, $item->id_empresa, $item->id, 8): $item->fs8;
            $fs9 = ( isset($item->foto9) && ! empty($item->foto9) ) ? $this->_gera_image($item->fs8, $item->foto9, $item->id_empresa, $item->id, 9): $item->fs8;
            $fs10 = ( isset($item->foto10) && ! empty($item->foto10) ) ? $this->_gera_image($item->fs10, $item->foto10, $item->id_empresa, $item->id, 10): $item->fs8;
            $fs11 = ( isset($item->foto11) && ! empty($item->foto11) ) ? $this->_gera_image($item->fs11, $item->foto11, $item->id_empresa, $item->id, 11): $item->fs8;
            $fs12 = ( isset($item->foto12) && ! empty($item->foto12) ) ? $this->_gera_image($item->fs12, $item->foto12, $item->id_empresa, $item->id, 12): $item->fs8;
        }
        else
        {
            if ( $item->mudou )
            {
                $endereco = str_replace('%id_empresa%',$item->id_empresa,$this->end_mudou);
                $fs1 = ( isset($item->foto1) && ! empty($item->foto1) && getimagesize($endereco.$item->foto1)) ? 
                        $this->_gera_image($item->fs1, $endereco.$item->foto1, $item->id_empresa, $item->id, 1, TRUE): 
                        $item->fs1;
                $fs2 = ( isset($item->foto2) && ! empty($item->foto2) && getimagesize($endereco.$item->foto2)) ? $this->_gera_image($item->fs2, $endereco.$item->foto2, $item->id_empresa, $item->id, 2): $item->fs2;
                $fs3 = ( isset($item->foto3) && ! empty($item->foto3) && getimagesize($endereco.$item->foto3)) ? $this->_gera_image($item->fs3, $endereco.$item->foto3, $item->id_empresa, $item->id, 3): $item->fs3;
                $fs4 = ( isset($item->foto4) && ! empty($item->foto4) && getimagesize($endereco.$item->foto4)) ? $this->_gera_image($item->fs4, $endereco.$item->foto4, $item->id_empresa, $item->id, 4): $item->fs4;
                $fs5 = ( isset($item->foto5) && ! empty($item->foto5) && getimagesize($endereco.$item->foto5)) ? $this->_gera_image($item->fs5, $endereco.$item->foto5, $item->id_empresa, $item->id, 5): $item->fs5;
                $fs6 = ( isset($item->foto6) && ! empty($item->foto6) && getimagesize($endereco.$item->foto6)) ? $this->_gera_image($item->fs6, $endereco.$item->foto6, $item->id_empresa, $item->id, 6): $item->fs6;
                $fs7 = ( isset($item->foto7) && ! empty($item->foto7) && getimagesize($endereco.$item->foto7)) ? $this->_gera_image($item->fs7, $endereco.$item->foto7, $item->id_empresa, $item->id, 7): $item->fs7;
                $fs8 = ( isset($item->foto8) && ! empty($item->foto8) && getimagesize($endereco.$item->foto8)) ? $this->_gera_image($item->fs8, $endereco.$item->foto8, $item->id_empresa, $item->id, 8): $item->fs8;
                $fs9 = ( isset($item->foto9) && ! empty($item->foto9) && getimagesize($endereco.$item->foto9)) ? $this->_gera_image($item->fs8, $endereco.$item->foto9, $item->id_empresa, $item->id, 9): $item->fs8;
                $fs10 = ( isset($item->foto10) && ! empty($item->foto10) && getimagesize($endereco.$item->foto10)) ? $this->_gera_image($item->fs8, $endereco.$item->foto10, $item->id_empresa, $item->id, 10): $item->fs8;
                $fs11 = ( isset($item->foto11) && ! empty($item->foto11) && getimagesize($endereco.$item->foto11)) ? $this->_gera_image($item->fs8, $endereco.$item->foto11, $item->id_empresa, $item->id, 11): $item->fs8;
                $fs12 = ( isset($item->foto12) && ! empty($item->foto12) && getimagesize($endereco.$item->foto12)) ? $this->_gera_image($item->fs8, $endereco.$item->foto12, $item->id_empresa, $item->id, 12): $item->fs8;
            }
            else
            {

                $fs1 = ( isset($item->foto1) && ! empty($item->foto1) && getimagesize($this->end_nao_mudou.$item->foto1) ) ? $this->_gera_image($item->fs1, $this->end_nao_mudou.$item->foto1, $item->id_empresa, $item->id, 1, TRUE): $item->fs1;
                $fs2 = ( isset($item->foto2) && ! empty($item->foto2) && getimagesize($this->end_nao_mudou.$item->foto2) ) ? $this->_gera_image($item->fs2, $this->end_nao_mudou.$item->foto2, $item->id_empresa, $item->id, 2): $item->fs2;
                $fs3 = ( isset($item->foto3) && ! empty($item->foto3) && getimagesize($this->end_nao_mudou.$item->foto3) ) ? $this->_gera_image($item->fs3, $this->end_nao_mudou.$item->foto3, $item->id_empresa, $item->id, 3): $item->fs3;
                $fs4 = ( isset($item->foto4) && ! empty($item->foto4) && getimagesize($this->end_nao_mudou.$item->foto4) ) ? $this->_gera_image($item->fs4, $this->end_nao_mudou.$item->foto4, $item->id_empresa, $item->id, 4): $item->fs4;
                $fs5 = ( isset($item->foto5) && ! empty($item->foto5) && getimagesize($this->end_nao_mudou.$item->foto5) ) ? $this->_gera_image($item->fs5, $this->end_nao_mudou.$item->foto5, $item->id_empresa, $item->id, 5): $item->fs5;
                $fs6 = ( isset($item->foto6) && ! empty($item->foto6) && getimagesize($this->end_nao_mudou.$item->foto6) ) ? $this->_gera_image($item->fs6, $this->end_nao_mudou.$item->foto6, $item->id_empresa, $item->id, 6): $item->fs6;
                $fs7 = ( isset($item->foto7) && ! empty($item->foto7) && getimagesize($this->end_nao_mudou.$item->foto7) ) ? $this->_gera_image($item->fs7, $this->end_nao_mudou.$item->foto7, $item->id_empresa, $item->id, 7): $item->fs7;
                $fs8 = ( isset($item->foto8) && ! empty($item->foto8) && getimagesize($this->end_nao_mudou.$item->foto8) ) ? $this->_gera_image($item->fs8, $this->end_nao_mudou.$item->foto8, $item->id_empresa, $item->id, 8): $item->fs8;
                $fs9 = ( isset($item->foto9) && ! empty($item->foto9) && getimagesize($this->end_nao_mudou.$item->foto9) ) ? $this->_gera_image($item->fs8, $this->end_nao_mudou.$item->foto9, $item->id_empresa, $item->id, 9): $item->fs8;
                $fs10 = ( isset($item->foto10) && ! empty($item->foto10) && getimagesize($this->end_nao_mudou.$item->foto10) ) ? $this->_gera_image($item->fs8, $this->end_nao_mudou.$item->foto10, $item->id_empresa, $item->id, 10): $item->fs8;
                $fs11 = ( isset($item->foto11) && ! empty($item->foto11) && getimagesize($this->end_nao_mudou.$item->foto11) ) ? $this->_gera_image($item->fs8, $this->end_nao_mudou.$item->foto11, $item->id_empresa, $item->id, 11): $item->fs8;
                $fs12 = ( isset($item->foto12) && ! empty($item->foto12) && getimagesize($this->end_nao_mudou.$item->foto12) ) ? $this->_gera_image($item->fs8, $this->end_nao_mudou.$item->foto12, $item->id_empresa, $item->id, 12): $item->fs8;
            }

        }
        //update imovel
        if ( isset($fs1) && $fs1 )
        {
            $dados = array( 'data_atualiza_foto' => date('Y-m-d H:i')  );
            $filtro = 'imoveis.id = '.$item->id;
            $update = $this->imoveis_model->editar($dados,$filtro);
        }
        else
        {
            $update = 0;
        }
        if ( $update > 0 )
        {
            $retorno[$item->id] = TRUE;
        }
        else
        {
            $retorno[$item->id] = FALSE;
        }
        echo '<br>'.$item->id.' - hora: '.date('H:i:s') . ' - status: ' . ( ( $update > 0 ) ? 1 : 0 );
        return $retorno;
    }

    private function _gera_image( $fs, $arquivo, $id_empresa, $id_arquivo, $sequencia = 1, $tm = FALSE )
    {
        $image_info = getimagesize($arquivo);
        switch($image_info["mime"])
        {
            case "image/jpeg":
                foreach ( $this->tamanhos as $tamanho )
                {

                    if ( $tamanho['tipo'] == 'TM' && $tm )
                    {
                        $arq = $this->_set_jpg($image_info, $arquivo, $id_empresa, $id_arquivo, $sequencia, $tamanho, TRUE );

                    }
                    elseif ( $tamanho['tipo'] != 'TM' )
                    {
                        $arq = $this->_set_jpg($image_info, $arquivo, $id_empresa, $id_arquivo, $sequencia, $tamanho, FALSE );
                    }
                }
                break;
            case "image/gif":
                foreach ( $this->tamanhos as $tamanho )
                {

                    if ( $tamanho['tipo'] == 'TM' && $tm )
                    {
                        $arq = $this->_set_gif($image_info, $arquivo, $id_empresa, $id_arquivo, $sequencia, $tamanho, TRUE );

                    }
                    elseif ( $tamanho['tipo'] != 'TM' )
                    {
                        $arq = $this->_set_gif($image_info, $arquivo, $id_empresa, $id_arquivo, $sequencia, $tamanho, FALSE );
                    }
                }
                break;
            case "image/png":
                foreach ( $this->tamanhos as $tamanho )
                {

                    if ( $tamanho['tipo'] == 'TM' && $tm )
                    {
                        $arq = $this->_set_png($image_info, $arquivo, $id_empresa, $id_arquivo, $sequencia, $tamanho, TRUE );

                    }
                    elseif ( $tamanho['tipo'] != 'TM' )
                    {
                        $arq = $this->_set_png($image_info, $arquivo, $id_empresa, $id_arquivo, $sequencia, $tamanho, FALSE );
                    }
                }
                break;
            default:
                $src_img = FALSE;
                $arq = FALSE;
                break;
        }
        if ( $arq )
        {
            $fs = md5_file($arquivo);
        }
        else
        {
            $fs = '';
        }
        var_dump($arq);
        return $fs;
    }
    
    private function _set_pasta_pow( $id_empresa )
    {
        
    }
    
    private function _set_pasta_destino( $id_empresa )
    {
        $retorno = getcwd().'/images/por_empresa/'.$id_empresa.'/';
        var_dump($retorno);
        if ( ! is_dir($retorno) )
        {
            mkdir($retorno, 0777);
        }
        return $retorno;
    }
    
    private function _set_jpg( $image_info, $arquivo, $id_empresa, $id_arquivo, $sequencia, $tamanho, $tm)
    {
        if ( ( $tamanho['height'] == 'auto' ) && ( $tamanho['width'] == 'auto' ) )
        {
            $new_width = $image_info[0];
            $new_height = $image_info[1];
        }
        elseif ( $tamanho['height'] == 'auto' )
        {
            $porcentagem = ( $image_info[0] / $tamanho['width'] );
            $new_width = $tamanho['width'];
            $new_height = ( $image_info[1] / $porcentagem ); 
        }
        else
        {
            $new_width = $tamanho['width'];
            $new_height = $tamanho['height'];
        }
        $src_img = imagecreatefromjpeg($arquivo); //jpeg file
        $dst_img = ImageCreateTrueColor($new_width,$new_height);
        imagecopyresampled($dst_img,$src_img,0,0,0,0,$new_width,$new_height,$image_info[0],$image_info[1]);
        $pasta_destino = $this->_set_pasta_destino($id_empresa);
        $arquivo = $tamanho['tipo'].'_'.$id_arquivo.( ( $tm ) ? '' : '_'.$sequencia ).'.jpg';
        $destino = $pasta_destino.''.$arquivo;
        if ( imagejpeg($dst_img,$destino,80) )
        {
            $retorno = $destino;
        }
        else
        {
            $retorno = FALSE;
        }
        imagedestroy($dst_img);
        return $retorno;
    }
    
    private function _set_gif( $image_info, $arquivo, $id_empresa, $id_arquivo, $sequencia, $tamanho, $tm)
    {
        if ( ( $tamanho['height'] == 'auto' ) && ( $tamanho['width'] == 'auto' ) )
        {
            $new_width = $image_info[0];
            $new_height = $image_info[1];
        }
        elseif ( $tamanho['height'] == 'auto' )
        {
            $porcentagem = ( $image_info[0] / $tamanho['width'] );
            $new_width = $tamanho['width'];
            $new_height = ( $image_info[1] / $porcentagem ); 
        }
        else
        {
            $new_width = $tamanho['width'];
            $new_height = $tamanho['height'];
        }
        $src_img = imagecreatefromgif($arquivo); //jpeg file
        $dst_img = ImageCreateTrueColor($new_width,$new_height);
        imagecopyresampled($dst_img,$src_img,0,0,0,0,$new_width,$new_height,$image_info[0],$image_info[1]);
        $pasta_destino = $this->_set_pasta_destino($id_empresa);
        $arquivo = $tamanho['tipo'].'_'.$id_arquivo.( ( $tm ) ? '' : '_'.$sequencia ).'.gif';
        $destino = $pasta_destino.''.$arquivo;
        if ( imagegif($dst_img,$destino) )
        {
            $retorno = $destino;
        }
        else
        {
            $retorno = FALSE;
        }
        imagedestroy($dst_img);
        return $retorno;
    }
    
    private function _set_png( $image_info, $arquivo, $id_empresa, $id_arquivo, $sequencia, $tamanho, $tm)
    {
        if ( ( $tamanho['height'] == 'auto' ) && ( $tamanho['width'] == 'auto' ) )
        {
            $new_width = $image_info[0];
            $new_height = $image_info[1];
        }
        elseif ( $tamanho['height'] == 'auto' )
        {
            $porcentagem = ( $image_info[0] / $tamanho['width'] );
            $new_width = $tamanho['width'];
            $new_height = ( $image_info[1] / $porcentagem ); 
        }
        else
        {
            $new_width = $tamanho['width'];
            $new_height = $tamanho['height'];
        }
        $src_img = imagecreatefrompng($arquivo); //jpeg file
        $dst_img = ImageCreateTrueColor($new_width,$new_height);
        imagecopyresampled($dst_img,$src_img,0,0,0,0,$new_width,$new_height,$image_info[0],$image_info[1]);
        $pasta_destino = $this->_set_pasta_destino($id_empresa);
        $arquivo = $tamanho['tipo'].'_'.$id_arquivo.( ( $tm ) ? '' : '_'.$sequencia ).'.png';
        $destino = $pasta_destino.''.$arquivo;
        if ( imagepng($dst_img,$destino) )
        {
            $retorno = $destino;
        }
        else
        {
            $retorno = FALSE;
        }
        imagedestroy($dst_img);
        return $retorno;
    }
    
    
    
    
}