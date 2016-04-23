<html>
    <head>
        <?php 
            require_once("template_class.php");
            $H = new template("Directory");
            $H->show_template();
        ?>
    </head>
    <script src='controllers/directory_controller.js'></script>
    <body ng-controller='directoryController' ng-init='getCommunities();'>
	<div>Not Finished.</div>
    <div class="container-fluid" ng-show="cows">
        <div class="row">
            <div class="col-md-12" class="container-fluid" align="center">
                <div class="form-inline">
                    <select ng-show="showCommunitySelect" id="selectCommunity" ng-model="selectCommunity" class="form-control" ng-change="changeCommunity(selectCommunity);">
                        <option ng-repeat="community in communities track by community.community_id" value={{community.community_id}}>{{community.community_name}}
                        </option>
                    </select>
                    <select ng-show="showMarkerSelect" id="selectMarker" ng-model="selectMarker" class="form-control" ng-change="changeMarker(); getFloorplan(communities[selectCommunity][selectMarker].has_floorplan)">
                        <option ng-show="markers.marker_id" ng-repeat="markers in communities[selectCommunity]" value='{{markers.marker_id}}'> {{markers.name}} </option>
                    </select>
                    <select ng-show="showSelectFloor" id="selectFloot" ng-model="selectFloor" class="form-control" ng-change="changeFloor()">
                        <option ng-show="floor.floorplan_id" ng-repeat="floor in floorplans" value='{{floor.floorplan_id}}'> {{floor.floor}} </option>
                    </select>
                    <select ng-show="showSelectRoom" id="selectRoom" ng-model="selectRoom" class="form-control" ng-change="changeRoom()">
                        <option ng-show="markers.marker_id" ng-repeat="markers in floorplans[selectFloor]" value='{{markers.marker_id}}'> {{markers.marker_name}} </option>
                    </select>
                </div>
                <button class="btn btn-info btn-sm" ng-show="showEmailButton" ng-click="openEmailModal()"> Show All Emails </button>
           </div>
        </div>
     </div>
        <div id="simpleView" ng-show="showCommunity">
            <div class="row">
            <h2> {{communities[selectCommunity].community_name}} </h2>
            </div>
            <table class="col-xs-12 table table-hover">
                <tr>
                    <td> Name </td>
                    <td> Primary Phone </td>
                    <td> Secondary Phone </td>
                    <td> Primary Email </td>
                    <td> Secondary Email </td>
                </tr>
                <tbody  ng-repeat="markers in communities[selectCommunity] ">
                    <tr ng-show="users.user_id" ng-repeat="users in communities[selectCommunity][markers.marker_id]"> 
                        <td > {{users.firstname}} {{users.lastname}}</td>
                        <td ng-show="users.phone_01"> {{users.phone_01}} </td>
                        <td ng-show="!users.phone_01"> N/A </td>
                        <td ng-show="users.phone_02"> {{users.phone_02}} </td>
                        <td ng-show="!users.phone_02"> N/A </td>
                        <td ng-show="users.email_01"> {{users.email_01}} </td>
                        <td ng-show="!users.email_01"> N/A </td>
                        <td ng-show="users.email_02"> {{users.email_02}} </td>
                        <td ng-show="!users.email_02"> N/A </td>
                    </tr>
                </tbody>         
            </table>
            </div>
            
            <div id="simpleMarker" ng-show="showMarker">
                <div class="row">
                <h2> {{communities[selectCommunity].community_name}} - {{communities[selectCommunity][selectMarker].name}}</h2>
                </div>
                <table class="col-xs-12 table table-hover">
                    <tr>
                        <td> Name </td>
                        <td> Primary Phone </td>
                        <td> Secondary Phone </td>
                        <td> Primary Email </td>
                        <td> Secondary Email </td>
                    </tr>
                    <tr ng-show="users.user_id" ng-repeat="users in communities[selectCommunity][selectMarker]">
                        <td > {{users.firstname}} {{users.lastname}}</td>
                        <td ng-show="users.phone_01"> {{users.phone_01}} </td>
                        <td ng-show="!users.phone_01"> N/A </td>
                        <td ng-show="users.phone_02"> {{users.phone_02}} </td>
                        <td ng-show="!users.phone_02"> N/A </td>
                        <td ng-show="users.email_01"> {{users.email_01}} </td>
                        <td ng-show="!users.email_01"> N/A </td>
                        <td ng-show="users.email_02"> {{users.email_02}} </td>
                        <td ng-show="!users.email_02"> N/A </td>
                    </tr>
                </table>
            </div>

            <div id="simpleFloor" ng-show="showFloor">
                <div class="row">
                <h2> {{communities[selectCommunity].community_name}} - {{communities[selectCommunity][selectMarker].name}} - {{floorplans[selectFloor].floor}}</h2>
                </div>
                <table class="col-xs-12 table table-hover">
                <tr>
                    <td> Name </td>
                    <td> Primary Phone </td>
                    <td> Secondary Phone </td>
                    <td> Primary Email </td>
                    <td> Secondary Email </td>
                </tr>
                <tbody  ng-repeat="markers in floorplans[selectFloor] ">
                    <tr ng-show="users.user_id" ng-repeat="users in floorplans[selectFloor][markers.marker_id]"> 
                        <td > {{users.firstname}} {{users.lastname}}</td>
                        <td ng-show="users.phone_01"> {{users.phone_01}} </td>
                        <td ng-show="!users.phone_01"> N/A </td>
                        <td ng-show="users.phone_02"> {{users.phone_02}} </td>
                        <td ng-show="!users.phone_02"> N/A </td>
                        <td ng-show="users.email_01"> {{users.email_01}} </td>
                        <td ng-show="!users.email_01"> N/A </td>
                        <td ng-show="users.email_02"> {{users.email_02}} </td>
                        <td ng-show="!users.email_02"> N/A </td>
                    </tr>
                </tbody>         
            </table>
            </div>
    
            <div id="simpleRoom" ng-show="showRoom">
                <div class="row">
                <h2> {{communities[selectCommunity].community_name}} - {{communities[selectCommunity][selectMarker].name}} - {{floorplans[selectFloor].floor}} - {{floorplans[selectFloor][selectRoom].marker_name}}</h2>
                </div>
                <table class="col-xs-12 table table-hover">
                    <tr>
                        <td> Name </td>
                        <td> Primary Phone </td>
                        <td> Secondary Phone </td>
                        <td> Primary Email </td>
                        <td> Secondary Email </td>
                    </tr>
                    <tr ng-show="users.user_id" ng-repeat="users in floorplans[selectFloor][selectRoom]">
                        <td > {{users.firstname}} {{users.lastname}}</td>
                        <td ng-show="users.phone_01"> {{users.phone_01}} </td>
                        <td ng-show="!users.phone_01"> N/A </td>
                        <td ng-show="users.phone_02"> {{users.phone_02}} </td>
                        <td ng-show="!users.phone_02"> N/A </td>
                        <td ng-show="users.email_01"> {{users.email_01}} </td>
                        <td ng-show="!users.email_01"> N/A </td>
                        <td ng-show="users.email_02"> {{users.email_02}} </td>
                        <td ng-show="!users.email_02"> N/A </td>
                    </tr>
                </table>
            </div>
            <div id="emailListModal" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content --> 
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h3 class="modal-title">Email Lsit</h3>
                        </div>
                        <div class="modal-body">
                            <span ng-repeat="markers in communities[selectCommunity]">
                                
                                    <span ng-show="users.email_01 && primaryEmail" ng-repeat="users in communities[selectCommunity][markers.marker_id]"
                                     ng-show="primaryEmail">{{users.email_01}}, </span>
                                    <spam ng-show="users.email_02 && secondaryEmail" ng-repeat="users in communities[selectCommunity][markers.marker_id]">
                                    {{users.email_02}}, </spam>
                                    
                                
                        </span>
                        <div class="modal-footer">
                            <button type="button" ng-click="showSecondaryEmail()" ng-show="primaryEmail" class="btn btn-info" style="width: auto"> Secondary Emails </button>
                            <button type="button" ng-click="showPrimaryEmail()" ng-show="secondaryEmail" class="btn btn-info" style="width: auto"> Primary Emails </button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal" style="width: auto">Close</button>
                        </div>
                </div>
            </form>
         </div>
      </div> 
    </body>
</html>