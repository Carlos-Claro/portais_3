
<?php if ( isset($function) && $function == 'mapa' ) :
//    $lat_lng = explode(', ',$mapa);
    $center_array = array('lat' => $mapa[1], 'lng' => $mapa[0]);
    $center_map = '{lat: '.$mapa[1].', lng: '.$mapa[0].'}';
    ?> 
<script type="text/javascript">
function initialize() {
var mapOptions = {
    zoom: 14,
    center: <?php echo $center_map;?>,
    mapTypeId: google.maps.MapTypeId.ROADMAP,
    scrollwheel: false

  }
  //center : new google.maps.LatLng(<?php //echo $mapa;?>)
  var map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
   var image = '<?php echo base_url();?>imagens/imoveis_marcador.png';
  var myLatLng = new google.maps.LatLng(<?php echo $mapa[1].','.$mapa[0];?>);
  var beachMarker = new google.maps.Marker({
      position: <?php echo $center_map;?>,
      map: map,
      icon: image
  });
}
</script>  
<?php endif; ?>
<div>
<?php
echo $conteudo;
?>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBbkOd1YLc7Yr3MEG8ZnrP5eWpOJqSP6XA&callback=initMap"></script>
<script type="text/javascript">
        initialize();
</script>  
</div>
