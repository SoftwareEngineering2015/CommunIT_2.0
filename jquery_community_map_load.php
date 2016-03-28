<script>
var map;
var myLatLng = {lat: 41.8074072, lng: -88.3847939};

function initMap() {

  map = new google.maps.Map(document.getElementById('community_map'), {
    zoom: 10,
    center: myLatLng
  });

  var marker = new google.maps.Marker({
    position: myLatLng,
    map: map,
    title: 'Hello World!'
  });
}
initMap();

</script>