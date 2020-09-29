<!DOCTYPE html>
<html>
    <head>
        <title><?php $assunto['usuario'];?></title>
        <style>
            html,body
            {
                margin:0px;
                padding:0px;
                background:#EDF0F3;
                color: #333;
            }
            .topo
            {
                padding:20px 25px;
                text-align: center;
                background:#fff;
            }
            .img-responsive
            {
                max-width: 100%;
            }
            .container
            {
                width:70%;
                margin-left: 15%;
            }
            @media only screen and (max-width:768px)
            {
                .container
                {
                    width:100%;
                    margin-left: 0px;
                }
            }
            .conteudo
            {
                background: #FFFFFF;
                border-radius: 0px 0px 8px 8px;
                box-sizing: border-box;
                margin-bottom: 20px;
            }
            .body
            {
                padding:20px 20px;
            }
            .btn
            {
                padding:10px 15px;
                border-radius: 5px;
                border:1px #00eaff solid;
                text-decoration: none;
                color:#000;
                margin-top: 15px;
                margin-bottom: 15px;
                background: #32c5d2;
                transition: .2s;
            }
            .btn:hover
            {
                background:#00eaff;
                border:1px solid #32c5d2;
            }
            .title
            {
                background:#F6F8FA;
                font-size: 22px;
                padding:20px 20px;
            }
            .helper
            {
                font-size: 12px
            }
            .helper-empresa
            {
                font-size: 12px;
                color:#aaa;
            }
            .rodape
            {
                font-size: 10px;
                margin-bottom:30px
            }
            .text-center
            {
                text-align: center;
            }
            .titulo
            {
                font-size: 18px;
                border-bottom: 1px solid #F6F8FA;
                margin-bottom: 15px;
            }
        </style>
    </head>
    <body>
        <div class="topo">
            <img class="img-responsive" src="<?php echo $logo; ?>" style="background-color:#FFF;"><img src="http://www.portaisimobiliarios.com.br/imagens/logo.png">
        </div>
        <div class="container">
            <div class="conteudo">
                <div class="title">
                    Prezado(a), <?php echo $dados['remetente']['nome'];?>
                </div>
                <div class="body">
                    <p>Data: <?php echo date('d/m/Y H:i');?></p>
                    <p>
                        <b><?php echo $assunto['usuario']; ?></b><br>
                    </p>
                    <h3>Sobre o Imóvel:</h3>
                    <img src="<?php echo $image;?>" style="width: 250px; height: 200px;">
                    <p><?php echo $dados['assunto'];?></p>
                    <p><?php echo $imovel->nome;?></p>
                    <p>Melhor Horário para receber ligações:</p>
                    <ul>
                        <?php 
                        foreach ( $dados['horario'] as $parcela ) :
                            echo '<li>'.$parcela.'</li>';
                        endforeach;
                        ?>
                    </ul>
                    <br><br>
                    <a href="<?php echo $link;?>">Clique e Visite novamente</a><br>
                    <?php echo " ou copie esta link no seu navegador: ".$link;?>
                    <h3>Seu contato a imobiliaria: <?php echo $empresa->empresa_nome_fantasia;?>.</h3>
                    <p>Nome do contato na imobiliária: <?php echo $empresa->contato_nome;?></p>
                    <h3>Seus dados:</h3>
                    <p>E-mail: <?php echo $dados['remetente']['email'];?></p>
                    <p>Telefone: <?php echo $dados['remetente']['fone'];?></p>
                    <p>Formas que você informou que gostaria de ser contatado: </p>
                    <ul>
                        <?php 
                        if( $dados['check_email'] == '1' ):
                            echo '<li>E-mail</li>';
                        endif;
                        if( $dados['check_telefone'] == '1' ):
                            echo '<li>Telefone</li>';
                        endif;
                        if( $dados['check_whatsapp'] == '1' ):
                            echo '<li>WhatsApp</li>';
                        endif;
                        ?>
                    </ul>
                    <h3>Mensagem:</h3>
                    <?php
                    echo $dados['mensagem'];
                    ?>
                    <br><br>
                    <a href="<?php echo $link;?>">Clique e confira o imóvel contatado</a><br>
                    <?php echo " ou copie esta link no seu navegador: ".$link;?>
                    <p>Esse email foi enviado a partir do portal <?php echo substr($cidade->portal, 11);?> em função de seu cadastro em portal integrante da rede PortaisImobiliarios.com.br</p>
                    <p>Caso tenha dificuldades com este e-mail, nos informe, entrando em contato com a POW para mais informações.</p>
                    <br>
                    <p>Agradecemos a atenção e nos colocamos a disposição em caso de dúvidas.</p>
                    <br>
                    <p>Atenciosamente,</p>
                    <br>
                    <p>Comercial</p>
                    <p>POW Internet</p>
                    <p>comercial@pow.com.br</p>
                    <p>Fone: (41) 3382-1581</p>
                    <p><b>Nos informe se a imobiliária não entrar em contato com você.</b></p>
                </div>
            </div>
            <div class="rodape text-center">
                Este é um e-mail automático.
            </div>
        </div>
    </body>
</html> 
