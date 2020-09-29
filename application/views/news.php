<table style="width:760px; margin:0 auto;">
    <tr>
        <td>
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <h2 style="text-align: center;">Confira alguns imóveis selecionados para você</h2>
                        <center><a href="<?php echo base_url();?>" class="btn btn-lg btn-primary " target="_blank">CLIQUE AQUI PARA BUSCAR MAIS IMÓVEIS NO PORTAL   <?php echo substr($cidade['portal'], 11)?> </a></center>
                    </div>
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <td style="height: 15px;">&nbsp;</td>
    </tr>
</table>
<style type="text/css">
    .thumbnail {
        margin-left: 5px;
    }
    .thumbnail img {
        height: auto;
        max-height: 200px;
        width: 100%;
    }
</style>
<table style="width:680px; margin:0 auto;">
    <?php
    if ( isset($itens) && count($itens) > 0 ) :
        $a = 0;
        foreach ( $itens as $item ) :
            if ( $a == 0 || $a == 2 || $a == 4 || $a == 6 ) :
                echo '<tr>';
            endif;
            ?>
            <td>
                <?php 
                $t = $item;
                $t = str_replace('650_F', 'TM', $t);
                echo $t;?>
            </td>
            <?php
            if ( $a == 1 || $a == 3 || $a == 5 || $a == 7 ) :
                echo '</tr><tr>
        <td style="height: 15px;">&nbsp;</td>
    </tr>';
            endif;
            $a++;
        endforeach;
    endif;
    ?>
</table>
<div class="divider-modo1"></div>

<table style="width:680px; margin:0 auto;">
    <tr>
        <td>
            <?php 
            
            if ( isset($link_tipo) ) :
                $url = $link_tipo['url'];
                unset($link_tipo['url']);
                ?>
            <center >
                <?php
                $conta = 0;
                $qtde_tipo = 5;
                foreach ( $link_tipo as $chave => $valor ): 
                    if ( isset($tipo_consulta) )
                    {
                        if ( $tipo_consulta != $chave )
                        {
                            $valor['itens'] = array();
                        }
                        else
                        {
                            $qtde_tipo = 8;
                        }
                    }
                    if ( count($valor['itens']) > 0 && $conta <= 1 ):
                        ?>
                        <h2>+ Imóveis para <?php echo $valor['titulo'];?> em <?php echo $cidade['titulo'];?></h2>
                                    <?php 
                                    $c_o = 0;
                                    foreach($valor['itens'] as $v):
                                        $array = array('[oq]','[tipo]');
                                        $array_b = array($chave,$v->link);
                                        if ( $c_o < $qtde_tipo )
                                        echo '<a href="'.  str_replace($array, $array_b, $url).'" title="'.$valor['titulo'].' '.$v->descricao.' '.$cidade['titulo'].'" class="btn btn-default" style="margin-left:5px; margin-bottom:5px;">'.$v->descricao.' <br> + '.  number_format(round(($v->qtde),-1),0,',','.').' Imóveis</a>';
                                        
                                        $c_o++;
                                    endforeach;
                                    ?>
                                    </ul>
                                </li>
                            </ul>
                        </li> 
                        <?php
                        $conta++;
                    endif;
                endforeach;
            endif;
            ?>
            </center>
        </td>
    </tr>
</table>
<div class="divider-modo1"></div>
<table style="width:680px; margin:0 auto;">
    <tr>
        <td>
            <?php 
            if ( isset($link_bairro) && count($link_bairro['itens']) > 0 ) :
                ?>
            <center><h2 >Bairros mais buscados em <?php  echo $cidade['titulo']; ?></h2> </center>
            <center >
                <?php
                foreach ($link_bairro['itens'] as $link) :
                ?>
                    <a href="<?php echo base_url().'imoveis/'.$link_bairro['link_cidade'].'/0/0/'.$link->id;?>" class="btn btn-default" style="width:25%; margin-bottom:5px;">Imóveis no <?php echo $link->descricao;?></a>
                <?php
                endforeach; 
            endif;
            ?>
            </center>
        </td>
    </tr>
</table>
<table width="100%">
    <tr>
        <td style="height: 15px;">&nbsp;</td>
    </tr>
    <tr>
        <td>
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <center><a href="<?php echo base_url();?>" class="btn btn-lg btn-primary " target="_blank" style="margin-bottom: 15px;">TEMOS O IMÓVEL QUE VOCÊ BUSCA NO PORTAL <?php echo substr($cidade['portal'], 11)?>, CLIQUE E CONFIRA </a></center>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <center><table border="0" cellpadding="20" cellspacing="0"  style="background:#EEEEEE  !important; border-top:1px solid #DDDDDD; clear:both;" id="canspamBarWrapper">
                            <tr>
                                <td align="center" valign="top" style="padding-bottom:0;">
                                    <table border="0" cellpadding="0" cellspacing="0" width="660" id="canspamBar">
                                        <tr>
                                            <td align="left" valign="top" style="color:#505050 !important;font-family:Verdana,Arial,Sans !important;font-size:11px !important;font-weight:normal !important;font-style:normal !important;text-decoration:none !important;vertical-align:top !important;text-align:center !important;">Enviado para *|EMAIL|* &mdash; 
                                                <a href="*|ABOUT_LIST|*" style="font-family:Verdana,Arial,Sans !important;font-size:11px !important;font-weight:normal !important;font-style:normal !important;text-decoration:underline !important;color:#404040 !important;"><em>Por que estou recebendo esse email?</em>
                                                </a>
                                                <br/>
                                                <a href="*|UNSUB|*" style="font-family:Verdana,Arial,Sans !important;font-size:11px !important;font-weight:normal !important;font-style:normal !important;text-decoration:none !important;color:#369 !important;">Remover meu email do cadastro</a> |
                                                <a href="*|UPDATE_PROFILE|*" style="font-family:Verdana,Arial,Sans !important;font-size:11px !important;font-weight:normal !important;font-style:normal !important;text-decoration:none !important;color:#369 !important;">Alterar meu Cadastro</a>*|LIST:ADDRESSLINE|*<br>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            </table></center>
                    </div>
                </div>
            </div>
        </td>
    </tr>
</table>



