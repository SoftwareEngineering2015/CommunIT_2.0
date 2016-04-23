<?php
   if(isset($_REQUEST["community"])) {
     $community_id = $_REQUEST["community"];
   } else {
     echo "noCommunity";
     exit;
   }

   include("db_class.php");

   $user_ids_array = array(); // Holds the user ids in the community; needed for updating the privileges of the residents
   $user_names_array = array(); // Holds the names of the residents in the communtiy
   $user_privileges_array = array(); // Holds the privileges of the residents in the communtiy

   $sql_users_in_community = "SELECT users.user_id AS user_id, users.first_name AS first_name, users.last_name AS last_name, privileges.privilege AS privilege FROM users INNER JOIN users_to_communities ON users.user_id = users_to_communities.user_id INNER JOIN privileges ON users_to_communities.privilege_id = privileges.privilege_id WHERE users_to_communities.community_id = '$community_id' ORDER BY users.user_id";

   $sql_users_in_community_result = mysqli_query($conn, $sql_users_in_community);

   $counter = 0;
   while ($row = $sql_users_in_community_result->fetch_assoc()) {
      $user_ids_array[$counter] = $row['user_id'];
      $user_names_array[$counter]['first_name'] = $row['first_name'];
      $user_names_array[$counter]['last_name'] = $row['last_name'];
      $user_privileges_array[$counter] = $row['privilege'];

      $counter++;
    }

?>

<script>

var residents = <?php echo json_encode($user_ids_array); ?>; // Holds the ids of the residents
var names = <?php echo json_encode($user_names_array); ?>;
var privileges = <?php echo json_encode($user_privileges_array); ?>;

var model_data = []; // This will hold the information to pass to the model
var residentBeingRemoved; // This will hold the id of the resident that the user wants to remove from the community
var buttonClicked; // This will hold the row of in the table of the resident that the user wants to remove from the community

$.each(names, function( index, value ) {
  if (residents[index] == localStorage.getItem("communit_user_id")) {
    if (privileges[index] == "moderator") {
      $('#residentsTable').append("<tr> <td>" + value.first_name + " </td> <td>" + value.last_name + " </td> <td class='privilegesTableCells hasSelectBox'> <select class='form-control residentPrivilegesSelectBox'><option value='resident'>Resident</option><option value='moderator' selected>Moderator</option></select> </td> <td> <button type='button' class='btn btn-danger btn-sm show_remove_residents_modal removeResidentsColumn' style='width: 100%;' value='" + residents[index] + "'>Remove Resident</button></td></tr>");
    } else if (privileges[index] == "owner") {
      $('#residentsTable').append("<tr> <td>" + value.first_name + " </td> <td>" + value.last_name + " </td> <td class='privilegesTableCells hasSelectBox'> <select class='form-control residentPrivilegesSelectBox'><option value='resident'>Resident</option><option value='moderator'>Moderator</option><option value='owner' selected>Owner</option></select> </td> <td> <button type='button' class='btn btn-danger btn-sm show_remove_residents_modal removeResidentsColumn' style='width: 100%;' value='" + residents[index] + "'>Remove Resident</button></td></tr>");
    } else if (privileges[index] == "creator") {
      $('#residentsTable').append("<tr> <td>" + value.first_name + " </td> <td>" + value.last_name + " </td> <td class='privilegesTableCells' value='creator'> Creator </td> <td> </td></tr>");
    } 
  } else if (privileges[index] == "resident") {
    $('#residentsTable').append("<tr> <td>" + value.first_name + " </td> <td>" + value.last_name + " </td> <td class='privilegesTableCells hasSelectBox'> <select class='form-control residentPrivilegesSelectBox'><option value='resident' selected>Resident</option><option value='moderator'>Moderator</option><option value='owner'>Owner</option></select> </td> <td> <button type='button' class='btn btn-danger btn-sm show_remove_residents_modal removeResidentsColumn' style='width: 100%;' value='" + residents[index] + "'>Remove Resident</button></td></tr>");
  } else if (privileges[index] == "moderator") {
    $('#residentsTable').append("<tr> <td>" + value.first_name + " </td> <td>" + value.last_name + " </td> <td class='privilegesTableCells hasSelectBox'> <select class='form-control residentPrivilegesSelectBox'><option value='resident'>Resident</option><option value='moderator' selected>Moderator</option><option value='owner'>Owner</option></select> </td> <td> <button type='button' class='btn btn-danger btn-sm show_remove_residents_modal removeResidentsColumn' style='width: 100%;' value='" + residents[index] + "'>Remove Resident</button></td></tr>");
  } else if (privileges[index] == "owner") {
    $('#residentsTable').append("<tr> <td>" + value.first_name + " </td> <td>" + value.last_name + " </td> <td class='privilegesTableCells hasSelectBox'> <select class='form-control residentPrivilegesSelectBox'><option value='resident'>Resident</option><option value='moderator'>Moderator</option><option value='owner' selected>Owner</option></select> </td> <td> <button type='button' class='btn btn-danger btn-sm show_remove_residents_modal removeResidentsColumn' style='width: 100%;' value='" + residents[index] + "'>Remove Resident</button></td></tr>");
  } else if (privileges[index] == "creator") {
    $('#residentsTable').append("<tr> <td>" + value.first_name + " </td> <td>" + value.last_name + " </td> <td class='privilegesTableCells' value='creator'> Creator </td> <td> </td></tr>");
  }
});

