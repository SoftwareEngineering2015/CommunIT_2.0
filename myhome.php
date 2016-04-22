<?php
   require_once( "template_class.php");       // css and headers
   $H = new template( "My Communities" );
   $H->show_template( );

?>

   <style>
      hr {
      border-color: white;
      }
      .btn {
      width: 100%;
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
   <script src='controllers/myhome_controller.js'></script>
   <body ng-controller='myhomeController'>
      <div  id="welcomejumbotron" class="jumbotron">
       <div style="padding-left: 5%;">
         <h2 id="welcomejumbotrontext" ng-show="userfirstname">Welcome {{userfirstname}} {{userlastname}}!</h2>
         <h2 id="welcomejumbotrontext" ng-show="!userfirstname">Welcome to CommunIT!</h2>
         <h4 id="welcomejumbotrontext">Here you can manage your communities.</h4>
       </div>
      </div>
      <div class="container-fluid">
         <div class="row">
            <div class="col-md-6" class="container-fluid">
               <h1> Created Communities </h1>
               <hr>
               <div ng-repeat="x in owned_communities_array" class="owned_communities">
                  <table class="table table-borderless">
                     <tr>
                        <td colspan = "4">
                           <h4> {{ x.community_name }} </h4>
                        </td>
                     </tr>
                     <tr>
                        <td colspan = "4"> <b style="color: black;"> Description: </b> <br /> {{ x.community_description }} </td>
                     </tr>
                     <tr>
                        <td align="center"><a type="button" class="btn btn-primary btn-md" href="communitymap.php?community={{ x.community_id }}">Visit Community</a></td>
                        <td align="center"><a type="button" class="btn btn-primary btn-md" href="editcommunitysettings.php?community={{ x.community_id }}">Edit Community Settings</a></td>
                        <td align="center"><button type="button" class="btn btn-danger btn-md" ng-click="show_delete_community_modal(x.community_id);">Delete Community</button></td>
                     </tr>
                  </table>
                  <br />
               </div>
               <a type="button" class="btn btn-primary btn-lg" href="createcommunity.php" ng-show="!hide_owned_communities_button">Create A Community</a>
            </div>
            <div class="col-md-6" class="container-fluid">
               <h1> Joined Communities </h1>
               <hr>
               <div ng-repeat="x in joined_communities_array ">
                  <table class="table table-borderless">
                     <tr>
                        <td colspan = "4">
                           <h4> {{ x.community_name }} - <span style="color:red; text-transform: capitalize;"> {{ x.privilege }} </span> </h4>
                        </td>
                     </tr>
                     <tr>
                        <td colspan = "4">
                           <b style="color: black;"> Description: </b> <br /> {{ x.community_description }}
                        </td>
                     </tr>
                     <tr ng-if="x.privilege == 'owner'">
                        <td align="center"><a type="button" class="btn btn-primary btn-md" href="communitymap.php?community={{ x.community_id }}">Visit Community</a></td>
                        <td align="center"><button type="button" class="btn btn-danger btn-md" ng-click="show_leave_community_modal(x.community_id);">Leave Community</button></td>
                        <td align="center"><a type="button" class="btn btn-primary btn-md" href="editcommunitysettings.php?community={{ x.community_id }}">Edit Community Settings</a></td>
                        <td align="center"><button type="button" class="btn btn-danger btn-md" ng-click="show_delete_community_modal(x.community_id);">Delete Community</button></td>
                     </tr>
                     <tr ng-if="x.privilege == 'moderator'">
                        <td align="center"><a type="button" class="btn btn-primary btn-md" href="communitymap.php?community={{ x.community_id }}">Visit Community</a></td>
                        <td align="center"><a type="button" class="btn btn-primary btn-md" href="editcommunitysettings.php?community={{ x.community_id }}">Edit Community Settings</a></td>
                        <td align="center"><button type="button" class="btn btn-danger btn-md" ng-click="show_leave_community_modal(x.community_id);">Leave Community</button></td>
                        <td align="center">&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp</td>
                     </tr>
                     <tr ng-if="x.privilege == 'resident'">
                        <td align="center" colspan='1'><a type="button" class="btn btn-primary btn-md" href="communitymap.php?community={{ x.community_id }}">Visit Community</a></td>
                        <td align="center"><button type="button" class="btn btn-danger btn-md" ng-click="show_leave_community_modal(x.community_id);">Leave Community</button></td>
                        <td align="center">&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp</td>
                        <td align="center">&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp</td>
                     </tr>
                  </table>
                  <br />
               </div>
               <a type="button" class="btn btn-primary btn-lg" href="communitysearch.php" ng-show="!hide_join_communities_button">Search For A Community To Join</a>
            </div>
         </div>
      </div>
      <div>
      <!-- Modal -->
      <div id="delete_community_modal" class="modal fade" role="dialog">
         <div class="modal-dialog">
            <!-- Modal content -->
            <div class="modal-content">
               <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h3 class="modal-title">Delete Community</h3>
               </div>
               <div class="modal-body">
                  <span class="text-danger" ng-show="errorMsgModal">{{errorMsgModal}}</span>
                  <b> Are you sure you want to delete this community?
                  <br />
                  <span style="color: red;"> This will permanently delete all information tied to this community. This action cannot be undone! </span>
                  <br /><br /> <span style="color: red;" id="deleteCommunityMessage"></span></b>
               </div>
               <div class="modal-footer">
                  <button type="submit" class="btn btn-primary" style="width: auto" ng-model="deleteCommunityButton" ng-click="delete_community();">Delete Community</button>
                  <button type="button" class="btn btn-danger" data-dismiss="modal" style="width: auto">Close</button>
               </div>
            </div>
            </form>
         </div>
      </div>

      <!-- Modal -->
      <div id="leave_community_modal" class="modal fade" role="dialog">
         <div class="modal-dialog">
            <!-- Modal content -->
            <div class="modal-content">
               <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h3 class="modal-title">Leave Community</h3>
               </div>
               <div class="modal-body">
                  <span class="text-danger" ng-show="errorLeaveMsgModal">{{errorLeaveMsgModal}}</span>
                  <b> Are you sure you want to leave this community?
                  <br /><br /> <span style="color: red;" id="leaveCommunityMessage"></span></b>
               </div>
               <div class="modal-footer">
                  <button type="submit" class="btn btn-primary" style="width: auto" ng-model="leaveCommunityButton" ng-click="leave_community();">Leave Community</button>
                  <button type="button" class="btn btn-danger" data-dismiss="modal" style="width: auto">Close</button>
               </div>
            </div>
            </form>
         </div>
      </div>
   </body>
</html>
