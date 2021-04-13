<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
use voku\helper\HtmlMin;
/**  
 *  
 * Classe responsável pela formação das principais partes do layout, meta, grupos e gerenciadores
 * @author Carlos Claro 
 *  
 */
class Layout {	
    /** 
     * @name CI	 
     * @access private	 
     * instancia do CodeIgniter	 
     */
    private $CI;		
    /**	 * classe atual	 */	
    private $classe = NULL;	
    /**	 * function atual	 */	
    private $function = NULL;	
    private $mapa = NULL;
    /**	 	 
     * titulo da pagina, 
     * NULL by default	 	 
     */	
    private $titulo = NULL;		
    /**	 	 
     * keywords da pagina, array by default	 	 
     */		
    private $keywords = array('');		
    /**	 	 * * description da pagina, NULL by default	 	 * */	
    private $description = '';		
    /**	 	 * * separador do titulo da pagina, | by default	 	 * */		
    private $separador = ' - ';		
    /**	 	 * * includes javascript e css	 	 * */	
    private $file_includes = array();	
    
    private $usuario = '';
    private $logo = '';
    private $analytics = '';
    private $google_tag = '';
    private $menu = NULL;
    private $on_load = NULL;
    private $sobre = array();
    private $favoritos = NULL;
    private $plus = NULL;
    private $facebook = NULL;
    private $link_bairro = NULL;
    private $robot = TRUE;
    private $canonical = NULL;
    private $header_extra = NULL;
    private $tag_adwords = NULL;
    private $relacionados = NULL;
    private $image_destaque = NULL;
    private $url = NULL;
    private $time = NULL;
    private $mobile = NULL;
    
    /**	 	 
     * Contrutor da Classe	 	 
     */	
    public function __construct() 		
    {
        $this->CI =& get_instance();				
    }	
 
    public function set_sobre( $sobre = NULL, $nome = NULL, $portal = NULL, $gentilico = NULL, $link_cidade = NULL, $uf_cidade = NULL, $link_prefeitura = FALSE, $mostra = FALSE )	
    {		
        $this->sobre = array('sobre' => $sobre, 'nome' => $nome, 'portal' => $portal, 'gentilico' => $gentilico, 'link_cidade' => $link_cidade, 'uf' => $uf_cidade, 'link_prefeitura' => $link_prefeitura, 'mostra' => $mostra );		
        return $this;	
        
    }	
    private function get_sobre()	
    {		
        return $this->sobre;	
        
    }
    public function set_canonical( $canonical = TRUE )	
    {		
        $this->canonical = $canonical;		
        return $this;	
        
    }	
    private function get_canonical()	
    {		
        return $this->canonical;	
        
    }
    public function set_on_load( $on_load = NULL )	
    {		
        $this->on_load = $on_load;		
        return $this;	
        
    }	
    private function get_on_load()	
    {		
        return $this->on_load;	
        
    }
    public function set_url()	
    {		
        $this->url = base_url();		
        return $this;	
        
    }	
    private function get_url()	
    {		
        return $this->url;	
        
    }
    public function set_relacionados( $relacionados = NULL )	
    {		
        $this->relacionados = $relacionados;		
        return $this;	
        
    }	
    private function get_relacionados()	
    {		
        
        return $this->relacionados;	
        
    }
    public function set_tag_adwords( $tag = TRUE )	
    {		
        $this->tag_adwords = $tag;		
        return $this;	
        
    }	
    private function get_tag_adwords()	
    {		
        return $this->tag_adwords;	
        
    }
    public function set_header_extra( $header = NULL )	
    {		
        
        $this->header_extra = $header;		
        return $this;	
        
    }	
    private function get_header_extra()	
    {		
        return $this->header_extra;	
        
    }
    public function set_robot( $robo = TRUE )	
    {		
        $this->robot = $robo;		
        return $this;	
        
    }	
    private function get_robot()	
    {		
        return $this->robot;	
        
    }
    public function set_link_bairro( $link )	
    {		
        $this->link_bairro = $link;		
        return $this;	
        
    }	
    private function get_link_bairro()	
    {		
        return $this->link_bairro;	
        
    }
    public function set_menu( $menu )	
    {		
        $this->menu = $menu;		
        return $this;	
        
    }	
    private function get_menu()	
    {		
        return $this->menu;	
        
    }	
    public function set_plus( $plus )	
    {		
        $this->plus = $plus;		
        return $this;	
        
    }	
    private function get_plus()	
    {		
        return $this->plus;	
        
    }	
    public function set_facebook( $facebook )	
    {		
        $this->facebook = $facebook;		
        return $this;	
        
    }	
    private function get_facebook()	
    {		
        return $this->facebook;	
        
    }	
    public function set_favoritos( $favoritos )	
    {		
        $this->favoritos = $favoritos;		
        return $this;	
        
    }	
    private function get_favoritos()	
    {		
        return $this->favoritos;	
        
    }	
    public function set_usuario( $usuario )	
    {		
        $this->usuario = $usuario;		
        return $this;	
        
    }	
    private function get_usuario()	
    {		
        return $this->usuario;	
        
    }	

