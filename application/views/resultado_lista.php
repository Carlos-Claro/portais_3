<?php
//echo isset($filtro_total) ? $filtro_total : '';
?>
<div class="container">
    <?php 
    if ( isset($filtro) ) :
        ?>
        <div class="col-lg-3 col-sm-3 col-md-3 col-xs-12">
            <?php
            echo $filtro;
            ?>
        </div>
        <?php
    else :
        //echo $filtro;
    endif;
    ?>
    
    
    
    <?php
    echo isset($listagem) ? $listagem : '';
    ?>
    <?php 
    echo isset($pagination)? $pagination : '';
    ?>
    
</div>