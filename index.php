
<?php
//include('login.php'); // Includes Login Script

  require_once( "index_template_class.php");       // css and headers
  $H = new template( "CommunIT" );
  $H->show_template( );

?>

<!DOCTYPE html>
<html>
<head>

<style>

body {
background: url('images/background.jpg') no-repeat fixed;
-webkit-background-size: cover;
-moz-background-size: cover;
-o-background-size: cover;
background-size: cover;
}
#loginview .panel {
    background: rgba(255, 255, 255, 0.8);
}
.centered-form {
      margin-top: 60px;
  }
  .centered-form .panel {
      background: rgba(255, 255, 255, 0.8);
  }
  label.label-floatlabel {
      font-weight: bold;
      color: #46b8da;
      font-size: 11px;
  }

</style>

<script src="controllers/index_controller.js"></script>
<!--
<script src="controllers/login_controller.js"></script>
<script src="controllers/createaccount_controller.js"></script>
-->

</head>

<body ng-app="indexApp" ng-controller="loginController" ng-init="checkLogin()" >
<div style="text-align: center;" >
  <div class="col-xs-12 " style="text-align: center; color: #FFFFFF; text-style: bold;
  text-shadow:
  	-2px -2px 0 #000000,
  	 2px -2px 0 #000000,
  	-2px  2px 0 #000000,
     2px  2px 0 #000000;
   ">

  	<div style="font-size: 1000%;">  <img src="images/logo_02.png" alt="CommunIT" style="width:125px; height:125px;"> CommunIT </img> </div>
  	<div style="font-size: 350%;"> Community Manager </div>
  </div>


<div class="container" ng-show="!formSwitch">
	<div class="col-xs-6 col-xs-offset-3">
    <div id="loginview" class="row centered-form">

      <div class="alert alert-success" ng-show="successMsg">
        <span class="text-success" ng-show="successMsg" style="font-weight: bold;">{{successMsg}}</span>
      </div>

      <div class="alert alert-danger" ng-show="tokenMsg">
        <span class="text-danger" ng-show="tokenMsg" style="font-weight: bold;">{{tokenMsg}}</span>
      </div>

		<div  class="panel panel-default">
			<div class="panel-heading">
				<b class="" style="color: #000000" id="community_name">
					<!--Inline PHP Variable of Community Name-->
					CommunIT Login

				</b>
			</div>

		<div  class="panel-body">
			<form  class="form-horizontal" ng-submit="postLogin()">
				<div   class="form-group">
					<b for="loginID" class="col-sm-3 control-label" style="color: #000000">Username</b>
					<div class="col-sm-9">
						<input id="loginID" name="username" type="text" class="form-control" placeholder="Username" required="" style="" required autofocus ng-model="inputDataLogin.username"> <br/>
					</div>
				</div>

			<div class="form-group">
				<b for="password" class="col-sm-3 control-label" style="color: #000000">Password</b>
				<div class="col-sm-9">
					<input  id="password" name="password" type="password" class="form-control" placeholder="Password" required ng-model="inputDataLogin.password"> <br/>
					<span class="text-danger" ng-show="errorMsg">{{errorMsg}}</span>
				</div>
			</div>


			<div class="form-group last">
				<div class="col-sm-12" style="text-align: center;">
					<button name="submit" type="submit" value=" Login " class="btn btn-primary" style=" width: 40%;">Sign In</button>
          <a class="btn btn-primary" ng-click="formSwitch = !formSwitch" ng-show="!formSwitch" style=" width: 50%;">Create an Account</a>
				</div>
			</div>

			</form>
		</div>
		</div>
	</div>
</div>
</div>

