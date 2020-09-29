<div class="container pagina-favoritos">
    <div class="linha-favoritos">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h4>Imóveis salvos como <span class="glyphicon glyphicon-star"></span> favoritos por você.</h4>
                <button class="btn btn-topo limpar-favoritos" data-toggle="modal" type="button" ><span class="glyphicon glyphicon-star"></span> <span class="hidden-xs hidden-sm pull-right">&nbsp;Remover dos Favoritos</span></button>
                <button class="btn btn-topo indique" data-toggle="modal" data-target="#modal-indicar" type="button" ><img src="<?php echo base_url();?>images/icones/contato_branco.png" class="img-icone"> <span class="hidden-xs hidden-sm pull-right">&nbsp;Indique estes imóveis</span></button>
                <button class="btn btn-topo imprimir" type="button" ><img src="<?php echo base_url();?>images/icones/imprimir.png" class="img-icone"> <span class="hidden-xs hidden-sm pull-right">&nbsp;Imprimir todos</span></button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <ul class="list-group resultado">
                <?php echo $lista;?>
            </ul>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-indicar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Indique estes imóveis </h4>
            </div>
            <div class="modal-body">
                <div class="formulario">
                    <form method="post" action="<?php echo base_url();?>index/email_indica">
                        <input type="hidden" name="imoveis" class="imoveis" value="">
                        <input type="hidden" name="redirect" class="" value="imovel">
                        <input type="text" name="validador" class="validador" value="">
                        <input type="hidden" name="local" value="i5">
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