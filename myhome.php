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
               <div ng-show="!detailedCreated">
                  <div ng-show="hasCreatedCommunities">
                     <h1> Created Communities </h1>
                     <hr>
                     <table class="table table-borderless table-responsive">
                        <tr> 
                           <th> Community Name </th>
                           <th> </th>
                           <th> </th>
                           <th> </th>
                        </tr>
                        <tr ng-repeat="x in owned_communities_array track by $index">
                           <td style="color: #006699; font-weight: bold;  cursor: pointer;" ng-click="showDetailedCreated($index)"> {{ x.community_name}} </td>
                           <td align="center"><a type="button" class="btn btn-primary btn-sm" href="communitymap.php?community={{ x.community_id }}">Visit</a></td>
                           <td align="center"><a type="button" class="btn btn-primary btn-sm" href="editcommunitysettings.php?community={{ x.community_id }}">Edit</a></td>
                           <td align="center"><button type="button" class="btn btn-danger btn-sm" ng-click="show_delete_community_modal(x.community_id);">Delete</button></td>
                        </tr>
                     </table>
                  </div>
                  <div ng-show="!hasCreatedCommunities">
                     <h3> You have not created any communities. </h3>
                  </div>
                  <br />
                  <a type="button" class="btn btn-primary btn-md" href="createcommunity.php" ng-show="!hide_owned_communities_button">Create A Community</a>
               </div>
               <div ng-show="detailedCreated">
                  <div align="center">
                  <br />
                     <button class='btn btn-primary btn-md' style='width:auto' ng-click="backToCreated()">
                     <span class="glyphicon glyphicon-arrow-left"></span> Back To Created Communities </button>
                  </div>
                  <h3> {{ owned_communities_array[owned_row_clicked].community_name }} </h3>
                  <table class="table  table-borderless table-responsive table-striped">
                     <tr> 
                        <th style="color: #006699"> City: </th> 
                        <td> {{ owned_communities_array[owned_row_clicked].city }} </td> 
                     </tr>
                     <tr> 
                        <th style="color: #006699"> State / Province: </th> 
                        <td> {{ owned_communities_array[owned_row_clicked].state }} </td> 
                     </tr>
                     <tr> 
                        <th style="color: #006699"> Country: </th> 
                        <td> {{ owned_communities_array[owned_row_clicked].country }} </td> 
                     </tr>
                     <tr> 
                        <th style="color: #006699"> Description: </th> 
                        <td> {{ owned_communities_array[owned_row_clicked].community_description }} </td> 
                     </tr>
                  </table>
               </div>
            </div>
            <div class="col-md-6" class="container-fluid">
               <div ng-show="!detailedJoined">
                  <div ng-show="hasJoinedCommunities">
                     <h1> Joined Communities </h1>
                     <hr>
                     <table class="table table-borderless table-responsive">
                        <tr> 
                           <th> Community Name </th>
                           <th> </th>
                           <th> </th>
                           <th> </th>
                           <th> </th>
                        </tr>
                        <tr ng-repeat="x in joined_communities_array track by $index">
                           <td style="color: #006699; font-weight: bold; cursor: pointer;" ng-click="showDetailedJoined($index)"> <span style="text-transform: capitalize;"> {{ x.privilege }} </span> Of {{ x.community_name}} </td>
                           
                           <td align="center" ng-if="x.privilege == 'owner'"><a type="button" class="btn btn-primary btn-sm" href="communitymap.php?community={{ x.community_id }}">Visit</a></td>
                           <td align="center" ng-if="x.privilege == 'owner'"><a type="button" class="btn btn-primary btn-sm" href="editcommunitysettings.php?community={{ x.community_id }}">Edit</a></td>
                           <td align="center" ng-if="x.privilege == 'owner'"><button type="button" class="btn btn-danger btn-sm" ng-click="show_leave_community_modal(x.community_id);">Leave</button></td>
                           <td align="center" ng-if="x.privilege == 'owner'"><button type="button" class="btn btn-danger btn-sm" ng-click="show_delete_community_modal(x.community_id);">Delete</button></td>
                        
                           <td align="center" ng-if="x.privilege == 'moderator'"><a type="button" class="btn btn-primary btn-sm" href="communitymap.php?community={{ x.community_id }}">Visit</a></td>
                           <td align="center" ng-if="x.privilege == 'moderator'"><a type="button" class="btn btn-primary btn-sm" href="editcommunitysettings.php?community={{ x.community_id }}">Edit</a></td>
                           <td align="center" ng-if="x.privilege == 'moderator'"><button type="button" class="btn btn-danger btn-sm" ng-click="show_leave_community_modal(x.community_id);">Leave</button></td>
                           <td align="center" ng-if="x.privilege == 'moderator'"></td>

                           <td align="center" ng-if="x.privilege == 'resident'"><a type="button" class="btn btn-primary btn-sm" href="communitymap.php?community={{ x.community_id }}">Visit</a></td>
                           <td align="center" ng-if="x.privilege == 'resident'"><button type="button" class="btn btn-danger btn-sm" ng-click="show_leave_community_modal(x.community_id);">Leave</button></td>
                           <td align="center" ng-if="x.privilege == 'resident'"></td>
                           <td align="center" ng-if="x.privilege == 'resident'"></td>

                        </tr>
                     </table>
                  </div>
                  <div ng-show="!hasJoinedCommunities">
                     <h3> You have not joined a community. </h3>
                  </div>
                  <br />
                  <a type="button" class="btn btn-primary btn-md" href="communitysearch.php" ng-show="!hide_join_communities_button">Search For A Community To Join</a>
               </div>
               <div ng-show="detailedJoined">
                  <div align="center">
                  <br />
                     <button class='btn btn-primary btn-md' style='width:auto' ng-click="backToJoined()"><span class="glyphicon glyphicon-arrow-left"></span> Back To Joined Communities </button>
                  </div>
                  <h3> {{ joined_communities_array[joined_row_clicked].community_name }} </h3>
                  <table class="table  table-borderless table-responsive table-striped">
                     <tr> 
                        <th style="color: #006699"> City: </th> 
                        <td> {{ joined_communities_array[joined_row_clicked].city }} </td> 
                     </tr>
                     <tr> 
                        <th style="color: #006699"> State / Province: </th> 
                        <td> {{ joined_communities_array[joined_row_clicked].state }} </td> 
                     </tr>
                     <tr> 
                        <th style="color: #006699"> Country: </th> 
                        <td> {{ joined_communities_array[joined_row_clicked].country }} </td> 
                     </tr>
                     <tr> 
                        <th style="color: #006699"> Description: </th> 
                        <td> {{ joined_communities_array[joined_row_clicked].community_description }} </td> 
                     </tr>
                  </table>
               </div>
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
