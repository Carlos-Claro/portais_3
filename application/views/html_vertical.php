<?php 
if ( $favorito ) :
    ?>
</div><!-- arquivo que fecha container para abrir favoritos -->
<div class="fundo-azul">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                <button class="btn btn-default voltar pull-right" type="button"><span class="glyphicon glyphicon-chevron-left pull-left"></span> <span class="hidden-xs pull-right">&nbsp;Voltar</span></button>
                <button class="btn btn-default imprimir_todos pull-right" type="button" ><span class="glyphicon glyphicon-print pull-left"></span> <span class="hidden-xs pull-right">&nbsp;Imprimir</span></button>
                <button class="btn btn-default indicar pull-right" data-toggle="modal" data-target="#modal-indicar" type="button" ><span class="glyphicon glyphicon-envelope pull-left"></span> <span class="hidden-xs pull-right">&nbsp;indique esses imóveis</span></button>
                <button style="margin-bottom:3px;" class="btn btn-default remover-favorito pull-right" type="button" data-item=""><span class="glyphicon glyphicon-star-empty pull-left"></span> <span class="hidden-xs pull-right">&nbsp;Remover todos</span></button>
            </div>
        </div>
    </div>
</div>
<div class="container"><br>
    <?php
endif;
?>
    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
        <?php 
        if ( isset($topo) && count($topo) > 0 ) :
            foreach( $topo as $t ) :
                echo $t;
            endforeach;
        endif;
        //echo $topo; 
        ?>
    </div>
    <div class="<?php echo $largura;?>">
        <?php 
        if ( ! empty( $titulo ) ) :
            ?>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h1 class="titulo_resultado text-center"><?php echo $titulo;?></h1>
                <hr>
            </div>
            <?php
        endif;
        echo $empresa;
        ?>
        <div class="row">
            <?php 
            echo $qtde;
            echo $ordenacao;
            ?>
        </div>
        <ul class="media-list">
            <?php 
            if ( isset($itens) )
            {
                $conta_banner = 0;
                foreach( $itens as $contador => $item )
                {
                    if ( $contador > 0 &&  ! ( $contador % 4 ) )
                    {
                        echo isset($vertical[($conta_banner)]) ? $vertical[($conta_banner)] : '';
                        unset($vertical[($conta_banner)]);
                        $conta_banner++;
                    }
                    echo $item;
                }
                if ( isset($vertical) && count($vertical) > 0 )
                {
                    foreach( $vertical as $v )
                    {
                        echo $v;
                    }
                }
            }
            //echo $destaques;
            //echo $vertical;
            //echo $itens;
            ?>
        </ul>
        <?php 
        echo $paginacao; 
        if ( ! empty($relacionados) ) :
            ?>
            <div class="alert ">
                <div class="row">
                    <?php echo $relacionados; ?>
                </div>
            </div>
            <?php
        endif;
        ?>
        <a href="<?php echo base_url();?>encontre" title="Não encontrou o imóvel que desejava?"><img src="<?php echo base_url();?>images/banner_nao_encontrei2.jpg" alt="Não encontrou o imóvel que desejava?" class="img-responsive"></a>
    </div>
    <?php
    if ( $publicidade ) :
        /*
        ?>
      <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
          <?php 
          echo $lateral_nacional;
          echo $lateral;
          ?>
      </div>
        <?php
         * 
         */
    endif;
    if ( $favorito ) :
        ?>
    <div class="modal fade container" id="modal-indicar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <p class="modal-title" id="myModalLabel">Indique estes imóveis </p>
                    </div>
                <div class="modal-body">
                    <div class="formulario">
                        <form method="post" action="<?php echo base_url();?>index/email_indica">
                            <input type="hidden" name="imoveis" class="imoveis" value="">
                            <input type="text" name="validador" class="validador" value="">
                            <input type="hidden" name="local" value="i4">
                            <div class="form-group"><label>Seu nome: </label><input required type="text" class="form-control" name="remetente[nome]" value="" placeholder="Digite seu nome"></div>
                            <div class="form-group"><label>Seu E-mail: </label><input required type="email" class="form-control" name="remetente[email]" value="" placeholder="Digite seu e-mail"></div>
                            <div class="form-group"><label>Digite o email dos destinatários: (separe varios e-mails por ","(vírgula))</label><textarea required name="emails" class="form-control" placeholder=""></textarea></div>
                            <div class="form-group"><label>Mensagem que acompanha o envio.</label><textarea name="mensagem" class="form-control" placeholder="Mensagem que acompanha o envio."></textarea></div>
                            <div class="form-group"><button class="btn btn-primary" type="submit"> Enviar imóveis </button></div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <?php 
    endif;
    ?>
    <div class="modal fade container" id="modal-contato" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <p class="modal-title" id="myModalLabel">Contato imóvel: <span class="id_imovel"></span> </p>
                </div>
                <div class="modal-body">
                    <div class="formulario">
                        <form method="post" action="<?php echo base_url();?>index/email_contato">
                            <input type="hidden" name="imovel" class="imovel" value="">
                            <input type="hidden" name="assunto" class="assunto" value="Via lista resultados de imóveis">
                            <input type="hidden" name="empresa" class="empresa" value="">
                            <input type="hidden" name="referencia" class="referencia" value="">
                            <input type="hidden" name="redirect" class="redirect" value="">
                            <input type="text" name="validador" class="validador" value="">
                            <input type="hidden" name="local" class="local" value="">
                            <div class="form-group"><label>Seu nome: </label><input required type="text" class="form-control" name="remetente[nome]" value="" placeholder="Digite seu nome"></div>
                            <div class="form-group"><label>Seu E-mail: </label><input required type="email" class="form-control" name="remetente[email]" value="" placeholder="Digite seu e-mail"></div>
                            <div class="form-group"><label>Mensagem: </label><textarea required name="mensagem" class="form-control mensagem" placeholder="Digite sua mensagem"></textarea></div>
                            <div class="form-group"><label><input type="checkbox" name="aceito" value="1" checked> Gostaria de receber noticias e novidades da Rede Portais Imobiliários</label></div>
                            <div class="form-group"><button class="btn btn-primary" type="submit" onclick="_gaq.push([\'_trackEvent\', \'Pergunta_Imobiliaria\', \'Envie_sua_pergunta_form01\']);"> Enviar contato </button></div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
            