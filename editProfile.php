<html >
  <head>
    <?php

    require_once( "template_class.php");
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
          <form class="form-vertical" ng-submit="update(); getInfo();" ng-click="successProfileMsg = null">
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
            <tr ng-show="selectProfile">
              <td id="profileRow">Location: </td>
              <td ng-show="contents[selectProfile].marker_name"><b>{{contents[selectProfile].marker_name}}</b></td>
              <td  ng-show="!contents[selectProfile].marker_name"><b>No place of residence set</b></td>
            </tr>

          </table>
          <br /><br /><br />
          <div ng-show="!selectProfile">
            <br />
            <hr />
          </div>

          <table class="table" ng-show="successProfileMsg">
            <tr style="text-align: center; font-weight: bold;" ng-show="successProfileMsg">
              <td colspan="2">
                <div class="alert alert-success" ng-show="successProfileMsg">{{successProfileMsg}}</div>
              </td>
            </tr>
          </table>
          <table class="table table-striped table-hover" ng-show="selectProfile" >
            <tr>
              <td id="profileRow">Primary Phone: </td>
              <td> </td>
              <td><input type="tel" class="form-control" id="inputPhone01" placeholder="Primary Phone Number" ng-value="{{contents[selectProfile].phone_01}}" ng-model="phone_01" minlength="10" required></td>
            </tr>
            <tr>
              <td id="profileRow">Secondary Phone: </td>
              <td> </td>
              <td><input type="tel" class="form-control" id="inputPhone02" placeholder="Secondary Phone Number" ng-value="{{contents[selectProfile].phone_02}}" ng-model="phone_02" minlength="10"></td>
            </tr>
            <tr>
              <td id="profileRow">Primary Email: </td>
              <td> </td>
              <td><input type="email" class="form-control" id="inputEmail01" placeholder="Primary E-mail Address" ng-value="{{contents[selectProfile].email_01}}" ng-model="email_01" required></td>
            </tr>
            <tr>
              <td id="profileRow">Secondary Email: </td>
              <td> </td>
              <td><input type="email" class="form-control" id="inputEmail02" placeholder="Secondary E-mail Address" ng-value="{{contents[selectProfile].email_02}}" ng-model="email_02"></td>
            </tr>
            <tr  ng-show="contents[selectProfile].pin_color && (contents[selectProfile].allow_user_pin_colors == 1)">
              <td id="profileRow">Pin Color: </td>
              <td> <img src="images/house_pin.png" id="house_pin" alt="" style="width:auto; height;auto"> </td>
              <td><input type="color" class="form-control" id="inputPinColor" ng-value="contents[selectProfile].pin_color" ng-model="pin_color" ng-change="changePinColor();"></td>
            </tr>
          </table>
          <div class="col-xs-12" ng-show="selectProfile">
            <button  class="col-xs-5 col-xs-offset-1 btn btn-lg btn-primary">Update Profile</button>
            <button ng-click="changeInfo()" class="col-xs-5 col-xs-offset-1 btn btn-lg btn-danger">Cancel</button>
          </div>
          <br /> <br /><br />

        </form>
         </div>
         <!-- Here we have the form for editting password and primary email -->
         <div class="col-sm-6" class="container-fluid" ng-show="viewSwitch" ng-init="getAccount(); getInfo();">
          <form class="form-vertical" ng-submit="updateAccount();">
          <h2>Edit Account</h2>
          <div style="text-align: center; font-weight: bold;">
            <div class="alert alert-success" ng-show="successAccountMsg">{{successAccountMsg}}</div>
            <div class="alert alert-danger" ng-show="errorAccountMsg">{{errorAccountMsg}}</div>
            <div><span class="text-danger" ng-show="errorAccountPasswordMsg">{{errorAccountPasswordMsg}}</span></div>
            <div><span class="text-danger" ng-show="errorAccountEmailMsg">{{errorAccountEmailMsg}}</span></div>
          </div>
          <table class="table table-striped table-hover ">
              <tr>
                  <td id="profileRow"> Change Password: </td>
                  <td><input type="password" class="form-control" placeholder="Password" ng-value="account.password" ng-model="password" ng-change="checkPassword()" minlength="8"></td>
              </tr>
              <tr>
                  <td id="profileRow"> Confirm Password: </td>
                  <td><input type="password" class="form-control" placeholder="Confirm Password" ng-model="passConfirm" ng-change="checkPassword()" ></td>
              </tr>
                  <td id="profileRow"> Change Account Email: </td>
                  <td><input type="email"  class="form-control" placeholder="Primary E-mail" ng-value="account.email" ng-model="primaryEmail" ng-change="checkPrimaryEmail()" minlength="5"></td>
              </tr>
              <tr>
                  <td id="profileRow"> Confirm Account Email: </td>
                  <td><input type="email" class="form-control" placeholder="Confirm Email" ng-model="emailConfirm" ng-change="checkPrimaryEmail()"></td>
              </tr>
          </table>
            <input type="submit" name="submit" value="Update Account" class="col-xs-8 col-xs-offset-2 btn btn-lg btn-primary"> </input>
          </form>
          </div>
         <!-- These only show if they don't have a profile -->
         <div class="jumbotron" ng-show="!viewSwitch" style="padding-left: 5%;">
           <h1> Whoops, looks like you have no profiles to edit. </h1>
           <h3> Why not search for a community to join or create one for your community? </h3>
         </div>
         <div class="container-fluid" ng-show="!viewSwitch">
             <a href="communitysearch.php" class="col-xs-10 col-xs-offset-1 col-sm-4 col-sm-offset-1 btn btn-lg btn-primary"> Join a Community </a>
             <a href="createcommunity.php" class="col-xs-10 col-xs-offset-1 col-sm-4 col-sm-offset-2 btn btn-lg btn-primary"> Create a Community </a>
         </div>
      </body>
</htmL>
