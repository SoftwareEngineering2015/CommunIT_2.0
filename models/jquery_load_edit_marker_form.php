<?php
   if(isset($_REQUEST["marker"])) {
     $marker_id = $_REQUEST["marker"];
     $marker_clicked = $_REQUEST["marker_clicked"];
   } else {
     echo "noMarker";
     exit;
   }

   include("db_class.php");

   $sql_marker_information = "SELECT * FROM markers WHERE marker_id = '$marker_id'";

   $sql_marker_information_result = mysqli_query($conn, $sql_marker_information);

   while ($row = $sql_marker_information_result->fetch_assoc()) {
      $name = $row['name'];
      $location = $row['location'];
      $pin_color = $row['pin_color'];
      $has_floorplans = $row['has_floorplan'];
      $latitude = $row['latitude'];
      $longitude = $row['longitude'];
   }
   
?>

<script>

var marker_clicked = <?php echo $marker_clicked; ?>; // This will keep track of which marker was clicked

pin_color = document.getElementById('pincolor').value;
overalayColor(pin_color);
document.getElementById('house_pin').src = fullimg;

//Change pin color on change of color select
$("#pincolor").change(function() {
    pin_color = document.getElementById('pincolor').value;
    overalayColor(pin_color);
    document.getElementById('house_pin').src = fullimg;
});

// Jquery Actions
$(document).ready(function() {

    $('#showUpdateMarkerModal').click(function() {
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
            $('#updateMarkerModal').modal('show');
        }
    });

    $('#updateMarkerModal').on('show.bs.modal', function() {
        $(this).find('.modal-body').css({
            width: 'auto', //probably not needed
            height: 'auto', //probably not needed 
            'max-height': '100%'
        });
    });

    $("#updateCommunityMarkers").click(function() {

        $.post("./models/update_community_markers_model.php", {
                marker: $("#updateCommunityMarkers").val(),
                inputMarkerName: $("#name").val(),
                inputMarkerLocation: $("#location").val(),
                inputPinColor: $("#pincolor").val(),
                inputMarkerHasFloorplans: $("input:radio[name=has_floorplans]:checked").val(), 
                inputMarkerLatitude: $("#latitude").val(),
                inputMarkerLongitude: $("#longitude").val(),
            },
            function(data, status) {
                if (data.trim() === "success") {
                  $('#updateMarkerModal').modal('hide');
                  overalayColor($("#pincolor").val());
                  markers[marker_clicked].setIcon(fullimg);
                  infowindows[marker_clicked].setContent("<b>Refresh the page to do additional marker actions.</b>");
                } else {
                    alert("There was an error submitting the form.");
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
      <td> <input type="text" class="form-control input-md" id="name" placeholder="Marker Name" value="<?php echo $name; ?>"> <span class="text-danger" id="errorMsgMarkerName"></span> </td>
   </tr>
   <tr>
      <th> Marker Location </th>
      <td>
         <a class="glyphicon glyphicon-question-sign" style="text-decoration: none" title="Specify the location of the marker (The location is the address that Google Maps will geocode)."> </a>
      </td>
      <td> </td>
      <td> <input type="text" class="form-control input-md" id="location" placeholder="Marker Location" value="<?php echo $location; ?>"> <span class="text-danger" id="errorMsgMarkerLocation"></span> </td>
   </tr>
   <tr>
      <th> Marker Pin Color </th>
      <td>
         <a class="glyphicon glyphicon-question-sign" style="text-decoration: none" title="Choose the color the marker will appear as in the community."> </a>
      </td>
      <td> <img src="images/house_pin.png" id="house_pin" alt="" style="width:auto; height;auto"> </td>
      <td> <input type="color" name="pincolor" id="pincolor" style="width: 100%" value="<?php echo $pin_color; ?>"> </td>
   </tr>
   <tr>
      <th> Has Floorplan(s)</th>
      <td>
         <a class="glyphicon glyphicon-question-sign" style="text-decoration: none" title="Check yes if the marker will have floorplans"> </a>
      </td>
      <td> </td>
      <td> <label class="radio-inline"><input type="radio" name="has_floorplans" value="1" <?php if ($has_floorplans == 1) { echo "checked";} ?>>Yes</label> <label class="radio-inline"><input type="radio" name="has_floorplans" value="0" <?php if ($has_floorplans == 0) { echo "checked";} ?>>No</label></td>
   </tr>
   <tr>
      <th> Latitude </th>
      <td>
         <a class="glyphicon glyphicon-question-sign" style="text-decoration: none" title="The latitude of the marker (Retrieved after dropping marker on map)."> </a>
      </td>
      <td> </td>
      <td> <input id="latitude" name="latitude" type="text" class="form-control input-md" value="<?php echo $latitude; ?>"readonly> <span class="text-danger" id="errorMsgMarkerLatitude"></span></td>
   </tr>
   <tr>
      <th> Longitude </th>
      <td>
         <a class="glyphicon glyphicon-question-sign" style="text-decoration: none" title="The longitude of the marker (Retrieved after dropping marker on map)."> </a>
      </td>
      <td> </td>
      <td> <input id="longitude" name="longitude" type="text" class="form-control input-md" value="<?php echo $longitude; ?>" readonly> <span class="text-danger" id="errorMsgMarkerLongitude"></span> </td>
   </tr>
   <tr>
      <th> </th>
      <td> </td>
      <td> </td>
      <td> <button type="button" class="btn btn-primary btn-md" id="showUpdateMarkerModal" style="width:100%"> Update Marker </button> </td>
   </tr>
</table>

<!-- Modal -->
<div id="updateMarkerModal" class="modal fade" role="dialog">
<div class="modal-dialog">
<!-- Modal content --> 
<div class="modal-content">
   <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h3 class="modal-title">Marker Information</h3>
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
      <button type="button" class="btn btn-primary" id="updateCommunityMarkers" value="<?php echo $marker_id; ?>">Update Marker</button>
      <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
   </div>
</div>
