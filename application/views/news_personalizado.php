<?php 
//var_dump($empresa);
?>
<table style="width:680px; margin:0 auto; ">
    <tr>
        <td colspan="2">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <h2 style="text-align: center;"><?php echo $titulo;?></h2>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <center><img src="<?php echo base_url();?>emails/201509/venturi/topo.jpg" alt="O imóvel que você procura Esta aqui na Venturi"></center>
                        <!-- espaco banner -->
                    </div>
                </div>
        </td>
    </tr>
    <?php if ( isset($image) ) :?>
    <tr>
        <td style="width: 120px;">
            <a href="<?php echo base_url();?>por_empresa/?pow[id_empresa]=<?php echo $empresa->id;?>" alt="veja os imóveis de <?php echo $empresa->nome_fantasia;?>" class="pull-left" style="margin-left: 60px; margin-top: 20px;">
                <img src="http://www.guiasjp.com/paginas/<?php echo $empresa->logo;?>" alt="Logo <?php echo $empresa->nome_fantasia;?>" style="width: 120px !important; height: 90px !important;">
            </a>
        </td>
        <td>
            <div class="thumbnail" style="height: 120px; width: 480px; margin: 0; margin-top: 10px; background-color: #FFFFFF;">
                <div class="caption pull-right" style="width: 97%; height: 103px; margin: 0 auto;">
                    <h3><?php echo $empresa->nome_fantasia;?><small> <?php echo $empresa->creci;?></small></h3>
                    <h4><?php echo $empresa->descricao;?></h4>
                </div>
            </div>
        </td>
    </tr>
    <?php endif; ?>
    <tr>
        <td style="height: 15px;" colspan="2">&nbsp;</td>
    </tr>
</table>
<style type="text/css">
    .thumbnail {
        margin-left: 5px;
    }
    .thumbnail img {
        height: 200px;
        width: 300px;
    }
    td {
        width: 340px;
    }
</style>
<table style="width:680px; margin:0 auto;">
    <?php
    if ( isset($itens) && count($itens) > 0 ) :
        $a = 0;
        foreach ( $itens as $item ) :
            if ( $a == 0 || $a == 2 || $a == 4 || $a == 6 || $a == 8 || $a == 10 ) :
                ?>
                <tr>
                <?php
            endif;
            ?>
            <td>
                <?php 
                $t = $item;
                $t = str_replace('FC', 'F', $t);
                echo $t;?>
            </td>
            <?php
            if ( $a == 1 || $a == 3 || $a == 5 || $a == 7 || $a == 9 || $a == 11 ) :
                ?>
                </tr>
                <tr>
                    <td style="height: 15px;" colspan="2">&nbsp;</td>
                </tr>
                <?php
            endif;
            $a++;
        endforeach;
    endif;
    ?>
</table>
<table style="width:680px; margin: 0 auto; margin-left: 30px;">
    <tr>
        <td style="height: 15px; width: 680px;">
            <div class="divider-modo1"></div>
            &nbsp;
        </td>
    </tr>
    <tr>
        <td style="width: 680px;">
            <center><a href="<?php echo base_url();?>por_empresa/?&pow[id_empresa]=<?php echo $empresa->id;?>" class="btn btn-lg btn-primary " target="_blank" style="margin-bottom: 15px; padding: 20px;">Encontre estes e muitos outros imóveis da <?php echo $empresa->nome_fantasia;?>, Clicando aqui</a></center>
            <center><img src="<?php echo base_url();?>emails/201509/venturi/rodape.png" alt="venturiimoveis.com.br"></center>

        </td>
    </tr>
</table>