    public function set_logo( $logo )	
    {		
        $this->logo = $logo;		
        return $this;	
        
    }	
    private function get_logo()	
    {		
        return $this->logo;	
        
    }	
    
    public function set_classe( $classe )	
    {		
        $this->classe = $classe;		
        return $this;	
    }	

    private function get_classe()	
    {		
        return $this->classe;	
    }	
    public function set_mapa( $mapa )	
    {		
        $this->mapa = $mapa;		
        return $this;	
    }	

    private function get_mapa()	
    {		
        return $this->mapa;	
    }
    public function set_analytics( $analytics )	
    {		
        $this->analytics = $analytics;		
        return $this;	
    }	

    private function get_analytics()	
    {		
        return $this->analytics;	
    }
    public function set_google_tag( $google_tag )	
    {		
        $this->google_tag = isset($google_tag) ? $google_tag : '';		
        return $this;	
    }	

    private function get_google_tag()	
    {		
        return $this->google_tag;	
    }

    public function set_function( $function )	
    {		
        $this->function = $function;		
        return $this;	
    }	
 
    private function get_function()	
    {		
        return $this->function;	
    }	

    /**	 	 * * 	 	 * * Inicia titulo	 	 * */	
    public function set_titulo($titulo) 		
    {				
        if ($titulo) 				
        {						
            $this->titulo = $titulo ;				
        }				
        else 				
        {						
            $this->titulo = '';				
        }				
        return $this;		
    }
    
    private function get_titulo() 		
    {				
        return $this->titulo;		
    }	
    
    public function set_keywords($keywords) 		
    {				
        if (is_array($keywords))				
        {						
            $this->keywords = $keywords;				
        }					
        else				
        {						
            $this->keywords[] = $keywords;				
        }				
        return $this;		
        
    }	
    private function get_keywords() 		
    {				
        if (count($this->keywords) > 0)
        {						
            $this->keywords = implode(', ', $this->keywords);				
        }				
        return $this->keywords;		
    }	

    public function set_description($description) 		
    {			
        $this->description  = $description;				
        return $this;		
    }	
    
    private function get_description() 		
    {				
        return $this->description;		
    }		
    
    public function set_time($time) 		
    {			
        $this->time  = $time;				
        return $this;		
    }	
    
    private function get_time() 		
    {				
        return $this->time;		
    }		
    
    public function set_mobile($mobile = FALSE) 		
    {			
        $this->mobile  = $mobile;				
        return $this;		
    }	
    
    private function get_mobile() 		
    {				
        return $this->mobile;		
    }		
    
    public function set_image_destaque($image) 		
    {			
        $this->image_destaque  = $image;				
        return $this;		
    }	
    
    private function get_image_destaque() 		
    {				
        return $this->image_destaque;		
    }		
    