<!--<div class="container" ng-show="formSwitch" ng-controller="createaccountController">-->
<div class="container" ng-show="formSwitch">
        <form ng-submit="postCreateAccount()">
        <div class="row centered-form">
            <div class="col-xs-6 col-xs-offset-3">
                <div class="panel panel-default">
                    <div class="panel-heading">
                      <b class="" style="color: #000000;">
                        Create a CommunIT Account
                      </b>
                    </div>
                    <div class="panel-body">
                            <div class="row">
                                <div class="col-xs-5">
                                    <div class="form-group">
                                        <b> Username: </b>
                                    </div>
                                </div>
                                <div class="col-xs-7">
                                    <div class="form-group">
                                        <input type="text" name="inputUsername" id="inputUsername" class="form-control input-sm floatlabel" placeholder="Username" required autofocus ng-model="inputData.username" maxlength="50" ng-change="checkUsername();">
                                        <span class="text-danger" ng-show="errorMsgUsername">{{errorMsgUsername}}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-5">
                                    <div class="form-group">
                                        <b> E-mail: </b>
                                    </div>
                                </div>
                                <div class="col-xs-7">
                                    <div class="form-group">
                                        <input type="email" name="inputEmail" id="inputEmail" class="form-control input-sm floatlabel" placeholder="E-mail Address" required ng-model="inputData.email" maxlength="50">
                                        <span class="text-danger" ng-show="errorMsgEmail">{{errorMsgEmail}}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-5">
                                    <div class="form-group">
                                        <b> First Name: </b>
                                    </div>
                                </div>
                                <div class="col-xs-7">
                                    <div class="form-group">
                                        <input type="text" name="inputFirstName" id="inputFirstName" class="form-control input-sm floatlabel" placeholder="First Name" required ng-model="inputData.firstName" maxlength="50">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-5">
                                    <div class="form-group">
                                        <b> Middle Initial: </b>
                                    </div>
                                </div>
                                <div class="col-xs-3 col-sm-3 col-md-3">
                                    <div class="form-group">
                                        <input type="text" name="inputMiddleInitial" id="inputMiddleInitial" class="form-control input-sm floatlabel" placeholder="MI" pattern="[A-Za-z]{1}" title="Enter the first letter of your middle-name." maxlength="1" style="text-transform:uppercase" ng-model="inputData.middleInitial">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-5">
                                    <div class="form-group">
                                        <b> Last Name: </b>
                                    </div>
                                </div>
                                <div class="col-xs-7">
                                    <div class="form-group">
                                        <input type="text" name="inputLastName" id="inputLastName" class="form-control input-sm floatlabel" placeholder="Last Name" required ng-model="inputData.lastName">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-5">
                                    <div class="form-group">
                                        <b> Gender: </b>
                                    </div>
                                </div>
                                <div class="col-xs-7">
                                    <div class="form-group">
                                        <select class="form-control input-sm floatlabel" name="inputGender" id="inputGender" ng-model="inputData.gender">
                                            <option selected value="" >No Answer</option>
                                            <option value="Male">Male </option>
                                            <option value="Female">Female </option>
                                            <option value="Other">Other </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-5">
                                    <div class="form-group">
                                        <b> Birth Date: </b>
                                    </div>
                                </div>
                                <div class="col-xs-7">
                                    <div class="form-group has-feedback">
                                        <input type="text" name="inputBirthDate" id="inputBirthDate" class="form-control input-sm floatlabel" required ng-model="inputData.BirthDate" readonly>
                                        <i class="glyphicon glyphicon-calendar form-control-feedback"></i>
                                        <span class="text-danger" ng-show="errorMsgBirthDate">{{errorMsgBirthDate}}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-5">
                                    <div class="form-group">
                                        <b> Password: </b>
                                    </div>
                                </div>
                                <div class="col-xs-7">
                                    <div class="form-group">
                                        <input type="password" name="inputPassword" id="inputPassword" class="form-control input-sm floatlabel" required  ng-change="checkPassword()" ng-model="inputData.password" minlength="8" maxlength="30">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-5">
                                    <div class="form-group">
                                        <b> Confirm Password: </b>
                                    </div>
                                </div>
                                <div class="col-xs-7">
                                    <div class="form-group">
                                        <input type="password" name="inputPasswordConfirm" id="inputPasswordConfirm" class="form-control input-sm floatlabel" required ng-change="checkPassword()" ng-model="inputData.passwordConfirm" minlength="8" maxlength="30">
                                        <span class="text-danger" ng-show="errorMsgPassword">{{errorMsgPassword}}</span>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-block"> Create Account </button>

                            <span class="text-danger" ng-show="errorMsgForm">{{errorMsgForm}}</span>
                          <br />
                          <a ng-click="formSwitch = !formSwitch" ng-show="formSwitch" >Or, go back to Login</a>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


<div style="text-align: center;" >
    <b class="" style="color: #000000">
          <a href="disclaimer.php" class="btn btn-primary btn-xs" >View Disclaimer</a>
    </b>
</div>
</div>

</body>
</html>
