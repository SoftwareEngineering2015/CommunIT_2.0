<?php
   require_once( "template_class.php");       // css and headers
   $H = new template( "Community Requests" );
   $H->show_template( );
       
?>

   <style>
      hr {
      border-color: white;
      }
      .table {
      border-bottom:0px !important;
      }
      .table th, .table td {
      border: 0px !important;
      }
      .fixed-table-container {
      border:0px !important;
      }
   </style>
   <script src='controllers/joincommunityrequests_controller.js'></script>
   <body ng-controller='joincommunityrequestsController'>
   <div id="welcomejumbotron" class="jumbotron">
    <div style="padding-left: 5%;">
      <h2 id="welcomejumbotrontext">Welcome {{userfirstname}} {{userlastname}}!</h2>
      <h4 id="welcomejumbotrontext">Here are your <b>Community Invitations</b> and <b>Join Requests.</b></h4>
    </div>
   </div>
      <div class="container-fluid">
         <div class="row">
            <div class="col-md-6" class="container-fluid">
               <h1> Requests To Join Communities </h1>
               <hr>
               <div id="requests">
                  <div ng-repeat="x in requested_array">
                  <table class="table table-borderless">
                     <tr>
                        <td colspan = "3">
                           <h4> <span style="color: black;"> Community Name - </span> {{ x.community_name }} </h4>
                        </td>
                     </tr>
                     <tr>
                        <td colspan = "3"> <b style="color: black;"> Description: </b> <br /> {{ x.community_description }} </td>
                     </tr>
                     <tr>
                        <td> <button type="button" class="btn btn-danger btn-md" ng-click="show_delete_request_to_community_modal(x.community_id);">Delete Request</button></td>
                        <td> </td>
                        <td> </td>
                     </tr>
                  </table>
                  <br />
                  </div>
               </div>
            </div>
            <div class="col-md-6" class="container-fluid">
               <h1> Invites To Join Communities </h1>
               <hr>
               <div id="invites">
                  <div ng-repeat="x in invited_array" >
                  <table class="table table-borderless">
                     <tr>
                        <td colspan = "3">
                           <h4> {{ x.community_name }} </h4>
                        </td>
                     </tr>
                     <tr>
                        <td colspan = "3"> 
                           <b> Description: </b> <br /> {{ x.community_description }} 
                        </td>
                     </tr>
                     <tr>
                        <td> <button type="button" class="btn btn-primary btn-md" ng-click="show_accept_invite_to_community_modal(x.community_id);" style="width: 100%;">Accept Invite</button> </td>
                        <td> <button type="button" class="btn btn-danger btn-md" ng-click="show_delete_invite_to_community_modal(x.community_id);" style="width: 100%;">Delete Invite</button> </td>
                        <td> &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp </td>
                     </tr>
                  </table>
                  <br />
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div>
        <!-- Modal -->
      <div id="delete_request_to_community_modal" class="modal fade" role="dialog">
         <div class="modal-dialog">
            <!-- Modal content --> 
            <div class="modal-content">
               <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h3 class="modal-title">Delete Request</h3>
               </div>
               <div class="modal-body">
                  <b> Are you sure you want to delete this request? <br /> <br /> <span style="color: red;" id="deleteRequestMessage"> </span> </b> 
                </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-primary" style="width: auto" ng-model="deleteRequestToCommunityButton" ng-click="delete_request_to_community();">Delete Request</button>
                  <button type="button" class="btn btn-danger" data-dismiss="modal" style="width: auto">Close</button>
               </div>
            </div>
            </form>
         </div>
      </div>
      <!-- Modal -->
      <div id="delete_invite_to_community_modal" class="modal fade" role="dialog">
         <div class="modal-dialog">
            <!-- Modal content --> 
            <div class="modal-content">
               <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h3 class="modal-title">Delete Invite</h3>
               </div>
               <div class="modal-body">
                  <b> Are you sure you want to delete this invite? <br /> <br /> <span style="color: red;" id="deleteInvitedMessage"> </span> </b> 
                </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-primary" style="width: auto" ng-model="deleteInviteToCommunityButton" ng-click="delete_invite_to_community();">Delete Invite</button>
                  <button type="button" class="btn btn-danger" data-dismiss="modal" style="width: auto">Close</button>
               </div>
            </div>
            </form>
         </div>
      </div>
      <!-- Modal -->
      <div id="accept_invite_to_community_modal" class="modal fade" role="dialog">
         <div class="modal-dialog">
            <!-- Modal content --> 
            <div class="modal-content">
               <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h3 class="modal-title">Accept Invite</h3>
               </div>
               <div class="modal-body">
                  <b> Are you sure you want to accept this invite? <br /> <br /> <span style="color: red;" id="acceptInvitedMessage"> </span> </b> 
                </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-primary" style="width: auto" ng-model="acceptInviteToCommunityButton" ng-click="accept_invite_to_community();">Accept Invite</button>
                  <button type="button" class="btn btn-danger" data-dismiss="modal" style="width: auto">Close</button>
               </div>
            </div>
            </form>
         </div>
      </div>
   </body>
</html>