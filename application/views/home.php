
<div data-role="content" >
    <?php 
    echo ( isset( $cidade ) ) ? $cidade : '';
    ?>
    <ul data-role="listview" data-tipo="negocio" class="negocio">
        <?php
        foreach ($tipo as $k => $v ) 
        {
            if ( $v['valor'] > 0 ) :
                echo '<li data-theme="b" data-role="li_'.$k.'" class="li_'.$k.'">';
                echo '<a data-item="'.$k.'" class="li_negocio li_'.$k.'" href="'.$v['link'].'">'.$v['titulo'].' <span class="ui-li-count"> '.number_format($v['valor'], 0, ',', '.').' </span></a>';
                echo '</li>';
           endif;
        }
        ?>
    </ul>
    
</div>
<div  data-role="content" >
    <ul data-role="listview">
        <li>
            <a href="<?php echo base_url();?>index.php/imoveis/cidades" class="bt_cidades" data-theme="c">
                <center>Outras Cidades</center>
            </a>
        </li>
    </ul>
    
</div>
