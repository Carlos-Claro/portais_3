<div class="container-fluid nao-encontrei-fundo">
    <div class="container">
        <div class="row">
            <?php 
            if ( $encaminhado ) :
                ?>
            <br><p class="alert alert-info"><b>Observação:</b> <br>Você foi encaminhado para esta página por ser proprietário e buscar anúncio para seu imóvel, nosso portal é voltado apenas para corretores, construtores e imobiliárias. Por favor, utilize os campos abaixo e vamos ajudar a encontrar um bom negócio, junto a nossos parceiros e anunciantes.</p>
                <?php
            endif;
            ?>
            <div class="col-lg-12 col-md-12 col-sm-12 hidden-xs fundo-tranparente">
                <h3><strong class="c-font-white">Não encontrei</strong></h3>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <h2><strong class="c-font-white">Caso não tenha localizado o imóvel que deseja, nós vamos te ajudar!</strong></h2>
                <div class="row vantagens">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <br>
                        <strong>
                            <p>
                                <img src="images/contatos-nao-encontrei-icon.png" width="40" />
                                &nbsp;&nbsp;Preencha o formulário com os detalhes do imóvel que procura.
                            </p>
                        </strong>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <br>
                        <strong>
                            <p>
                                <img src="images/graficos-nao-encontrei-icon.png" width="40" />
                                &nbsp;&nbsp;Após análise o seu pedido será disponibilizado para as imoiliárias de curitba e as demais participantes da rede portaisimobiliarios.com
                            </p>
                        </strong>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <br>
                        <strong>
                            <p>
                                <img src="images/imobiliarias-nao-encontrei-icon.png" width="40" />&nbsp;&nbsp;As imobiliárias entrarão em contato caso possam lhe atender.
                            </p>
                        </strong>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <br>
                        <strong>
                            <p>
                                <img src="images/possiveis-contatos-nao-encontrei-icon.png" width="40" />
                                &nbsp;&nbsp;Seu pedido estará disponível por 30 dias ou até ser respondido por 20 imobiliárias.
                            </p>
                        </strong>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                <div class="row-fluid">
                    <form method="post" action="<?php echo $url;?>" >
                        <div class="row row-normal text-center">
                            <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                                <h3 class="c-font-white">Preencha as informações abaixo:</h3>   
                            </div>
                        </div> 
                        <div class="row row-transparente">
                            <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                                <BR>
                                <div class="form-group">
                                    <input type="email" name="remetente[email]" required class="form-control email" placeholder="Email:">
                                </div>
                            </div>
                            <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                                <div class=" form-group">
                                    <input type="text" name="remetente[nome]" required class="form-control nome" placeholder="Nome:">
                                </div>
                            </div>
                            <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                                <div class="form-group">
                                    <input type="text" name="remetente[telefone]" required class="form-control telefone telefone_0" placeholder="Telefone:">
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-3 col-md-3 col-xs-3">
                                <div class="form-group">
                                    <input type="text" name="remetente[estado]" class="form-control estado" placeholder="UF: (PR)">
                                </div>
                            </div>
                            <div class="col-lg-9 col-sm-9 col-md-9 col-xs-9">
                                <div class="form-group">
                                    <input type="text" name="remetente[cidade]" class="form-control cidade" placeholder="Cidade:">
                                </div>
                            </div>
                            <?php
                                foreach( $filtro as $f ) :
                                    foreach( $f['itens'] as $linha ):
                                        ?>
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" title="clique nas caixas para nos ajudar a melhorar seu retorno">
                                            <div class="form-group">
                                            <?php echo $linha['item'];?>
                                            </div>
                                        </div>
                                        <?php
                                    endforeach;
                                endforeach;
                            ?>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <textarea name="observacao" class="form-control observacao" placeholder="Escreva sua mensagem"></textarea>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="margin-top-10"></div>
