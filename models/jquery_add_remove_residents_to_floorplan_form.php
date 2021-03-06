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

        $('#residentsTiedToMarker').on('click', '.show_unlink_resident_modal', function() {
            row = ($(this).closest("tr").index() - 1);

            $.post("./models/unlink_resident_model.php", {
                    marker: clickedMarker,
                    profile: $(this).val()

                },
                function(data, status) {
                    data = jQuery.parseJSON(data);
                    if (data.status.trim() === "success") {
                        row = row + 1;
                        $("#residentsTiedToMarker tr:eq(" + row + ")").remove();
                        $("#residentToFloorplanMarkerErrorMessage").empty(); // Clear the error message
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
                        $("#residentToFloorplanMarkerErrorMessage").html("There was an error unlinking the resident.");
                    }
                });
        });
        
        $('#residentsAvailableTableRow').on('click', '#assignResidentToMarkerButton', function() {
            var profile = $("#residentsAvailableSelectBox option:selected").val();
            $.post("./models/assign_resident_to_marker.php", {
                    profile: profile,
                    marker: clickedMarker
                },
                function(data, status) {
                    data = jQuery.parseJSON(data);
                    console.log(data);
                    if (data.status.trim() === "success") {
                        $("#residentsAvailableSelectBox option[value='" + profile + "']").remove();
                        $("#residentToFloorplanMarkerErrorMessage").empty(); // Clear the error message
                        $("#residentsTiedToMarker").append("<tr> <td class='resident_first_name'> " + data.first_name + " </td> <td class='resident_last_name'> " + data.last_name + " </td> <td> <button type='button' class='btn btn-danger btn-sm show_unlink_resident_modal' value='" + data.profile + "' style='width: 100%;'>Unlink Resident</button> </td> </tr>");
                        if ($('#residentsAvailableSelectBox option').length == 0) {
                            $('#residentsAvailableTableRow').html("<b> There are no available residents. </b>");
                        }
                    } else {
                        $("#residentToFloorplanMarkerErrorMessage").html("There was an error assigning the resident to this marker.");
                }
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
    <div style="font-weight: bold; color: red;" id="residentToFloorplanMarkerErrorMessage"> </div>