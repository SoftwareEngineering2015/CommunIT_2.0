<html>
    <head>
        <?php 
            require_once("template_class.php");
            $H = new template("Directory");
            $H->show_template();
        ?>
    </head>
    <script src='controllers/directory_controller.js'></script>
    <body ng-controller='directory_controller' ng-init='getCommunities();'>
        <select id="selectCommunity" ng-model="selectCommunity" class="form-control" ng-change="changeCommunity(selectCommunity);">
            <option ng-repeat="community in communities track by community.community_id" value={{community.community_id}}>{{community.community_name}}
            </option>
        </select>
        <div id="simpleView" ng-show="showSimple">
            {{selectedCommunity}}
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
    </body>
</html>