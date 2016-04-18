<html >
  <head>
    <?php

    require_once( "template_class.php");
    $H = new template( "CommunIT Account" );
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
      <body ng-controller="editProfileController" ng-init="getInfo();" >
       <div id="welcomejumbotron" class="jumbotron">
          <div style="padding-left: 5%;">
            <h2 id="welcomejumbotrontext">Welcome {{userfirstname}} {{userlastname}}!</h2>
            <h4 id="welcomejumbotrontext">Here is your account information.</h4>
          </div>
        </div>
         <div class="col-xs-12 col-sm-6 col-sm-offset-3" class="container-fluid" ng-init="getAccount();">
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
                  <td><input type="password" class="form-control" placeholder="New Password" ng-value="account.password" ng-model="password" ng-change="checkPassword()" minlength="8"></td>
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
      </body>
</html>
