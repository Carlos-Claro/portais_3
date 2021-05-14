<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

 

$route['manifest.json'] = 'log/manifest';

$route['queremos_voce_de_novo'] = 'index/landing_page/queremos_voce_de_novo';

$route['novidades'] = 'index/landing_page/nova_cara';

$route['rede'] = 'index/rede';
$route['redisparo_emails'] = 'index/redisparo_emails';
$route['redisparo_emails/(:any)'] = 'index/redisparo_emails/$1';

$route['images_admin/index'] = 'images_admin/index';
$route['images_admin/deletar_imoveis_inativos'] = 'images_admin/deletar_imoveis_inativos';

$route['imoveis/mapa/(:any)'] = 'index/mapa/$1';
$route['imoveis/mapa/(:any)/(:any)'] = 'index/mapa/$1/$2';
$route['imoveis/mapa_empresa/(:any)'] = 'index/mapa_empresa/$1';

$route['index.php/imoveis/mapa/(:any)'] = 'index/mapa/$1';
$route['index.php/imoveis/mapa/(:any)/(:any)'] = 'index/mapa/$1/$2';
$route['index.php/imoveis/mapa_empresa/(:any)'] = 'index/mapa_empresa/$1';

$route['favoritos/get'] = 'favoritos/index';
$route['favoritos/lista'] = 'favoritos/lista_normal';
$route['favoritos/compara'] = 'favoritos/lista_compara';
$route['favoritar/(:any)'] = 'favoritos/favoritar/$1';
$route['favoritar/(:any)/(:any)'] = 'favoritos/favoritar/$1/$2';

$route['estatistica'] = 'estatisticas/index';
$route['imoveis/estatistica.php'] = 'index/estatistica';

$route['mapa_do_site'] = 'index/mapa_do_site/'; 
$route['quem.php'] = 'index/quem_somos/'; 
$route['quem_somos'] = 'index/quem_somos/'; 
$route['nao_encontrei'] = 'nao_encontrei/index';

$route['por'] = 'index/por/'; 
$route['por/(:any)'] = 'index/por/$1'; 
$route['por/(:any)/(:any)'] = 'index/por/$1/$2'; 

$route['publicidade'] = 'publicidade/'; 
$route['publicidade/(:any)'] = 'publicidade/$1'; 
$route['publicidade/(:any)/(:any)'] = 'publicidade/$1/$2'; 

$route['index/set_log'] = 'index/set_log/'; 
$route['index/set_log/(:any)'] = 'index/set_log/$1'; 
$route['index/set_log/(:any)/(:any)'] = 'index/set_log/$1/$2'; 


$route['imagens/'] = 'imagens';
$route['imagens/(:any)'] = 'imagens/$1';
$route['imagens/(:any)/(:any)'] = 'index/$1/$2';
$route['images/'] = 'index/e404';
$route['images/(:any)'] = 'index/e404';
$route['images/(:any)/(:any)'] = 'index/e404';
$route['images/(:any)/(:any)/(:any)'] = 'index/e404';
$route['images/(:any)/(:any)/(:any)/(:any)'] = 'index/e404';

$route['apple/'] = 'index/e404';
$route['apple/(:any)'] = 'index/e404';
$route['apple/(:any)/(:any)'] = 'index/e404';
$route['apple/(:any)/(:any)/(:any)'] = 'index/e404';
$route['apple/(:any)/(:any)/(:any)/(:any)'] = 'index/e404';

$route['portais_novo/'] = 'index/e404';
$route['portais_novo/(:any)'] = 'index/e404';
$route['portais_novo/(:any)/(:any)'] = 'index/e404';
$route['portais_novo/(:any)/(:any)/(:any)'] = 'index/e404';
$route['portais_novo/(:any)/(:any)/(:any)/(:any)'] = 'index/e404';

$route['index/qrcode'] = 'index/qrcode/'; 
$route['index/qrcode/(:any)'] = 'index/qrcode/$1'; 
$route['index/qrcode/(:any)/(:any)'] = 'index/qrcode/$1/$2'; 

$route['imoveis/noticias.php'] = 'index/noticias/'; 
$route['blog'] = 'index/'; 
$route['noticias'] = 'index/'; 
$route['noticias/(:any)'] = 'index/';
$route['noticias.php'] = 'index/'; 
$route['blog/(:any)'] = 'index/';
$route['blog/(:any)/(:any)'] = 'index/';

$route['cidades'] = 'cidades/'; 
$route['cidades/cidade_json'] = 'cidades/cidade_json'; 
$route['cidades/cidade_json/(:any)'] = 'cidades/cidade_json/$1'; 
$route['cidades/busca_cidade_json/(:any)'] = 'cidades/busca_cidade_json/$1'; 