    /**	 	 
     * ->set_include('js/modernizr.js', TRUE)	 
     * Inicia includes padr�es
                    ->set_include('js/3_3/funcoes_padrao.min.js', TRUE)				
                    ->set_include('js/3_3/default.min.js', TRUE)			
                    ->set_include('css/3_3/topo.min.css', TRUE)
                    ->set_include('css/3_3/filtro.min.css', TRUE)
                    ->set_include('css/3_3/rodape.min.css', TRUE)
                    ->set_include('css/3_3/publicidade.min.css', TRUE)
                    ->set_include('js/3_3/publicidade.min.js', TRUE)
                    ->set_include('js/3_3/destaques.min.js', TRUE)
                    ->set_include('js/3_3/favoritos.min.js', TRUE)
                    ->set_include('css/3_3/favoritos.min.css', TRUE)
                    ->set_include('js/3_3/estatistica.min.js', TRUE)
     */
    public function set_includes_defaults() 		
    {	
        $this
//                ->set_include('css/compilado.css', TRUE)
//                ->set_include('js/compilado.js', TRUE)
                ->set_include('js/jquery-3.3.1.min.js')
                ->set_include('js/default.js', TRUE)				
                ->set_include('js/jquery-migrate-3.0.1.min.js', TRUE)
                ->set_include('css/bootstrap.min.css', TRUE, TRUE)
                ->set_include('plugins/cubeportfolio/css/cubeportfolio.min.css', TRUE, TRUE)
                ->set_include('plugins/font-awesome/css/font-awesome.min.css', TRUE, TRUE)
                ->set_include('plugins/bootstrap-toastr/toastr.min.css', TRUE, TRUE)
//                ->set_include('plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css', TRUE)
//                ->set_include('plugins/bootstrap-modal/css/bootstrap-modal.css', TRUE)
//                ->set_include('plugins/css/components.min.css', TRUE)
                ->set_include('plugins/css/layout.min.css', TRUE, TRUE)
                ->set_include('css/components.min.css', TRUE, TRUE)
//                ->set_include('css/plugins.min.css', TRUE, TRUE)
                ->set_include('css/default.min.css', TRUE, TRUE)
                ->set_include('plugins/select2/css/select2.min.css', TRUE, TRUE)
                ->set_include('js/bootstrap.min.js', TRUE)				
                ->set_include('plugins/cubeportfolio/js/jquery.cubeportfolio.min.js', TRUE)
                ->set_include('plugins/bootstrap-toastr/toastr.min.js', TRUE)
                ->set_include('js/jquery.smooth-scroll.min.js', TRUE)
//                ->set_include('plugins/bootstrap-modal/js/bootstrap-modalmanager.js', TRUE)
//                ->set_include('plugins/bootstrap-modal/js/bootstrap-modal.js', TRUE)
                ->set_include('plugins/jquery-slimscroll/jquery.slimscroll.min.js', TRUE)
                ->set_include('js/jquery.blockui.min.js', TRUE)
//                ->set_include('js/ui-extended-modals.min.js', TRUE)
//                ->set_include('js/table-datatables-scroller.min.js', TRUE)
                ->set_include('js/components.min.js', TRUE)				
                ->set_include('js/app.min.js', TRUE)				
                ->set_include('js/jquery.mobile.touch.min.js', TRUE)				
                ->set_include('js/quick-nav.min.js', TRUE)				
                ->set_include('plugins/js/app.min.js', TRUE)				
                ->set_include('plugins/js/layout.min.js', TRUE)				
                ->set_include('plugins/select2/js/select2.full.min.js', TRUE)
                ->set_include('plugins/bootstrap-sweetalert/sweetalert.min.css', TRUE, TRUE)
                ->set_include('plugins/bootstrap-sweetalert/sweetalert.min.js', TRUE)
                ->set_include('js/favoritos.js', TRUE)				
                ->set_include('js/estatistica.js', TRUE)				
                ->set_include('js/publicidade.js', TRUE)				
                ->set_include('css/style.css', TRUE, TRUE)				
                    ;
        return $this;

    }
    
    public function set_include($path, $prepend_base_url = TRUE, $replace = FALSE)		
    {				
        if ($prepend_base_url)				
        {						
            $path = base_url().$path;				
            
        }	
        if (preg_match('/js$/', $path))				
        {						
            $this->file_includes['js'][] = $path;				
        }				
        elseif (preg_match('/css$/', $path))				
        {						
            $this->file_includes['css'][] = $path.( $replace ? '?replace' : '');				
        }				
        return $this;		
    
    }	
    public function get_includes($incorpora = TRUE)		
    {			
        $final_includes = '';		
        if ( isset($this->file_includes['css']) )
        {
            foreach ($this->file_includes['css'] as $include) 				
            {					
                if ( $incorpora )
                {
                    if ( strstr($include,'replace') )
                    {
                        $e = explode('/',$include);
                        array_pop($e);
                        $a = implode('/', $e);
                        array_pop($e);
                        $b = implode('/',$e);
                        array_pop($e);
                        $c = implode('/',$e);
                        $file = file_get_contents(str_replace(['?replace',base_url()], '',$include));
                        $final_includes .= PHP_EOL.'<!-- '.$include.' --><style>'.str_replace(['../../','../','./'], [$c.'/',$b.'/',$a.'/'], $file).'</style>';
                    }
                    else
                    {
                        $file = file_get_contents(str_replace(base_url(), '',$include));
                        $final_includes .= PHP_EOL.'<style>'.$file.'</style>';
                    }
                }
                else
                {
                    $final_includes .= '<link rel="stylesheet" href="'.$include.'?t='.time().'" type="text/css" />'.PHP_EOL;				
                }
            }				
        }
        if ( isset($this->file_includes['js']) )
        {
            
            foreach ($this->file_includes['js'] as $include) 				
            {						
                if ( $incorpora )
                {
                    $file = file_get_contents(str_replace(base_url(), '',$include));
                    $final_includes .= PHP_EOL.'<script type="text/javascript">
                                        '.$file.'
                                        </script>';
                }
                else
                {
                    $final_includes .= '<script type="text/javascript" src="'.$include.'?t='.time().'"></script>'.PHP_EOL;				
                }

            }				
        }
        return $final_includes;		
        
    }	
    