// Jquery Actions
$(document).ready(function() {

    if (currentUsersPrivilege == 3) {
      $(".removeResidentsColumn").hide();
    }

    $('#updateResidentsPrivilegesModal').on('show.bs.modal', function() {
        $(this).find('.modal-body').css({
            width: 'auto', //probably not needed
            height: 'auto', //probably not needed 
            'max-height': '100%'
        });
    });

    $("#show_update_residents_privileges_modal").click(function(index) {
        var buttonClicked = $(this).val();
        $( ".privilegesTableCells" ).each(function() {
          if ($(this).is(".hasSelectBox")) {
            model_data.push($(this).find(":selected").val());
          } else {
            model_data.push("creator");
          }
        });
        $("#updateResidentsPrivilegesMessage").html("<b> Are you sure you want to update the privileges of the residents in the community? </b>");
        $("#updateResidentsPrivilegesModal").modal("show");
    });

    $("#updateResidentsPrivileges").click(function() {

        $.post("./models/update_residents_privileges.php", {
                community: $("#updateResidentsPrivileges").val(),
                users: residents,
                privileges: model_data
            },
            function(data, status) {
                if (data.trim() === "success") {
                  $('#updateResidentsPrivilegesModal').modal('hide');
                  model_data.length = 0; // Clear the array so that the list can be filled again if they change privileges of residents
                } else {
                  model_data.length = 0; // Clear the array so that the list can be filled again if they change privileges of residents
                  $("#updateResidentsPrivilegesErrorMessage").html("There was an error updating the settings.");
                }
            });
    });

    $(".show_remove_residents_modal").click(function() {
        buttonClicked = $(this).closest('td').parent()[0].sectionRowIndex; // Need this to check which row we are deleting; Have to minus one because the table header row;
        residentBeingRemoved = this.value;
        $("#remove_residents_modal").modal("show");    
    });

    $("#removeResident").click(function() {
        $.post("./models/remove_resident_from_community_model.php", {
                community: $(this).val(),
                user: residentBeingRemoved,
            },
            function(data, status) {
                if (data.trim() === "success") {
                  $('#remove_residents_modal').modal('hide');
                  $("#residentsTable tr:eq(" + buttonClicked + ")").remove();
                } else {
                  $("#removeResidentsErrorMessage").html("There was an error updating the settings.");
                }
            });
    });
    $("#updateResidentsPrivilegesModal").on('hidden.bs.modal', function() {
        $("#updateResidentsPrivilegesErrorMessage").empty(); // Clear the error message
    });

    $("#remove_residents_modal").on('hidden.bs.modal', function() {
        $("#removeResidentsErrorMessage").empty(); // Clear the error message
    });
});
</script>

<h3> Residents In Community </h3>
<button type='button' class='btn btn-primary btn-sm' style='width: 100%;' id="show_update_residents_privileges_modal" value="<?php echo $community_id; ?>">Update Residents</button>
<hr style="border: none; width: 1px">
<table class="table table-striped table-hover table-condensed" id="residentsTable">
   <tr>
      <th> First Name </th>
      <th> Last Name </th>
      <th> Privilege <a class="glyphicon glyphicon-question-sign" style="text-decoration: none" title="Resident - Can only view information in the community &#xAModerator - Can edit the community &#xAOwner - Can edit / delete the community"> </a> </th>
      <th class="removeResidentsColumn"> Remove Resident </th>
   </tr>
</table>

<!-- Modal -->
<div id="updateResidentsPrivilegesModal" class="modal fade" role="dialog">
<div class="modal-dialog">
<!-- Modal content --> 
<div class="modal-content">
   <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h3 class="modal-title">Update Residents Privileges</h3>
   </div>
   <div class="modal-body">
      <div style="font-weight: bold" id="updateResidentsPrivilegesMessage"> </div>
      <div style="font-weight: bold; color: red" id="updateResidentsPrivilegesErrorMessage"> </div>
   </div>
   <div class="modal-footer">
      <button type="button" class="btn btn-primary" id="updateResidentsPrivileges" value="<?php echo $community_id; ?>">Update Residents Privileges</button>
      <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
   </div>
</div>
</div>
</div>

<!-- Modal -->
<div id="remove_residents_modal" class="modal fade" role="dialog">
<div class="modal-dialog">
<!-- Modal content --> 
<div class="modal-content">
   <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h3 class="modal-title">Remove Resident</h3>
   </div>
   <div class="modal-body">
      <b> Are you sure you want to remove this resident from the community?
      <br />
      <span style="color: red;"> This will delete all information tied to this resident in the community. This action cannot be undone! </span> <br /><br />
      <span style="color: red;" id="removeResidentsErrorMessage"> </span></b>
   </div>
   <div class="modal-footer">
      <button type="button" class="btn btn-primary" id="removeResident" value="<?php echo $community_id; ?>">Remove Resident</button>
      <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
   </div>
</div>
</div>
</div>