$route['imobiliarias'] = 'imobiliarias/index'; 
$route['imoveis/imobiliarias.php'] = 'imobiliarias/index'; 
$route['imobiliarias/imobiliarias_lista/'] = 'imobiliarias/imobiliarias_lista/';
$route['imobiliarias/imobiliarias_lista/(:any)'] = 'imobiliarias/imobiliarias_lista/$1';

$route['imovel/(:any)'] = 'imovel/index/$1';
$route['imovel/(:any)/(:any)'] = 'imovel/index/$1/$2';
$route['imovel/(:any)/(:any)/(:any)'] = 'imovel/index/$1/$2/$3';
$route['imovel/(:any)/(:any)/(:any)/(:any)'] = 'imovel/index/$1/$2/$3/$4';
$route['imovel/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'imovel/index/$1/$2/$3/$4/$5';
$route['imovel'] = 'imoveis/resultado'; 

$route['principal'] = 'imoveis/principal'; 
$route['principal/(:any)'] = 'imoveis/principal/$1'; 
$route['principal/(:any)/(:any)'] = 'imoveis/principal/$1/$2'; 

$route['cidades'] = 'cidades/'; 
$route['cidades/(:any)'] = 'cidades/$1'; 
$route['cidades/(:any)/(:any)'] = 'cidades/$1/$2'; 

$route['set_log'] = 'log/set_estatistica/'; 
$route['log/(:any)'] = 'log/$1/'; 
$route['log/(:any)/(:any)'] = 'log/$1/$2/'; 
$route['set_estatistica'] = 'log/set_estatistica/'; 

$route['funcoes/(:any)'] = 'funcoes/$1'; 
$route['funcoes/(:any)/(:any)'] = 'funcoes/$1/$2'; 
$route['funcoes/(:any)/(:any)/(:any)'] = 'funcoes/$1/$2/$3'; 
$route['funcoes/(:any)/(:any)/(:any)/(:any)'] = 'funcoes/$1/$2/$3/$4'; 

$route['imoveis'] = 'imoveis/index'; 
$route['imoveis/(:any)'] = 'imoveis/index/$1'; 
$route['imoveis/(:any)/(:any)'] = 'imoveis/index/$1/$2'; 
$route['imoveis/(:any)/(:any)/(:any)'] = 'imoveis/index/$1/$2/$3'; 
$route['imoveis/(:any)/(:any)/(:any)/(:any)'] = 'imoveis/index/$1/$2/$3/$4'; 
$route['imoveis/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'imoveis/index/$1/$2/$3/$4/$5';
$route['imoveis/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'imoveis/index/$1/$2/$3/$4/$5/$6'; 
$route['imoveis/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'imoveis/index/$1/$2/$3/$4/$5/$6/$7'; 


$route['imobiliaria'] = 'imoveis/imobiliaria'; 
$route['imobiliaria/(:any)'] = 'imoveis/imobiliaria/$1'; 
$route['imobiliaria/(:any)/(:any)'] = 'imoveis/imobiliaria/$1/$2'; 
$route['imobiliaria/(:any)/(:any)/(:any)'] = 'imoveis/imobiliaria/$1/$2/$3'; 
$route['imobiliaria/(:any)/(:any)/(:any)/(:any)'] = 'imoveis/imobiliaria/$1/$2/$3/$4'; 
$route['imobiliaria/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'imoveis/imobiliaria/$1/$2/$3/$4/$5'; 
$route['imobiliaria/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'imoveis/imobiliaria/$1/$2/$3/$4/$5/$6'; 
$route['imobiliaria/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'imoveis/imobiliaria/$1/$2/$3/$4/$5/$6/$7'; 

$route['por_empresa'] = 'index/por_empresa'; 
$route['por_empresa/(:any)'] = 'index/por_empresa/$1'; 
$route['por_empresa/(:any)/(:any)'] = 'index/por_empresa/$1/$2'; 
$route['por_empresa/(:any)/(:any)/(:any)'] = 'index/por_empresa/$1/$2/$3'; 
$route['por_empresa/(:any)/(:any)/(:any)/(:any)'] = 'index/por_empresa/$1/$2/$3/$4'; 
$route['por_empresa/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'index/por_empresa/$1/$2/$3/$4/$5'; 
$route['por_empresa/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'index/por_empresa/$1/$2/$3/$4/$5/$6'; 
$route['por_empresa/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'index/por_empresa/$1/$2/$3/$4/$5/$6/$7'; 
$route['por_empresa/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'index/por_empresa/$1/$2/$3/$4/$5/$6/$7/$8'; 

$route['funcoes/(:any)'] = 'funcoes/$1'; 
$route['funcoes/(:any)/(:any)'] = 'funcoes/$1/$2'; 

$route['encontre'] = 'nao_encontrei/index';

$route['comprar'] = 'index/comprar';
$route['compra'] = 'index/comprar';
$route['aluga'] = 'index/alugar';
$route['alugar'] = 'index/alugar';
$route['anuncie'] = 'index/anuncie';
$route['anuncie/(:any)'] = 'index/anuncie/$1';
$route['anuncie/(:any)/(:any)'] = 'index/anuncie/$1/$2';
//$route['imoveis/mapa'] = 'imoveis/mapa'; 
//$route['imoveis/mapa/(:any)'] = 'imoveis/mapa/$1'; 
$route['favoritos'] = 'index/favoritos'; 
$route['favoritos/(:any)'] = 'index/favoritos/$1'; 

$route['contato'] = 'contato/index'; 
$route['contato/teste_mensagem'] = 'contato/teste_mensagem'; 
$route['contato/nao_encontrei'] = 'contato/nao_encontrei'; 
$route['contato/teste_nao_encontrei'] = 'contato/teste_nao_encontrei'; 
$route['email_indica'] = 'contato/index/email_indica'; 
$route['email_indica/(:any)'] = 'contato/index/email_indica/$1'; 
$route['envio_formulario_imovel'] = 'contato/index/envio_formulario_imovel'; 
$route['envio_formulario_imovel/(:any)'] = 'contato/index/envio_formulario_imovel/$1'; 
$route['consulta_cadastro'] = 'contato/consulta_cadastro'; 


$route['index/pesquisa'] = 'index/pesquisa'; 
$route['index/pesquisa/(:any)'] = 'index/pesquisa/$1'; 
$route['index/pesquisa/(:any)/(:any)'] = 'index/pesquisa/$1/$2'; 
$route['index/verifica_favoritos'] = 'index/verifica_favoritos'; 
$route['index/set_estatistica'] = 'index/set_estatistica'; 
$route['index/set_destaques'] = 'index/set_destaques'; 
$route['index/set_publicidade'] = 'index/set_publicidade'; 
$route['index/set_publicidade/(:any)'] = 'index/set_publicidade/$1'; 
$route['index/favorito'] = 'index/favorito'; 
$route['index/favorito/(:any)'] = 'index/favorito/$1'; 

//$route['index/(:any)'] = 'index/$1'; 
//$route['index/(:any)/(:any)'] = 'index/$1/$2'; 
//$route['index/(:any)/(:any)/(:any)'] = 'index/$1/$2/$3'; 
//$route['index/pesquisa/(:any)'] = 'index/pesquisa/$1'; 

$route['xml/completo.xml'] = 'xml/completo';
$route['xml/(:any)'] = 'index/$1'; 
$route['xml/(:any)/(:any)'] = 'index/$1/$2'; 
$route['xml/(:any)/(:any)/(:any)'] = 'index/$1/$2/$3'; 

$route['set_form'] = 'imoveis/set_form/'; 

$route['get_itens'] = 'imoveis/get_itens/'; 
$route['get_itens/(:any)'] = 'imoveis/get_itens/$1'; 
$route['get_itens/(:any)/(:any)'] = 'imoveis/get_itens/$1/$2'; 

$route['imovel_modal/(:any)'] = 'imoveis/imovel_modal/$1'; 

$route['pesquisa'] = 'imoveis/pesquisa/'; 
$route['pesquisa/(:any)'] = 'imoveis/pesquisa/$1'; 
$route['pesquisa/(:any)/(:any)'] = 'imoveis/pesquisa/$1/$2'; 

$route['get_images_por_imovel/(:any)'] = 'imovel/get_fotos/$1'; 
$route['get_politica'] = 'imovel/get_politica'; 
$route['get_termos'] = 'imovel/get_termos'; 
$route['menu'] = 'imoveis/menu'; 
$route['menu_principais'] = 'imoveis/menu_principais'; 
$route['relacionados'] = 'imovel/get_relacionados'; 

$route['(:any)'] = 'imoveis/index/$1'; 
$route['(:any)/(:any)'] = 'imoveis/index/$1/$2'; 
$route['(:any)/(:any)/(:any)'] = 'imoveis/index/$1/$2/$3'; 
$route['(:any)/(:any)/(:any)/(:any)'] = 'imoveis/index/$1/$2/$3/$4'; 


$route['default_controller'] = "imoveis";
$route['404_override'] = 'imoveis/e404';


/* End of file routes.php */
/* Location: ./application/config/routes.php */
