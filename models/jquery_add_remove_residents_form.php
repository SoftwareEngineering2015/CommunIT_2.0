<?php
if (isset($_REQUEST["marker"]) && isset($_REQUEST["community"])) {
    $marker_id    = $_REQUEST["marker"];
    $community_id = $_REQUEST["community"];
} else {
    echo "noMarker";
    exit;
}

include("db_class.php");

$sql_users_tied_to_marker = "SELECT profiles.profile_id AS profile_id, users.first_name AS first_name, users.last_name AS last_name FROM markers INNER JOIN profiles_to_markers ON markers.marker_id = profiles_to_markers.marker_id INNER JOIN profiles ON profiles_to_markers.profile_id = profiles.profile_id INNER JOIN users ON profiles.user_id = users.user_id WHERE markers.marker_id = '$marker_id'";

$sql_users_tied_to_marker_result = mysqli_query($conn, $sql_users_tied_to_marker);

?>

<script>
var clickedMarker = '<?php echo $marker_id; ?>'; // This is the marker that they clicked
var row; // Will hold the row of the clicked element

// Jquery Actions
$(document).ready(function() {

    $('#confirmUnlinkResidentModal').on('show.bs.modal', function() {
        $(this).find('.modal-body').css({
            width: 'auto', //probably not needed
            height: 'auto', //probably not needed 
            'max-height': '100%'
        });
    });

    $('#residentsTiedToMarker').on('click', '.show_unlink_resident_modal', function() {
        document.getElementById("unlinkResident").value = $(this).val();
        row = ($(this).closest("tr").index() - 1);
        var residentFirstName;
        var residentLastName;
        $( ".resident_first_name" ).each(function( index ) {
          if (row == index) {
            residentFirstName = $( this ).text();
          }
        });
        $( ".resident_last_name" ).each(function( index ) {
          if (row == index) {
            residentLastName = $( this ).text();
          }
        });
        $("#unlinkResidentMessage").html("<b>Are you sure you want to unlink " + residentFirstName + " " + residentLastName + "from this marker?</b>");
        $("#confirmUnlinkResidentModal").modal("show");
    });

    $("#unlinkResident").click(function() {

        $.post("./models/unlink_resident_model.php", {
              marker: clickedMarker,
              profile: $(this).val()

            },
            function(data, status) {
                data = jQuery.parseJSON(data);
                console.log(data);
                if (data.status.trim() === "success") {
                  $('#confirmUnlinkResidentModal').modal('hide');
                  row = row + 1;
                  $("#residentsTiedToMarker tr:eq(" + row + ")").remove();

                  if (!$('#residentsAvailableSelectBox').length) {
                      $('#residentsAvailableTableRow').empty();
                      $('#residentsAvailableTableRow').append("<td> <select class='form-control' id='residentsAvailableSelectBox'> </td><td><button type='button' class='btn btn-primary' id='assignResidentToMarkerButton'>Assign To Marker</button></td>");
                      $('#residentsAvailableSelectBox').append($('<option/>', { 
                        value: data.profile,
                        text : data.name 
                      }));
                  } else {
                      $('#residentsAvailableSelectBox').append($('<option/>', { 
                        value: data.profile,
                        text : data.name 
                      }));
                  }
                } else {
                    $("#unlinkResidentErrorMessage").html("There was an error unlinking the resident.");
                }
            });
    });

    $('#residentsAvailableTableRow').on('click', '#assignResidentToMarkerButton', function() {
        document.getElementById("assignResidentToMarker").value = $("#residentsAvailableSelectBox option:selected").val();
        $("#assignResidentToMarkerMessage").html("<b>Are you sure you want to assign " + $("#residentsAvailableSelectBox option:selected").text() + " to this marker?</b>");
        $("#assignResidentToMarkerModal").modal("show");
    });

    $("#assignResidentToMarker").click(function() {
      var profile = $(this).val();

        $.post("./models/assign_resident_to_marker.php", {
                profile: profile,
                marker: clickedMarker
            },
            function(data, status) {
                data = jQuery.parseJSON(data);
                if (data.status.trim() === "success") {
                  $("#residentsAvailableSelectBox option[value='" + profile + "']").remove();
                  $('#assignResidentToMarkerModal').modal('hide');
                  $("#residentsTiedToMarker").append("<tr> <td class='resident_first_name'> " + data.first_name + " </td> <td class='resident_last_name'> " + data.last_name + " </td> <td> <button type='button' class='btn btn-danger btn-sm show_unlink_resident_modal' value='" + data.profile + "' style='width: 100%;'>Unlink Resident</button> </td> </tr>");
                  if ($('#residentsAvailableSelectBox option').length == 0) {
                    $('#residentsAvailableTableRow').html("<b> There are no available residents. </b>");
                  }
                } else {
                    $("#assignResidentErrorMessage").html("There was an error assigning the resident to this marker."); // Clear the error message
                }
            });
    });

    $("#confirmUnlinkResidentModal").on('hidden.bs.modal', function() {
        $("#unlinkResidentErrorMessage").empty(); // Clear the error message
    });

    $("#assignResidentToMarkerModal").on('hidden.bs.modal', function() {
        $("#assignResidentErrorMessage").empty(); // Clear the error message
    });
});
</script>

