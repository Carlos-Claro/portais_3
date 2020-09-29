<!DOCTYPE html>
<html>
    <head>
        <title>Corretor feliz</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width">
        <style type="text/css">
            *{ margin:0px; padding: 0px; font-family: Arial;}
        </style>
    </head>
    <body style="margin: 0px; padding: 0px;">
        <center style="margin: 0px; padding: 0px;">
            <div style="width: 600px;">
                <div style="height: 100px; background-color: #0A4689; color:white;" >
                    <h1>Seja você também um<br> Corretor de Imóveis Feliz</h1>
                </div>
                <div>
                    <img style="margin-bottom: -10px;" src="http://isaojose.com/m/guianovo/images/email/corretor-feliz.jpg"  alt="corretor-feliz" title="corretor-feliz" />
                </div>
                <div style="height: auto; background-color: #CDD7F5;" >
                    <div>
                        <p style="padding:40px 20px 0px 60px; text-align:justify; color:#005190; font-size: 26px; ">Somos a  Rede PortaisImobiliarios.com e <br> estamos chegando em sua cidade!</p>
                    </div>
                    <div>
                        <p style="padding:40px 20px 0px 60px; text-align:justify; color:#6C707B; font-size: 22px; ">Em <?php echo $cidade['titulo']; ?> acesse:</p>
                        <p style="padding:0px 20px 0px 60px; text-align:justify; color:#F3353D; font-size: 20px; "><?php echo str_replace('http://', '', $cidade['portal']); ?></p>
                        <br><br>
                        <p style="padding:0px 20px 0px 60px; text-align:justify; color:#4C83B6; font-size: 16px; ">Hoje estamos presentes em 260 cidades do Brasil.</p>
                        <br>
                        <p style="padding:0px 20px 0px 60px; text-align:justify; color:#4C83B6; font-size: 16px; ">
                            Lembrando que  os 260 portais são integrados, ou seja, com um unico <br> anúncio sua empresa poderá aparecer em todas as cidades.
                        </p>
                        <br><br>
                    </div>
                </div>
                <div style="height: auto; background-color:#0C57AC; color:white;" >
                    <p style="padding-top:20px;"><span style="color:#FFFF00; font-size: 26px;">7 meses grátis</span> (Somente para os primeiros cadastrados na cidade)</p>
                    <br>
                </div>
                <div style="height: auto; background-color:#CDD7F5; color:black;" >
                    <div style="padding:50px 20px 0px 60px;">
                        <div style="float:left;">
                            <a href="http://www.portaisimobiliarios.com.br/contrato.php" style="background-color:#CD0000; color:white; text-decoration: none; padding: 10px 20px; font-size: 26px;">
                                Cadastre-se já!
                            </a>
                        </div>
                        <div style="float:right; margin-right: 100px;">
                            <p style="color:#0C5996; text-align: left;">Código da promoção:</p>
                            <p style="text-align: left;">WR8TEGX2FSA</p>
                        </div>
                        <div class="clear:both;"></div>
                        <div>
                            <p style="padding:90px 0px 0px 0px; text-align:justify; font-size: 15px; ">
                                Após o cadastro, envie o  contrato assinado com sua logo.<br>
                                Obs.: Para se cadastrar é imprescindível CRECI.
                            </p>
                            <br><br>
                        </div>
                    </div>
                </div>
                <div style="height: auto; background-color:#0C57AC; color:white; margin-top: -20px;" >
                    <h2 style="padding-top:10px"><?php echo str_replace('http://', '', $cidade['portal']); ?></h2>
                    <br>
                    <p style="font-size: 14px;">
                        Um serviço POW Internet (41) 3382-1581 - vendas01@pow.com.br
                    </p>
                    <br>
                </div>
                <div style="padding:20px 0px 20px 0px;">
                    <div style="float: left; margin-left: 0px;">
                       <img src=" <?php echo $cidade['portal'].'/imagens/'.$cidade['logo']; ?>" />
                    </div>
                    <div style="float: right;">
                        <img src="http://isaojose.com/m/guianovo/images/email/portais-imobiliarios.jpg" alt="portais-imobiliarios" title="portais-imobiliarios">
                        <img src="http://isaojose.com/m/guianovo/images/email/pow-internet.jpg" alt="pow-internet" title="pow-internet">
                    </div>
                    <div style="clear: both;"></div>
                </div>
            </div>
        </center>
    </body>
</html>
