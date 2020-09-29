<div class="container">
    <div class="row">
        <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
            <?php 
            if ( isset($topo) && count($topo) > 0 ) :
                foreach( $topo as $t ) :
                    echo $t;
                endforeach;
            endif;
            //echo $topo;
            ?>
            <h2 class="titulo_horizontal">Destaque de Imóveis <?php echo $cidade;?></h2>
            <div class="row lista">
                <?php
                for ( $a = 0; $a < 4; $a++ ) :
                    echo isset($destaques[$a]) ? $destaques[$a] : '';
                endfor;
                ?>
            </div>
            <?php 
            if ( isset($meio) && count($meio) > 0 ) :
                foreach( $meio as $m ) :
                    echo $m;
                endforeach;
            endif;
            //echo $meio; 
            ?>
            <div class="row lista">
                <?php
                if ( count($destaques) >= 8 ) :
                    for ( $a = 4; $a < 8; $a++ ) :
                        echo isset($destaques[$a]) ? $destaques[$a] : '';
                    endfor;
                endif;
                    
                ?>
            </div>
            <?php 
            if ( isset($meio_2) && count($meio_2) > 0 ) :
                foreach( $meio_2 as $m2 ) :
                    echo $m2;
                endforeach;
            endif;
            //echo $meio_2; 
            ?>
            <div class="row lista">
                <?php
                if ( count($destaques) >= 12 )
                for ( $a = 8; $a < 12; $a++ ) :
                    echo isset($destaques[$a]) ? $destaques[$a] : '';
                endfor;
                ?>
            </div>
            <?php 
            if ( isset($rodape) && count($rodape) > 0 ) :
                foreach( $rodape as $r ) :
                    echo $r;
                endforeach;
            endif;
            //echo $rodape;
            ?>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
            <?php
            if ( isset($lateral_nacional) && count($lateral_nacional) > 0 ) :
                foreach( $lateral_nacional as $ln ) :
                    echo $ln;
                endforeach;
            endif;
            //echo $lateral_nacional;
            if ( isset($vertical) && count($vertical) > 0 ) :
                foreach( $vertical as $v ) :
                    echo $v;
                endforeach;
            endif;
            //cho $vertical;
            if ( isset($lateral) && count($lateral) > 0 ) :
                foreach( $lateral as $l ) :
                    echo $l;
                endforeach;
            endif;
            //echo $lateral;
            ?>
        </div>
    </div>
</div>