<?php

  $var_location = $_GET['key'];
  $hj = "chilakaluripet";
  $var_location = str_replace(' ', '+', $var_location);
$url = "https://maps.googleapis.com/maps/api/geocode/json?address=$var_location&sensor=false&key=<your API key>";


$response = file_get_contents($url);
$response = json_decode($response, true);
echo $response['status'];
//print_r($response);
if($response['status']=="OK"){
$lat = $response['results'][0]['geometry']['location']['lat'];
$long = $response['results'][0]['geometry']['location']['lng'];

echo $lat,$long;

}
else{
  echo "No results";
}

?>

<!DOCTYPE html>
<html>
  <head>
    <title>Places Search Box</title>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <link rel ="stylesheet" href="style.css">
      
     
   
    <script>
      var latitude = <?php echo json_encode($lat); ?>;
      var longitude = <?php echo json_encode($long); ?>;


      console.log(latitude,longitude);


      function initAutocomplete() {
        var circle;
        const map = new google.maps.Map(document.getElementById("map"), {
          center: { lat: latitude, lng: longitude },
          zoom: 13,
          mapTypeId: "roadmap",
        });
        // Create the search box and link it to the UI element.
        const input = document.getElementById("pac-input");
        const searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
        // Bias the SearchBox results towards current map's viewport.
        map.addListener("bounds_changed", () => {
          searchBox.setBounds(map.getBounds());
        });
        let markers = [];
        // Listen for the event fired when the user selects a prediction and retrieve
        // more details for that place.
        searchBox.addListener("places_changed", () => {
          const places = searchBox.getPlaces();

          if (places.length == 0) {
            return;
          }
          // Clear out the old markers.
          markers.forEach((marker) => {
            marker.setMap(null);
          });
          markers = [];
            circle = new google.maps.Circle({
        center: map.getCenter(),
        radius: 20, // meters
        strokeColor: "#0000FF",
        strokeOpacity: 0.8,
        strokeWeight: 2,
        fillColor: "#0000FF",
        fillOpacity: 0.26
    });

    circle.setMap(map);
          // For each place, get the icon, name and location.
          const bounds = circle.getBounds();;
          // places.forEach((place) => {
          //   if (!place.geometry || !place.geometry.location) {
          //     console.log("Returned place contains no geometry");
          //     return;
          //   }
          //   const icon = {
          //     url: place.icon,
          //     size: new google.maps.Size(71, 71),
          //     origin: new google.maps.Point(0, 0),
          //     anchor: new google.maps.Point(17, 34),
          //     scaledSize: new google.maps.Size(25, 25),
          //   };
          //   // Create a marker for each place.
          

          //   if (place.geometry.viewport) {
          //     // Only geocodes have viewport.
          //     bounds.union(place.geometry.viewport);
          //   } else {
          //     bounds.extend(place.geometry.location);
          //   }
          // });

        

    map.fitBounds(bounds);
    var locations=[]
    var sw = bounds.getSouthWest();
    var ne = bounds.getNorthEast();    
    for (var i = 0; i < 100; i++) {
       var ptLat = Math.random() * (ne.lat() - sw.lat()) + sw.lat();
       var ptLng = Math.random() * (ne.lng() - sw.lng()) + sw.lng();
       locations.push([ptLat,ptLng]);
       var point = new google.maps.LatLng(ptLat,ptLng);
       if (google.maps.geometry.spherical.computeDistanceBetween(point,circle.getCenter()) < circle.getRadius()) {

         createMarker(map, point,"marker "+i);
         // break;
       }
    }
    console.log(locations[0]);
         
        });
      }


      function createMarker(map, point, content) {
        var infowindow = new google.maps.InfoWindow({
  content: "Click the map to get Lat/Lng!",
});
         var marker = new google.maps.Marker({position:point, map:map});
           google.maps.event.addListener(marker, "click", function(evt) {
               infowindow.setContent(content+"<br>"+marker.getPosition().toUrlValue(6));
               infowindow.open(map, marker);
           });
return marker;

}
  
    </script>

    <!-- <script>
var circle;
var infowindow = new google.maps.InfoWindow({
  content: "Click the map to get Lat/Lng!",
});
function initialize() {


  var map = new google.maps.Map(document.getElementById("map"),
    {
        zoom: 4,
        center: new google.maps.LatLng(22.7964, 79.8456),
        mapTypeId: google.maps.MapTypeId.HYBRID
    });

    circle = new google.maps.Circle({
        center: map.getCenter(),
        radius: 10, // meters
        strokeColor: "#0000FF",
        strokeOpacity: 0.8,
        strokeWeight: 2,
        fillColor: "#0000FF",
        fillOpacity: 0.26
    });

    circle.setMap(map);

    var bounds = circle.getBounds();
    map.fitBounds(bounds);
    var sw = bounds.getSouthWest();
    var ne = bounds.getNorthEast();    
    for (var i = 0; i < 100; i++) {
       var ptLat = Math.random() * (ne.lat() - sw.lat()) + sw.lat();
       var ptLng = Math.random() * (ne.lng() - sw.lng()) + sw.lng();
       var point = new google.maps.LatLng(ptLat,ptLng);
       if (google.maps.geometry.spherical.computeDistanceBetween(point,circle.getCenter()) < circle.getRadius()) {
         createMarker(map, point,"marker "+i);
         // break;
       }
    }

}
function createMarker(map, point, content) {
         var marker = new google.maps.Marker({position:point, map:map});
           google.maps.event.addListener(marker, "click", function(evt) {
               infowindow.setContent(content+"<br>"+marker.getPosition().toUrlValue(6));
               infowindow.open(map, marker);
           });
return marker;
}
</script> -->
  </head>
  <body>
    <input
      id="pac-input"
      class="controls"
      type="text"
      placeholder="Search Box"
    />
     <div class="topnav">
  <a  href="index.html">Home</a>
  <a class = "active" href="#">View Neighborhood</a>
  <a href="search.php">Search Location</a>
 
</div>
    <div id="map"></div>

    <!-- Async script executes immediately and must be after any DOM elements used in callback. -->
    <script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCgDHrXPSopLPEe4sxIZRtRXJ8t2ZQ0uVs&callback=initAutocomplete&libraries=places,geometry&sensor=false"
      async
    ></script>
  </body>
</html>
