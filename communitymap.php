<?php
    require_once( "template_class.php");       // css and headers
    $H = new template( "Community Map" );
    $H->show_template( );

    // Get the community id from the url for which community map to display
    if(isset($_GET["community"])) {
        $community = $_GET["community"];
    } else {
        header("location: myhome.php");
        exit;
    }

?>

<!-- Google API KEY for accessing a broader spectrum of Google APIs-->
<script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyCTUwndh9ZED3trNlGZqcCEjkAb5-bpoUw"></script>
<!-- File that holds the javascript to color a marker -->
<script type="text/javascript" src="js/colorpins.js"></script>
<!-- Add controller files -->
<script type="text/javascript" src="controllers/communitymap_controller.js"></script>
<style>
    body,
    html {
        height: 100%;
        width: 100%;
    }

    select {
        display: block;
        font-size: 2em;
        margin-top: 0.67em;
        margin-bottom: 0.67em;
        margin-left: 0;
        margin-right: 0;
        font-weight: bold;
    }

    #informationField::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
        border-radius: 10px;
        background-color: rgba(0, 0, 0, .5);
    }

    #informationField::-webkit-scrollbar {
        width: 12px;
        background-color: rgba(0, 0, 0, .5);
    }

    #informationField::-webkit-scrollbar-thumb {
        border-radius: 10px;
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, .3);
        background-color: #1995dc;
    }

    #infowindowPanel {
        width: 300px;
        height: 91%;
        padding: 10px;
        background-color: #FFFFFF;
        margin-left: -300px;
        position: fixed;
        top: 55px;
        /*background: rgba(0,0,0,.5);*/
    }

    #infowindowPanel::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
        border-radius: 10px;
        background-color: rgba(0, 0, 0, .5);
    }

    #infowindowPanel::-webkit-scrollbar {
        width: 12px;
        background-color: rgba(0, 0, 0, .5);
    }

    #infowindowPanel::-webkit-scrollbar-thumb {
        border-radius: 10px;
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, .3);
        background-color: #1995dc;
    }

    @media (max-width: @screen-xs-min) {
        .modal-xs {
            width: @modal-sm;
        }
    }

    .scrollbar {
        margin-left: 30px;
        float: left;
        height: 300px;
        width: 65px;
        background: #F5F5F5;
        overflow-y: scroll;
        margin-bottom: 25px;
    }
</style>

<body ng-controller="communitymapController">
    <div style="width:100%; height:91%;">
        <div class="col-md-4" style="background-color: #19A3FF; height:100%; overflow:auto;" id="informationField" >
            <div id='community_name' style="text-align: center; color: #FFFFFF; text-style: bold;
          text-shadow: -1px -1px 0 #000000, 1px -1px 0 #000000, -1px 1px 0 #000000, 1px 1px 0 #000000;
          font-size: 300%; font-weight: bold;">
            </div>

            <div id='street-view' class="col-sm-12" style="background-color: #EEEEEE;  min-height: 175px; height:20% width: 100%; text-align: center; font-size: 25px; font-weight: bold;">
                Select a Residence
            </div>
            <div> &nbsp </div>

            <div class="col-sm-12" style="background-color: #EEEEEE; " ng-show="!noProfiles" ng-hide="noProfiles">
                <b><div class="col-sm-12" Style="text-align: center; font-size: 125%;" id="head_resident_panel"> {{ marker_name }} </div></b>
                <div class="col-sm-12" Style="text-align: center; font-size: 100%; font-weight: bold;" id="address_panel"> {{ marker_location }} </div>
                <hr />
                </br>
                <div ng-repeat="x in profiles_array">
                  <h4> {{ x.residents_name }} </h4>
                  <table class="table table-hover" Style="font-size: 90%; background-color: #FFFFFF;">
                    <tr id='phone_01' ng-show="x.phone_01">
                      <td style="color: #006699; font-weight: bold;"> Primary Phone: </td>
                      <td>{{ x.phone_01 }} </td>
                    </tr>
                    <tr id='phone_02' ng-show="x.phone_02">
                      <td style="color: #006699; font-weight: bold;"> Secondary Phone: </td>
                      <td style="text-align: left;">{{ x.phone_02 }} </td>
                    </tr>
                    <tr id='email_01' ng-show="x.email_01">
                      <td style="color: #006699; font-weight: bold;"> Primary E-mail: </td>
                      <td>{{ x.email_01 }} </td>
                    </tr>
                    <tr id='email_02' ng-show="x.email_02">
                      <td style="color: #006699; font-weight: bold;"> Secondary E-mail: </td>
                      <td>{{ x.email_02 }} </td>
                    </tr>
                  </table>

                </div>
                  <br />
            </div>

            <div class="col-sm-12" style="background-color: #EEEEEE; font-size: 100%;" ng-show="noProfiles" ng-hide="!noProfiles">
              <div align="center">
                <h3> {{ profiles_array.no_profiles }} </h3>
              </div>
            </div>
            <div>&nbsp</div>
        </div>
        <!--Google Map Div-->
        <div class="col-md-8" id="googleMap" style="height:100%;"></div>
    </div>
    <!-- Modal -->
    <div id="floorplans_model" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <div align="center">
                        <h1> Floorplans </h1>
                        <select id="select_floorplans" class="form-control"></select>
                    </div>
                </div>
                <div class="modal-body" id="floorplans">
                    <div id='floorplan_div'></div>
                    <img id='floorplan' style='height: 100%; width: 100%;' />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal" id="modal-close">Close</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
