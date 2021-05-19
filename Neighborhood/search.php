<!DOCTYPE html>
<html>
  <head>
    <title>Places Search Box</title>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <link rel="stylesheet" href="style.css">
    <script>
      // This example adds a search box to a map, using the Google Place Autocomplete
      // feature. People can enter geographical searches. The search box will return a
      // pick list containing a mix of places and predicted search terms.
      // This example requires the Places library. Include the libraries=places
      // parameter when you first load the API. For example:
      // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">
        var output;

      function initAutocomplete() {
      var list =[];
        const myLatlng = { lat: -25.363, lng: 131.044 };
        const map = new google.maps.Map(document.getElementById("map"), {
          center: myLatlng,
          zoom: 13,
          mapTypeId: "roadmap",
        });
        
         // Create the initial InfoWindow.
        let infoWindow = new google.maps.InfoWindow({
          content: "Click the map to get Lat/Lng!",
          position: myLatlng,
        });
        infoWindow.open(map);
        // Configure the click listener.
        map.addListener("click", (mapsMouseEvent) => {
          // Close the current InfoWindow.
          infoWindow.close();
          // Create a new InfoWindow.
          infoWindow = new google.maps.InfoWindow({
            position: mapsMouseEvent.latLng,
          });
          infoWindow.setContent(
            JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2)

          );
          
          infoWindow.open(map);
          list.push(JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2));

           
        });

        


        // Create the search box and link it to the UI element.
        const input = document.getElementById("pacinput");
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
          // For each place, get the icon, name and location.
          const bounds = new google.maps.LatLngBounds();
          places.forEach((place) => {
            if (!place.geometry || !place.geometry.location) {
              console.log("Returned place contains no geometry");
              return;
            }
            const icon = {
              url: place.icon,
              size: new google.maps.Size(71, 71),
              origin: new google.maps.Point(0, 0),
              anchor: new google.maps.Point(17, 34),
              scaledSize: new google.maps.Size(25, 25),
            };

            // Create a marker for each place.
            markers.push(
              new google.maps.Marker({
                map,
                icon,
                title: place.name,
                position: place.geometry.location,
              })
            );

            if (place.geometry.viewport) {
              // Only geocodes have viewport.
              bounds.union(place.geometry.viewport);
            } else {
              bounds.extend(place.geometry.location);
            }
          });
          map.fitBounds(bounds);
        });

      }
      
    </script>
    <script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCgDHrXPSopLPEe4sxIZRtRXJ8t2ZQ0uVs&callback=initAutocomplete&libraries=places,geometry"
      async
    ></script>
  </head>

  <script language="javascript" type="text/javascript">
    var loc;
    var fin;
    function onload() { 
        loc = document.getElementById('pacinput');
    }
    function kk(){
      var fin = loc.value;
      var url = "view_neighborhood.php?key="+fin;
      window.location.href=url;
    }

  </script>
  
  <body onload="onload();">
    <form method="post" action="view_neighborhood.php">
    <input
      id="pacinput"
      class="controls"
      type="text"
      placeholder="Search Box"
      >
    <input type="button" value="click" onclick="kk();"/>
        

  </form>
 
      



   <div class="topnav">
  <a  href="index.html">Home</a>
  <a href="view_neighborhood.php">View Neighborhood</a>
  <a class = "active" href="#">Search Location</a>
 
</div>
    <div id="map"></div>

    <!-- Async script executes immediately and must be after any DOM elements used in callback. -->
    
    
  </body>

</html>