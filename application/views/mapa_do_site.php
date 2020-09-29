<style type="text/css">
    .list-group-item {
    height: auto !important;
}
</style>
<div class="container mapa_do_site">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h1>Mapa do Site Portal de Imóveis <?php echo $cidade['titulo'].' - '.  substr($cidade['portal'], 11);?>: </h1>
            <ul class="list-group">
                <li class="list-group-item col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <a href="<?php echo base_url();?>" alt="Página principal">
                        Página Principal
                    </a>
                </li>
                <li class="list-group-item col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <a href="<?php echo base_url();?>" alt="Página de pesquisa">
                        Página de pesquisa
                    </a>
                </li>
                <?php 
                if ( isset($itens['venda']['itens']) && $itens['venda']['itens'] && count( $itens['venda']['itens'] ) > 0 ) :
                    ?>
                    <li class="list-group-item  col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <a href="<?php echo base_url().'imoveis-venda-'.$cidade['link'].'';?>" >
                            Comprar
                        </a>
                        <ul class="list-group">
                            <?php 
                            foreach ( $itens['venda']['itens'] as $venda) :
                                ?>
                                <li class="list-group-item col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-2 col-sm-10 col-sm-offset-2 col-xs-10 col-xs-offset-2">
                                    <a href="<?php echo base_url().'imoveis/'.$cidade['link'].'/venda/'.$venda->link;?>/">
                                    <?php 
                                    echo 'Comprar '.$venda->descricao;
                                    ?>
                                        <span class="badge">
                                            <?php 
                                            echo $venda->qtde;
                                            ?>
                                        </span>
                                    </a>
                                </li>
                                <?php 
                            endforeach;
                            ?>
                        </ul>
                    </li>
                    <?php 
                endif;
                if ( isset($itens['locacao']['itens']) && $itens['locacao']['itens'] && count( $itens['locacao']['itens'] ) > 0 ) :
                    ?>
                    <li class="list-group-item  col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <a href="<?php echo base_url().'imoveis/'.$cidade['link'].'/locacao/';?>" >
                            Alugar
                        </a>
                        <ul class="list-group">
                            <?php 
                            foreach ( $itens['locacao']['itens'] as $venda) :
                                ?>
                                <li class="list-group-item col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-2 col-sm-10 col-sm-offset-2 col-xs-10 col-xs-offset-2">
                                    <a href="<?php echo base_url().'imoveis/'.$cidade['link'].'/locacao/'.$venda->link;?>/">
                                    <?php 
                                    echo 'Alugar '.$venda->descricao;
                                    ?>
                                        <span class="badge">
                                            <?php 
                                            echo $venda->qtde;
                                            ?>
                                        </span>
                                    </a>
                                </li>
                                <?php 
                            endforeach;
                            ?>
                        </ul>
                    </li>
                    <?php 
                endif;
                if ( isset($itens['locacao_dia']['itens']) && $itens['locacao_dia']['itens'] && count( $itens['locacao_dia']['itens'] ) > 0 ) :
                    ?>
                    <li class="list-group-item  col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <a href="<?php echo base_url().'imoveis/'.$cidade['link'].'/locacao_dia/';?>" >
                            Alugar por Dia
                        </a>
                        <ul class="list-group">
                            <?php 
                            foreach ( $itens['locacao_dia']['itens'] as $venda) :
                                ?>
                                <li class="list-group-item col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-2 col-sm-10 col-sm-offset-2 col-xs-10 col-xs-offset-1">
                                    <a href="<?php echo base_url().'imoveis/'.$cidade['link'].'/locacao_dia/'.$venda->link;?>/">
                                    <?php 
                                    echo 'Alugar por Dia '.$venda->descricao;
                                    ?>
                                        <span class="badge">
                                            <?php 
                                            echo $venda->qtde;
                                            ?>
                                        </span>
                                    </a>
                                </li>
                                <?php 
                            endforeach;
                            ?>
                        </ul>
                    </li>
                    <?php 
                endif;
                ?>    
                <li class="list-group-item col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <a href="<?php echo base_url();?>imobiliarias" alt="Imobiliárias">
                        Imobiliárias
                    </a>
                </li>               
                <li class="list-group-item col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <a href="<?php echo base_url();?>encontre" alt="Encontre pra mim">
                        Encontre pra mim
                    </a>
                </li>               
                <li class="list-group-item col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <a href="<?php echo base_url();?>blog" alt="Blog">
                        Blog
                    </a>
                </li>               
                <li class="list-group-item col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <a href="<?php echo base_url();?>anuncie" alt="Anuncie">
                        Anuncie
                    </a>
                </li>   
                <li class="list-group-item col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <a href="<?php echo base_url();?>por/comprar/tipo" alt="Comprar por Tipo">
                        Comprar por Tipo
                    </a>
                </li>   
                <li class="list-group-item col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <a href="<?php echo base_url();?>por/alugar/tipo" alt="Alugar por Tipo">
                        Alugar por Tipo
                    </a>
                </li>   
                <li class="list-group-item col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <a href="<?php echo base_url();?>por/comprar/bairro" alt="Comprar por Bairro">
                        Comprar por Bairro
                    </a>
                </li>   
                <li class="list-group-item col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <a href="<?php echo base_url();?>por/alugar/bairro" alt="Alugar por Bairro">
                        Alugar por Bairro
                    </a>
                </li>   
                <li class="list-group-item col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <a href="<?php echo base_url();?>quem_somos" alt="Quem Somos">
                        Quem Somos
                    </a>
                </li>   
                <li class="list-group-item col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <a href="<?php echo base_url();?>estatistica" alt="">
                        Estatistíca
                    </a>
                </li>   
                <li class="list-group-item col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <a href="<?php echo base_url();?>mapa_do_site" alt="Mapa do Site">
                        Mapa do Site
                    </a>
                </li>   
            </ul>
        </div>
    </div>
</div>