<h3> Residents Tied To Marker </h3>
<table class="table table-striped table-hover table-condensed " id="residentsTiedToMarker">
   <tr>
      <th> First Name </th>
      <th> Last Name </th>
      <th> Remove Resident From Marker </th>
   </tr>

   <?php

    while ($row = $sql_users_tied_to_marker_result->fetch_assoc()) {
        echo "<tr> <td class='resident_first_name'> " . $row['first_name'] . " </td>";
        echo "<td class='resident_last_name'> " . $row['last_name'] . " </td>";
        echo "<td> <button type='button' class='btn btn-danger btn-sm show_unlink_resident_modal' value='" . $row['profile_id'] . "' style='width: 100%;'>Unlink Resident</button> </td> </tr>";
    }

?>

</table>

<table class="table table-striped table-hover table-condensed ">
   <tr>
      <th> Residents Available </th>
      <th> </th>
   </tr>
   <tr id='residentsAvailableTableRow'> 

   <?php


    $residents_available_array = array();

    $sql_available_residents = "SELECT * FROM profiles INNER JOIN users ON profiles.user_id = users.user_id WHERE profiles.profile_id NOT IN (SELECT profile_id FROM profiles_to_markers) AND profiles.community_id = '$community_id'";

    $sql_available_residents_result = mysqli_query($conn, $sql_available_residents);

    if (mysqli_num_rows($sql_available_residents_result) == 0) {
        echo "<td> <b> There are no available residents. </b></td> <td></td>";
    } else {
        echo "<td> <select class='form-control' id='residentsAvailableSelectBox'>";

        $sql_available_residents_result = mysqli_query($conn, $sql_available_residents);
        $counter = 0;
        while ($row = $sql_available_residents_result->fetch_assoc()) {
        
            $residents_available_array[$counter]['profile_id'] = $row['profile_id'];
            $residents_available_array[$counter]['first_name'] = $row['first_name'];
            $residents_available_array[$counter]['last_name']  = $row['last_name'];
        
            $counter++;
        }
    
        foreach ($residents_available_array as $key => $value) {
            if ($key == 0) {
                echo "<option selected value='" . $value['profile_id'] . "'> " . $value['first_name'] . " " . $value['last_name'] . " </option>";
            } else {
                echo "<option value='" . $value['profile_id'] . "'> " . $value['first_name'] . " " . $value['last_name'] . " </option>";
            }
        }
        echo "</td><td><button type='button' class='btn btn-primary' id='assignResidentToMarkerButton'>Assign To Marker</button></td>";
    }

  ?>

  </tr>
</table>
<br />
<br />
<br />

<!-- Modal -->
<div id="confirmUnlinkResidentModal" class="modal fade" role="dialog">
<div class="modal-dialog">
<!-- Modal content --> 
<div class="modal-content">
   <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h3 class="modal-title">Unlink Resident</h3>
   </div>
   <div class="modal-body">
      <div style="font-weight: bold;" id="unlinkResidentMessage"> </div> <br />
      <div style="font-weight: bold; color: red;" id="unlinkResidentErrorMessage"> </div>
   </div>
   <div class="modal-footer">
      <button type="button" class="btn btn-primary" id="unlinkResident">Unlink Resident</button>
      <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
   </div>
</div>
</div>
</div>

<!-- Modal -->
<div id="assignResidentToMarkerModal" class="modal fade" role="dialog">
<div class="modal-dialog">
<!-- Modal content --> 
<div class="modal-content">
   <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h3 class="modal-title">Assign Resident To Marker</h3>
   </div>
   <div class="modal-body">
      <div style="font-weight: bold;" id="assignResidentToMarkerMessage"> </div> <br />
      <div style="font-weight: bold; color: red;" id="assignResidentErrorMessage"> </div>
   </div>
   <div class="modal-footer">
      <button type="button" class="btn btn-primary" id="assignResidentToMarker">Assign Resident To Marker</button>
      <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
   </div>
</div>
</div>
</div>
