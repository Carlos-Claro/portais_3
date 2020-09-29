    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="jumbotron text-center">
                    <h1><small>Desculpe-nos, mas este imóvel não existe mais...<br>Utilize os links abaixo.</small></h1>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <br>
                            <?php 
                            if ( isset($relacionados) ) :
                                foreach( $relacionados as $link ) :
                                    ?>
                                    <ul>
                                    <?php
                                    echo $link;
                                    ?>
                                    </ul>
                                    <?php
                                endforeach;
                            endif;
                            ?>
                        </div>
                    </div>
            </div>
            
        </div>
    </div>