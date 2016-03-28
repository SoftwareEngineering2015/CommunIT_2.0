<?php
class template {
function __construct( $title ) {
$this->TITLE = $title;
}
function show_template( ) {
print "<!DOCTYPE html>\n<html ng-app='communitApp'>\n<head> <title> $this->TITLE </title>
<link rel='icon' type='image/icon' href='images/favicon.ico'>
<meta name='google' value='notranslate'> </head>
<meta name='viewport' content='width=device-width, initial-scale=1.0'>
<link rel='stylesheet' type='text/css' href='css/bootstrap.css'>
<link href='js/jquery-ui/jquery-ui.min.css' rel='stylesheet'>
<script src='js/angular.js'></script>
<script src='js/ng-file-upload.js'></script>
<script src='js/jquery.js'></script>
<script src='js/jquery-ui/jquery-ui.js'></script>
<script src='js/angular-ui-router.js'></script>
<script src='js/communit_App.js'></script>
<script src='js/colorpins.js'></script>
<script src='controllers/authentication_controller.js'></script>
<script src='js/bootstrap.js'></script>";
}
}
?>
<?php
//include('session.php');
include('db_class.php');
$error = '';
?>
<!-- Style the house icon to the left //e6f2ff; -->
<style type="text/css">
  .navbar-brand{
    margin-left: auto;
    margin-right: auto;
  }
</style>
<!--Conatins the Site's Header Nav Bar-->
<html ng-controller='authenticationController' ng-init='authenticater()' ng-show="authenticated">
<nav class="navbar navbar-default navbar-fixed-top" ng-show="authenticated">
<div class="container-fluid" ng-show="authenticated">
<div class="navbar-header" ng-show="authenticated">
<!-- image is needed so the community map icons change colors -->
<!--<img src="images/house_pin.png" alt="" class="navbar-brand" data="images/house_pin.png"></img>-->
<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
<span class="sr-only">Toggle navigation</span>
<span class="icon-bar"></span>
<span class="icon-bar"></span>
<span class="icon-bar"></span>
<span class="icon-bar"></span>
</button>
<a class="navbar-brand" href="myhome.php">CommunIT</a>

<!--<a class="navbar-brand" href="communitymap.php">CommunIT Map</a></img>-->
</div>
<!-- Collect the nav links, forms, and other content for toggling -->
<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
<ul class="nav navbar-nav">
<li class="dropdown">
<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> Communities <span class="caret"></span></a>
<ul class="dropdown-menu">
<li><a class="" href="myhome.php">My Communities</a></li>
<li><a href="createcommunity.php">Create Community</a></li>
<li><a href="communitysearch.php">Community Search</a></li>
<li><a href="joincommunityrequests.php">Community Requests</a></li>
</ul>
</li>
<li><a href="myprofile.php">My Profiles</a></li>
</ul>
<ul class="nav navbar-nav navbar-right">
<!--<li><a href="directory.php">Directory</a></li>-->
<li><a ng-click="logout();">Logout</a></li>
</ul>
</div><!-- /.navbar-collapse -->
</nav>
<div id="nav-bar-spacing" style="height: 55px">&nbsp</div>


<!--
<div>
&nbsp
<br/>
<br/>
<br/>
</div>
-->
