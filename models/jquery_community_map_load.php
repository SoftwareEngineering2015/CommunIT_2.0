<?php

  if(!isset($_REQUEST['community'])) {
    echo "noCommunity"; exit();
  } else {
    $community_id = $_REQUEST['community'];
  }

?>

<script>


var map;
var streetview = new google.maps.StreetViewService();
var myCenter = new google.maps.LatLng(41.7605556, -88.3200)

var markers = []; // This is the array for the markers
var marker_ids = []; //Marker information
var marker_names = []; //Marker name
var marker_latitudes = []; //holds marker latitude
var marker_longitudes = []; //holds marker longitude
var marker_latlngs = []; //holds parsed latlng marker data
var marker_locations = []; //holds marker location
var marker_pin_colors = []; //holds marker pin color
var marker_has_floorplans = []; //Specifies that the marker has floorplans

var infowindows = [];
var prev_infowindow = false; // Varaible to check to 

//creates a bounds object that is extended in the main loop
var bounds = new google.maps.LatLngBounds();

loadCommunity('<?php echo $community_id; ?>');

// Function to load the map in angular
function loadCommunity(community) {

    $.post("models/load_community_map_model.php", {
        community: community
    }, function(data) {
        if (data === 'noCommunity') {
            window.location.href = 'myhome.php';
        } else {
            var obj = jQuery.parseJSON(data);
            if (obj == "") {
                initializeNewCommunity();
            } else {
                $.each(obj, function(key, value) {
                    //populates the marker arrays with the data from the database
                    marker_ids.push(value.marker_id);
                    marker_names.push(value.name);
                    marker_latitudes.push(value.latitude);
                    marker_longitudes.push(value.longitude);
                    marker_latlngs.push(new google.maps.LatLng(value.latitude, value.longitude));
                    marker_locations.push(value.location);
                    marker_pin_colors.push(value.pin_color);
                    marker_has_floorplans.push(value.has_floorplan);
                });

                // Load Google Maps
                initializeFilledCommunity();
                //google.maps.event.addDomListener(window, 'load', initialize);
            }
        }

    });
}

function initializeNewCommunity() {
    var mapProp = {
        center: myCenter,
        zoom: 10,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    map = new google.maps.Map(document.getElementById("community_map"), mapProp);

    google.maps.event.addDomListener(window, "resize", function() {
        google.maps.event.trigger(map, "resize");
        map.panTo(myCenter);
    });

    myCenter = map.getBounds().getCenter();
}

function initializeFilledCommunity() {
    var mapProp = {
        center: myCenter,
        zoom: 10,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    map = new google.maps.Map(document.getElementById("community_map"), mapProp);

    google.maps.event.addDomListener(window, "resize", function() {
        google.maps.event.trigger(map, "resize");
        map.panTo(myCenter);
    });

    //this loop will create all of the markers, then invoke the addlistener function
    for (i in marker_ids) {
        //extend the bounds object to fit the iterated marker
        bounds.extend(new google.maps.LatLng(marker_latitudes[i], marker_longitudes[i]));

        //Change the color of each image through this function
        colorPins(marker_pin_colors[i]);
        //creates a marker in the markers array
        markers.push(new google.maps.Marker({
            map: map,
            position: marker_latlngs[i],
            title: (marker_names[i] + "\n" + marker_locations[i]),
            //Place changed image as the icon
            icon: fullimg,
            animation: google.maps.Animation.DROP
        }));

        // If the marker has a floorplan add the listener for the floorplan, otherwise add a listener to get the profile of the marker
        if (marker_has_floorplans[i] == 1) {
            infowindows[i] = new google.maps.InfoWindow({
                content: "<b> Name: " + marker_names[i] + " <br /> Location: " + marker_locations[i]
            });
            addFloorplanListener(i);
        } else {
            infowindows[i] = new google.maps.InfoWindow({
                content: "<b> Name: " + marker_names[i] + " <br /> Location: " + marker_locations[i]
            });
            addProfileListener(i);
        }
    };
    myCenter = map.getCenter();

}
// End of the initialize function

// Add a listener to the marker for a floorplan
function addFloorplanListener(i) {
    markers[i].addListener('click', function() {

        if (prev_infowindow) {
            prev_infowindow.close();
        }

        prev_infowindow = infowindows[i];

        infowindows[i].open(map, markers[i]);

        map.panTo(marker_latlngs[i]);
    });
}


// Add a click event listener to the marker for the profile
function addProfileListener(i) {
    markers[i].addListener('click', function() {

        if (prev_infowindow) {
            prev_infowindow.close();
        }

        prev_infowindow = infowindows[i];

        infowindows[i].open(map, markers[i]);

        map.panTo(marker_latlngs[i]);
    });
}

//PRocess to change the colors of each pin
//Variables to store each process
selectImg = '';
canvas = document.createElement("canvas");
ctx = canvas.getContext("2d");
originalPixels = null;
currentPixels = null;
color = '';
fullimg = '';
img = new Image();
img.src = "images/house_pin.png";
 
// Function for convert Hexdecimal code into RGB color
function HexToRGB(Hex){
 var Long = parseInt(Hex.replace(/^#/, ""), 16);
 return {
 R: (Long >>> 16) & 0xff,
 G: (Long >>> 8) & 0xff,
 B: Long & 0xff
 };
}
// Function to fill the color of generated image
function fillColor(path){
 color = path;
  
 if(!originalPixels) return; // Check if image has loaded
 var newColor = HexToRGB(color);
  
 for(var I = 0, L = originalPixels.data.length; I < L; I += 4){
  if(currentPixels.data[I + 3] > 0){
   currentPixels.data[I] = originalPixels.data[I] / 255 * newColor.R;
   currentPixels.data[I + 1] = originalPixels.data[I + 1] / 255 * newColor.G;
   currentPixels.data[I + 2] = originalPixels.data[I + 2] / 255 * newColor.B;
  }
 }
  
 ctx.putImageData(currentPixels, 0, 0);
 fullimg = canvas.toDataURL("image/house_pin.png");
}
 
// Function for draw a image
function colorPins(color){
  //fullimg = document.getElementsByTagName('img')[0];
  selectImg = img;
  //alert(img.src);
  //alert(img.src);
  canvas.width = selectImg.width;
  canvas.height = selectImg.height;
 
  ctx.drawImage(selectImg, 0, 0, selectImg.naturalWidth, selectImg.naturalHeight, 0, 0, selectImg.width, selectImg.height);
  originalPixels = ctx.getImageData(0, 0, selectImg.width, selectImg.height);
  currentPixels = ctx.getImageData(0, 0, selectImg.width, selectImg.height);
   
  selectImg.onload = null;
  fillColor(color);
}
//End of the color process

</script>