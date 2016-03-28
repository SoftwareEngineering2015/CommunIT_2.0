<?php
   if(isset($_REQUEST["community"])) {
     $community_id = $_REQUEST["community"];
   } else {
     echo "noCommunity";
     exit;
   }
   
?>

<script>

var placedMarkerInfoWindow; // This will hold the infowindow of added markers to the community

pin_color = document.getElementById('pincolor').value;
overalayColor(pin_color);
document.getElementById('house_pin').src = fullimg;

//Change pin color on change of color select
$("#pincolor").change(function() {
    pin_color = document.getElementById('pincolor').value;
    overalayColor(pin_color);
    document.getElementById('house_pin').src = fullimg;
});

function geocodeAddress(geocoder, resultsMap) {

    if (typeof placedMarker[0] !== 'undefined') {
      placedMarker[0].setMap(null);
      placedMarker.pop();
    }

    var address = document.getElementById('location').value;
    geocoder.geocode({
        'address': address
    }, function(results, status) {
        if (status === google.maps.GeocoderStatus.OK) {
            resultsMap.panTo(results[0].geometry.location);
            resultsMap.setZoom(bounds);
            //Sets a Marker at the locations in the Geocoder search
            var marker = new google.maps.Marker({
                map: resultsMap,
                draggable: true,
                icon: "images/house_pin.png",
                position: results[0].geometry.location
            });
            placedMarker.push(marker);

            document.getElementById("latitude").value = marker.getPosition().lat();
            document.getElementById("longitude").value = marker.getPosition().lng();
            // Zoom to 15 when clicking on marker and opens the infow window if its closed
            google.maps.event.addListener(marker, 'click', function() {
                map.panTo(marker.getPosition());
            });
        } else {
            alert('Geocode was not successful for the following reason: ' + status);
        }
        google.maps.event.addListener(marker, 'dragend', function(event) {
            document.getElementById("latitude").value = this.getPosition().lat();
            document.getElementById("longitude").value = this.getPosition().lng();
        });
    });
}

// Jquery Actions
$(document).ready(function() {
  $("#addMarkersButton, #editCommunitySettingsButton").click(function(event) {
    if (typeof placedMarker[0] !== 'undefined') {
      placedMarker[0].setMap(null);
      placedMarker.pop();
    }
  });

    // Drops pin on the map based on the location value
    $("#dropMarker").click(function() {
        geocodeAddress(geocoder, map);
    });

    $('#show_modal_button').click(function() {
        if ($.trim($('#name').val()) == '') {
            $("#errorMsgMarkerName").html("Marker name is required.");
        } else if ($.trim($('#location').val()) == '') {
            $("#errorMsgMarkerName").empty();
            $("#errorMsgMarkerLocation").html("Marker location is required.");
        } else if ($.trim($('#latitude').val()) == '') {
            $("#errorMsgMarkerName").empty();
            $("#errorMsgMarkerLocation").empty();
            $("#errorMsgMarkerLatitude").html("Marker latitude is required (Drop marker pin to get latitude).");
        } else if ($.trim($('#longitude').val()) == '') {
            $("#errorMsgMarkerName").empty();
            $("#errorMsgMarkerLocation").empty();
            $("#errorMsgMarkerLatitude").empty();
            $("#errorMsgMarkerLongitude").html("Marker longitude is required (Drop marker pin to get latitude).");
        } else {
            $("#errorMsgMarkerName").empty();
            $("#errorMsgMarkerLocation").empty();
            $("#errorMsgMarkerLatitude").empty();
            $("#errorMsgMarkerLongitude").empty();

            $("#inputMarkerName").html($("#name").val());
            $("#inputMarkerLocation").html($("#location").val());

            pin_color = document.getElementById('pincolor').value;
            overalayColor(pin_color);
            document.getElementById('inputPinColor').src = fullimg;

            if ($("input:radio[name=has_floorplans]:checked").val() == "1") {
                $("#inputMarkerHasFloorplans").html("Yes");
            } else {
                $("#inputMarkerHasFloorplans").html("No");
            }

            $("#inputMarkerLatitude").html($("#latitude").val());
            $("#inputMarkerLongitude").html($("#longitude").val());
            $('#addMarkersModal').modal('show');
        }
    });

    $('#addMarkersModal').on('show.bs.modal', function() {
        $(this).find('.modal-body').css({
            width: 'auto', //probably not needed
            height: 'auto', //probably not needed 
            'max-height': '100%'
        });
    });

    $("#addMarkerToCommunity").click(function() {

        $.post("./models/add_community_markers_model.php", {
                community: $("#addMarkerToCommunity").val(),
                inputMarkerName: $("#name").val(),
                inputMarkerLocation: $("#location").val(),
                inputPinColor: $("#pincolor").val(),
                inputMarkerHasFloorplans: $("input:radio[name=has_floorplans]:checked").val(), 
                inputMarkerLatitude: $("#latitude").val(),
                inputMarkerLongitude: $("#longitude").val(),
            },
            function(data, status) {
                if (data.trim() === "success") {
                  $('#addMarkersModal').modal('hide');

                  overalayColor($("#pincolor").val());
                  var addedMarker = new google.maps.Marker({
                    position: placedMarker[0].position,
                    icon: fullimg,
                    map: map
                  });
                  placedMarkerInfoWindow = new google.maps.InfoWindow({
                      content: "<b>Refresh the page to edit / delete marker.</b>"
                  });
                  addedMarker.addListener('mouseover', function() {
                    placedMarkerInfoWindow.open(map, this);
                  });

                  // assuming you also want to hide the infowindow when user mouses-out
                  addedMarker.addListener('mouseout', function() {
                    placedMarkerInfoWindow.close();
                  });
                  placedMarker[0].setMap(null);
                  placedMarker.pop();
                } else {
                    alert("There was an error updating the settings.");
                }
            });
    });
});
</script>

