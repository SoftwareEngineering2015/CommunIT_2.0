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
      $miscinfo = $row['miscinfo'];
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

$("input[name=has_floorplans]:radio").change(function () {
  $("#changingHasFloorplanRadioButtonAlert").modal("show");
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
            $("#inputMarkerInformation").html($("#information").val());
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
                inputMarkerInformation: $("#information").val(),
                inputMarkerLocation: $("#location").val(),
                inputPinColor: $("#pincolor").val(),
                inputMarkerHasFloorplans: $("input:radio[name=has_floorplans]:checked").val(), 
                inputMarkerLatitude: $("#latitude").val(),
                inputMarkerLongitude: $("#longitude").val(),
            },
            function(data, status) {
                data = jQuery.parseJSON(data);
                if (data.status.trim() === "success") {
                  $('#updateMarkerModal').modal('hide');
                  if (!default_pin_color_status) {
                    overalayColor($("#pincolor").val());
                    markers[marker_clicked].setIcon(fullimg);
                  }

                  markers[marker_clicked].setTitle(data.marker_name + "\n" + data.marker_location);

                  if (data.has_floorplan == 1) {
                      infowindows[marker_clicked].setContent("");
                      infowindows[marker_clicked].setContent("<b> Name: " + data.marker_name + " <br /> Location: " + data.marker_location + " </b> <br /> <a onclick='edit_marker(`" + data.marker_id + "`, `" + marker_clicked + "`)'> Edit Marker </a> <br /> <a onclick='load_floor_plans(`" + data.marker_id + "`)'> Load Floorplans </a>  <br /> <a onclick='delete_marker(`" + data.marker_id + "`)'> Delete Marker </a>");
                  } else {
                      infowindows[marker_clicked].setContent("");
                      infowindows[marker_clicked].setContent("<b> Name: " + data.marker_name + " <br /> Location: " + data.marker_location + " </b> <br /> <a onclick='edit_marker(`" + data.marker_id + "`, `" + marker_clicked + "`)'> Edit Marker </a> <br /> <a onclick='add_remove_residents(`" + data.marker_id + "`)'> Add / Remove Residents </a> <br /> <a onclick='delete_marker(`" + data.marker_id + "`)'> Delete Marker </a>");
                  }
                } else {
                    $("#updateMarkerErrorMessage").html("There was an error submitting the form.");
                }
            });
    });

    $("#updateMarkerModal").on('hidden.bs.modal', function() {
        $("#updateMarkerErrorMessage").empty(); // Clear the error message
    });
});
</script>
<h5>Click and drag to move the marker on the map.</h5>
<table class="table table-striped table-hover table-condensed ">
   <tr>
      <th> Marker Name </th>
      <td>
         <a class="glyphicon glyphicon-question-sign" style="text-decoration: none" title="Give the marker a name."> </a>
      </td>
      <td colspan="2"> <input type="text" class="form-control input-md" id="name" placeholder="Marker Name" value="<?php echo $name; ?>"> <span class="text-danger" id="errorMsgMarkerName"></span> </td>
   </tr>
   <tr>
      <th> Marker Information </th>
      <td> 
        <a class="glyphicon glyphicon-question-sign" style="text-decoration: none" title="Information about this marker."> </a>
      </td>
      <td colspan="2"> <textarea class="form-control" id="information" placeholder="Marker Information" wrap="soft" rows="5"><?php echo $miscinfo; ?></textarea></td>
   </tr>
   <tr>
      <th> Marker Location </th>
      <td>
         <a class="glyphicon glyphicon-question-sign" style="text-decoration: none" title="Specify the location of the marker (The location is the address that Google Maps will geocode)."> </a>
      </td>
      <td colspan="2"> <input type="text" class="form-control input-md" id="location" placeholder="Marker Location" value="<?php echo $location; ?>"> <span class="text-danger" id="errorMsgMarkerLocation"></span> </td>
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
      <td colspan="2"> <label class="radio-inline"><input type="radio" name="has_floorplans" value="1" <?php if ($has_floorplans == 1) { echo "checked";} ?>>Yes</label> <label class="radio-inline"><input type="radio" name="has_floorplans" value="0" <?php if ($has_floorplans == 0) { echo "checked";} ?>>No</label></td>
   </tr>
   <tr>
      <th> Latitude </th>
      <td>
         <a class="glyphicon glyphicon-question-sign" style="text-decoration: none" title="The latitude of the marker (Retrieved after dropping marker on map)."> </a>
      </td>
      <td colspan="2"> <input id="latitude" name="latitude" type="text" class="form-control input-md" value="<?php echo $latitude; ?>"readonly> <span class="text-danger" id="errorMsgMarkerLatitude"></span></td>
   </tr>
   <tr>
      <th> Longitude </th>
      <td>
         <a class="glyphicon glyphicon-question-sign" style="text-decoration: none" title="The longitude of the marker (Retrieved after dropping marker on map)."> </a>
      </td>
      <td colspan="2"> <input id="longitude" name="longitude" type="text" class="form-control input-md" value="<?php echo $longitude; ?>" readonly> <span class="text-danger" id="errorMsgMarkerLongitude"></span> </td>
   </tr>
   <tr>
      <th> </th>
      <td> </td>
      <td> </td>
      <td> <button type="button" class="btn btn-primary btn-md" id="showUpdateMarkerModal" style="width:100%"> Update Marker </button> </td>
   </tr>
</table>
<br />
<br />
<br />

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
            <th> Marker Information </th>
            <td> <textarea id="inputMarkerInformation" style="width: 100%;"></textarea> </td>
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
      <div style="font-weight: bold; color: red" id="updateMarkerErrorMessage"> </div>
   </div>
   <div class="modal-footer">
      <button type="button" class="btn btn-primary" id="updateCommunityMarkers" value="<?php echo $marker_id; ?>">Update Marker</button>
      <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
   </div>
</div>
</div>
</div>

<!-- Modal -->
<div id="changingHasFloorplanRadioButtonAlert" class="modal fade" role="dialog">
<div class="modal-dialog">
<!-- Modal content --> 
<div class="modal-content">
   <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h3 class="modal-title">Changing Floorplan Status</h3>
   </div>
   <div class="modal-body">
      <b class="text-danger"> Changing this will value will hide all residents tied to this marker. <br /> <br />
        Unlink residents tied to this marker if you would like to assign them to other markers. </b>
   <div class="modal-footer">
      <button type="button" class="btn btn-warning" data-dismiss="modal">I Understand</button>
   </div>
  </div>
</div>
</div>
</div>