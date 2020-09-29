<div class="row">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <?php 
        foreach ( $itens['Venda'] as $tipo => $bairros ):
            ?>
            <!--<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">-->
            <?php
            $valores[] = ['Bairros','Venda'];
            $bairro_link = '';
            foreach( $bairros as $bairro => $valor ):
                $tipo_link = $valor->link_tipo;
                $valores[] = [$bairro,$valor->venda];
                $bairro_link .= '<li class="link-est"><a style="font-size: 13px;" href="'.base_url().$tipo_link.'-venda-'.$cidade['link'].'-'.$valor->link_bairro.'">'.$tipo .' para Venda em '.$cidade['titulo'].' no bairro '.$bairro.'</a></li>';
            endforeach;
            $json = json_encode($valores);
            ?><div class="container">
                <div class="portlet box red-sunglo">
                    <div class="portlet-title" style="margin-top: 30px; font-size: 19px;">
                        <div class="caption font-red">
                            <i class="icon-settings font-red"></i>
                            <span class="caption-subject bold uppercase"><a class="azul-pow c-font-bold" href="<?php echo base_url().$tipo_link.'-venda-'.$cidade['link']; ?>"><?php echo $tipo;?> a Venda em <?php echo $cidade['titulo'];?></a></span>
                        </div>
                        <div class="tools"> 
                            <a href="javascript:;" class="expand" data-original-title="" title=""> </a>
                        </div>
                    </div>
                    <div class="portlet-body portlet-collapsed">
                        <div id="columnchart_values_venda_<?php echo $tipo_link;?>" style="width: 100%; height: 500px;"></div>
                        <ul class="c-sidebar-menu"><?php echo $bairro_link;?></ul>
                    </div>
                </div>
            </div>    
            <script type="text/javascript">

                    google.charts.load('current', {'packages':['corechart']});
                    google.charts.setOnLoadCallback(drawChart);
                    function drawChart() {
                      var data = google.visualization.arrayToDataTable(<?php echo $json;?>);

                      var view = new google.visualization.DataView(data);
                      view.setColumns([0, 1]);

                      var options = {
                        title: '<?php echo $tipo;?> Venda em <?php echo $cidade['titulo'];?>',
//                        width: 600,
                        height: 400,
                        bar: {groupWidth: "95%"},
                        legend: { position: "none" },
                      };
                      var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values_venda_<?php echo $tipo_link;?>"));
                      chart.draw(view, options);
                  }
                    

            </script>
            <?php
            unset($valores,$json);
//            var_dump($json);
        endforeach;
        ?>
</div>
<div class="row">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <?php 
        foreach ( $itens['Alugar'] as $tipo => $bairros ):
            ?>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

            <?php
            $valores[] = ['Bairros','Venda'];
                        $bairro_link = '';

            foreach( $bairros as $bairro => $valor ):
                $valores[] = [$bairro,$valor->locacao];
                $tipo_link = $valor->link_tipo;
                $bairro_link .= '<li class="link-est"><a style="font-size: 13px;" href="'.base_url().$tipo_link.'-locacao-'.$cidade['link'].'-'.$valor->link_bairro.'">'.$tipo .' para Alugar em '.$cidade['titulo'].' no bairro '.$bairro.'</a></li>';
            endforeach;
            $json = json_encode($valores);
            ?>
                <div class="container">
                    <div id="columnchart_values_alugar_<?php echo $tipo_link;?>" style="width: 100%; height: 500px;"></div>
                    <ul class="c-sidebar-menu "><?php echo $bairro_link;?></ul>
                </div>
            </div>
            <script type="text/javascript">

                    google.charts.load('current', {'packages':['corechart']});
                    google.charts.setOnLoadCallback(drawChart);
                    function drawChart() {
                      var data = google.visualization.arrayToDataTable(<?php echo $json;?>);

                      var view = new google.visualization.DataView(data);
                      view.setColumns([0, 1]);

                      var options = {
                        title: '<?php echo $tipo;?> Alugar em <?php echo $cidade['titulo'];?>',
//                        width: 600,
                        height: 400,
                        bar: {groupWidth: "95%"},
                        legend: { position: "none" },
                      };
                      var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values_alugar_<?php echo $tipo_link;?>"));
                      chart.draw(view, options);
                  }
                    

            </script>
            <?php
            unset($valores,$json);
//            var_dump($json);
        endforeach;
        ?>
</div>
<?php
/*

<div class="container">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h1>Valores médios de imóveis, por bairros, ofertados para venda.</h1>
            <h2>Abaixo os valores médios de imóveis nos bairros de <?php echo $cidade['titulo'];?> </h2>
            <?php 
            if ( isset($itens) && $itens['qtde'] > 0 ):
                ?>
            <p class="text-success">Estão listados <?php echo isset($itens['qtde']) ? $itens['qtde'] : 0;?> registros em <?php echo $cidade['titulo'];?>. Os registros de imóveis estão separados por tipo e por bairro. Para ver os imóveis que geraram a média basta clicar no texto.</p>
            <ul class="list-unstyled">
                <?php
                foreach ($itens['itens'] as $l) :
                    $titulo = $l->nome_tipo.' em '.$cidade['titulo'].' no bairro '.$l->nome_bairro.': R$ '.  number_format($l->media, 2, ',', '.').' - quantia avaliada: '.$l->nume_i;
                    ?>
                    <li class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                        <a style="float:none;" rel="nofollow" class="list-group-item clearfix " href="<?php echo base_url().( isset($l->link_tipo) ? $l->link_tipo.'-' : 'imoveis-' ).'venda-'.$cidade['link'].'-'.$l->link_bairro;?>" title="<?php echo $titulo;?>">
                            <?php echo $titulo;?>
                        </a>
                    </li>
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
*/

