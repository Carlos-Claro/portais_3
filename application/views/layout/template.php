<!DOCTYPE html!>
<html lang=pt-br>
<head>
	<meta charset="UTF-8" >
        <link rel="apple-touch-icon" href="<?php echo base_url();?>images/favicon.png">
        <link rel="apple-touch-icon-precomposed" href="<?php echo base_url();?>images/favicon.png"> 
        <link rel="icon" href="<?php echo base_url();?>images/favicon.png" type="image/x-icon">
        <link rel="shortcut icon" href="<?php echo base_url();?>images/favicon.png" type="image/x-icon">
        <link rel="shortcut icon" href="<?php echo base_url();?>images/favicon.png" type="image/vnd.microsoft.icon">
	<meta name="description" content="<?php if ( isset( $description ) ) : echo $description; endif; ?>" />
	<meta name="keywords" content="<?php if ( isset( $keywords ) ) : echo $keywords; endif;?>" />
	<meta name="author" content="POW Internet - http://www.powinternet.com" />
	<title><?php if ( isset( $titulo ) ) : echo $titulo; endif; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php 
	echo ( isset($includes) ? $includes : '' );
        echo ( isset($analytics) ? $analytics : '' );
?>
        
</head>
<body>
    <img src="<?php echo base_url().'images/beta_s1.png';?>" style="position: absolute; top: 0px; right: 0px; z-index: 10;"> 
    <div data-role="page">
        <div data-role="header" data-theme="c" class="fixed">
            <div class="brand">
                <a href="<?php echo base_url();?>">
                <img src="<?php echo str_replace('/m','',base_url()).'imagens/'.$logo;?>">
                </a>
                <!--<p>Os imóveis e as Imobiliárias da cidade reunidos aqui!</p>-->
            </div>
        </div> 
    
        <?php
        echo $conteudo;
        ?>
    </div>
</body>
</html>
