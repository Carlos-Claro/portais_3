<?php
/**
 * Description of Rss
 * 
 * @author programador
 */
use Vinelab\Rss\Rss as Feed;
class Rss {
     
    private $rss;
    private $feed;
    private $CI;

    public function set($url)
    {
        $this->rss = simplexml_load_file('http://www.portaisimobiliarios.com.br/blog/feed/', 'SimpleXMLElement');
        $this->CI =& get_instance();
    }
    
    public function get($qtde = 3)
    {
        $itens = $this->rss->channel->item;
        $html = '';
        if(isset($itens[0]))
        {
            foreach ($itens as $chave => $item)
            {
                if($item->children('media', TRUE))
                {
                    $mes_semana_en = array(
                        'Mon',
                        'Tues',
                        'Wed',
                        'Thurs',
                        'Fri',
                        'Sat',
                        'Sun',
                        '+0000',
                        'Feb',
                        'Apr',
                        'May',
                        'Aug',
                        'Sep',
                        'Oct',
                        'Dec'
                    );
                    $mes_semana_pt = array(
                        'Seg',
                        'Ter',
                        'Qua',
                        'Qui',
                        'Sex',
                        'Sab',
                        'Dom',
                        '',
                        'Fev',
                        'Abr',
                        'Mai',
                        'Ago',
                        'Set',
                        'Out',
                        'Dez'
                    );
                    $data = $item;
                    $data->data = str_replace($mes_semana_en, $mes_semana_pt, ($item->pubDate));
                    $data->imagem = $item->children('media', TRUE)->content->attributes();
                    $data->descricao = explode('</p>',$item->description)[0].'</p>';
                    $html .= $this->CI->load->view('rss/noticias',$data,TRUE);
                }
                if($qtde === 0)
                {
                    return $html;
                }
                $qtde --;
            }
        }
        return $html;
    }
    
    
}
