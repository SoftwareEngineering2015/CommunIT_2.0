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
<!-- File that holds the date code to convert datetime to day of the week -->
<script type="text/javascript" src="js/date.js"></script>
<!-- File that holds the javascript to color a marker -->
<script type="text/javascript" src="js/colorpins.js"></script>
<!-- Dropdown list file -->
<script src="js/dropdown.js"></script>
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
    #profileRow{
      color: #006699;
      fojjnt-weight: bold;
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

    #floorplanInformationField::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
        border-radius: 10px;
        background-color: rgba(0, 0, 0, .5);
    }

    #floorplanInformationField::-webkit-scrollbar {
        width: 12px;
        background-color: rgba(0, 0, 0, .5);
    }

    #floorplanInformationField::-webkit-scrollbar-thumb {
        border-radius: 10px;
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, .3);
        background-color: #1995dc;
    }

    #forecastDiv::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
        border-radius: 10px;
        background-color: rgba(0, 0, 0, .5);
    }

    #forecastDiv::-webkit-scrollbar {
        width: 12px;
        background-color: rgba(0, 0, 0, .5);
    }

    #forecastDiv::-webkit-scrollbar-thumb {
        border-radius: 10px;
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, .3);
        background-color: #1995dc;
    }
    #floorplanModalDiv {
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .ui-widget-content::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
        border-radius: 10px;
        background-color: rgba(0, 0, 0, .5);
    }

    .ui-widget-content::-webkit-scrollbar {
        width: 12px;
        background-color: rgba(0, 0, 0, .5);
    }

    .ui-widget-content::-webkit-scrollbar-thumb {
        border-radius: 10px;
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, .3);
        background-color: #1995dc;
    }

    @media (max-width: @screen-xs-min) {
        .modal-xs {
            width: @modal-sm;
        }
    }

     #floorplan-modal-dialog{
        position: relative;
        display: table;
        overflow-y: auto;
        overflow-x: auto;
        width: 90%;
        height: 90%;
    }

    #floorplan-modal-content {
          height: 99%;
        }

    #table-borderless tbody tr td, #table-borderless tbody tr th, #table-borderless thead tr th {
        border: none;
    }

</style>

