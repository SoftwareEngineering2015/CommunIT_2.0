<?php
   if(isset($_REQUEST["marker"])) {
     $marker_id = $_REQUEST["marker"];
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
   }
   
?>

<script>

        var marker_clicked = '<?php echo $marker_id; ?>'; // This will keep track of which marker was clicked
        floorplanMarkerBeingEdited = marker_clicked;

        pin_color = document.getElementById('floorplanMarkerPincolor').value;
        overalayColor(pin_color);
        document.getElementById('house_pin').src = fullimg;

        //Change pin color on change of color select
        $("#floorplanMarkerPincolor").change(function() {
            pin_color = document.getElementById('floorplanMarkerPincolor').value;
            overalayColor(pin_color);
            document.getElementById('house_pin').src = fullimg;
        });

        // Jquery Actions
        $(document).ready(function() {

            $('#updateFloorplanMarker').click(function() {
                var position = $("#marker_" + marker_clicked + "").position();
                var percentLeft = position.left / $("#floorplanModalDiv").width() * 100;
                var percentTop = position.top / $("#floorplanModalDiv").height() * 100;

                if (percentTop < 0 || percentTop > 100 || percentLeft < 0 || percentLeft > 100) {
                    $("#errorMsgFloorplanMarkerOutside").html("Marker must be on the image.");
                    $('#floorplan_marker_being_added').css("top", "50%").css("left", "50%");
                } else if ($.trim($('#floorplanMarkerName').val()) == '') {
                    $("#errorMsgFloorplanMarkerOutside").empty();
                    $("#errorMsgFloorplanMarkerName").html("Marker name is required.");
                } else if ($.trim($('#floorplanMarkerLocation').val()) == '') {
                    $("#errorMsgFloorplanMarkerOutside").empty();
                    $("#errorMsgFloorplanMarkerName").empty();
                    $("#errorMsgFloorplanMarkerLocation").html("Marker location is required.");
                } else {
                    $("#errorMsgFloorplanMarkerOutside").empty();
                    $("#errorMsgFloorplanMarkerName").empty();
                    $("#errorMsgFloorplanMarkerLocation").empty();

                    $.post("./models/update_floorplan_marker_model.php", {
                            marker: marker_clicked,
                            inputMarkerName: $("#floorplanMarkerName").val(),
                            inputMarkerInformation: $("#floorplanMarkerInformation").val(),
                            inputMarkerLocation: $("#floorplanMarkerLocation").val(),
                            inputPinColor: $("#floorplanMarkerPincolor").val(),
                            inputMarkerLatitude: percentLeft,
                            inputMarkerLongitude: percentTop,
                        },
                        function(data, status) {
                            data = jQuery.parseJSON(data);
                            if (data.status.trim() === "success") {

                                if (default_pin_color_status) {
                                    overalayColor(default_pin_color);
                                } else {
                                    overalayColor($("#floorplanMarkerPincolor").val());
                                }

                                $("#marker_" + marker_clicked + "").remove();

                                $("#floorplanModalDiv").append('<img src=' + fullimg + ' class="markers_on_floorplan" id="marker_' + data.marker_id + '" style="display: block; position: absolute; left:' + data.marker_latitude + '%; top:' + data.marker_longitude + '%;" title="' + data.marker_name + '\n' + data.marker_location + '" onclick="marker_actions(`' + data.marker_id + '`)"/>');
                                
                                $("#updateFloorplanMarkerMessage").html("<span style='font-weight: bold; color: green'> Marker was successfully updated. </span>");

                            } else {
                                $("#updateFloorplanMarkerMessage").html("<span style='font-weight: bold; color: red'> There was an error updating the marker. </span>");
                            }
                        });

                }
            });
        });
    </script>

<h5> Click and drag to move the marker on the map </h5>
<table class="table table-striped table-hover table-condensed ">
        <tr>
            <th> Marker Name </th>
            <td>
                <a class="glyphicon glyphicon-question-sign" style="text-decoration: none" title="Give the marker a name."> </a>
            </td>
            <td> </td>
            <td> <input type="text" class="form-control input-md" id="floorplanMarkerName" placeholder="Marker Name" value="<?php echo $name; ?>"> <span class="text-danger" id="errorMsgFloorplanMarkerName"></span> </td>
        </tr>
        <tr>
            <th> Marker Information </th>
            <td> 
                <a class="glyphicon glyphicon-question-sign" style="text-decoration: none" title="Information about this marker."> </a>
            </td>
            <td> </td>
            <td> <textarea class="form-control" id="floorplanMarkerInformation" placeholder="Marker Information" wrap="soft" rows="5"><?php echo $miscinfo; ?></textarea></td>
        </tr>
        <tr>
            <th> Marker Location </th>
            <td>
                <a class="glyphicon glyphicon-question-sign" style="text-decoration: none" title="Specify the location of the marker."> </a>
            </td>
            <td> </td>
            <td> <input type="text" class="form-control input-md" id="floorplanMarkerLocation" placeholder="Marker Location" value="<?php echo $location; ?>"> <span class="text-danger" id="errorMsgFloorplanMarkerLocation"></span> </td>
        </tr>
        <tr>
            <th> Marker Pin Color </th>
            <td>
                <a class="glyphicon glyphicon-question-sign" style="text-decoration: none" title="Choose the color the marker will appear as in the community."> </a>
            </td>
            <td> <img src="images/house_pin.png" id="house_pin" alt="" style="width:auto; height;auto"> </td>
            <td> <input type="color" name="pincolor" id="floorplanMarkerPincolor" style="width: 100%" value="<?php echo $pin_color; ?>"> </td>
        </tr>
        <tr>
            <th> </th>
            <td> </td>
            <td> </td>
            <td> <button type="button" class="btn btn-primary btn-md" id="updateFloorplanMarker" style="width:100%"> Update Marker </button> <span class="text-danger" id="errorMsgFloorplanMarkerOutside"></span> <span id="updateFloorplanMarkerMessage"></span></td>
        </tr>
    </table>
