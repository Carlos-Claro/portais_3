<div class="container">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h1>Pesquisar imóveis</h1>
            <p class="descricao">Este é um poderoso mecanismo de pesquisa que permitirá encontrar imóvel que procura facilmente. Para isso utilize os campos abaixo, indicando sua preferência. Este mecanismo irá buscar, em nosso banco de dados, os imóveis que atendem as características informadas. </p>

            <h2>Por <?php echo $tipo;?></h2>
            <p class="text-info">Escolha uma opção abaixo para visualizar os imóveis ofertados.</p>
            <?php 
            if ( isset($lista) ):
                ?>
            <ul class="list-unstyled">
                <?php
                foreach ($lista as $l) :
                    if ( $tipo == 'Tipo' ) :
                        $titulo = '<span class="pull-left"><strong>'.$l->descricao.'</strong> '.$acao['titulo'].' em '.$cidade['titulo'].'&nbsp;&nbsp;&nbsp;</span> '.( isset ( $l->qtde ) ? '<span class="badge pull-left">'.$l->qtde.'</span>' : '');
                    else :
                        if ( $acao['link'] == 'venda' ) :
                            $titulo = '<span class="pull-left">Imóvel no <strong>'.$l->descricao.'</strong> '.$acao['titulo'].' em '.$cidade['titulo'].'&nbsp;&nbsp;&nbsp;</span> <span class="badge pull-left">'.$l->qtde.'</span>';
                        else:
                            $titulo = '<span class="pull-left">Imóvel '.$acao['titulo'].' no <strong>'.$l->descricao.'</strong> em '.$cidade['titulo'].'&nbsp;&nbsp;&nbsp;</span> <span class="badge pull-left">'.$l->qtde.'</span>';
                        endif;
                    endif;
                    $array_replace = array('<strong>','</strong>','<span class="badge pull-left">','</span>','<span class="pull-left">','</span>');
                    ?>
                    <li class="col-lg-4 col-sm-4 col-md-6 col-xs-12" ><a class="list-group-item " href="<?php echo base_url().( ($tipo == 'Tipo') ? $l->id.'-' : 'imoveis-' ).$acao['link'].'-'.$cidade['link'].( $tipo == 'Bairro' ? '-'.$l->id : '' );?>" title="<?php echo str_replace($array_replace, '', $titulo);?>"><?php echo $titulo;?></a></li>
                    <?php 
                endforeach;
                ?>
            </ul>
                <?php
            endif;
            ?>
        </div>
    </div>
</div>

<?php