<body ng-controller="communitymapController">
    <div style="width:100%; height:91%;">
        <div class="col-sm-4" style="background-color: #19A3FF; height:100%; overflow:auto;" id="informationField" >
            <div id='community_name' style="text-align: center; color: #FFFFFF; text-style: bold;
          text-shadow: -1px -1px 0 #000000, 1px -1px 0 #000000, -1px 1px 0 #000000, 1px 1px 0 #000000;
          font-size: 300%; font-weight: bold;">
            </div>

            <div id='street-view' class="col-sm-12" style="background-color: #EEEEEE;  min-height: 175px; height:20% width: 100%; text-align: center; font-size: 25px; font-weight: bold;">
                Select A Residence
            </div>
            <div> &nbsp </div>

            <div class="col-sm-12" style="background-color: #EEEEEE; " ng-show="!noInformation" ng-hide="noInformation">
                <b><div class="col-sm-12" Style="text-align: center; font-size: 125%;" id="head_resident_panel"> {{ marker_name }} </div></b>
                <div class="col-sm-12" Style="text-align: center; font-size: 100%; font-weight: bold;" id="address_panel"> {{ marker_location }} </div>
                <div class="col-sm-12" Style="text-align: center; font-size: 100%; font-weight: bold;" id="address_panel"> </div>
                <div class="col-sm-12" Style="text-align: center; font-size: 100%;" id="address_panel" ng-show="hasMarkerMisc">
                    <br />
                    <button class="btn btn-info btn-xs" ng-click="showMiscInfo();" ng-show="showMarkerInfoButton"> Show Info </button>
                    <button class="btn btn-info btn-xs" ng-click="hideMiscInfo();" ng-show="misc_panel"> Hide Info </button>
                    <br />
                    <div ng-show="misc_panel"> <textarea style="width: 100%;" rows="4">{{ marker_misc }}</textarea> </div>
                    <br />
                </div>
                <div ng-show="!noProfiles">
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
                            <tr id='miscinfo' ng-show="x.miscinfo">
                                <td style="color: #006699; font-weight: bold;"> Misc Info:  </td>
                                <td> <textarea style="width: 100%;" rows="4">{{ x.miscinfo }} </textarea></td>
                            </tr>
                        </table>
                    </div>
                    <br />
                </div>
                <div ng-show="hasFloorplans">
                    <h4> Floorplans </h4>
                    <table class="table table-hover" Style="font-size: 90%; background-color: #FFFFFF;">
                        <tr ng-repeat="x in profiles_array" >
                            <td style="color: #006699; font-weight: bold;"> {{ x.floor}} </td>
                            <td > <button type='button' class='btn btn-success btn-sm' ng-click="load_view_floorplan(x.floorplan_id)" style='width: 100%;'>View Floorplan</button> </td>
                        </tr>
                    </table>
                    <br />
                </div>
            </div>

            <div class="col-sm-12" style="background-color: #EEEEEE; font-size: 100%;" ng-show="noInformation" ng-hide="!noInformation">
                <b><div class="col-sm-12" Style="text-align: center; font-size: 125%;" id="head_resident_panel"> {{ marker_name }} </div></b>
                <div class="col-sm-12" Style="text-align: center; font-size: 100%; font-weight: bold;" id="address_panel"> {{ marker_location }} </div>
                <div class="col-sm-12" Style="text-align: center; font-size: 100%; font-weight: bold;" id="address_panel"> </div>
                <div class="col-sm-12" Style="text-align: center; font-size: 100%;" ng-show="hasMarkerMisc">
                    <br />
                    <button class="btn btn-info btn-xs" ng-click="showMiscInfo();" ng-show="showMarkerInfoButton"> Show Info </button>
                    <button class="btn btn-info btn-xs" ng-click="hideMiscInfo();" ng-show="misc_panel"> Hide Info </button>
                    <br />
                    <div ng-show="misc_panel"> <textarea style="width: 100%;" rows="4">{{ marker_misc }}</textarea> </div>
                    <br />
                </div>
                <div align="center">
                    <h3> {{ profiles_array.no_profiles }} </h3>
                </div>
            </div>
            <div>&nbsp</div>
        </div>
        <!--Google Map Div-->
        <div class="col-sm-8" id="googleMap" style="height:100%;"></div>
    </div>

    <!-- Modal -->
    <div id="viewFloorplanModal" class="modal fade" role="dialog"  style="overflow-y:auto; ">
        <div class="modal-dialog" id="floorplan-modal-dialog" style="overflow-y:auto;">
            <!-- Modal content -->
            <div class="modal-content" id="floorplan-modal-content" style="overflow-y:auto; overflow-x: hidden; ">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 id="floorplanName"> </h3>
                </div>
                <div class="modal-body" style="height: 85%; overflow-y:auto; overflow-x: hidden;">
                    <div style="overflow-y:auto; overflow-x: auto; background-color: #19A3FF;" class="col-sm-4 col-lg-3 container-fluid">
                        <div id="floorplanInformationField" style="height: 90%; overflow-y:auto; overflow-x: auto; background-color: #FFFFFF; padding: 5px; margin-top: 10px; margin-bottom: 10px;">
                            <h3 align="center"> Select a residence </h3>
                        </div>
                    </div>
                    <div id='floorplanModalDiv' class="col-sm-8 col-lg-9 container-fluid">
                        <img id='floorplanImage' style='width: 100%; height: auto;'>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="weatherModal" class="modal fade" role="dialog">
        <div class="modal-dialog" style="min-width: 80%;">
            <!-- Modal content -->
            <div class="modal-content" style="min-width: 80%;">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 id="markerNameWeather"></h3>
                </div>
                <div class="modal-body" id='weatherInformation'>
                    <div id="forecastDiv" style="overflow: auto;">
                        <h4> Current Weather <span id="currentWeatherPic"> </span> </h4>
                        <table class="table table-responsive" id="table-borderless">
                            <tr>
                                <th> Description </th>
                                <th> Temp </th>
                                <th> Humidity </th>
                                <th> Wind </th>
                            </tr>
                            <tr>
                                <td id="currentWeatherDescription" style="text-transform: capitalize;"> </td>
                                <td id="currentWeatherTemp"> </td>
                                <td id="currentWeatherHumidity"> </td>
                                <td id="currentWeatherWind"> </td>
                            </tr>
                        </table>
                        <h4> 5-Day Forecast </h4>
                        <table class="table table-responsive" id="table-borderless">
                            <tr id="dayOfWeekForWeather" style="font-weight: bold;"> </tr>
                            <tr id="weatherForForecast"> </tr>
                            <tr id="descriptionForForecast" style="text-transform: capitalize;"> </tr>
                            <tr id="tempForForecast" style="font-weight: bold;"> </tr>
                        </table>
                    </div>
                    <div id="weatherError"> </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
