<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd"> 
<html lang="pt-br">
<head>
	<meta charset="UTF-8" >
        <title><?php if ( isset( $titulo ) ) : echo $titulo; endif; ?></title>
        <?php
        //echo $includes
        ?>
<style>
    
button,input,select,textarea {  margin: 0;  font-family: inherit;  font-size: 100%;}
button,input {  line-height: normal;}
button,select {  text-transform: none;}
button,html input[type="button"],input[type="reset"],input[type="submit"] {  cursor: pointer;  -webkit-appearance: button;}
button[disabled],html input[disabled] {  cursor: default;}
input[type="checkbox"],input[type="radio"] {  padding: 0;  box-sizing: border-box;}
input[type="search"] {  -webkit-box-sizing: content-box;     -moz-box-sizing: content-box;          box-sizing: content-box;  -webkit-appearance: textfield;}
input[type="search"]::-webkit-search-cancel-button,input[type="search"]::-webkit-search-decoration {  -webkit-appearance: none;}
button::-moz-focus-inner,input::-moz-focus-inner {  padding: 0;  border: 0;}
textarea {  overflow: auto;  vertical-align: top;}
table {  border-collapse: collapse;  border-spacing: 0;}
body {  font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;  font-size: 14px;  line-height: 1.428571429;  color: #333333;  background-color: #ffffff;}
input,button,select,textarea {  font-family: inherit;  font-size: inherit;  line-height: inherit;}
button,input,select[multiple],textarea {  background-image: none;}
a {  color: #428bca;  text-decoration: none;}
a:hover,a:focus {  color: #2a6496;  text-decoration: underline;}
a:focus {  outline: thin dotted #333;  outline: 5px auto -webkit-focus-ring-color;  outline-offset: -2px;}
img {  vertical-align: middle;}
.img-responsive {  display: block;  height: auto;  max-width: 100%;}
.img-thumbnail {  display: inline-block;  height: auto;  max-width: 100%;  padding: 4px;  line-height: 1.428571429;  background-color: #ffffff;  border: 1px solid #dddddd;  border-radius: 4px;  -webkit-transition: all 0.2s ease-in-out;          transition: all 0.2s ease-in-out;}
.img-circle {  border-radius: 50%;}
hr {  margin-top: 20px;  margin-bottom: 20px;  border: 0;  border-top: 1px solid #eeeeee;}
p {  margin: 0 0 10px;}
small {  font-size: 85%;}
h1,h2,h3,h4,h5,h6,.h1,.h2,.h3,.h4,.h5,.h6 {  font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;  font-weight: 500;  line-height: 1.1;}
h1 small,h2 small,h3 small,h4 small,h5 small,h6 small,.h1 small,.h2 small,.h3 small,.h4 small,.h5 small,.h6 small {  font-weight: normal;  line-height: 1;  color: #999999;}
h1,h2,h3 {  margin-top: 20px;  margin-bottom: 10px;}
h4,h5,h6 {  margin-top: 10px;  margin-bottom: 10px;}
h1,.h1 {  font-size: 36px;}
h2,.h2 {  font-size: 30px;}
h3,.h3 {  font-size: 24px;}
h4,.h4 {  font-size: 18px;}
h5,.h5 {  font-size: 14px;}
h6,.h6 {  font-size: 12px;}
h1 small,.h1 small {  font-size: 24px;}
h2 small,.h2 small {  font-size: 18px;}
h3 small,.h3 small,h4 small,.h4 small {  font-size: 14px;}
ul,ol {  margin-top: 0;  margin-bottom: 10px;}
ul ul,ol ul,ul ol,ol ol {  margin-bottom: 0;}
.list-inline {  padding-left: 0;  list-style: none;}
.list-inline > li {  display: inline-block;  padding-right: 5px;  padding-left: 5px;}
.container {  padding-right: 15px;  padding-left: 15px;  margin-right: auto;  margin-left: auto;}
.container:before,.container:after {  display: table;  content: " ";}
.container:after {  clear: both;}
.container:before,.container:after {  display: table;  content: " ";}
.container:after {  clear: both;}
.row {  margin-right: -15px;  margin-left: -15px;}
.row:before,.row:after {  display: table;  content: " ";}
.row:after {  clear: both;}
.row:before,.row:after {  display: table;  content: " ";}
.row:after {  clear: both;}
.col-xs-1,.col-xs-2,.col-xs-3,.col-xs-4,.col-xs-5,.col-xs-6,.col-xs-7,.col-xs-8,.col-xs-9,.col-xs-10,.col-xs-11,.col-xs-12,.col-sm-1,.col-sm-2,.col-sm-3,.col-sm-4,.col-sm-5,.col-sm-6,.col-sm-7,.col-sm-8,.col-sm-9,.col-sm-10,.col-sm-11,.col-sm-12,.col-md-1,.col-md-2,.col-md-3,.col-md-4,.col-md-5,.col-md-6,.col-md-7,.col-md-8,.col-md-9,.col-md-10,.col-md-11,.col-md-12,.col-lg-1,.col-lg-2,.col-lg-3,.col-lg-4,.col-lg-5,.col-lg-6,.col-lg-7,.col-lg-8,.col-lg-9,.col-lg-10,.col-lg-11,.col-lg-12 {  position: relative;  min-height: 1px;  padding-right: 15px;  padding-left: 15px;}
.col-xs-1,.col-xs-2,.col-xs-3,.col-xs-4,.col-xs-5,.col-xs-6,.col-xs-7,.col-xs-8,.col-xs-9,.col-xs-10,.col-xs-11 {  float: left;}
.col-xs-1 {  width: 8.333333333333332%;}
.col-xs-2 {  width: 16.666666666666664%;}
.col-xs-3 {  width: 25%;}
.col-xs-4 {  width: 33.33333333333333%;}
.col-xs-5 {  width: 41.66666666666667%;}
.col-xs-6 {  width: 50%;}
.col-xs-7 {  width: 58.333333333333336%;}
.col-xs-8 {  width: 66.66666666666666%;}
.col-xs-9 {  width: 75%;}
.col-xs-10 {  width: 83.33333333333334%;}
.col-xs-11 {  width: 91.66666666666666%;}
.col-xs-12 {  width: 100%;}
table {  max-width: 100%;  background-color: transparent;}
th {  text-align: left;}
.table {  width: 100%;  margin-bottom: 20px;}
.btn {  display: inline-block;  padding: 6px 12px;  margin-bottom: 0;  font-size: 14px;  font-weight: normal;  line-height: 1.428571429;  text-align: center;  white-space: nowrap;  vertical-align: middle;  cursor: pointer;  border: 1px solid transparent;  border-radius: 4px;  -webkit-user-select: none;     -moz-user-select: none;      -ms-user-select: none;       -o-user-select: none;          user-select: none;}
.btn:focus {  outline: thin dotted #333;  outline: 5px auto -webkit-focus-ring-color;  outline-offset: -2px;}
.btn:hover,.btn:focus {  color: #333333;  text-decoration: none;}
.btn:active,.btn.active {  background-image: none;  outline: 0;  -webkit-box-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);          box-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);}
.btn.disabled,.btn[disabled],fieldset[disabled] .btn {  pointer-events: none;  cursor: not-allowed;  opacity: 0.65;  filter: alpha(opacity=65);  -webkit-box-shadow: none;          box-shadow: none;}
.btn-default {  color: #333333;  background-color: #ffffff;  border-color: #cccccc;}
.btn-default:hover,.btn-default:focus,.btn-default:active,.btn-default.active,.open .dropdown-toggle.btn-default {  color: #333333;  background-color: #ebebeb;  border-color: #adadad;}
.btn-default:active,.btn-default.active,.open .dropdown-toggle.btn-default {  background-image: none;}
.btn-default.disabled,.btn-default[disabled],fieldset[disabled] .btn-default,.btn-default.disabled:hover,.btn-default[disabled]:hover,fieldset[disabled] .btn-default:hover,.btn-default.disabled:focus,.btn-default[disabled]:focus,fieldset[disabled] .btn-default:focus,.btn-default.disabled:active,.btn-default[disabled]:active,fieldset[disabled] .btn-default:active,.btn-default.disabled.active,.btn-default[disabled].active,fieldset[disabled] .btn-default.active {  background-color: #ffffff;  border-color: #cccccc;}
.btn-primary {  color: #ffffff;  background-color: #428bca;  border-color: #357ebd;}
.btn-primary:hover,.btn-primary:focus,.btn-primary:active,.btn-primary.active,.open .dropdown-toggle.btn-primary {  color: #ffffff;  background-color: #3276b1;  border-color: #285e8e;}
.btn-primary:active,.btn-primary.active,.open .dropdown-toggle.btn-primary {  background-image: none;}
.btn-primary.disabled,.btn-primary[disabled],fieldset[disabled] .btn-primary,.btn-primary.disabled:hover,.btn-primary[disabled]:hover,fieldset[disabled] .btn-primary:hover,.btn-primary.disabled:focus,.btn-primary[disabled]:focus,fieldset[disabled] .btn-primary:focus,.btn-primary.disabled:active,.btn-primary[disabled]:active,fieldset[disabled] .btn-primary:active,.btn-primary.disabled.active,.btn-primary[disabled].active,fieldset[disabled] .btn-primary.active {  background-color: #428bca;  border-color: #357ebd;}
.thumbnail {  display: inline-block;  display: block;  height: auto;  max-width: 100%;  padding: 4px;  line-height: 1.428571429;  background-color: #ffffff;  border: 1px solid #dddddd;  border-radius: 4px;  -webkit-transition: all 0.2s ease-in-out;          transition: all 0.2s ease-in-out;}
.thumbnail > img {  display: block;  height: auto;  max-width: 100%;}
a.thumbnail:hover,a.thumbnail:focus {  border-color: #428bca;}
.thumbnail > img {  margin-right: auto;  margin-left: auto;}
.thumbnail .caption {  padding: 9px;  color: #333333;}
.clearfix:before,.clearfix:after {  display: table;  content: " ";}
.clearfix:after {  clear: both;}
.pull-right {  float: right !important;}
.pull-left {  float: left !important;}
body, h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {    font-family: calibri, arial;    }
embed {    z-index: 1;}
.topo {    position: fixed;    bottom: 5px;    right: 10px;}
h3 {    font-size: 20px;}
.titulo_horizontal {    font-size: 20px;    color: #B41D21;}
.container {    padding: 0px;}
.padding10 {    padding: 10px;}
.padding-no-right {    padding-right: 0px !important;}
li.menu-principal .row {    padding-top: 0px;    padding-bottom: 0px;    margin-top: 3px;    margin-bottom: 3px;}
.menu-login {    color: #777777;    margin-bottom: 0px !important;}
.menu-login .divider {    height: 12px;    margin-bottom: 0px;    width: 1px;    border-right: 1px solid #777777;    padding: 0px;}
.divider-modo1 {    background: rgb(255,255,255);    background: -moz-linear-gradient(top,  rgba(255,255,255,1) 0%, rgba(204,204,204,1) 100%);    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(255,255,255,1)), color-stop(100%,rgba(204,204,204,1)));    background: -webkit-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(204,204,204,1) 100%);    background: -o-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(204,204,204,1) 100%);    background: -ms-linear-gradient(top,  rgba(255,255,255,1) 0%,rgba(204,204,204,1) 100%);    background: linear-gradient(to bottom,  rgba(255,255,255,1) 0%,rgba(204,204,204,1) 100%);    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#cccccc',GradientType=0 );    height: 20px;}
.divider-modo2 {    background: url("<?php echo base_url();?>images/divider-modo-2.png") center center transparent no-repeat;    height: 25px;    margin-top: -5px;}
.titulo_resultado {    color: #FF0000;    font-size: 18px;}
.lista .col-sm-6, .lista .col-md-3, .lista .col-lg-3{    padding-left: 8px;    padding-right: 8px;}
.thumbnail {    height: 410px;    border: none;    background-color: #EEEEEE;    padding-left: 10px;    padding-right: 10px;    border-radius: 5px;    }
.thumbnail img {    height: 160px;}
.print .thumbnail {    height: auto;}
.print .thumbnail img{    max-height: auto;    min-height: auto;    height: auto;    }
.thumbnail .caption {    height: 150px;    background-color: #FFFFFF;}
.lista .thumbnail:hover {    background-color: #DEDEDE;}
.thumbnail h5 {    font-weight: bold !important;    margin-top: 5px;    color: #6c6c6c;    font-size: 18px;}
.thumbnail h4 {    font-weight: bold !important;    margin-top: 5px;    margin-bottom: 0px;    color: #6c6c6c;    font-size: 11px;}
.thumbnail p {    color: #9A9A9A;    margin-bottom: 2px;}
p.border-bottom {    border-bottom: 1px solid #DDD;}
.thumbnail p strong{    color: #666666;}
p span.bold {    font-weight: 900;}
hr {    border-color: #9A9A9A;    margin: 5px 0px;}
.thumbnail a:hover {    text-decoration: none;}
.anuncie .thumbnail {    background-color: transparent;}
.venda h3, .venda_locacao h3 {    color: #FF0000;    margin: 0px;    padding: 10px 0px;    padding-bottom: 3px;    border-bottom: 4px solid #FF0000;        font-size: 18px;}
.locacao h3, .locacao_locacao_dia h3 {    color: #1E3385;    margin: 0px;    padding: 10px 0px;    padding-bottom: 3px;    border-bottom: 4px solid #1E3385;        font-size: 18px;}
.locacao_dia h3 {    color: #0F0;    margin: 0px;    padding: 10px 0px;    padding-bottom: 3px;    border-bottom: 4px solid #0F0;        font-size: 18px;}
/* btn personalizado */.bg-pow{    background-color: #1E3385;    color: #FFFFFF;    padding-top: 5px;    padding-bottom: 5px;}
a{	color:#1d72a9;}
footer {    width: 100%;}
footer .container {    margin: 0 auto;}
footer .nav > li > a, footer .nav > li > a:hover, footer .nav > li > a:focus {   margin-bottom: 10px;   padding: 0px 5px;   background-color: transparent;}
footer .row {    margin-top: 10px;    margin-bottom: 10px;}
.btn-enviar{    margin-left: 15px; }
.alinhar-centro{    text-align: center;}
p,h3,h4,h5,h2,button{    font-family:Arial,Helvetica,sans-serif;}
.fundo-thumbnail{   background-color: #fff;    }
a { border: none;} a img{border:none;}
</style>        
</head>
<body width="680">
    <table style="width:680px; margin:0 auto;">
        <tr>
            <td style="text-align: center; width: 290px;">
                <!-- <div style="margin:0 auto; width: 680px;"> -->
                <a href="<?php echo base_url();?>" style="float: left;">
                    <center><img src="<?php echo str_replace('/m','',base_url()).'imagens/'.$logo;?>" alt="Os imóveis e as Imobiliárias da cidade reunidos aqui!" title="Os imóveis e as Imobiliárias da cidade reunidos aqui!" class="img-responsive"></center>
                </a>
                <!-- </div> -->
            </td>
            <td style="width:100px;">
                <a href="http://www.portaisimobiliarios.com.br" title="Visite a rede de portais imobiliários" target="_blank" style="float: left;" >
                    <center><img src="<?php echo base_url();?>/images/logo_portais_imobiliarios.png" alt="Visite a rede de portais imobiliários" title="Visite a rede de portais imobiliários" class="img-responsive"></center>
                </a>
            </td>
            <td style="text-align: right; width: 190px;">
                <a class="pull-right" target="_blank" title="POW Internet" href="http://www.pow.com.br" sty>
                    Um Serviço:
                    <img class="img-responsive" style="margin-top: 10px; width:100px;height: auto;" target="_blank" alt="POW Internet" src="http://www.icuritiba.com//images/logopow2.png">
                </a>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <div class="divider-modo1"></div>
            </td>
        </tr>
    </table>
    <table style="width:680px; margin:0 auto;">
        <tr>
            <td>
            <?php 
            echo isset($conteudo) ? $conteudo : '';
            ?>
            </td>
        </tr>
    </table>
    <hr>
    <footer>
        <div class="divider-modo2">
        </div>
        <div class="row container">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 consideracoes">
                <center><p class="text-center"><i>"Graça seja convosco, e paz, da parte de Deus nosso Pai, e do Senhor Jesus Cristo." 1CO 1:3<br>O sangue de Jesus Cristo, filho do Deus vivo, te purifica de todos os pecados.</i></p></center>
            </div>
        </div>
    </footer>
</body>
</html>