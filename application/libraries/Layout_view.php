<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
use voku\helper\HtmlMin;
/**  
 *  
 * Classe responsável pela formação das principais partes do layout, meta, grupos e gerenciadores
 * @author Carlos Claro 
 *  
 */
class Layout_view {	
    /** 
     * @name CI	 
     * @access private	 
     * instancia do CodeIgniter	 
     */
    private $CI;		
    
    /**	 	 
     * Contrutor da Classe	 	 
     */	
    public function __construct() 		
    {
        $this->CI =& get_instance();				
    }	
     
    public function view($view_name, $params = array(), $layout = 'layout/layout', $retorno_html = FALSE)		
    {	
        $htmlMin = new HtmlMin();
        $htmlMin->doOptimizeViaHtmlDomParser();
        $htmlMin->doOptimizeAttributes();   
        $htmlMin->doRemoveOmittedQuotes(FALSE); 
        $view_content = $this->CI->load->view($view_name, $params, TRUE);
        return $htmlMin->minify($view_content);				
//      				
        
    }
        
}
                            