    public function set_navigation_bar($title, $url, $active = 0, $prepend_base_url = TRUE) 		
    {				
        if ($prepend_base_url)				
        {						
            $url = base_url().$url;				
        }				
        $this->navigation_bar_for_layout[] = (object) array('title'=>$title, 'url'=>$url, 'active'=>$active);				
        return $this;		
    }	
    
    private function get_navigation_bar() 		
    {				
        return $this->navigation_bar_for_layout;		
    }	
    
    public function view($view_name, $params = array(), $layout = 'layout/layout', $retorno_html = FALSE)		
    {	
        
            
        $htmlMin = new HtmlMin();
        $htmlMin->doOptimizeViaHtmlDomParser();
        $htmlMin->doOptimizeAttributes();   
        $htmlMin->doRemoveOmittedQuotes(FALSE); 
        $view_content = $this->CI->load->view($view_name, $params, TRUE);
        if ($this->CI->input->is_ajax_request() && ! $retorno_html)				
        {						
            print $htmlMin->minify($view_content);				
//            print $view_content;				
            
        }				
        else				
        {	
            $params = array(											
                'conteudo'              => $view_content, 											
//                'includes'              => '[*include*]',
                'includes'              => $this->get_includes(FALSE),
                'titulo'		=> $this->get_titulo(),											
                'keywords' 		=> '',											
                'description'           => $this->get_description(),							
                'function' 		=> $this->get_function(),							
                'classe'		=> $this->get_classe(),	
                'usuario'               => $this->get_usuario(),
                'logo'                  => $this->get_logo(),
                'mapa'                  => $this->get_mapa(),
                'analytics'             => $this->get_analytics(),
                'google_tag'            => $this->get_google_tag(),
                'menu'                  => $this->get_menu(),
                'sobre'                 => $this->get_sobre(),
                'favoritos'             => $this->get_favoritos(),
                'facebook'              => $this->get_facebook(),
                'plus'                  => $this->get_plus(),
                'link_bairro'           => $this->get_link_bairro(),
                'robot'                 => $this->get_robot(),
                'canonical'             => $this->get_canonical(),
                'header_extra'          => $this->get_header_extra(),
                'tag_adwords'           => $this->get_tag_adwords(),
                'relacionados'          => $this->get_relacionados(),
                'image_destaque'        => $this->get_image_destaque(),
                'url'                   => $this->get_url(),
                'on_load'               => $this->get_on_load(),
                'time'                  => $this->get_time(),
                'mobile'                => $this->get_mobile(),
                );	
        
            if ( $retorno_html ) 
            {
                $r =  $this->CI->load->view($layout, $params, $retorno_html);	
                return $htmlMin->minify($r);
            }
            else
            {
//                $this->CI->load->view($layout, $params);	
                $r = $this->CI->load->view($layout, $params, TRUE);
                echo $htmlMin->minify($r);
//                $parametro = ['includes' => $this->get_includes()];
////                var_dump($parametro['includes']);
////                var_dump(preg_replace('/(/* !)*?(*/)/','', $parametro['includes']));die();
//                $s = $this->CI->load->view('includes', $parametro, TRUE);
////                $handle = fopen('docs/compilado.html','a+');
////                fwrite($handle, $s);
////                fclose($handle);
////                $s = file_get_contents('docs/compilado.html');
//                echo str_replace('[*include*]', $s, $r);
            }
            			
            
        }		
        
    }
        
}
                            