<html >
  <head>
    <?php

    require_once( "template_class.php");       // css and headers
    $H = new template( "CommunIT Profile" );
    $H->show_template( );

    ?>
	<script src="controllers/editprofile_controller.js"></script>
  <style>
    #profileRow{
      color: #006699;
      font-weight: bold;
    }
  </style>
  </head>
      <body ng-controller="editProfileController" ng-init="getInfo()" >
          <!-- Here is the form for edit profiles -->
          <div class="col-sm-6" class="container-fluid" ng-show="viewSwitch">
          <form class="form-vertical" ng-submit="update(); getInfo();">
          <h2>Edit Profile</h2>
          <div class="col-xs-12" style="text-align: center; font-weight: bold;" ng-show="!selectProfile">
            Please Select a Community.
            <br /><br />
          </div>
          <table class="col-xs-12 table table-hover">
            <tr>
              <td id="profileRow">Selected Community: &nbsp</td>
              <td style="float: left;">
                <select id="selectMarker" ng-model="selectProfile" class="form-control" ng-change="changeInfo()">
                    <option ng-repeat= "markers in contents" value={{$index}}>{{markers.community_name}}</option>
                </select>
              </td>
            </tr>
            <tr ng-show="contents[selectProfile].marker_name">
              <td id="profileRow">Location: </td>
              <td><b>{{contents[selectProfile].marker_name}}</b></td>
            </tr>
          </table>
          <br /><br /><br />
          <div ng-show="!selectProfile">
            <br />
            <hr />
          </div>

          <table class="table table-striped table-hover" ng-show="selectProfile">
            <tr>
              <td id="profileRow">Primary Phone: </td>
              <td><input type="tel" class="form-control" id="inputPhone01" placeholder="Primary Phone Number" value="{{contents[selectProfile].phone_01}}" ng-model="phone_01" minlength="10" required></td>
            </tr>
            <tr>
              <td id="profileRow">Secondary Phone: </td>
              <td><input type="tel" class="form-control" id="inputPhone02" placeholder="Secondary Phone Number" value="{{contents[selectProfile].phone_02}}" ng-model="phone_02" minlength="10"></td>
            </tr>
            <tr>
              <td id="profileRow">Primary Email: </td>
              <td><input type="text" class="form-control" id="inputEmail01" placeholder="Primary E-mail Address" value="{{contents[selectProfile].email_01}}" ng-model="email_01" required></td>
            </tr>
            <tr>
              <td id="profileRow">Secondary Email: </td>
              <td><input type="text" class="form-control" id="inputEmail02" placeholder="Secondary E-mail Address" value="{{contents[selectProfile].email_02}}" ng-model="email_02"></td>
            </tr>
            <tr>
              <td id="profileRow">Pin Color: </td>
              <td><input type="color" class="form-control" id="inputPinColor" placeholder="#{{contents[selectProfile].pin_color}}" ng-value="contents[selectProfile].pin_color" ng-model="pin_color"></td>
            </tr>
          </table>
          <div class="col-xs-12" ng-show="selectProfile">
            <button  class="col-xs-5 col-xs-offset-1 btn btn-lg btn-primary">Submit</button>
            <button ng-click="changeInfo()" class="col-xs-5 col-xs-offset-1 btn btn-lg btn-danger">Cancel</button>
          </div>
          <br /> <br /><br />

        </form>
         </div>
         <!-- Here we have the form for editting password and primary email -->
         <div class="col-sm-6" class="container-fluid" ng-show="viewSwitch">
          <form class="form-vertical">
          <h2>Edit Account</h2>
          <table class="table table-striped table-hover ">
              <tr>
                  <td id="profileRow"> Change Password: </td>
                  <td><input type="password" class="form-control" placeholder="{{contents[selectProfile].password}}" ng-model="password" minlength="8"></td>
              </tr>
              <tr>
                  <td id="profileRow"> Confirm Password: </td>
                  <td><input type="password" class="form-control" placeholder="Confirm Password" ng-model="passConfirm" minlength="8"></td>
              </tr>
                  <td id="profileRow"> Change Primary Email: </td>
                  <td><input type="text"  class="form-control" placeholder="{{contents[selectProfile].email}}" ng-model="primaryEmail"></td>
              </tr>
              <tr>
                  <td id="profileRow"> Confirm Primary Email: </td>
                  <td><input type="text" class="form-control" placeholder="Confirm Email" ng-model="emailConfirm"></td>
              </tr>
          </table>
            <a type="submit" name="submit" class="col-xs-8 col-xs-offset-2 btn btn-lg btn-primary"> Submit Account Info </a>
          </form>
          </div>
         <!-- These only show if they don't have a profile -->
         <div class="jumbotron" ng-show="!viewSwitch">
           <h1> Whoops, looks like you have no profiles to edit. </h1>
           <h3> Why not search for a community to join? Or create one for your community! </h3>
         </div>
         <div class="container-fluid" ng-show="!viewSwitch">
           <a href="communitysearch.php" class="btn btn-lg btn-block btn-primary"> Join a Community </a>
           <a href="createcommunity.php" class="btn btn-lg btn-block btn-primary"> Create a Community </a>
         </div>
      </body>
</htmL>
