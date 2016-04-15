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
  <script>
  /*
    $( window ).bind('load',function() {
      pin_color =  document.getElementById('pincolor').value;
      overalayColor(pin_color);
      document.getElementById('house_pin').src = fullimg;
    });

    $(document).ready(function() {
      //Change pin color on change of color select
      $( "#selectMarker" ).change(function() {
          pin_color =  document.getElementById('pincolor').value;
          overalayColor(pin_color);
          document.getElementById('house_pin').src = fullimg;
      });
    });
    */
  </script>
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
      <body ng-controller="profileCtrl">
        <div class="jumbotron" ng-show="viewSwitch ">
          <div ng-show="!selectProfile" style="padding-left: 5%;">
            <h1>Welcome {{userfirstname}}!</h1>
            <h3>Please select a community</h3>
          </div>
          <div ng-show="selectProfile" style="padding-left: 5%;">
            <h1>Welcome {{userfirstname}}!</h1>
            <p ng-show="contents[selectProfile].marker_name">Here is your profile for {{contents[selectProfile].marker_name}}, at {{contents[selectProfile].community_name}}.</p>
            <p ng-show="!contents[selectProfile].marker_name">No place of residence set, at {{contents[selectProfile].community_name}}.</p>
          </div>
        </div>
        <div class="jumbotron" ng-show="!viewSwitch" style="padding-left: 5%;">
            <h1> Whoops, looks like you have no profiles. </h1>
            <h3>Why not search for a community to join or create one for your community?</h3>
        </div>
         <!-- This is where are table is. It changes depending on which community they have selected -->
        <div class="col-sm-6" class="container-fluid" ng-show="viewSwitch">
          <div ng-show="!contents[selectProfile].phone_01 && selectProfile">
            <h3 style="text-align: center;">No profile set for {{contents[selectProfile].name}}, at {{contents[selectProfile].community_name}}.</h3>
            <a href="editprofile.php" class="col-sm-8 col-sm-offset-2 btn btn-lg btn-primary" > Add one here! </a>
          </div>
          <table id="profileTable" class="table table-striped table-hover" ng-show="selectProfile && contents[selectProfile].phone_01">

            <tr>
              <td id="profileRow">Primary Phone: </td>
              <td> </td>
              <td ng-show="(contents[selectProfile].phone_01)">{{contents[selectProfile].phone_01}}</td>
            </tr>
            <tr>
              <td id="profileRow">Secondary Phone: </td>
              <td> </td>
              <td ng-show="(contents[selectProfile].phone_02)">{{contents[selectProfile].phone_02}}</td>
              <td id="notAvailable" ng-show="!(contents[selectProfile].phone_02)"> N/A</td>
            </tr>
            <tr>
              <td id="profileRow">Primary Email: </td>
              <td> </td>
              <td ng-show="(contents[selectProfile].email_01)">{{contents[selectProfile].email_01}}</td>
            </tr>
            <tr>
              <td id="profileRow">Secondary Email: </td>
              <td> </td>
              <td ng-show="(contents[selectProfile].email_02)">{{contents[selectProfile].email_02}}</td>
              <td id="notAvailable" ng-show="!(contents[selectProfile].email_02)"> N/A</td>
            </tr>
            <tr ng-show="(contents[selectProfile].pin_color)">
              <td id="profileRow">Pin Color: </td>
              <td> <img src="images/house_pin.png" id="house_pin" alt="" style="width:auto; height;auto"> </td>
              <td style="background: {{contents[selectProfile].pin_color}};"></td>
              <!--
              <td> <input type="color" name="pincolor" id="pincolor" ng-value="contents[selectProfile].pin_color" style="width: 100%"></td>
              <td> <img src="images/house_pin.png" id="house_pin" alt="" style="width:auto; height;auto"> </td>
              -->
            </tr>
        </table>

      </div>
      <div class="col-sm-4" ng-show="viewSwitch">
        <!-- Here is the important part. Here we use the json object that we get from profileModel.php
      and use ng-repeat to create options for the select box. ng-repeat automaticly creates the
      $index variable which is equal to the current index that ng-repeat is at. -->
      <div class="col-xs-12" style="text-align: center; font-weight: bold;" >
        <span ng-show="!selectProfile">Please Select a Community.</span>
        <span ng-show="selectProfile">Selected Community.</span>
        <br /><br />
      </div>
        <select id="selectMarker" ng-model="selectProfile" class="form-control" ng-change="changePinColor();">
          <option ng-repeat="markers in contents" value={{$index}}>{{markers.community_name}}</option>
        </select>
        <div style="text-align: right;">
          <br />
          <a href="editprofile.php" class="col-xs-12 btn btn-lg btn-primary" > Edit Profiles </a>
        </div>
      </div>
      <div class="container-fluid" ng-show="!viewSwitch">
          <a href="communitysearch.php" class="col-xs-10 col-xs-offset-1 col-sm-4 col-sm-offset-1 btn btn-lg btn-primary"> Join a Community </a>
          <a href="createcommunity.php" class="col-xs-10 col-xs-offset-1 col-sm-4 col-sm-offset-2 btn btn-lg btn-primary"> Create a Community </a>
      </div>
      </body>
</htmL>
