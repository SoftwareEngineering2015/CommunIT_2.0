<?php

// Get the community id from the post for which community map to display
if(isset($_REQUEST["community"])) {
  $community_id = $_REQUEST["community"];
} else {
  echo "noCommunity";
  exit;
}

include("db_class.php");

$users_array = array();

$sql_community_requests = "SELECT * FROM requests_to_join_communities INNER JOIN users ON requests_to_join_communities.user_id = users.user_id WHERE requests_to_join_communities.community_id = '$community_id'";
$sql_community_requests_result = mysqli_query($conn, $sql_community_requests);

if (mysqli_num_rows($sql_community_requests_result) == 0 ) {
  echo "<h3> Send Invites </h3>
        <hr style='border: none; width: 1px;'>
        <table class='table table-striped table-hover table-condensed'>
          <tr>
            <td> <input type='text' class='form-control' id='invitedTextField' placeholder='Username'> </td>
            <td> <button type='button' class='btn btn-primary btn-md' style='width:100%' onclick='send_invite()'> Send Invite </button> </td>
          </tr>
          <tr>
            <td colspan='2'> <span style='color:red;' id='sendInviteMessageError'> </span> <span style='color:green;' id='sendInviteMessageSuccess'> </span> </td>
          </tr>
        </table>";

  echo "<br />
        <h3> Requests / Invites </h3>
        <hr style='border: none; width: 1px;'>
        <b> There are currently no requests / invites for your community. </b>";

} else { 

  echo "<h3> Send Invites </h3>
        <hr style='border: none; width: 1px;'>
        <table class='table table-striped table-hover table-condensed'>
          <tr>
            <td> <input type='text' class='form-control' id='invitedTextField' placeholder='Username'> </td>
            <td> <button type='button' class='btn btn-primary btn-md' style='width:100%' onclick='send_invite()'> Send Invite </button> </td>
          </tr>
          <tr>
            <td colspan='2'> <span style='color:red;' id='sendInviteMessageError'> </span> <span style='color:green;' id='sendInviteMessageSuccess'> </span> </td>
          </tr>
        </table>
        <br />
        <h3> Requests / Invites </h3>
        <hr style='border: none; width: 1px;'>
        <table class='table table-striped table-hover table-condensed' id='residentRequestsTable'>
          <tr> 
            <th> Username </th>
            <th> First Name </th>
            <th> Last Name </th>
            <th> </th>
            <th> </th>
          </tr>";

  $sql_community_requests_result = mysqli_query($conn, $sql_community_requests);
  while($row = $sql_community_requests_result->fetch_assoc()){
    array_push($users_array, $row['user_id']);

    echo "<tr> <td> " . $row['username'] . " </td>";
    echo "<td> " . $row['first_name'] . " </td>";
    echo "<td> " . $row['last_name'] . " </td>";
    if ($row['requested_or_invited'] == 0) {
      echo "<td> <button type='button' class='btn btn-primary btn-sm acceptResidentButton' style='width: 100%;'> Accept Resident </button></td>";
      echo "<td> <button type='button' class='btn btn-danger btn-sm deleteRequestInviteButton' style='width: 100%;'> Decline Request </button> </td></tr>";
    } else {
      echo "<td> </td><td> <button type='button' class='btn btn-danger btn-sm deleteRequestInviteButton' style='width: 100%;'> Delete Invite </button> </td></tr>";
    }
  }

  echo "</table>";
}

?>

<script>
var users = <?php echo json_encode($users_array); ?>;

var tableRowClicked;
var userClicked;

$(".acceptResidentButton").click(function(event) {
    tableRowClicked = ($(this).closest("tr").index());
    userClicked = users[$(".acceptResidentButton").index(this)];
    document.getElementById("approveResidentButton").value = users[$(".acceptResidentButton").index(this)];
    $("#approveResidentModal").modal("show");
});

$("#approveResidentButton").click(function(event) {
    $.post(
        "models/accept_resident_into_community.php", {
            community: $.urlParam('community'), // Get the community id from the url
            user: userClicked
        },
        function(data) {
            if (data.trim() == "success") {
                $("#approveResidentModal").modal("hide");
                $("#residentRequestsTable tr:eq(" + tableRowClicked + ")").remove();
            } else {
                $("#errorMessageAcceptResident").html("There was an error accepting the resident.");
            }
        }
    );
});