<table class="table table-striped table-hover table-condensed ">
   <tr>
      <th> Marker Name </th>
      <td>
         <a class="glyphicon glyphicon-question-sign" style="text-decoration: none" title="Give the marker a name."> </a>
      </td>
      <td> </td>
      <td> <input type="text" class="form-control input-md" id="name" placeholder="Marker Name"> <span class="text-danger" id="errorMsgMarkerName"></span> </td>
   </tr>
   <tr>
      <th> Marker Location </th>
      <td>
         <a class="glyphicon glyphicon-question-sign" style="text-decoration: none" title="Specify the location of the marker (The location is the address that Google Maps will geocode)."> </a>
      </td>
      <td> </td>
      <td> <input type="text" class="form-control input-md" id="location" placeholder="Marker Location"> <span class="text-danger" id="errorMsgMarkerLocation"></span> </td>
   </tr>
   <tr>
      <th> </th>
      <td> </td>
      <td> </td>
      <td> <button type="button" class="btn btn-primary btn-md" id="dropMarker" style="width:100%"> Drop Marker </button> </td>
   </tr>
   <tr>
      <th> Marker Pin Color </th>
      <td>
         <a class="glyphicon glyphicon-question-sign" style="text-decoration: none" title="Choose the color the marker will appear as in the community."> </a>
      </td>
      <td> <img src="images/house_pin.png" id="house_pin" alt="" style="width:auto; height;auto"> </td>
      <td> <input type="color" name="pincolor" id="pincolor" style="width: 100%" value="#96F0F0"> </td>
   </tr>
   <tr>
      <th> Has Floorplan(s)</th>
      <td>
         <a class="glyphicon glyphicon-question-sign" style="text-decoration: none" title="Check yes if the marker will have floorplans"> </a>
      </td>
      <td> </td>
      <td> <label class="radio-inline"><input type="radio" name="has_floorplans" value="1">Yes</label> <label class="radio-inline"><input type="radio" name="has_floorplans" value="0" checked>No</label></td>
   </tr>
   <tr>
      <th> Latitude </th>
      <td>
         <a class="glyphicon glyphicon-question-sign" style="text-decoration: none" title="The latitude of the marker (Retrieved after dropping marker on map)."> </a>
      </td>
      <td> </td>
      <td> <input id="latitude" name="latitude" type="text" class="form-control input-md" readonly> <span class="text-danger" id="errorMsgMarkerLatitude"></span></td>
   </tr>
   <tr>
      <th> Longitude </th>
      <td>
         <a class="glyphicon glyphicon-question-sign" style="text-decoration: none" title="The longitude of the marker (Retrieved after dropping marker on map)."> </a>
      </td>
      <td> </td>
      <td> <input id="longitude" name="longitude" type="text" class="form-control input-md" readonly> <span class="text-danger" id="errorMsgMarkerLongitude"></span> </td>
   </tr>
   <tr>
      <th> </th>
      <td> </td>
      <td> </td>
      <td> <button type="button" class="btn btn-primary btn-md" id="show_modal_button" style="width:100%"> Add Marker </button> </td>
   </tr>
</table>
<!-- Modal -->
<div id="addMarkersModal" class="modal fade" role="dialog">
<div class="modal-dialog">
<!-- Modal content --> 
<div class="modal-content">
   <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h3 class="modal-title">Community Information</h3>
   </div>
   <div class="modal-body">
      <table class="table table-striped table-hover">
         <tr>
            <th> Marker Name </th>
            <td id="inputMarkerName"> </td>
         </tr>
         <tr>
            <th> Marker Location </th>
            <td id="inputMarkerLocation"> </td>
         </tr>
         <tr>
            <th> Marker Pin Color </th>
            <td> <img src="images/house_pin.png" id="inputPinColor" alt="" style="width:auto; height;auto"> </td>
         </tr>
         <tr>
            <th> Marker Has Floorplans </th>
            <td id="inputMarkerHasFloorplans"> </td>
         </tr>
         <tr>
            <th> Marker Latitude </th>
            <td id="inputMarkerLatitude"> </td>
         </tr>
         <tr>
            <th> Marker Longitude </th>
            <td id="inputMarkerLongitude"> </td>
         </tr>
      </table>
   </div>
   <div class="modal-footer">
      <button type="button" class="btn btn-primary" id="addMarkerToCommunity" value="<?php echo $community_id; ?>">Add Marker To Community</button>
      <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
   </div>
</div>
