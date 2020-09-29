<div class="container-fluid">
    <div class="row fundo">
        <div class="col-lg-1 col-sm-1 col-md-1 col-xs-1">
            
        </div>
        <div class="col-lg-7 col-md-7 col-sm-11 col-xs-11">
            <div class="alinha-texto ">
            <?php
            if( ! $cobrar ) 
            {
            ?>
            <p class="texto-fundo-transparente border-radius-all">
                <b>
                    Corra em direção ao sucesso!
                </b>
                <br />
                <br />

                    Aqui você tem soluções para ir
                    com tranquilidade ainda mais longe.
            </p>
            <div class="hidden-sm hidden-xs"><br><br><br></div><br>
            <div class="texto-fundo-transparente-verde border-radius-top">
                   Seus imóveis divulgados gratuitamente por 6 meses
            </div>
            <div class="subtexto-transparente-verde  border-radius-bottom">
                <a href="http://www.portaisimobiliarios.com.br/contrato.php">Clique e faça já o cadastro com o código WR8TEGX2FSA</a>
            </div>
            
        </div>
            <?php
            }
            else
            {
            ?>
            <p class="texto-fundo-transparente border-radius-top">
                <b>
                    Amplie as possibilidades de vender<br />
                    ou alugar seus imóveis
                    
                </b>
            </p>
            <div class="subtexto-transparente border-radius-bottom">
                <p>
                    <b>
                    Aqui você recebe contatos reais <br />
                    Tem atendimento descomplicado e sem espera
                    </b>
                </p>
            </div>
            <div class="hidden-sm hidden-xs"><br><br><br></div><br>
            <div class="subtexto-transparente  border-radius-all">
                
                    <h3>
                    Tudo isso para você ir <br />
                    mais rápido e mais longe!
                    </h3>
                <br>
            </div>
            </div>
            <?php
            }
            ?>
        
            <br>
            <div class="hidden-lg hidden-md col-sm-12 col-xs-12 formulario-estatico">
               <form class="form" data-toggle="validator" action="<?php echo base_url();?>anuncie" method="post">
                    <h3 class="text-center">
                        Quero falar com um consultor. 
                    </h3>
                    <div class="radio">
                        <label>
                            <input type="radio" name="consultor" value="corretor" id="corretor" checked="checked">
                            Corretor de imóveis 
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="consultor" value="imobiliaria" id="imobiliaria">
                            Imobiliária 
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="consultor" value="construtor" id="construtor">
                            Construtor 
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="consultor" value="proprietario" id="proprietario"> 
                            Proprietário 
                        </label>
                    </div>
                    <div class="form-group email_erro">
                        <label for="email">E-mail :</label>
                        <input type="text" name="email" id="email" class="form-control" placeholder="E-mail" aria-describedby="email_help">
                        <span id="email_help" class="help-block">
                           Campo obrigatório
                        </span>
                    </div>
                    <div class="form-group" id="nome_erro">
                        <label for="nome">Nome :</label>
                        <input type="text" name="nome" id="nome" class="form-control" placeholder="Nome" aria-describedby="email_help">
                        <span id="email_help" class="help-block">
                           Campo obrigatório
                        </span>
                    </div>
                    <div class="form-group" id="telefone_erro">
                        <label for="cidade">Cidade :</label>
                        <input type="text" name="cidade" id="cidade" class="form-control" placeholder="Cidade" aria-describedby="cidade_help">
                    </div>
                    <div class="form-group telefone_erro">
                        <label for="telefone">Telefone :</label>
                        <input type="text" name="telefone" id="telefone" class="form-control" placeholder="Telefone" aria-describedby="telefone_help">
                        <span id="telefone_help" class="help-block">
                           Campo obrigatório
                        </span>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="condicoes" checked="checked">
                            Aceito receber novidades e notícias da Rede Portais Imobiliários.
                        </label>
                    </div>
                    <button class="btn btn-warning btn-block">Receba mais informações</button>
                </form>
            </div>
            <br>
        </div>
        <div class="col-lg-4 col-md-4 hidden-sm hidden-xs formulario-height">
            <div class="formulario-estatico formulario-normal-topo" id="formulario-estatico">
                <form class="form" data-toggle="validator" action="<?php echo base_url();?>anuncie" method="post">
                    <h4 class="text-center">
                        Quero falar com um consultor. 
                    </h4>
                    <div class="radio">
                        <label>
                            <input type="radio" name="consultor" value="corretor" id="corretor" checked="checked">
                            Corretor de imóveis 
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="consultor" value="imobiliaria" id="imobiliaria">
                            Imobiliária 
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="consultor" value="construtor" id="construtor">
                            Construtor 
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input type="radio" name="consultor" value="proprietario" id="proprietario"> 
                            Proprietário 
                        </label>
                    </div>
                    <div class="form-group">
                        <input type="text" name="email" id="email" class="form-control" placeholder="E-mail">
                        <div class="hidden border-radius-bottom text-center" id="email_erro">O campo e-mail é obrigatório</div>
                    </div>
                    <div class="form-group">
                        <input type="text" name="nome" id="nome" class="form-control" placeholder="Nome">
                        <div class="hidden border-radius-bottom text-center" id="nome_erro">O Campo nome é obrigatório</div>
                    </div>
                    <div class="form-group">
                        <input type="text" name="cidade" id="cidade" class="form-control" placeholder="Cidade">
                    </div>
                    <div class="form-group">
                        <input type="text" name="telefone" id="telefone" class="form-control" placeholder="Telefone">
                        <div class="hidden border-radius-bottom text-center" id="telefone_erro">Campo telefone é obrigatório</div>
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="condicoes" checked="checked">
                            Aceito receber novidades e notícias da Rede Portais Imobiliários.
                        </label>
                    </div>
                    <button class="btn btn-warning btn-block">Receba mais informações</button>
                    <input type="hidden" name="validador" value="" />
                </form>
                <hr>
                <a class="btn btn-danger btn-block" href="http://www.portaisimobiliarios.com.br/contrato.php" >Já sei como funciona, quero contratar! </a>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <?php 
        if( $cobrar ) 
        {
        ?>
        <div class="col-lg-8 col-md-8 col-sm-12 hidden-xs">
            <p><h3>Promoção por tempo limitado, aproveite:</h3></p>
            <ul class="oportunidades">
              <li class="oportunidadesli" onmouseover="select(this);" onclick="go();" onmouseout="out(this);">
                   <span class="titulo linha">
                       65 imóveis
                   </span><br>
                   <div class="linha">
                        
                       <span class="preco_novo">
                           R$99,00
                       </span>
                   </div>
                   <button class="btn_contratar">
                       <strong>
                           Contratar
                       </strong>
                   </button>
               </li>
               <li class="oportunidadesli2" onmouseover="select(this);" onclick="go();" onmouseout="out(this);">
                   <span class="titulo linha">
                       300 imóveis
                   </span><br>
                   <div class="linha">
                       <span class="preco_novo">
                           R$189,00
                       </span>
                   </div>
                   <button class="btn_contratar">
                       <strong>
                           Contratar
                       </strong>
                   </button>
               </li>
               <li class="oportunidadesli" onmouseover="select(this);" onclick="go();" onmouseout="out(this);">
                   <span class="titulo linha">
                       2.600 imóveis
                   </span><br>
                   <div class="linha">
                       <span class="preco_novo">
                           R$249,00
                       </span>
                   </div>
                   <button class="btn_contratar">
                       <strong>
                           Contratar
                       </strong>
                   </button>
               </li>
               <li class="oportunidadesli2" onmouseover="select(this);" onclick="go();" onmouseout="out(this);">
                   <span class="titulo linha">
                       10.000 imóveis
                   </span><br>
                   <div class="linha">
                       <span class="preco_novo">
                           R$331,20
                       </span>
                   </div>
                   <button class="btn_contratar">
                       <strong>
                           Contratar
                       </strong>
                   </button>
               </li>
           </ul>  
        </div>
        <div class="hidden-lg hidden-md hidden-sm col-xs-12 text-center">
            <ul class="oportunidades" style="width:300px">
              <li class="oportunidadesli" onmouseover="select(this);" onclick="go();" onmouseout="out(this);">
                   <span class="titulo linha">
                       65 imóveis
                   </span>
                  <br>
                   <div class="linha">
                       <span class="preco_novo">
                           R$ 99,00
                       </span>
                   </div>
                   <button class="btn_contratar">
                       <strong>
                           Contratar
                       </strong>
                   </button>
               </li>
               <li class="oportunidadesli2" onmouseover="select(this);" onclick="go();" onmouseout="out(this);">
                   <span class="titulo linha">
                       300 imóveis
                   </span><br>
                   <div class="linha">
                       <span class="preco_novo">
                           R$ 189,00
                       </span>
                   </div>
                   <button class="btn_contratar">
                       <strong>
                           Contratar
                       </strong>
                   </button>
               </li>
               <li class="oportunidadesli" onmouseover="select(this);" onclick="go();" onmouseout="out(this);">
                   <span class="titulo linha">
                       2.600 imóveis
                   </span>
                   <br>
                   <div class="linha">
                       <span class="preco_novo">
                           R$249,00
                       </span>
                   </div>
                   <button class="btn_contratar">
                       <strong>
                           Contratar
                       </strong>
                   </button>
               </li>
               <li class="oportunidadesli2" onmouseover="select(this);" onclick="go();" onmouseout="out(this);">
                   <span class="titulo linha">
                       10.000 imóveis
                   </span><br>
                   <div class="linha">
                       <span class="preco_novo">
                           R$331,20
                       </span>
                   </div>
                   <button class="btn_contratar">
                       <strong>
                           Contratar
                       </strong>
                   </button>
               </li>
           </ul>    
        </div>
        <?php 
        }
        else
        {
        ?>
        <div class="col-lg-8 col-sm-8 col-md-8 col-xs-8">
            <p>
                <h2 style="color:#09893B">Oba tem promoção!</h2>
                Aproveite já e faça bons negócios
            </p>
            <p>
                Promoção para uso gratuito por 6 meses.<br />
            </p>
            <p>
                * Limitada e válida somente para novos cadastros.
            </p>
            <a href="http://www.portaisimobiliarios.com.br/contrato.php">
                <button class="btn btn-warning btn-large">
                    Contrate aqui
                </button>
            </a>
        </div>
        <?php   
        }        
        ?>
        <div class="col-lg-8 col-sm-8 col-md-8 col-xs-8">
            <hr>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
            <div class="row">
                <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                    <h3>
                        Vantagens exclusivas aos anunciantes
                    </h3>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-4 col-xs-6 text-center vantagens">
                    <div clas="panel panel-default vantagens">
                        <h4 class="panel-heading"><img src="images/anuncie_publico_qualificado.jpg" alt="Público Qualificado"/></h4>
                        <h4>
                            Público Qualificado
                        </h4>
                        <p>
                            Pessoas que realmente tem interesse em imóveis
                        </p>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-4 col-xs-6 text-center vantagens">
                    <div clas="panel panel-default vantagens">
                        <h4 class="panel-heading"><img src="images/anuncie_facil_de_usar.jpg" alt="Fácil de usar"/></h4>
                        <h4>
                            Fácil de usar
                        </h4>
                        <p>
                            Com apenas alguns cliques seu imóvel está publicado!
                        </p>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-4 col-xs-6 text-center vantagens">
                    <div clas="panel panel-default vantagens">
                        <h4 class="panel-heading"><img src="images/anuncie_facil_interacao.jpg" alt="Fácil interação"/></h4>
                        <h4>
                            Fácil interação
                        </h4>
                        <p>
                            Navegação descomplicada, adaptável a diversas telas: desktop, tablet, smartphones, etc
                        </p>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-4 col-xs-6 text-center vantagens">
                    <div clas="panel panel-default vantagens">
                        <h4 class="panel-heading"><img src="images/anuncie_integracao.jpg" alt="Integração"/></h4>
                        <h4>
                            Integração
                        </h4>
                        <p>
                            Publicação automática de imóveis através da integração com diversos softwares
                        </p>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-4 col-xs-6 text-center vantagens">
                    <div clas="panel panel-default vantagens">
                        <h4 class="panel-heading"><img src="images/anuncie_maior_divulgacao.jpg" alt="Maior divulgação"/></h4>
                        <h4>
                            Maior divulgação
                        </h4>
                        <p>
                            Rede integrada com mais de 270 portais de diversas cidades do país, gerando mais possibilidades de negócios
                        </p>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-4 col-xs-6 text-center vantagens">
                    <div clas="panel panel-default vantagens">
                        <h4 class="panel-heading"><img src="images/anuncie_planos_que_cabem_no_bolso.jpg" alt="Planos que cabem no seu bolso"/></h4>
                        <h4>
                            Planos que cabem no seu bolso
                        </h4>
                        <p>
                            Diversas opções de planos, que permitem a divulgação de todos os seus imóveis pelo melhor preço
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><!--Container-->
<br><br><br><br><br><br>