$(".deleteRequestInviteButton").click(function(event) {
    tableRowClicked = ($(this).closest("tr").index());
    userClicked = users[$(".deleteRequestInviteButton").index(this)];
    document.getElementById("deleteRequestInvite").value = users[$(".acceptResidentButton").index(this)];
    $("#deleteRequestInviteModal").modal("show");
});

$("#deleteRequestInvite").click(function(event) {
    $.post(
        "models/delete_request_invite_model.php", {
            community: $.urlParam('community'), // Get the community id from the url
            user: userClicked
        },
        function(data) {
            if (data.trim() == "success") {
                $("#deleteRequestInviteModal").modal("hide");
                $("#residentRequestsTable tr:eq(" + tableRowClicked + ")").remove();
            } else {
                $("#errorMessageAcceptResident").html("There was an error deleting the request / invite.");
            }
        }
    );
});

function send_invite() {
    if (document.getElementById("invitedTextField").value == "") {
      document.getElementById("sendInviteMessageSuccess").innerHTML = "";
      document.getElementById("sendInviteMessageError").innerHTML = "No input for username.";
    } else {
        $.post(
            "models/send_invite_model.php", {
                community: $.urlParam('community'), // Get the community id from the url
                user: document.getElementById("invitedTextField").value
            },
            function(data) {
                if (document.getElementById("invitedTextField").value == "") {
                    document.getElementById("sendInviteMessageError").innerHTML = "No input for username.";
                }
                if (data.trim() == "noUser") {
                    document.getElementById("sendInviteMessageSuccess").innerHTML = "";
                    document.getElementById("sendInviteMessageError").innerHTML = document.getElementById("invitedTextField").value + " does not exist.";
                } else if (data.trim() == "alreadyJoined") {
                    document.getElementById("sendInviteMessageSuccess").innerHTML = "";
                    document.getElementById("sendInviteMessageError").innerHTML = document.getElementById("invitedTextField").value + " is already in your community.";
                } else if (data.trim() == "alreadyInvited") {
                    document.getElementById("sendInviteMessageSuccess").innerHTML = "";
                    document.getElementById("sendInviteMessageError").innerHTML = document.getElementById("invitedTextField").value + " already has an invite.";
                } else if (data.trim() == "fail") {
                    document.getElementById("sendInviteMessageSuccess").innerHTML = "";
                    document.getElementById("sendInviteMessageError").innerHTML = "There was an error sending the invite.";
                } else if (data.trim() == "success") {
                    document.getElementById("sendInviteMessageError").innerHTML = "";
                    document.getElementById("sendInviteMessageSuccess").innerHTML = "Invitation sent to " + document.getElementById("invitedTextField").value;
                }
            }
        );
    }
  }

$('#approveResidentModal').on('hidden.bs.modal', function() {
  $("#errorMessageAcceptResident").empty();
})
</script>

   <!-- Modal -->
<div id="approveResidentModal" class="modal fade" role="dialog">
<div class="modal-dialog">
<!-- Modal content --> 
<div class="modal-content">
   <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h3 class="modal-title">Accept Resident</h3>
   </div>
   <div class="modal-body">
      <b> Would you like to accept this resident into the community? </b> 
      <br />
      <span style="color: red" id="errorMessageAcceptResident"> </span>
   </div>
   <div class="modal-footer">
      <button type="button" class="btn btn-primary" id="approveResidentButton">Accept Resident</button>
      <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
   </div>
</div>
</div>
</div>

   <!-- Modal -->
<div id="deleteRequestInviteModal" class="modal fade" role="dialog">
<div class="modal-dialog">
<!-- Modal content --> 
<div class="modal-content">
   <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h3 class="modal-title">Delete Request / Invite</h3>
   </div>
   <div class="modal-body">
      <b> Are you sure you want to delete this request / invite? </b> 
      <br />
      <span style="color: red" id="errorMessageDeleteRequestInvite"> </span>
   </div>
   <div class="modal-footer">
      <button type="button" class="btn btn-primary" id="deleteRequestInvite">Delete Request / Invite</button>
      <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
   </div>
</div>
</div>
</div>