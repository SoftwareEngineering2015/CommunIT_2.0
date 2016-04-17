<!-- Here is where we delcare our app and controller -->
<html>
  <head>
    <?php

    require_once( "template_class.php");       // css and headers
    $H = new template( "CommunIT Profile" );
    $H->show_template( );

    ?>
    <!-- Here we include bootstrap, angularjs and our controller -->
    <link rel='stylesheet' type='text/css' href='css/bootstrap.css'>
  <script src="controllers/profile_controller.js"></script>
  <style>
    #profileRow{
      color: #006699;
      font-weight: bold;
    }
    #notAvailable{
      color: #bebebe;
    }
  </style>
  </head>
      <body ng-controller="profileController" ng-init="getInfo()" ng-click="successProfileMsg = null">
        <div class="jumbotron" ng-show="viewSwitch ">
          <div ng-show="!selectProfile" style="padding-left: 5%;">
            <h1>Welcome {{userfirstname}} {{userlastname}}!</h1>
            <h3>Please select a community</h3>
          </div>
          <div ng-show="selectProfile" style="padding-left: 5%;">
            <h1>Welcome {{userfirstname}} {{userlastname}}!</h1>
            <h3 ng-show="contents[selectProfile].marker_name">Here is your profile for <b>{{contents[selectProfile].marker_name}}</b>, at <b>{{contents[selectProfile].community_name}}</b>.</h3>
            <h3 ng-show="!contents[selectProfile].marker_name">No place of residence set at <b>{{contents[selectProfile].community_name}}</b>.</h3>
          </div>
        </div>

    <div class="container" ng-show="viewSwitch">
      <div id="communitySelecter" class="col-xs-12 col-md-8 col-md-offset-2 col-lg-8 col-lg-offset-2" ng-show="!selectProfile && viewSwitch">
        <h2>Please Select a Community:</h2>
        <div class="col-xs-12" style="text-align: center; font-weight: bold;" ng-show="!selectProfile">
        </div>
        <table class="col-xs-12 table table-hover">
          <tr>
            <td id="profileRow">Selected Community: </td>
            <td style="float: left;">
              <select id="selectMarker" ng-model="selectProfile" class="form-control" ng-change="changeInfo();">
                  <option ng-repeat="markers in contents track by $index" value="{{$index}}">{{markers.community_name}}</option>
              </select>
            </td>
          </tr>
        </table>
      </div>

        
      <div class="col-sm-4" ng-show="viewSwitch && selectProfile">
        <h2>Selected Community:</h2>
        <div class="col-xs-12" style="text-align: center; font-weight: bold;" >
          <span ng-show="!selectProfile">Please Select a Community:</span>
          <span ng-show="selectProfile"></span>
          
        </div>
        <select id="selectMarker" ng-model="selectProfile" class="form-control" ng-change="changeInfo();">
          <option ng-repeat="markers in contents track by $index" value="{{$index}}">{{markers.community_name}}</option>
        </select>
        <div style="text-align: right;">
          <br />
          <a class="col-xs-12 btn btn-lg btn-primary" ng-click="showEditProfile = true;" ng-show="!showEditProfile"> Edit Profile </a>
        </div>
      </div>

        <div class="col-sm-6 col-sm-offset-1" class="container" ng-show="viewSwitch">
          <div ng-show="!contents[selectProfile].phone_01 && selectProfile && !showEditProfile">
            <h3 style="text-align: center;">No profile set for {{contents[selectProfile].community_name}}.</h3>
            <a  class="col-sm-6 col-sm-offset-3 col-xs-8 col-xs-offset-2 btn btn-lg btn-primary" ng-click="showEditProfile = true;"> Add one here! </a> 
            <br /><br /><br /><br />
          </div>
          <div ng-show="contents[selectProfile].phone_01 && selectProfile && !showEditProfile">
            <div class="col-sm-12" class="container-fluid" ng-show="selectProfile && contents[selectProfile].phone_01">
            <div class="alert alert-success" ng-show="successProfileMsg">{{successProfileMsg}}</div>
            <h2>Profile: {{contents[selectProfile].first_name}} {{contents[selectProfile].last_name}}</h2>
              <table id="profileTable" class="table table-striped table-hover" ng-show="selectProfile && contents[selectProfile].phone_01">
                <tr>
                  <td id="profileRow">Selected Community: </td>
                  <td >{{contents[selectProfile].community_name}}</td>
                </tr>
                <tr>
                  <td id="profileRow">Location: </td>
                  <td  ng-show="contents[selectProfile].marker_name">{{contents[selectProfile].marker_name}}</td>
                  <td  ng-show="!contents[selectProfile].marker_name">No place of residence set</td>
                </tr>
                <tr>
                  <td id="profileRow">Primary Phone: </td>
                  <td  ng-show="(contents[selectProfile].phone_01)">{{contents[selectProfile].phone_01}}</td>
                </tr>
                <tr>
                  <td id="profileRow">Secondary Phone: </td>
                  <td  ng-show="(contents[selectProfile].phone_02)">{{contents[selectProfile].phone_02}}</td>
                  <td  id="notAvailable" ng-show="!(contents[selectProfile].phone_02)"> N/A</td>
                </tr>
                <tr>
                  <td id="profileRow">Primary Email: </td>
                  <td  ng-show="(contents[selectProfile].email_01)">{{contents[selectProfile].email_01}}</td>
                </tr>
                <tr>
                  <td id="profileRow">Secondary Email: </td>
                  <td  ng-show="(contents[selectProfile].email_02)">{{contents[selectProfile].email_02}}</td>
                  <td  id="notAvailable" ng-show="!(contents[selectProfile].email_02)"> N/A</td>
                </tr>
                <tr ng-show="(contents[selectProfile].pin_color)">
                  <td id="profileRow">Pin Color: </td>
                  <td> <img src="images/house_pin.png" id="house_pin" alt="" style="width:auto; height:auto;"> </td>
                </tr>
              </table>
            </div>
          </div>

          <div class="col-sm-12" class="container-fluid" ng-show="viewSwitch && showEditProfile">
            <form class="form-vertical" ng-submit="update(); getInfo();">
            <div class="alert alert-success" ng-show="successProfileMsg">{{successProfileMsg}}</div>
            <h2>Edit Profile: {{contents[selectProfile].first_name}} {{contents[selectProfile].last_name}}</h2>
            <h3></h3 >
            <table class="table table-striped table-hover" ng-show="selectProfile" >
              <tr>
                <td id="profileRow">Selected Community: </td>
                <td colspan="2">{{contents[selectProfile].community_name}}</td>
              </tr>
              <tr ng-show="selectProfile">
                <td id="profileRow">Location: </td>
                <td colspan="2" ng-show="contents[selectProfile].marker_name">{{contents[selectProfile].marker_name}}</td>
                <td colspan="2" ng-show="!contents[selectProfile].marker_name">No place of residence set</td>
              </tr>
              <tr>
                <td id="profileRow">Primary Phone: </td>
                <td colspan="2"><input type="tel" class="form-control" id="inputPhone01" placeholder="Primary Phone Number"  ng-model="phone_01" minlength="10" required></td>
              </tr>
              <tr>
                <td id="profileRow">Secondary Phone: </td>
                <td colspan="2"><input type="tel" class="form-control" id="inputPhone02" placeholder="Secondary Phone Number"  ng-model="phone_02" minlength="10"></td>
              </tr>
              <tr>
                <td id="profileRow">Primary Email: </td>
                <td colspan="2"><input type="email" class="form-control" id="inputEmail01" placeholder="Primary E-mail Address"  ng-model="email_01" required></td>
              </tr>
              <tr>
                <td id="profileRow">Secondary Email: </td>
                <td colspan="2"><input type="email" class="form-control" id="inputEmail02" placeholder="Secondary E-mail Address" ng-model="email_02"></td>
              </tr>
              <tr ng-show="contents[selectProfile].pin_color && (contents[selectProfile].allow_user_pin_colors == 1)">
                <td id="profileRow">Pin Color: <img src="images/house_pin.png" id="house_pinEdit" alt="" style="width:auto; height: auto"> </td>
                <td><input type="color" class="form-control" id="inputPinColor" ng-model="pin_color" ng-change="changePinColorEdit();"></td>
              </tr>
            </table>
            <div class="col-xs-12" ng-show="selectProfile">
              <button type="submit" class="col-xs-5 col-xs-offset-1 btn btn-lg btn-primary">Update Profile</button>
              <a ng-click="cancelEdit();" class="col-xs-5 col-xs-offset-1 btn btn-lg btn-danger">Cancel</a>
            </div>
            <br /> <br /><br />   
          </form>
        </div>

    </div>

         <div class="jumbotron" ng-show="!viewSwitch" style="padding-left: 5%;">
           <h1> Whoops, looks like you have no profiles. </h1>
           <h3> Why not search for a community to join or create one for your community? </h3>
         </div>
         <div class="container-fluid" ng-show="!viewSwitch">
             <a href="communitysearch.php" class="col-xs-10 col-xs-offset-1 col-sm-4 col-sm-offset-1 btn btn-lg btn-primary"> Join a Community </a>
             <a href="createcommunity.php" class="col-xs-10 col-xs-offset-1 col-sm-4 col-sm-offset-2 btn btn-lg btn-primary"> Create a Community </a>
         </div>

      </body>
</html>