<?php
   require_once( "template_class.php");       // css and headers
   $H = new template( "Community Requests" );
   $H->show_template( );
       
?>
   <!-- Google API KEY for accessing a broader spectrum of Google APIs-->
   <script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyCTUwndh9ZED3trNlGZqcCEjkAb5-bpoUw"></script>
   <script type="text/javascript" src="js/colorpins.js"></script>

   <script src="js/date.js"> </script> <!-- For the date stuff -->

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
               <div ng-show="!detailedRequest">
                  <h1> Requests To Join Communities </h1>
                  <hr>
                  <div id="requests">
                     <table class="table table-borderless table-responsive">
                        <tr>
                           <th> Community Name </th>
                           <th> Date Sent </th>
                           <th> </th>
                        <tr ng-repeat="x in requested_array track by $index">
                           <td style="color: #006699; font-weight: bold; cursor: pointer;" ng-click="showDetailedRequest($index)"> {{ x.community_name}} </td>
                           <td ng-click="showDetailedRequest($index)" style="cursor: pointer;"> {{ x.date_created }} </td>
                           <td> <button type="button" class="btn btn-danger btn-sm" ng-click="show_delete_request_to_community_modal(x.community_id);">Delete Request</button></td>
                        </tr>
                     </table>
                  </div>
               </div>
               <div ng-show="detailedRequest">
                  <div align="center">
                  <br />
                     <button class='btn btn-primary btn-md' style='width:auto' ng-click="backToRequests()"><span class="glyphicon glyphicon-arrow-left"/> Back To Requests </button>
                  </div>
                  <h3> {{ requested_array[request_row_clicked].community_name }} </h3>
                  <table class="table  table-borderless table-responsive table-striped">
                     <tr> 
                        <th style="color: #006699"> City: </th> 
                        <td> {{ requested_array[request_row_clicked].city }} </td> 
                     </tr>
                     <tr> 
                        <th style="color: #006699"> State / Province: </th> 
                        <td> {{ requested_array[request_row_clicked].state }} </td> 
                     </tr>
                     <tr> 
                        <th style="color: #006699"> Country: </th> 
                        <td> {{ requested_array[request_row_clicked].country }} </td> 
                     </tr>
                     <tr> 
                        <th style="color: #006699"> Description: </th> 
                        <td> {{ requested_array[request_row_clicked].community_description }} </td> 
                     </tr>
                  </table>
                  <div align="center">
                     <button type='button' class='btn btn-success btn-sm' style='width:auto' ng-click='load_map_into_modal(requested_array[request_row_clicked].community_id)' data-toggle='modal' data-target='#view_community_modal'>View Community</button> </td>
                  </div> 
               </div>
            </div>
            <div class="col-md-6" class="container-fluid">
               <div ng-show="!detailedInvite">
                  <h1> Invites To Join Communities </h1>
                  <hr>
                  <div id="invites">
                     <table class="table table-borderless table-responsive">
                        <tr>
                           <th> Community Name </th>
                           <th> Date Sent </th>
                           <th> </th>
                           <th> </th>
                        <tr ng-repeat="x in invited_array track by $index">
                           <td style="color: #006699; font-weight: bold;  cursor: pointer;" ng-click="showDetailedInvite($index)"> {{ x.community_name}} </td>
                           <td ng-click="showDetailedInvite($index)" style="cursor: pointer;"> {{ x.date_created }} </td>
                           <td> <button type="button" class="btn btn-primary btn-sm" ng-click="show_accept_invite_to_community_modal(x.community_id);" style="width: 100%;">Accept Invite</button> </td>
                           <td> <button type="button" class="btn btn-danger btn-sm" ng-click="show_delete_invite_to_community_modal(x.community_id);" style="width: 100%;">Delete Invite</button> </td>
                        </tr>
                     </table>
                  </div>
               </div>
               <div ng-show="detailedInvite">
                  <div align="center">
                  <br />
                     <button class='btn btn-primary btn-md' style='width:auto' ng-click="backToInvites()"><span class="glyphicon glyphicon-arrow-left"/> Back To Invites </button>
                  </div>
                  <h3> {{ invited_array[invite_row_clicked].community_name }} </h3>
                  <table class="table  table-borderless table-responsive table-striped">
                     <tr> 
                        <th style="color: #006699"> City: </th> 
                        <td> {{ invited_array[invite_row_clicked].city }} </td> 
                     </tr>
                     <tr> 
                        <th style="color: #006699"> State / Province: </th> 
                        <td> {{ invited_array[invite_row_clicked].state }} </td> 
                     </tr>
                     <tr> 
                        <th style="color: #006699"> Country: </th> 
                        <td> {{ invited_array[invite_row_clicked].country }} </td> 
                     </tr>
                     <tr> 
                        <th style="color: #006699"> Description: </th> 
                        <td> {{ invited_array[invite_row_clicked].community_description }} </td> 
                     </tr>
                  </table>
                  <div align="center">
                     <button type='button' class='btn btn-success btn-sm' style='width:auto' ng-click='load_map_into_modal(invited_array[invite_row_clicked].community_id)' data-toggle='modal' data-target='#view_community_modal'>View Community</button> </td>
                     <button type="button" class="btn btn-primary btn-sm" ng-click="show_accept_invite_to_community_modal(invited_array[invite_row_clicked].community_id);" style="width: auto;">Accept Invite</button>
                  </div> 
               </div>
            </div>
         </div>
      </div>
      <!-- Modal -->
      <div id="view_community_modal" class="modal fade" role="dialog">
         <div class="modal-dialog">
            <!-- Modal content -->
            <div class="modal-content">
               <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title" id="community_name"></h4>
               </div>
               <div class="modal-body" id="community_map" style="height: 450px"></div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
               </div>
            </div>
         </div>
      </div>
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