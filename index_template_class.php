<?php

class template {
  function __construct( $title ) {
    $this->TITLE = $title;
  }

  function show_template( ) {

    print "<html>\n<head> <title> $this->TITLE </title>
    <link rel='icon' type='image/icon' href='images/favicon.ico'>
    <meta name='google' value='notranslate'> </head>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
  	<link rel='stylesheet' type='text/css' href='css/bootstrap.css'>
  	<link href='js/jquery-ui/jquery-ui.min.css' rel='stylesheet'>
  	<script src='js/angular.js'></script>
  	<script src='js/jquery.js'></script>
    <script src='js/jquery-ui/jquery-ui.js'></script>";

  }
}
    //Conatins the Site's Header Nav Bar
?>

      <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php">CommunIT</a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav">
          </ul>

      </div>
    </div>
  </nav>
  <div id="nav-bar-spacing" style="height: 75px">&nbsp</div>