<!--                                <div class="portlet light">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <span class="caption-subject bold font-grey-gallery uppercase"> Mais opções </span>
                                            <span class="caption-helper">forneça mais informações sobre seu desejo...</span>
                                        </div>
                                        <div class="tools">
                                            <a href="javascript:;" class="expand"></a>
                                        </div>
                                    </div>
                                    <div class="portlet-body portlet-collapsed" >
                                        <ul class="list-unstyled">
                                            <li class="localidades">
                                                <label class="control-label c-font-uppercase c-font-bold">Cidade / Bairro</label>
                                                <select class="form-control c-square c-theme localidade-select" name="localidade">
                                                    <option value="">Busque a localidade</option>';
                                                </select>
                                                <ul class="c-card-menu-items itens list-unstyled" >
                                                </ul>
                                            </li>
                                            <li class="tipos">
                                                <label class="control-label c-font-uppercase c-font-bold">Tipo do imóvel</label>
                                                <select class="form-control c-square c-theme">
                                                    <option value="">Selecione o tipo</option>
                                                    <option value="andar">Andar</option><option value="apartamento">Apartamento</option><option value="area">Área</option><option value="barracao_galpao">Barracão / Galpão</option><option value="casa">Casa</option><option value="conjunto_comercial">Conjunto Comercial</option><option value="fazenda">Fazenda</option><option value="flat">Flat</option><option value="garagem">Garagem</option><option value="haras">Haras</option><option value="hotel">Hotel</option><option value="kitinete">Kitinete</option><option value="loft">Loft</option><option value="loja">Loja</option><option value="lote_terreno">Lote / Terreno</option><option value="negocio_empresa">Negócio/ Empresa</option><option value="outro">Outro</option><option value="ponto_comercial">Ponto Comercial</option><option value="pousada">Pousada</option><option value="predio">Prédio</option><option value="quarto">Quarto</option><option value="salao">Salão</option><option value="sitio_chacara">Sítio e Chácara</option><option value="sobrado">Sobrado</option>                </select>
                                                <ul class="c-card-menu-items itens list-unstyled" >
                                                </ul>
                                            </li>
                                            <li>
                                                <label class="control-label c-font-uppercase c-font-bold">Faixa de preço</label>
                                                <div class="c-price-range-box input-group">
                                                        <div class="c-price input-group col-md-6 pull-left">
                                                                <span class="input-group-addon c-square c-theme">R$</span>
                                                                <input class="form-control c-square c-theme valor valor-min" placeholder="Minimo" type="text" value="">
                                                        </div>
                                                        <div class="c-price input-group col-md-6 pull-left">
                                                                <span class="input-group-addon c-square c-theme">R$</span>
                                                                <input class="form-control c-square c-theme valor valor-max" placeholder="Maximo" type="text" value="">
                                                        </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>-->
                            </div>
                            
                                <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 form-group">
                                    <label class="aceite"><input type="checkbox" value="1" name="aceito" checked="checked" class="aceito">&nbsp;&nbsp; Aceito receber novidades e notícias da Rede Portais Imobiliários.</label>
                                    <input type="text" name="validador" value="" class="hidden"> 
                                </div>
                                <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 form-group">
                                    <button type="submit" class="btn btn-warning btn-block g-recaptcha" data-sitekey="<?php echo $sitekey;?>" type="submit" data-size="invisible" data-callback="onSubmit">Enviar solicitação</button>

                                </div>
                                <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 form-group">
                                    <label>&nbsp;Ao enviar, você concorda com os <a class="termos-de-uso" data-toggle="modal" data-target="#modal-termo" href="#">Termos de Uso</a>, <a class="termos-de-uso" data-toggle="modal" data-target="#modal-politica" href="#">Política de Privacidade</a> e recebimento de sugestões de imóveis.</label>
                                </div>
                        </div>
                    </div>
                </form>
                <?php
                if ( $insert ) :
                    ?>
                    <div class="modal fade container" id="modal-insert" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <h3>Obrigado por enviar sua solicitação</h3>
                                    <p>Enviamos sua mensagem as nossas imobiliárias associadas e existindo a disponibilidade, retornaram sua Ligação.</p>
                                    <p>A Rede Portais Imobiliários, agradece seu contato.</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                                </div>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
                    <script type="text/javascript">
                    $(function(){
                        $('#modal-insert').modal('show');
                    });

                    </script>
                    <?php
                endif;
                ?>
            </div>
            <div class="col-lg-1 col-md-1 hidden-sm hidden-xs"></div>
        </div>
    </div>
</div>
