<?php 
if ( isset($function) && $function == 'mapa' ) :
    $lat_lng = explode(', ',$mapa);
    $center_array = array('lat' => $lat_lng[0], 'lng' => $lat_lng[1]);
    $center_map = '{lat: '.$lat_lng[0].', lng: '.$lat_lng[1].'}';
    ?> 
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAhg7BcZP_pASkncQjFi8UugGWio0WrYk4"></script>
<script type="text/javascript">
function initialize() {
  var mapOptions = {
    zoom: 14,
    center: <?php echo $center_map;?>,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  }
  var center : new google.maps.LatLng(<?php echo $mapa;?>)
  var map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
   var image = '<?php echo base_url();?>imagens/imoveis_marcador.png';
  var myLatLng = new google.maps.LatLng(<?php echo $mapa;?>);
  var beachMarker = new google.maps.Marker({
      position: <?php echo $center_map;?>,
      map: map,
      icon: image
  });
}
$(document).ready(function(){
    initialize();
});;
</script>  
<?php endif; ?>
<div id="mapa">
    <?php
    echo $conteudo;
    ?>
</div>
